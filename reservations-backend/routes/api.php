<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['middleware' => 'cors'], function () {
    Route::post('reservation', 'ReservationFlatAPIController@store');
    Route::post('reservation/{token}', 'ReservationAPIController@update');
    Route::post('reservation/cancel/{token}', 'ReservationAPIController@cancel');
    Route::get('reservation', 'ReservationAPIController@show');
    Route::get('brands', 'BrandAPIController@index');
    Route::get('stores', 'StoreAPIController@index');
});