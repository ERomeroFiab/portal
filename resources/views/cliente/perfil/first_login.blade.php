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
    {{-- <link href="{{asset('assets/vendor/font-awesome/css/all.min.css')}}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- datatables styles --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.6/dist/sweetalert2.min.css">

    <!-- CSS Files -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/now-ui-dashboard.min.css?v=1.1.0')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{ URL::asset('css/font.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('css/bt.css') }}" />

    @yield('customcss')
</head>


<body class="sidebar-mini">
    <div class="wrapper">


        {{-- SIDEBAR --}}
        @include('partials.sidebar')

        {{-- MAINPANEL --}}
        <div class="main-panel custom-gradient-fiabilis">
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
                                <span class="mx-1">{{ auth()->user()->name }} ({{ auth()->user()->rol }}) </span>
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

            <div class="panel-header panel-header-sm custom-gradient-fiabilis"></div>

            <!-- Start Content -->
            <div class="content">

                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row my-5">
                                    <div class="col-12">
                                        <h5 class="text-center">Primer Inicio de Sesión</h5>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-12">
                                        <h6 class="text-center">Antes de continuar es necesario que actualice su contraseña y su email</h6>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-5">
                                        <form action="{{ route('cliente.change_password_first_time') }}" method="POST">
                                            @csrf

                                            <div class="form-group">
                                                <label>Nueva Contraseña (Mínimo 8 caracteres)</label>
                                                <input name="password" type="password" class="form-control" autocomplete="off">
                                                @error('password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Email</label>
                                                <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                                                @error('email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <input type="submit" class="btn btn-sm btn-success float-right" value="Registrar cambios" autocomplete="off">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>


                
            </div> <!-- End Content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="copyright">
                        © {{ date('Y') }}, Developed by <a href="https://www.fiabilis.cl/" target="_blank">Fiabilis</a>.
                        Soporte: portalclientes@fiabiliscg.com
                    </div>
                </div>
            </footer>
        </div>
            


    </div>
    <!--   Core JS Files   -->
    <script src="{{asset('assets/js/core/jquery.min.js?v=1645015120')}}"></script>
    <script src="{{asset('assets/js/core/popper.min.js?v=1645015120')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js?v=1645015120')}}"></script>
    <script src="{{asset('assets/js/plugins/perfect-scrollbar.jquery.min.js?v=1645015120')}}"></script>
    <script src="{{asset('assets/js/now-ui-dashboard.min.js?v=1645015120')}}" type="text/javascript"></script>

    {{-- START-DATATBLES --}}
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.6/dist/sweetalert2.min.js"></script>

    {{-- Custom Scripts --}}
    @yield('customjs')
</body>

</html>