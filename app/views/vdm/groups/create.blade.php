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
	 {{ Form::label('vehicleList', 'Select the vehicles:') }}
	 </div>
	 
		@foreach($userVehicles as $key => $value)
			{{ Form::checkbox('vehicleList[]', $key, null, ['class' => 'field']) }}
			{{ Form::label($value) }}
			<br/>
		@endforeach
		</br/>
		</br/>
	{{ Form::submit('Create the Group!', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
@stop