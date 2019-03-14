<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/jquery.dataTables.min.css')}}">

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <title>{{config('app.name')}}</title>

    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body>
    @include('inc.topnav')
    @include('inc.dropdowns')
    @include('inc.sidenav')
    <div id="body">
        @yield('content')
    </div>

</body>

<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>

<script>
    var url = '{{URL::to('/')}}';
    window.Laravel = [$('meta[name="csrf-token"]').attr('content')];
    window.notif_url = ['{{route("notifs")}}'];

    window.Laravel.userId = <?php echo auth()->user()->id; ?>
</script>

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->

<script src="{{asset('js/jquery.dataTables.min.js')}}"></script>

<script src="{{asset('js/app.js')}}"></script>

@stack('scripts')

</html>