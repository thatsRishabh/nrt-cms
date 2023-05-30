<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    //
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
                $query->where('name', 'LIKE', '%'.$request->name.'%');
            }
			// if(!empty($request->status))
			// {
			// 	$query->where('status',1);
			// }
			if(!empty($request->status))
			{
				$query->where('status',$request->status);
			}
			if(!empty($request->position_type))
			{
				$query->where('position_type',$request->position_type);
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

	public function subMenus(Request $request)
	{
		try {

			$query = Menu::select('*')
			->whereNotNull('parent_id')
			// ->whereNull('parent_id')
			->orderBy('id', 'desc');
			if(!empty($request->id))
			{
				$query->where('id', $request->id);
			}
			if(!empty($request->name))
            {
                $query->where('name', 'LIKE', '%'.$request->name.'%');
            }
			if(!empty($request->parent_id))
			{
				$query->where('parent_id', $request->parent_id);
			}
			// if(!empty($request->status))
			// {
			// 	$query->where('status',1);
			// }
			if(!empty($request->status))
			{
				$query->where('status',$request->status);
			}
			if(!empty($request->position_type))
			{
				$query->where('position_type',$request->position_type);
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
	public function showsubMenus($id)
	{
		try {
			$menuPage = Menu::whereNotNull('parent_id')->find($id);
			// $menuPage = Menu::find($id);
			if($menuPage)
			{
				return prepareResult(true,'Record Fatched Successfully' ,$menuPage, 200); 
			}
			return prepareResult(false,'Record not found' ,[], 500);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}
    
	public function store(Request $request)
	{
        $validation = Validator::make($request->all(),  [
            'name'                      => 'required',
			'status'             => 'required|numeric',
			'order_number'             => 'required|numeric',
			'position_type'             => 'required|numeric',
        ]);
		if ($validation->fails()) {
			return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
		} 
		DB::beginTransaction();
		try { 
			$menuPage = new Menu;
			$menuPage->name = $request->name;
            $menuPage->parent_id = $request->parent_id;
            $menuPage->slug = Str::slug($request->name);
            $menuPage->order_number = $request->order_number;
			$menuPage->position_type = $request->position_type;
			$menuPage->status = $request->status;
			$menuPage->icon_path = $request->icon_path;
			$menuPage->save();

			DB::commit();
			return prepareResult(true,'Your data has been saved successfully' , $menuPage, 200);

		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}
    
	public function update(Request $request, $id)
	{
		$validation = Validator::make($request->all(), [
			'name'                      => 'required',
			'status'                   => 'required|numeric',
			'order_number'             => 'required|numeric',
			'position_type'             => 'required|numeric',
		]);
		if ($validation->fails()) {
			return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
		} 
		DB::beginTransaction();
		try {      
			$menuPage= Menu::find($id);
			if (empty($menuPage)) {
				return prepareResult(false,'Record Not Found' ,[], 500);
			 }
			$menuPage->name = $request->name;
            $menuPage->parent_id = $request->parent_id;
            $menuPage->slug = Str::slug($request->name);
            $menuPage->order_number = $request->order_number;
			$menuPage->position_type = $request->position_type;
			$menuPage->status = $request->status;
			$menuPage->icon_path = $request->icon_path;
			$menuPage->save();
			DB::commit();
			return prepareResult(true,'Your data has been Updated successfully' ,$menuPage, 200);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

	public function show($id)
	{
		try {
			$menuPage = Menu::find($id);
			if($menuPage)
			{
				return prepareResult(true,'Record Fatched Successfully' ,$menuPage, 200); 
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
			$menuPage = Menu::find($id);
			if($menuPage)
			{
				$result=$menuPage->delete();
				return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
			}
			return prepareResult(false,'Record Not Found' ,[], 500);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}
}
