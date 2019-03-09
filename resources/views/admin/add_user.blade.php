<div id="add_user" class="modal">
    <div class="modal-content">
        <div class="row">
          {!! Form::open(['action' => 'UserController@add', 'method' => 'POST', ]) !!}
            <div class="col s6">
                <div class="input-field">
                    {{Form::text('name', '', ['placeholder' => 'Name of the user', 'autocomplete' => 'off', 'required'])}}
                    {{Form::label('name', 'Full name')}}
                </div>
            </div>

            <div class="col s6">
                <div class="input-field">
                    {{Form::text('username', '', ['placeholder' => 'Username of the user', 'autocomplete' => 'off', 'required'])}}
                    {{Form::label('username', 'Username')}}
                </div>
            </div>

            <div class="col s12">
                <div class="input-field">
                    {{Form::select('department', $data['dept'], null, ['placeholder' => 'Choose your option', 'id' => 'department', 'required'])}}
                    
                    <label for="department">
                    <b>Department of user
                        <span style="color:red">*</span>
                    </b>
                    </label>
                </div>
            </div>

            <div class="col s12">
                <div class="input-field">
                    {{Form::select('role', $data['role'], null, ['placeholder' => 'Choose your option', 'id' => 'role', 'required'])}}
                    
                    <label for="role">
                    <b>Role of user
                        <span style="color:red">*</span>
                    </b>
                    </label>
                </div>
            </div>

            <div class="input-field col s6">
                <input id="password" type="password" name="password" required>
                <label for="password">Password</label>
            </div>

            <div class="input-field col s6">
                <input id="password-confirm" type="password" name="password_confirmation" required>
                <label for="password-confirm">Confirm Password</label>
            </div>

        </div>
    </div>

    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Add', ['class'=>'btn green'])}}
        {!! Form::close() !!} 
        </div>
    </div>
</div>