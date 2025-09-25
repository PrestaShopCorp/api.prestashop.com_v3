<?php

namespace App\Http\Controllers\API;

use App\Events\CheckPrestaShopVersionUpdatesQuery;
use App\Events\SendTrackingToSandrineCommand;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VersionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function checkVersion(Request $request): ResponseFactory|Response
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
        event(new SendTrackingToSandrineCommand($parameters));

        return response($query->getResult(), 200);
    }
}
