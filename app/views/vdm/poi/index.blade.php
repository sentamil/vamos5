<!-- app/views/nerds/index.blade.php -->

<!DOCTYPE html>
<html>
<head>
	<title>VAMOS Device Management</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	
</head>
<body>
	
<div class="container">
<div>
<nav class="navbar navbar-inverse">
	<div class="navbar-header">
		<a class="navbar-brand" href="{{ URL::to('vdmDevices') }}">VAMO SYSTEMS</a>
	</div>
	<ul class="nav navbar-nav">
		<li><a href="{{ URL::to('vdmDevices') }}">View All Devices</a></li>
		<li><a href="{{ URL::to('vdmDevices/create') }}">Add a Device</a>
		<li><a href="{{ URL::to('vdmGroups') }}">View All Groups</a></li>
		<li><a href="{{ URL::to('vdmGroups/create') }}">Create a Group</a>
		<li><a href="{{ URL::to('vdmUsers') }}">View All Users</a></li>
		<li><a href="{{ URL::to('vdmUsers/create') }}">Create a User</a>
		<li><a href="{{ URL::to('vdmPOI/create') }}">Create a User</a>
		<li><a href="{{ URL::to('logout/') }}">Logout</a></li>	
	</ul>

</nav>
</div>


<h1>Places of Interest</h1>

<!-- will be used to show any messages -->
@if (Session::has('message'))
	<div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>ID</td>
			<td>Vehicle Id</td>
			<td>Proximity Level</td>
			<td>Address</td>
	
			<td>Actions</td>

		</tr>
	</thead>
	<tbody>
	@foreach($vehicleList as $key => $value)
		<tr>
			<td>{{ $key }}</td>
			<td>{{ $value }}</td>
			<td>Click Show for details</td>	
			<td>Click Show for details</td>	

				 
			<!-- we will also add show, edit, and delete buttons -->
			<td>
		
				<!-- delete the nerd (uses the destroy method DESTROY /nerds/{id} -->
				<!-- we will add this later since its a little more complicated than the other two buttons -->
				{{ Form::open(array('url' => 'vdmPOI/' . $value, 'class' => 'pull-right')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					{{ Form::submit('Clear Places of Interest', array('class' => 'btn btn-warning')) }}
				{{ Form::close() }}
				<!-- show the nerd (uses the show method found at GET /vdmDevices/{id} -->
				<a class="btn btn-small btn-success" href="{{ URL::to('vdmPOI/' . $value) }}">Show POI</a>

				<!-- edit this nerd (uses the edit method found at GET /vdmDevices/{id}/edit -->
				<a class="btn btn-small btn-info" href="{{ URL::to('vdmPOI/' . $value . '/edit') }}">Edit POI </a>

			
				
				
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