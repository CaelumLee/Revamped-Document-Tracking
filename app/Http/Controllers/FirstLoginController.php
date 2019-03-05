<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Support\Facades\Hash;

class FirstLoginController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request,[
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $new_password = Hash::make($request->input('password'));

        $user = User::find(Auth::user()->id);
        $user->first_login = 0;
        $user->password = $new_password;
        $user->save();

        $request->session()->flash('success', 'Password changed! Welcome ' . Auth::user()->name);
        return redirect()->route('home');
    }
}
