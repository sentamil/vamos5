@extends('includes.vdmheader')
@section('mainContent')

<h1> {{$routeNo}} Stops List</h1>


<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>Id</td>

			<td>Stops</td>
			<td>Actions</td>

		</tr>
	</thead>
	<tbody>
	@foreach($stopList as $key => $value)
		<tr>
			<td>{{ $key }}</td>
			<td>{{ $value }}</td>
	
	
			<!-- we will also add show, edit, and delete buttons -->
			<td>
				{{ Form::open(array('url' => 'vdmGeoFence/' . $key, 'class' => 'pull-right')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					{{ Form::submit('Delete this route', array('class' => 'btn btn-warning')) }}
				{{ Form::close() }}
				<a class="btn btn-small btn-success" href="{{ URL::to('vdmBusStops/' . $routeNo . ':' . $value) }}">Show Details</a>

				<a class="btn btn-small btn-info" href="{{ URL::to('vdmBusStops/' . $schoolId . ':' . $routeNo . ':' .$value . '/edit') }}">Edit Details</a>

			
				
				
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@stop