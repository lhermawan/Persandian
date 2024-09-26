<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name', ' | VMS') }}</title>
    <link rel="shortcut icon" href="{{ asset('image/logo.png') }}">

    <link href="{{ asset('plugin-inspinia/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin-inspinia/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin-inspinia/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin-inspinia/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin-inspinia/css/style.css') }}" rel="stylesheet">

</head>

<body class="white-bg">

    @yield('content')

    <!-- Mainly scripts -->
    <script src="{{ asset('plugin-inspinia/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/bootstrap.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('plugin-inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>

    @yield('onpage-js')

</body>

</html>
