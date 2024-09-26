@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h5>List of Role</h5>
                <div class="ibox-tools">
                    <a class="btn btn-default btn-xs" type="button" href="{{ route('backend.role.create') }}">
                        <i class="fa fa-plus"></i>
                        <strong>New</strong>
                    </a>
                </div>

            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Created Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td><a href="{{ route('backend.role.edit', $role->id) }}" class="font-bold">
                                        {{ $role->name }}
                                    </a></td>
                                    <td>{{ $role->description }}</td>
                                    <td>{{ $role->created_at }}</td>
                                    <td>
                                        <a class="" href="#" onclick="event.preventDefault(); document.getElementById('form-toggle-{{ $role->id }}').submit();" >
                                            {!! ($role->status == config('setting.status.active') ? $SPAN_ACTIVE_START.$status[$role->status].$SPAN_ACTIVE_END : $SPAN_NOTACTIVE_START.$status[$role->status].$SPAN_NOTACTIVE_END) !!}
                                        </a>

                                        <form id="form-toggle-{{ $role->id }}" action="{{ route('backend.role.toggle') }}" method="POST" style="display: none;" >
                                            @csrf
                                            <input type="hidden" name="id" id="id" value="{{ $role->id }}">
                                            <input type="hidden" name="status" id="status" value="{{ ($role->status == config('setting.status.active')) ? 0 : 1  }}" >
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $roles->links() }}
                </div>
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
