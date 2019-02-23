<div id="send" class="modal">
    <div class="modal-content">
        <div class="row">
            {!! Form::open(['action' => 'TransactionsController@send_docu', 'method' => 'POST', ]) !!}
            <input type="hidden" id="docu_id" name = "docu_id" value = "{{$data['docu']->id}}">

            <div class="col s12">
                <div class="input-field">
                    <div id="recipient" name="recipient" class="chips chips-autocomplete"></div>
                    <input type="hidden" id="hidden_recipients" name = "hidden_recipients" value = "">
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

            <div class="col s5">
                <div class="input-field">
                    {{Form::text('date_deadline', '', ['id' => 'date_deadline', 'class' => 'datepicker', 'autocomplete' => 'off'])}}
                    
                    <label for="final_action_date">
                        <b>Date Deadline for Routing Info
                        <span style="color:red">*</span>
                        </b>
                    </label>
                </div>
            </div>


        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Receive', ['class'=>'btn green'])}}
        {!! Form::close() !!} 
    </div>
</div>

<span class="right">
    <a href="#send" class="waves-effect waves-light green btn modal-trigger">Send Document</a>
    {!! Form::close() !!} 
</span>