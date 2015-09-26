@if (Session::has('error'))
  {{ trans(Session::get('reason')) }}
@endif
 
 {{ HTML::ul($errors->all()) }}
 
{{ Form::open(array('route' => array('password.update', $token))) }}
 
  <p>{{ Form::label('userid', 'userid') }}
  {{ Form::text('userid') }}</p>

  <p>{{ Form::label('password', 'Password') }}
  {{ Form::password('password') }}</p>
 
  <p>{{ Form::label('password_confirmation', 'Password confirm') }}
  {{ Form::password('password_confirmation') }}</p>
 
  {{ Form::hidden('token', $token) }}
 
  <p>{{ Form::submit('Submit') }}</p>
 
{{ Form::close() }}