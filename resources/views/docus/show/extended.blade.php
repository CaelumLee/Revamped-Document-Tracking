<div class="nav-content">
    <ul class="tabs tabs-transparent">
    <li class="tab">
      <a href='{{route("home")}}' target = "_self">Back</a>
    </li>

    <li class="tab">
        <a href='{{route("route_info", ["id" => $data["docu"]->id])}}' target="_self">View Routing Info</a>
    </li>

    <li class="tab">
        <a href="{{route('responses', ['id' => $data['docu']->id])}}" target='_self'>User Responses</a>
    </li>

    <li class="tab disabled"><a href="#">Conver to PDF</a></li>

    @if($data['docu']->deleted_at == null && Auth::user()->role->name == 'Admin')
        <li class="tab"><a href="#archiveConfirm" class="modal-trigger">Archive</a></li>
    @elseif($data['docu']->deleted_at != null && Auth::user()->role->name == 'Admin')
        <li class="tab"><a href="#restoreRecord" class="modal-trigger">Restore</a></li>
    @endif

    </ul>
</div>