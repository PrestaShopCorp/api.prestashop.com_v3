<?php

namespace App\Http\Controllers\API;

use App\Events\CheckPrestaShopVersionUpdatesQuery;
use App\Events\SendTrackingToSandrineCommand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VersionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function checkVersion(Request $request): void
    {
        $parameters = [
            'address' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match('/^10\.2/', $_SERVER['REMOTE_ADDR'])
                ? $_SERVER['HTTP_X_FORWARDED_FOR']
                : $_SERVER['REMOTE_ADDR'],
            'referer' => $request->headers->get('referer', ''),
            'activity' => $request->input('activity', 0),
            'version' => $request->input('v', ''),
            'iso_code' => $request->input('lang', 'en'),
            'hosted_mode' => $request->input('hosted_mode', 0),
        ];

        event($query = new CheckPrestaShopVersionUpdatesQuery($parameters));
        echo $query->getResult();
        event(new SendTrackingToSandrineCommand($parameters));
    }
}
