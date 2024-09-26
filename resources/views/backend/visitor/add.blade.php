@extends('backend.layouts.app')



@section('content')

<style>
	.frame {
		overflow-y: auto;
		height: 3em;
		width: 10em;
		line-height: 1em;
	}

	.frame::-webkit-scrollbar {
		-webkit-appearance: none;
	}

	.frame::-webkit-scrollbar:vertical {
		width: 11px;
	}

	.frame::-webkit-scrollbar:horizontal {
		height: 11px;
	}

	.frame::-webkit-scrollbar-thumb {
		border-radius: 8px;
		border: 2px solid white; /* should match background, can't be transparent */
		background-color: rgba(0, 0, 0, .5);
	}

</style>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="row">
				<form class="form" role="form" method="POST" action="" aria-label="{{ __('Add User') }}" enctype="multipart/form-data">
					@csrf
						<div class="row">        
							<div class="col-md-2 ">        	
								<button class="btn btn-info btn-lg" style="min-height: 100px; top:50%; left:50%;">Already Book ?</button>
							</div>
							<div class="col-md-2 ">
								<button class="btn btn-success btn-lg" style="min-height: 100px; top:50%; left:50%;"><i class="fa fa-sign-in" aria-hidden="true"> Courier</i></button>
							</div>
							<div class="col-lg-8">
								<img id="card-img" src="{{ asset('/image/visitor_banner.png') }}" class="img-fluid img-rounded" style="margin-bottom: 25px;width:100%;">
							</div>
	                    </div>
						<div class="col-lg-4">
							<div class="ibox">
								<div class="ibox-content">
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<div class="select">
													<label for="videoSource">Camera source: </label>
													<select class="form-control m-b" id="videoSource"></select>
												</div>
												<div id="screenshot" style="text-align:center;">
													<video class="videostream" id="videovisitor" autoplay="" width="150" height="150"></video>                                                    
												</div>

												@if ($errors->has('visitorPicture'))
												<span class="invalid-feedback" role="alert">
													<strong>{{ $errors->first('visitorPicture') }}</strong>
												</span>
												@endif
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<div class="visitor text-center">
													<label>Visitor image</label>
													<img id="visitor-img" src="{{ asset('/storage/upload/profile.png') }}" class="img-thumbnail" style="margin-bottom: 10px;">
													<input
														id="fotoimage"
														name="visitorPicture"
														type="hidden" 
														value="{{ old('visitorPicture') }}"
														>
													<button class="btn btn-primary" id="visitor-button" type="button" disabled=""><i class="fa fa-camera"></i> Take Picture</button>
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<div class="card-visitor text-center">
													<label>Visitor ID Card</label>
													<img id="card-img" src="{{ asset('/storage/upload/card.png') }}" class="img-thumbnail" style="margin-bottom: 10px;">
													<input
														id="fotoimage2"
														name="visitorIdCard"
														type="hidden" 
														value="{{ old('visitorIdCard') }}"
														>
													<button class="btn btn-primary" id="card-button" type="button" disabled=""><i class="fa fa-camera"></i> Take ID Card</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
	                    </div>
						<div class="col-lg-4">
							<div class="ibox">
								<div class="ibox-title">
									<div class="ibox-content">
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label for="name">{{ __('Visitor Name') }}</label>
									
													<input
														class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
														id="name"
														name="name"
														type="text" 
														value="{{ old('name') }}"
														placeholder="{{ __('Type a name') }}"
														required
														autofocus
													>

													@if ($errors->has('visitor_name'))
														<span class="invalid-feedback" role="alert">
															<strong>{{ $errors->first('visitor_name') }}</strong>
														</span>
													@endif
												</div>
											</div>

											<div class="col-lg-12">
												<div class="form-group">
													<label for="phone_number">{{ __('Phone Number') }} </label>
													<input
														class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}"
														id="phone_number"
														name="phone_number"
														type="text" 
														value="{{ old('phone_number') }}"
														placeholder="{{ __('+62-xxx') }}"
														required
														autofocus
													>
					
													@if ($errors->has('phone_number'))
														<span class="invalid-feedback" role="alert">
															<strong>{{ $errors->first('phone_number') }}</strong>
														</span>
													@endif
												</div>
											</div>
											<div class="col-lg-12">
												<div class="form-group">
													<label for="email">{{ __('Email') }} </label>
													<input
														class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}"
														id="email"
														name="email"
														type="email" 
														value="{{ old('email') }}"
														placeholder="{{ __('example@example.com') }}"
														required
														autofocus
													>
					
													@if ($errors->has('email'))
														<span class="invalid-feedback" role="alert">
															<strong>{{ $errors->first('email') }}</strong>
														</span>
													@endif
												</div>
											</div>

											<div class="col-lg-12">
												<div class="form-group">
													<label for="from">{{ __('From') }} </label>
													<input
														class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}"
														id="from"
														name="from"
														type="text" 
														value="{{ old('from') }}"
														placeholder="{{ __('Type visitor origin') }}"
														required
														autofocus
													>
					
													@if ($errors->has('from'))
														<span class="invalid-feedback" role="alert">
															<strong>{{ $errors->first('from') }}</strong>
														</span>
													@endif
												</div>
											</div>
											<div class="col-lg-12">
												<div class="ibox">
													<div class="ibox-title">
														<h5>Visitor Luggage</h5>
													</div>
													<div class="ibox-content" style="height: 225px; overflow: hidden; position:relative;">
														<div style="margin-bottom: 10px;">
															<a href="#" class="btn btn-primary" onclick="addRow()"><i class="fa fa-plus"></i> Add Luggage</a>
														</div>
														
														<div class="frame" style="height:150px; width:100%; position:relative;">
															<table class="table table-bordered items" id="table_barang">
																<tr style="background-color:#1ab394;color:white;vertical-align: center;" class="text-center">
																	<td style="width:105px;">Name of goods</td>
																	<td>Total item (s)</td>
																	<td style="width: 145px;">Status Permitted</td>
																	<td>Action</td>
																</tr>
															</table>	
														</div>
														<!-- end row -->
													</div>
												</div>
											</div>
											<div class="col-lg-12">
												
												<input
													class="form-control{{ $errors->has('check_in_time') ? ' is-invalid' : '' }}"
													id="check_in_time"
													name="check_in_time"
													type="hidden" 
													value="{{ date('d-m-Y H:i:s') }}"
													placeholder="{{ __('Check In Time') }}"
													autofocus
													readonly
												>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-4">
							<div class="ibox">
								<div class="ibox-content">
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<label for="purpose">{{ __('Purpose') }}</label>
												<select
													id="purpose"
													name="purpose"
													class="form-control{{ $errors->has('purpose') ? ' is-invalid' : '' }}"
													parsley-trigger="change"
													required
												>
													<option value="" disabled selected>{{ __('Choose Purpose') }}</option>

													
												</select>

												@if ($errors->has('purpose'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('purpose') }}</strong>
													</span>
												@endif
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<label for="intended_person">{{ __('Intended Person') }}</label>

												<select
													id="intended_person"
													name="intended_person"
													class="form-control{{ $errors->has('intended_person') ? ' is-invalid' : '' }}"
													parsley-trigger="change"
													required
												>
													<option value="" disabled selected>{{ __('Choose Person') }}</option>
													
												</select>
				
												@if ($errors->has('visitor_name'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('visitor_name') }}</strong>
													</span>
												@endif
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<label for="department_id">{{ __('To Departement') }}</label>
												<select
													id="department_id"
													name="department_id"
													class="form-control{{ $errors->has('department_id') ? ' is-invalid' : '' }}"
													parsley-trigger="change"
													required
												>
													<option value="" disabled selected>{{ __('Intended Department') }}</option>

													{{-- @foreach ($data['departments'] as $department)
														@if (old('department_id') == $department)
															<option value="{{ $department['id'] }}" selected>{{ $department['name'] }}</option>
														@else
															<option value="{{ $department['id'] }}">{{ $department['name'] }}</option>
														@endif
													@endforeach --}}
												</select>
												@if ($errors->has('department_id'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('department_id') }}</strong>
													</span>
												@endif
											</div>	
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12" style="margin-bottom: 35px;">
							<button type="submit" class="btn btn-lg btn-primary btn-block">
								<b>{{ __('CHECK IN') }}</b> <i class="fa fa-sign-in"></i>
							</button>
						</div>
					</form>
				<div class="clearfix"></div>
			</div><!-- end row -->
		</div>
	</div>
</div>
<input type="hidden" id="ktpbase64">
<!-- End row -->

@endsection

@section('onpage-js')

    @include('backend.layouts.message')
    
    <script>
        $(document).ready(function () {
            
        });
    </script>

@endsection