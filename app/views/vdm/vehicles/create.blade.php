@extends('includes.vdmheader')
@section('mainContent')

<h1>Add a Vehicle</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmVehicles')) }}
<div class="row">
	<div class="col-md-4">
	<div class="form-group">
		{{ Form::label('vehicleId', 'Vehicle Id') }}
		{{ Form::text('vehicleId', Input::old('vehicleId'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('deviceId', 'Device Id') }}
		{{ Form::text('deviceId', Input::old('deviceId'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('shortName', 'Short Name') }}
		{{ Form::text('shortName', Input::old('shortName'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('deviceModel', 'Device Model') }}
		{{ Form::text('deviceModel', Input::old('deviceModel'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('regNo', 'Vehicle Registration Number') }}
		{{ Form::text('regNo', Input::old('regNo'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
        {{ Form::label('vehicleType', 'Vehicle Type') }}
      {{ Form::select('vehicleType', array( 'Car' => 'Car', 'Truck' => 'Truck','Bus' => 'Bus'), Input::old('vehicleType'), array('class' => 'form-control')) }}             

    </div>
	
	<div class="form-group">
		{{ Form::label('morningTripStartTime', 'Morning Trip Start Time') }}
		{{ Form::text('morningTripStartTime', Input::old('morningTripStartTime'), array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
        {{ Form::label('eveningTripStartTime', 'Evening Trip Start Time') }}
        {{ Form::text('eveningTripStartTime', Input::old('eveningTripStartTime'), array('class' => 'form-control')) }}
    </div>

	

	</div>
	<div class="col-md-4">
	<div class="form-group">
		{{ Form::label('oprName', 'Telecom Operator Name') }}
	  {{ Form::select('oprName', array( 'airtel' => 'airtel', 'reliance' => 'reliance','idea' => 'idea'), Input::old('oprName'), array('class' => 'form-control')) }}             

	</div>
	
	<div class="form-group">
		{{ Form::label('mobileNo', 'Mobile Number for Alerts') }}
		{{ Form::text('mobileNo', Input::old('mobileNo'), array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('overSpeedLimit', 'OverSpeed Limit') }}
		{{ Form::text('overSpeedLimit', Input::old('overSpeedLimit'), array('class' => 'form-control')) }}
	</div>
	
    <div class="form-group">
		{{ Form::label('odoDistance', 'Odometer Reading') }}
		{{ Form::text('odoDistance', Input::old('odoDistance'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('driverName', 'Driver Name') }}
		{{ Form::text('driverName', Input::old('driverName'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('gpsSimNo', 'GPS Sim Number') }}
		{{ Form::text('gpsSimNo', Input::old('gpsSimNo'), array('class' => 'form-control')) }}
	</div>
	
		<div class="form-group">
		{{ Form::label('email', 'Email for Notification') }}
		{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
	</div>
	
	   <div class="form-group">
	   {{ Form::label('sendGeoFenceSMS', 'Send GeoFence SMS') }}
      {{ Form::select('sendGeoFenceSMS', array( 'no' => 'No','yes' => 'Yes' ), Input::old('vehicleType'), array('class' => 'form-control')) }}             

	   </div>
	</div>
	</div>
	{{ Form::submit('Add the Vehicle!', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
@stop