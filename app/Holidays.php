<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{
    public $timestamps = false;

    public function edit($data)
    {   
        $hol = $this->find($data->input('holiday_id'));
        $date = explode('-', $data->input('holiday_date'));
        $hol->holiday_date = $date[1] . '-' . $date[2];
        $hol->holiday_name = $data->input('holiday_name');
        $hol->save();

        return $hol;
    }

    public function add($data)
    {
        $new_hol = new Holidays;
        $date = explode('-', $data->input('new_holiday_date'));
        $new_hol->holiday_date = $date[1] . '-' . $date[2];
        $new_hol->holiday_name = $data->input('new_holiday_name');
        $new_hol->save();
    }
}
