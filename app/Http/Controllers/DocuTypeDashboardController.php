<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TypeOfDocu;
use DB;

class DocuTypeDashboardController extends Controller
{
    public function __construct(TypeOfDocu $type)
    {
        $this->type = $type;
    }

    public function index()
    {
        $docu_type_list = TypeOfDocu::get();

        return view('admin.docuType', compact('docu_type_list'));
    }

    public function edit(Request $request)
    {
        $this->validate($request,[
            'docu_type' => 'required'
        ]);
        DB::beginTransaction();
        try{
            $docutype_to_update = TypeOfDocu::whereId($request->input('docutype_id'))->first();
            $old_value = $docutype_to_update->docu_type;
            $docutype_to_update->docu_type = $request->input('docu_type');
            $docutype_to_update->save();
            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            throw $e;
        }

        $request->session()->flash('success', 'Document type updated: from ' . 
        $old_value . ' to ' . $request->input('docu_type'));
        
        return redirect()->route('docuType');
    }

    public function disable(Request $request)
    {
        DB::beginTransaction();
        try{
            $docutype_to_disable = TypeOfDocu::find($request->input('docutype_id_disable'));
            $is_disable = $docutype_to_disable->is_disabled;
            if($is_disable == 0){
                $docutype_to_disable->is_disabled = 1;
                $strout = 'disabled';
            }
            else{
                $docutype_to_disable->is_disabled = 0;
                $strout = 'enabled';
            }
            
            $docutype_to_disable->save();
            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            throw $e;
        }

        $request->session()->flash('success', 'Document type ' . 
        $docutype_to_disable->docu_type . ' has been ' . $strout . '!'
        );
        
        return redirect()->route('docuType');
    }

    public function add(Request $request)
    {
        $this->validate($request,[
            'docu_type' => ['required', 'unique:type_of_docus,docu_type'],
        ]);

        $this->type->add($request);
        $request->session()->flash('success', 'New document type added!');

        return redirect()->route('docuType');
    }
}
