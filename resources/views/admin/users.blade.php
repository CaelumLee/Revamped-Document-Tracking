@extends('admin.dashboard')
@section('main-content')
<div class="row">
    <h4>Users list on {{Auth::user()->department->name}}</h4>
    <div class="col s12">
        <table class="dashboard-table" id="users-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user_list as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->username}}</td>
                        <td>{{$user->role->name}}</td>
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
</script>
@endpush
