<!-- app/views/nerds/index.blade.php -->

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


<h1>Franchises Management</h1>

<!-- will be used to show any messages -->
@if (Session::has('message'))
	<div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>ID</td>
			<td>Franchise Name</td>
			<td>Franchise Code</td>
			<td>Actions</td>

		</tr>
	</thead>
	<tbody>
	@foreach($fcodeArray as $key => $value)
		<tr>
			<td>{{ $key }}</td>
			<td>{{ array_get($fnameArray, $value)}}</td>	
			<td>{{ $value }}</td>
				 
			<!-- we will also add show, edit, and delete buttons -->
			<td>
		
				
				{{ Form::open(array('url' => 'vdmFranchises/' . $value, 'class' => 'pull-right')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					{{ Form::submit('Remove this Franchise', array('class' => 'btn btn-warning')) }}
				{{ Form::close() }}

				<a class="btn btn-small btn-success" href="{{ URL::to('vdmFranchises/' . $value) }}">Show this Franchise</a>

		
				<a class="btn btn-small btn-info" href="{{ URL::to('vdmFranchises/' . $value . '/edit') }}">Amend this  Franchise</a>

			
				
				
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
<footer class="row">
		@include('includes.footer')
	</footer>
</div>
</body>
</html>