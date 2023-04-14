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


		// Route::controller(CategoryController::class)->group(function () {
		// 	Route::post('categories', 'categories');
		// 	Route::post('category-update/{id?}', 'update');
		// 	Route::resource('category', CategoryController::class)->only([
		// 		'store','destroy','show' ]);
		// });

        // Site Page
        Route::controller(SitePagesController::class)->group(function () {
			Route::post('sitepages', 'sitePages');
			Route::resource('sitepage', SitePagesController::class)->only([
				'store','destroy','show' , 'update']);
		});

 		// // dashboard        
		// Route::controller(DashboardController::class)->group(function () {
		// 	Route::post('dashboard', 'dashboard');
		// 	Route::post('category-wise-list', 'categoryWiseList'); 
		// 	Route::post('dashboard-graph', 'dashboardGraph'); 
		// 	Route::post('dashboard-graph-list', 'dashboardGraphByName'); 
		// });
	});
});
