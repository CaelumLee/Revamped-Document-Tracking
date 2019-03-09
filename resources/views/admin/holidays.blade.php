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
                    <th>Disabled</th>
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
                        @if($holiday->is_disabled == 0)
                        <td>No</td>
                        @else
                        <td>Yes</td>
                        @endif
                        @if(Auth::user()->department->id == 9 && Auth::user()->role->id == 1)
                        <td>
                            <a href='#' class='waves-effect waves-light waves-light btn-small btn-flat modal-trigger action-buttons'><i class='material-icons'>edit</i></a>
                            <a href='#disable' class='waves-effect waves-light waves-light btn-small btn-flat modal-trigger action-buttons disable'
                            data-id = "{{$holiday->id}}" data-is_disabled = "{{$holiday->is_disabled}}" data-date = "{{$holiday->holiday_date}}">
                                <i class='material-icons'>do_not_disturb</i>
                            </a>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="disable" class="modal">
    <div class="modal-content">
        <h4 id="title-disable-placeholder">...</h4>
        {!!Form::open(['action' => ['HolidaysDashboardController@disable'], 'method' => 'POST'])!!}
        <p id="text-holder">Are you sure you want to this document?</p>
        <input type="hidden" id="holiday_id_disable" name = "holiday_id_disable" value = "">
    </div>

    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">No</a>
        {{Form::submit('Yes', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

@stop

@push('scripts')
<script>
    $(document).ready(function(){
        $(document).on('click', '.disable', function(){
            var id = $(this).data('id');
            var isDisabled = $(this).data('is_disabled');
            var date = $(this).data('date');
            var months = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]
            if(isDisabled == 0){
                var p = 'Disable ' + date.split('-')[1] + ' of ' +
                months[ date.split('-')[0] - 1 ] + ' in the holiday lists';
                var t = 'Are you sure you want to disable this date?'
            }
            else{
                var p = 'Enable ' + date.split('-')[1] + ' of ' +
                months[ date.split('-')[0] - 1 ] + ' in the holiday lists';
                var t = 'Are you sure you want to enable this date?'
            }
            $('#title-disable-placeholder').text(p);
            $('#text-holder').text(t);
            $('#holiday_id_disable').val(id);
        });

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

        $('.modal').modal({
            preventScrolling : false
        });
    });
</script>
@endpush
