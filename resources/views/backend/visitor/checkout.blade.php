@extends('layout.main')

@section('title', 'Add User')

@section('content')
<style>
.sweet-alert .icon.custom {
  background-size: contain;
  border-radius: 0;
  border: none;
  background-position: center center;
  background-repeat: no-repeat;
  width: 400px !important;
}
</style>
<div class="container" id="container_checkout">
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
		@csrf
	</div>
	<div class="row">
		@php 
			$index = 0;
			$poto = '';
		@endphp
		@foreach ($data['visitor_picture'] as $key=>$visitor_picture)
			@if($visitor_picture->visitor_id == $data['visitor']->id)
				<div class="col-lg-6">
					<div class="ibox">
		                <div class="ibox-content product-box">
		                    <div class="product-imitation" style="padding: 30px 0 !important;">
		                        <img src="{{ asset($visitor_picture->image_name) }}" class="img-thumbnail" />
		                    </div>
		                    <div class="product-desc">
		                        <div class="text-center">
		                        	<h3>
		                        		@if($index == 0)
			                        		Visitor Image
			                        	@elseif($index == 1)
			                        		Visitor ID Card
			                        	@endif
		                        	</h3>
		                        </div>
		                    </div>
		                </div>
		            </div>
				</div>
				@php
					$poto =  asset($visitor_picture->image_name);
					$index++;
				@endphp
			@endif
		@endforeach
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title" id="ibox-visitornumber">
					<h5>Checkout Form</h5>
					<div class="ibox-content">
						<div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label class="col-sm-2 control-label">Visitor Number</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                        	<input 
                                        		class="form-control {{ $errors->has('idcard') ? ' is-invalid' : '' }}"
                                        		id="idcard"
												name="idcard"
												type="text" 
												value="{{ old('idcard') }}"
												placeholder="{{ __('Fill the visitor card number') }}"
												required
												autofocus
                                        	> 
                                        	<span class="input-group-btn"> 
                                        		<a href="#" class="btn btn-primary" id="check_button">Check</a> 
                                        	</span>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title">
					<h5>Visiting Detail</h5>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-6">
								<fieldset>
									<legend>Visitor Detail</legend>
									<div class="col-lg-12">
										<div class="form-group">
		                                	<label class="col-lg-4 control-label">{{ __('Identity Number') }}</label>
		                                    <div class="col-lg-8">
		                                    	<p class="form-control-static">{{$data['visitor']->idcard_number }}</p>
		                                    </div>
		                                </div>
									</div>
									<div class="col-lg-12">
										<div class="form-group">
		                                	<label class="col-lg-4 control-label">{{ __('Visitor Number') }}</label>
		                                    <div class="col-lg-8">
		                                    	<p class="form-control-static">{{$data['visitor']->visitor_card_number }}</p>
		                                    </div>
		                                </div>
									</div>
									<div class="col-lg-12">
										<div class="form-group">
		                                	<label class="col-lg-4 control-label">{{ __('Visitor Name') }}</label>
		                                    <div class="col-lg-8">
		                                    	<p class="form-control-static">{{$data['visitor']->name }}</p>
		                                    </div>
		                                </div>
									</div>
									<div class="col-lg-12">
										<div class="form-group">
		                                	<label class="col-lg-4 control-label">{{ __('Origin') }}</label>
		                                    <div class="col-lg-8">
		                                    	<p class="form-control-static">{{$data['visitor']->origin }}</p>
		                                    </div>
		                                </div>
									</div>
								</fieldset>
							</div>
							<div class="col-lg-6">
								<fieldset>
									<legend>Visitor Purpose</legend>
									<div class="col-lg-12">
										<div class="form-group">
		                                	<label class="col-lg-4 control-label">{{ __('Intended Person') }}</label>
		                                    <div class="col-lg-8">
		                                    	<p class="form-control-static">{{$data['visitor']->nama_user }}</p>
		                                    </div>
		                                </div>
									</div>
									<div class="col-lg-12">
										<div class="form-group">
		                                	<label class="col-lg-4 control-label">{{ __('From Departemen') }}</label>
		                                    <div class="col-lg-8">
		                                    	<p class="form-control-static">{{$data['visitor']->nama_department }}</p>
		                                    </div>
		                                </div>
									</div>
									<div class="col-lg-12">
										<div class="form-group">
		                                	<label class="col-lg-4 control-label">{{ __('Purpose') }}</label>
		                                    <div class="col-lg-8">
		                                    	<p class="form-control-static">{{$data['visitor']->purpose_name }}</p>
		                                    </div>
		                                </div>
									</div>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title"> 
					<h5>Visitor Items</h5>
					<div class="ibox-content">
						<fieldset>
							<legend>Visitor IMEI Devices</legend>
							<table class="table table-bordered" id="table_imei">
								<tr>
									<td>IMEI Type</td>
									<td>IMEI Number</td>
								</tr>
								@foreach ($data['visitor_imeis'] as $visitor_imeis)
									@if($visitor_imeis->visitor_id == $data['visitor']->id)
										<tr>
											<td>{{ $visitor_imeis->imei_type_name }}</td>
											<td>{{ $visitor_imeis->imei_number }}</td>
										</tr>
									@endif
								@endforeach	
							</table>
						</fieldset>
						<hr>
						<fieldset>
							<legend>Visitor Luggage</legend>
							@if(isset($data['visitor_items']))
								<table class="table table-bordered" id="table_barang">
									<tr>
										<td>Name of Goods</td>
										<td>Amount</td>
										<td>Status Permitted</td>
									</tr>

									@foreach ($data['visitor_items'] as $visitor_items)
										<tr>
											<td>{{ $visitor_items->item_name }}</td>
											<td>{{ $visitor_items->quantity }}</td>
											<td>{{ $visitor_items->status_permited }}</td>		
										</tr>
									@endforeach	
									
								</table>
							@else
								<h3>(Visitor Luggage Empty)</h3>
							@endif
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


@section('customjs')
<script>

$("#check_button").on('click', function() {
	var guest_id = $('#idcard').val();
	var visitor_id = {{$data['visitor']->id }};
	$('#ibox-visitornumber').children('.ibox-content').toggleClass('sk-loading');
	$('#idcard').attr('disabled', 'disabled');
	$.ajax({
		type: "POST",
		url: '{{url("web/checkin/checkvalidasi")}}',
		data: {visitor_id:visitor_id, guest_id: guest_id},
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function( respone ) {
			if(respone.status == 'success'){
	
				swal({
						title: "Visitor Name : {{$data['visitor']->name }}",
						text: respone.msg,
						imageUrl: "{{ $poto }}",
						imageAlt: "Visitor",
						showCancelButton: true,
						confirmButtonClass: 'btn-danger waves-effect waves-light',
						confirmButtonText: 'Check Out!',
						confirmButtonColor: '#fc020f',
						closeOnConfirm: true,
						dangerMode: true,
					}, function (isConfirm) {
						if (isConfirm) {
							window.location = "{{ url('web/checkin/checkoutperson',$data['visitor']->id)}}";
						} else {
							swal("Canceled !");
						}
					});
				
			}else{
				swal(respone.msg);
			}
			
		},
		complete : function(){
			$('#idcard').removeAttr('disabled');
			$('#ibox-visitornumber').children('.ibox-content').toggleClass('sk-loading');
		}
	});
});
</script>
@endsection