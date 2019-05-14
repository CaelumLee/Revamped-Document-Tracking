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
        <div class="col s12">
            <i>
            Download the latest sample of 
            <a href="{{asset('Excel_Document_Records_Sample.xlsx')}}" download>
                excel file
            </a>
            for batch upload on the link.
            </i>
        </div>
        
        <div class="col s6" style="margin-top:10px;">
            {{Form::submit('Submit', ['class'=>'btn green', 'id' => 'submit_button'])}}
            {!! Form::close() !!}
        </div>
    </div>

    <div class="divider"></div>

    <div class="section">
        <h4>Instructions</h4>
        <p>The following are the instruction in filling up the provided Excel File:</p>
        <ul class="instructions_lists">
            <li>
            •	Rush field needs to be filled up with “yes” or “no”, regardless of the capitalization*
            </li>
            <li>
            •	Iso number is filled with the format of eg: DDMMYYYYOED-FO-001-Rev.00
            </li>
            <li>
            •	Confidential field needs to be filled up with “yes” or “no”, regardless of the capitalization*
            </li>
            <li>
            •	Complexity field needs to be filled up with “simple” or “complex”, regardless of the capitalization*
            </li>
            <li>
            •	Sender field is filled up with the sender name, regardless from inside or outside the organization*
            </li>
            <li>
            •	Sender Address field is filled up with the sender address, regardless from inside or outside the organization*
            </li>
            <li>
            •	Type of Document field needs to be filled with the following only: Memorandum, Office Order, Letter, Projects/Project Proposal, Financial Documents, Uncategorized, regardless of capitalization*
            </li>
            <li>
            •	Final Action Date needs to be filled with the format of yyyy-mm-dd*
            </li>
            <li>
            •	Route To field needs to be filled with the username in the format of eg: jlee.
                <br> &nbsp &nbsp &nbsp
                Incase of multiple users the format will be: eg. jlee,jlucero,lignacio,fnohay*
                <br>
            </li>
            <li>
            •	Remarks field needs to be filled with the comments/remarks of the document*
            </li>
            <li>
            •	Deadline for Routing Info field needs to be filled with the format of yyyy-mm-dd*
            </li>
        </ul>
        <blockquote>Note: The following with asterisk (*) are required to be filled.</blockquote>
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
