<?php

use App\Http\Controllers\API\InstallationSupportController;
use App\Http\Controllers\API\PrestashopVersionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/version/check_version.php', [PrestashopVersionController::class, 'checkVersion']);
Route::get('/iframe/install.php', [InstallationSupportController::class, 'getInstallationHelp']);
