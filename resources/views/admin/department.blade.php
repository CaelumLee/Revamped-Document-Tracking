@extends('admin.dashboard')
@section('main-content')
<div class="row">
    <h4>Department list
        @if(Auth::user()->department->id == 9 && Auth::user()->role->id == 1)
        <span>
            <a href="#add_department" class="right waves-effect waves-light green btn modal-trigger">Add Department</a>
        </span>
        @endif
    </h4>
    <div class="col s12">
        <table class="dashboard-table" id="department-table">
            <thead>
                <tr>
                    <th>Department Name</th>
                    <th>Acronym</th>
                    <th>Enabled</th>
                    @if(Auth::user()->department->id == 9 && Auth::user()->role->id == 1)
                    <th>Options</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $dept)
                <tr>
                    <td>{{$dept->name}}</td>
                    <td>{{$dept->acronym}}</td>
                    @if($dept->is_disabled == 0)
                    <td>No</td>
                    @else
                    <td>Yes</td>
                    @endif
                    @if(Auth::user()->department->id == 9 && Auth::user()->role->id == 1)
                    <td>
                        <a href='#edit' class='waves-effect white waves-green btn-small btn-flat modal-trigger edit'
                            data-id="{{$dept->id}}" data-name="{{$dept->name}}" data-acro="{{$dept->acronym}}">
                            <i class='material-icons'>edit</i>
                        </a>

                        <a href='#disable' class='waves-effect white waves-red btn-small btn-flat modal-trigger disable'
                            data-id="{{$dept->id}}" data-name="{{$dept->name}}" data-is_disabled="{{$dept->is_disabled}}">
                            <i class='material-icons'>
                                @if($dept->is_disabled == 0)
                                radio_button_checked
                                @else
                                radio_button_unchecked
                                @endif
                            </i>
                        </a>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="edit" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4>Edit department values</h4>
            {!!Form::open(['action' => ['DepartmentDashboardController@edit'], 'method' => 'POST'])!!}
            <div class="input-field col s6">
                {{Form::text('dept_name', '', ['id' => 'dept_name', 'placeholder' => 'Department name', 'autocomplete'
                => 'off'])}}
                {{Form::label('dept_name', 'Department name')}}
            </div>
            <div class="col s6 input-field">
                {{Form::text('dept_acro', '', ['id' => 'dept_acro', 'placeholder' => 'Department acronym',
                'autocomplete' => 'off'])}}
                {{Form::label('dept_name', 'Department acronym')}}
            </div>
            <input type="hidden" id="department_id" name="department_id" value="">
        </div>
    </div>

    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Yes', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

<div id="disable" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4 id="title-disable-placeholder">...</h4>
            {!!Form::open(['action' => ['DepartmentDashboardController@disable'], 'method' => 'POST'])!!}
            <blockquote id="text-holder"></blockquote>
            <input type="hidden" id="dept_id_disable" name="dept_id_disable" value="">
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">No</a>
        {{Form::submit('Yes', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

<div id="add_department" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4 id="title-add-placeholder">Adding of new department</h4>
            {!!Form::open(['action' => ['DepartmentDashboardController@add'], 'method' => 'POST'])!!}
            <div class="input-field col s6">
                {{Form::text('dept_name', '', ['id' => 'dept_name', 'placeholder' => 'Department name', 'autocomplete'
                => 'off'])}}
                {{Form::label('dept_name', 'Department name')}}
            </div>
            <div class="col s6 input-field">
                {{Form::text('dept_acro', '', ['id' => 'dept_acro', 'placeholder' => 'Department acronym',
                'autocomplete' => 'off'])}}
                {{Form::label('dept_name', 'Department acronym')}}
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Add', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

@stop

@push('scripts')
<script>
    $(document).ready(function () {
        $('#department-table').DataTable({
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
        $('.modal').modal();
    });

    $(document).on('click', '.edit', function () {
        var dataID = $(this).data('id');
        var dept = $(this).data('name');
        var acro = $(this).data('acro');
        $('#dept_name').val(dept);
        $('#dept_acro').val(acro)
        $('#department_id').val(dataID);
        M.updateTextFields();
    });

    $(document).on('click', '.disable', function () {
        var dataID = $(this).data('id');
        var dept = $(this).data('name');
        var isDisabled = $(this).data('is_disabled');
        if (isDisabled == 0) {
            var p = 'Disable ' + dept + ' in the lists';
            var t = 'Are you sure you want to disable this department?'
        } else {
            var p = 'Enable ' + dept + ' in the lists';
            var t = 'Are you sure you want to enable this department?'
        }
        $('#title-disable-placeholder').text(p);
        $('#text-holder').text(t);
        $('#dept_id_disable').val(dataID);
    });

</script>
@endpush
