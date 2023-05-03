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
		// Blog
		Route::controller(BlogController::class)->group(function () {
			Route::post('blogs', 'blogs');
			Route::resource('blog', BlogController::class)->only([
				'store','destroy','show','update'  ]);
		});	
		// contactUs
		Route::controller(ContactUsontroller::class)->group(function () {
			Route::post('contact-uss', 'contactUs');
			Route::resource('contact-us', ContactUsontroller::class)->only([
				'destroy','show',  ]);
		});	
		// AboutUsHighlights
		Route::controller(AboutUsHighlightsController::class)->group(function () {
			Route::post('about-us-highlights', 'highlights');
			Route::resource('about-us-highlight', AboutUsHighlightsController::class)->only([
				'store','destroy','show','update'  ]);
		});	
		// AboutUsTestimony
		Route::controller(AboutUsTestimonycontroller::class)->group(function () {
			Route::post('about-us-testimonies', 'aboutUsTestimonies');
			Route::resource('about-us-testimony', AboutUsTestimonycontroller::class)->only([
				'store','destroy','show','update'  ]);
		});	
		// Brand
		Route::controller(BrandController::class)->group(function () {
			Route::post('brands', 'brands');
			Route::resource('brand', BrandController::class)->only([
				'store','destroy','show','update'  ]);
		});				
		// DynamicPage
		Route::controller(DynamicPageController::class)->group(function () {
			Route::post('dynamic-pages', 'dynamicPages');
			Route::resource('dynamic-page', DynamicPageController::class)->only([
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
		Route::post('front/blogs', 'blogs');
		Route::post('front/blog/{slug?}', 'blog');
		Route::post('front/about-us-highlights', 'highlights');
		Route::post('front/about-us-testimonies', 'aboutUsTestimonies');
		Route::post('front/brands', 'brands');
		Route::post('front/contact-us', 'ContactUs');
		Route::post('front/dynamic-page/{slug?}', 'dynamicPage');
	});
});