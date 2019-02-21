<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
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
        return $this->belongsTo('App\User');
    }

    public function typeOfDocu()
    {
       return $this->belongsTo('App\TypeOfDocu');
    }

    public function singleSave($data)
    {
        $outData = [];
        $outData['creator'] = $data->input('user_id');
        $outData['is_rush'] = $data->input('rushed');
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
        $docu_instance->confidentiality = $data['confidentiality'];
        $docu_instance->complexity = $data['complexity'];
        $docu_instance->iso_code = $data['iso_code'];
        $docu_instance->sender_name = $data['sender_name'];
        $docu_instance->sender_address = $data['sender_address'];
        $docu_instance->type_of_docu_id = $data['type_of_docu_id'];
        $docu_instance->final_action_date = $data['final_action_date'];
        $docu_instance->subject = $data['subject'];
        $docu_instance->progress = 5;
        $docu_instance->save();

        return $docu_instance;
    }
}
