<?php
use App\Statuscode;
// use Carbon\Carbon;
?>
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col s12">
            <h5>Record : {{$data['docu']->reference_number}} 
                &nbsp;&nbsp;    
                <span class ="blue white-text" style="padding:4px 3px;">
                    {{Statuscode::whereId($data['docu']->progress)->first()->status}}
                </span>
            </h5>
        </div>

        @include('docus.show.details')
        @include('docus.show.uploads')
        @include('docus.show.transactions')
        
    </div>
@stop

@push('scripts')

@endpush