<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('tituloPagina')</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <link rel="stylesheet" href="{{ asset('css/fontawesome-free/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/fullcalendar/main.css') }}">
        <link rel="stylesheet" href="{{ asset('css/adminlte/adminlte.min.css') }}">
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">

            @include('layouts.navbar')
            @include('layouts.sidebar')
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @yield('contenido')
            </div>
            <!-- /.content-wrapper -->
            @include('layouts.footer')
        <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>

        <!-- Bootstrap -->
        <script src="{{ asset('js/bootstrap/bootstrap.bundle.js')}}"></script>

        <!-- jQuery UI -->
        <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>

        <!-- AdminLTE App -->
        <script src="{{ asset('js/adminlte/adminlte.min.js') }}"></script>

        @yield('scriptsPagina')
    </body>
</html>
