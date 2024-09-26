<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ config('app.name', ' | BO') }}</title>
    <link rel="shortcut icon" href="{{ asset('image/pdam.png') }}">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('plugin-inspinia/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Animation CSS -->
    <link href="{{ asset('plugin-inspinia/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin-inspinia/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('plugin-inspinia/css/style.css') }}" rel="stylesheet">
</head>

<body id="page-top" class="landing-page no-skin-config">

    @yield('content')

    <!-- Mainly scripts -->
    <script src="{{ asset('plugin-inspinia/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('plugin-inspinia/js/inspinia.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/wow/wow.min.js') }}"></script>

    @yield('onpage-js')

</body>

</html>
