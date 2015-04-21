
<!DOCTYPE html>
<html>
<head>
	<title>VAMOS</title>
	
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	
</head>
<body>
<div class="container">
<div>
<nav class="navbar navbar-inverse">

<div class="navbar-header">

		 <h1 class="logo"><a class="navbar-brand" ><img src="assets/imgs/logo.png"/>Vehicle Advanced Monitoring System</a></h1>
		
		
	</div>
	
</nav>
<br/>
	{{ Form::open(array('url' => 'login')) }}
		<h4>Login :</h4>

		<!-- if there are login errors, show them here -->
	
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
					<br/>
	<h4>Password Reset :</h4>

    <div>
      To reset your password, complete this form:
  
      {{ HTML::link('password/reset', 'Reset', array('id' => 'linkid'), true)}}
      
    </div>

    <div>
    
    <br/>

    
   <h4> Browser Requirements :</h4>
   VAMOS is built on the nifty features found in modern browsers. You'll need to one of the browsers below to use VAMOS effectively.
  	
<br/>
<br/>
   <p><a href="http://www.mozilla.org/en-US/firefox/new/">Mozilla Firefox - latest version</a></p>
   <p><a href="https://www.google.com/chrome">Google Chrome - latest version</a></p>
	<p><a href="http://www.apple.com/safari/">Apple Safari 8+</a></p>

<p>Apple iOS 8+</p>

<p>Android Version 4+</p>
    </div>
	
</div>

</body>
</html>

