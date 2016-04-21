@include('includes.header_index')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4><b><font> SMS REPORT </font></b></h4>
					</div>
					<div class="panel-body">
						<!-- <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"> -->
						<div class="row">
							<div class="col-sm-12">
								{{ HTML::ul($errors->all()) }}
                                    {{ Form::open(array('url' => 'vdmSmsReport')) }}
								 <div class="row">
									<div class="col-md-3">{{ Form::label('orgId', 'Organization List :') }}</div>
									<div class="col-md-6">{{ Form::select('orgId', $orgsArr, Input::old('orgId'),array('class' => 'form-control','required' => 'required')) }}</div>
								</div>
								<br />
								 <div class="row">
									<div class="col-md-3">{{ Form::label('vehicleId', 'Vehicle Id :') }}</div>
									<div class="col-md-6">{{ Form::text('vehicleId', Input::old('vehicleId'), array('class' => 'form-control','placeholder'=>'Vehicle Id', 'required' => 'required')) }}</div>
								</div>
								<br />
								 <div class="row">
									<div class="col-md-3">{{ Form::label('Date', 'Date :') }}</div>
									<div class="col-md-6"><input type="date" name="date" class="form-control" required></div>
									<!-- <form action="action_page.php"> -->
								</div>
								<br />
								<div class="row">
									<div class="col-md-3">{{ Form::label('tripType', 'Trip :') }}</div>
									<div class="col-md-6">{{ Form::select('tripType', array('pickUp' => 'PickUp','drop' => 'Drop'),Input::old('tripType'),array('class' => 'form-control', 'required' => 'required')) }}</div>
								</div>
								<br />
								<div class="row">
									<div class="col-md-3"></div>
									<div class="col-md-6">{{Form::submit('Submit', array('class' => 'btn btn-primary')) }}</div>
								</div>
								{{ Form::close() }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('includes.js_index')
</body>
</html>


