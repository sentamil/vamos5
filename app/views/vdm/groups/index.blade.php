@extends('includes.vdmheader')
@section('mainContent')

<h1>Groups Management</h1>

<!-- will be used to show any messages -->
@if (Session::has('message'))
	<div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>ID</td>
			<td>GroupId</td>
			<td>Vehicles</td>
			<td>Actions</td>

		</tr>
	</thead>
	<tbody>
	@foreach($groupList as $key => $value)
		<tr>
			<td>{{ $key }}</td>
			<td>{{ $value }}</td>
			<td>{{ array_get($vehicleListArr,$value)}}</td>	

				 
			<!-- we will also add show, edit, and delete buttons -->
			<td>

				<!-- delete the nerd (uses the destroy method DESTROY /nerds/{id} -->
				<!-- we will add this later since its a little more complicated than the other two buttons -->
				{{ Form::open(array('url' => 'vdmGroups/' . $value, 'class' => 'pull-right')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					{{ Form::submit('Delete this Group', array('class' => 'btn btn-warning')) }}
				{{ Form::close() }}
				<!-- show the nerd (uses the show method found at GET /nerds/{id} -->
				<a class="btn btn-small btn-success" href="{{ URL::to('vdmGroups/' . $value) }}">Show this Group</a>

				<!-- edit this nerd (uses the edit method found at GET /nerds/{id}/edit -->
				<a class="btn btn-small btn-info" href="{{ URL::to('vdmGroups/' . $value . '/edit') }}">Edit this Group</a>

			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@stop