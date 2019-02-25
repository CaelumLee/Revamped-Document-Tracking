@extends('layouts.app')

@section('content')
@include('inc.snav')
<div class="main">
    <div class = "msg">
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
        {{Form::submit('Submit', ['class'=>'btn green'])}}            
        {!! Form::close() !!} 
        </div>
    </div>
</div>

@stop

@push('scripts')
<script>
var isAdvancedUpload = function() {
  var div = document.createElement('div');
  return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();
if (isAdvancedUpload) {
  $form.addClass('has-advanced-upload');
}
</script>
@endpush