
<!DOCTYPE html>
<html>
<head>
	<title>VAMO Systems</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	
</head>
<body>
	
<div class="container">
<div>
<nav class="navbar navbar-inverse">
	<div class="navbar-header">
		<a class="navbar-brand" href="{{ URL::to('vdmFranchises') }}">VAMO SYSTEMS</a>
	</div>
	<ul class="nav navbar-nav">
		<li><a href="{{ URL::to('vdmFranchises') }}">View All Franchises</a></li>
		<li><a href="{{ URL::to('vdmFranchises/create') }}">Add a Franchise</a>
		<li><a href="{{ URL::to('logout/') }}">Logout</a></li>	
	</ul>

</nav>
</div>
<div>
<footer class="row">
		@include('includes.footer')
	</footer>
</div>
</body>
</html>
