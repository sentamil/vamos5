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
            {{ Form::text('fullName', Input::old('fullName'), array('placeholder' => 'Full name', 'class'=>'form-control')) }}
            <span style="top: 8px;" class="fa fa-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            {{ Form::text('phoneNumber', Input::old('fullName'), array('placeholder' => 'Phone number', 'class'=>'form-control')) }}
            <span style="top: 8px;" class="fa fa-mobile-phone form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            {{ Form::email('email_address',  Input::old('email'), array('placeholder' => 'Email', 'class'=>'form-control')) }}
            <span style="top: 8px;" class="fa fa-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            {{ Form::password('password', array('placeholder' => 'Password', 'class'=>'form-control')) }}
            <span style="top: 8px;" class="fa fa-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            {{ Form::password('password', array('placeholder' => 'Retyp password', 'class'=>'form-control')) }}
            <span style="top: 8px;" class="fa fa-sign-in form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">    
              <div class="checkbox icheck">
                <label class="">
                 <input name="remember" type="checkbox" /> I agree to the <a href="#">{{ HTML::link('', 'terms', array(), true)}} </a>
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              {{ Form::submit('Register', array('class'=>'btn btn-primary btn-block btn-flat')) }}
            </div><!-- /.col -->
            {{ Form::close() }}
          </div>
        </form>

        <!-- /.social-auth-links -->

        <a href="#">{{ HTML::link('login', 'I already have a membership', array(), true)}} </a><br>

      </div><!-- /.login-box-body -->
    </div>
</body>
</html>

