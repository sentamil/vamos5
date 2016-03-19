
<!DOCTYPE html>
<html>
<head>
	<title>GPSVTS</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	
</head>
<body>
	
<div class="container">
<div>
<nav class="navbar navbar-inverse">
	<div class="navbar-header">
		<a class="navbar-brand" href="{{ URL::to('Reports') }}">VAMO SYSTEMS</a>
		
	</div>
	<br/>
	<ul class="nav navbar-nav">
		<li><a href="{{ URL::to('liveReports') }}">Live Reports</a></li>
		<li><a href="{{ URL::to('Alarms') }}">Alarms</a>
		<li><a href="{{ URL::to('logout/') }}">Logout</a></li>	
	</ul>

</nav>
</div>

@yield('mainContent')

<footer class="row">
		@include('includes.footer')
	</footer>
</div>
</body>
</html>