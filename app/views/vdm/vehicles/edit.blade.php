<!-- app/views/nerds/edit.blade.php -->

<!DOCTYPE html>
<html>
<head>
	<title>VAMO Systems</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container">

<nav class="navbar navbar-inverse">
	<div class="navbar-header">
		<a class="navbar-brand" href="{{ URL::to('vdmVehicles') }}">VAMO SYSTEMS</a>
		</div>
		<ul class="nav navbar-nav">
		<li><a href="{{ URL::to('vdmVehicles') }}">View All Vehicles</a></li>
		<li><a href="{{ URL::to('vdmVehicles/create') }}">Add a Vehicle</a>
		<li><a href="{{ URL::to('vdmGroups') }}">View All Groups</a></li>
		<li><a href="{{ URL::to('vdmGroups/create') }}">Create a Group</a>
		<li><a href="{{ URL::to('vdmUsers') }}">View All Users</a></li>
		<li><a href="{{ URL::to('vdmUsers/create') }}">Create a User</a>
			<li><a  href="{{ URL::to('logout/') }}">Logout</a></li>	
	</ul>
</nav>

<h1>Amend Vehicles</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($vehicleId, array('route' => array('vdmVehicles.update', $vehicleId), 'method' => 'PUT')) }}

	<div class="form-group">
		{{ Form::label('vehicleId', 'Vehicle Id :')  }}
				<br/>
		{{ Form::label('vehicleId' , $vehicleId) }}
	</div>
	<div class="form-group">
		{{ Form::label('deviceId', 'Device Id') }}
		<br/>
		{{ Form::text('deviceId', $refData['deviceId'], array('class' => 'form-control')) }}

	</div>
	<div class="form-group">
		{{ Form::label('shortName', 'Short Name') }}
		{{ Form::text('shortName', $refData['shortName'], array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('deviceModel', 'Vehicle Model') }}
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
	
	
	{{ Form::submit('Update the Vehicle!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
<footer class="row">
		@include('includes.footer')
	</footer>
</div>
</body>
</html>