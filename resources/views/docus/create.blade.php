<?php 
use App\Department;
?>
@extends('layouts.app')

@section('content')
<div class="msg">
    @include('inc.message')
</div>

<div class="container">
    <h4 class="grey-text text-darken-3">Add a Record</h4>
    <div class="card white">
        <div class="card-content black-text">
            <a href="{{route('home')}}" class="btn red" style=" float:right; margin:auto;">Cancel</a>
            <br><br>
            <div class="row">
                {!! Form::open(['id' => 'create_form' ,'action' => 'DocuController@store', 'method' => 'POST', ]) !!}
                {{Form::hidden('user_id', Auth::user()->id)}}

                <div class="col s5">
                    <div class="input-field">
                        {{Form::select('typeOfDocu', $data['docu_type'], null, ['placeholder' => 'Choose your option',
                        'id' => 'typeOfDocu'])}}

                        <label for="typeOfDocu">
                            <b>Type of Document
                                <span style="color:red">*</span>
                            </b>
                        </label>
                    </div>
                </div>

                <div class="col s2">
                    <div class="input-field">
                        {{Form::select('rushed',
                        ['1' => 'Yes',
                        '0' => 'No'
                        ], null, ['placeholder' => 'Yes/No','id' => 'rushed', 'data-ans']
                        )}}

                        <label for="rushed">
                            <b>Is it Rush?
                                <span style="color:red">*</span>
                            </b>
                        </label>
                    </div>
                </div>

                <div class="col s2">
                    <div class="input-field">
                        {{Form::select('confidential',
                        ['1' => 'Yes',
                        '0' => 'No'
                        ], null, ['placeholder' => 'Yes/No','id' => 'confidential']
                        )}}
                        <label for="confidential">
                            <b>Is it Confidential?
                                <span style="color:red">*</span>
                            </b>
                        </label>
                    </div>
                </div>

                <div class="col s3">
                    <div class="input-field">
                        {{Form::select('complexity',
                        ['Simple' => 'Simple',
                        'Complex' => 'Complex'
                        ], null, ['placeholder' => 'Choose your option', 'id' => 'complexity']
                        )}}

                        <label for="complexity">
                            <b>Simple or Complex?
                                <span style="color:red">*</span>
                            </b>
                        </label>
                    </div>
                </div>

                <div class="col s4">
                    <div class="input-field">
                        {{Form::text('iso', '', ['placeholder' => 'ISO Number', 'autocomplete' => 'off'])}}
                        {{Form::label('iso', 'ISO Number')}}
                    </div>
                </div>

                <div class="col s8">
                    <div class="input-field">
                        {{Form::text('subject', '', ['placeholder' => 'Subject'])}}

                        <label for="subject">
                            <b>Subject
                                <span style="color:red">*</span>
                            </b>
                        </label>
                    </div>
                </div>

                <div class="col s6">
                    <div class="input-field">
                        {{Form::text('sender', '', ['placeholder' => 'Sender', 'class' => 'autocomplete', 'id' =>
                        'sender', 'autocomplete' => 'off'])}}

                        <label for="sender">
                            <b>Sender
                                <span style="color:red">*</span>
                            </b>
                        </label>
                    </div>
                </div>

                <div class="col s6">
                    <div class="input-field">
                        {{Form::text('sender_add', '', ['placeholder' => 'Sender Address', 'id' => 'sender_add',
                        'autocomplete' => 'off'])}}
                        {{Form::label('sender_add', 'Sender Address')}}
                    </div>
                </div>

                <div class="col s12">
                    <div class="input-field">
                        <div id="recipient" name="recipient" class="chips chips-autocomplete">
                            @if(Auth::user()->department->id != 15)
                            @php($user_ro = \App\User::find(1)->username)
                            <div class="chip">
                                {{$user_ro}}
                            </div>
                            @else
                            @php($user_ro = '')
                            @endif
                        </div>
                        <input type="hidden" id="hidden_recipients" name="hidden_recipients" value="{{$user_ro}}">
                    </div>
                </div>

                <div class="col s12">
                    <div class="input-field">
                        {{Form::text('remarks', '', ['placeholder' => 'Remarks'])}}

                        <label for="remarks">
                            <b>Remarks
                                <span style="color:red">*</span>
                            </b>
                        </label>
                    </div>
                </div>

                <div class="col s4">
                    <div class="input-field">
                        {{Form::text('date_deadline', '', ['class' => 'datepicker', 'autocomplete' => 'off'])}}

                        <label for="date_deadline">
                            <b>Date Deadline for Routing Info
                                <span style="color:red">*</span>
                            </b>
                        </label>
                    </div>
                </div>

                <div class="col s4">
                    <div class="input-field">
                        {{Form::text('final_action_date', '', ['class' => 'datepicker', 'autocomplete' => 'off'])}}

                        <label for="final_action_date">
                            <b>Final Action Date
                                <span style="color:red">*</span>
                            </b>
                        </label>
                    </div>
                </div>

                <div class="col s8 file-field input-field">
                    <div class="btn">
                        <span>File</span>
                        <input type="file" name="filename[]" multiple>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Upload one or more files">
                    </div>
                </div>

                <div class="col s4 ">
                    <div class="input-field right-align">
                        {{Form::submit('Create', ['class'=>'btn green', 'id' => 'create_button'])}}
                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('layouts.preloader')
