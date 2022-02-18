<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('assets/img/brand/favicon.png')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Portal Cliente</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no"
        name="viewport">
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet">
    <link href="{{asset('assets/vendor/font-awesome/css/all.min.css')}}" rel="stylesheet">
    <!-- CSS Files -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/now-ui-dashboard.min.css?v=1.1.0')}}" rel="stylesheet">
    @yield('customcss')
</head>


<body class="sidebar-mini">
    <div class="wrapper">

        @auth

            @if ( auth()->user()->rol === "Administrador" )
                @include('administrador.partials.sidebar')
            @else
                @include('partials.sidebar')
            @endif

            <div class="main-panel">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-transparent  navbar-absolute bg-primary fixed-top">
                    <div class="container-fluid">
                        <div class="navbar-wrapper">
                            <div class="navbar-toggle">
                                <button type="button" class="navbar-toggler">
                                    <span class="navbar-toggler-bar bar1"></span>
                                    <span class="navbar-toggler-bar bar2"></span>
                                    <span class="navbar-toggler-bar bar3"></span>
                                </button>
                            </div>
                            <a class="navbar-brand">Portal Cliente</a>
                        </div>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                            aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navigation">

                            <ul class="navbar-nav">


                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navUserDropdown"
                                        data-toggle="dropdown">
                                        <!-- <img height="16" src="#"> -->
                                        <span class="mx-1">{{ auth()->user()->name }} </span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navUserDropdown">
                                        <a class="dropdown-item" href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- End Navbar -->

                <div class="panel-header panel-header-sm"></div>

                <!-- Start Content -->
                <div class="content">
                    @yield('content')
                </div> <!-- End Content -->

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="copyright">
                            © 2022, Developed by <a href="https://www.fiabilis.cl/" target="_blank">Fiabilis</a>.
                        </div>
                    </div>
                </footer>
            </div>

        @else

            @include('partials.sidebar')

            <div class="main-panel">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-transparent  navbar-absolute bg-primary fixed-top">
                    <div class="container-fluid">
                        <div class="navbar-wrapper">
                            <div class="navbar-toggle">
                                <button type="button" class="navbar-toggler">
                                    <span class="navbar-toggler-bar bar1"></span>
                                    <span class="navbar-toggler-bar bar2"></span>
                                    <span class="navbar-toggler-bar bar3"></span>
                                </button>
                            </div>
                            <a class="navbar-brand">Portal Cliente</a>
                        </div>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                            aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navigation">

                            <ul class="navbar-nav">

                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">{{ __('Login') }}</a>
                                </li>
                                <li class="nav-item">
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">{{ __('Register') }}</a>
                                    @endif
                                </li>

                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- End Navbar -->

                <div class="panel-header panel-header-sm"></div>

                <!-- Start Content -->
                <div class="content">
                    @yield('content')
                </div> <!-- End Content -->

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="copyright">
                            © 2022, Developed by <a href="https://www.fiabilis.cl/" target="_blank">Fiabilis</a>.
                        </div>
                    </div>
                </footer>
            </div>
            
        @endauth


    </div>
    <!--   Core JS Files   -->
    <script src="{{asset('assets/js/core/jquery.min.js?v=1645015120')}}"></script>
    <script src="{{asset('assets/js/core/popper.min.js?v=1645015120')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js?v=1645015120')}}"></script>
    <script src="{{asset('assets/js/plugins/perfect-scrollbar.jquery.min.js?v=1645015120')}}"></script>
    <script src="{{asset('assets/js/now-ui-dashboard.min.js?v=1645015120')}}" type="text/javascript"></script>

    {{-- Custom Scripts --}}
    @yield('customjs')
</body>

</html>