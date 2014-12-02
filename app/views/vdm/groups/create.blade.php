@extends('includes.vdmheader')
@section('mainContent')
<h1>Create a Group</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmGroups')) }}

	<div class="form-group">
		{{ Form::label('groupId', 'Group Id') }}
		{{ Form::text('groupId', Input::old('groupId'), array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('vehicleList', 'Vehicle List (press shift to select multiple vehicles)') }}
		{{ Form::select('vehicleList[]', $userVehicles, Input::old('vehicleList'),  array('multiple' => true,'class' => 'form-control')) }}

	</div>

	{{ Form::submit('Create the Group!', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
@stop