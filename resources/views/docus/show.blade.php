<?php
use App\Statuscode;
// use Carbon\Carbon;
?>
@extends('layouts.app')

@section('extended_nav')
<div class="nav-content">
    <ul class="tabs tabs-transparent">
    <li class="tab">
      <a href='{{route("home")}}' target = "_self">Back</a>
    </li>
    <li class="tab"><a href="#">View Routing Info</a></li>
    <li class="tab disabled"><a href="#">Conver to PDF</a></li>
    <li class="tab"><a href="#">Archive</a></li>
    </ul>
</div>
@endsection

@section('content')
    <br><br><br>
    <div class = "msg">
        @include('inc.message')
    </div>
    <div class="row">
        <div class="col s12">
            <h5>Record : {{$data['docu']->reference_number}} 
                &nbsp;&nbsp;    
                <span class ="blue white-text" style="padding:4px 3px;">
                    {{Statuscode::whereId($data['docu']->progress)->first()->status}}
                </span>
                @foreach($data['transactions'] as $t)
                    @if($t->recipient == Auth::user()->id && $t->is_received == 0)
                        @include('docus.show.receive')

                    @elseif($t->recipient == Auth::user()->id 
                    && $t->is_received == 1 && $t->has_sent == 0 && $t->to_continue == 1)
                        @include('docus.show.send')

                    @endif
                @endforeach
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
            '{{$list->holiday_date}}',
            @endforeach
        ];

        function holidayDate(date){
            for(i = 0; i < holiday_list.length; i++){
            if(date.getMonth() == holiday_list[i].split("-")[0] - 1
                && date.getDate() == holiday_list[i].split("-")[1]){
                    return true;
                }
            }
            return false;
        }
        $(document).ready(function(){
            var final_date = '{{explode(" ", $data["docu"]->final_action_date)[0]}}'
            $('#to_continue option:first').attr('disabled', true);
            $('.modal').modal();
            $('#comment').characterCounter();
            $('select').formSelect();
            $('.tooltipped').tooltip();
            $('#date_deadline').datepicker({
                disableWeekends : true,
                format: "yyyy-mm-dd",
                container : 'body',
                minDate : new Date(),
                maxDate : new Date(final_date.split('-')[0], final_date.split('-')[1] - 1, final_date.split('-')[2]),
                disableDayFn : holidayDate,
            });

            $('.chips-autocomplete').chips({
                autocompleteOptions: {
                data: {
                    @foreach($data['user_list'] as $user)
                        '{{$user->username}}' : null,
                    @endforeach
                },
                limit: 5,
                minLength: 1
                },
                placeholder : 'Route to/CC: ',
                secondaryPlaceholder : 'another user?',
                onChipAdd : recipientsToInput,
                onChipDelete : recipientsToInput,
            });

            function recipientsToInput(){
                var arr = [];
                var instance = M.Chips.getInstance($('.chips'))
                for(var i=0; i<instance.chipsData.length; i++){
                    arr.push(instance.chipsData[i].tag);
                }
                $('#hidden_recipients').val(arr)
            }
        });
    </script>
@endpush