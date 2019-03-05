@extends('layouts.login')

@section('content')
<div class="section"></div>
  <main>
    <center>
      <div class="container">
        <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
        <img class="responsive-img" style="width: 100px;" src="{{url('/images/prrc_logo.png')}}" />
        <h5 class="indigo-text">Please, change your default password</h5>
          <form class="col s12" method="POST" action="{{ route('first') }}">
          @csrf
            <div class='row'>
              <div class='col s12'>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" required autofocus>
                <label for='password'>Enter your new password</label>
                @if ($errors->has('password'))
                    <span class="message red-text" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                <!-- <label for='confirm'>Confirm</label> -->
                <label for="password-confirm">{{ __('Confirm Password') }}</label>
                @if ($errors->has('confirm'))
                    <span class="message red-text" role="alert">
                        <strong>{{ $errors->first('confirm') }}</strong>
                    </span>
                @endif
              </div>
            </div>

            <br />
            <center>
              <div class='row'>
                <button type="submit" class="btn btn-primary blue">
                    {{ __('Change Password') }}
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