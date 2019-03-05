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
        if(Auth::user()->first_login == 1){
            return view('first');
        }
        else{
            $title = "All Documents";
            $docus = Docu::orderBy('final_action_date', 'asc')
            ->with('statuscode')
            ->get();
            return view('home', compact('title', 'docus'));    
        }
    }

    public function accepted()
    {
        $title = 'Accepted Documents';
        $docus = Docu::withTrashed()
        ->orderBy('final_action_date' , 'asc')
        ->where('is_accepted', '1')
        ->with('statuscode')
        ->get();
        return view('home', compact('title', 'docus'));
    }

    public function inactive()
    {
        $title = "Inactive Documents";
        $docus = Docu::orderBy('final_action_date', 'asc')
        ->where('final_action_date', '<' , date('Y-m-d H:i:s'))
        ->with('statuscode')
        ->get();
        return view('home', compact('title', 'docus'));
    }

    public function received()
    {
        $title = 'Received Documents';
        $docus = Docu::join('transactions', 'docus.id', '=', 'transactions.docu_id')
        ->where([
            ['transactions.recipient', Auth::user()->id],
            ['is_accepted', 0]
        ])
        ->select('docus.*')
        ->with('statuscode')
        ->orderBy('docus.final_action_date', 'asc')
        ->get();
        return view('home', compact('title', 'docus'));
    }

    public function archived()
    {
        $title = "Archived Documents";
        $docus = Docu::onlyTrashed()
        ->orderBy('final_action_date' , 'asc')
        ->with('statuscode')
        ->get();
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
