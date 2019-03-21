<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Holidays;
use Illuminate\Validation\Rule;
use DB;

class HolidaysDashboardController extends Controller
{

    public function __construct(Holidays $holidays)
    {
        $this->holidays = $holidays;
    }

    public function index()
    {
        $holiday_list = Holidays::get();

        return view('admin.holidays', compact('holiday_list'));
    }

    public function disable(Request $request)
    {
        $holiday = Holidays::find($request->input('holiday_id_disable'));
        $is_disable = $holiday->is_disabled;

        if($is_disable == 0){
            $holiday->is_disabled = 1;
            $strout = 'disabled';
        }
        else{
            $holiday->is_disabled = 0;
            $strout = 'enabled';
        }

        $holiday->save();

        $request->session()->flash('success', 'Date ' . 
        $holiday->holiday_date . ' has been ' . $strout . '!'
        );

        return redirect()->route('holidays');
    }

    public function edit(Request $request)
    {
        $this->validate($request,[
            'holiday_date' => ['required', 'date_format:"Y-m-d"', 
                Rule::unique('holidays', 'holiday_date')->ignore($request->input('holiday_id'))
            ],
            'holiday_name' => ['required', 'string',
                Rule::unique('holidays', 'holiday_name')->ignore($request->input('holiday_id'))
            ],
        ]);

        DB::beginTransaction();
            try{
                $holiday = $this->holidays->edit($request);
                $request->session()->flash('success', 'Details of holidays ' . 
                $holiday->holiday_name . ' has been changed!');
                DB::commit();
            }
            catch(\Exception $e){
                DB::rollback();
                throw $e;
            }
        
        return redirect()->route('holidays');
    }

    public function add(Request $request)
    {
        $this->validate($request,[
            'new_holiday_date' => ['required', 'date_format:"Y-m-d"'],
            'new_holiday_name' => ['required', 'string','unique:holidays,holiday_name'],
        ]);

        $this->holidays->add($request);
        $request->session()->flash('success', 'New holiday added!');

        return redirect()->route('holidays');
    }
}
