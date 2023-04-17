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
    
	public function update(Request $request, $id)
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
			$setting= AppSetting::find($id);
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
            {
				if(gettype($request->logo_path) == "string"){
					$setting->logo_path = $request->logo_path;
				}
				else{
					if ($request->hasFile('logo_path')) {
						$file = $request->file('logo_path');
						$filename=time().'.'.$file->getClientOriginalExtension();
						if ($file->move('assets/setting_photos', $filename)) {
							$setting->logo_path=env('CDN_DOC_URL').'assets/setting_photos/'.$filename.'';
						}
					}
				}
			}
            {
				if(gettype($request->logo_thumb_path) == "string"){
					$setting->logo_thumb_path = $request->logo_thumb_path;
				}
				else{
					if ($request->hasFile('logo_thumb_path')) {
						$file = $request->file('logo_thumb_path');
						$filename=time().'.'.$file->getClientOriginalExtension();
						if ($file->move('assets/setting_photos', $filename)) {
							$setting->logo_thumb_path=env('CDN_DOC_URL').'assets/setting_photos/'.$filename.'';
						}
					}
				}
			}
            {
				if(gettype($request->fav_icon) == "string"){
					$setting->fav_icon = $request->fav_icon;
				}
				else{
					if ($request->hasFile('fav_icon')) {
						$file = $request->file('fav_icon');
						$filename=time().'.'.$file->getClientOriginalExtension();
						if ($file->move('assets/setting_photos', $filename)) {
							$setting->fav_icon=env('CDN_DOC_URL').'assets/setting_photos/'.$filename.'';
						}
					}
				}
			}
            {
				if(gettype($request->certificate_image) == "string"){
					$setting->certificate_image = $request->certificate_image;
				}
				else{
					if ($request->hasFile('certificate_image')) {
						$file = $request->file('certificate_image');
						$filename=time().'.'.$file->getClientOriginalExtension();
						if ($file->move('assets/setting_photos', $filename)) {
							$setting->certificate_image=env('CDN_DOC_URL').'assets/setting_photos/'.$filename.'';
						}
					}
				}
			}
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
