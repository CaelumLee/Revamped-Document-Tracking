<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statuscode extends Model
{
     //Table Name
     protected $table = 'statuscode';
     //Primary Key
     public $primaryKey = 'id';

     public $timestamps = false;

     public function docu()
     {
          return $this->hasOne('App\Docu');
     }
}
