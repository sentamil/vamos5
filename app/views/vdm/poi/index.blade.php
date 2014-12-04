@extends('includes.vdmheader')
@section('mainContent')

<h1>Places of Interest</h1>


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
				<!-- show the nerd (uses the show method found at GET /vdmVehicles/{id} -->
				<a class="btn btn-small btn-success" href="{{ URL::to('vdmPOI/' . $value) }}">Show POI</a>

				<!-- edit this nerd (uses the edit method found at GET /vdmVehicles/{id}/edit -->
				<a class="btn btn-small btn-info" href="{{ URL::to('vdmPOI/' . $value . '/edit') }}">Edit POI </a>

			
				
				
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@stop