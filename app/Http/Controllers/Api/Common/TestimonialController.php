<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    //
     //
     public function testimonials(Request $request)
     {
         try {
 
             $query = Testimonial::select('*')
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
             'order_number'                      => 'numeric',
         ]);
         if ($validation->fails()) {
             return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
         } 
         DB::beginTransaction();
         try { 
             $testimony = new Testimonial;
             $testimony->name = $request->name;
             $testimony->content = $request->content;
             $testimony->designation = $request->designation;
             $testimony->profile_image_path = $request->profile_image_path;
             $testimony->rating = $request->rating;
             $testimony->status = $request->status;
             $testimony->save();
             DB::commit();
             return prepareResult(true,'Your data has been saved successfully' , $testimony, 200);
 
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
             $testimony= Testimonial::find($id);
             $testimony->name = $request->name;
             $testimony->content = $request->content;
             $testimony->designation = $request->designation;
             $testimony->profile_image_path = $request->profile_image_path;
             $testimony->rating = $request->rating;
             $testimony->status = $request->status;
             $testimony->save();
             DB::commit();
             return prepareResult(true,'Your data has been Updated successfully' ,$testimony, 200);
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
 
     public function show($id)
     {
         try {
             $testimony = Testimonial::find($id);
             if($testimony)
             {
                 return prepareResult(true,'Record Fatched Successfully' ,$testimony, 200); 
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
             $testimony = Testimonial::find($id);
             if($testimony)
             {
                 $result=$testimony->delete();
                 return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
             }
             return prepareResult(false,'Record Not Found' ,[], 500);
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
}
