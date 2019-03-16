<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;

class DepartmentDashboardController extends Controller
{

    public function __construct(Department $department)
    {
        $this->dept = $department;
    }
    
    public function index()
    {
        $departments = Department::get();

        return view('admin.department', compact('departments'));
    }

    public function edit(Request $request)
    {
        $this->validate($request,[
            'dept_name' => ['required', 'string', 'max:255', 
                Rule::unique('departments', 'name')->ignore($request->input('department_id'))
            ],
            'dept_acro' => ['required', 'string', 'max:255', 
                Rule::unique('departments', 'acronym')->ignore($request->input('department_id'))
            ],
        ]);
        
        DB::beginTransaction();
            try{
                $dept = $this->dept->edit($request);
                $request->session()->flash('success', 'Details of department ' . 
                $dept->name . ' has been changed!');
                DB::commit();
            }
            catch(\Exception $e){
                DB::rollback();
                throw $e;
            }
        
        return redirect()->route('department');
    }

    public function disable(Request $request)
    {
        DB::beginTransaction();
        try{
            $dept_to_disable = $this->dept->find($request->input('dept_id_disable'));
            $is_disable = $dept_to_disable->is_disabled;
            if($is_disable == 0){
                $dept_to_disable->is_disabled = 1;
                $strout = 'disabled';
            }
            else{
                $dept_to_disable->is_disabled = 0;
                $strout = 'enabled';
            }
            
            $dept_to_disable->save();
            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            throw $e;
        }

        $request->session()->flash('success', 'Department ' . 
        $dept_to_disable->name . ' has been ' . $strout . '!'
        );
        
        return redirect()->route('department');
    }

    public function add(Request $request)
    {
        $message = [
            'dept_name.unique' => 'Your input ":input" for department name has already been taken',
            'dept_acro.unique' => 'Your input ":input" for department acronym has already been taken',
        ];

        $this->validate($request,[
            'dept_name' => ['required', 'string', 'max:255', 'unique:departments,name'],
            'dept_acro' => ['required', 'string', 'max:255', 'unique:departments,acronym'],
        ], $message);

        $this->dept->add($request);
        $request->session()->flash('success', 'New department added!');

        return redirect()->route('department');
    }
}
