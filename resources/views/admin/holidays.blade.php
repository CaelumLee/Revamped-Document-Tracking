@extends('admin.dashboard')
@section('main-content')
<?php
use Carbon\Carbon;
?>
<div class="row">
    <h4>Holidays list for the year {{date('Y')}}</h4>
    <div class="col s12">
        <table class="dashboard-table" id="holidays-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Holiday Date</th>
                    <th>Holiday Name</th>
                    @if(Auth::user()->department->id == 9 && Auth::user()->role->id == 1)
                    <th>Options</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($holiday_list as $holiday)
                    <tr>
                        <td>{{$holiday->id}}</td>
                        <?php 
                            $date = $holiday->holiday_date;
                            $month = explode('-', $date)[0];
                            $day = explode('-', $date)[1];
                            $holiday_date = Carbon::createFromDate(null, $month, $day)->format('M-d-Y');
                        ?>
                        <td>{{$holiday_date}}</td>
                        <td>{{$holiday->holiday_name}}</td>
                        @if(Auth::user()->department->id == 9 && Auth::user()->role->id == 1)
                        <td>
                            <a href='#' class='waves-effect waves-light btn-small btn-flat modal-trigger action-buttons' id='generate_code'><i class='material-icons'>remove_red_eye</i></a>
                            <a href='#' class='waves-effect waves-light waves-light btn-small btn-flat modal-trigger action-buttons' id='generate_code'><i class='material-icons'>edit</i></a>
                            <a href='#' class='waves-effect waves-light waves-light btn-small btn-flat modal-trigger action-buttons' id='generate_code'><i class='material-icons'>delete</i></a>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@push('scripts')
<script>
    $('#holidays-table').DataTable({
        pagingType: "simple",
        dom: '<div>pt',
        pageLength: 15,
        language:{
            paginate:{
                previous: "<i class='material-icons'>chevron_left</i>",
                next: "<i class='material-icons'>chevron_right</i>"
            }
        }
    });
</script>
@endpush
