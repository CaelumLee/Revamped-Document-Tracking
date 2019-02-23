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

    public function upload($data)
    {
        if($data->hasFile('filename')){
            $file_to_upload = $data->file('filename');
            $file_info = $this->file_store($file_to_upload);
        }

        $fileStore_instance = new FileUploads;
        $fileStore_instance->docu_id = $data->input('docu_id');
        $fileStore_instance->uploaded_by = Auth::user()->id;
        $fileStore_instance->upload_data = json_encode($file_info);
        $fileStore_instance->save();
    }
}
