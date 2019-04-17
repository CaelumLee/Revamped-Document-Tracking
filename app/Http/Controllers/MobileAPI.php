<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Docu;
use Carbon\Carbon;

class MobileAPI extends Controller
{
    public function login(Request $request){
        $user = User::where('username', $request->input('username'))->first();
        if($user){
            if(Hash::check($request->input('password'), $user->password)){
                $out = $user;
                $code = 200;
            }
            else{
                $out = [
                    'message' => 'Wrong password'
                ];
                $code = 404;
            }
        }
        else{
            $out = [
                'message' => 'No user found'
            ];
            $code = 404;
        }
        return response()->json($out, $code);
    }

    public function all_docu()
    {
        $docus = Docu::orderBy('docus.created_at' , 'asc')
        ->join('statuscode', 'docus.statuscode_id', '=', 'statuscode.id')
        ->join('users', 'docus.creator', '=', 'users.id')
        ->select('docus.id as docu_id', 'statuscode.status', 'users.username' ,
                'reference_number', 'final_action_date', 'docus.subject')
        ->get();

        return response()->json($docus, 200);
    }

    public function my_docu($id)
    {
        $docus = Docu::orderBy('docus.created_at' , 'asc')
        ->join('statuscode', 'docus.statuscode_id', '=', 'statuscode.id')
        ->join('users', 'docus.creator', '=', 'users.id')
        ->select('docus.id as docu_id', 'statuscode.status', 'users.username' ,
                'reference_number', 'final_action_date', 'docus.subject')
        ->where('creator', $id)
        ->get();

        return response()->json($docus, 200);
    }

    public function accepted()
    {
        $docus = Docu::withTrashed()
        ->whereNotNull('approved_at')
        ->orderBy('docus.created_at' , 'asc')
        ->join('statuscode', 'docus.statuscode_id', '=', 'statuscode.id')
        ->join('users', 'docus.creator', '=', 'users.id')
        ->select('docus.id as docu_id', 'statuscode.status', 'users.username' ,
                'reference_number', 'final_action_date', 'docus.subject')
        ->get();

        return response()->json($docus, 200);
    }

    public function inactive()
    {
        $docus = Docu::orderBy('is_rush', 'desc')
        ->orderBy('final_action_date', 'asc')
        ->where('final_action_date', '<' , Carbon::now())
        ->select('docus.id as docu_id', 'statuscode.status', 'users.username' ,
                'reference_number', 'final_action_date', 'docus.subject')
        ->join('statuscode', 'docus.statuscode_id', '=', 'statuscode.id')
        ->join('users', 'docus.creator', '=', 'users.id')
        ->get();

        return response()->json($docus, 200);
    }

    public function received($id)
    {
        $docu_ids= Docu::join('transactions', 'docus.id', '=', 'transactions.docu_id')
        ->where([
            ['transactions.recipient', $id],
            ['transactions.is_received', 0],
            ['deleted_at', null]
        ])
        ->select('docus.id')
        ->groupBy('docus.id')
        ->get();

        $docus = Docu::whereIn('docus.id', $docu_ids)
        ->join('statuscode', 'docus.statuscode_id', '=', 'statuscode.id')
        ->join('users', 'docus.creator', '=', 'users.id')
        ->select('docus.id as docu_id', 'statuscode.status', 'users.username' ,
                'reference_number', 'final_action_date', 'docus.subject')
        ->orderBy('is_rush', 'desc')
        ->orderBy('final_action_date', 'desc')
        ->get();

        return response()->json($docus, 200);
    }

    public function archived()
    {
        $docus = Docu::onlyTrashed()
        ->join('statuscode', 'docus.statuscode_id', '=', 'statuscode.id')
        ->join('users', 'docus.creator', '=', 'users.id')
        ->select('docus.id as docu_id', 'statuscode.status', 'users.username' ,
                'reference_number', 'final_action_date', 'docus.subject')
        ->orderBy('deleted_at', 'desc')
        ->get();

        return response()->json($docus, 200);
    }

    public function show(Request $request)
    {
        $docu_info = Docu::withTrashed()
        ->with(['transaction.from', 'transaction.to'])
        ->find($request->input('id'));

        return response()->json($docu_info, 200);
    }
}
