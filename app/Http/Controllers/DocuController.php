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
use App\Department;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;
use DB;
use Auth;

class DocuController extends Controller
{

    public function __construct(Statuscode $statuses, User $user,
    Docu $docu, Holidays $holidays, TypeOfDocu $type_of_docu, Transaction $transaction,
    Department $department, FileUploads $files)
    {
        $this->middleware('auth');
        $this->statuses = $statuses;
        $this->user = $user;
        $this->docu = $docu;
        $this->holidays = $holidays;
        $this->type = $type_of_docu;
        $this->transaction = $transaction;
        $this->department = $department;
        $this->files = $files;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $user_not_including_the_auth_user = $this->user->whereNotIn('users.id', [Auth::user()->id])
        ->pluck('username');
        $data = [
            'docu_type' => $docu_type,
            'holidays_list' => $holidays_list,
            'users' => $user_not_including_the_auth_user
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
            'date_deadline' => 'required',
        ]);

        DB::beginTransaction();
            try{
                $qrcode = new BaconQrCodeGenerator;
                $docu_saved = $this->docu->singleSave($request);
                $this->transaction->makeTransaction($request, $docu_saved);
                
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
        $docu = Docu::join('type_of_docus', 'docus.type_of_docu_id', '=', 'type_of_docus.id')
        ->leftJoin('departments', 'docus.sender_address', '=', 'departments.name')
        ->where('docus.id', $id)
        ->get(['source_type', 'subject', 'final_action_date', 'docu_type', 
        'reference_number', 'progress', 'iso_code', 'docus.id', 'confidentiality', 'complexity'])
        ->first();

        $transaction = $this->transaction->where('docu_id', $id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $filtered_ids = $transaction->map(function($item){
            return $item->recipient;
        });

        $user_list = $this->user
        ->whereNotIn('users.id', [Auth::user()->id])
        ->whereNotIn('id', $filtered_ids)
        ->get(['username']);

        $holidays_list = $this->holidays->all();

        $file_uploads = $this->files->where('docu_id', $id)
        ->get();

        $data = [
            'docu' => $docu,
            'transactions' => $transaction,
            'holidays_list' => $holidays_list,
            'user_list' => $user_list,
            'file_uploads' => $file_uploads
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
