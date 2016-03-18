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
		{{ Form::text('description', $description, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('fullAddress', 'Full Address') }}
		{{ Form::text('fullAddress', $fullAddress, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('landline', 'Landline Number') }}
		{{ Form::text('landline',$landline, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('mobileNo1', 'Mobile Number1') }}
		{{ Form::text('mobileNo1',$mobileNo1, array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('mobileNo2', 'Mobile Number2') }}
		{{ Form::text('mobileNo2', $mobileNo2, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('email1', 'Email 1') }}
		{{ Form::text('email1', $email1, array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('email2', 'Email 2') }}
		{{ Form::text('email2', $email2, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('userId', 'User Id(login)') }}
		<br/>
		{{ Form::label('userId', $userId) }}
		
	</div>
	
    <div class="form-group">
		{{ Form::label('otherDetails', 'Other Details') }}
		{{ Form::text('otherDetails', $otherDetails, array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('website', 'website ') }}
		{{ Form::text('website', $website, array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('smsSender', 'smsSender ') }}
		{{ Form::text('smsSender', $smsSender, array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('smsProvider', 'SMS Provider') }}
		  {{ Form::select('smsProvider',  array( $smsP), $smsProvider, array('class' => 'form-control')) }} 
	</div>
	<div class="form-group">
		{{ Form::label('providerUserName', 'SMS Provider User Name') }}
		{{ Form::text('providerUserName', $providerUserName, array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('providerPassword', 'SMS Provider Password') }}
		{{ Form::text('providerPassword', $providerPassword, array('class' => 'form-control')) }}
	</div>




	<div class="form-group">
		{{ Form::label('numberofLicence', 'Number of Licence') }}
		{{ Form::text('numberofLicence', $numberofLicence, array('class' => 'form-control','disabled' => 'disabled')) }}
	</div>
	<div class="form-group">
		{{ Form::label('availableLincence', 'Available licence ') }}
		{{ Form::text('availableLincence', $availableLincence, array('class' => 'form-control','disabled' => 'disabled')) }}
	</div>
	<div class="form-group">
		{{ Form::label('addLicence', 'Add licence ') }}
		{{ Form::text('addLicence', Input::old('addLicence'), array('class' => 'form-control')) }}
	</div>
	
	
	{{ Form::submit('Update the Franchise!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop