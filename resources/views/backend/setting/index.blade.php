@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h5>Setting</h5>

            </div>

            <div class="ibox-content">
                <form class="form-horizontal" action="{{ route('backend.setting.settingedit') }}" method="POST">
                    @csrf
                    @foreach ($settings as $data)
                    <div class="form-group"><label class="col-lg-2 control-label">{{$data->label}}</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="{{$data->name}}" name="{{$data->name}}" value="{{$data->value}}">
                            <span class="help-block m-b-none">{{$data->helper_text}}</span>
                        </div>
                    </div>
                    @endforeach
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button class="btn btn-sm btn-primary" type="submit">Simpan</button>
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
