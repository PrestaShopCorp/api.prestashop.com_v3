<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PreactivationService;
use App\Services\PrestaShopLinkService;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PartnerController extends Controller
{
    private PreactivationService $preactivationService;
    private PrestaShopLinkService $prestaShopLinkService;

    public function __construct(PreactivationService $preactivationService, PrestaShopLinkService $prestaShopLinkService)
    {
        $this->preactivationService = $preactivationService;
        $this->prestaShopLinkService = $prestaShopLinkService;
    }

    /**
     * Get PayPal Preactivation warnings
     */
    public function getPreactivationWarnings(Request $request): Response|RedirectResponse|ResponseFactory
    {
        if (!$request->has('version') && !$request->has('partner')) {
            return redirect()->away('https://www.prestashop.com');
        }

        $parameters = [
            'version' => strtolower($request->input('version', '')),
            'partner' => strtolower($request->input('partner', '')),
        ];
        try {
            $buffer = $this->preactivationService->getPreactivationWarnings($parameters);
            return response($buffer)->header('Content-Type', 'text/plain; charset=utf-8');
        } catch (Exception $exception) {
            return response('Unprocessable entity', 422);
        }
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function getPrestaShopLink(Request $request): Response|ResponseFactory
    {
        $parameters = [
            'iso_lang' => strtolower($request->input('iso_lang', 'en'))
        ];
        try {
            $buffer = $this->prestaShopLinkService->getPrestaShopLink($parameters);
            return response($buffer);
        } catch (Exception $exception) {
            return response('Unprocessable entity', 422);
        }
    }
}
