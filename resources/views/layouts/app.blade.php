<!doctype html>
<html lang="{{ config('app.locale', 'es') }}">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icono app -->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}">

    <!-- Title app -->
    <title>{{ config('app.name', 'MERÚ Administrativo') }}</title>

    <!-- Styles -->
    <!-- AdminLTE v3.2.0 --- Bootstrap v4.6.1 -->
    <link href="{{ asset('template/dist/css/adminlte.min.css') }}" rel="stylesheet">

    <!-- FontAwesome 5.15 -->
    <link href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css" />

</head>
<body>

    <div>
        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

    @yield('js')
</body>
</html>
