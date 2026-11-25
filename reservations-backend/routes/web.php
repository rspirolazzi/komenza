<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', 'HomeController@index');;



Auth::routes();

//Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'admin', 'middleware' => 'authAdmin:admin'], function () {

    Route::group(['middleware' => 'check.role.store'], function (){

        Route::get('reservationFlats', ['as' => 'admin.reservationFlats.index', 'uses' => 'Admin\ReservationFlatController@index']);
        Route::post('reservationFlats', ['as' => 'admin.reservationFlats.store', 'uses' => 'Admin\ReservationFlatController@store']);
        Route::get('reservationFlats/create', ['as' => 'admin.reservationFlats.create', 'uses' => 'Admin\ReservationFlatController@create']);
        Route::put('reservationFlats/{reservationFlats}', ['as' => 'admin.reservationFlats.update', 'uses' => 'Admin\ReservationFlatController@update']);
        Route::patch('reservationFlats/{reservationFlats}', ['as' => 'admin.reservationFlats.update', 'uses' => 'Admin\ReservationFlatController@update']);
        Route::delete('reservationFlats/{reservationFlats}', ['as' => 'admin.reservationFlats.destroy', 'uses' => 'Admin\ReservationFlatController@destroy']);
        Route::get('reservationFlats/{reservationFlats}', ['as' => 'admin.reservationFlats.show', 'uses' => 'Admin\ReservationFlatController@show']);
        Route::get('reservationFlats/{reservationFlats}/edit', ['as' => 'admin.reservationFlats.edit', 'uses' => 'Admin\ReservationFlatController@edit']);

        Route::get('brands', ['as' => 'admin.brands.index', 'uses' => 'Admin\BrandController@index']);
        Route::post('brands', ['as' => 'admin.brands.store', 'uses' => 'Admin\BrandController@store']);
        Route::get('brands/create', ['as' => 'admin.brands.create', 'uses' => 'Admin\BrandController@create']);
        Route::put('brands/{brands}', ['as' => 'admin.brands.update', 'uses' => 'Admin\BrandController@update']);
        Route::patch('brands/{brands}', ['as' => 'admin.brands.update', 'uses' => 'Admin\BrandController@update']);
        Route::delete('brands/{brands}', ['as' => 'admin.brands.destroy', 'uses' => 'Admin\BrandController@destroy']);
        Route::get('brands/{brands}', ['as' => 'admin.brands.show', 'uses' => 'Admin\BrandController@show']);
        Route::get('brands/{brands}/edit', ['as' => 'admin.brands.edit', 'uses' => 'Admin\BrandController@edit']);

        Route::get('stores', ['as' => 'admin.stores.index', 'uses' => 'Admin\StoreController@index']);
        Route::post('stores', ['as' => 'admin.stores.store', 'uses' => 'Admin\StoreController@store']);
        Route::get('stores/create', ['as' => 'admin.stores.create', 'uses' => 'Admin\StoreController@create']);
        Route::put('stores/{stores}', ['as' => 'admin.stores.update', 'uses' => 'Admin\StoreController@update']);
        Route::patch('stores/{stores}', ['as' => 'admin.stores.update', 'uses' => 'Admin\StoreController@update']);
        Route::delete('stores/{stores}', ['as' => 'admin.stores.destroy', 'uses' => 'Admin\StoreController@destroy']);
        Route::get('stores/{stores}', ['as' => 'admin.stores.show', 'uses' => 'Admin\StoreController@show']);
        Route::get('stores/{stores}/edit', ['as' => 'admin.stores.edit', 'uses' => 'Admin\StoreController@edit']);
        Route::get('stores/{stores}/copy', ['as' => 'admin.stores.copy', 'uses' => 'Admin\StoreController@copy']);

        Route::get('stores/{stores}/delivery', ['as' => 'admin.stores.delivery', 'uses' => 'Admin\StoreController@delivery']);
        Route::put('stores/{stores}/storesDelivery', ['as' => 'admin.stores.storeDelivery', 'uses' => 'Admin\StoreController@storeDelivery']);

        Route::get('users', ['as'=> 'admin.users.index', 'uses' => 'Admin\UserController@index']);
        Route::post('users', ['as'=> 'admin.users.store', 'uses' => 'Admin\UserController@store']);
        Route::get('users/create', ['as'=> 'admin.users.create', 'uses' => 'Admin\UserController@create']);
        Route::put('users/{users}', ['as'=> 'admin.users.update', 'uses' => 'Admin\UserController@update']);
        Route::patch('users/{users}', ['as'=> 'admin.users.update', 'uses' => 'Admin\UserController@update']);
        Route::delete('users/{users}', ['as'=> 'admin.users.destroy', 'uses' => 'Admin\UserController@destroy']);
        Route::get('users/{users}', ['as'=> 'admin.users.show', 'uses' => 'Admin\UserController@show']);
        Route::get('users/{users}/edit', ['as'=> 'admin.users.edit', 'uses' => 'Admin\UserController@edit']);

        Route::get('admin-users', ['as'=> 'admin.admin_users.index', 'uses' => 'Admin\AdminUserController@index']);
        Route::post('admin-users', ['as'=> 'admin.admin_users.store', 'uses' => 'Admin\AdminUserController@store']);
        Route::get('admin-users/create', ['as'=> 'admin.admin_users.create', 'uses' => 'Admin\AdminUserController@create']);
        Route::put('admin-users/{id}', ['as'=> 'admin.admin_users.update', 'uses' => 'Admin\AdminUserController@update']);
        Route::patch('admin-users/{id}', ['as'=> 'admin.admin_users.update', 'uses' => 'Admin\AdminUserController@update']);
        Route::delete('admin-users/{id}', ['as'=> 'admin.admin_users.destroy', 'uses' => 'Admin\AdminUserController@destroy']);
        Route::get('admin-users/{id}', ['as'=> 'admin.admin_users.show', 'uses' => 'Admin\AdminUserController@show']);
        Route::get('admin-users/{id}/edit', ['as'=> 'admin.admin_users.edit', 'uses' => 'Admin\AdminUserController@edit']);


        Route::get('auditories', ['as' => 'admin.auditories.index', 'uses' => 'AuditorController@index']);
        Route::get('auditory', ['as' => 'admin.auditories.show', 'uses' => 'AuditorController@show']);

        Route::get('reservationTestEmail', ['as' => 'admin.reservation.testEmail', 'uses' => 'Admin\ReservationController@testEmail']);
    });

    Route::get('/', ['as' => 'admin.dashboard.index', 'uses' => 'Admin\DashboardController@index']);

    Route::get('reservations', ['as'=> 'admin.reservations.index', 'uses' => 'Admin\ReservationController@index']);
    Route::post('reservations/print', ['as'=> 'admin.reservations.print', 'uses' => 'Admin\ReservationController@print']);
    Route::get('reservations/list', ['as'=> 'admin.reservations.list', 'uses' => 'Admin\ReservationController@listIndex']);
    Route::post('reservations', ['as'=> 'admin.reservations.store', 'uses' => 'Admin\ReservationController@store']);
    Route::get('reservations/create', ['as'=> 'admin.reservations.create', 'uses' => 'Admin\ReservationController@create']);
    Route::put('reservations/{reservations}', ['as'=> 'admin.reservations.update', 'uses' => 'Admin\ReservationController@update']);
    Route::patch('reservations/{reservations}', ['as'=> 'admin.reservations.update', 'uses' => 'Admin\ReservationController@update']);
    Route::delete('reservations/{reservations}', ['as'=> 'admin.reservations.destroy', 'uses' => 'Admin\ReservationController@destroy']);
    Route::get('reservations/{reservations}', ['as'=> 'admin.reservations.show', 'uses' => 'Admin\ReservationController@show']);
    Route::get('reservations/{reservations}/edit', ['as'=> 'admin.reservations.edit', 'uses' => 'Admin\ReservationController@edit']);
    Route::get('reservations_update/', ['as'=> 'admin.reservations.update_reservation', 'uses' => 'Admin\ReservationController@updateReservation']);

    Route::get('reports', ['as'=> 'admin.reports.index', 'uses' => 'ReportsController@index']);
    Route::post('reports', ['as'=> 'admin.reports.index', 'uses' => 'ReportsController@index']);
    Route::get('reports/report1', ['as'=> 'admin.reports.report1', 'uses' => 'ReportsController@report1']);
    Route::get('reports/pdf/r1', ['as'=> 'admin.reports.report1', 'uses' => 'ReportsController@report1']);
    Route::get('reports/pdf/r2', ['as'=> 'admin.reports.report2', 'uses' => 'ReportsController@report2']);
    Route::get('reports/pdf/r3', ['as'=> 'admin.reports.report3', 'uses' => 'ReportsController@report3']);
    Route::get('reports/pdf/r4', ['as'=> 'admin.reports.report4', 'uses' => 'ReportsController@report4']);

    Route::get('settings', ['as'=> 'admin.settings.index', 'uses' => 'Admin\SettingsController@index']);
});
