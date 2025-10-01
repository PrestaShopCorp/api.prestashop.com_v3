<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\InstallationSupportService;
use App\Services\RssService;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RssController extends Controller
{
    private RssService $rssService;

    public function __construct(RssService $rssService)
    {
        $this->rssService = $rssService;
    }

    /**
     * Get Rss news
     */
    public function getRssNews(Request $request): ResponseFactory|Response
    {
        //Patch in order to handle original client IP after migration of SI behind CloudFlare
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $address = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }

        $parameters = [
            'address' => $address,
            'iso_lang' => $request->input('lang', 'en'),
            'step' => $request->input('step', false),
            'errors' => $request->input('errors', false),
        ];

        try {
            $buffer = $this->rssService->getNews($parameters);
            return response($buffer, 200);
        } catch (Exception $exception) {
            return response('Unprocessable entity', 422);
        }
    }
}
