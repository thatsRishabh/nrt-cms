<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ContactUs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class ContactUsontroller extends Controller
{
    //
    public function contactUs(Request $request)
     {
         try {
 
             $query = ContactUs::select('*')
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
     

     public function show($id)
     {
         try {
             $ContactUsData = ContactUs::find($id);
             if($ContactUsData)
             {
                 return prepareResult(true,'Record Fatched Successfully' ,$ContactUsData, 200); 
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
             $ContactUsData = ContactUs::find($id);
             if($ContactUsData)
             {
                 $result=$ContactUsData->delete();
                 return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
             }
             return prepareResult(false,'Record Not Found' ,[], 500);
         } catch (\Throwable $e) {
             Log::error($e);
             return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
         }
     }
}
