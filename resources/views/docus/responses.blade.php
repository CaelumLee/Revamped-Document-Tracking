<?php
use Carbon\Carbon;
?>
@extends('layouts.app')

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

@section('content')
<h4>
    User Responses
    <span class="right">
        <a href="{{route('docu.show', ['id' => $data['id']])}}" class="waves-effect waves-light red btn modal-trigger">
            Back
        </a>
    </span>
</h4>
<table id="responses-table">
    <thead>
        <tr>
            <th>FROM</th>
            <th>TO</th>
            <th>SEEN</th>
            <th>RECEIVED</th>
            <th>SENT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['transactions'] as $info)
        <tr>
            <td>{{$info->from->username}}</td>
            <td>{{$info->to->username}}</td>

            @if($info->seen_at == null)
            @php($seen_at = '------')

            @else
            @php($seen_at = Carbon::parse($info->seen_at)->format('Y-m-d H:i:s a'))
            @endif
            <td>{{$seen_at}}</td>

            @if($info->received_at == null)
            @php($received_at = '------')

            @else
            @php($received_at = Carbon::parse($info->received_at)->format('Y-m-d H:i:s a'))
            @endif
            <td>{{$received_at}}</td>

            @if($info->sent_at == null)
            @php($sent_at = '------')

            @else
            @php($sent_at = Carbon::parse($info->sent_at)->format('Y-m-d H:i:s a'))
            @endif
            <td>{{$sent_at}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop

@push('scripts')
<script>
    $(document).ready(function () {
        $('#responses-table').DataTable({
            pagingType: "simple",
            pageLength: 15,
            dom: '<div>pt',
            language: {
                paginate: {
                    previous: "<i class='material-icons'>chevron_left</i>",
                    next: "<i class='material-icons'>chevron_right</i>"
                }
            },
            order: []
        });
        oTable = $('#responses-table').DataTable();
        $('#autocomplete-input').keyup(function () {
            oTable.search($(this).val()).draw();
        });
    });

</script>
@endpush
