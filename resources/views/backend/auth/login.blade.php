@extends('backend.layouts.app-auth')

@section('content')

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                {{-- <h1 class="logo-name">IN+</h1> --}}
                <img class="logo" src="{{ asset('image/logo.png') }}" style="width: auto; max-height: 150px;" />

            </div>
        {{-- <h3>Welcome to VMS</h3> --}}
        {{-- <p>Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views. --}}
        <!--Continually expanded and constantly improved Inspinia Admin Them (IN+)-->
            {{-- </p> --}}
            {{-- <p>Login in. To see it in action.</p> --}}

            <form class="m-t" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                @csrf
                <div class="form-group">
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" required>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback text-xs text-danger" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                    @endif
                </div>

                {{--            <div class="form-group">--}}
                {{--                <input class="form-check-input i-checks" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

                {{--                       <label class="form-check-label" for="remember">--}}
                {{--                    {{ __('Remember Me') }}--}}
                {{--                </label>--}}
                {{--            </div>--}}

                <button type="submit" class="btn btn-success block full-width m-b">
                    {{ __('Login') }}
                </button>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        <small>{{ __('Forgot Password?') }}</small>
                    </a>
                @endif

                <br>
                <br>
                <p>
                    <a class="btn btn-success btn-outline" href="https://play.google.com/store/apps/details?id=com.swamedia.klinikmutiara.booking">
                        <i class="fa fa-android"> </i> Download Aplikasi Android
                    </a>
                </p>

            </form>

        </div>
    </div>

@endsection

@section('onpage-js')
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
@endsection
