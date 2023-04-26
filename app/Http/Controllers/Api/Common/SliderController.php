<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Slider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    //
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

	public function store(Request $request)
	{
        $validation = Validator::make($request->all(),  [
            'title'                      => 'required',
            // 'image'                       => $request->hasFile('image') ? 'mimes:jpeg,jpg,png,gif|max:10000' : '',
            'order_number'                      => 'numeric',
        ]);
		if ($validation->fails()) {
			return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
		} 
		DB::beginTransaction();
		try { 
			$sliderImage = new Slider;
			$sliderImage->title = $request->title;
			$sliderImage->sub_title = $request->sub_title;
			$sliderImage->button_test = $request->button_test;
			$sliderImage->url = $request->url;
			$sliderImage->description = $request->description;
            $sliderImage->menu_id = $request->menu_id;
            $sliderImage->order_number = $request->order_number;
			$sliderImage->status = $request->status;
			$sliderImage->image = $request->image;
			$sliderImage->save();

			DB::commit();
			return prepareResult(true,'Your data has been saved successfully' , $sliderImage, 200);

		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}
    
	public function update(Request $request, $id)
	{
		$validation = Validator::make($request->all(), [
			'title'             => 'required',
            'order_number'                      => 'numeric',

		]);
		if ($validation->fails()) {
			return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
		} 
		DB::beginTransaction();
		try {      
			$sliderImage= Slider::find($id);
			$sliderImage->title = $request->title;
			$sliderImage->sub_title = $request->sub_title;
			$sliderImage->button_test = $request->button_test;
			$sliderImage->url = $request->url;
			$sliderImage->description = $request->description;
            $sliderImage->menu_id = $request->menu_id;
            $sliderImage->order_number = $request->order_number;
            $sliderImage->image = $request->image;
			$sliderImage->status = $request->status;
			$sliderImage->save();
			DB::commit();
			return prepareResult(true,'Your data has been Updated successfully' ,$sliderImage, 200);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

	public function show($id)
	{
		try {
			$sliderImage = Slider::find($id);
			if($sliderImage)
			{
				return prepareResult(true,'Record Fatched Successfully' ,$sliderImage, 200); 
			}
			return prepareResult(false,'Record not found' ,[], 500);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

	public function destroy($id)
	{
		try {
			$sliderImage = Slider::find($id);
			if($sliderImage)
			{
				$result=$sliderImage->delete();
				return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
			}
			return prepareResult(false,'Record Not Found' ,[], 500);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}
}
