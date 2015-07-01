@include('includes.header_index')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						Vehicles List
					</div>
					<div class="panel-body">
						<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<div class="row">
							
								<div class="row">
								    {{ HTML::ul($errors->all()) }}
                                    {{ Form::open(array('url' => 'vdmSmsReport')) }}

									<div class="form-group">
										{{ Form::label('orgId', 'Organization List') }}
										{{ Form::select('orgId', $orgsArr, array('class' => 'form-control')) }}
									</div>
                                    <br />
                                    <div class="form-group">
                                        {{ Form::label('vehicleId', 'Vehicle Id') }}
                                        {{ Form::text('vehicleId', Input::old('vehicleId'), array('class' => 'form-control')) }}
                                    </div>
                                    <br />
                                    
									<div class="form-group">
										{{ Form::label('Date', 'Date') }}
										{{ Form::text('date',   Input::old('date','yyyy-mm-dd'), array('class' => 'form-control')) }}
									</div>
                                    <br />
									<div class="form-group">
										{{ Form::label('tripType', 'Trip') }}
										{{ Form::select('tripType', array('pickUp' => 'PickUp','drop' => 'Drop'), array('class' => 'form-control')) }}
									</div>
									
									<br/>
									
									<div class="form-group">
									    {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}{{ Form::close() }}
									 </div>   

								

								</div>
							</div>
						</div>
					</div>
				</div>
				@include('includes.js_index')
				</body>
				</html>
