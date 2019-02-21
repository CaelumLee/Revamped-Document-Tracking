@if(count($errors) > 0)
    <div class="message red white-text" style = "padding : 20px;">
        @foreach($errors->all() as $error)
            <p> {{$error}} </p>
        @endforeach
    <i class="material-icons right-align alert_close">close</i>
    </div>
@endif

@if(session()->has('error'))
    <div class="message red white-text" style = "padding : 20px;">
        {{session()->get('error')}}
        <i class="material-icons right-align alert_close">close</i>
    </div>
@endif

@if(session()->has('success'))
    <div class="message green white-text" style = "padding : 20px;">
        {{session()->get('success')}}
        <i class="material-icons right-align alert_close">close</i>
    </div>
@endif