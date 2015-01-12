@extends('includes.vdmheader')
@section('mainContent')


<h1>Create a User</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmUsers')) }}

	<div class="form-group">
		{{ Form::label('userId', 'userId') }}
		{{ Form::text('userId', Input::old('userId'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('mobileNo', 'mobileNo') }}
		{{ Form::text('mobileNo', Input::old('mobileNo'), array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('email', 'email') }}
		{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
	</div>

	<!-- 
	<div class="form-group">
		{{ Form::label('vehicleGroups', 'Vehicle Groups') }}
		{{ Form::select('vehicleGroups[]', $vehicleGroups, Input::old('vehicleGroups'),  array('multiple' => true,'class' => 'form-control')) }}

	</div>
	 -->
	
	 <div class="form-group">
	 {{ Form::label('vehicleGroups', 'Select the Groups:') }}
	 </div>
	 
		@foreach($vehicleGroups as $key => $value)
			{{ Form::checkbox('vehicleGroups[]', $key, null, ['class' => 'field']) }}
			{{ Form::label($value) }}
			<br/>
		@endforeach
		</br/>
		</br/>

	{{ Form::submit('Create the User!', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
@stop