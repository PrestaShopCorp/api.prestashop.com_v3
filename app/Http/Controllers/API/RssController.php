<?php

namespace App\Http\Controllers\API;

use App\Events\ActionShouldBeTracked;
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
        if (isset($_SERVER['CF-Connecting-IP'])) {
            $address = $_SERVER['CF-Connecting-IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }

        $parameters = [
            'address' => $address,
            'version' => $request->input('v', ''),
            'iso_lang' => strtolower($request->input('lang', 'en')),
            'referer' => $request->headers->get('referer', ''),
            'activity' => $request->input('activity', 0),
        ];
        if (!in_array($parameters['iso_lang'], ['es', 'fr', 'it', 'de'])) {
            $parameters['iso_lang'] = 'en';
        }

        try {
            $buffer = $this->rssService->getNews($parameters);
            event(new ActionShouldBeTracked($parameters));
            return response($buffer, 200);
        } catch (Exception $exception) {
            p($exception->getMessage());
            return response('Unprocessable entity', 422);
        }
    }
}
