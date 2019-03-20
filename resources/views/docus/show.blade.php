<?php
use Carbon\Carbon;
use Carbon\CarbonPeriod;
$a = Carbon::parse($data['docu']->final_action_date)->format('Y-m-d H:i:s');
$holidays_list = (array) $data['holidays_list'];

CarbonPeriod::macro('countDaysLeft', function() use ($holidays_list){
$diff = $this->filter('isWeekday')->count();
$range = $this->filter('isWeekday')->toArray();

foreach($range as $date){
    $in = in_array($date->format('m-d'), $holidays_list); 
    if($in){
        $diff--;
    }
}
return $diff;
});

$diff_final_action_date = CarbonPeriod::create(Carbon::now(), $a)->countDaysLeft();

?>
@extends('layouts.app')

@section('extended_nav')
@include('docus.show.extended')
@endsection

@section('content')
<br><br><br>

<div id="archiveConfirm" class="modal">
    <div class="modal-content">
        <h4>Confirming archiving of Document:
            <br> {{$data['docu']->reference_number}}</h4>
        <p>Are you sure you want to archive this document?</p>
        {!!Form::open(['action' => ['DocuController@destroy', $data['docu']->id], 'method' => 'POST'])!!}
        {{Form::hidden('_method', 'DELETE')}}

    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Archive', ['class' => 'btn green'])}}
        {!!Form::close()!!}

    </div>
</div>

<div id="restoreRecord" class="modal">
    <div class="modal-content">
        <h4>Restoring document with reference number :
            <br> {{$data['docu']->reference_number}}</h4>
        <p>Are you sure you want to restore this document?</p>
        {!!Form::open(['action' => ['DocuController@restore', $data['docu']->id], 'method' => 'POST'])!!}
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Restore', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

<div class="msg">
    @include('inc.message')
</div>
<div class="row">
    <div class="col s12">
        <h5>Reference Number : {{$data['docu']->reference_number}}
            &nbsp;&nbsp;
            <span class="blue white-text" style="padding:4px 3px;">
                {{$data['docu']->statuscode->status}}
            </span> &nbsp;
            @if($data['docu']->deleted_at != null)
            - Archived
            @endif

            @if($data['receive_bool'] == true)
            @include('docus.show.receive')

            @elseif($data['send_bool'] == true)
            @include('docus.show.send')

            @endif

            @if($data['ready_to_approve'] == true && Auth::user()->role->id == 4
            && !is_null($data['latest_route']) && $data['latest_route']->recipient == Auth::user()->id 
            && $data['docu']->final_action_date >= date('Y-m-d H:i:s'))
            @include('docus.show.approve')
            @endif

        </h5>
    </div>

    @include('docus.show.details')
    @include('docus.show.uploads')
    @include('docus.show.transactions')

</div>
@stop

@push('scripts')
<script>
    var holiday_list = [
        @foreach($data['holidays_list'] as $list)
        '{{$list}}',
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

    $(document).on('click', '#fileViewer-close-button', function () {
        $("#File_to_place").html('Loading');
    });

    $(document).on('click', '#view_comment', function () {
        var username = $(this).data('username');
        var comment = $(this).data('comment');
        $('#comment-title').text('Comment made by ' + username);
        $('#comment-content').text(comment);
    });

    $(document).on('click', '#edit_deadline_date', function () {
        var deadline = $(this).data('deadline');
        var dataId = $(this).data('transaction_id');
        $('#transaction_id').val(dataId);
        $('#old_deadline').val(deadline);
        M.updateTextFields();
    })

    $(document).on('click', '#view_files', function () {
        var dataID = $(this).data('upload_file_id');
        $.ajax({
            type: 'POST',
            url: '{{route("file_to_json")}}',
            data: {
                dataID: dataID,
                '_token': '<?php echo csrf_token() ?>'
            },
            success: function (data) {
                $("#File_to_place").html(data.File_Uploads);
            },
            fail: function (err) {
                console.log(err);
            }
        });
    });

    $(document).ready(function () {
        $('#to_approve option:first').attr('disabled', true);
        var final_date = '{{explode(" ", $data["docu"]->final_action_date)[0]}}'
        $('#to_continue option:first').attr('disabled', true);
        $('.modal').modal();
        $('#comment').characterCounter();
        $('select').formSelect();
        $('.tooltipped').tooltip();
        $('#date_deadline').datepicker({
            disableWeekends: true,
            format: "yyyy-mm-dd",
            container: 'body',
            minDate: new Date(),
            maxDate: new Date(final_date.split('-')[0], final_date.split('-')[1] - 1, final_date.split(
                '-')[2]),
            disableDayFn: holidayDate,
            autoClose : true
        });

        $('.chips-autocomplete').chips({
            autocompleteOptions: {
                data: {
                    @foreach($data['user_list'] as $user)
                    '{{$user->username}}': null,
                    @endforeach
                },
                limit: 5,
                minLength: 1
            },
            placeholder: 'Route to/CC: ',
            secondaryPlaceholder: 'another user?',
            onChipAdd: recipientsToInput,
            onChipDelete: recipientsToInput,
        });

        function recipientsToInput() {
            var arr = [];
            var instance = M.Chips.getInstance($('.chips'))
            for (var i = 0; i < instance.chipsData.length; i++) {
                arr.push(instance.chipsData[i].tag);
            }
            $('#hidden_recipients').val(arr)
        }
    });

</script>
@endpush