@stop

@push('scripts')
<script>
    (function ($) {
        $.fn.invisible = function () {
            $(this).css("visibility", "hidden");
        };
        $.fn.visible = function () {
            $(this).css("visibility", "visible");
        };
    }(jQuery));


    (function(){
        $('#create_form').on('submit', function(){
            $('#create_button').attr('disabled', true);
                // $('.preloader-background').visible();
                // $('.preloader-wrapper').visible();
        })
    })

    var holiday_list = [
        @foreach($data['holidays_list'] as $list)
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

    var recipients_list = [];

    $('#confidential').change(function () {
        var input = $(this).val();
        $.ajax({
            type: 'POST',
            url: '{{route("ajax_userlists")}}',
            data: {
                'answer': input,
                '_token': '<?php echo csrf_token() ?>'
            },
            success: function (data) {
                // recipients_list = [];
                data.user_list.forEach(function (user) {
                    recipients_list[user] = null
                });

            },
            fail: function (err) {
                console.log(err)
            }
        })
    });

    $('#rushed').change(function () {
        var ans = $(this).val();
        $(this).data('ans', ans)
        var dNow = new Date();
        var dExpected = new Date();
        if (ans == 1) {
            dExpected.setDate(dNow.getDate() + 2)
        } else {
            var compAns = $('#complexity').val();
            if (compAns == 'Simple') {
                dExpected.setDate(dNow.getDate() + 3)
            } else if (compAns == 'Complex') {
                dExpected.setDate(dNow.getDate() + 5)
            }
        }

        var buff = dayBuffer(dNow, dExpected);
        dExpected.setDate(dExpected.getDate() + buff);

        $('.datepicker').datepicker({
            autoClose : true,
            format: "yyyy-mm-dd",
            disableWeekends: true,
            minDate: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() + 1),
            disableDayFn: holidayDate,
            maxDate: new Date(dExpected.getFullYear(), dExpected.getMonth(), dExpected.getDate())
        });

    });

    $('#complexity').change(function () {
        var dNow = new Date();
        var dExpected = new Date();
        var ans = $('#rushed').val();
        if (ans != null && ans == 0) {
            var compAns = $('#complexity').val();
            if (compAns == 'Simple') {
                dExpected.setDate(dNow.getDate() + 3)
            } else if (compAns == 'Complex') {
                dExpected.setDate(dNow.getDate() + 5)
            }
        } else if (ans == 1) {
            dExpected.setDate(dNow.getDate() + 2)
        }

        var buff = dayBuffer(dNow, dExpected);
        dExpected.setDate(dExpected.getDate() + buff);

        $('.datepicker').datepicker({
            autoClose : true,
            format: "yyyy-mm-dd",
            disableWeekends: true,
            minDate: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() + 1),
            disableDayFn: holidayDate,
            maxDate: new Date(dExpected.getFullYear(), dExpected.getMonth(), dExpected.getDate())
        });

    });

    function dayBuffer(date1, date2) {
        var buff = 0;
        //for weekends first
        while (date1 < date2) {
            var day = date1.getDay();

            if (day == 5) {
                buff += 2;
                date1.setDate(date1.getDate() + 3);
            } else if (day == 6) {
                buff += 2;
                date1.setDate(date1.getDate() + 2);
            }

            date1.setDate(date1.getDate() + 1);
        }

        //for holidays
        date1 = new Date();
        while (date1 < date2) {
            for (i = 0; i < holiday_list.length; i++) {
                if (date1.getMonth() == holiday_list[i].split("-")[0] - 1 &&
                    date1.getDate() == holiday_list[i].split("-")[1]) {
                    return buff += 1;
                }
            }
            date1.setDate(date1.getDate() + 1);
        }
        return buff;
    }

    $(document).ready(function () {
        $('#rushed option:first').attr('disabled', true);
        $('#typeOfDocu option:first').attr('disabled', true);
        $('#confidential option:first').attr('disabled', true);
        $('#complexity option:first').attr('disabled', true);

        $('select').formSelect();

        $('input.autocomplete').autocomplete({
            data: {
                @foreach($data['users'] as $user)
                '{{$user}}': null,
                @endforeach
            },
            limit: 5,
            sortFunction: function (a, b, inputString) {
                return a.indexOf(inputString) - b.indexOf(inputString);
            },
            onAutocomplete: function (input) {
                $('#sender_add').val('')
                $.ajax({
                    type: 'POST',
                    url: '{{route("ajax_address")}}',
                    data: {
                        'name': input,
                        '_token': '<?php echo csrf_token() ?>'
                    },
                    success: function (data) {
                        $('#sender_add').val(data.department)
                    },
                    fail: function (err) {
                        console.log(err)
                    }
                })
            }
        });

        $('.chips-autocomplete').chips({
            @if(Auth::user()->department-> id == 15)
            autocompleteOptions: {
                data: recipients_list,
                limit: 5,
                minLength: 1
            },

            @else
            limit: 0,

            @endif

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

    })

</script>
@endpush
