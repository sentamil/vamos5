@include('includes.header_index')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4><b><font color="blue"> SMS REPORT </font></b></h4>
					</div>
					<div class="panel-body">
						<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<div class="row">
                				<div class="col-sm-12">
								    {{ HTML::ul($errors->all()) }}
                                    {{ Form::open(array('url' => 'vdmSmsReport')) }}
									<div class="row">	
     <div class="col-md-6">
	<div class="form-group">
    <div class="col-md-6"> 
										{{ Form::label('orgId', 'Organization List :') }}
										</div>
								<div class="col-md-6">
										{{ Form::select('orgId', $orgsArr, array('class' => 'form-control')) }}
									</div>
                                    <br><br>
                                     <div class="col-md-6"> 
                                        {{ Form::label('vehicleId', 'Vehicle Id :') }}
											</div>
								<div class="col-md-6">
                                        {{ Form::text('vehicleId', Input::old('vehicleId'), array('class' => 'form-control')) }}
                                    </div>
                                    <br><br><br>
                                    
									 <div class="col-md-6"> 
										{{ Form::label('Date', 'Date :') }}
										<form action="action_page.php">
											</div>
								<div class="col-md-6">
										<!--{{ Form::text('Date', Input::old('Date'), array('bday' => 'bday'), array('class' => 'form-control')) }} -->
								     <input type="date" name="bday">
										
									</div>
                                    <br><br><br>
									 <div class="col-md-6"> 
										{{ Form::label('tripType', 'Trip :') }}
											</div>
								<div class="col-md-6">
										{{ Form::select('tripType', array('pickUp' => 'PickUp','drop' => 'Drop'), array('class' => 'form-control')) }}
									</div>
									
									<br><br><br>
									
									 <div class="col-md-6"> 
									    {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}{{ Form::close() }}
									 </div>   

								</form>

								</div>
							</div>
						</div>
					</div>
				</div>
				@include('includes.js_index')
				</body>
				</html>

  
