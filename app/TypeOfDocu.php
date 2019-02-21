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
}
