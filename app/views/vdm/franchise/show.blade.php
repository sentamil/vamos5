<!-- app/views/nerds/show.blade.php -->

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
		<a class="navbar-brand" href="{{ URL::to('vdmUsers') }}">Data Management</a>
	</div>
	<ul class="nav navbar-nav">
		<li><a href="{{ URL::to('vdmFranchises') }}">View All Franchises</a></li>
		<li><a href="{{ URL::to('vdmFranchises/create') }}">Add a Franchise</a>
			<li><a href="{{ URL::to('logout/') }}">Logout</a></li>
	</ul>
</nav>

<h1>Showing Franchise</h1>

	<div class="jumbotron text-center">
		
		<p>
			<br/><h2>{{ 'Franchise Name : ' . $fname }}</h2>
			<br/><h2>{{ 'Franchise Id : ' . $fcode }}</h2>
			<br/><strong>Other Details: </strong><br/><br/> {{ $franchiseDetails }}<br>
		
		</p>
	</div>
<footer class="row">
		@include('includes.footer')
	</footer>
</div>
</body>
</html>