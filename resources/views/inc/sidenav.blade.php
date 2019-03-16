<ul class="sidenav" id="mobile-demo">
    <li>
        <div class="user-view">
            <div class="background">
                <img src="{{asset('images/office.jpg')}}">
            </div>
            <a href="#!user"><img class="circle" src="{{asset('images/unknown.jpg')}}"></a>
            <a href="#!name"><span class="white-text name">{{Auth::user()->username}}</span></a>
            <a href="#!department"><span class="white-text email">{{Auth::user()->department->name}}</span></a>
        </div>
    </li>
    <li>
        <a class='dropdown-trigger' href='#' data-target='create_record'>
            <i class="material-icons">add</i>&nbsp;
            Create Record
        </a>
    </li>
    <li><a href='{{route("home")}}'><i class="material-icons">folder_open</i>&nbsp; All Documents</a></li>
    <li><a href='{{route("docu.index")}}'><i class="material-icons">folder</i>&nbsp; My Documents</a></li>
    <li><a href='{{route("accepted")}}'><i class="material-icons">check</i>&nbsp; Accepted Documents</a></li>
    <li><a href='{{route("inactive")}}'><i class="material-icons">error_outline</i>&nbsp; Inactive Documents</a></li>
    <li><a href='{{route("received")}}'><i class="material-icons">note</i>&nbsp; Received Documents</a></li>
    <li><a href='{{route("archived")}}'><i class="material-icons">delete</i>&nbsp; Archived Documents</a></li>
    @if(Auth::user()->role->name == 'Admin')
    <li><a href='{{route("dashboard")}}'><i class="material-icons">people</i>&nbsp; Admin Dashboard</a></li>
    @endif
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <form action="{{route('logout')}}" method="POST">
            @csrf
            <button class="btn btn-logout" type="submit" style="margin-left:30px;"><i class="fa fa-sign-out"></i>&nbsp;
                Logout </button>
        </form>
    </li>
</ul>

<ul id='create_record' class='dropdown-content'>
    <li><a href='{{route("batch")}}'><i class="material-icons">add_to_photos</i>Batch Adding</a></li>
    <li><a href='{{route("docu.create")}}'><i class="material-icons">mode_edit</i>Manual Adding</a></li>
</ul>
