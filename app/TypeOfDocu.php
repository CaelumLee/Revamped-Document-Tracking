<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeOfDocu extends Model
{
    public $timestamps = false;

    public function docu()
    {
        return $this->hasOne('App\Docu');
    }

    public function add($data)
    {
        $new_type = new TypeOfDocu;
        $new_type->docu_type = $data->input('docu_type');
        $new_type->save();
    }
}
