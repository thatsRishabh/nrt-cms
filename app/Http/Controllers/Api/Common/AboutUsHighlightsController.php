<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AboutUsHighlight;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AboutUsHighlightsController extends Controller
{
    //
    public function highlights(Request $request)
     {
         try {
 
             $query = AboutUsHighlight::select('*')
             ->orderBy('id', 'desc');
             if(!empty($request->id))
             {
                 $query->where('id', $request->id);
             }
             if(!empty($request->name))
             {
                 $query->where('name', 'LIKE', '%'.$request->name.'%');
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
             'status'                      => 'required',
          
         ]);
         if ($validation->fails()) {
             return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
         } 
         DB::beginTransaction();
         try { 
             $highlight = new AboutUsHighlight;
             $highlight->title = $request->title;
             $highlight->description = $request->description;
             $highlight->status = $request->status;
             $highlight->icon_path = $request->icon_path;
             $highlight->image_path = $request->image_path;
             $highlight->save();
 
             DB::commit();
             return prepareResult(true,'Your data has been saved successfully' , $highlight, 200);
 
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
     
     public function update(Request $request, $id)
     {
         $validation = Validator::make($request->all(), [
             'title'             => 'required',
             'status'            => 'required',     
         ]);
         if ($validation->fails()) {
             return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
         } 
         DB::beginTransaction();
         try {      
             $highlight= AboutUsHighlight::find($id);
             if (empty($highlight)) {
				return prepareResult(false,'Record Not Found' ,[], 500);
			 }
             $highlight->title = $request->title;
             $highlight->description = $request->description;
             $highlight->status = $request->status;
             $highlight->icon_path = $request->icon_path;
             $highlight->image_path = $request->image_path;
             $highlight->save();
             DB::commit();
             return prepareResult(true,'Your data has been Updated successfully' ,$highlight, 200);
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
 
     public function show($id)
     {
         try {
             $highlight = AboutUsHighlight::find($id);
             if($highlight)
             {
                 return prepareResult(true,'Record Fatched Successfully' ,$highlight, 200); 
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
             $highlight = AboutUsHighlight::find($id);
             if($highlight)
             {
                 $result=$highlight->delete();
                 return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
             }
             return prepareResult(false,'Record Not Found' ,[], 500);
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
    
}
