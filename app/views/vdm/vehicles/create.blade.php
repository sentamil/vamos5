@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		 <div class="panel-heading">
                   		 Vehicles Create 
                	 </div>
                	<div class="panel-body">
                		<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                			<div class="row">
                				<div class="col-sm-12">
			                		{{ HTML::ul($errors->all()) }}
									{{ Form::open(array('url' => 'vdmVehicles')) }}
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-6">
														{{ Form::label('vehicleId', 'Vehicle ID') }}
													</div>
													<div class="col-md-6">
														{{ Form::text('vehicleId', Input::old('vehicleId'), array('class' => 'form-control')) }}
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-6">
														{{ Form::label('deviceId', 'Device ID') }}
													</div>
													<div class="col-md-6">
														{{ Form::text('deviceId', Input::old('deviceId'), array('class' => 'form-control')) }}
													</div>
												</div>	
											</div>
											<hr />
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-6">
														{{ Form::label('shortName', 'Short Name') }}
													</div>
													<div class="col-md-6">
														{{ Form::text('shortName', Input::old('shortName'), array('class' => 'form-control')) }}
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-6">
														{{ Form::label('deviceModel', 'Device Model') }}
													</div>
													<div class="col-md-6">
														{{ Form::text('deviceModel', Input::old('deviceModel'), array('class' => 'form-control')) }}
													</div>
												</div>	
											</div>
								
										<hr /><br />
										
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('regNo', 'Vehicle Registration Number') }}
												</div>
												<div class="col-md-6">
													{{ Form::text('regNo', Input::old('regNo'), array('class' => 'form-control')) }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('vehicleType', 'Vehicle Type') }}
												</div>
												<div class="col-md-6">
													{{ Form::select('vehicleType', array( 'Car' => 'Car', 'Truck' => 'Truck','Bus' => 'Bus'), Input::old('vehicleType'), array('class' => 'form-control')) }}      
												</div>
											</div>	
										</div>
										
										<hr />
										
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('overSpeedLimit', 'OverSpeed Limit') }}
												</div>
												<div class="col-md-6">
													{{ Form::text('overSpeedLimit', Input::old('overSpeedLimit'), array('class' => 'form-control')) }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('morningTripStartTime', 'Morning Trip Start Time') }}
												</div>
												<div class="col-md-6">
													{{ Form::text('morningTripStartTime', Input::old('morningTripStartTime'), array('class' => 'form-control')) }}     
												</div>
											</div>	
										</div>
										<hr /><br />
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('eveningTripStartTime', 'Evening Trip Start Time') }}
												</div>
												<div class="col-md-6">
													{{ Form::text('eveningTripStartTime', Input::old('eveningTripStartTime'), array('class' => 'form-control')) }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('orgId', 'org/College Name') }}
												</div>
												<div class="col-md-6">
													{{ Form::select('orgId', array($orgList), Input::old('orgId'), array('class' => 'form-control')) }}    
												</div>
											</div>	
										</div>
										<hr /><br />
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('oprName', 'Telecom Operator Name') }}
												</div>
												<div class="col-md-6">
													{{ Form::select('oprName', array( 'airtel' => 'airtel', 'reliance' => 'reliance','idea' => 'idea'), Input::old('oprName'), array('class' => 'form-control')) }} 
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('mobileNo', 'Mobile Number for Alerts') }}
												</div>
												<div class="col-md-6">
													{{ Form::text('mobileNo', Input::old('mobileNo'), array('class' => 'form-control')) }}
												</div>
											</div>	
										</div>
										<hr />
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('odoDistance', 'Odometer Reading') }}
												</div>
												<div class="col-md-6">
													{{ Form::text('odoDistance', Input::old('odoDistance'), array('class' => 'form-control')) }} 
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('driverName', 'Driver Name') }}
												</div>
												<div class="col-md-6">
													{{ Form::text('driverName', Input::old('driverName'), array('class' => 'form-control')) }}
												</div>
											</div>	
										</div>
										<hr /><br />	
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('gpsSimNo', 'GPS Sim Number') }}
												</div>
												<div class="col-md-6">
													{{ Form::text('gpsSimNo', Input::old('gpsSimNo'), array('class' => 'form-control')) }} 
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('email', 'Email for Notification') }}
												</div>
												<div class="col-md-6">
													{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
												</div>
											</div>	
										</div>										
										<hr />			
										
										
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ Form::label('parkingAlert', 'Parking Alert') }}
                                                </div>
                                                <div class="col-md-6">
                                                    {{ Form::select('parkingAlert',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('parkingAlert'), array('class' => 'form-control')) }} 
                                                </div>  
                                            </div>
                                        </div>
                                        
																	
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													{{ Form::label('sendGeoFenceSMS', 'Send GeoFence SMS') }}
												</div>
												<div class="col-md-6">
													{{ Form::select('sendGeoFenceSMS', array( 'no' => 'No','yes' => 'Yes' ), Input::old('vehicleType'), array('class' => 'form-control')) }} 
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">	
													</div>
												<div class="col-md-6">
													{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}{{ Form::close() }}
												</div>	
											</div>
										</div>
										
										
										<div class="col-md-6">
												<div class="row">
													<div class="col-md-6">
														{{ Form::label('altShort', 'Alternate Short Name') }}
													</div>
													<div class="col-md-6">
														{{ Form::text('altShortName', Input::old('altShortName'), array('class' => 'form-control')) }}
													</div>
												</div>	
											</div>
								</div>
                			</div>
            		</div>
        		</div>
    	  </div>
	</div>
</div>

</div>
</div>
@include('includes.js_create')
</body>
</html>