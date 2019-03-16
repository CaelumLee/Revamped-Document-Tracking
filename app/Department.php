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

    public function fromLoc()
    {
        return $this->hasOne('App\Transaction', 'location');
    }

    public function toLoc()
    {
        return $this->hasOne('App\Transaction', 'route');
    }

    public function edit($request)
    {
        $dept = $this->find($request->input('department_id'));
        $dept->name = $request->input('dept_name');
        $dept->acronym = $request->input('dept_acro');
        $dept->save();

        return $dept;
    }

    public function add($request)
    {
        $new_dept = new Department;
        $new_dept->name = $request->input('dept_name');
        $new_dept->acronym = $request->input('dept_acro');
        $new_dept->save();
    }
}
