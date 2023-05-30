<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Faq;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    //
    public function faqs(Request $request)
    {
        try {

            $query = Faq::select('*')
            ->orderBy('id', 'desc');
            if(!empty($request->id))
            {
                $query->where('id', $request->id);
            }
            if(!empty($request->title))
            {
                $query->where('title', 'LIKE', '%'.$request->title.'%');
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
            'title'                      => 'required',
        ]);
        if ($validation->fails()) {
            return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
        } 
        DB::beginTransaction();
        try { 
            $FaqData = new Faq;
            $FaqData->title = $request->title;
            $FaqData->description = $request->description;
            $FaqData->status = $request->status;
            $FaqData->save();

            DB::commit();
            return prepareResult(true,'Your data has been saved successfully' , $FaqData, 200);

        } catch (\Throwable $e) {
            Log::error($e);
            return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'title'             => 'required',     
        ]);
        if ($validation->fails()) {
            return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
        } 
        DB::beginTransaction();
        try {      
            $FaqData= Faq::find($id);
            if (empty($FaqData)) {
				return prepareResult(false,'Record Not Found' ,[], 500);
			 }
            $FaqData->title = $request->title;
            $FaqData->description = $request->description;
            $FaqData->status = $request->status;
            $FaqData->save();
            DB::commit();
            return prepareResult(true,'Your data has been Updated successfully' ,$FaqData, 200);
        } catch (\Throwable $e) {
            Log::error($e);
            return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $FaqData = Faq::find($id);
            if($FaqData)
            {
                return prepareResult(true,'Record Fatched Successfully' ,$FaqData, 200); 
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
            $FaqData = Faq::find($id);
            if($FaqData)
            {
                $result=$FaqData->delete();
                return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
            }
            return prepareResult(false,'Record Not Found' ,[], 500);
        } catch (\Throwable $e) {
            Log::error($e);
            return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
        }
    }
   
}
