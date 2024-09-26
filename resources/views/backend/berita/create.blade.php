@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h3>Create Berita </h3>
                <div class="ibox-tools">

                </div>

            </div>
            <div class="ibox-content">
                <form role="form" action="{{ route('backend.berita.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Kode Berita</label>
                        <input type="text" name="kode_news" placeholder="Enter News" class="form-control" required value="">
                    </div>
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" placeholder="Enter Title" class="form-control" required value="">
                    </div>
                    <div class="form-group">
                        <label>Isi</label>
                        <textarea type="text" class="form-control" placeholder="Enter Content" name="isi" required value=""></textarea>
                        
                    </div>
                    <div class="form-group" id="data_1">
                        <label>Gambar</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/x-png,image/jpg,image/jpeg">
                    </div>
                    <div class="form-group">
                        <label>Url</label>
                        <input type="text" name="url" placeholder="Enter url" class="form-control" required value="">
                    </div>
                    <div>
                        <button class="btn btn-sm btn-success pull-right m-t-n-xs" type="submit">
                            <strong>SAVE</strong>
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
              $('#data_1 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });
        });
    </script>
@endsection
