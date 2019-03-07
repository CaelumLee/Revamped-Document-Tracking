<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Notifications\PasswordChange;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    public function index()
    {
        return view('auth.passwords.reset');
    }

    public function findUser(Request $request)
    {
        $messages = [
            'username.exists' => 'No username found'
        ];

        $validator = $this->validate($request,[
            'username' => 'required|exists:users,username'
        ], $messages);

        $user = User::where('username', $request->input('username'))->first();
        
        $admin = User::where([
                ['role_id', 1],
                ['department_id', 9]
                ])
                ->first();
        
        $admin->notify(new PasswordChange($user));
        return view('auth.passwords.wait');

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
