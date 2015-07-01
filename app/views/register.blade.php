<!DOCTYPE html>
<html>
<head>
	<title>VAMOSGPS</title>
	<link href="http://almsaeedstudio.com/themes/AdminLTE/dist/css/AdminLTE.min.css" rel="stylesheet">
	<link rel="stylesheet" href="http://almsaeedstudio.com/themes/AdminLTE/bootstrap/css/bootstrap.min.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body class="login-page">
	
	{{ Form::open(array('url' => 'login')) }}
    <div class="login-box">
      <div class="login-logo">
        <img src="assets/imgs/logo.png"/>
        <h5>
        <?php if(Session::has('flash_notice')): ?>
            <div class="text-danger" id="flash_notice"><?php echo Session::get('flash_notice') ?></div>
        <?php endif; ?>
    	</h5>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">VAMO Systems Fleet Management</p>
        <form action="../../index2.html" method="post">
          <div class="form-group has-feedback">
            {{ Form::text('userName', Input::old('userName'), array('placeholder' => 'Username', 'class'=>'form-control')) }}
            <span style="top: 8px;" class="fa fa-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            {{ Form::password('password', array('placeholder' => 'Password', 'class'=>'form-control')) }}
            <span style="top: 8px;" class="fa fa-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">    
              <div class="checkbox icheck">
                <label class="">
                 <input name="remember" type="checkbox" /> Remember Me
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              {{ Form::submit('Sign In', array('class'=>'btn btn-primary btn-block btn-flat')) }}
            </div><!-- /.col -->
            {{ Form::close() }}
          </div>
        </form>

        <!-- /.social-auth-links -->

        <a href="#">{{ HTML::link('password/reset', 'Forgot your password?', array('id' => 'linkid'), true)}} </a><br>

      </div><!-- /.login-box-body -->
    </div>

    
	
<div style="display: none;">
<nav class="navbar navbar-inverse">

<div class="navbar-header">

		 <h1 class="logo"><a class="navbar-brand" ><img src="assets/imgs/logo.png"/>VAMO Systems Fleet Management</a></h1>
		
		<!-- 
		<a class="navbar-brand" ><img src="assets/imgs/logo.png"/><h2> VAMO Systems Fleet Management </h2></a>
		 -->
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
   VAMOSGPS is built on the nifty features found in modern browsers. You'll need to one of the browsers below to use VAMOSGPS effectively.
  	
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

