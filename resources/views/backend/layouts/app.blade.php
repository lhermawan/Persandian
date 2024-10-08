<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', ' | BO') }}</title>
    <link rel="shortcut icon" href="{{ asset('image/logo.png') }}">
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <link href="{{ asset('plugin-inspinia/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Toastr style -->
    <link href="{{ asset('plugin-inspinia/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">

    <!-- Font Awesome style -->
    <link href="{{ asset('plugin-inspinia/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="{{ asset('plugin-inspinia/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">

    <!-- Jasny File Input -->
    <link href="{{ asset('plugin-inspinia/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin-inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">

    <!-- Custom style -->
    <link href="{{ asset('plugin-inspinia/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin-inspinia/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin-inspinia/css/plugins/iCheck/custom.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @yield('onpage-css')

</head>

<body>
    <div id="wrapper">

        <nav class="navbar-default navbar-static-side" role="navigation">
            @guest
            @else
                <div class="sidebar-collapse">
                    <ul class="nav metismenu" id="side-menu">
                        <li class="nav-header">
                            <div class="dropdown profile-element center">
                                <span>
                                    <a href="{{ route('/') }}">
                                        <img alt="image" class="img-circle"
                                            src="{{ env('API_PATH').'/uploads/profiles/'.Session::get('data')->user->image }}"
                                        />
                                    </a>
                                </span>
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <span class="clear">
                                        <span class="block m-t-xs">
                                            <strong class="font-bold">
                                                {{ Session::get('data')->user->name }}
                                            </strong>
                                        </span>
                                        <span class="text-muted text-xs block">
                                            As {{ Session::get('data')->role->name }}
                                            <b class="caret"></b>
                                        </span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                    <li>
                                        <a href="{{ route('backend.change-password.index') }}">Change Password</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('backend.profile.index') }}">Profile</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="logo-element">
                                <strong class="text-success"> BO </strong>
                            </div>
                        </li>

                        @php
                            $menu_role = Session::get('data')->menus;
                        @endphp

                        @foreach( $menu_role as $menus)
                        @if($menus->parent == 0)
                        @if($menus->route == '#')

                        <li class="" id="parent-{{ $menus->id }}">

                            <a href="javascript:void(0);" class="waves-effect" aria-expanded="false">
                                <i class="{{ $menus->icon }}"></i>
                                <span> {{ $menus->label }} <span class="fa arrow"></span></span>
                            </a>

                            <ul class="nav nav-second-level group-{{ $menus->id }} collapse" aria-expanded="false">

                                @foreach($menu_role as $menus2)
                                @if($menus2->parent == $menus->id)

                                <li class="child {{ Route::is($menus2->route) ? 'active' : '' }}" id="child-of-{{$menus2->parent}}">
                                    <a href="{{ route($menus2->route) }}">
                                        <i class="{{ $menus2->icon }}"></i>
                                        <span style="display: inline-block;"> {{ $menus2->label }} </span>
                                    </a>
                                </li>

                                @endif
                                @endforeach

                            </ul>
                        </li>
                        @else
                        <li class="{{ Route::is($menus->route) ? 'active' : '' }}">
                            <a href="{{ route($menus->route) }}">
                                <i class="{{ $menus->icon }}"></i>
                                <span> {{ $menus->label }} </span>
                            </a>
                        </li>
                        @endif
                        @endif
                        @endforeach

                    </ul>

                </div>
            @endguest
        </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-success " href="#"><i class="fa fa-bars"></i>
                        </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">
                                <strong>Welcome</strong> to <strong class="text-success">BOOKING ONLINE</strong>, <strong class="text-default">{{ Session::get('data')->user->name }}</strong>
                            </span>
                        </li>


                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>




                    </ul>

                </nav>
            </div>

            <div class="wrapper wrapper-content animated fadeIn">
                @yield('content')
            </div>

            <div class="footer">
                <div class="pull-right">
                    <strong>Copyright</strong> &copy; {{ date('Y') }}
                </div>
            </div>
        </div>

    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('plugin-inspinia/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('plugin-inspinia/js/inspinia.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/pace/pace.min.js') }}"></script>

    <!-- Toastr script -->
    <script src="{{ asset('plugin-inspinia/js/plugins/toastr/toastr.min.js') }}"></script>

    <!-- iCheck -->
    <script src="{{ asset('plugin-inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>

    <!-- Nestable List -->
    <script src="{{ asset('plugin-inspinia/js/plugins/nestable/jquery.nestable.js') }}"></script>

    <!-- Sweet alert -->
    <script src="{{ asset('plugin-inspinia/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Jasny -->
    <script src="{{ asset('plugin-inspinia/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

   <!-- Data picker -->
   <script src="{{ asset('plugin-inspinia/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

    <script>
        $(document).ready(function () {
            if($('li .child').hasClass('active'))
            {
                var li = $('li .child.active').attr('id');
                var parent = li.split("-");
                parent = parent[2];
                $("ul .nav.nav-second-level.group-"+parent).addClass('in');
                $("#parent-"+parent).addClass('active');
            }
        });

    </script>
    <script src="https://js.pusher.com/8.3/pusher.min.js"></script>
    <script src="{{ mix('js/app.js') }}" defer></script> <!-- Pastikan file ini ada -->



    @yield('onpage-js')

</body>

</html>
