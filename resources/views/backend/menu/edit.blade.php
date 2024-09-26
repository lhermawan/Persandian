@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h5>Edit Menu <span class="text-info">{{ $detail->name }}</span></h5>
                <div class="ibox-tools">

                </div>

            </div>
            <div class="ibox-content">
                <form role="form" action="{{ route('backend.menu.update', $detail->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Parent</label>
                        <select name="parent" class="form-control" required>
                            <option value="0" selected> Select Parent </option>
                            @foreach ($parents as $parent)
                            @if($detail->parent == $parent->id)
                                <option selected value="{{ $parent->id }}">{{ $parent->label }}</option> 
                            @else
                                <option value="{{ $parent->id }}">{{ $parent->label }}</option> 
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Label</label>
                        <input type="text" name="label" placeholder="Enter Label" class="form-control" required value="{{ $detail->label }}">
                    </div>

                    <div class="form-group">
                        <label>Route</label>
                        <input type="text" name="route" placeholder="Enter Route" class="form-control" required value="{{ $detail->route }}">
                    </div>

                    <div class="form-group">
                        <label>Url</label>
                        <input type="text" name="url" placeholder="Enter URL" class="form-control" required value="{{ $detail->url }}">
                    </div>

                    <div class="form-group">
                        <label>Mobile Page</label>
                        <input type="text" name="mobilepage" placeholder="Enter Mobile Page URL" class="form-control" required value="{{ $detail->mobilepage }}">
                    </div>

                    <div class="form-group">
                        <label>Icon</label>
                        <input type="text" name="icon" placeholder="Enter Icon" class="form-control" required value="{{ $detail->icon }}">
                    </div>

                    <div class="form-group">
                        <label>Order</label>
                        <input type="number" name="order" placeholder="Enter Order" class="form-control" required value="{{ $detail->order }}">
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
