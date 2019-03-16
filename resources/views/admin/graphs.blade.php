@extends('admin.dashboard')
@section('main-content')
<div class="row">
    <div class="col s12">
        <h2>Dashboard for {{Auth::user()->department->name}}</h2>
    </div>

    <div class="col l3 m6 s12">
        <div class="card" style="border : 2px solid green;">
            <div class="card-stacked">
                <div class="card-content">
                    <data-counter icon="create" v-bind:start="0" v-bind:end="{{$data_values['a']}}"></data-counter>
                    <p>Total records created on this </p>
                    <p>department for this month</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col l3 m6 s12">
        <div class="card" style="border : 2px solid gray;">
            <div class="card-stacked">
                <div class="card-content">
                    <data-counter icon="error_outline" v-bind:start="0" v-bind:end="{{$data_values['b']}}"></data-counter>
                    <p>Total records inactive on this </p>
                    <p>department for this month</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col l3 m6 s12">
        <div class="card" style="border : 2px solid yellow;">
            <div class="card-stacked">
                <div class="card-content">
                    <data-counter icon="check_box" v-bind:start="0" v-bind:end="{{$data_values['c']}}"></data-counter>
                    <p>Total records approve on this</p>
                    <p>department for this month</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col l3 m6 s12">
        <div class="card" style="border : 2px solid red;">
            <div class="card-stacked">
                <div class="card-content">
                    <data-counter icon="archive" v-bind:start="0" v-bind:end="{{$data_values['d']}}"></data-counter>
                    <p>Total records archived on this </p>
                    <p>department for this month</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col s12">
        <div class="card" style="border : 2px solid black;">
            <bar-chart docu_count_url="{{route('DocuJson')}}" department_list_url="{{route('DeptList')}}"></bar-chart>
        </div>
    </div>

</div>
@stop

@push('scripts')
<script src="{{asset('js/graphs.js')}}"></script>
@endpush
