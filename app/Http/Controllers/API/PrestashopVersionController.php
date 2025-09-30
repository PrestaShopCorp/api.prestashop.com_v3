<?php

namespace App\Http\Controllers\API;

use App\Events\PrestaShopVersionChecked;
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
        $parameters = [
            'address' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) && isset($_SERVER['REMOTE_ADDR']) && preg_match('/^10\.2/', $_SERVER['REMOTE_ADDR'])
                ? $_SERVER['HTTP_X_FORWARDED_FOR']
                : $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'referer' => $request->headers->get('referer', ''),
            'activity' => $request->input('activity', 0),
            'version' => $request->input('v', ''),
            'iso_code' => $request->input('lang', 'en'),
            'hosted_mode' => $request->input('hosted_mode', 0),
        ];

        try {
            $buffer = $this->prestashopVersionCheckerService->checkPrestaShopVersion($parameters);
            event(new PrestaShopVersionChecked($parameters));
            return response($buffer, 200);
        } catch (Exception $exception) {
            return response('Unprocessable entity', 422);
        }
    }
}
