@extends('includes.adminheader')
@section('mainContent')

<h1>Amend Franchises</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($fcode, array('route' => array('vdmFranchises.update', $fcode), 'method' => 'PUT')) }}

	<div class="form-group">
		{{ Form::label('fname', 'Franchise Name') }}
	<br/>
		{{ Form::label('fname', $fname) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('fcode', 'Franchise Code') }}
	<br/>
		{{ Form::label('fcode', $fcode) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('description', 'Description') }}
		{{ Form::text('description', Input::old('description'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('fullAddress', 'Full Address') }}
		{{ Form::text('fullAddress', Input::old('fullAddress'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('landline', 'Landline Number') }}
		{{ Form::text('landline', Input::old('landline'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('mobileNo1', 'Mobile Number1') }}
		{{ Form::text('mobileNo1', Input::old('mobileNo1'), array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('mobileNo2', 'Mobile Number2') }}
		{{ Form::text('mobileNo2', Input::old('mobileNo2'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('email1', 'Email 1') }}
		{{ Form::text('email1', Input::old('email1'), array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('email2', 'Email 2') }}
		{{ Form::text('email2', Input::old('email2'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('userId', 'User Id(login)') }}
		<br/>
		{{ Form::label('userId', $userId) }}
		
	</div>
	
    <div class="form-group">
		{{ Form::label('otherDetails', 'Other Details') }}
		{{ Form::text('otherDetails', Input::old('otherDetails'), array('class' => 'form-control')) }}
	</div>
	
	
	{{ Form::submit('Update the Franchise!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop