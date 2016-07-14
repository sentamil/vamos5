<!DOCTYPE html>
<html>
<head>
  <title>GPS</title>
  <link href="http://almsaeedstudio.com/themes/AdminLTE/dist/css/AdminLTE.min.css" rel="stylesheet">
  <link rel="stylesheet" href="http://almsaeedstudio.com/themes/AdminLTE/bootstrap/css/bootstrap.min.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>

<body class="login-page">
  
  {{ Form::open(array('url' => 'login')) }}
    <div class="login-box">
      <div class="login-logo">
        <img id="imagesrc"/>
        <script>
          
                  
          sessionStorage.clear();
          var logo =document.location.host;
          function ValidateIPaddress(ipaddress)   
          {  
           var ipformat = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;  
            if(ipaddress.match(ipformat)) {
              return (true)  
            }  
          // alert("You have entered an invalid IP address!")  
          return (false)  
          }  



          if(ValidateIPaddress(logo)) {
            var parser    =   document.createElement('a');
            parser.href   =   document.location.ancestorOrigins[0];
            logo      =   parser.host;
          }


         
          // (function test(){
          //    console.log("arun")
          // }());
          


          var path = document.location.pathname;
          var splitpath  = path.split("/");
          //console.log(' path '+"----"+splitpath[1]);

          var imgName= '/'+splitpath[1]+'/public/uploads/'+logo+'.png';
          //cons
          $('#imagesrc').attr('src', imgName);
        </script>
        <h5>
        <?php if(Session::has('flash_notice')): ?>
            <div class="text-danger" id="flash_notice"><?php echo Session::get('flash_notice') ?></div>
        <?php endif; ?>
      </h5>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">GPS Tracking System</p>
        <form action="../../index2.html" method="post">
          <div class="form-group has-feedback">
            {{ Form::text('userName', Input::old('userName'), array('placeholder' => 'Username', 'class'=>'form-control', 'id'=>'userIds')) }}
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
              {{ Form::submit('Login', array('class'=>'btn btn-primary btn-block btn-flat', 'id'=>'clickme')) }}
            </div><!-- /.col -->
            {{ Form::close() }}
          </div>
        </form>
        
        <!-- /.social-auth-links -->

        <a href="#">{{ HTML::link('password/reset', 'Forgot/reset your password?', array('id' => 'linkid'), false)}} </a><br>
        <a href="/gps/public/apiAcess">Api Access</a><br>
        <a href="#" target="_blank"><img src="/gps/public/assets/imgs/apple.png" /></a>
        <a href="#" target="_blank"><img src="/gps/public/assets/imgs/android.png" /></a>
        
      </div><!-- /.login-box-body -->
    </div>
    <script type="text/javascript">

       $('#clickme').click(function(){
            var userId  = $('#userIds').val();
            sessionStorage.setItem('userIdName', JSON.stringify('username'+","+userId));
        });
    </script>
