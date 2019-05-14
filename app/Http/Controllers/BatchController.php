<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Docu;
use App\Transaction;
use DB;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;
use Auth;
use Validator;
use Illuminate\Validation\Rule;

class BatchController extends Controller
{
    public function __construct(Docu $docu, Transaction $transaction){
        $this->middleware('auth');
        $this->docu = $docu;
        $this->transaction = $transaction;
    }

    public function index()
    {
        $title = 'Batch Adding of Documents';
        return view('batch.batch', compact('title'));
    }

    public function add(Request $request)
    {
        if($request->hasFile('batch_upload')){
            if($request->file('batch_upload')->getClientOriginalExtension() == 'xlsx'){
                $file = $request->file('batch_upload')->getRealPath();       
                $data = \Excel::selectSheetsByIndex(0)->load($file, function($reader){
                })->get();
                if(!empty($data) && $data->count()){
                    $ref_num_holder = array();
                    DB::beginTransaction();
                    foreach($data as $row){
                        try{
                            $DataFromExcel = array();

                            $DataFromExcel['creator'] = Auth::user()->id;

                            $DataFromExcel['department'] = Auth::user()->department->id;
                            
                            if(strtolower($row->is_it_rush) == 'yes'){
                                $rush = 1;
                            }
                            else if(strtolower($row->is_it_rush) == 'no'){
                                $rush = 0;
                            }
                            else{
                                $rush = null;
                            }
                            $DataFromExcel['is_rush'] = $rush;

                            $DataFromExcel['iso'] = $row->iso_number;

                            if(strtolower($row->is_it_confidential) == 'yes'){
                                $confidential = 1;
                            }
                            else if(strtolower($row->is_it_confidential) == 'no'){
                                $confidential = 0;
                            }
                            else{
                                $confidential = null;
                            }
                            $DataFromExcel['confidentiality'] = $confidential;

                            $DataFromExcel['complexity'] = ucfirst(strtolower($row->simple_or_complex));

                            $DataFromExcel['sender_name'] = $row->sender;

                            $DataFromExcel['sender_address'] = $row->sender_address;

                            $DataFromExcel['typeOfDocu'] = ucfirst(strtolower($row->type_of_document));
                            
                            if($row->final_action_date == ""){
                                $DataFromExcel['final_action_date'] = null;
                            }
                            else{
                                $DataFromExcel['final_action_date'] = $row->final_action_date->format('Y-m-d');
                            }

                            $DataFromExcel['subject'] = $row->subject;
                            
                            if(Auth::user()->department->id == 15){
                                $DataFromExcel['route_to'] = $row->route_to;
                            }
                            else{
                                $DataFromExcel['route_to'] = \App\User::find(1)->username;
                            }
                            
                            
                            $DataFromExcel['remarks'] = $row->remarks;

                            if($row->deadline_for_routing_info == ""){
                                $DataFromExcel['deadline'] = null;
                            }
                            else{
                                $DataFromExcel['deadline'] = $row->deadline_for_routing_info->format('Y-m-d');
                            }
                            
                            $messages = [
                                'is_rush.required' => 'The column "Is it rush" must not be empty',
                                'is_rush.boolean' => 'The input must be either Yes or No',
                                'confidentiality.required' => 'The column "Is it confidential" must not be empty',
                                'confidentiality.boolean' => 'The input must be either Yes or No',
                                'complexity.required' => 'The column "Simple or Complex" must not be empty',
                                'complexity.in' => 'The input must be either Simple or Complex',
                                'sender_name.required' => 'The column "Sender" must not be empty',
                                'sender_address.required' => 'The column "Sender Address" must not be empty',
                                'subject.required' => 'The column "Subject" must not be empty',
                                'typeOfDocu.required' => 'The column "Type of Document" must not be empty',
                                'typeOfDocu.exists' => 'No type of document found in the database',
                                'final_action_date.required' => 'The column "Final Action Date" must not be empty',
                                'final_action_date.date_format' => 'Follow the following format for entering a date
                                    value : "YYYY-MM-DD"',
                                'route_to.required' => 'The column "Route To" must not be empty',
                                'remarks.required' => 'The column "Remarks" must not be empty',
                                'deadline.required' => 'The column "Deadline for Routing Info" must not be empty',
                                'deadline.date_format' => 'Follow the following format for entering a date
                                    value : "YYYY-MM-DD"',
                            ];
                            $validator = Validator::make($DataFromExcel,[
                                'is_rush' => 'required|boolean',
                                'iso' => 'nullable',
                                'confidentiality' => 'required|boolean',
                                'complexity' => [
                                    'required',
                                    Rule::in(['Simple', 'Complex']),
                                ],
                                'sender_name' => 'required',
                                'sender_address' => 'required',
                                'subject' => 'required',
                                'typeOfDocu' => 'required|exists:type_of_docus,docu_type',
                                'final_action_date' => 'required|date_format:"Y-m-d"',
                                'route_to' => 'required',
                                'remarks' => 'required',
                                'deadline' => 'required|date_format:"Y-m-d"|before_or_equal:final_action_date',
                            ], $messages);

                            if($validator->fails()){
                                return redirect()->route('batch')->withErrors($validator)->withInput();
                            }
                            else{
                                $docu_saved = $this->docu->batchSave($DataFromExcel);
                                $this->transaction->makeBatchTransaction($DataFromExcel, $docu_saved);
                                array_push($ref_num_holder, $docu_saved->reference_number);
                            }
                        }

                        catch(\Exception $e){
                            DB::rollback();
                            throw $e;
                        }
                    }
                    
                    $qrcode = new BaconQrCodeGenerator;
                    foreach($ref_num_holder as $ref_num){
                        $qrcode->size(100)
                        ->encoding('UTF-8')
                        ->format('png')
                        ->generate($ref_num, '../public/storage/qrcodes/' . $ref_num . '.png');
                    }

                    DB::commit();
                    $request->session()->flash('success', count($ref_num_holder) . ' Documents Created');
                    return redirect()->route('home');
                }
            }
            else{
                $request
                ->session()
                ->flash('error', 'File not an excel file! Download the copy and use it for 
                uploading multiple records');
                return back();
            }
        }
        else{
            $request
            ->session()
            ->flash('error', 'File not found! Upload the recommended excel file!');
            return back();
        }
    }
}
