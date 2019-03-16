<div id="approve" class="modal">
    <div class="modal-content">
        <div class="row">
            {!! Form::open(['action' => ['DocuController@approve', $data['docu']->id], 'method' => 'POST', ]) !!}
            <h4>Aprrover's Decision for {{$data['docu']->reference_number}}</h4>

            <div class="col s12">
              <div class="input-field">
                {{Form::select('to_approve', 
                  ['1' => 'Approve',
                  '0' => 'Disapprove'
                  ], null, ['placeholder' => 'Yes/No',
                  'id' => 'to_approve']
                )}}
                        
                <label for="to_continue">
                  <b>Decision 
                      <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>

            <div class="input-field col s12">
              {{Form::textarea('remarks', null, ['id' => 'remakrs',
              'class' => 'materialize-textarea', 'data-length' => '120'])}}
              <label for="comment">Remarks</label>
            </div>

        </div>
        
        <blockquote>
              Upon disapproving, it will make a transaction and the
              recipient will be the latest sender : 
              {{$data['latest_route']->from->username}}
               and moving the final action date two weeks from now
        </blockquote>
        <input type="hidden" id="transaction_id" name = "transaction_id" value = "{{$data['latest_route']->id}}">

        <input type="hidden" id="latest_sender_username" name = "latest_sender_username" value = "{{$data['latest_route']->from->username}}">
    </div>

    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('OK', ['class'=>'btn green'])}}
        {!! Form::close() !!} 
    </div>
</div>

<span class="right">
    <a href="#approve" class="waves-effect waves-dark black-text yellow btn modal-trigger">Approve Document</a>
</span>