@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h3>Edit User <span class="text-info">{{ $detail->name }}</span></h3>
                <div class="ibox-tools">

                </div>

            </div>
            <div class="ibox-content">
                <form role="form" action="{{ route('backend.user.update', $detail->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="role_id" required>
                            <option value="" disabled selected>Choose Role</option>
                            @foreach ($roles as $role)
                            @if($detail->role_id == $role->id)
                                <option selected value="{{ $role->id }}">{{ $role->name }}</option>
                            @else
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Enter Name" class="form-control" required value="{{ $detail->name }}">
                    </div>
                     <div class="form-group">
                        <label>Telephone</label>
                        <input type="telephone" name="telephone" placeholder="Enter Telephone" class="form-control" required value="{{ $detail->telephone }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter Email" class="form-control" required value="{{ $detail->email }}">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control" type="text" name="address" id="address" placeholder="Enter Address" required=""> {{ $detail->address }}
                        </textarea>
                        
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter Password" class="form-control" required>
                        <span class="help-block text-10 text-info">
                            Password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Retype Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
                    </div>

                    <div>
                        <button class="btn btn-sm btn-success pull-right m-t-n-xs" type="submit">
                            <strong>UPDATE</strong>
                        </button>
                    </div>

                </form>
                <div class="clearfix"></div>
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