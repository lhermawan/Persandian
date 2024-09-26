@extends('layout.main')

@section('title', 'Add User')

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
		                        <img src="{{ asset($visitor_picture->image_name) }}" class="img-thumbnail"/>
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
				<div class="ibox-title">
					<h5>Visiting Detail</h5>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
                                	<label class="col-lg-2 control-label">{{ __('Identity Number') }}</label>
                                    <div class="col-lg-10">
                                    	<p class="form-control-static">{{$data['visitor']->idcard_number }}</p>
                                    </div>
                                </div>
								<div class="form-group">
                                	<label class="col-lg-2 control-label">{{ __('Visitor Number') }}</label>
                                    <div class="col-lg-10">
                                    	<p class="form-control-static">{{$data['visitor']->visitor_card_number }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                	<label class="col-lg-2 control-label">{{ __('Visitor Name') }}</label>
                                    <div class="col-lg-10">
                                    	<p class="form-control-static">{{$data['visitor']->name }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                	<label class="col-lg-2 control-label">{{ __('Intended Person') }}</label>
                                    <div class="col-lg-10">
                                    	<p class="form-control-static">{{$data['visitor']->nama_user }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                	<label class="col-lg-2 control-label">{{ __('From Departemen') }}</label>
                                    <div class="col-lg-10">
                                    	<p class="form-control-static">{{$data['visitor']->nama_department }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                	<label class="col-lg-2 control-label">{{ __('Purpose') }}</label>
                                    <div class="col-lg-10">
                                    	<p class="form-control-static">{{$data['visitor']->purpose_name }}</p>
                                    </div>
                                </div>
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
										@if($visitor_items->visitor_id == $data['visitor']->id)
											<tr>
												<td>{{ $visitor_items->item_name }}</td>
												<td>{{ $visitor_items->quantity }}</td>
												
												<td>
													@if($visitor_items->permission == 1)
														{{ __('Diizinkan') }}
													@elseif($visitor_items->permission == 2)
														{{ __('Tidak Diizinkan') }}
													@endif
												</td>		
											</tr>
										@endif
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

</script>
@endsection