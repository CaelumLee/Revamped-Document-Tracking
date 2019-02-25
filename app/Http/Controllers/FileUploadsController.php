<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FileUploads;
use DB;

class FileUploadsController extends Controller
{
    
    public function __construct(FileUploads $fileUploads)
    {
        $this->middleware('auth');
        $this->uploads = $fileUploads;
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'filename' => 'array',
            'filename.*' => 'mimes:jpeg,bmp,png,pptx,pdf,xlsx,docx|max:500000',
        ]);
        
        $this->uploads->upload($request);
        $request->session()->flash('success', 'File/s uploaded!');
        
        return redirect()->route('docu.show', ['id' => $request->input('docu_id')]);
    }

    public function getFiles(Request $request)
    {
        $fileUpload_id = $request->input('dataID');
        $data = $this->uploads->find($fileUpload_id)->upload_data;
        $output = $this->uploads->view($data);
        return response()->json([
            'File_Uploads' => $output
        ],200);
    }
}
