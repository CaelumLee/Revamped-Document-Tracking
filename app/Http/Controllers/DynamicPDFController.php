<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Docu;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use DB;
use PDF;

class DynamicPDFController extends Controller
{
    
    function get_docu_data($docu_id)
    {
     $docu_data = Docu::withTrashed()->with('transaction')->find($docu_id);
     return $docu_data;
    }

 
    function pdf($docu_id) 
    {
     $pdf = \App::make('dompdf.wrapper');
     $pdf->loadHTML($this->convert_docu_data_to_html($docu_id));
    return $pdf->stream();
    }

    function convert_docu_data_to_html($docu_id)
    {
        $docu_data = $this->get_docu_data($docu_id);
        $route_info = $docu_data->transaction->reject(function($item){
            return $item->is_received == 0;
        });
        
        if($docu_data->is_rush){
            $checked = 'checked';
        }
        else{
            $checked = '';
        }
        $output = '<!DOCTYPE html>
        <html>
        <head>
            <title> Reference Number: '. $docu_data->reference_number .'</title>
        </head>
        <body>
            
        
            <style>
            .logo
            {
                float: left;
                display: block;
                padding-right: 10px;
                width: 100px;
            }
        
            .comfortaa
            {
                font-family: "Comfortaa";
            }
        
            .roboto
            {
                font-family: "Roboto";
            }
        
            .heading
            {
                width: 100%;
            }
            .rush
            {
                border: 2px solid #0d47a1;
                padding: 20px;
                text-align: center;
                width: 100px;
                margin-top: 15px;
                border-radius: 8px;
            }
        
            .blue-text
            {
                color: #0d47a1;
            }
        
            .blue
            {
                background: #0d47a1 !important;
                color: white;
            }
        
            .lighter-blue
            {
                background: #0e67c2;
            }
        
            {
                padding: 5px;
        
            }
        
            .slip
            {
                border: 2px solid #0d47a1;
                border-collapse: collapse;
                width: 100%;
            }
        
            .slip td
            {
                border: 2px solid #0d47a1;
                padding: 8px;
            }
        
            .slip th
            {
                border: 2px solid #0d47a1;
                background-color: #3283ce;
                text-align: left;
                padding: 8px;
                color: white;
                font-family: comfortaa;
        
            }
        
            b
            {
                font-family: Comfortaa;
                color: #0d47a1;
            }
        </style>
        <table class="heading">
            <tr>
                <td>
                    <img src="https://i.imgur.com/qNfnFn3.png" class="logo">
                    <h3 class="comfortaa blue-text">PASIG RIVER REHABILITATION COMMISION</h3>
                    <p class="roboto">DOCUMENT ROUTING SLIP</p></td>
                <td>
                    <img src="' . url("/storage/qrcodes/" . $docu_data->reference_number . ".png") 
                    . '" class="logo">
                </td>
                <td style="float:right;">
                    <div class="roboto rush">
                        <label>
                            <input type="checkbox"'. $checked .'>
                            <span class="checkmark"></span>
                            RUSH
                        </label>
                    </div>
                </td>
            </tr>
        </table>
        <br><br>
            <table class="roboto slip">
                <tr>
                    <td colspan="2" ><b>From:</b> <br>	&nbsp;	&nbsp;'. $docu_data->user->name .'</td>
                    <td colspan="2"><b>Date Received:</b>
                        <br> 	&nbsp;	&nbsp;'. Carbon::parse($docu_data->created_at)->format('Y-m-d g:i:s A') .'</td>
                        <td colspan="2"><b>Reference Number:</b> <br>
                            &nbsp;	&nbsp;'.$docu_data->reference_number.'</td>
                        </tr>
        
                        <tr>
                            <td colspan="6"><b>Subject:</b>
                                <br>	&nbsp;	&nbsp; '.$docu_data->subject.'</td>
                            </tr>
        
                            <tr>
                                <th colspan="6" class="blue" style="padding: 10px; text-align: center !important;">ROUTING SLIP</th>
                            </tr>
        
                            <tr>
                                <th >FROM</th>
                                <th >TO</th>
                                <th >DATE</th>
                                <th >INSTRUCTIONS/REMARKS</th>
                                <th >DEADLINE</th>
                                <th >DATE COMPLIED</th>
                            </tr>';
                            
                            if($route_info->isNotEmpty()){
                                foreach($route_info as $info){
                                    $output .= '<tr>
                                        <td>' . $info->from->username . '</td>
                                        <td>' . $info->to->username . '</td>
                                        <td>' . Carbon::parse($info->created_at)->format('Y-m-d H:i:s a') . '</td>
                                        <td>' . $info->remarks . '</td>
                                        <td>' . Carbon::parse($info->date_deadline)->format('Y-m-d H:i:s a') . '</td>
                                        <td>' . Carbon::parse($info->updated_at)->format('Y-m-d H:i:s a') . '</td>
                                    </tr>';
                                }
                            }                        
                            else{
                                $output .= '<tr> 
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>No Routing Info</td>
                                    <td></td>
                                    <td></td>
                                </tr>';
                            }    

                        $output .= '</table>'.
                        '<footer>' . $docu_data->iso_code .'</footer></body></html>';
        return $output;
    }
}
