@extends('includes.vdmheader')
@section('mainContent')

<h1>Geo Fence List</h1>


<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>GeoFenceId</td>

			<td>Place of Interest</td>
			
			<td>Actions</td>

		</tr>
	</thead>
	<tbody>
	@foreach($poiList as $key => $value)
		<tr>
			<td>{{ $key }}</td>
			<td>{{ $value }}</td>
		
	
			<!-- we will also add show, edit, and delete buttons -->
			<td>
		
				<!-- delete the nerd (uses the destroy method DESTROY /nerds/{id} -->
				<!-- we will add this later since its a little more complicated than the other two buttons -->
				{{ Form::open(array('url' => 'vdmGeoFence/' . $key, 'class' => 'pull-right')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					{{ Form::submit('Delete Places of Interest', array('class' => 'btn btn-warning')) }}
				{{ Form::close() }}
				<a class="btn btn-small btn-success" href="{{ URL::to('vdmGeoFence/' . $key . '/view') }}">Show GF</a>

				<a class="btn btn-small btn-info" href="{{ URL::to('vdmGeoFence/' . $key . '/edit') }}">Edit GF </a>

			
				
				
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@stop