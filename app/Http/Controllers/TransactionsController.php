<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Transaction;
use App\Docu;

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

        $docu_id = $id;

        $data = [
            'transactions' => $transactions,
            'id' => $docu_id
        ];
        
        return view('docus.routeInfo', compact('data'));
    }
}
