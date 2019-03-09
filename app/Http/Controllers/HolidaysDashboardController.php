<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Holidays;

class HolidaysDashboardController extends Controller
{
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
}
