<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ url('css/lumen.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <link href="{{ url('css/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src={{ url('js/app.js') }} defer>
    </script>

</head>

<body>
    <script type="text/javascript" src={{ url('js/bootstrap.bundle.js') }} defer>
    </script>
    <main class="d-flex flex-column vh-100">
        <!-- <main style="height: 85dvh;"> -->
        <header>
            <nav id="navbar" class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
                <div class="container-fluid">
                    <h1><a class="navbar-brand" href="{{ url('/questions?filter=top') }}">
                            <img src="{{ asset('images/logo.svg') }}" alt="Geras Logo" width="64" class="m-2">
                        </a></h1>
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
                                    <!-- <a class="dropdown-item" href="{{ url('/admin/tags') }}">Tags</a> -->
                                    <!-- <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/admin/statistics') }}">Statistics</a> -->
                            </li>
                            @endif
                            @if (Auth::check())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->username }}</a>
                                <div class="dropdown-menu" style="max-width: 20%;">
                                    <a class="dropdown-item" href="{{ route('users.profile', Auth::user()->id) }}">View Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}">{{ csrf_field() }}<button class="dropdown-item" type="submit">Logout</button>
                                </div>
                            </li>
                            @endif
                        </ul>
                        <div class="d-flex flex-row" style="max-width: 70%;">
                            <form class="d-flex" action="{{ route('search') }}" method="GET">
                                {{ csrf_field() }}
                                @method('GET')
                                <input class="form-control me-sm-2" type="search" name="searchTerm" placeholder="Search">
                                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                            </form>
                        </div>

                        @if (!Auth::check() && Route::currentRouteName() != 'register' && Route::currentRouteName() != 'login')
                        <a class="btn btn-secondary ms-3" href="{{ url('/login') }}">Login / Register</a>
                        @endif
                    </div>
                </div>
            </nav>
        </header>
            @yield('content')
    </main>
</body>

</html>