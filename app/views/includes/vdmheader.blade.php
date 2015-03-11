<!DOCTYPE html>
<html>
<head>
	<title>VAMOS</title>
<!--
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
-->

	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body>
<div class="container-fluid">
<div>
<nav class="navbar navbar-inverse">
	<div class="navbar-header">
	<!--
		<a class="navbar-brand" href="{{ URL::to('vdmVehicles') }}">VAMO SYSTEMS</a>
-->
	<ul class="nav navbar-nav">
		<li><a href="{{ URL::to('vdmVehicles') }}">View All Vehicles</a></li>
		<li><a href="{{ URL::to('vdmVehicles/create') }}">Add a Vehicle</a>
		<li><a href="{{ URL::to('vdmGroups') }}">View All Groups</a></li>
		<li><a href="{{ URL::to('vdmGroups/create') }}">Create a Group</a>
	   <li><a href="{{ URL::to('vdmSchool/create') }}">Add a School</a>
		<li><a href="{{ URL::to('vdmUsers') }}">View All Users</a></li>
		<li><a href="{{ URL::to('vdmUsers/create') }}">Create a User</a>
		<li><a href="{{ URL::to('vdmGeoFence/create') }}">Add a GeoFencing</a>
		<li><a  href="{{ URL::to('logout/') }}">Logout User: {{Auth::user ()->username }}</a></li>	
		
			
	</ul>
</nav>
@if (Session::has('message'))
	<div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
</div>

@yield('mainContent')
<footer class="row">
		@include('includes.footer')
	</footer>
</div>
</body>
</html>
