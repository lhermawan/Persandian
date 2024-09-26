@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">

                    <h5>List of Menu</h5>
                    <div class="ibox-tools">
                        <a class="btn btn-default btn-xs" type="button" href="{{ route('backend.menu.create') }}">
                            <i class="fa fa-plus"></i>
                            <strong>New</strong>
                        </a>
                        {{-- <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a>
                            </li>
                            <li><a href="#">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a> --}}
                    </div>

                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Group</th>
                                <th>Name</th>
                                <th>Route</th>
                                <th>Url</th>
                                <th>Mobile</th>
                                <th>Icon</th>
                                <th>Description</th>
                                <th>Created Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($menus as $menu)
                                <tr>
                                    <td>{{ $menu->id }}</td>
                                    <td>{{ $menu->parent_label }}</td>
                                    <td><a href="{{ route('backend.menu.edit', $menu->id) }}" class="font-bold">
                                            {{ $menu->label }}
                                        </a></td>
                                    <td>{{ $menu->route }}</td>
                                    <td>{{ $menu->url }}</td>
                                    <td>{{ $menu->mobilepage }}</td>
                                    <td>{{ $menu->icon }}</td>
                                    <td>{{ $menu->description }}</td>
                                    <td>{{ $menu->created_at }}</td>
                                    <td>
                                        <a class="" href="#" onclick="event.preventDefault(); document.getElementById('form-toggle-{{ $menu->id }}').submit();" >
                                            {!! ($menu->status == config('setting.status.active') ? $SPAN_ACTIVE_START.$status[$menu->status].$SPAN_ACTIVE_END : $SPAN_NOTACTIVE_START.$status[$menu->status].$SPAN_NOTACTIVE_END) !!}
                                        </a>

                                        <form id="form-toggle-{{ $menu->id }}" action="{{ route('backend.menu.toggle') }}" method="POST" style="display: none;" >
                                            @csrf
                                            <input type="hidden" name="id" id="id" value="{{ $menu->id }}">
                                            <input type="hidden" name="status" id="status" value="{{ ($menu->status == config('setting.status.active')) ? 0 : 1  }}" >
                                        </form>
                                    </td>
                                    <td>
                                        <a class="btn btn-success" type="button" href="{{ route('backend.menu.edit', $menu->id) }}"><i class="fa fa-pencil"></i>&nbsp;Ubah</a>
                                        <button class="btn btn-danger " type="button" onclick="deleteWarning({{ $menu->id }})"><i class="fa fa-trash"></i>&nbsp;Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $menus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="deleteform" action="{{ route('backend.menu.delete') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" id="uid" name="uid" value="" />
    </form>
@endsection

@section('onpage-js')

    @include('backend.layouts.message')

    <script>
        $(document).ready(function () {

        });
        function deleteWarning(uid) {
            swal({
                title: "Hapus Menu",
                text: "Anda yakin akan menghapus menu ini?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                $("#uid").val(uid);
                document.getElementById('deleteform').submit();
            });
        }

    </script>
@endsection
