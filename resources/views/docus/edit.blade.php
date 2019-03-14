@extends('layouts.app')

@section('content')
<div class="container">
    <div class="msg">
        @include('inc.message')
    </div>

    <div class="card white">
        <div class="card-content black-text">
            <a href="{{route('docu.show', ['id' => $data['docu']->id] )}}" 
            class="btn red" style=" float:right; margin:auto;">Cancel</a>

            <br><br>

            <div class="row">
                {!! Form::open(['action' => ['DocuController@update', $data['docu']->id], 'method' => 'PUT']) !!}

                <div class="col s5">
                    <div class="input-field">
                        {{Form::select('typeOfDocu', $data['types'], 
                        $data['docu']->type_of_docu_id, 
                        ['placeholder' => 'Choose your option', 'id' => 'typeOfDocu'])}}
                        
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
                                ], $data['docu']->is_rush, ['placeholder' => 'Yes/No', 'id' => 'rushed']
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
                            ], $data['docu']->confidentiality, 
                            ['placeholder' => 'Yes/No','id' => 'confidential']
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
                            ], $data['docu']->complexity, 
                            ['placeholder' => 'Choose your option', 'id' => 'complexity']
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
                        {{Form::text('iso', $data['docu']->iso_code, 
                        ['placeholder' => 'ISO Number', 'autocomplete' => 'off'])}}
                        {{Form::label('iso', 'ISO Number')}}
                    </div>
                </div>

                <div class="col s8">
                    <div class="input-field">
                        {{Form::text('subject', $data['docu']->subject, ['placeholder' => 'Subject'])}}
                        
                        <label for="subject">
                        <b>Subject 
                            <span style="color:red">*</span>
                        </b>
                        </label>
                    </div>
                </div>

                <div class="col s6">
                    <div class="input-field">
                        {{Form::text('sender', $data['docu']->sender_name, 
                        ['placeholder' => 'Sender', 'class' => 'autocomplete', 'id' => 'sender', 'autocomplete' => 'off'])}}
                
                        <label for="sender">
                        <b>Sender 
                            <span style="color:red">*</span>
                        </b>
                        </label>
                    </div>
                </div>
                
                <div class="col s6">
                    <div class="input-field">
                        {{Form::text('sender_add', $data['docu']->sender_address, 
                        ['placeholder' => 'Sender Address', 'id' => 'sender_add', 'autocomplete' => 'off'])}}
                        {{Form::label('sender_add', 'Sender Address')}}
                    </div>
                </div>

                <div class="col s4">
                    <div class="input-field">
                    {{Form::text('final_action_date', explode(' ', $data['docu']->final_action_date)[0], 
                    ['class' => 'datepicker', 'autocomplete' => 'off'])}}
                    
                    <label for="final_action_date">
                        <b>Final Action Date 
                        <span style="color:red">*</span>
                        </b>
                    </label>
                    </div>
                </div>

                <div class="col s4"></div>

                <div class="col s4 ">
                    <div class="input-field right-align">
                    {{Form::submit('Edit', ['class'=>'btn green'])}}            
                    {!! Form::close() !!} 
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
    var holiday_list = [
        @foreach($data['holidays'] as $list)
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

    $('#rushed').change(function(){
        var ans = $(this).val();
        $(this).data('ans', ans)
        var dNow = new Date();
        var dExpected = new Date();
        if(ans==1){
        dExpected.setDate(dNow.getDate() + 2)
        }
        else{
            var compAns = $('#complexity').val();
            if(compAns == 'Simple'){
                dExpected.setDate(dNow.getDate() + 3)
            }
            else if(compAns == 'Complex'){
                dExpected.setDate(dNow.getDate() + 5)
            }
        }

        var buff = dayBuffer(dNow, dExpected);
        dExpected.setDate(dExpected.getDate() + buff);

        $('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            disableWeekends : true,
            minDate : new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() + 1),
            disableDayFn : holidayDate,
            maxDate : new Date(dExpected.getFullYear(), dExpected.getMonth(), dExpected.getDate())
        });
        
    });

    $('#complexity').change(function(){
        var dNow = new Date();
        var dExpected = new Date();
        var ans = $('#rushed').val();
        if(ans != null && ans == 0){
          var compAns = $('#complexity').val();
          if(compAns == 'Simple'){
            dExpected.setDate(dNow.getDate() + 3)
          }
          else if(compAns == 'Complex'){
            dExpected.setDate(dNow.getDate() + 5)
          }
        }
        else if(ans == 1){
          dExpected.setDate(dNow.getDate() + 2)
        }

        var buff = dayBuffer(dNow, dExpected);
        dExpected.setDate(dExpected.getDate() + buff);

        $('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            disableWeekends : true,
            minDate : new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() + 1),
            disableDayFn : holidayDate,
            maxDate : new Date(dExpected.getFullYear(), dExpected.getMonth(), dExpected.getDate())
        });

    });

    function dayBuffer(date1, date2){
        var buff = 0;
        //for weekends first
        while (date1 < date2) {
          var day = date1.getDay();
          
          if(day==5){
            buff += 2;
            date1.setDate(date1.getDate() + 3);
          }

          else if(day==6){  
            buff += 2;
            date1.setDate(date1.getDate() + 2);
          }
          
          date1.setDate(date1.getDate() + 1);
        }

        //for holidays
        date1 = new Date();
        while(date1 < date2){
          for(i = 0; i < holiday_list.length; i++){
            if(date1.getMonth() == holiday_list[i].split("-")[0] - 1
            && date1.getDate() == holiday_list[i].split("-")[1]){
                return buff+=1;
            }
          }
          date1.setDate(date1.getDate() + 1);
        }
        return buff;
    }

    $(document).ready(function(){
        $('#rushed option:first').attr('disabled', true);
        $('#typeOfDocu option:first').attr('disabled', true);
        $('#confidential option:first').attr('disabled', true);
        $('#complexity option:first').attr('disabled', true);

        $('input.autocomplete').autocomplete({
            data: {
              @foreach($data['users'] as $user)
                '{{$user}}' : null,
              @endforeach
            },
            limit : 5,
            sortFunction : function(a, b , inputString){
                return a.indexOf(inputString) - b.indexOf(inputString);
            },
            onAutocomplete : function(input){
                $('#sender_add').val('')
                $.ajax({
                    type:'POST',
                    url:'{{route("ajax_address")}}',
                    data : {
                        'username' : input,
                        '_token' : '<?php echo csrf_token() ?>'
                    },
                    success:function(data){
                        $('#sender_add').val(data.department)
                    },
                    fail:function(err){
                        console.log(err)
                    }
                })
            }
        });

        $('select').formSelect();
        $('.datepicker').datepicker();
    });
</script>
@endpush