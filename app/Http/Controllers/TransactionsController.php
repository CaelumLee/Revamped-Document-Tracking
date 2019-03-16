<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Transaction;
use App\Docu;
use Carbon\Carbon;

class TransactionsController extends Controller
{
    public function __construct(Transaction $transaction, Docu $docu)
    {
        $this->middleware('auth');
        $this->transaction = $transaction;
        $this->docu = $docu;
    }

    public function receive_docu(Request $request)
    {
        $this->validate($request,[
            'to_continue' => 'required',
        ]);
    
        $this->transaction->receive($request);
        
        $request->session()->flash('success', 'Document received!');

        return redirect()->route('docu.show', ['id' => $request->input('docu_id')]);
    }

    public function send_docu(Request $request)
    {
        $this->validate($request,[
            'hidden_recipients' => 'required',
            'remarks' => 'required',
            'date_deadline' => 'required',
        ]);

        DB::beginTransaction();
            try{
                $docu_instance = $this->docu->find($request->input('docu_id'));
                $this->transaction->makeManualTransaction($request, $docu_instance);
                
                $transaction_instance = $this->transaction->find($request->input('transaction_id'));
                $transaction_instance->has_sent = 1;
                $transaction_instance->sent_at = date('Y-m-d H:i:s');
                $transaction_instance->save();

                $request->session()->flash('success', 'Document ' . $docu_instance->reference_number 
                . ' sent!');

                DB::commit();
            }
            catch(\Exception $e){
                DB::rollback();
                throw $e;
            }
        return redirect()->route('docu.show', ['id' => $request->input('docu_id')]);     
    }

    public function routeinfo($id)
    {
        $transactions = $this->transaction
        ->where([
            ['docu_id', $id],
            ['is_received', 1]
        ])
        ->get();

        $data = [
            'transactions' => $transactions,
            'id' => $id //docu_id
        ];
        
        return view('docus.routeInfo', compact('data'));
    }

    public function responses($id)
    {
        $transactions = $this->transaction
        ->where([
            ['docu_id', $id]
        ])
        ->get();

        $data = [
            'transactions' => $transactions,
            'id' => $id //docu_id
        ];

        return view('docus.responses', compact('data'));
    }

    public function update_date_deadline(Request $request)
    {
        $this->validate($request, [
            'date_deadline' => 'required|date_format:"Y-m-d"'
        ]);

        $transaction_to_change_date = $this->transaction->find($request->input('transaction_id'));
        $d = Carbon::createFromFormat('Y-m-d H:i', $request->input('date_deadline') . ' 23:59');
        $transaction_to_change_date->date_deadline = $d->toDateTimeString();
        $transaction_to_change_date->save();

        $request->session()->flash('success', 'Date deadline updated!');

        return redirect()->route('docu.show', ['id' => $request->input('docu_id')]);     
    }
}
