<?php

namespace App\Http\Controllers\API;

use App\Events\CheckPrestaShopVersionUpdatesQuery;
use App\Events\GetInstallationIFrameQuery;
use App\Events\SendTrackingToSandrineCommand;
use App\Http\Controllers\Controller;
use App\Services\GeoIPService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InstallationController extends Controller
{

    /**
     * Get installation help iframe
     */
    public function getInstallationHelp(Request $request): ResponseFactory|Response
    {
        $parameters = [
            'address' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match('/^10\.2/', $_SERVER['REMOTE_ADDR'])
                ? $_SERVER['HTTP_X_FORWARDED_FOR']
                : $_SERVER['REMOTE_ADDR'],
            'iso_lang' => $request->input('lang', 'en'),
            'step' => $request->input('step', false),
            'errors' => $request->input('errors', false),
        ];

        event($query = new GetInstallationIFrameQuery($parameters));

        return response($query->getResult(), 200);
    }
}
