@if (Session::has('error'))
  {{ trans(Session::get('reason')) }}
@elseif (Session::has('success'))
  An email with the password reset has been sent.
@endif

{{ Form::open(array('route' => 'password.postremind')) }}

  <p>{{ Form::label('userid', 'userid') }}
  {{ Form::text('userid') }}</p>
  <p>{{ Form::submit('Send Reminder') }}</p>

{{ Form::close() }}