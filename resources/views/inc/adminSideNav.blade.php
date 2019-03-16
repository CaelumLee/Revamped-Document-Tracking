<div class="snav hide-on-med-and-down">
    <br>
    <a href='{{route("home")}}'><i class="material-icons">arrow_back</i>&nbsp; Back to homepage</a>
    <a href='{{route("dashboard")}}'><i class="material-icons">trending_up</i>&nbsp; Statistics Table</a>
    @if(Auth::user()->department->id == 9 && Auth::user()->role->id == 1)
    <a class='dropdown-trigger' href='#' data-target='user_list'><i class="material-icons">person</i>&nbsp; User</a>
    @else
    <a href='{{route("userlists")}}'><i class="material-icons">people</i>&nbsp; User's List</a>
    @endif
    <a href='{{route("docuType")}}'><i class="material-icons">assignment</i>&nbsp; Types of Docus</a>
    <a href='{{route("holidays")}}'><i class="material-icons">date_range</i>&nbsp; Holidays List</a>
    <a href='{{route("department")}}'><i class="material-icons">date_range</i>&nbsp; Departments List</a>
</div>

<ul id='user_list' class='dropdown-content'>
    <li><a href='{{route("userlists")}}'><i class="material-icons">people</i>&nbsp; User's List</a></li>
    <li><a href="{{route('allUsers')}}"><i class="material-icons">person_pin</i>&nbsp; Manage Users</a></li>
</ul>
