<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="shortcut icon" href="{{ asset('images/logo.svg') }}">
    <link href="{{ url('css/theme.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <link href="{{ url('css/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <script type="text/javascript" src="{{ url('js/bootstrap.bundle.js') }}" defer>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="module" src="{{ url('js/app.js') }}" defer></script>
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>

    <script>
        var userId = "{{ Auth::user() ? Auth::user()->id : NULL}}";
    </script>


</head>

<body>
    <main class="d-flex flex-column vh-100">
        <header>
            <nav id="navbar" class="navbar navbar-expand-lg bg-primary">
                <div class="container-fluid">
                    <h1>
                        <a class="navbar-brand" href="{{ url('/questions?filter=top') }}">
                            <img src="{{ asset('images/logo.svg') }}" alt="Geras Logo" width="64" class="m-2">
                        </a>
                    </h1>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarColor01">
                        <ul class="navbar-nav me-auto">
                            @if (Auth::check() && Auth::user()->type == 'Admin')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Administration</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('/admin/users') }}">Users</a>
                                    <a class="dropdown-item" href="{{ url('/admin/tags') }}">Tags</a>
                                    <!-- <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/admin/statistics') }}">Statistics</a> -->
                            </li>
                            @endif
                            @if (Auth::check())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->username }}</a>
                                <div class="dropdown-menu" style="max-width: 20%;">
                                    <a class="dropdown-item" href="{{ route('user.profile', Auth::user()->id) }}">View Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}">{{ csrf_field() }}<button class="dropdown-item" type="submit">Logout</button></form>
                                </div>
                            </li>
                            @endif
                        </ul>

                        <button id="theme-toggle" class="btn btn-link me-3 text-white" type="button">
                            <i class="bi bi-brightness-high-fill d-block-light d-none"></i>
                            <i class="bi bi-moon-stars-fill d-block-dark d-none"></i>
                        </button>

                        <div class="d-flex flex-row" style="max-width: 70%;">
                            <form class="d-flex" action="{{ route('search') }}" method="GET">
                                {{ csrf_field() }}
                                @method('GET')
                                <input id="search-bar" class="form-control me-sm-2" type="search" name="searchTerm" placeholder="Search">
                                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                            </form>
                        </div>

                        @if(Auth::check())
                        <div style="margin-left: 1em; margin-right: 1em;">
                            <button id="notification-button" type="button" class="btn btn-secondary position-relative">
                                <i id="notification-icon" class="bi bi-bell-fill"></i>
                                @if(Auth::user()->getUnreadNotificationsAttribute())
                                <span id="notification-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{Auth::user()->getUnreadNotificationsAttribute()}}<span class="visually-hidden">unread messages</span></span>
                                @endif
                            </button>
                        </div>
                        @endif

                        @if (!Auth::check() && Route::currentRouteName() != 'register' && Route::currentRouteName() != 'login')
                        <a class="btn btn-secondary ms-3" href="{{ url('/login') }}">Login / Register</a>
                        @endif
                    </div>
                    @if(Auth::check())
                    <ul id="notifications" class="d-none list-group align-items-center d-flex flex-column list-unstyled position-absolute mt-1" style="z-index: 200; top:100%; min-width: 30dvw; min-height: 20dvh;">
                        @include('partials.notificationsCard')
                    </ul>
                    @endif
                </div>
            </nav>
        </header>
        @yield('content')
    </main>
</body>

</html>