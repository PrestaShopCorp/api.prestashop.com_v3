<?php

namespace App\Http\Controllers\API;

use App\Events\ActionShouldBeTracked;
use App\Http\Controllers\Controller;
use App\Services\PrestashopVersionCheckerService;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrestashopVersionController extends Controller
{
    private PrestashopVersionCheckerService $prestashopVersionCheckerService;

    public function __construct(PrestashopVersionCheckerService $prestashopVersionCheckerService)
    {
        $this->prestashopVersionCheckerService = $prestashopVersionCheckerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function checkVersion(Request $request): ResponseFactory|Response
    {
        //Patch in order to handle original client IP after migration of SI behind CloudFlare
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $address = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }

        //Try to directly collect IP localisation from CloudFlare
        $cloudflareCountryIP = '';
        if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
            $cloudflareCountryIP = $_SERVER['HTTP_CF_IPCOUNTRY'];
        }


        $parameters = [
            'address' => $address,
            'cloudflare_country_ip' => $cloudflareCountryIP,
            'referer' => $request->headers->get('referer', ''),
            'activity' => $request->input('activity', 0),
            'version' => $request->input('v', ''),
            'iso_code' => $request->input('lang', 'en'),
            'hosted_mode' => $request->input('hosted_mode', 0),

        ];

        try {
            $buffer = $this->prestashopVersionCheckerService->checkPrestaShopVersion($parameters);
            event(new ActionShouldBeTracked($parameters));
            return response($buffer, 200);
        } catch (Exception $exception) {
            return response('Unprocessable entity', 422);
        }
    }
}
