<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Department;
use DB;
use App\Role;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function add(Request $request)
    {
        
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'department' => 'required',
            'role' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::beginTransaction();
            try{
                $user = $this->user->add_user($request);
                $request->session()->flash('success', 'User ' . $user->username . ' created!');
                DB::commit();
            }
            catch(\Exception $e){
                DB::rollback();
                throw $e;
            }
        
        return redirect()->route('allUsers');
    }

    public function index()
    {
        $user_list = User::where('department_id', Auth::user()->department->id)
        ->get();

        return view('admin.users', compact('user_list'));
    }

    public function allUsers()
    {   
        $user_list = User::all();

        $dept = Department::pluck('name', 'id');

        $role = Role::pluck('name', 'id');

        $data = [
            'users' => $user_list,
            'dept' => $dept,
            'role' => $role
        ];

        return view('admin.user_MIS', compact('data'));
    }

    public function disable(Request $request)
    {
        $user = User::find($request->input('user_id_disable'));
        $is_disable = $user->is_disabled;
        if($is_disable == 0){
            $user->is_disabled = 1;
            $strout = 'disabled';
        }
        else{
            $user->is_disabled = 0;
            $strout = 'enabled';
        }

        $user->save();

        $request->session()->flash('success', 'User ' . 
        $user->username . ' has been ' . $strout . '!'
        );

        return redirect()->route('allUsers');
    }

    public function pass(Request $request)
    {
        $this->validate($request,[
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        DB::beginTransaction();
            try{
                $user = $this->user->change_pass($request);
                $request->session()->flash('success', 'Password of user ' . 
                $user->username . ' has been changed!');
                DB::commit();
            }
            catch(\Exception $e){
                DB::rollback();
                throw $e;
            }
        
        return redirect()->route('allUsers'); 
    }

    public function edit(Request $request)
    {
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 
                Rule::unique('users', 'username')->ignore($request->input('hidden_user_id'))
            ],
            'department' => 'required',
            'role' => 'required',
        ]); 
        
        DB::beginTransaction();
            try{
                $user = $this->user->edit($request);
                $request->session()->flash('success', 'Details of user ' . 
                $user->username . ' has been changed!');
                DB::commit();
            }
            catch(\Exception $e){
                DB::rollback();
                throw $e;
            }
        
        return redirect()->route('allUsers'); 
        
    }

}
