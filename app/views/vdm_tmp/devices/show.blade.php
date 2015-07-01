<!-- app/views/nerds/show.blade.php -->

<!DOCTYPE html>
<html>
<head>
	<title>VAMO Systems Data Management</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container">

<nav class="navbar navbar-inverse">
	<div class="navbar-header">
		<a class="navbar-brand" href="{{ URL::to('vdmUsers') }}">Data Management</a>
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

<h1>Showing Device</h1>

	<div class="jumbotron text-center">
			<h2>{{'Device Id : ' . $deviceId }}</h2>
		<p>
			<br/><h2>{{ 'Vehicle Id : ' . $vehicleId }}</h2>
			<br/><strong>Other Details: </strong><br/><br/> {{ $deviceRefData }}<br>
		
		</p>
	</div>
<footer class="row">
		@include('includes.footer')
	</footer>
</div>
</body>
</html>