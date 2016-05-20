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
						<!-- <div class="row"> -->
							<div class="col-md-12">
								{{ HTML::ul($errors->all()) }}
                                    {{ Form::open(array('url' => 'vdmSmsReport')) }}
								<div class="form-group">
									{{ Form::label('orgId', 'Organization List :') }}
									{{ Form::select('orgId', $orgsArr, Input::old('orgId'),array('class' => 'form-control selectpicker show-menu-arrow', 'data-live-search '=> 'true','required' => 'required')) }}
								</div>
								
								 <div class="form-group">
									{{ Form::label('vehicleId', 'Vehicle Id :') }}
									{{ Form::text('vehicleId', Input::old('vehicleId'), array('class' => 'form-control','placeholder'=>'Vehicle Id', 'required' => 'required')) }}
								</div>
								
								 <div class="form-group">
									{{ Form::label('Date', 'Date :') }}
									<input type="date" name="date" class="form-control" required>
									<!-- <form action="action_page.php"> -->
								</div>
								
								<div class="form-group">
									{{ Form::label('tripType', 'Trip :') }}
									{{ Form::select('tripType', array('pickUp' => 'PickUp','drop' => 'Drop'),Input::old('tripType'),array('class' => 'form-control', 'required' => 'required')) }}
								</div>
								
								<div class="form-group">
									{{Form::submit('Submit', array('class' => 'btn btn-primary')) }}
									<br >
								</div>
								{{ Form::close() }}
							</div>
						<!-- </div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('includes.js_index')
</body>
</html>


