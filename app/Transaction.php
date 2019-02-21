<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Notifications\SendDocu;
use Carbon\Carbon;

class Transaction extends Model
{
    //Table Name
    protected $table = 'transactions';
    //Primary Key
    public $primaryKey = 'id';

    public $timestamps = true;

    public function makeTransaction($request, $docu_data)
    {
        $recipients = explode(',' , $request->input('hidden_recipients'));
        foreach($recipients as $recipient){
            $user = User::where('username', $recipient)->first();

            if($user != null){
                $transaction_instance = new Transaction;

                $transaction_instance->location = Auth::user()->department->id;
                $transaction_instance->in_charge = Auth::user()->id;
                $transaction_instance->route = $user->department->id;
                $transaction_instance->recipient = $user->id;
                $transaction_instance->remarks = $request->input('remarks');
                $d = Carbon::createFromFormat('Y-m-d H:i', $request->input('date_deadline') . ' 23:59');
                $transaction_instance->date_deadline = $d->toDateTimeString();
                $transaction_instance->save();
                $user->notify(new SendDocu($docu_data));
            }

        }
    }
}
