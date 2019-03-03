<?php
use Carbon\Carbon;
?>
@extends('layouts.app')

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

@section('content')
<h4>
  Routing History
  <span class="right">
    <a href="{{route('docu.show', ['id' => $data['id']])}}" class="waves-effect waves-light red btn modal-trigger">
      Back
    </a>
  </span>
</h4>
<table id="routing-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>FROM</th>
            <th>TO</th>
            <th>DATE SENT</th>
            <th>INSTRUCTIONS/REMARKS</th>
            <th>DEADLINE</th>
            <th>DATE COMPLIED</th>
        </tr>
    </thead>
    <tbody>
      @foreach($data['transactions'] as $key => $info)
        <tr>
          <td>{{$key + 1}}</td>
          <td>{{$info->from->username}}</td>
          <td>{{$info->to->username}}</td>
          <td>{{Carbon::parse($info->created_at)->format('Y-m-d H:i:s a')}}</td>
          <td>{{$info->remarks}}</td>
          <td>{{Carbon::parse($info->date_deadline)->format('Y-m-d H:i:s a')}}</td>
          <td>{{Carbon::parse($info->updated_at)->format('Y-m-d H:i:s a')}}</td>
        </tr>
      @endforeach
    </tbody>
</table>
@stop

@push('scripts')
<script>
    $(document).ready(function(){
        $('#routing-table').DataTable({
            pagingType: "simple",
            pageLength: 10,
            dom: '<div>pt',
            language:{
                paginate:{
                    previous: "<i class='material-icons'>chevron_left</i>",
                    next: "<i class='material-icons'>chevron_right</i>"
                }
            },
            order: []
        });
        oTable = $('#routing-table').DataTable();
        $('#autocomplete-input').keyup(function() {
            oTable.search($(this).val()).draw();
        });
    });
  </script>
@endpush