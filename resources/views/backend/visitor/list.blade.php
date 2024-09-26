@extends('layout.main')

@section('title', 'Users List')

@section('content')

<div class="container">
	<div class="row">
		@if(Session::has('success'))
		<div class="alert alert-success">
			{{ Session::get('success') }}
			@php
				Session::forget('success');
			@endphp
		</div>
		@endif
		@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<div class="col-sm-12">
			<div class="card-box">
				<div class="table-rep-plugin">
					<div class="table-responsive" data-pattern="priority-columns">
						<a href="{{ url('web/checkin/add')}}">
							<button class="btn btn-custom waves-effect waves-light m-b-5 pull-right" type="button">
								<i class="fa fa-plus m-l-5"></i>&nbsp;<b> ADD VISITORS</b>
							</button>
						</a>
						<h4 class="header-title m-t-0 m-b-30">Add User</h4>

						<p class="text-muted font-13 m-b-15">
							For basic styling—light padding and only horizontal dividers—add the base class <code>.table</code> to any <code>&lt;table&gt;</code>.
						</p>

						<table class="table m-0">
							<thead>
								<tr>
									<th>#</th>
									<th data-priority="1">Nama Visitor</th>
									<th data-priority="2">Bertemu Dengan</th>
									<th data-priority="3">Departemen</th>
                                    <th data-priority="4">Keperluan</th>
                                    <th data-priority="5">Waktu Checkin</th>
                                    <th data-priority="6">Nomor Kartu Tamu</th>
                                    <th data-priority="7">Jumlah Orang</th>
                                    <th data-priority="8">Created By</th>
									<th data-priority="9">Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($visitors as $visitor)
								<tr>
									<th scope="row">{{ $visitor->id }}</th>
									<td>{{ $visitor->NamaVisitor }}</td>
                                    <td>{{ $visitor->bertemu_dengan }}</td>
                                    <td>{{ $visitor->departemen }}</td>
                                    <td>{{ $visitor->keperluan }}</td>
                                    <td>{{ date('d-m-Y', strtotime($visitor->check_in_time)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($visitor->check_out_time)) }}</td>
                                    <td>{{ $visitor->nomor_kartu_tamu }}</td>
                                    <td>{{ $visitor->createby }}</td>
                                    <td>{{ $visitor->jumlah_orang }}</td>
									<td>
										<a href="{{ route('web.user.edit', $visitor->idvisitor) }}">
											<button class="btn btn-icon waves-effect waves-light btn-purple m-b-5" data-toggle="tooltip" data-placement="top" title="Edit User">
												<i class="fa fa-pencil"></i>
											</button>
										</a>
										<form method='GET' action="{{ route('web.checkin.delete', $visitor->idvisitor) }}" style="display:initial;">
											@csrf
											<button type="submit" class="btn btn-icon waves-effect waves-light btn-danger m-b-5 delete-button" data-toggle="tooltip" data-placement="top" title="Delete User" name="delete-button" id="delete-button{{ $visitor->idvisitor }}">
												<i class="fa fa-trash"></i>
											</button>
										</form>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>

				</div>
				{{ $visitors->links() }}
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<!-- End row -->
</div> <!-- container -->

@endsection

@section('customjs')
<script>
	$(document).ready(function()
	{
		$('.delete-button').on('click',function(e){
			e.preventDefault();
			
			var form = $(this).parents('form');
			swal({
				title: "Are you sure?",
				text: "Once deleted, you will not be able to recover this !",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: 'btn-danger waves-effect waves-light',
				confirmButtonText: 'Delete!',
				closeOnConfirm: true,
				dangerMode: true,
			}, function (isConfirm) {
				if (isConfirm) {
					form.submit();
				} else {
					swal("Canceled !");
				}
			});
		});
	}); /* END DOC READY */

</script>
@endsection