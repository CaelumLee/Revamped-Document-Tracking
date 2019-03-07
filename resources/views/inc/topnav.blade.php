<div class="navbar-fixed">
    <nav class='blue darken-4'>
        <div class="nav-wrapper">
            <a href="{{URL::to('/')}}/home" class="brand-logo">
                <img src="{{asset('images/new_logo.png')}}" class="responsive-img"
                style ="width:270px; margin-top:5px;"
                />
            </a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                @if(Auth::user()->role->name == 'Encoder' || Auth::user()->role->name == 'Admin')
                <li>
                    <a class='dropdown-trigger' href="#" data-target='create_dropdown'>
                        <i class="material-icons left">add</i>Create
                    </a>
                </li>
                @endif
                <li class = "dropdown-notification">
                    <a class='dropdown-trigger' href="#" data-target='notif_dropdown'>
                        <span class="new badge red">0</span>
                        <i data-count="0" class="material-icons left">notifications</i>
                        <span class="notifications-text">Notif</span>
                    </a>
                </li>
                <li>
                    <a class='dropdown-trigger' href="#" data-target='user_dropdown'>
                        <i class="material-icons left">account_circle</i>{{Auth::user()->username}}
                    </a>
                </li>
            </ul>
            @yield('search')
        </div>
        @yield('extended_nav')
    </nav>
</div>