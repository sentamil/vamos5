
<!DOCTYPE html>
<html>
<head>
	<title>VAMO Data Management</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">

	
</head>
<body>
<div class="container">
<div>
<nav class="navbar navbar-inverse">

<div class="navbar-header">
		<a class="navbar-brand" ><h2><strong> VAMO SYSTEMS Data Management <strong></h2></a>
	</div>
	
</nav>
	{{ Form::open(array('url' => 'login')) }}
		<h3><br/>Login</h3>

		<!-- if there are login errors, show them here -->
		<br/>
		<p>
			{{ $errors->first('userName') }}
			{{ $errors->first('password') }}
		</p>

		<p>
			{{ Form::label('userName', 'User Name') }}
			{{ Form::text('userName', Input::old('userName'), array('placeholder' => 'username')) }}
		</p>

		<p>
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password') }}
		</p>
				<br/>
		<p>{{ Form::submit('Submit!') }}</p>
			{{ Form::close() }}


</div>

</body>
</html>

