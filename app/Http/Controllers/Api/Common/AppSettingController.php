<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AppSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AppSettingController extends Controller
{
    public function appSettings()
    {
        try
        {
            $appSetting = AppSetting::first();
            return prepareResult(true,'App Setting Fetched' , $appSetting, 200);
            
        }
        catch (\Throwable $e) {
            		Log::error($e);
            		return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
            	}
    }
    
	public function update(Request $request)
	{
		$validation = Validator::make($request->all(), [
            'app_name'                        => 'required',
            'logo_path'                       => $request->hasFile('logo_path') ? 'mimes:jpeg,jpg,png,gif|max:10000' : '',
            'call_us'                         => 'numeric',
            'mail_us'                         => 'email',
            'org_number'                      => 'numeric',
            'support_email'                   => 'numeric',
            'support_email'                   => 'email',

		]);
		if ($validation->fails()) {
			return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
		} 
		DB::beginTransaction();
		try {     
			if(AppSetting::first())
            {
                $setting = AppSetting::first();
            }
            else
            {
                $setting = new AppSetting;
            } 
			// $setting= AppSetting::find($id);
			$setting->app_name = $request->app_name;
            $setting->description = $request->description;
            $setting->call_us = $request->call_us;
            $setting->mail_us = $request->mail_us;
            $setting->service_start_time = $request->service_start_time;
            $setting->service_end_time = $request->service_end_time;
            $setting->fb_url = $request->fb_url;
            $setting->twitter_url = $request->twitter_url;
            $setting->insta_url = $request->insta_url;
            $setting->linkedIn_url = $request->linkedIn_url;
            $setting->pinterest_url = $request->pinterest_url;
            $setting->copyright_text = $request->copyright_text;
            $setting->address = $request->address;
            $setting->meta_title = $request->meta_title;
            $setting->meta_keywords = $request->meta_keywords;
            $setting->meta_description = $request->meta_description;
            $setting->org_number = $request->org_number;
            $setting->customer_care_number = $request->customer_care_number;
            $setting->allowed_app_version = $request->allowed_app_version;
            $setting->invite_url = $request->invite_url;
            $setting->play_store_url = $request->play_store_url;
            $setting->app_store_url = $request->app_store_url;
            $setting->support_email = $request->support_email;
            $setting->support_contact_number = $request->support_contact_number;

			$setting->logo_path = $request->logo_path;
            $setting->logo_thumb_path = $request->logo_thumb_path;
            $setting->fav_icon = $request->fav_icon;
            $setting->certificate_image = $request->certificate_image;
			$setting->save();
			DB::commit();
			return prepareResult(true,'Your data has been Updated successfully' ,$setting, 200);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

	public function show($id)
	{
		try {
			$setting = AppSetting::find($id);
			if($setting)
			{
				return prepareResult(true,'Record Fatched Successfully' ,$setting, 200); 
			}
			return prepareResult(false,'Record not found' ,[], 500);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}


}
