@extends('layouts.app')

@section('content')
@include('inc.snav')
<div class="main">
    <div class="msg">
        @include('inc.message')
    </div>
    <h4>{{$title}}</h4>
    {!! Form::open(['action' => 'BatchController@add', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    {{Form::hidden('user_id', Auth::user()->id)}}
    <div class="row">
        <div class="col s12">
            <div class="file-field input-field">
                <div class="btn">
                    <span>File</span>
                    <input type="file" name="batch_upload">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="Upload an excel file">
                </div>
            </div>
        </div>
        <div class="col s6">
            {{Form::submit('Submit', ['class'=>'btn green', 'id' => 'submit_button'])}}
            {!! Form::close() !!}
        </div>
    </div>
</div>

@include('layouts.preloader')

@stop

@push('scripts')
<script>
    (function ($) {
        $.fn.invisible = function () {
            $(this).css("visibility", "hidden");
        };
        $.fn.visible = function () {
            $(this).css("visibility", "visible");
        };
    }(jQuery));

    $('#submit_button').click(function () {
        $('.preloader-background').visible();
        $('.preloader-wrapper').visible();
    })

</script>
@endpush
