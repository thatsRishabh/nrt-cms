<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SeoKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SeoKeyController extends Controller
{
    //
     //
     public function seoKeys(Request $request)
     {
         try {
 
             $query = SeoKey::select('*')
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
             'page_name'                      => 'required',
          
         ]);
         if ($validation->fails()) {
             return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
         } 
         DB::beginTransaction();
         try { 
             $seoInfo = new SeoKey;
             $seoInfo->page_name = $request->page_name;
             $seoInfo->page_title = $request->page_title;
             $seoInfo->meta_keywords = $request->meta_keywords;
             $seoInfo->meta_description = $request->meta_description;
             $seoInfo->image_path = $request->image_path;
             $seoInfo->canonical = $request->canonical;
             $seoInfo->save();
 
             DB::commit();
             return prepareResult(true,'Your data has been saved successfully' , $seoInfo, 200);
 
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
     
     public function update(Request $request, $id)
     {
         $validation = Validator::make($request->all(), [
             'page_name'             => 'required',
           
 
         ]);
         if ($validation->fails()) {
             return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
         } 
         DB::beginTransaction();
         try {      
             $seoInfo= SeoKey::find($id);
             $seoInfo->page_name = $request->page_name;
             $seoInfo->page_title = $request->page_title;
             $seoInfo->meta_keywords = $request->meta_keywords;
             $seoInfo->meta_description = $request->meta_description;
             $seoInfo->image_path = $request->image_path;
             $seoInfo->canonical = $request->canonical;
             $seoInfo->save();
             DB::commit();
             return prepareResult(true,'Your data has been Updated successfully' ,$seoInfo, 200);
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
 
     public function show($id)
     {
         try {
             $seoInfo = SeoKey::find($id);
             if($seoInfo)
             {
                 return prepareResult(true,'Record Fatched Successfully' ,$seoInfo, 200); 
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
             $seoInfo = SeoKey::find($id);
             if($seoInfo)
             {
                 $result=$seoInfo->delete();
                 return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
             }
             return prepareResult(false,'Record Not Found' ,[], 500);
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
    

}
