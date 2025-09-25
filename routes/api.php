<?php

use App\Http\Controllers\API\InstallationController;
use App\Http\Controllers\API\VersionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/version/check_version.php', [VersionController::class, 'checkVersion']);
Route::get('/iframe/install.php', [InstallationController::class, 'getInstallationHelp']);
