<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Docu;
use App\User;
use Carbon\Carbon;
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
            // $docus_after_now = Docu::where('final_action_date', '>', Carbon::now())
            // ->orderBy('is_rush', 'desc')
            // ->orderBy('final_action_date', 'desc')
            // ->with('statuscode')
            // ->get();
            // $docus_before_now = Docu::where('final_action_date', '<', Carbon::now())
            // ->orderBy('is_rush', 'desc')
            // ->orderBy('final_action_date', 'desc')
            // ->with('statuscode')
            // ->get();

            // $docus = $docus_after_now->merge($docus_before_now);
            $docus = Docu::orderBy('is_rush', 'desc')
            ->orderBy('final_action_date', 'desc')
            ->with('statuscode')
            ->get();
            return view('home', compact('title', 'docus'));    
        }
    }

    public function accepted()
    {
        $title = 'Accepted Documents';
        // $docus_after_now = Docu::withTrashed()
        // ->where([
        //     ['final_action_date', '>', Carbon::now()],
        //     ['is_accepted', '1']
        // ])
        // ->orderBy('is_rush', 'desc')
        // ->orderBy('final_action_date', 'desc')
        // ->with('statuscode')
        // ->get();
        // $docus_before_now = Docu::withTrashed()
        // ->where([
        //     ['final_action_date', '<', Carbon::now()],
        //     ['is_accepted', '1']
        // ])
        // ->orderBy('is_rush', 'desc')
        // ->orderBy('final_action_date', 'desc')
        // ->with('statuscode')
        // ->get();

        // $docus = $docus_after_now->merge($docus_before_now);
        $docus = Docu::withTrashed()
        ->where([
            ['is_accepted', '1']
        ])
        ->orderBy('final_action_date', 'desc')
        ->with('statuscode')
        ->get();
        return view('home', compact('title', 'docus'));
    }

    public function inactive()
    {
        $title = "Inactive Documents";
        $docus = Docu::orderBy('is_rush', 'desc')
        ->orderBy('final_action_date', 'asc')
        ->where('final_action_date', '<' , Carbon::now())
        ->with('statuscode')
        ->get();
        return view('home', compact('title', 'docus'));
    }

    public function received()
    {
        $title = 'Received Documents';
        // $docus_after_now = Docu::join('transactions', 'docus.id', '=', 'transactions.docu_id')
        // ->where([
        //     ['transactions.recipient', Auth::user()->id],
        //     ['is_accepted', 0],
        //     ['final_action_date', '>', Carbon::now()]
        // ])
        // ->select('docus.*')
        // ->with('statuscode')
        // ->orderBy('is_rush', 'desc')
        // ->orderBy('final_action_date', 'desc')
        // ->get();

        // $docus_before_now = Docu::join('transactions', 'docus.id', '=', 'transactions.docu_id')
        // ->where([
        //     ['transactions.recipient', Auth::user()->id],
        //     ['is_accepted', 0],
        //     ['final_action_date', '<', Carbon::now()]
        // ])
        // ->select('docus.*')
        // ->with('statuscode')
        // ->orderBy('is_rush', 'desc')
        // ->orderBy('final_action_date', 'desc')
        // ->get();
        
        // $docus = $docus_after_now->merge($docus_before_now);

        $docus= Docu::join('transactions', 'docus.id', '=', 'transactions.docu_id')
        ->where([
            ['transactions.recipient', Auth::user()->id],
            ['is_accepted', 0],
        ])
        ->select('docus.*')
        ->with('statuscode')
        ->orderBy('is_rush', 'desc')
        ->orderBy('final_action_date', 'desc')
        ->get();
        return view('home', compact('title', 'docus'));
    }

    public function archived()
    {
        $title = "Archived Documents";

        // $docus_after_now = Docu::onlyTrashed()
        // ->where('final_action_date', '>', Carbon::now())
        // ->orderBy('is_rush', 'desc')
        // ->orderBy('final_action_date', 'desc')
        // ->with('statuscode')
        // ->get();
        // $docus_before_now = Docu::onlyTrashed()
        // ->where('final_action_date', '<', Carbon::now())
        // ->orderBy('is_rush', 'desc')
        // ->orderBy('final_action_date', 'desc')
        // ->with('statuscode')
        // ->get();

        // $docus = $docus_after_now->merge($docus_before_now);

        $docus = Docu::onlyTrashed()
        ->orderBy('deleted_at', 'desc')
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
