@extends('includes.vdmheader')
@section('mainContent')

<h1>Edit User</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}


{{ Form::model($userId, array('route' => array('vdmUsers.update', $userId), 'method' => 'PUT')) }}


	<div class="form-group">
		{{ Form::label('userId', 'UserId :') }}
		<br/>
		{{ Form::label('userId', $userId) }}
	</div>

<div class="form-group">
		{{ Form::label('mobileNo', 'mobileNo') }}
		{{ Form::text('mobileNo', $mobileNo, array('class' => 'form-control')) }}
	</div>
<div class="form-group">
		{{ Form::label('email', 'email') }}
		{{ Form::text('email', $email, array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('vehicleGroups', 'Vehicle Groups') }}
		{{ Form::select('vehicleGroups[]', $vehicleGroups, Input::old('vehicleGroups'),  array('multiple' => true,'class' => 'form-control')) }}
		
	</div>

	{{ Form::submit('Update the User!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop