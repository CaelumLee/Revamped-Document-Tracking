@extends('admin.dashboard')
@section('main-content')
<?php
use Carbon\Carbon;
?>
<div class="row">
    <h4>Holidays list for the year {{date('Y')}}
        @if(Auth::user()->department->id == 9 && Auth::user()->role->id == 1)
        <span>
            <a href="#add_holiday" class="right waves-effect waves-light green btn modal-trigger add">Add Holiday</a>
        </span>
        @endif
    </h4>
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
                        <a href='#edit' class='waves-effect waves-light waves-light btn-small btn-flat modal-trigger action-buttons edit'
                            data-id="{{$holiday->id}}" data-date="{{$holiday->holiday_date}}" data-date_name="{{$holiday->holiday_name}}">
                            <i class='material-icons'>edit</i></a>
                        <a href='#disable' class='waves-effect waves-light waves-light btn-small btn-flat modal-trigger action-buttons disable'
                            data-id="{{$holiday->id}}" data-is_disabled="{{$holiday->is_disabled}}" data-date="{{$holiday->holiday_date}}">
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
        <blockquote id="text-holder"></blockquote>
        <input type="hidden" id="holiday_id_disable" name="holiday_id_disable" value="">
    </div>

    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">No</a>
        {{Form::submit('Yes', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

<div id="edit" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4>Edit holiday date values</h4>
            {!!Form::open(['action' => ['HolidaysDashboardController@edit'], 'method' => 'POST'])!!}
            <div class="input-field col s6">
                {{Form::text('holiday_date', '', ['id' => 'holiday_date',
                'class' => 'datepicker', 'autocomplete' => 'off'])}}

                <label for="holiday_date">
                    <b>Edit holiday date
                        <span style="color:red">*</span>
                    </b>
                </label>
            </div>
            <div class="col s6 input-field">
                {{Form::text('holiday_name', '', ['id' => 'holiday_name', 'placeholder' => 'Holiday name',
                'autocomplete'
                => 'off'])}}
                {{Form::label('holiday_name', 'Holiday name')}}
            </div>
            <input type="hidden" id="holiday_id" name="holiday_id" value="">
        </div>
    </div>

    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Yes', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

<div id="add_holiday" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4 id="title-add-placeholder">Adding of new holiday</h4>
            {!!Form::open(['action' => ['HolidaysDashboardController@add'], 'method' => 'POST'])!!}
            <div class="input-field col s6">
                {{Form::text('new_holiday_date', '', ['id' => 'new_holiday_date',
                'class' => 'datepicker', 'autocomplete' => 'off'])}}

                <label for="holiday_date">
                    <b>New holiday date
                        <span style="color:red">*</span>
                    </b>
                </label>
            </div>

            <div class="col s6 input-field">
                {{Form::text('new_holiday_name', '', ['id' => 'new_holiday_name', 'placeholder' => 'Holiday name',
                'autocomplete' => 'off'])}}
                {{Form::label('new_holiday_name', 'Holiday name')}}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Add', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>
@stop

@push('scripts')
<script>
    var holiday_list = [
        @foreach($holiday_list as $list)
        '{{$list->holiday_date}}',
        @endforeach
    ];

    function holidayDate(date) {
        for (i = 0; i < holiday_list.length; i++) {
            if (date.getMonth() == holiday_list[i].split("-")[0] - 1 &&
                date.getDate() == holiday_list[i].split("-")[1]) {
                return true;
            }
        }
        return false;
    }

    $(document).ready(function () {
        $(document).on('click', '.disable', function () {
            var id = $(this).data('id');
            var isDisabled = $(this).data('is_disabled');
            var date = $(this).data('date');
            var months = ["January", "February", "March", "April", "May", "June", "July", "August",
                "September", "October", "November", "December"
            ]
            if (isDisabled == 0) {
                var p = 'Disable ' + date.split('-')[1] + ' of ' +
                    months[date.split('-')[0] - 1] + ' in the holiday lists';
                var t = 'Are you sure you want to disable this date?'
            } else {
                var p = 'Enable ' + date.split('-')[1] + ' of ' +
                    months[date.split('-')[0] - 1] + ' in the holiday lists';
                var t = 'Are you sure you want to enable this date?'
            }
            $('#title-disable-placeholder').text(p);
            $('#text-holder').text(t);
            $('#holiday_id_disable').val(id);
        });

        $(document).on('click', '.edit', function () {
            var id = $(this).data('id');
            var date_name = $(this).data('date_name');
            var date = $(this).data('date');

            $('#holiday_id').val(id);
            $('#holiday_name').val(date_name);
            var date_now = new Date();
            var defaultDate = new Date(date_now.getFullYear(), date.split('-')[0] - 1, date.split('-')[
                1])

            $('#holiday_date').datepicker({
                autoClose: true,
                format: "yyyy-mm-dd",
                disableWeekends: true,
                container: 'body',
                defaultDate: defaultDate,
                setDefaultDate: true
            });
        });

        $(document).on('click', '.add', function () {
            $('#new_holiday_date').datepicker({
                autoClose: true,
                format: "yyyy-mm-dd",
                disableWeekends: true,
                container: 'body',
                disableWeekends: true,
                disableDayFn: holidayDate,
            });
        });

        $('#holidays-table').DataTable({
            pagingType: "simple",
            dom: '<div>pt',
            pageLength: 15,
            language: {
                paginate: {
                    previous: "<i class='material-icons'>chevron_left</i>",
                    next: "<i class='material-icons'>chevron_right</i>"
                }
            }
        });

        $('.modal').modal({
            preventScrolling: false
        });
    });

</script>
@endpush
