<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Docu;
use App\User;
use App\Statuscode;
use App\Holidays;
use App\TypeOfDocu;
use App\Transaction;
use App\FileUploads;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;
use DB;
use Auth;

class DocuController extends Controller
{

    public function __construct(Statuscode $statuses, User $user,
    Docu $docu, Holidays $holidays, TypeOfDocu $type_of_docu, Transaction $transaction,
    FileUploads $files)
    {
        $this->middleware('auth');
        $this->statuses = $statuses;
        $this->user = $user;
        $this->docu = $docu;
        $this->holidays = $holidays;
        $this->type = $type_of_docu;
        $this->transaction = $transaction;
        $this->files = $files;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'My Documents';
        $docus = $this->docu->orderBy('created_at' , 'asc')
        ->where('creator', Auth::user()->id)
        ->get();
        return view('home', compact('docus', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $docu_type = $this->type->where('is_disabled', '0')->pluck('docu_type', 'id');
        $holidays_list = $this->holidays->all();
        $users = $this->user->all()
        ->pluck('username');
        $data = [
            'docu_type' => $docu_type,
            'holidays_list' => $holidays_list,
            'users' => $users
        ];
        return view('docus.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'hidden_recipients.required' => 'Route to/CC field must not be empty'
        ];

        $this->validate($request,[
            'typeOfDocu' => 'required',
            'rushed' => 'required',
            'confidential' => 'required',
            'complexity' => 'required',
            'subject' => 'required',
            'sender_add' => 'required',
            'hidden_recipients' => 'required',
            'remarks' => 'required',
            'final_action_date' => 'required',
            'date_deadline' => 'required|before_or_equal:final_action_date',
        ], $messages);

        DB::beginTransaction();
            try{
                $qrcode = new BaconQrCodeGenerator;
                $docu_saved = $this->docu->singleSave($request);
                $this->transaction->makeManualTransaction($request, $docu_saved);
                
                $qrcode->size(100)
                ->encoding('UTF-8')
                ->format('png')
                ->generate($docu_saved->reference_number, '../public/storage/qrcodes/' . $docu_saved->reference_number . '.png');

                $request->session()->flash('success', 'Document ' . $docu_saved->reference_number . ' Created');

                DB::commit();
            }
            catch(\Exception $e){
                DB::rollback();
                throw $e;
            }

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $docu = Docu::withTrashed()
        ->with('Transaction')
        ->findOrFail($id);
        
        if($docu->confidentiality == 1){
            if(Auth::user()->role->id != 1){
                if(is_null($docu->transaction->where('recipient', Auth::user()->id)->last())){
                    return back()->withErrors(['This record is for "admin" role only']);
                }
            }
            
            // $user_list = $this->user
            // ->where('role_id', 1)
            // ->whereNotIn('users.id', [Auth::user()->id])
            // ->get(['username']);
        }
        // else{
            $user_list = $this->user
            ->whereNotIn('users.id', [Auth::user()->id])
            ->get(['username']);
        // }

        //seen
        $docu->transaction
        ->sortByDesc('created_at')
        ->map(function($item){
            if($item->recipient == Auth::user()->id){
                if($item->seen_at == null){
                    $t = $this->transaction->find($item->id);
                    $t->seen_at = date('Y-m-d H:i:s');
                    $t->save();
                }
            }
        });

        $is_received_collection = $docu->transaction
        ->sortByDesc('created_at')
        ->map(function($item){
            if(($item->is_received == 1 && $item->to_continue == 0) 
            || ($item->is_received == 1 && $item->to_continue == 1 && $item->has_sent == 1)){
                return 1;
            }
            else{
                return 0;
            }
        });

        //checker for approver if all recipients are finished so the approver can
        //decide to approve or not
        $all_received = $is_received_collection->every(function($value){
            return $value == 1;
        });
    
        //lists of holidays
        $holidays_list = $this->holidays->pluck('holiday_date')->toArray();

        //lists of file uploads
        $file_uploads = $this->files->where('docu_id', $id)
        ->get();

        //check if the button of send / receive must be shown
        $last = $docu->transaction->where('recipient', Auth::user()->id)->last();
        if(!is_null($last)){
            if($last->is_received == 0 && $docu->final_action_date >= date('Y-m-d H:i:s')){
                $receive_bool = true;
                $send_bool = false;
            }
            elseif($last->is_received == 1 && $last->has_sent == 0 
            && $last->to_continue == 1 && $docu->final_action_date >= date('Y-m-d H:i:s')){
                $receive_bool = false;
                $send_bool = true;
            }
            else{
                $receive_bool = false;
                $send_bool = false; 
            }
        }
        else{
            $receive_bool = false;
            $send_bool = false; 
        }
        
        $data = [
            'docu' => $docu,
            'holidays_list' => $holidays_list,
            'user_list' => $user_list,
            'file_uploads' => $file_uploads,
            'ready_to_approve' => $all_received,
            'receive_bool' => $receive_bool,
            'send_bool' => $send_bool,
            'latest_route_of_current_user' => $last,
            'latest_route' => $docu->transaction->last()
        ];

        return view('docus.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $docu = $this->docu->withTrashed()
        ->findOrFail($id);
        $docu_type = $this->type->where('is_disabled', '0')->pluck('docu_type', 'id');
        $holidays_list = $this->holidays->all();
        $user_not_including_the_auth_user = $this->user->all()
        ->pluck('username');

        $data = [
            'docu' => $docu,
            'types' => $docu_type,
            'holidays' => $holidays_list,
            'users' => $user_not_including_the_auth_user
        ];

        return view('docus.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'typeOfDocu' => 'required',
            'rushed' => 'required',
            'confidential' => 'required',
            'complexity' => 'required',
            'subject' => 'required',
            'sender' => 'required',
            'sender_add' => 'required',
            'final_action_date' => 'required',
        ]);

        $this->docu->updateDocu($request, $id);
        $request->session()->flash('success', 'Document Updated');
        return redirect()->route("docu.show", ["id" => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $docu = $this->docu->withTrashed()->find($id);    
        if($docu->deleted_at == null){
            $docu->delete();
            $request->session()->flash('success', 'Record ' . $docu->reference_number . 
            ' has been archived');
        }        
        return redirect()->route("archived");
    }

    public function restore(Request $request, $id)
    {
        $docu_to_restore = $this->docu->
        onlyTrashed()
        ->find($id);
        
        $docu_to_restore->restore();

        $request->session()->flash('success', 'Document restored!');

        return redirect()->route("docu.show", ["id" => $id]);
    }

    public function approve(Request $request, $id)
    {
        
        $this->validate($request,[
            'to_approve' => 'required',
            'remarks' => 'required'
        ]);
        
        if($request->input('to_approve') == 0){
            //disapprove
            DB::beginTransaction();
            try{
                $docu = $this->docu->disapprove($id);
                $this->transaction->makeTransactionUponDisapprove($request, $docu);

                $transaction_instance = $this->transaction->find($request->input('transaction_id'));
                $transaction_instance->has_sent = 1;
                $transaction_instance->sent_at = date('Y-m-d H:i:s');
                $transaction_instance->save();

                $request->session()->flash('success', 'Document ' . $docu->reference_number . ' sent');
                DB::commit();

                return redirect()->route("docu.show", ["id" => $id]);
            }
            catch(\Exception $e){
                DB::rollback();
                throw $e;
            }

        }

        else{
            //approve
            DB::beginTransaction();
            try{
                $docu = $this->docu->approve($id);
                $this->transaction->makeTransactionUponApprove($request, $docu);

                $transaction_instance = $this->transaction->find($request->input('transaction_id'));
                $transaction_instance->has_sent = 1;
                $transaction_instance->sent_at = date('Y-m-d H:i:s');
                $transaction_instance->save();

                $request->session()->flash('success', 'Document ' . $docu->reference_number . ' sent');
                DB::commit();

                return redirect()->route("docu.show", ["id" => $id]);
            }
            catch(\Exception $e){
                DB::rollback();
                throw $e;
            }  
        }
    }
}
