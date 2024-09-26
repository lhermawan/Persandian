@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h5>List of User</h5>
                <div class="ibox-tools">
                    <a class="btn btn-default btn-xs" type="button" href="{{ route('backend.user.create') }}">
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
                                <th>Role</th>
                                <th>Email</th>
                                <th>Created Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td><b>{{ $user->name }}</b></td>
                                    <td>{{ $user->role->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>
                                        <a class="" href="#" onclick="event.preventDefault(); document.getElementById('form-toggle-{{ $user->id }}').submit();" >

                                        @if ($user->status == 2)
                                            {!! $SPAN_NOTACTIVE_START.'Menunggu konfirmasi'.$SPAN_NOTACTIVE_END !!}
                                        @elseif ($user->status == 1)
                                            {!! $SPAN_ACTIVE_START.'Aktif'.$SPAN_ACTIVE_END !!}
                                        @else
                                            {!! $SPAN_NOTACTIVE_START.'Tidak aktif'.$SPAN_NOTACTIVE_END !!}
                                        @endif

{{--                                            {!! ($user->status == config('setting.status.active') ? $SPAN_ACTIVE_START.$status[$user->status].$SPAN_ACTIVE_END : $SPAN_NOTACTIVE_START.$status[$user->status].$SPAN_NOTACTIVE_END) !!}--}}
                                        </a>
                                        <form id="form-toggle-{{ $user->id }}" action="{{ route('backend.user.toggle') }}" method="POST" style="display: none;" >
                                            @csrf
                                            <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                                            <input type="hidden" name="status" id="status" value="{{ ($user->status == config('setting.status.active')) ? 0 : 1  }}" >
                                        </form>
                                    </td>
{{--                                    <td>--}}
{{--                                        <a class="btn btn-info" type="button" href=" {{ route('backend.keluarga.index', $user->id) }}">--}}
{{--                                            <i class="fa fa-file"></i>--}}
{{--                                            <strong>Daftar Keluarga</strong>--}}
{{--                                        </a>--}}
{{--                                        <a class="btn btn-success" type="button" href=" {{ route('backend.user.edit', $user->id) }}">--}}
{{--                                            <i class="fa fa-pencil"></i>--}}
{{--                                            <strong>Ubah</strong>--}}
{{--                                        </a>--}}
{{--                                         <button class="btn btn-danger " type="button" onclick="deleteWarning({{ $user->id }})"><i class="fa fa-trash"></i>&nbsp;Hapus</button>--}}
{{--                                        --}}
{{--                                    </td>--}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<form id="deleteform" action="{{ route('backend.user.deleteuser') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" id="uid" name="uid" value="" />
</form>
@endsection

@section('onpage-js')

    @include('backend.layouts.message')

    <script>
        $(document).ready(function () {

        });

        function deleteWarning(uid){
            swal({
            title: "Hapus User",
                    text: "Anda yakin akan menghapus User ini?",
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
