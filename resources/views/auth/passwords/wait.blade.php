@extends('layouts.login')

@section('content')
<div class="section"></div>
<main>
    <center>
        <div class="container">
            <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
                <img class="responsive-img" style="width: 100px;" src="{{url('/images/prrc_logo.png')}}" />
                <h5 class="indigo-text">Password reset request has been sent!</h5>
                <div class='row'>
                    <div class='col s12'>
                        <blockquote>
                            Please wait for your admin's update
                        </blockquote>
                    </div>
                </div>

                <center>
                    <div class='row'>
                        <a href="{{route('start')}}" class="btn blue">Go back</a>
                    </div>
                </center>

            </div>
        </div>
    </center>

    <div class="section"></div>
    <div class="section"></div>
</main>
@endsection
