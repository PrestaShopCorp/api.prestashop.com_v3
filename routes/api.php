<?php

use App\Http\Controllers\API\InstallationSupportController;
use App\Http\Controllers\API\PrestashopVersionController;
use App\Http\Controllers\API\RssController;
use App\Http\Controllers\API\TipsOfTheDayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/version/check_version.php', [PrestashopVersionController::class, 'checkVersion']);
Route::get('/iframe/install.php', [InstallationSupportController::class, 'getInstallationHelp']);
Route::get('/rss/news2.php', [RssController::class, 'getRssNews']);
Route::get('/rss2/news2.php', [RssController::class, 'getRss2News']);
Route::get('/partner/tipsoftheday/', [TipsOfTheDayController::class, 'getTipsOfTheDay']);
