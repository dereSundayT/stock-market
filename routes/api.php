<?php

use App\Http\Controllers\api\AuthApiController;
use App\Http\Controllers\api\DashboardApiController;
use App\Http\Controllers\api\StockApiController;
use App\Http\Controllers\api\VirtualInvestmentApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::group(['prefix' => 'v1'], function () {
    //
    Route::post('login', [AuthApiController::class, 'login'])->name('auth.login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('dashboard', [DashboardApiController::class, 'index']);
        Route::post('logout', [AuthApiController::class, 'logout'])->name('auth.logout');
        Route::apiResource('stocks', StockApiController::class, [
            'except' => ['show']
        ]);
        Route::get('virtual-investment/clients/{client_id}', [VirtualInvestmentApiController::class, 'allStockPurchaseByClient']);
        Route::post('virtual-investment/clients/fund-wallet', [VirtualInvestmentApiController::class, 'fundClientWallet']);
        Route::post('virtual-investment/clients', [VirtualInvestmentApiController::class, 'addNewClient']);
        Route::get('virtual-investment/clients', [VirtualInvestmentApiController::class, 'getAllClient']);
        Route::apiResource('virtual-investment', VirtualInvestmentApiController::class, ['only' => 'store']);
    });
});
