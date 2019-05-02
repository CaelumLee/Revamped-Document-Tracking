<?php
    use App\Department;
    use Carbon\Carbon;
?>

<div class="col s12">
    <div class="card z-depth-3">
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo" style="font-size : 1.5em !important;">Transaction</a>
            </div>
        </nav>

        <div class="card-content">
            <table class="stripped">
                <thead>
                    <tr class="blue white-text">
                        <th>Location</th>
                        <th>Person in charge</th>
                        <th>Route</th>
                        <th>To</th>
                        <th>Remarks</th>
                        <th>Date deadline</th>
                        <th>Has received?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['docu']->transaction->sortByDesc('created_at') as $transaction)
                    <tr>
                        <th>{{$transaction->fromLoc->acronym}}</th>
                        <th>{{$transaction->from->name}}</th>
                        <th>{{$transaction->toLoc->acronym}}</th>
                        <th>{{$transaction->to->name}}</th>
                        <th>{{$transaction->remarks}}</th>
                        <th>
                            {{Carbon::parse($transaction->date_deadline)->format('Y-m-d H:i:s a')}}
                            &nbsp; &nbsp;
                            <a href="#deadline_date_info" class="btn-small btn-flat waves-effect waves-yellow white modal-trigger action-buttons"
                                id="edit_deadline_date" data-deadline="{{Carbon::parse($transaction->date_deadline)->format('Y-m-d')}}"
                                data-transaction_id={{$transaction->id}}>
                                <i class="material-icons">edit</i>
                            </a>
                        </th>
                        <th>
                            @if($transaction->is_received)
                            Yes
                            &nbsp;
                            <a href="#comment_modal" class="btn-small btn-flat waves-effect waves-green white modal-trigger action-buttons"
                                id="view_comment" data-username="{{$transaction->to->username}}" data-comment="{{$transaction->comment}}">
                                <i class="material-icons">remove_red_eye</i>
                            </a>
                            @else
                            No
                            @endif
                        </th>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

<div id="comment_modal" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4 id="comment-title"></h4>
            <blockquote id="comment-content">
                
            </blockquote>
        </div>
    </div>

    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Close</a>
    </div>
</div>

<div id="deadline_date_info" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4>Change deadline date</h4>
            {!! Form::open(['action' => 'TransactionsController@update_date_deadline', 'method' => 'POST', ]) !!}
            <div class="input-field col s6">
                <input disabled value="" id="old_deadline" name="old_deadline" type="text" class="validate">
                <label class="active" for="old_deadline">Old Deadline Date</label>
            </div>

            <div class="input-field col s6">
                {{Form::text('date_deadline', '', ['id' => 'date_deadline', 'class' => 'datepicker', 'autocomplete' =>
                'off'])}}
                <label class="active" for="date_deadline">New Deadline Date</label>
            </div>
            <input type="hidden" id="docu_id" name="docu_id" value="{{$data['docu']->id}}">
            <input type="hidden" id="transaction_id" name="transaction_id" value="">
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Close</a>
        {{Form::submit('Update', ['class'=>'btn green'])}}
        {!! Form::close() !!}
    </div>
</div>
