<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Docu;
use App\User;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = "All Documents";
        $docus = Docu::orderBy('created_at', 'desc')->get();
        return view('home', compact('title', 'docus'));
    }

    public function getAddress(Request $request)
    {
        $username = $request->input('username');
        $user = User::whereUsername($username)->first();
        if($user){
            return response()->json([
                'department' => $user->department->name
            ],200);   
        }
        return response()->json(null,200);
    }

    public function getUsers(Request $request)
    {
        $is_confidential = $request->input('answer');
        if($is_confidential == 1){
            $user_list = User::where([
                ['id', '!=', Auth::user()->id],
                ['role_id', '1']
            ])
            ->pluck('username');
        }
        else{
            $user_list = User::where('id', '!=', Auth::user()->id)
            ->pluck('username');
        }
        return response()->json([
            'user_list' => $user_list
        ],200);
    }
}
