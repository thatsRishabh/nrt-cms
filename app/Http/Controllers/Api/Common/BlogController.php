<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    //
    public function blogs(Request $request)
    {
        try {

            $query = Blog::select('*')
            ->orderBy('id', 'desc');
            if(!empty($request->id))
            {
                $query->where('id', $request->id);
            }
            if(!empty($request->title))
            {
                $query->where('title', 'LIKE', '%'.$request->title.'%');
            }
            if(!empty($request->slug))
            {
                $query->where('slug', $request->slug);
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
            $blogPost = new Blog;
            $blogPost->title = $request->title;
            $blogPost->slug = Str::slug($request->title);
            $blogPost->content = $request->content;
            $blogPost->video_path = $request->video_path;
            $blogPost->image_path = $request->image_path;
            $blogPost->posted_by = $request->posted_by;
            $blogPost->views = 0;
            $blogPost->post_date = $request->post_date;
            $blogPost->order_number = $request->order_number;
            $blogPost->status = $request->status;
            $blogPost->save();
            DB::commit();
            return prepareResult(true,'Your data has been saved successfully' , $blogPost, 200);

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
            $blogPost= Blog::find($id);
            $blogPost->title = $request->title;
            $blogPost->slug = Str::slug($request->title);
            $blogPost->content = $request->content;
            $blogPost->video_path = $request->video_path;
            $blogPost->image_path = $request->image_path;
            $blogPost->posted_by = $request->posted_by;
            $blogPost->views = $blogPost->views;
            $blogPost->post_date = $request->post_date;
            $blogPost->order_number = $request->order_number;
            $blogPost->status = $request->status;
            $blogPost->save();
            DB::commit();
            return prepareResult(true,'Your data has been Updated successfully' ,$blogPost, 200);
        } catch (\Throwable $e) {
            Log::error($e);
            return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $blogPost = Blog::find($id);
            if($blogPost)
            {
                return prepareResult(true,'Record Fatched Successfully' ,$blogPost, 200); 
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
            $blogPost = Blog::find($id);
            if($blogPost)
            {
                $result=$blogPost->delete();
                return prepareResult(true,'Record Deleted Successfully' ,$result, 200); 
            }
            return prepareResult(false,'Record Not Found' ,[], 500);
        } catch (\Throwable $e) {
            Log::error($e);
            return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
        }
    }
   
}
