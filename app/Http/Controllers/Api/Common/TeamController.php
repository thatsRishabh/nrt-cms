<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    //
     //
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
                 $query->where('name', 'LIKE', '%'.$request->name.'%');
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
             'name'                      => 'required',
             // 'image'                       => $request->hasFile('image') ? 'mimes:jpeg,jpg,png,gif|max:10000' : '',
             'order_number'                      => 'numeric',
         ]);
         if ($validation->fails()) {
             return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
         } 
         DB::beginTransaction();
         try { 
             $teamInfo = new Team;
             $teamInfo->name = $request->name;
             $teamInfo->title = $request->title;
             $teamInfo->sub_title = $request->sub_title;
             $teamInfo->designation = $request->designation;
             $teamInfo->profile_image_path = $request->profile_image_path;
             $teamInfo->role_description = $request->role_description;
             $teamInfo->no_of_developers = $request->no_of_developers;
             $teamInfo->status = $request->status;
             $teamInfo->save();
 
             DB::commit();
             return prepareResult(true,'Your data has been saved successfully' , $teamInfo, 200);
 
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
     
     public function update(Request $request, $id)
     {
         $validation = Validator::make($request->all(), [
             'name'             => 'required',
             'order_number'                      => 'numeric',
 
         ]);
         if ($validation->fails()) {
             return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
         } 
         DB::beginTransaction();
         try {      
             $teamInfo= Team::find($id);
             if (empty($teamInfo)) {
				return prepareResult(false,'Record Not Found' ,[], 500);
			 }
             $teamInfo->name = $request->name;
             $teamInfo->title = $request->title;
             $teamInfo->sub_title = $request->sub_title;
             $teamInfo->designation = $request->designation;
             $teamInfo->profile_image_path = $request->profile_image_path;
             $teamInfo->role_description = $request->role_description;
             $teamInfo->no_of_developers = $request->no_of_developers;
             $teamInfo->status = $request->status;
             $teamInfo->save();
             DB::commit();
             return prepareResult(true,'Your data has been Updated successfully' ,$teamInfo, 200);
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
 
     public function show($id)
     {
         try {
             $teamInfo = Team::find($id);
             if($teamInfo)
             {
                 return prepareResult(true,'Record Fatched Successfully' ,$teamInfo, 200); 
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
             $teamInfo = Team::find($id);
             if($teamInfo)
             {
                 $result=$teamInfo->delete();
                 return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
             }
             return prepareResult(false,'Record Not Found' ,[], 500);
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
}
