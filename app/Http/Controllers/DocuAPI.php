<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Docu as DocuResource;
use App\Docu;
use App\Department;
use DB;

class DocuAPI extends Controller
{
    public function index()
    {
        $docus = Docu::withTrashed()
        ->select('department_id', DB::raw('count(*) as record_count'))
        ->groupBy('department_id')
        ->get();

        return response()->json($docus);
    }

    public function deptlist()
    {
        $dept = Department::select('acronym')
        ->orderBy('id')
        ->get();

        return response()->json($dept);
    }
}
