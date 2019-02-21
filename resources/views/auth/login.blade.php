<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

     <!--Import Google Icon Font-->
     <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <title>{{config('app.name')}}</title>

    <style>
    body {
      display: flex;
      min-height: 100vh;
      flex-direction: column;
    }

    main {
      flex: 1 0 auto;
    }

    body {
      background: #fff;
    }

    .input-field input[type=date]:focus + label,
    .input-field input[type=text]:focus + label,
    .input-field input[type=password]:focus + label {
      color: #e91e63;
    }

    .input-field input[type=date]:focus,
    .input-field input[type=text]:focus,
    .input-field input[type=password]:focus {
      border-bottom: 2px solid #e91e63;
      box-shadow: none;
    }
  </style>

<body>

<div class="section"></div>
  <main>
    <center>
      <!-- <img class="responsive-img" style="width: 100px;" src="{{url('/images/prrc_logo.png')}}" /> -->
      <!-- <div class="section"></div> -->

      <!-- <h5 class="indigo-text">Please, login into your account</h5> -->
      <!-- <div class="section"></div> -->

      <div class="container">
        <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
        <img class="responsive-img" style="width: 100px;" src="{{url('/images/prrc_logo.png')}}" />
        <h5 class="indigo-text">Please, login into your account</h5>
          <form class="col s12" method="POST" action="{{ route('login') }}">
          @csrf
            <div class='row'>
              <div class='col s12'>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>
                <label for='username'>Enter your username</label>
                @if ($errors->has('username'))
                    <span class="message red-text" role="alert">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <!-- <input class='validate' type='password' name='password' id='password' /> -->
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                <label for='password'>Enter your password</label>
                @if ($errors->has('password'))
                    <span class="message red-text" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
              </div>
                <!-- <label style='float: right;'>
                    <a class='pink-text' href='#!'><b>Forgot Password?</b></a>
                </label> -->
            </div>

            <br />
            <center>
              <div class='row'>
                <button type="submit" class="btn btn-primary">
                    {{ __('Login') }}
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

</body>

<script
src="https://code.jquery.com/jquery-3.3.1.min.js"
integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
crossorigin="anonymous"></script>

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</html>

