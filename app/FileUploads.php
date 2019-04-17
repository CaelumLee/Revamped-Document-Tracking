<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Storage;
use File;

class FileUploads extends Model
{
    //Table Name
    protected $table = 'file_uploads';
    //Primary Key
    public $primaryKey = 'id';

    public function docu()
    {
        return $this->belongsTo('App\Docu');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function file_store($file_to_upload)
    {
        $folder_name_with_current_date = Date('Ymd') . '_file_uploads';
        foreach($file_to_upload as $key => $upload){
            //get filename with extension
            $filenameWithExt = $upload->getClientOriginalName();
            //get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just extension
            $extension = $upload->getClientOriginalExtension();
            //filename to store
            $file = $filename. '_' . time(). '.' . $extension;
            //upload file
            $upload->storeAs('public/uploads/' . $folder_name_with_current_date, $file);
            $dataFile['file' . $key] = $file;
        }
        $file_info = [
            'path' => $folder_name_with_current_date,
            'dataFile' => $dataFile
        ];

        return $file_info;
    }

    public function upload($docu_id, $data)
    {
        if($data->hasFile('filename')){
            $file_to_upload = $data->file('filename');
            $file_info = $this->file_store($file_to_upload);
        }
        else{
            $file_info = [];
        }

        $fileStore_instance = new FileUploads;
        $fileStore_instance->docu_id = $docu_id;
        $fileStore_instance->uploaded_by = Auth::user()->id;
        $fileStore_instance->upload_data = json_encode($file_info);
        $fileStore_instance->save();
    }

    public function view($fileInfos)
    {
        $array_of_fileinfo = json_decode($fileInfos, true);
        if(empty($array_of_fileinfo)){
            $output = "<div class = 'row'><h4>No uploaded files found</h4></div>";
        }
        else{
            $output = "<div class = 'row'><h4>Uploaded Files</h4>";
            $directory_with_date_of_file = $array_of_fileinfo['path'];
            foreach($array_of_fileinfo['dataFile'] as $filename){
                switch(strtolower(substr(strrchr($filename,'.'),1))){
                    case "png":
                    case "jpg":
                    case "jpeg":
                    case "bmp":
                        $output .= "<div class ='col s4'> <a href='" . asset('storage/uploads/'. 
                        $directory_with_date_of_file .'/' . $filename) ."' target='_blank'>" .
                        "<img id='image-upload' src='" . asset('storage/uploads/'. 
                        $directory_with_date_of_file .'/' . $filename) .  "'>" .
                        "</a><span class='truncate'>". $filename ."</span></div>";
                        break;
                    case "pdf":
                        $output .= "<div class ='col s4'> <a href='" . asset('storage/uploads/'. 
                        $directory_with_date_of_file .'/' . $filename) . "' download>" .
                        "<img id='image-upload' src='" . asset('images/pdf_logo.jpg') . "'>" .
                        "</a><span class='truncate'>". $filename ."</div>";
                        break;
                    case "pptx":
                    case "ppt":
                        $output .= "<div class ='col s4'> <a href='" . asset('storage/uploads/'. 
                        $directory_with_date_of_file .'/' . $filename) . "' download>" .
                        "<img id='image-upload' src='" . asset('images/powerpoint_logo.png') . "'>" .
                        "</a><span>". $filename ."</span></div>";
                        break;
                    case "docx":
                    case "doc" :
                    $output .= "<div class ='col s4'> <a href='" . asset('storage/uploads/'. 
                    $directory_with_date_of_file .'/' . $filename) . "' download>" .
                    "<img id='image-upload' src='" . asset('images/word_logo.png') . "'>" .
                        "</a><span class='truncate'>". $filename ."</span></div>";
                        break;
                    case "xlsx":
                    case "xls":
                        $output .= "<div class ='col s4'> <a href='" . asset('storage/uploads/'. 
                        $directory_with_date_of_file .'/' . $filename) . "' download>" .
                        "<img id='image-upload' src='" . asset('images/excel_logo.png') . "'>" .
                        "</a><span class='truncate'>". $filename ."</span></div>";
                        break;
                }
            }
            $output .= '</div>';
        }
        return $output;
    }
}
