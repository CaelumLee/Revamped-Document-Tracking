@extends('admin.dashboard')
@section('main-content')
<div class="row">
    <h4>Document Types
        <span>
            <a href="#add_type" class="right waves-effect waves-light green btn modal-trigger">Add Document Type</a>
        </span>
    </h4>
    <div class="col s12">
        <table class="dashboard-table" id="docu-type-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Document Type Name</th>
                    <th>Enabled</th>
                    @if(Auth::user()->department->id == 9 && Auth::user()->role->id == 1)
                    <th>Options</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($docu_type_list as $type)
                    <td>{{$type->id}}</td>
                    <td>{{$type->docu_type}}</td>
                    @if($type->is_disabled == 0)
                        <td>No</td>
                    @else
                        <td>Yes</td>
                    @endif

                    @if(Auth::user()->department->id == 9 && Auth::user()->role->id == 1)                            
                            <td>
                                <a href='#edit' class='waves-effect white waves-green btn-small btn-flat modal-trigger edit' 
                                data-id = "{{$type->id}}" data-name = "{{$type->docu_type}}">
                                    <i class='material-icons'>edit</i>
                                </a>

                                <a href='#disable' class='waves-effect white waves-red btn-small btn-flat modal-trigger disable' 
                                data-id = "{{$type->id}}" data-name = "{{$type->docu_type}}" data-is_disabled = "{{$type->is_disabled}}">
                                    <i class='material-icons'>
                                        @if($type->is_disabled == 0)
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
    <div id="edit_modal_placeholder" class="modal-content">
        <div class="row">
            {!!Form::open(['action' => ['DocuTypeDashboardController@edit'], 'method' => 'POST'])!!}
            <h4>Edit values</h4>
            <div class="input-field col s12">
                <input disabled value="" id="disabled" type="text" class="validate">
            </div>
            <div class="col s12 input-field">
                {{Form::text('docu_type', '', ['id' => 'docu_type'])}}
                {{Form::label('docu_type', 'Enter new value for document type')}}
            </div>
            <input type="hidden" id="docutype_id" name = "docutype_id" value = "">
        </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Send', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

<div id="disable" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4 id="title-disable-placeholder">...</h4>
            {!!Form::open(['action' => ['DocuTypeDashboardController@disable'], 'method' => 'POST'])!!}
            <p id="text-holder">Are you sure you want to chuchu this document?</p>
            <input type="hidden" id="docutype_id_disable" name = "docutype_id_disable" value = "">
        </div>
    </div>
    <div class="modal-footer">
    <a href="#!" class="modal-close waves-effect waves-red btn red">No</a>
      {{Form::submit('Yes', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

<div id="add_type" class="modal">
    <div class="modal-content">
        <div class="row">
            <h4 id="title-add-placeholder">Adding of new document type</h4>
                {!!Form::open(['action' => ['DocuTypeDashboardController@add'], 'method' => 'POST'])!!}
                <div class="col s12 input-field">
                {{Form::text('docu_type', '', ['id' => 'docu_type'])}}
                {{Form::label('docu_type', 'Enter new value for document type')}}
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
    $(document).ready(function(){
        $('#docu-type-table').DataTable({
            pagingType: "simple",
            dom: '<div>pt',
            pageLength: 15,
            language:{
                paginate:{
                    previous: "<i class='material-icons'>chevron_left</i>",
                    next: "<i class='material-icons'>chevron_right</i>"
                }
            }
        });
        $('.modal').modal();
    });

    $(document).on('click', '.edit', function(){
        var dataID = $(this).data('id');
        var docuType = $(this).data('name');
        $('#disabled').val(docuType);
        $('#docutype_id').val(dataID);
    });

    $(document).on('click', '.disable', function(){
        var dataID = $(this).data('id');
        var docuType = $(this).data('name');
        var isDisabled = $(this).data('is_disabled');
        if(isDisabled == 0){
            var p = 'Disable ' + docuType + ' in the lists';
            var t = 'Are you sure you want to disable this document type?'
        }
        else{
            var p = 'Enable ' + docuType + ' in the lists';
            var t = 'Are you sure you want to enable this document type?'
        }
        $('#title-disable-placeholder').text(p);
        $('#text-holder').text(t);
        $('#docutype_id_disable').val(dataID);
    });
</script>
@endpush
