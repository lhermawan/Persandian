@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h3>Edit detail of <span class="text-info">{{ $berita->judul }}</span></h3>
                <div class="ibox-tools">

                </div>

            </div>
            <div class="ibox-content">
                <form role="form" action="{{ route('backend.berita.update', $berita->id) }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Kode News</label>
                        <input disabled type="text" name="kode_news" placeholder="Enter Code" class="form-control" value="{{ $berita->kode_news}}">
                    </div>
                    
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" placeholder="Enter Title" class="form-control" required value="{{ $berita->judul }}">
                    </div>

                    <div class="form-group">
                        <label>Isi</label>
                        <textarea type="text" name="isi" placeholder="Enter Content" class="form-control" required>
                            {{ $berita->isi }}
                        </textarea>
                        
                    </div>

                    <div class="form-group">
                        <label>Ganti Gambar</label>
                        <input type="file" name="gambar"  class="form-control" >
                    </div>

                    <div class="form-group">
                        <label>Url</label>
                        <input type="text" name="url" placeholder="Enter Content" class="form-control" required value="{{ $berita->url }}">
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