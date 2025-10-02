<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\TipsOfTheDayService;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TipsOfTheDayController extends Controller
{
    private TipsOfTheDayService $service;

    public function __construct(TipsOfTheDayService $service)
    {
        $this->service = $service;
    }

    /**
     * Get Rss news
     */
    public function getTipsOfTheDay(Request $request): ResponseFactory|Response
    {
        $parameters = [
            'iso_lang' => strtolower($request->input('iso_lang', 'en')),
            'iso_country' => strtolower($request->input('iso_country', 'fr')),
        ];
        try {
            $buffer = $this->service->getTipOfTheDay($parameters);
            return response($buffer)
                ->header('Content-Type', 'text/plain; charset=utf-8');
        } catch (Exception $exception) {
            return response('KO|No advice for this country')
                ->header('Content-Type', 'text/plain; charset=utf-8');
        }

        //amzpayments
        //bluesnap
        //boxtal
        //boxtal2
        //cdiscount
        //chronopost
        //chrowin
        //easymarketing
    }

}
