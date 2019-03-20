@extends('admin.dashboard')

@section('search')
<form class="hide-on-med-and-down search-on-nav">
    <div class="input-field grey lighten-2">
        <input id="autocomplete-input" type="search" class="autocomplete search-bar" required>
        <label class="label-icon search-icon" for="autocomplete-input">
            <i class="material-icons blue-text">search</i>
        </label>
    </div>
</form>
@endsection

@section('main-content')
<div class="row">
    @include('admin.add_user')
    <h4>
        Manage users list on all departments
        <span>
            <a href="#add_user" class="right waves-effect waves-light green btn modal-trigger">Add User</a>
        </span>
    </h4>

    <div class="col s12">
        <table class="dashboard-table" id="users-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Disabled</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['users'] as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->username}}</td>
                    <td>{{$user->role->name}}</td>
                    <td>{{$user->department->name}}</td>
                    @if($user->is_disabled == 0)
                    <td>No</td>
                    @else
                    <td>Yes</td>
                    @endif
                    <td>
                        <a href='#edit' id="edit_button" class='waves-effect waves-light waves-light btn-small btn-flat modal-trigger action-buttons'
                            data-id="{{$user->id}}" data-username="{{$user->username}}" data-name="{{$user->name}}"
                            data-dept="{{$user->department_id}}" data-role="{{$user->role_id}}">
                            <i class='material-icons'>edit</i>
                        </a>

                        <a href='#pass' id="change_pass_button" class='waves-effect waves-light waves-light btn-small btn-flat modal-trigger action-buttons'
                            data-id="{{$user->id}}" data-username="{{$user->username}}">
                            <i class='material-icons'>vpn_key</i>
                        </a>

                        <a href='#disable' id="disable_button" class='waves-effect waves-light waves-light btn-small btn-flat modal-trigger action-buttons'
                            data-id="{{$user->id}}" data-username="{{$user->username}}" data-is_disabled="{{$user->is_disabled}}">
                            <i class='material-icons'>do_not_disturb</i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="edit" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4 id="title-edit-placeholder">...</h4>
            {!!Form::open(['action' => ['UserController@edit'], 'method' => 'POST'])!!}
            <input type="hidden" id="hidden_user_id" name="hidden_user_id" value="">

            <div class="input-field col s6">
                <input value="" id="name" name="name" type="text" class="validate">
                <label class="active" for="name">Full Name</label>
            </div>

            <div class="input-field col s6">
                <input value="" id="username" name="username" type="text" class="validate">
                <label class="active" for="username">Username</label>
            </div>

            <div class="input-field col s6">
                <select name="department">
                    <option value="" disabled selected>Choose your option</option>
                    @foreach($data['dept'] as $key => $dept)
                    <option value="{{$key}}">{{$dept}}</option>
                    @endforeach
                </select>
                <label>Department</label>
            </div>

            <div class="input-field col s6">
                <select name="role">
                    <option value="" disabled selected>Choose your option</option>
                    @foreach($data['role'] as $key => $role)
                    <option value="{{$key}}">{{$role}}</option>
                    @endforeach
                </select>
                <label>Role</label>
            </div>

        </div>
    </div>

    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Update', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

<div id="pass" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4 id="title-change-placeholder">...</h4>
            {!!Form::open(['action' => ['UserController@pass'], 'method' => 'POST'])!!}

            <div class="row">
                <div class="input-field col s6">
                    <input id="password" type="password" name="password" required>
                    <label for="password">Password</label>
                </div>

                <div class="input-field col s6">
                    <input id="password-confirm" type="password" name="password_confirmation" required>
                    <label for="password-confirm">Confirm Password</label>
                </div>
            </div>

            <input type="hidden" id="hidden_id" name="hidden_id" value="">
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Change', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

<div id="disable" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4 id="title-disable-placeholder">...</h4>
            {!!Form::open(['action' => ['UserController@disable'], 'method' => 'POST'])!!}
            <blockquote id="text-holder"></blockquote>
            <input type="hidden" id="user_id_disable" name="user_id_disable" value="">
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">No</a>
        {{Form::submit('Yes', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>
@stop

@push('scripts')
<script>
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);
        const username_from_url = urlParams.get('username');

        $(document).on('click', '#edit_button', function () {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var username = $(this).data('username');
            var role_id = $(this).data('role');
            var dept_id = $(this).data('dept');

            $('#username').val(username);
            $('#name').val(name);

            $('#hidden_user_id').val(id);
            $('#title-edit-placeholder').text('Edit ' + username + ' info');
            M.updateTextFields();
        });

        $(document).on('click', '#disable_button', function () {
            var id = $(this).data('id');
            var username = $(this).data('username');
            var isDisabled = $(this).data('is_disabled');
            if (isDisabled == 0) {
                var p = 'Disable ' + username + ' in the lists';
                var t = 'Are you sure you want to disable this user?'
            } else {
                var p = 'Enable ' + username + ' in the lists';
                var t = 'Are you sure you want to enable this user?'
            }
            $('#title-disable-placeholder').text(p);
            $('#text-holder').text(t);
            $('#user_id_disable').val(id);
        });

        $(document).on('click', '#change_pass_button', function () {
            var id = $(this).data('id');
            var username = $(this).data('username');

            $('#hidden_id').val(id);
            $('#title-change-placeholder').text('Change User ' + username + "'s password");
        });

        $('#users-table').DataTable({
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
        oTable = $('#users-table').DataTable();
        if(username_from_url != null){
            $('#autocomplete-input').val(username_from_url)
            oTable.search($('#autocomplete-input').val()).draw();
        }
        $('#autocomplete-input').keyup(function () {
            oTable.search($(this).val()).draw();
        });
        $('.modal').modal({
            preventScrolling: false
        });
        $('#typeOfDocu option:first').attr('disabled', true);
        $('#role option:first').attr('disabled', true);
        $('select').formSelect();
    });

</script>
@endpush
