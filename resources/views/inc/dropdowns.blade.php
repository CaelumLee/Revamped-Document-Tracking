<ul id='create_dropdown' class='dropdown-content'>
    <li>
        <a href='{{route("batch")}}'><i class="material-icons">add_to_photos</i>Batch Adding</a>
    </li>
    <li><a href='{{route("docu.create")}}'><i class="material-icons">mode_edit</i>Manual Adding</a></li>
</ul>

<ul id='notif_dropdown' class='dropdown-content'>
    <li class="header">
        <h6 class="dropdown-toolbar-title">Notifications 
        (<span class="notif-count">0</span>)
        <span class="right">
            <a href='{{route("readAll")}}' class="black-text hover">Mark all as read</a>    
        </span>
        </h6>
    </li>
    <ul class="dropdown-menu">
    </ul>
    <li class="divider" tabindex="-1"></li>
    <li><a href="#" class="center">View All</a></li>
</ul>

<ul id='user_dropdown' class='dropdown-content'>
    <li>
        <form action="{{route('logout')}}" method="POST">
            @csrf    
            <button class="btn-small btn-logout" type="submit">Logout </button>
        </form>
    </li>
</ul>