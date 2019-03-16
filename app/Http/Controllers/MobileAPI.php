<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Docu;

class MobileAPI extends Controller
{
    public function login(Request $request){
        $user = User::where('username', $request->input('username'))->first();
        if($user){
            if(Hash::check($request->input('password'), $user->password)){
                $out = $user;
            }
            else{
                $out = [
                    'message' => 'Wrong password'
                ];
            }
        }
        else{
            $out = [
                'message' => 'No user found'
            ];
        }
        return json_encode($out);
    }

    public function all_docu(){
        $docus = $docus = Docu::orderBy('is_rush', 'desc')
        ->orderBy('final_action_date', 'desc')
        ->with('statuscode')
        ->get();

        return json_encode($docus);
    }
}
