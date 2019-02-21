<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //Table Name
    protected $table = 'departments';
    //Primary Key
    public $primaryKey = 'id';

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function docu()
    {
        return $this->hasOne('App\Docu');
    }
}
