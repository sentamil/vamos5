@extends('includes.vdmheader')
@section('mainContent')
<h1>Edit Devices</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($deviceId, array('route' => array('vdmVehicles.update', $deviceId), 'method' => 'PUT')) }}

	<div class="form-group">
		{{ Form::label('deviceId', 'Device Id :')  }}
				<br/>
		{{ Form::label('deviceId' , $deviceId) }}
	</div>
		<div class="form-group">
		{{ Form::label('vehicleId', 'Vehicle Id') }}
		{{ Form::text('vehicleId', $vehicleId, array('class' => 'form-control')) }}
	</div>
	

	
	<div class="form-group">
		{{ Form::label('deviceModel', 'Device Model') }}
		{{ Form::text('deviceModel', $refData['deviceModel'], array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('regNo', 'Vehicle Registration Number') }}
		{{ Form::text('regNo', $refData['regNo'], array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('vehicleMake', 'Vehicle Make') }}
		{{ Form::text('vehicleMake', $refData['vehicleMake'], array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('vehicleType', 'Vehicle Type') }}
		{{ Form::text('vehicleType', $refData['vehicleType'], array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('oprName', 'Operator Name') }}
		{{ Form::text('oprName', $refData['oprName'], array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('mobileNo', 'Mobile Number') }}
		{{ Form::text('mobileNo', $refData['mobileNo'], array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('vehicleCap', 'Vehicle Capacity') }}
		{{ Form::text('vehicleCap', $refData['vehicleCap'], array('class' => 'form-control')) }}
	</div>
	
    <div class="form-group">
		{{ Form::label('odoDistance', 'Odometer Reading') }}
		
		{{ Form::text('odoDistance', $refData['odoDistance'], array('class' => 'form-control')) }}
		
	</div>
	
	
	{{ Form::submit('Update the Device!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop