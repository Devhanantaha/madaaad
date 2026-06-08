<?php

use Illuminate\Support\Facades\Route;
use Core\Http\Controllers\SystemController;
use Core\Http\Controllers\Api\TranslationController;
use Core\Http\Controllers\ContactMessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::get('/locale/{lang}', [TranslationController::class, 'themeTranslations']);
    Route::get('/cache-reset', [SystemController::class, 'clearSystemCacheFromApi']);
    Route::post('/store/contact/message', [ContactMessageController::class, 'storeContactMessage']);
});