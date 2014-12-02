<!--  <form action="{{ action('RemindersController@postRemind') }}" method="POST">
    <input type="email" name="email">
    <input type="submit" value="Send Reminder">
</form>
-->

@if (Session::has('error'))
  {{ trans(Session::get('reason')) }}
@elseif (Session::has('success'))
  An email with the password reset has been sent.
@endif
 
{{ Form::open(array('route' => 'password.request')) }}
 
  <p>{{ Form::label('userId', 'userId') }}
  {{ Form::text('userId') }}</p>
 
  <p>{{ Form::submit('Send Reminder') }}</p>
 
{{ Form::close() }}