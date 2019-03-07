@extends('admin.dashboard')

@section('search')
<form class= "hide-on-med-and-down search-on-nav">
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
    <h4>Manage users list on all departments</h4>
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
                @foreach($user_list as $user)
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
                            <a href='#' class='waves-effect waves-light waves-light btn-small btn-flat modal-trigger action-buttons' id='generate_code'><i class='material-icons'>edit</i></a>
                            <a href='#' class='waves-effect waves-light waves-light btn-small btn-flat modal-trigger action-buttons' id='generate_code'><i class='material-icons'>do_not_disturb</i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@push('scripts')
<script>
    $('#users-table').DataTable({
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
    oTable = $('#users-table').DataTable();
    $('#autocomplete-input').keyup(function() {
        oTable.search($(this).val()).draw();
    });
</script>
@endpush
