<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function ($router) {
    Route::get('test', 'App\Http\Controllers\SyncController@index');
    Route::post('webhook-product-update', 'App\Http\Controllers\SyncController@productUpdate');
    Route::get('products', 'App\Http\Controllers\SyncController@products');
    Route::post('products', 'App\Http\Controllers\SyncController@storeProduct');
    Route::post('test-webhook', 'App\Http\Controllers\WebhookController@test');
});
