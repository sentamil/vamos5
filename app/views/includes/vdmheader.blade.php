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
		<li><a href="{{ URL::to('vdmPOI') }}">View All POI</a></li>
		<li><a href="{{ URL::to('vdmPOI/create') }}">Add a POI</a>
			
			<li><a  href="{{ URL::to('logout/') }}">Logout</a></li>	
	</ul>
</nav>
@yield('mainContent')
<footer class="row">
		@include('includes.footer')
	</footer>
</div>
</body>
</html>