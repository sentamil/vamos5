
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
		<a class="navbar-brand" ><h2><strong> VAMOS Vehicle Management <strong></h2></a>
	</div>
	
</nav>
	{{ Form::open(array('url' => 'login')) }}
		<h3><br/>Login</h3>

		<!-- if there are login errors, show them here -->
		<br/>
		<!-- 
		<p>
			{{ $errors->first('userName') }}
			{{ $errors->first('password') }}
		</p>
		 -->
		 <p>
            <?php  if(Session::has('flash_notice')): ?>
                <div id="flash_notice"><?php echo Session::get('flash_notice') ?></div>
            <?php endif; ?>
        </p>
        
		<p>
			{{ Form::label('userName', 'User Name') }}
			{{ Form::text('userName', Input::old('userName'), array('placeholder' => 'username')) }}
		</p>

		<p>
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password') }}
		</p>
		  <div>
            <input name="remember" type="checkbox" /> Remember me
        </div>
        
      
				<br/>
		<p>{{ Form::submit('Submit!') }}</p>
			{{ Form::close() }}
			
	<h3>Password Reset</h3>

    <div>
      To reset your password, complete this form:
  
      {{ HTML::link('password/reset', 'Reset', array('id' => 'linkid'), true)}}
      
    </div>

	
</div>

</body>
</html>

