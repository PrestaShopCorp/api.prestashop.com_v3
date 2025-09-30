<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\InstallationSupportService;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InstallationSupportController extends Controller
{
    private InstallationSupportService $installationSupportService;

    public function __construct(InstallationSupportService $installationSupportService)
    {
        $this->installationSupportService = $installationSupportService;
    }

    /**
     * Get installation help iframe
     */
    public function getInstallationHelp(Request $request): ResponseFactory|Response
    {
        $parameters = [
            'address' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) && isset($_SERVER['REMOTE_ADDR']) && preg_match('/^10\.2/', $_SERVER['REMOTE_ADDR'])
                ? $_SERVER['HTTP_X_FORWARDED_FOR']
                : $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'iso_lang' => $request->input('lang', 'en'),
            'step' => $request->input('step', false),
            'errors' => $request->input('errors', false),
        ];

        try {
            $buffer = $this->installationSupportService->getIFrame($parameters);
            return response($buffer, 200);
        } catch (Exception $exception) {
            return response('Unprocessable entity', 422);
        }
    }
}
