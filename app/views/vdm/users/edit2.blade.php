<!-- app/views/nerds/edit.blade.php -->

<!DOCTYPE html>
<html>
<head>
	<title>VAMO Systems Device Management</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container">

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
		<li><a  href="{{ URL::to('logout/') }}">Logout</a></li>	
	</ul>
</nav>

<h1>Edit User</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}


{{ Form::model($userId, array('route' => array('vdmUsers.update', $userId), 'method' => 'PUT')) }}


	<div class="form-group">
		{{ Form::label('userId', 'UserId :') }}
		<br/>
		{{ Form::label('userId', $userId) }}
	</div>

<div class="form-group">
		{{ Form::label('mobileNo', 'mobileNo') }}
		{{ Form::text('mobileNo', $mobileNo, array('class' => 'form-control')) }}
	</div>


	<div class="form-group">
		{{ Form::label('vehicleGroups', 'Vehicle Groups') }}
		{{ Form::select('vehicleGroups[]', $vehicleGroups, Input::old('vehicleGroups'),  array('multiple' => true,'class' => 'form-control')) }}
		
	</div>

	{{ Form::submit('Update the User!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
<footer class="row">
		@include('includes.footer')
	</footer>
</div>
</body>
</html>