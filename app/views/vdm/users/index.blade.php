@extends('includes.vdmheader')
@section('mainContent')


<h1>User Management</h1>

<!-- will be used to show any messages -->
@if (Session::has('message'))
	<div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>ID</td>
			<td>UserId</td>
			<td>UserGroups</td>
			<td>Code</td>
			<td>Actions</td>

		</tr>
	</thead>

	
	<tbody>
	@foreach($userList as $key => $value)
		<tr>
			<td>{{ $key }}</td>
			<td>{{ $value }}</td>
			<td>{{ array_get($userGroupsArr, $value)}}</td>	
			<td>{{ $cpyCode }}</td>
				 
				 
			<!-- we will also add show, edit, and delete buttons -->
			<td>

				<!-- delete the nerd (uses the destroy method DESTROY /nerds/{id} -->
				<!-- we will add this later since its a little more complicated than the other two buttons -->
				{{ Form::open(array('url' => 'vdmUsers/' . $value, 'class' => 'pull-right')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					{{ Form::submit('Delete this User', array('class' => 'btn btn-warning')) }}
				{{ Form::close() }}
				<!-- show the nerd (uses the show method found at GET /nerds/{id} -->
				<a class="btn btn-small btn-success" href="{{ URL::to('vdmUsers/' . $value) }}">Show this User</a>

				<!-- edit this nerd (uses the edit method found at GET /nerds/{id}/edit -->
				<a class="btn btn-small btn-info" href="{{ URL::to('vdmUsers/' . $value . '/edit') }}">Edit this User</a>

			</td>
		</tr>
	@endforeach
	</tbody>
	
</table>
@stop