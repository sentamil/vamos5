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
		{{ Form::label('gt06nCount', 'GT06N Count') }}
		{{ Form::text('gt06nCount', $gt06nCount, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('tr02Count', 'TR02 Count') }}
		{{ Form::text('tr02Count', $tr02Count, array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('gt03aCount', 'GT03A Count') }}
		{{ Form::text('gt03aCount', $gt03aCount, array('class' => 'form-control')) }}
	</div>
	
	
	
	
	{{ Form::submit('Update IPAddress!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@stop