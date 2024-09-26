@extends('layout.main')

@section('title', 'Users List')

@section('content')
<style type="text/css">
	.btn-mojito{
		background: #ADA996;  /* fallback for old browsers */
		background: -webkit-linear-gradient(to right, #EAEAEA, #DBDBDB, #F2F2F2, #ADA996);  /* Chrome 10-25, Safari 5.1-6 */
		background: linear-gradient(to right, #EAEAEA, #DBDBDB, #F2F2F2, #ADA996); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
		border-radius: 5px 5px 5px 5px;
	}
</style>
<div class="wrapper wrapper-content">
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
		<div class="card-box">
			<div class="col-lg-12 text-right">
				<div style="margin-bottom: 20px;">
					<a href="{{ url('web/checkin/create')}}">
						<button class="btn btn-success waves-effect waves-light btn-lg" type="button">
							<i class="fa fa-plus m-l-5"></i>&nbsp;<b> CHECK IN</b>
						</button>
					</a>
				</div>
				
				<div class="ibox">
					<div class="ibox-title bg-primary text-center">
						<h3>
							<b>CHECK IN LIST</b>
						</h3>
					</div>
					<div class="ibox-content">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover dataTables-example" id="checkin-table" style="width:100%;">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th data-priority="1">Identity Number</th>
										<th data-priority="1">Visitor Number</th>
										<th data-priority="2" width="15%">Department</th>
										<th data-priority="5">Check In Time</th>
										<th data-priority="6">Action</th>
									</tr>
								</thead>
							</table>
						</div>
						<div class="clearfix"></div>
						
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="ibox">
					<div class="ibox-title bg-danger text-center">
						<h3>
							<b>CHECK OUT LIST</b>
						</h3>
					</div>
					<div class="ibox-content">

						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover dataTables-example" id="checkout-table" style="width:100%;">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th data-priority="1">Identity Number</th>
										<th data-priority="2" width="5%">Department</th>
										<th data-priority="5">Check In Time</th>
										<th data-priority="5">Check Out Time</th>
										<th data-priority="6">Action</th>
									</tr>
								</thead>
							</table>
						</div>
						<div class="clearfix"></div>

					</div>
				</div>
			</div>
			<div class="clearfix"></div>
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

		/*
		 * Function Menu Datatables
		 * Method Get
		 * @param none
		 */
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var url = "{{ route('web.visitor.list_checkin') }}";
		
		$('#checkin-table').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
            	url: url,
			},
			columns: [
				{data: 'DT_Row_Index', searchable: false, orderable: false},
				// {data: 'id', name: 'users.id'},
				{data: 'nama_visitor', name: 'name'},
				{data: 'guest_id', name: 'idcard_number'},
				{data: 'visitor_card_number', name: 'visitor_card_number'},
				{data: 'department_name', name: 'departments.name'},
				{data: 'check_in_time', name: 'check_in_time'},
				{data: 'action', name: 'action', orderable: false, searchable: false, className:"text-center"}
			]
		});

		/*
		 * Function Menu Datatables
		 * Method Get
		 * @param none
		 */
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var url = "{{ route('web.visitor.list_checkout') }}";
		
		$('#checkout-table').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
            	url: url,
			},
			columns: [
				{data: 'DT_Row_Index', searchable: false, orderable: false},
				// {data: 'id', name: 'users.id'},
				{data: 'nama_visitor', name: 'name'},
				{data: 'guest_id', name: 'idcard_number'},
				{data: 'department_name', name: 'departments.name'},
				{data: 'check_in_time', name: 'check_in_time'},
				{data: 'check_out_time', name: 'check_out_time'},
				{data: 'action', name: 'action', orderable: false, searchable: false, className:"text-center"}
			]
		});

	}); /* END DOC READY */

</script>
@endsection