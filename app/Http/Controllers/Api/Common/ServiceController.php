<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    //

    public function services(Request $request)
	{
		try {

			$query = Service::select('*')
			->with('menuDetail','subMenuDetail')
			->orderBy('id', 'desc');
			if(!empty($request->id))
			{
				$query->where('id', $request->id);
			}
			if(!empty($request->name))
			{
				$query->where('name', 'LIKE', '%'.$request->name.'%');
			}
			if(!empty($request->status))
			{
				$query->where('status',$request->status);
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
            'name'                      => 'required',
            // 'image'                       => $request->hasFile('image') ? 'mimes:jpeg,jpg,png,gif|max:10000' : '',
        ]);
		if ($validation->fails()) {
			return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
		} 
		DB::beginTransaction();
		try { 
			$servicePage = new Service;
			$servicePage->name = $request->name;
            $servicePage->menu_id = $request->menu_id;
			$servicePage->sub_menu_id = $request->sub_menu_id;
            $servicePage->order_number = $request->order_number;
            $servicePage->description = $request->description;
			$servicePage->image = $request->image;
			$servicePage->icon_image_path = $request->icon_image_path;
			$servicePage->redirect_url = $request->redirect_url;
			$servicePage->status = $request->status;
			$servicePage->save();

			DB::commit();
			return prepareResult(true,'Your data has been saved successfully' , $servicePage, 200);

		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}
    
	public function update(Request $request, $id)
	{
		$validation = Validator::make($request->all(), [
			'name' => 'required',

		]);
		if ($validation->fails()) {
			return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
		} 
		DB::beginTransaction();
		try {      
			$servicePage= Service::find($id);
			if (empty($servicePage)) {
				return prepareResult(false,'Record Not Found' ,[], 500);
			 }
			$servicePage->name = $request->name;
            $servicePage->menu_id = $request->menu_id;
			$servicePage->sub_menu_id = $request->sub_menu_id;
            $servicePage->order_number = $request->order_number;
            $servicePage->description = $request->description;
			$servicePage->image = $request->image;
			$servicePage->icon_image_path = $request->icon_image_path;
			$servicePage->redirect_url = $request->redirect_url;
			$servicePage->status = $request->status;
			$servicePage->save();
			DB::commit();
			return prepareResult(true,'Your data has been Updated successfully' ,$servicePage, 200);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

	public function show($id)
	{
		try {
			$servicePage = Service::find($id);
			if($servicePage)
			{
				return prepareResult(true,'Record Fatched Successfully' ,$servicePage, 200); 
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
			$servicePage = Service::find($id);
			if($servicePage)
			{
				$result=$servicePage->delete();
				return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
			}
			return prepareResult(false,'Record Not Found' ,[], 500);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

}
