@extends('backend.layouts.app')

@section('content')

<div class="p-w-md m-t-sm">
    <div class="row animated fadeInRight">
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Profile Detail</h5>
                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">
                        <img alt="image" class="img-responsive" src="{{ asset('storage'.config('setting.path.profile').$user->image) }}">
                    </div>
                    <div class="ibox-content profile-content">
                        <h4 class="pull-right"><i class="text-warning fa fa-hand-rock-o"></i> &nbsp; {{ $user->role->name }}</h4>
                        <h4><strong>{{ $user->name }}</strong></h4>
                        <p class="pull-right text-info"><i class="fa fa-map-marker"></i> &nbsp; {{ $user->address }}</p>
                        <p class="text-danger"><strong>{{ $user->email }}</strong></p>
                        <h5>
                            About me
                        </h5>
                        <p>
                            {{ $user->about }}
                        </p>
                        <div class="user-button">
                            <div class="row">
                                {{-- <div class="col-md-6">
                                    <button type="button" class="btn btn-primary btn-sm btn-block"><i
                                            class="fa fa-envelope"></i> Send Message</button>
                                </div> --}}
                                {{-- <div class="col-md-6">
                                    <button type="button" class="btn btn-default btn-sm btn-block"><i
                                            class="fa fa-coffee"></i> Buy a coffee</button>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Update Profile</h5>
                </div>
                <div class="ibox-content">

                    <form class="form-horizontal" method="POST" action="{{ route('backend.profile.update') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4">Name</label>

                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required >
                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4">Address</label>

                            <div class="col-md-8">
                                <textarea id="address" class="form-control" name="address" required>{{ $user->address }}</textarea>
                                
                                @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('about') ? ' has-error' : '' }}">
                            <label for="about" class="col-md-4">About Me</label>

                            <div class="col-md-8">
                                <textarea id="about" class="form-control" name="about" required>{{ $user->about }}</textarea>
                                
                                @if ($errors->has('about'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('about') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-md-4">Profile Image</label>

                            <div class="col-md-8">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename">{{ $user->image }}</span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Select file</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="image"/>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div> 
                            </div>
                        </div>
                        <h5 class="text-warning">You need to relogin after updating your profile</h5>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-10">
                                <button type="submit" class="btn btn-success">
                                    UPDATE
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

</div>

@endsection

@section('onpage-js')
    
    @include('backend.layouts.message')

    <script>
        $(document).ready(function () {
        });
    </script>
@endsection
