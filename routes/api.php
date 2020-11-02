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

Route::post('/login', 'Auth\LoginController@login');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user-authenticated', 'API\UserController@getUserLogin')->name('user.authenticated');
    Route::get('user-lists', 'API\UserController@userLists')->name('user.index');

    // Dashboard Pembankit
    Route::get('dashboard-pembangkittotal', 'API\DashboardController@getPembangkitTotal');
    Route::get('dashboard-pembangkitplta', 'API\DashboardController@getPembangkitPlta');
    Route::get('dashboard-pembangkitminihydro', 'API\DashboardController@getPembangkitMiniHydro');
    Route::get('dashboard-penyalurantotal', 'API\DashboardController@getPenyaluranTotal');
    Route::get('dashboard-penyaluranpln', 'API\DashboardController@getPenyaluranPln');

    // Pembangkit
    Route::get('pembangkit-metering', 'API\PembangkitController@getMeteringData');
    // Penyaluran
    Route::get('penyaluran-metering', 'API\PenyaluranController@getMeteringData');

    Route::get('report-produksi', 'API\ReportController@getProduksiData');

    Route::get('users', 'API\UserController@index');
    Route::delete('users/{user}', 'API\UserController@destroy');
    Route::get('roles', 'API\RolePermissionController@getAllRole');
    Route::get('permissions', 'API\RolePermissionController@getAllPermission')->name('permission');
    Route::post('role-permission', 'API\RolePermissionController@getRolePermission')->name('role_permission');
    Route::post('set-role-permission', 'API\RolePermissionController@setRolePermission')->name('set_role_permission');
    Route::post('set-role-user', 'API\RolePermissionController@setRoleUser')->name('user.set_role');
});
