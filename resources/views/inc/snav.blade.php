<div class="snav hide-on-med-and-down">
    <br>
    <a href='{{route("home")}}'><i class="material-icons">folder_open</i>&nbsp; All Documents</a>
    <a href='{{route("docu.index")}}'><i class="material-icons">folder</i>&nbsp; My Documents</a>
    <a href='{{route("accepted")}}'><i class="material-icons">check</i>&nbsp; Accepted Documents</a>
    <a href='{{route("inactive")}}'><i class="material-icons">error_outline</i>&nbsp; Inactive Documents</a>
    <a href='{{route("received")}}'><i class="material-icons">note</i>&nbsp; Received Documents</a>
    <a href='{{route("archived")}}'><i class="material-icons">delete</i>&nbsp; Archived Documents</a>
    <a href='{{asset("PRRC-MobileApp.apk")}}' target="_blank" download>
        <i class="material-icons">android</i>&nbsp; APK File Download
    </a>
    @if(Auth::user()->role->name == 'Admin')
    <a href='{{route("dashboard")}}'><i class="material-icons">people</i>&nbsp; Admin Dashboard</a>
    @endif
</div>
