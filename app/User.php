<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'department_id', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function docu()
    {
        return $this->hasMany('App\Docu', 'creator');
    }

    public function fileUploads()
    {
        return $this->hasMany('App\FileUploads');
    }

    public function from()
    {
        return $this->hasMany('App\Transaction', 'in_charge');
    }

    public function to()
    {
        return $this->hasMany('App\Transaction', 'recipient');
    }
}
