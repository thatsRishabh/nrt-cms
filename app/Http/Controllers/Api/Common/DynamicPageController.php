<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DynamicPage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DynamicPageController extends Controller
{
    //
    public function dynamicPages(Request $request)
     {
         try {
 
             $query = DynamicPage::select('*')
             ->with('menuDetail','subMenuDetail')
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
          
         ]);
         if ($validation->fails()) {
             return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
         } 
         DB::beginTransaction();
         try { 
            if(!empty($request->menu_id)) {
                $dynamicInfos= DynamicPage::where('menu_id',$request->menu_id)->where('sub_menu_id',$request->sub_menu_id)->first();
                if(empty($dynamicInfos))
                {
                    $dynamicInfos = new DynamicPage;
                }
            }
            else {
                $dynamicInfos = new DynamicPage;
            }

             $dynamicInfos->menu_id = $request->menu_id;
             $dynamicInfos->sub_menu_id = $request->sub_menu_id;
             $dynamicInfos->title = $request->title;
             $dynamicInfos->sub_title = $request->sub_title;
             $dynamicInfos->banner_image_path = $request->banner_image_path;
             $dynamicInfos->document_path = $request->document_path;
             $dynamicInfos->description = $request->description;
             $dynamicInfos->status = $request->status;
             $dynamicInfos->save();
 
             DB::commit();
             return prepareResult(true,'Your data has been saved successfully' , $dynamicInfos, 200);
 
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
            
            // if(!empty($request->menu_id)) {
            //     $checkId= DynamicPage::where('menu_id',$request->menu_id)->where('sub_menu_id',$request->sub_menu_id)->first();
            //     if($checkId)
            //     {
            //         return prepareResult(false,'Record Already exists with given menu!',[],500);
            //     }
            // }

            $dynamicInfos = DynamicPage::find($id);
            if (!is_object($dynamicInfos)) {
                return prepareResult(false,'Record Not Found!',[],500);
            }

             $dynamicInfos->menu_id = $request->menu_id;
             $dynamicInfos->sub_menu_id = $request->sub_menu_id;
             $dynamicInfos->title = $request->title;
             $dynamicInfos->sub_title = $request->sub_title;
             $dynamicInfos->banner_image_path = $request->banner_image_path;
             $dynamicInfos->document_path = $request->document_path;
             $dynamicInfos->description = $request->description;
             $dynamicInfos->status = $request->status;
             $dynamicInfos->save();
             DB::commit();
             return prepareResult(true,'Your data has been Updated successfully' ,$dynamicInfos, 200);
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
 
     public function show($id)
     {
         try {
             $dynamicInfos = DynamicPage::find($id);
             if($dynamicInfos)
             {
                 return prepareResult(true,'Record Fatched Successfully' ,$dynamicInfos, 200); 
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
             $dynamicInfos = DynamicPage::find($id);
             if($dynamicInfos)
             {
                 $result=$dynamicInfos->delete();
                 return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
             }
             return prepareResult(false,'Record Not Found' ,[], 500);
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
}
