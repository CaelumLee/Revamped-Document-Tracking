<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Docu;
use App\Notifications\SendDocu;
use App\Notifications\DeclineNotif;
use App\Notifications\AcceptNotif;
use Carbon\Carbon;

class Transaction extends Model
{
    //Table Name
    protected $table = 'transactions';
    //Primary Key
    public $primaryKey = 'id';

    public $timestamps = true;

    public function docu()
    {
        return $this->belongsTo('App\Docu');
    }

    public function from()
    {
        return $this->belongsTo('App\User', 'in_charge');
    }

    public function to()
    {
        return $this->belongsTo('App\User', 'recipient');
    }

    public function fromLoc()
    {
        return $this->belongsTo('App\Department', 'location');
    }

    public function toLoc()
    {
        return $this->belongsTo('App\Department', 'route');
    }

    public function makeManualTransaction($request, $docu_data)
    {
        $outData = [];
        $recipients = explode(',' , $request->input('hidden_recipients'));
        $outData['recipients'] = $recipients;
        $outData['docu_id'] = $docu_data->id;
        $outData['location'] = Auth::user()->department->id;
        $outData['in_charge'] = Auth::user()->id;
        $outData['remarks'] = $request->input('remarks');
        $d = Carbon::createFromFormat('Y-m-d H:i', $request->input('date_deadline') . ' 23:59');
        $outData['date_deadline'] = $d->toDateTimeString();

        $transaction_instance = new Transaction;
        $out = $transaction_instance->insert($outData, $docu_data);

    }

    public function makeBatchTransaction($DataFromExcel, $docu_data)
    {
        $outData = [];
        $recipients = explode(',' , $DataFromExcel['route_to']);
        $outData['recipients'] = $recipients;
        $outData['docu_id'] = $docu_data->id;
        $outData['location'] = Auth::user()->department->id;
        $outData['in_charge'] = Auth::user()->id;
        $outData['remarks'] = $DataFromExcel['remarks'];
        $d = Carbon::createFromFormat('Y-m-d H:i', $DataFromExcel['deadline'] . ' 23:59');
        $outData['date_deadline'] = $d->toDateTimeString();

        $transaction_instance = new Transaction;
        $out = $transaction_instance->insert($outData, $docu_data);
    }

    public function makeTransactionUponDisapprove($request, $docu_data)
    {
        $outData = [];
        $recipients = explode(',' , $request->input('latest_sender_username'));
        $outData['recipients'] = $recipients;
        $outData['docu_id'] = $docu_data->id;
        $outData['location'] = Auth::user()->department->id;
        $outData['in_charge'] = Auth::user()->id;
        $outData['remarks'] = $request->input('remarks');
        $d = $docu_data->final_action_date;
        $outData['date_deadline'] = $d;
        $transaction_instance = new Transaction;
        $out = $transaction_instance->insert($outData, $docu_data);

    }

    public function makeTransactionUponApprove($request, $docu_data)
    {
        $outData = [];
        $recipients = User::find($docu_data->creator)->username;
        $outData['recipients'] = $recipients;
        $outData['docu_id'] = $docu_data->id;
        $outData['location'] = Auth::user()->department->id;
        $outData['in_charge'] = Auth::user()->id;
        $outData['remarks'] = $request->input('remarks');
        $d = $docu_data->final_action_date;
        $outData['date_deadline'] = $d;
        $transaction_instance = new Transaction;
        $out = $transaction_instance->insert($outData, $docu_data);
    }

    public function insert($data, $docu_instance)
    {
        foreach($data['recipients'] as $recipient){
            $user = User::where('username', $recipient)->first();
            if($user != null){
                $transaction_instance = new Transaction;
                $transaction_instance->docu_id = $data['docu_id'];
                $transaction_instance->location = $data['location'];
                $transaction_instance->in_charge = $data['in_charge'];
                $transaction_instance->route = $user->department->id;
                $transaction_instance->recipient = $user->id;
                $transaction_instance->remarks = $data['remarks'];
                $transaction_instance->date_deadline = $data['date_deadline'];
                $transaction_instance->save();
                if($docu_instance->statuscode_id == 5){
                    $user->notify(new SendDocu($docu_instance));
                }
                elseif($docu_instance->statuscode_id == 2){
                    $user->notify(new DeclineNotif($docu_instance));
                }   
                elseif($docu_instance->statuscode_id == 1){
                    $user->notify(new AcceptNotif($docu_instance));
                }
            }
        }
    }

    public function receive($request)
    {
        $transaction_to_update = $this->find($request->input('transaction_id'));
        $transaction_to_update->comment = $request->input('comment');
        $transaction_to_update->to_continue = $request->input('to_continue');
        $transaction_to_update->is_received = 1;
        $transaction_to_update->received_at = date('Y-m-d H:i:s');
        $transaction_to_update->save();

        //if disapproved and received, change to under review
        $docu_to_under_review = Docu::find($transaction_to_update->docu_id);
        if($docu_to_under_review->statuscode_id == 2){
            $docu_to_under_review->statuscode_id = 3;
            $docu_to_under_review->save();
        }
    }

}
