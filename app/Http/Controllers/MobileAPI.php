<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Docu;
use App\Department;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Notifications\PasswordChange;

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
                    'message' => 'Wrong username/password input. Try again!'
                ];
                $code = 404;
            }
        }
        else{
            $out = [
                'message' => 'Wrong username/password input. Try again!'
            ];
            $code = 404;
        }
        return response()->json($out, $code);
    }

    public function request_for_change_password(Request $request){
        $user = User::where('username', $request->input('username'))->first();
        if(is_null($user)){
            $out = [
                'message' => 'Your search did not return any results. Please try again with other information.'
            ];

            return response()->json($out, 404);
        }
        
        $admin = User::where([
                ['role_id', 1],
                ['department_id', 9]
                ])
                ->first();
        
        $admin->notify(new PasswordChange($user));

        $out = [
            'message' => "Password reset request has been sent! Please wait for your admin's update"
        ];

        return response()->json($out, 200);
    }

    public function all_docu()
    {
        $docus = Docu::orderBy('is_rush', 'desc')
        ->orderBy('docus.final_action_date' , 'desc')
        ->join('statuscode', 'docus.statuscode_id', '=', 'statuscode.id')
        ->join('users', 'docus.creator', '=', 'users.id')
        ->select('docus.id as docu_id', 'statuscode.status', 'users.name as username' ,
                'reference_number', 'final_action_date', 'docus.subject', 'is_rush')
        ->get();

        return response()->json($docus, 200);
    }

    public function my_docu($id)
    {
        $docus = Docu::orderBy('docus.created_at' , 'asc')
        ->join('statuscode', 'docus.statuscode_id', '=', 'statuscode.id')
        ->join('users', 'docus.creator', '=', 'users.id')
        ->select('docus.id as docu_id', 'statuscode.status', 'users.name as username' ,
                'reference_number', 'final_action_date', 'docus.subject', 'is_rush')
        ->where('creator', $id)
        ->get();

        return response()->json($docus, 200);
    }

    public function accepted()
    {
        $docus = Docu::withTrashed()
        ->whereNotNull('approved_at')
        ->orderBy('final_action_date', 'desc')
        ->join('statuscode', 'docus.statuscode_id', '=', 'statuscode.id')
        ->join('users', 'docus.creator', '=', 'users.id')
        ->select('docus.id as docu_id', 'statuscode.status', 'users.name as username' ,
                'reference_number', 'final_action_date', 'docus.subject', 'is_rush')
        ->get();

        return response()->json($docus, 200);
    }

    public function inactive()
    {
        $docus = Docu::orderBy('is_rush', 'desc')
        ->orderBy('final_action_date', 'asc')
        ->where('final_action_date', '<' , Carbon::now())
        ->select('docus.id as docu_id', 'statuscode.status', 'users.name as username' ,
                'reference_number', 'final_action_date', 'docus.subject', 'is_rush')
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
        ->select('docus.id as docu_id', 'statuscode.status', 'users.name as username' ,
                'reference_number', 'final_action_date', 'docus.subject', 'is_rush')
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
        ->select('docus.id as docu_id', 'statuscode.status', 'users.name as username' ,
                'reference_number', 'final_action_date', 'docus.subject', 'is_rush')
        ->orderBy('deleted_at', 'desc')
        ->get();

        return response()->json($docus, 200);
    }

    public function show(Request $request)
    {
        $docu_info = Docu::withTrashed()
        // ->with(['transaction.from', 'transaction.to', 'transaction.fromLoc', 'transaction.toLoc'])
        ->with('transaction')
        ->where('reference_number', $request->input('reference_number'))
        ->first();

        if(is_null($docu_info)){
            return response()->json($docu_info, 404);
        }

        if($docu_info->confidentiality == 1){
            if($request->input('userRoleID') != 1){
                if(is_null($docu_info->transaction->where('recipient', $request->input('userID'))->last())){
                    $out = [
                        'message' => 'This record is for "admin" role only'
                    ];

                    return response()->json($out,404);
                }
            }
        }

        $transactions_of_docu = $docu_info->transaction;

        foreach($transactions_of_docu as $transaction){
            $transaction->location = $transaction->fromLoc->acronym;
            $transaction->in_charge = $transaction->from->name;
            $transaction->route = $transaction->toLoc->acronym;
            $transaction->recipient = $transaction->to->name;
        }

        Collection::macro('pluckTransactions', function($data){
            return $this->map(function($item) use ($data){
                $list = [];
                foreach ($data as $key) {
                    if($key == "created_at"){
                        $list[$key] = data_get($item, $key)->toDateTimeString();
                    }
                    else{
                        $list[$key] = data_get($item, $key);
                    }
                }
                return $list;
            }, new static);
        });

        $new_transaction_record = $transactions_of_docu->pluckTransactions(['location', 
        'in_charge', 'route', 'recipient', 'remarks', 'is_received', 'date_deadline', 'comment',
        'to_continue', 'seen_at', 'received_at', 'sent_at', 'has_sent', 'created_at']);

        unset($docu_info->transaction);

        $docu_info->department = $docu_info->department->name;
        $docu_info->creator = $docu_info->user->name;
        $docu_info->docuType = $docu_info->typeOfDocu->docu_type;
        $docu_info->status = $docu_info->statuscode->status;
        
        $sender_address = $docu_info->sender_address;
        $dept = Department::where('name', $sender_address)->first();

        if(is_null($dept)){
            $docu_info->source = "External";
        }
        else{
            $docu_info->source = "Internal";
        }

        $docu_info->transaction = $new_transaction_record;

        unset($docu_info->department);
        unset($docu_info->department_id);
        unset($docu_info->user);
        unset($docu_info->statuscode_id);
        unset($docu_info->type_of_docu_id);
        unset($docu_info->type_of_docu);
        unset($docu_info->statuscode);
        

        return response()->json($docu_info, 200);
    }

    public function details(Request $request)
    {
        $docu_details = Docu::withTrashed()
        ->where('reference_number', $request->input('reference_number'))
        ->first();

        if(is_null($docu_details)){
            return response()->json($docu_details, 404);
        }

        return response()->json($docu_details, 200);
    }
}
