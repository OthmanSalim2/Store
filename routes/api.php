<?php

use App\Http\Controllers\Api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccessToTokensController;
use App\Http\Controllers\Api\DeliveriesController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user();
    // other way
    // here speak clearly to guard : sanctum
    return Auth::guard('sanctum')->user();
});

// this's already will identify 5 route without create and edit
Route::apiResource('/products', ProductsController::class);

// other way
// Route::resource('/products', ProductsController::class)
//     ->except('create', 'edit');

Route::post('auth/access-token', [AccessToTokensController::class, 'store'])
    // I here speak guard:sanctum
    ->middleware('guest:sanctum');

Route::delete('auth/access-token/{token?}', [AccessToTokensController::class, 'destroy'])
    // can't delete specific token but just can delete specific token if was authentication user
    ->middleware('auth:sanctum');

Route::get('deliveries/{delivery}', [DeliveriesController::class, 'show']);
Route::put('deliveries/{delivery}', [DeliveriesController::class, 'update']);
