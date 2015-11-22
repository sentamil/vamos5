@extends('includes.adminheader')
@section('mainContent')
<h1>Add a Franchise</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmFranchises')) }}



	<div class="form-group">
		{{ Form::label('fname', 'Franchise Name') }}
		{{ Form::text('fname', Input::old('fname'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('fcode', 'Franchise Code') }}
		{{ Form::text('fcode', Input::old('fcode'), array('class' => 'form-control')) }}
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
		{{ Form::text('userId', Input::old('userId'), array('class' => 'form-control')) }}
	</div>
	
    <div class="form-group">
		{{ Form::label('otherDetails', 'Other Details') }}
		{{ Form::text('otherDetails', Input::old('otherDetails'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('numberofLicence', 'Number of Licence') }}
		{{ Form::text('numberofLicence', Input::old('numberofLicence'), array('class' => 'form-control')) }}
	</div>
	
	{{ Form::submit('Add the Franchise!', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
@stop
