@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h3>Edit detail of <span class="text-info">{{ $detail->name }}</span></h3>
                <div class="ibox-tools">

                </div>

            </div>
            <div class="ibox-content">
                <form role="form" action="{{ route('backend.company.update', $detail->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Parent</label>
                        <select name="parent" class="form-control" required>
                            <option value="0" selected> Select Parent </option>
                            @foreach ($parents as $parent)
                            @if($detail->parent == $parent->id)
                                <option selected value="{{ $parent->id }}">{{ $parent->name }}</option> 
                            @else
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option> 
                            @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Enter Name" class="form-control" required value="{{ $detail->name }}">
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address"
                            class="form-control"
                            style="resize:none;"
                            placeholder="Enter Address"
                            required
                        >{{ $detail->address }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description"
                            class="form-control"
                            style="resize:none;"
                            placeholder="Give any Description."
                            required
                        >{{ $detail->description }}</textarea>
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