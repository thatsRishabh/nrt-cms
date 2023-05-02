<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Portfolio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PortfolioController extends Controller
{
    //
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
        
        ]);
		if ($validation->fails()) {
			return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
		} 
		DB::beginTransaction();
		try { 
			$portfolioData = new Portfolio;
			$portfolioData->name = $request->name;
            $portfolioData->project_type = $request->project_type;
            $portfolioData->slug =Str::slug($request->name);
			$portfolioData->image = $request->image;
            $portfolioData->date = $request->date;
            $portfolioData->url = $request->url;
            $portfolioData->description = $request->description;
			$portfolioData->status = $request->status;
			$portfolioData->save();

			DB::commit();
			return prepareResult(true,'Your data has been saved successfully' , $portfolioData, 200);

		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}
    
	public function update(Request $request, $id)
	{
		$validation = Validator::make($request->all(), [
			'name'             => 'required',
         

		]);
		if ($validation->fails()) {
			return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
		} 
		DB::beginTransaction();
		try {      
			$portfolioData= Portfolio::find($id);
            $portfolioData->name = $request->name;
            $portfolioData->project_type = $request->project_type;
            $portfolioData->slug =Str::slug($request->name);
			$portfolioData->image = $request->image;
            $portfolioData->date = $request->date;
            $portfolioData->url = $request->url;
            $portfolioData->description = $request->description;
			$portfolioData->status = $request->status;
			$portfolioData->save();
			DB::commit();
			return prepareResult(true,'Your data has been Updated successfully' ,$portfolioData, 200);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

	public function show($id)
	{
		try {
			$portfolioData = Portfolio::find($id);
			if($portfolioData)
			{
				return prepareResult(true,'Record Fatched Successfully' ,$portfolioData, 200); 
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
			$portfolioData = Portfolio::find($id);
			if($portfolioData)
			{
				$result=$portfolioData->delete();
				return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
			}
			return prepareResult(false,'Record Not Found' ,[], 500);
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}
}
