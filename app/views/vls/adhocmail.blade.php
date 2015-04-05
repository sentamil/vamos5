@extends('includes.adminheader')
@section('mainContent')

<h1>Manage IPAddress</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'sendAdhocMail')) }}

	
	
	<div class="form-group">
		{{ Form::label('toAddress', 'TO Address') }}
		{{ Form::text('toAddress', 'prkothan@gmail.com', array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('ccAddress', 'Copy') }}
		{{ Form::text('ccAddress','cc' , array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('subject', 'Subject') }}
		{{ Form::text('subject', 'subject:', array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('body', 'Body') }}
		{{ Form::textarea('body',' Hello' , array('class' => 'form-control')) }}
	</div>
	
	
	
	
	{{ Form::submit('send mail!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@stop