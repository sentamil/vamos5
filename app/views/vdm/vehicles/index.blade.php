@extends('includes.vdmheader')
@section('mainContent')

<h1>Vehicles Management</h1>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>ID</td>
			<td>Vehicle Id</td>
			<td>Short Name</td>
			<td>Device Id</td>
			<td>Port No</td>
			<td>mobile No</td>
			<td>Actions</td>

		</tr>
	</thead>
	<tbody>
	@foreach($vehicleList as $key => $value)
		<tr>
			<td>{{ $key }}</td>
			<td>{{ $value }}</td>
			<td>{{ array_get($shortNameList, $value)}}</td>
			<td>{{ array_get($deviceList, $value)}}</td>
			<td>{{ array_get($portNoList, $value)}}</td>	
	       <td>{{ array_get($mobileNoList, $value)}}</td>    
				 
			<!-- we will also add show, edit, and delete buttons -->
			<td>
		
				
				{{ Form::open(array('url' => 'vdmVehicles/' . $value, 'class' => 'pull-right')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					{{ Form::submit('Remove this Vehicle', array('class' => 'btn btn-warning')) }}
				{{ Form::close() }}

				<a class="btn btn-small btn-success" href="{{ URL::to('vdmVehicles/' . $value) }}">Show this Vehicle</a>
				
				<a class="btn btn-small btn-success" href="{{ URL::to('vdmGeoFence/' . $value) }}">Show GeoFencing</a>
				
					
				<a class="btn btn-small btn-info" href="{{ URL::to('vdmVehicles/' . $value . '/edit') }}">Modify this  Vehicle</a>

			
				
				
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@stop