@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h5>List of Company</h5>
                <div class="ibox-tools">
                    <a class="btn btn-default btn-xs" type="button" href="{{ route('backend.company.create') }}">
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
                                <th>Address</th>
                                <th>Description</th>
                                <th>Created Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <td>{{ $company->id }}</td>
                                    <td><a href="{{ route('backend.company.edit', $company->id) }}" class="font-bold">
                                        {{ $company->name }}
                                    </a></td>
                                    <td>{{ $company->address }}</td>
                                    <td>{{ $company->description }}</td>
                                    <td>{{ $company->created_at }}</td>
                                    <td>
                                        <a class="" href="#" onclick=" event.preventDefault(); document.getElementById('form-toggle-{{ $company->id }}').submit();" >
                                            {!! ($company->status == config('setting.status.active') ? $SPAN_ACTIVE_START.$status[$company->status].$SPAN_ACTIVE_END : $SPAN_NOTACTIVE_START.$status[$company->status].$SPAN_NOTACTIVE_END) !!}
                                        </a>

                                        <form id="form-toggle-{{ $company->id }}" action="{{ route('backend.company.toggle') }}" method="POST" style="display: none;" >
                                            @csrf
                                            <input type="hidden" name="id" id="id" value="{{ $company->id }}">
                                            <input type="hidden" name="status" id="status" value="{{ ($company->status == config('setting.status.active')) ? 0 : 1  }}" >
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $companies->links() }}
                </div>
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
