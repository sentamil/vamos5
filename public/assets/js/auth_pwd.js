app.controller('mainCtrl',function($scope){
	


	//loading start function
	var startLoading		= function () {
		$scope.statusUi  = '';
		$('#status').show(); 
		$('#preloader').show();
	};

	//loading stop function
	var stopLoading		= function () {
		$('#status').fadeOut(); 
		$('#preloader').delay(350).fadeOut('slow');
		$('body').delay(350).css({'overflow':'visible'});
	};

	stopLoading();


	// checking auth in the addsite and rfid 

  	$scope.passwordConfirm  = function(){
  		startLoading();
  		
   		var password  = $scope.pwd;
    	var URL_ROOT    = "AddSiteController/";    /* Your website root URL */
    	$.post(URL_ROOT+'checkPwd',{
      		'_token': $('meta[name=csrf-token]').attr('content'),
      		'pwd': password,
    	}).done(function(data){
      		console.log('Sucess---------->' + data+location.pathname);
      		stopLoading();
      		if(data == 'incorrect')
      			$scope.statusUi   = "Invalid Password"; 
      		else if (data == 'correct'){
            var path = context+'/public/track?maps=sites'
      			location.href = path;
      			sessionStorage.setItem('auth', 'sitesVal');
      		}
      			
      			     
    	}).fail(function(){
    		stopLoading();
      		console.log('fail');
      		$scope.statusUi   = "Response Failure";  
    	})
	}

});
