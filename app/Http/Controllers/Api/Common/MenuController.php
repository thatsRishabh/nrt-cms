<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


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

			$query = Menu::select('*')->with('subMenus')->whereNull('parent_id')
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
			return prepareResult(false,'Error while fatching Records' ,$e->getMessage(), 500);
		}
	}
    
	public function store(Request $request)
	{
        $validation = Validator::make($request->all(),  [
            'name'                      => 'required|unique:menus,name,',
            'slug'                      => 'required',
			'position_type'             => 'numeric',
        ]);
		if ($validation->fails()) {
			return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
		} 
		DB::beginTransaction();
		try { 
			$menuPage = new Menu;
			$menuPage->name = $request->name;
            $menuPage->parent_id = $request->parent_id;
            $menuPage->slug = $request->slug;
            $menuPage->order_number = $request->order_number;
			$menuPage->position_type = $request->position_type;
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
			'name' => 'required|unique:menus,name,'.$id,
            'slug'                      => 'required',
			'position_type'             => 'numeric',
		]);
		if ($validation->fails()) {
			return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
		} 
		DB::beginTransaction();
		try {      
			$menuPage= Menu::find($id);
			$menuPage->name = $request->name;
            $menuPage->parent_id = $request->parent_id;
            $menuPage->slug = $request->slug;
            $menuPage->order_number = $request->order_number;
			$menuPage->position_type = $request->position_type;
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
