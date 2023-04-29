<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FileUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    //
    public function store(Request $request)
    {
        if($request->is_multiple==1)
        {
            $validation = Validator::make($request->all(),[ 
                'file'     => 'required|array|max:20480|min:1'
            ]);
        }
        else
        {
            $validation = Validator::make($request->all(),[ 
                'file'     => 'required|max:20480',
            ]);
        }
        if ($validation->fails()) {
            return prepareResult(false,$validation->errors()->first() ,$validation->errors(), 500);
        }


        try
        {
            $file = $request->file;
            $destinationPath = 'uploads/';
            $fileArray = array();
            $formatCheck = ['doc','docx','png','jpeg','jpg','pdf','svg','mp4','tif','tiff','bmp','gif','eps','raw','jfif','webp','pem','csv'];

            if($request->is_multiple==1)
            {
                foreach ($file as $key => $value) 
                {
                    $extension = strtolower($value->getClientOriginalExtension());
                    if(!in_array($extension, $formatCheck))
                    {
                        return prepareResult(false,'File Not Allowed' ,['Only allowed : doc,docx,png,jpeg,jpg,pdf,svg,mp4,tif,tiff,bmp,gif,eps,raw,jfif,webp,pem,csv'], 500);

                    }

                    $fileName   = time().'-'.rand(0,99999).'.' . $value->getClientOriginalExtension();
                    $extension = $value->getClientOriginalExtension();
                    $fileSize = $value->getSize();

        
                    $value->move($destinationPath, $fileName);
    

                    //Create File Log
                    $file_name  = $fileName;
                    $file_type  = $extension;
                    $file_location  = env('CDN_DOC_URL').$destinationPath.$fileName;
                    $file_size  = $fileSize;

                    $fileSave = $this->CreateFileUploadRecord($file_name,$file_type,$file_location,$file_size);
                    
                    $fileArray[] = [
                        // 'file_location'         => env('CDN_DOC_URL').$destinationPath.$fileName,
                        'file_location'         => $destinationPath.$fileName,
                        'file_extension'    => $value->getClientOriginalExtension(),
                        'file_name' => $value->getClientOriginalName(),
                    ];
                }
                return prepareResult(true,'File Uploaded Successfully' , $fileArray, 200);
                
            }
            else
            {
                $fileName   = time().'-'.rand(0,99999).'.' . $file->getClientOriginalExtension();
                $extension = strtolower($file->getClientOriginalExtension());
                $fileSize = $file->getSize();

                if(!in_array($extension, $formatCheck))
                {
                    return prepareResult(false,'File Not Allowed' ,['Only allowed : doc,docx,png,jpeg,jpg,pdf,svg,mp4,tif,tiff,bmp,gif,eps,raw,jfif,webp,pem,csv'], 500);
                }

                $file->move($destinationPath, $fileName);

                //Create File Log
                $file_name  = $fileName;
                $file_type  = $extension;
                $file_location  = env('CDN_DOC_URL').$destinationPath.$fileName;
                $file_size  = $fileSize;
                
                $fileSave = $this->CreateFileUploadRecord($file_name,$file_type,$file_location,$file_size);

                $fileInfo = [
                    // 'file_location'          => env('CDN_DOC_URL').$destinationPath.$fileName,
                     'file_location'          => $destinationPath.$fileName,
                    'file_extension'         => $file->getClientOriginalExtension(),
                    'file_name'              => $file->getClientOriginalName(),
                ];
                
                return prepareResult(true,'File Uploaded Successfully' , $fileInfo, 200);
            }   
        }
        catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
    }

    private function CreateFileUploadRecord($file_name,$file_type,$file_location,$file_size)
    {
        $fileupload = new FileUpload;
        $fileupload->file_name = $file_name;
        // $fileupload->file_type = $file_type;
        $fileupload->file_location = $file_location;
        // $fileupload->file_size = $file_size;
        $fileupload->save();
        return $fileupload;
    }
}
