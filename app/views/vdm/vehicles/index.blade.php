<!-- app/views/nerds/index.blade.php -->

<!DOCTYPE html>
<html>
<head>
	<title>VAMOS Vehicles Management</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	
</head>
<body>
	
<div class="container">
<div>
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
		<li><a href="{{ URL::to('logout/') }}">Logout</a></li>	
	</ul>

</nav>
</div>


<h1>Vehicles Management</h1>

<!-- will be used to show any messages -->
@if (Session::has('message'))
	<div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>ID</td>
			<td>Vehicle Id</td>
			<td>Device Id</td>
			<td>Actions</td>

		</tr>
	</thead>
	<tbody>
	@foreach($vehicleList as $key => $value)
		<tr>
			<td>{{ $key }}</td>
			<td>{{ $value }}</td>
			<td>{{ array_get($deviceList, $value)}}</td>	
	
				 
			<!-- we will also add show, edit, and delete buttons -->
			<td>
		
				
				{{ Form::open(array('url' => 'vdmVehicles/' . $value, 'class' => 'pull-right')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					{{ Form::submit('Remove this Vehicle', array('class' => 'btn btn-warning')) }}
				{{ Form::close() }}

				<a class="btn btn-small btn-success" href="{{ URL::to('vdmVehicles/' . $value) }}">Show this Vehicle</a>

		
				<a class="btn btn-small btn-info" href="{{ URL::to('vdmVehicles/' . $value . '/edit') }}">Amend this  Vehicle</a>

			
				
				
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