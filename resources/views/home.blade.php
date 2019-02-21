<?php
use App\User;
use App\Statuscode;
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
@include('inc.snav')
<div class="main">
    <div class="msg">
        @include('inc.message')  
    </div>
    <h4>{{$title}}</h4>
    <table class="bordered" id="docus-table">
        <thead>
            <tr>
            <th style="width:15%;">REFERENCE NUMBER</th>
            <th style="width:30%;">SUBJECT</th>
            <th style="width:20%;">CREATOR</th>
            <th style="width:25%;">FINAL ACTION DATE</th>
            <th style="width:10%;">STATUS</th>
            </tr>
        </thead>
        <tbody>
          @foreach($docus as $docu)
            @if($docu->is_rush)
              <tr class = "red lighten-2">
            @else
              <tr>
            @endif
                <td>{{$docu->reference_number}}</td>
                <td><a href="{{route('docu.show', ['id' => $docu->id])}}">{{$docu->subject}}</a></td>
                <td>{{User::where('id', $docu->creator)->first()->username}}</td>
                <td>{{$docu->final_action_date}}</td>
                <td>{{Statuscode::where('id', $docu->progress)->first()->status}}</td>
              </tr>
          @endforeach
        </tbody>
    </table>
</div>    
@stop

@push('scripts')
<script>
     $('#docus-table').DataTable({
        pagingType: "simple",
        pageLength: 15,
        dom: '<div>pt',
        language:{
            paginate:{
                previous: "<i class='material-icons'>chevron_left</i>",
                next: "<i class='material-icons'>chevron_right</i>"
            }
        },
        order: []
    });
    oTable = $('#docus-table').DataTable();
    $('#autocomplete-input').keyup(function() {
        oTable.search($(this).val()).draw();
    });
</script>
@endpush
