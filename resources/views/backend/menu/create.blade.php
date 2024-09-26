@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h5>Create New Menu</h5>
                <div class="ibox-tools">

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
                <form role="form" action="{{ route('backend.menu.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Group</label>
                                <select class="form-control" name="parent">
                                    <option value="0" selected>Choose Group</option>
                                    @foreach ($parents as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Label</label>
                                <input type="text" name="label" placeholder="Enter Label" class="form-control" required>
                            </div>
        
                            <div class="form-group">
                                <label>Route</label>
                                <input type="text" name="route" placeholder="Route Name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Url</label>
                                <input type="text" name="url" placeholder="Enter URL" class="form-control" required>
                            </div>
        
                        </div>

                        <div class="col-lg-6">
        
                            <div class="form-group">
                                <label>Mobile Page</label>
                                <input type="text" name="mobilepage" placeholder="Mobile Page Url" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Icon</label>
                                <input type="text" name="icon" placeholder="Icon Menu" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description"
                                    class="form-control"
                                    style="resize:none;"
                                    placeholder="Give any Description."
                                    required
                                ></textarea>
                            </div>
        
                            <div class="form-group">
                                <label>Order</label>
                                <input type="number" name="order" placeholder="Ordering Number" class="form-control" required>
                            </div>
        
                        </div>

                    </div>
                    <div>
                        <button class="btn btn-sm btn-success pull-right m-t-n-xs" type="submit">
                            <strong>SUBMIT</strong>
                        </button>
                    </div>

                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

@endsection
