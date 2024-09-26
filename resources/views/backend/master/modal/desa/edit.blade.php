<form action="{{ route('backend.master.desaedit') }}" method="post">
    @csrf
    <input type="hidden" id="desa_id" name="desa_id" value="{{ $desa->id }}">
    <div class="form-group"><label>Alamat Website</label> <input type="text" id="website" name="website"  class="form-control"  value="{{$desa->website}}"></div>
    <div class="form-group"><label>Status TTE</label> <input type="text" id="tte" name="tte"  class="form-control"  value="{{$desa->tte}}"></div>
{{--    <div class="form-group"><label>Jenis Website</label> <input type="text" id="jenis" name="jenis"  class="form-control"  value="{{$desa->jenis}}"></div>--}}
    <div class="form-group">
        <label>Jenis Website*</label>
        <select class="form-control m-b" id="jenis" name="jenis">
            <option value="E-office Desa" {{ $desa->jenis == 'E-office Desa' ? 'selected' : '' }}>E-office Desa</option>
            <option value="Luar" {{ $desa->jenis == 'luar' ? 'selected' : '' }}>Luar</option>
            <option value="" {{ $desa->jenis == '' ? 'selected' : '' }}></option>

        </select>
    </div>



    <div class="form-group"><label>Sosialisasi</label> <textarea id="sosialisasi" name="sosialisasi" class="form-control">{{$desa->sosialisasi}}</textarea></div>
    <div>
        <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Ubah</strong></button>
    </div>
</form>
