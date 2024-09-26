<form action="{{ route('backend.master.bimtekcreate') }}" method="post" enctype="multipart/form-data">
    @csrf

     <div class="form-group">
        <label>Nama Desa*</label>
        <select class="form-control" id="id_desa" name="id_desa" required>
            <option value="" selected disabled>-- Pilih Desa --</option>
            @foreach ($desa as $data)
                <option value="{{ $data->id  }}">{{ $data->kecamatan .' - '.$data->desa }}</option>
            @endforeach
        </select>
    </div>


    <div class="form-group" id="tanggal" name="tanggal">
        <label>Tanggal Bimtek</label>
        <div class="input-group date">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            <input type="text" id="tgl_bimtek" name="tgl_bimtek" class="form-control" value="">
        </div>
    </div>


    <div class="form-group">
        <label>Jenis Bimtek*</label>
        <select class="form-control m-b" id="jenis_bimtek" name="jenis_bimtek">
            <option value="Bimtek Mandiri">Bimtek Mandiri</option>
            <option value="Bimtek Kecamatan">Bimtek Kecamatan</option>
            <option value="Bimtek Diskominfo">Bimtek Diskominfo</option>
            <option value="Tahap 1">Tahap 1</option>


        </select>
    </div>
    <div class="form-group">
        <label>Surat</label>
        <input type="file" id="surat" name="surat" accept="pdf" >
    </div>

    <div>
        <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Tambah</strong></button>
    </div>

</form>

<script>
    $( function() {

        $('#tanggal .input-group.date').datepicker({
            startView: 1,
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            format: "dd/mm/yyyy"
        });
    } );
</script>
