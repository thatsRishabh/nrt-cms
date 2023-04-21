<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Models\Menu;
use App\Models\AppSetting;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Team;
use App\Models\Testimonial;

class FrontDataController extends Controller
{
    //
    public function menus(Request $request)
	{
		try {

			$query = Menu::select('*')->with('subMenus')
			->whereNull('parent_id')
			->orderBy('id', 'desc');
			if(!empty($request->id))
			{
				$query->where('id', $request->id);
			}
			if(!empty($request->name))
			{
				$query->where('name', $request->name);
			}
			if(!empty($request->status))
			{
				$query->where('status',1);
			}

			if(!empty($request->per_page_record))
			{
				$perPage = $request->per_page_record;
				$page = $request->input('page', 1);
				$total = $query->count();
				$result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

				$pagination =  [
					'data' => $result,
					'total' => $total,
					'current_page' => $page,
					'per_page' => $perPage,
					'last_page' => ceil($total / $perPage)
				];
				$query = $pagination;
			}
			else
			{
				$query = $query->get();
			}

			return prepareResult(true,'Records Fatched Successfully' ,$query, 200);

		} 
		catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

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

	public function portfolios(Request $request)
	{
		try {

			$query = Portfolio::select('*')
			->orderBy('id', 'desc');
			if(!empty($request->id))
			{
				$query->where('id', $request->id);
			}
			if(!empty($request->name))
			{
				$query->where('name', $request->name);
			}

			if(!empty($request->per_page_record))
			{
				$perPage = $request->per_page_record;
				$page = $request->input('page', 1);
				$total = $query->count();
				$result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

				$pagination =  [
					'data' => $result,
					'total' => $total,
					'current_page' => $page,
					'per_page' => $perPage,
					'last_page' => ceil($total / $perPage)
				];
				$query = $pagination;
			}
			else
			{
				$query = $query->get();
			}

			return prepareResult(true,'Records Fatched Successfully' ,$query, 200);

		} 
		catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

	public function services(Request $request)
	{
		try {

			$query = Service::select('*')
			->orderBy('id', 'desc');
			if(!empty($request->id))
			{
				$query->where('id', $request->id);
			}
			if(!empty($request->name))
			{
				$query->where('name', $request->name);
			}

			if(!empty($request->per_page_record))
			{
				$perPage = $request->per_page_record;
				$page = $request->input('page', 1);
				$total = $query->count();
				$result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

				$pagination =  [
					'data' => $result,
					'total' => $total,
					'current_page' => $page,
					'per_page' => $perPage,
					'last_page' => ceil($total / $perPage)
				];
				$query = $pagination;
			}
			else
			{
				$query = $query->get();
			}

			return prepareResult(true,'Records Fatched Successfully' ,$query, 200);

		} 
		catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

	public function sliders(Request $request)
	{
		try {

			$query = Slider::select('*')
			->orderBy('id', 'desc');
			if(!empty($request->id))
			{
				$query->where('id', $request->id);
			}
			if(!empty($request->name))
			{
				$query->where('name', $request->name);
			}

			if(!empty($request->per_page_record))
			{
				$perPage = $request->per_page_record;
				$page = $request->input('page', 1);
				$total = $query->count();
				$result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

				$pagination =  [
					'data' => $result,
					'total' => $total,
					'current_page' => $page,
					'per_page' => $perPage,
					'last_page' => ceil($total / $perPage)
				];
				$query = $pagination;
			}
			else
			{
				$query = $query->get();
			}

			return prepareResult(true,'Records Fatched Successfully' ,$query, 200);

		} 
		catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

	public function teams(Request $request)
	{
		try {

			$query = Team::select('*')
			->orderBy('id', 'desc');
			if(!empty($request->id))
			{
				$query->where('id', $request->id);
			}
			if(!empty($request->name))
			{
				$query->where('name', $request->name);
			}

			if(!empty($request->per_page_record))
			{
				$perPage = $request->per_page_record;
				$page = $request->input('page', 1);
				$total = $query->count();
				$result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

				$pagination =  [
					'data' => $result,
					'total' => $total,
					'current_page' => $page,
					'per_page' => $perPage,
					'last_page' => ceil($total / $perPage)
				];
				$query = $pagination;
			}
			else
			{
				$query = $query->get();
			}

			return prepareResult(true,'Records Fatched Successfully' ,$query, 200);

		} 
		catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

	public function testimonials(Request $request)
     {
         try {
 
             $query = Testimonial::select('*')
             ->orderBy('id', 'desc');
             if(!empty($request->id))
             {
                 $query->where('id', $request->id);
             }
             if(!empty($request->name))
             {
                 $query->where('name', $request->name);
             }
 
             if(!empty($request->per_page_record))
             {
                 $perPage = $request->per_page_record;
                 $page = $request->input('page', 1);
                 $total = $query->count();
                 $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();
 
                 $pagination =  [
                     'data' => $result,
                     'total' => $total,
                     'current_page' => $page,
                     'per_page' => $perPage,
                     'last_page' => ceil($total / $perPage)
                 ];
                 $query = $pagination;
             }
             else
             {
                 $query = $query->get();
             }
 
             return prepareResult(true,'Records Fatched Successfully' ,$query, 200);
 
         } 
         catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
}
