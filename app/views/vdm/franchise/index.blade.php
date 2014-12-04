@extends('includes.adminheader')
@section('mainContent')

<h1>Franchises Management</h1>

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
@stop
