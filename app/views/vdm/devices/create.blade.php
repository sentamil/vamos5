<!-- app/views/nerds/create.blade.php -->

<!DOCTYPE html>
<html>
<head>
	<title>Add a Device</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container"><div>
<nav class="navbar navbar-inverse">
	<div class="navbar-header">
		<a class="navbar-brand" href="{{ URL::to('vdmDevices') }}">VAMO SYSTEMS</a>
	
	</div>
	<ul class="nav navbar-nav">
		<li><a href="{{ URL::to('vdmDevices') }}">View All Devices</a></li>
		<li><a href="{{ URL::to('vdmDevices/create') }}">Add a Device</a>
		<li><a href="{{ URL::to('vdmGroups') }}">View All Groups</a></li>
		<li><a href="{{ URL::to('vdmGroups/create') }}">Create a Group</a>
		<li><a href="{{ URL::to('vdmUsers') }}">View All Users</a></li>
		<li><a href="{{ URL::to('vdmUsers/create') }}">Create a User</a>
			<li><a href="{{ URL::to('logout/') }}">Logout</a></li>	
	</ul>
</nav>


</div>

<h1>Add a device</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmDevices')) }}

	<div class="form-group">
		{{ Form::label('deviceId', 'Device Id') }}
		{{ Form::text('deviceId', Input::old('deviceId'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('vehicleId', 'Vehicle Id') }}
		{{ Form::text('vehicleId', Input::old('vehicleId'), array('class' => 'form-control')) }}
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
	
	
	

	{{ Form::submit('Add the device!', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}

	<footer class="row">
		@include('includes.footer')
	</footer>
</div>
</body>
</html>