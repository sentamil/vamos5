@extends('includes.vdmheader')
@section('mainContent')

<h1>Edit Group</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($groupId, array('route' => array('vdmGroups.update', $groupId), 'method' => 'PUT')) }}
	
	<div class="form-group">
		{{ Form::label('groupId', 'Group Id :')  }}
		
		{{ Form::label('groupId' , $groupId) }}
	</div>
	

	
	<div class="form-group">
		{{ Form::label('vehicleList', 'Vehicle List (press shift to select multiple vehicles)') }}
		{{ Form::select('vehicleList[]', $vehicleList, Input::old('vehicleList'),  array('multiple' => true,'class' => 'form-control')) }}

	</div>

	{{ Form::submit('Update the Group!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop