@extends('includes.vdmheader')
@section('mainContent')

<h1>Add a Vehicle</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmVehicles')) }}

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
		{{ Form::label('vehicleMake', 'Vehicle Make') }}
		{{ Form::text('vehicleMake', Input::old('vehicleMake'), array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('vehicleType', 'Vehicle Type') }}
		{{ Form::text('vehicleType', Input::old('vehicleMake'), array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('oprName', 'Operator Name') }}
		{{ Form::text('oprName', Input::old('oprName'), array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('mobileNo', 'Mobile Number') }}
		{{ Form::text('mobileNo', Input::old('mobileNo'), array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('vehicleCap', 'Vehicle Capacity') }}
		{{ Form::text('vehicleCap', Input::old('vehicleCap'), array('class' => 'form-control')) }}
	</div>
	
    <div class="form-group">
		{{ Form::label('odoDistance', 'Odometer Reading') }}
		{{ Form::text('odoDistance', Input::old('odoDistance'), array('class' => 'form-control')) }}
	</div>
	
	{{ Form::submit('Add the Vehicle!', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
@stop