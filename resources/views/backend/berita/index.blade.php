@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h5>List of Berita </h5>
                <div class="ibox-tools">
                    <a class="btn btn-default btn-xs" type="button" href="{{ route('backend.berita.create') }}">
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
                                <th>Kode News</th>
                                <th>Judul</th>
                                <th>Gambar</th>
                                <th>Url</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($berita as $kl)
                                <tr>
                                    <td>{{ $kl->id }}</td>
                                    <td>{{ $kl->kode_news }}</td>
                                    <td>
                                        <b>{{ $kl->judul }}</b>
                                    </td>
                                    <td>
                                        <img alt="image" class="img-rounded img-md" style="max-width: 100px;" src="{{ asset('uploads/berita/'.$kl->gambar) }}">
                                    </td>
                                    <td>{{ $kl->url }}</td>
                                    <td>{{ $kl->created_at }}</td>
                                    <td>
                                       <a class="btn btn-success" type="button" href=" {{ route('backend.berita.edit', $kl->id) }}">
                                            <i class="fa fa-pencil"></i>
                                            <strong>Ubah</strong>
                                        </a>
                                         <button class="btn btn-danger " type="button" onclick="deleteWarning({{ $kl->id }})"><i class="fa fa-trash"></i>&nbsp;Hapus</button>
                                        
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@foreach($berita as $value)
<form id="deleteform" action="{{ route('backend.berita.deleteberita') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" id="parent_id" name="parent_id" value="{{ $value->id }}" />
    <input type="hidden" id="uid" name="uid" value="" />
</form>
@endforeach
@endsection

@section('onpage-js')

    @include('backend.layouts.message')
    
    <script>
        $(document).ready(function () {
            
        });

        function deleteWarning(uid){
            swal({
            title: "Hapus Berita",
                    text: "Anda yakin akan menghapus Berita ini?",
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
