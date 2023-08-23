<!DOCTYPE html>
<html lang="{{ config('app.locale', 'es') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icono app -->
    <link rel="icon" href="{{ asset('img/favicon.png') }}">

    <!-- Title app -->
    <title>{{ config('app.name', 'MERÚ Administrativo') }}</title>

	<!-- Styles -->

    <!-- FontAwesome 5.15 -->
	<link href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <!-- AdminLTE Overlay Scrollbars 1.13.0 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <!-- AdminLTE v3.2.0 --- Bootstrap v4.6.1 -->
	<link href="{{ asset('template/dist/css/adminlte.min.css') }}" rel="stylesheet">

	<!-- Toastr 2.1.4 -->
    <link href="{{ asset('template/plugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css">

	<!-- Select2 4.0.13 -->
	<link href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}"/>
	<link href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
	<link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- LiveWire 2.10 -->
    @livewireStyles
    <!-- Manejo de colores de la plantilla para el sidebar y la aplicación-->

    <style>
        /* Color del header */
        .main-header {
            background-color: rgb(29, 126, 206) !important;
        }

        /* Color del sidebar */
        .main-sidebar {
            background-color: rgb(29, 126, 206) !important;
        }

        /* Color de borde navbar */
        .navbar-dark {
            border-color: #e0e0e0 !important;
        }

        /* Color borde inferior brand */
        .brand-link {
            border-bottom: 1px solid #e0e0e0 !important;
        }

        /* Color borde inferior panel usuario */
        .user-panel {
            border-bottom: 1px solid #e0e0e0 !important;
        }

        /* Color y pading de los items del sidebar */
        .nav-sidebar .nav-item .nav-link {
            color: #fff !important;
            padding: 0.3rem 1rem !important;
        }

        /* Posición ícono de flecha en sidebar */
        .nav-sidebar .nav-link >.right, .nav-sidebar .nav-link > p > .right {
            top: 0.3rem !important;
        }

        /* Color y color de fondo de los items del sidebar on hover*/
        .nav-sidebar .nav-item .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.5) !important;
            color: #000 !important;
        }

        /* Color y color de fonde de los items del sidebar on focus*/
        .nav-sidebar .nav-item .nav-link:focus {
            background-color: rgba(255, 255, 255, 1) !important;
            color: #000 !important;
        }

        /* Color y color de fonde de los items abiertos del sidebar */
        .nav-sidebar .nav-item.menu-open >.nav-link {
            background-color: rgba(255, 255, 255, 0.5) !important;
            color: #000 !important;
        }

    </style>
</head>

<body
    class="hold-transition layout-fixed"
>
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-secondary navbar-dark elevation-4">

            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
            </ul>

            <!-- Center navbar title -->
            <ul class="nav navbar-nav mx-auto">
                <li class="nav-item text-white">
                    <h4>{{ config('app.name') }}<h4>
                </li>
            </ul>

            @if (Auth::check())
                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand">
                    <li class="nav-item dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">{{ Auth::user()->name }}</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Salir
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </ul>
                    </li>
                </ul>
            @endif
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            @yield('sidebar')
        </aside>

        <div class="content-wrapper px-4 py-2">
            @yield('content')
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                {{ ucfirst(\Carbon\Carbon::now()->locale(config('app.locale'))->isoFormat('dddd, DD \d\e MMMM \d\e YYYY')) }}
            </div>
            <strong>Agua para el Progreso</strong>
        </footer>
    </div>

    <!-- Scripts -->

    <!-- jQuery 3.6.0 -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>

    <!-- jQuery-UI 1.13.0 -->
    <script src="{{ asset('template/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>$.widget.bridge('uibutton', $.ui.button);</script>

    <!-- Popper 1.16.1 -->
    <script src="{{ asset('template/plugins/popper/popper.min.js') }}"></script>

    <!-- Bootstrap Bundle 4.6.1 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Bootstrap 4.6.1 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- AdminLET Overlay Scrollbars 1.13.0 -->
    <script src="{{ asset('template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    <!-- AdminLTE 3.2.0 -->
    <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>

    <!-- Data Tables 1.10.20 -->
    <script src="{{ asset('template/plugins/datatables/jquery.dataTables.min.js' ) }}"></script>
    <script src="{{ asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js' ) }}"></script>

    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js' ) }}"></script>

    <!-- Bootstrap Switch 3.3.4 -->
    <script src="{{ asset('template/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

    <!-- Toastr 2.1.4-->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js' ) }}"></script>

    <!-- Select2 4.0.13 -->
    <script src="{{ asset('template/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Bs-custom-file-input -->
    <script src="{{ asset('template/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <!-- Sweetalert -->
    @include('sweetalert::alert')

    <!-- InputMask 5.0.7 -->
    <script src="{{ asset('template/plugins/inputmask/inputmask.min.js') }}"></script>
    <script src="{{ asset('template/plugins/inputmask/bindings/inputmask.binding.js') }}"></script>
    <script src="{{ asset('template/plugins/inputmask/jquery.inputmask.min.js') }}"></script>

    @livewireScripts

    @yield('js')

    @stack('scripts')

    <!-- AlpineJS - Mask 3.10.3 -->
    <script src="{{ asset('js/alpinejs/alpinejs.3.10.3.mask.min.js') }}"></script>

    <!-- AlpineJS 3.10.3 -->
    <script src="{{ asset('js/alpinejs/alpinejs.3.10.3.min.js') }}"></script>

</body>

</html>
