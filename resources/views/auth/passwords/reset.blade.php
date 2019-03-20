@extends('layouts.login')

@section('content')
<div class="section"></div>
<main>
    <center>
        <div class="container">
            <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
                <img class="responsive-img" style="width: 100px;" src="{{url('/images/prrc_logo.png')}}" />
                <h5 class="indigo-text">Request for Resetting Password</h5>
                <form class="col s12" method="POST" action="{{ route('findUser') }}">
                    @csrf
                    <div class='row'>
                        <div class='col s12'>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='input-field col s12'>
                            <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                name="username" value="{{ old('username') }}" required autofocus>
                            <label for='username'>Enter your username</label>
                            @if ($errors->has('username'))
                            <span class="message red-text" role="alert">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <blockquote>
                            The admin of MIS Departmen will receive a notification and you'll wait for
                            <br>
                            his/her update
                        </blockquote>
                    </div>

                    <br />
                    <center>
                        <div class='row'>
                            <button type="submit" class="btn btn-primary blue">
                                {{ __('Request') }}
                            </button>
                        </div>
                    </center>
                </form>
            </div>
        </div>
    </center>

    <div class="section"></div>
    <div class="section"></div>
</main>
@endsection
