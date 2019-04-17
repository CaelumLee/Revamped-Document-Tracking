<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        'password', 'remember_token', 'created_at', 'updated_at', 'first_login'
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

    public function add_user($data)
    {
        $user_instance = new User;
        $user_instance->name = $data->input('name');
        $user_instance->username = $data->input('username');
        $user_instance->department_id = $data->input('department');
        $user_instance->role_id = $data->input('role');
        $user_instance->password = Hash::make($data->input('password'));
        $user_instance->remember_token = Str::random(10);
        $user_instance->save();

        return $user_instance;
    }

    public function change_pass($data)
    {
        $user = $this->find($data->input('hidden_id'));
        $user->password = Hash::make($data->input('password'));
        $user->first_login = 1;
        $user->save();

        return $user;
    }

    public function edit($data)
    {
        $user = $this->find($data->input('hidden_user_id'));
        $user->name = $data->input('name');
        $user->username = $data->input('username');
        $user->department_id = $data->input('department');
        $user->role_id = $data->input('role');
        $user->save();

        return $user;
    }
}
