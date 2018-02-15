<!-- app/views/nerds/index.blade.php -->

<!DOCTYPE html>
<html>
<head>
	<title>VAMO Systems</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
	
</head>
<body>
	
<div class="container">
<div>
<nav class="navbar navbar-inverse">
	<div class="navbar-header">
		<a class="navbar-brand" href="{{ URL::to('vdmFranchises') }}">VAMO SYSTEMS</a>
		

	</div>
	<ul class="nav navbar-nav">
		<li><a href="{{ URL::to('vdmFranchises/fransearch') }}">Switch Login</a></li>
		<li><a href="{{ URL::to('vdmFranchises/users') }}">Switch Login users</a></li>
		<li><a href="{{ URL::to('vdmFranchises') }}">View All Franchises</a></li>
		<li><a href="{{ URL::to('vdmFranchises/create') }}">Add a Franchise</a>
		<li><a href="{{ URL::to('ipAddressManager') }}">IPAddressManager</a>
		<li><a href="{{ URL::to('vdmFranchises/buyAddress') }}">Address control</a>
		<li><a href="{{ URL::to('logout/') }}">Logout User: {{Auth::user ()->username }}</a></li>	

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