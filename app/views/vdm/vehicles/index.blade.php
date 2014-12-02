@extends('includes.vdmheader')
@section('mainContent')

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
@stop