<!DOCTYPE html>
<html>
<head>
  <title>GPS</title>
  
  <link rel="stylesheet" type="text/css" href="assets/css/login.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

</head>
<link rel="shortcut icon" href="assets/imgs/tab.ico">
<body class="cont">

  {{ Form::open(array('url' => 'login')) }}
    <div class="demo">
      <div class="login"  align="center">
      <div class="login__check"> </div>
        <img id="imagesrc" style="border-radius: 8px;max-width: 100%;height: auto;"/>
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

          var wwwSplit = logo.split(".")
              if(wwwSplit[0]=="www"){
                wwwSplit.shift();
                imgName = '/'+splitpath[1]+'/public/uploads/'+wwwSplit[0]+'.'+wwwSplit[1]+'.png';
              }

          
          //cons
          $('#imagesrc').attr('src', imgName);
        </script>
        <br>
        <br>
        <p class="login__signup" style="font-size: 14px"><a>GPS Tracking System</a></p>
        <h5>
          <?php if(Session::has('flash_notice')): ?>
            <div class="flashMessage" id="flash_notice"><?php echo Session::get('flash_notice') ?></div>
          <?php endif; ?>
        </h5>

        <div class="login__form">
          
          <div class="login__row">
            <svg class="login__icon name svg-icon" viewBox="0 0 20 20">
              <path d="M0,20 a10,8 0 0,1 20,0z M10,0 a4,4 0 0,1 0,8 a4,4 0 0,1 0,-8" />
            </svg>
            {{ Form::text('userName', Input::old('userName'), array('placeholder' => 'Username', 'class'=>'login__input name', 'id'=>'userIds')) }}
          </div>
          <div class="login__row">
            <svg class="login__icon pass svg-icon" viewBox="0 0 20 20">
              <path d="M0,20 20,20 20,8 0,8z M10,13 10,16z M4,8 a6,8 0 0,1 12,0" />
            </svg>
            {{ Form::password('password', array('placeholder' => 'Password', 'class'=>'login__input pass')) }}
          </div>

        {{ Form::submit('Login', array('class'=>'login__submit', 'id'=>'clickme')) }}
     
       <p class="login__signup"><a href="#">{{ HTML::link('password/reset', 'Forgot/reset your password?', array('id' => 'linkid'), false)}} </a> &nbsp;<a href="/gps/public/apiAcess">Api Access</a></p>

        <span style="padding: 10px">
          <div id="cf">
            <img class="bottom" src="/gps/public/assets/imgs/andG.png" style="width: 25px; height: 25px"/>
            <img class="top" src="/gps/public/assets/imgs/andGy.png" style="width: 25px; height: 25px"/>
          </div>

          <div id="cff">
            <img class="bottom" src="/gps/public/assets/imgs/appG.png" style="width: 25px; height: 25px"/>
            <img class="top" src="/gps/public/assets/imgs/appGy.png" style="width: 25px; height: 25px"/>
          </div>
         
      </span>
      <label style="font-size: 10px; color: #fff">
       <input name="remember" type="checkbox" /> Remember Me
      </label>
        
        </div>
    </div>

     
      

    </div>
     {{ Form::close() }}
</body>
    <script type="text/javascript">

       $('#clickme').click(function(){
            var userId  = $('#userIds').val();
            sessionStorage.setItem('userIdName', JSON.stringify('username'+","+userId));
        });

  $('#userIds').on('change', function() {
    
    var postValue = {
      'id': $(this).val()

      };
    // alert($('#groupName').val());
    $.post('{{ route("ajax.apiKeyAcess") }}',postValue)
      .done(function(data) {
        
        // $('#validation').text(data);
            sessionStorage.setItem('apiKey', JSON.stringify(data));
            
          }).fail(function() {
            console.log("fail");
      });

    
  })

    </script>
