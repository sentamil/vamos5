@extends('includes.vdmheader')
@section('mainContent')

<h1>School List</h1>


<table class="table table-striped table-bordered">
	<thead>
		<tr>
		    <td>ID</td>
			<td>SchoolId</td>
			<td>Actions</td>

		</tr>
	</thead>
	<tbody>
	@foreach($schoolList as $key => $value)
		<tr>
			<td>{{ $key }}</td>
			<td>{{ $value }}</td>
		
	
			<!-- we will also add show, edit, and delete buttons -->
			<td>
		
				{{ Form::open(array('url' => 'vdmBusRoutes/' . $key, 'class' => 'pull-right')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					{{ Form::submit('Delete Bus Routes', array('class' => 'btn btn-warning')) }}
				{{ Form::close() }}
				<a class="btn btn-small btn-success" href="{{ URL::to('vdmBusRoutes/'. $value) }}">Show Routes</a>
	   
				<a class="btn btn-small btn-info" href="{{ URL::to('vdmSchool/' . $key . '/edit') }}">Edit School </a>
				
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@stop