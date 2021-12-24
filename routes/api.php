<?php

use App\Http\Controllers\api\StockApiController;
use App\Http\Controllers\api\VirtualInvestmentApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('stocks', StockApiController::class);
    Route::get('virtual-investment/clients/{client_id}', [VirtualInvestmentApiController::class, 'allStockPurchaseByClient']);
    //add new client and listing of all the clients
    Route::post('virtual-investment/clients', [VirtualInvestmentApiController::class, 'addNewClient']);
    Route::get('virtual-investment/clients', [VirtualInvestmentApiController::class, 'getAllClient']);
    //
    Route::apiResource('virtual-investment', VirtualInvestmentApiController::class);
});
