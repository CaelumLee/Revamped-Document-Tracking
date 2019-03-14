<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Docu;
use Auth;
use Carbon\Carbon;

class AdminDashboard extends Controller
{
    public function index()
    {
        $starting_month = new Carbon("first day of this month");
        $endinng_month = new Carbon("last day of this month");
        
        $total_created_docu = Docu::withTrashed()
        ->whereBetween("created_at", [$starting_month, $endinng_month])
        ->where('department_id', Auth::user()->department->id)
        ->get()
        ->count();

        $total_accepted_docu = Docu::withTrashed()
        ->whereBetween("created_at", [$starting_month, $endinng_month])
        ->where([
            ['department_id', Auth::user()->department->id],
            //['is_accepted', 1]
        ])
        ->get()
        ->count();

        $total_archived_docu = Docu::onlyTrashed()
        ->whereBetween("created_at", [$starting_month, $endinng_month])
        ->where([
            ['department_id', Auth::user()->department->id],
        ])
        ->get()
        ->count();

        $total_inactive_docu = Docu::where([
            ['final_action_date', '<', date('Y-m-d')],
            ['department_id', Auth::user()->department->id]
        ])
        ->get()
        ->count();

        $data_values = [
            "a" => $total_created_docu,
            "b" => $total_inactive_docu,
            "c" => $total_accepted_docu,
            "d" => $total_archived_docu
        ];

        return view('admin.graphs', compact('data_values'));
    }

}
