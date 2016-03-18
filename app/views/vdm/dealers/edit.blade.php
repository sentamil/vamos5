@extends('includes.vdmheader')
@section('mainContent')

<h2><font color="blue">Edit Dealer</font></h2>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}


{{ Form::model($dealerid, array('route' => array('vdmDealers.update', $dealerid), 'method' => 'PUT')) }}


							<div class="row">
								<div class="col-md-4">
	<div class="form-group">
		<h4><font color="green">{{ Form::label('dealerid', 'Dealer Id :') }}</h4>
		
		{{ Form::label('dealerid', $dealerid) }}</font>
	</div>

<div class="form-group">
		{{ Form::label('mobileNo', 'MobileNo :') }}
		{{ Form::text('mobileNo', $mobileNo, array('class' => 'form-control')) }}
	</div>
<div class="form-group">
		{{ Form::label('email', 'EMail :') }}
		{{ Form::text('email', $email, array('class' => 'form-control')) }}
	</div>
<div class="form-group">
		{{ Form::label('website', 'Website :') }}
		{{ Form::text('website', $website, array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('smsSender', 'Sms Sender  :') }}
		{{ Form::text('smsSender', $smsSender, array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('smsProvider', 'SMS Provider :') }}
		{{ Form::select('smsProvider',  array( $smsP), $smsProvider, array('class' => 'form-control')) }} 
	</div>
	<div class="form-group">
		{{ Form::label('providerUserName', 'Provider UserName :') }}
		{{ Form::text('providerUserName', $providerUserName, array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('providerPassword', 'Provider Password :') }}
		{{ Form::text('providerPassword', $providerPassword, array('class' => 'form-control')) }}
	</div>
		 
		</br/>
		</br>
	
	{{ Form::submit('Update the User!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop