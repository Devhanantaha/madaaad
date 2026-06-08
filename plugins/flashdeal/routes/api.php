<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugin\Flashdeal\Http\Controllers\FlashDealController;

Route::group(['prefix' => 'v1/flash-deal'], function () {
    Route::post('active-flash-deals', [FlashDealController::class, 'activeFlashDeals']);
    Route::post('flash-deal-details', [FlashDealController::class, 'fashDealDetails']);
    Route::post('flash-deal-products', [FlashDealController::class, 'fashDealProducts']);
});
