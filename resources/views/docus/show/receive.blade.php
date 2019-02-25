<?php
    if($data['docu']->deleted_at != null){
        $disabled = 'disabled';
    }
    else{
        $disabled = '';
    }
?>
<div id="receive" class="modal">
    <div class="modal-content">
        <div class="row">
          {!! Form::open(['action' => 'TransactionsController@receive_docu', 'method' => 'POST', ]) !!}
          <input type="hidden" id="docu_id" name = "docu_id" value = "{{$data['docu']->id}}">
          <input type="hidden" id="transaction_id" name = "transaction_id" value = "{{$t->id}}">
            <div class="input-field col s12">
              {{Form::textarea('comment', null, ['id' => 'comment',
              'class' => 'materialize-textarea', 'data-length' => '120'])}}
              <label for="comment">Comment</label>
            </div>

            <div class="col s12">
              <div class="input-field">
                {{Form::select('to_continue', 
                  ['1' => 'Yes',
                  '0' => 'No'
                  ], null, ['placeholder' => 'Yes/No',
                  'id' => 'to_continue', 'data-ans']
                )}}
                        
                <label for="to_continue">
                  <b>Continue with transaction? 
                      <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>

            <div class="col s12">
              <div class="label">
                  <span>Warning! This cannot be change! Make sure you're certain
                    if you still want to send the record to other employee or not
                  </span>
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
    <a href="#receive" {{$disabled}} class="waves-effect waves-light green btn modal-trigger">Receive Document</a>
</span>