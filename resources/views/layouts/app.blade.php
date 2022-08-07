<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        @stack('css')
        <style>
        .nav-link i{
            color: grey;
            font-size: 30px;
        }
        </style>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <button 
                    class="navbar-toggler" 
                    type="button" 
                    data-toggle="collapse" 
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" 
                    aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}"
                >
                    <span 
                        class="navbar-toggler-icon"
                    >
                    </span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        @guest
                            <li class="nav-item">
                                {{-- Tinder Logoでindex画面のリンクになります。 --}}
                                <a class="navbar-brand" href="{{ url('/welcome') }}">
                                    welcome
                                </a>
                            </li>
                        @endif
                    </ul>
                        @auth
                            <ul class="navbar-nav mx-auto align-items-center justify-content-around flex-grow-1">
                                <li class="nav-item dropdown">
                                    <a 
                                        id="navbarDropdown" 
                                        class="nav-link" 
                                        href="#" 
                                        role="button" 
                                        data-toggle="dropdown"
                                        aria-haspopup="true" 
                                        aria-expanded="false" 
                                        v-pre
                                    >
                                        <i class="fa fa-cog fa-2x" aria-hidden="true"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ url('/') }}">
                                            top
                                        </a>
                                        <a 
                                            class="dropdown-item" 
                                            href="{{ route('logout') }}" 
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                                        >
                                            {{ __('Logout') }}
                                        </a>

                                        <form 
                                            id="logout-form" 
                                            action="{{ route('logout') }}" 
                                            method="POST"
                                            class="d-none"
                                        >
                                            @csrf
                                        </form>
                                    </div>
                                </li>

                                <li class="nav-item">
                                    {{-- Tinder Logoでindex画面のリンクになります。 --}}
                                    <a class="navbar-brand" href="{{ url('/users') }}">
                                        <img 
                                            src="https://worldvectorlogo.com/logos/tinder-1.svg" 
                                            alt="Tinder Logo"
                                            title="Tinder Logo" 
                                            style="width: 100px"
                                        >
                                    </a>
                                </li>
                                    
                                <li class="nav-item">
                                    {{-- commentsのアイコンですが、マッチしたusers一覧画面になります。 --}}
                                    <a class="nav-link" href="{{ route('users.matches') }}">
                                        <i class="fa fa-comments fa-2x" aria-hidden="true"></i>
                                    </a>
                                </li>
                            </ul>
                        @endauth

                        <ul class="navbar-nav ml-auto">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a 
                                            class="nav-link" 
                                            href="{{ route('login') }}"
                                        >
                                            {{ __('Login') }}
                                        </a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a 
                                            class="nav-link" 
                                            href="{{ route('register') }}"
                                        >
                                            {{ __('Register') }}
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
            {{-- /nav --}}

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    {{-- Scripts --}}
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}" ></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" ></script>
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('js')
</body>
</html>
