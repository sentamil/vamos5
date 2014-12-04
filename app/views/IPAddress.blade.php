@extends('includes.adminheader')
@section('mainContent')

<h1>Manage IPAddress</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'ipAddressManager')) }}

	
	
	<div class="form-group">
		{{ Form::label('IPAddress', 'IPAddress') }}
		{{ Form::text('ipAddress', $ipAddress, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('deviceHandler_gt06n', 'Device Handler GT06N') }}
		{{ Form::text('deviceHandler_gt06n', $deviceHandler_gt06n, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('portNo_gt06n', 'Port No GT06N') }}
		{{ Form::text('portNo_gt06n', $portNo_gt06n, array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('range_gt06n', 'range GT06N') }}
		{{ Form::text('range_gt06n', $range_gt06n, array('class' => 'form-control')) }}
	</div>
	
	
	
	
	{{ Form::submit('Update IPAddress!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@stop