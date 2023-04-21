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
			Route::post('sub-menus', 'subMenus');
			Route::resource('menu', MenuController::class)->only([
				'store','destroy','show' , 'update']);
		});

		// services
		Route::controller(ServiceController::class)->group(function () {
			Route::post('services', 'services');
			Route::resource('service', ServiceController::class)->only([
				'store','destroy','show','update' ]);
		});
		
		// AppSetting
		Route::controller(AppSettingController::class)->group(function () {
			Route::get('app-settings', 'appSettings');
			Route::post('app-setting-update', 'update');
		});
		// slider
		Route::controller(SliderController::class)->group(function () {
			Route::post('sliders', 'sliders');
			Route::resource('slider', SliderController::class)->only([
				'store','destroy','show','update'  ]);
		});

		// portfolios
		Route::controller(PortfolioController::class)->group(function () {
			Route::post('portfolios', 'portfolios');
			Route::resource('portfolio', PortfolioController::class)->only([
				'store','destroy','show','update'  ]);
		});
		// SeoKey
		Route::controller(SeoKeyController::class)->group(function () {
			Route::post('seo-keys', 'seoKeys');
			Route::resource('seo-key', SeoKeyController::class)->only([
				'store','destroy','show','update'  ]);
		});
		// teams
		Route::controller(TeamController::class)->group(function () {
			Route::post('teams', 'teams');
			Route::resource('team', TeamController::class)->only([
				'store','destroy','show','update'  ]);
		});
		// testimonials
		Route::controller(TestimonialController::class)->group(function () {
			Route::post('testimonials', 'testimonials');
			Route::resource('testimonial', TestimonialController::class)->only([
				'store','destroy','show','update'  ]);
		});				
		Route::controller(FileUploadController::class)->group(function () {
			Route::post('file-upload', 'store');
		});
	});
});

Route::namespace('App\Http\Controllers\Api')->group(function () {
	Route::controller(FrontDataController::class)->group(function () {
		Route::get('front/app-setting', 'appSettings');
		Route::post('front/menus', 'menus');
		Route::post('front/portfolios', 'portfolios');
		Route::post('front/services', 'services');
		Route::post('front/sliders', 'sliders');
		Route::post('front/teams', 'teams');
		Route::post('front/testimonials', 'testimonials');
	});
});