<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use App\TypeOfDocu;
use Carbon\Carbon;

class Docu extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    
    //Table Name
    protected $table = 'docus';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;

    protected $dates = ['deleted_at'];

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'creator');
    }

    public function typeOfDocu()
    {
       return $this->belongsTo('App\TypeOfDocu');
    }

    public function fileUploads()
    {
        return $this->hasMany('App\FileUploads');
    }

    public function statuscode()
    {
        return $this->belongsTo('App\Statuscode');
    }

    public function transaction()
    {
        return $this->hasMany('App\Transaction');
    }

    public function singleSave($data)
    {
        $outData = [];
        $outData['creator'] = $data->input('user_id');
        $outData['is_rush'] = $data->input('rushed');
        $outData['department'] = Auth::user()->department->id;
        $outData['confidentiality'] = $data->input('confidential');
        $outData['complexity'] = $data->input('complexity');
        if($data->input('iso') != null){
            $outData['iso_code'] = $data->input('iso');
        }
        else{
            $outData['iso_code'] = null;
        }
        $outData['sender_name'] = $data->input('sender');
        $outData['sender_address'] = $data->input('sender_add');
        $outData['type_of_docu_id'] = $data->input('typeOfDocu');
        $outData['subject'] = $data->input('subject');
        $d = Carbon::createFromFormat('Y-m-d H:i', $data->input('final_action_date') . ' 23:59');
        $outData['final_action_date'] = $d->toDateTimeString();
        $docu_instance = new Docu;
        $out = $docu_instance->insert($outData);

        return $out;
    }

    public function batchSave($data)
    {
        $outData = [];
        $outData['creator'] = $data['creator'];
        $outData['is_rush'] = $data['is_rush'];
        $outData['confidentiality'] = $data['confidentiality'];
        $outData['complexity'] = $data['complexity'];
        if($data['iso'] != null){
            $outData['iso_code'] = $data['iso'];
        }
        else{
            $outData['iso_code'] = null;
        }
        $outData['department'] = $data['department'];
        $outData['sender_name'] = $data['sender_name'];
        $outData['sender_address'] = $data['sender_address'];
        $type = TypeOfDocu::where('docu_type', $data['typeOfDocu'])->first();
        $outData['type_of_docu_id'] = $type->id;
        $outData['subject'] = $data['subject'];
        $d = Carbon::createFromFormat('Y-m-d H:i', $data['final_action_date'] . ' 23:59');
        $outData['final_action_date'] = $d->toDateTimeString();
        $docu_instance = new Docu;
        $out = $docu_instance->insert($outData);
        
        return $out;
    }

    public function insert($data)
    {
        $docu_instance = new Docu;
        $thisYear = date('Y');
        $latestRefNum = $docu_instance->withTrashed()
        ->where('reference_number', 'like', $thisYear.'%')
        ->orderBy('id', 'desc')->pluck('reference_number')->first();
        if(is_null($latestRefNum)){
            $docu_instance->reference_number = $thisYear . '-0001';
        }
        else{
            $idWithLeadingZeroes = explode('-',$latestRefNum)[1];
            $incrementedId = (int)$idWithLeadingZeroes + 1;
            $newIdWithLeadingZeroes = $thisYear . '-' . str_pad($incrementedId, 4, '0', STR_PAD_LEFT);
            $docu_instance->reference_number = $newIdWithLeadingZeroes;
        }

        $docu_instance->creator = $data['creator'];
        $docu_instance->is_rush = $data['is_rush'];
        $docu_instance->department_id = $data['department'];
        $docu_instance->confidentiality = $data['confidentiality'];
        $docu_instance->complexity = $data['complexity'];
        $docu_instance->iso_code = $data['iso_code'];
        $docu_instance->sender_name = $data['sender_name'];
        $docu_instance->sender_address = $data['sender_address'];
        $docu_instance->type_of_docu_id = $data['type_of_docu_id'];
        $docu_instance->final_action_date = $data['final_action_date'];
        $docu_instance->subject = $data['subject'];
        $docu_instance->statuscode_id = 5;
        $docu_instance->save();

        return $docu_instance;
    }

    public function updateDocu($data, $id)
    {
        $docu_to_update = $this->withTrashed()->find($id);
        $d = Carbon::createFromFormat('Y-m-d H:i', $data->input('final_action_date') . ' 23:59');
        
        $docu_to_update->is_rush = $data->rushed;
        $docu_to_update->confidentiality = $data->confidential;
        $docu_to_update->complexity = $data->complexity;
        $docu_to_update->iso_code = $data->iso;
        $docu_to_update->sender_name = $data->sender;
        $docu_to_update->sender_address = $data->sender_add;
        $docu_to_update->type_of_docu_id = $data->typeOfDocu;
        $docu_to_update->final_action_date = $d->toDateTimeString();
        $docu_to_update->save();
    }
}
