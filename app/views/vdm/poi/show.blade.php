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
	>
	</ul>
</nav>

<h1>Showing Device</h1>

	<div class="jumbotron text-center">
		
		<p>
			<br/><h2>{{ 'Vehicle Id : ' . $vehicleId }}</h2>
			<br/><strong>Proximity Level: </strong><br/><br/> {{ $proximityArr }}<br>
			<br/><strong>Geo Address: </strong><br/><br/> {{ $geoAddres }}<br>
			<br/><strong>Geo Coordinates: </strong><br/><br/> {{ $geoCoordinates }}<br>
			
		
		</p>
	</div>
<footer class="row">
		@include('includes.footer')
	</footer>
</div>
</body>
</html>