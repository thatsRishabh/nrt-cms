<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::namespace('App\Http\Controllers\Api\Common')->group(function () {

	Route::controller(AuthController::class)->group(function () {
		Route::post('user-login', 'login');
		Route::post('user-logout', 'logout')->middleware('auth:api');
		Route::post('change-password', 'changePassword')->middleware('auth:api');
	});

	Route::group(['middleware' => 'auth:api'],function () {



		 // menu Pages
		 Route::controller(MenuController::class)->group(function () {
			Route::post('menus', 'menus');
			Route::resource('menu', MenuController::class)->only([
				'store','destroy','show' , 'update']);
		});

		// services
		Route::controller(ServiceController::class)->group(function () {
			Route::post('services', 'services');
			Route::post('service-update/{id?}', 'update');
			Route::resource('service', ServiceController::class)->only([
				'store','destroy','show' ]);
		});

		// services
		Route::controller(AppSettingController::class)->group(function () {
			Route::post('app-settings', 'appSettings');
			Route::post('app-setting-update/{id?}', 'update');
			Route::resource('app-setting', AppSettingController::class)->only([
				'store','show' ]);
		});

		Route::controller(SliderController::class)->group(function () {
			Route::post('sliders', 'sliders');
			Route::post('slider-update/{id?}', 'update');
			Route::resource('slider', SliderController::class)->only([
				'store','destroy','show' ]);
		});

	});
});
