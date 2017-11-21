app.controller('mainCtrl',['$scope','$http','vamoservice','$filter', '_global', function($scope,$http,vamoservice, $filter, GLOBAL){

 // global declaration
	
	$scope.uiDate 	=	{};

	// //loading start function
	// var startLoading		= function () {
	// 	$('#status').show(); 
	// 	$('#preloader').show();
	// };

	// //loading stop function
	// var stopLoading		= function () {
	// 	$('#status').fadeOut(); 
	// 	$('#preloader').delay(350).fadeOut('slow');
	// 	$('body').delay(350).css({'overflow':'visible'});
	// };


    $scope.trimColon = function(textVal){
		return textVal.split(":")[0].trim();
	}

    $scope.trimHyphen = function(textVal){

       var splitValue = textVal.split(/[ -]+/);

     return splitValue[0]+''+splitValue[1]+''+splitValue[2];
	}

    $scope.trimColonHrs = function(textVal){

      var hrsTime=textVal.split(":");
		
	 return hrsTime[0]+""+hrsTime[1]+""+hrsTime[2];
    }

  $scope.trimHyphenDate = function(textVal){

  	  var dates=textVal.split("-");
		
	return dates[0]+""+dates[1]+""+dates[2];
 }
 

 // var vehcId=getParameterByName('vid');

    function getUiValue(){

  	  $scope.uiDate.fromdate   =   $('#dateFrom').val();
      $scope.uiDate.fromtime   =   $('#timeFrom').val();
      $scope.uiDate.todate     =   $('#dateTo').val();
	  $scope.uiDate.totime     =   $('#timeTo').val();
    }

    function webCall(){

    	$scope.imgSrcData = [];

      //var imgUrl    = 'http://188.166.244.126:9000/getImages?vehicleId=vehicleId&fromTime=20170921093906&toTime=20170922182612&userId=vantec_user';
        var imgUrl  = GLOBAL.DOMAIN_NAME+'/getImages?vehicleId='+$scope.vehiname+'&fromTime='+$scope.trimHyphenDate($scope.uiDate.fromdate)+''+$scope.trimColonHrs($scope.uiDate.fromtime)+'&toTime='+$scope.trimHyphenDate($scope.uiDate.todate)+''+$scope.trimColonHrs($scope.uiDate.totime);

        console.log(imgUrl);

      	$http.get(imgUrl).success(function(data){
  			$scope.imgSrcData = data;
   		});
    }


	$scope.sort = sortByDate('startTime');
                
	function getParameterByName(name) {
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	//global declartion

	$scope.locations = [];
	$scope.url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+getParameterByName('vg');
	$scope.gIndex =0;
  //$scope.locations01 = vamoservice.getDataCall($scope.url);
	

	function sessionValue(vid, gname){
		sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
		$("#testLoad").load("../public/menu");
	}
	
	function getTodayDate(date) {
     	var date = new Date(date);
    	return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
    };



    function convert_to_24h(time_str) {
		//console.log(time_str);
 		var str		=	time_str.split(' ');
 		var stradd	=	str[0].concat(":00");
 		var strAMPM	=	stradd.concat(' '+str[1]);
 		var time = strAMPM.match(/(\d+):(\d+):(\d+) (\w)/);
	    var hours = Number(time[1]);
	    var minutes = Number(time[2]);
	    var seconds = Number(time[2]);
	    var meridian = time[4].toLowerCase();
	
	    if (meridian == 'p' && hours < 12) {
	      hours = hours + 12;
	    }
	    else if (meridian == 'a' && hours == 12) {
	      hours = hours - 12;
	    }	    
	    var marktimestr	=	''+hours+':'+minutes+':'+seconds;	    
	    return marktimestr;
    };


    // millesec to day, hours, min, sec
    $scope.msToTime = function(ms) 
    {
        days = Math.floor(ms / (24 * 60 * 60 * 1000));
	  	daysms = ms % (24 * 60 * 60 * 1000);
		hours = Math.floor((daysms) / (60 * 60 * 1000));
		hoursms = ms % (60 * 60 * 1000);
		minutes = Math.floor((hoursms) / (60 * 1000));
		minutesms = ms % (60 * 1000);
		seconds = Math.floor((minutesms) / 1000);
		if(days==0)
			return hours +" h "+minutes+" m "+seconds+" s ";
		else
			return days+" d "+hours +" h "+minutes+" m "+seconds+" s ";
	}

	$scope.recursiveEvent 	= 	function(locationEvent, indexEvent)
	{
		var index4 = 0;
		angular.forEach(locationEvent, function(value ,primaryKey){
			//console.log(' primaryKey '+primaryKey)
			index4 = primaryKey;
			if(locationEvent[index4].address == undefined)
			{
				var latEvent		 =	locationEvent[index4].latitude;
			 	var lonEvent		 =	locationEvent[index4].longitude;
				var tempurlEvent =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latEvent+','+lonEvent+"&sensor=true";
				delayed4(2000, function (index4) {
				      return function () {
				        google_api_call_Event(tempurlEvent, index4, latEvent, lonEvent);
				      };
				    }(index4));
			}
		})
	}


	function formatAMPM(date) {
    	  var date = new Date(date);
		  var hours = date.getHours();
		  var minutes = date.getMinutes();
		  var ampm = hours >= 12 ? 'PM' : 'AM';
		  hours = hours % 12;
		  hours = hours ? hours : 12; // the hour '0' should be '12'
		  minutes = minutes < 10 ? '0'+minutes : minutes;
		  var strTime = hours + ':' + minutes + ' ' + ampm;
		  return strTime;
	}


 // service call for the event report

/*	function webServiceCall(){
		$scope.siteData = [];
		if((checkXssProtection($scope.uiDate.fromdate) == true) && (checkXssProtection($scope.uiDate.fromtime) == true) && (checkXssProtection($scope.uiDate.todate) == true) && (checkXssProtection($scope.uiDate.totime) == true)) {
			
			var url 	= "http://"+globalIP+context+"/public//getActionReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime)+"&interval="+$scope.interval+"&stoppage="+$scope.uiValue.stop+"&stopMints="+$scope.uiValue.stopmins+"&idle="+$scope.uiValue.idle+"&idleMints="+$scope.uiValue.idlemins+"&notReachable="+$scope.uiValue.notreach+"&notReachableMints="+$scope.uiValue.notreachmins+"&overspeed="+$scope.uiValue.speed+"&speed="+$scope.uiValue.speedkms+"&location="+$scope.uiValue.locat+"&site="+$scope.uiValue.site+'&fromDateUTC='+utcFormat($scope.uiDate.fromdate,convert_to_24h($scope.uiDate.fromtime))+'&toDateUTC='+utcFormat($scope.uiDate.todate,convert_to_24h($scope.uiDate.totime));
			vamoservice.getDataCall(url).then(function(responseVal){
				$scope.recursiveEvent(responseVal, 0);
				$scope.eventData = responseVal;
				var entry=0,exit=0; 
				angular.forEach(responseVal, function(val, key){
					if(val.state == 'SiteExit')
						exit++ 
					else if (val.state == 'SiteEntry')
						entry++
				})
				$scope.siteEntry 	=	entry;
				$scope.siteExit 	=	exit;
				stopLoading();
			});
		}
		stopLoading();
	}
*/

	function _global_date_time(){

		//console.log('timer');
		startLoading();
		$scope.fromNowTS		    =	new Date();
        $scope.uiDate.fromdate 		=	getTodayDate($scope.fromNowTS);
        $scope.uiDate.todate 		=	getTodayDate($scope.fromNowTS);
        var date_time		 		=	$filter('date')($scope.fromNowTS.getTime(), "HH")
        $scope.uiDate.fromtime 		= 	date_time+":00:00";
  	 // $scope.uiDate.totime 		= 	date_time+":00";
  		var add_zero 				=  	(parseInt(date_time) +1)+"";
  		$scope.uiDate.totime 		= 	(add_zero.length > 1) ? add_zero+ ":00:00" : "0"+add_zero+ ":00:00";
  		
  		if(date_time == "23"){
  			$scope.uiDate.fromtime	=	'22:00:00';
  			$scope.uiDate.totime	=	'23:00:00';
  		}
		  	
	    webCall();
	    stopLoading();
	}

    setInterval(_global_date_time, 180000);


// initial method

	$scope.$watch("url", function (val) {
		vamoservice.getDataCall($scope.url).then(function(data) {
			$scope.vehicle_list = data;
			if(data.length){
				$scope.vehiname	= getParameterByName('vid');
				$scope.uiGroup 	= $scope.trimColon(getParameterByName('vg'));
				$scope.gName 	= getParameterByName('vg');
				angular.forEach(data, function(val, key){
					if($scope.gName == val.group){
						$scope.gIndex = val.rowId;
						angular.forEach(data[$scope.gIndex].vehicleLocations, function(value, keys){
							if($scope.vehiname == value.vehicleId)
							$scope.shortNam	= value.shortName;
						    $scope.vehIds = value.vehicleId;
						})
			        }
				});
				
			  sessionValue($scope.vehiname, $scope.gName)
			}

			_global_date_time();
		  	stopLoading();
		});	
	});


	$scope.groupSelection 	= function(groupName, groupId) {
		startLoading();
		$scope.gName 	= 	groupName;
		$scope.uiGroup 	= 	$scope.trimColon(groupName);
		$scope.gIndex	=	groupId;
		var url  		= 	GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+groupName;
		vamoservice.getDataCall(url).then(function(response){
			stopLoading();
			$scope.vehicle_list = response;
			$scope.shortNam		= response[$scope.gIndex].vehicleLocations[0].shortName;
			$scope.vehiname		= response[$scope.gIndex].vehicleLocations[0].vehicleId;
			sessionValue($scope.vehiname, $scope.gName)
			getUiValue();
			webCall();
            stopLoading();
		});
	}

	
	$scope.submitFunction 	=	function(){
		startLoading();
		getUiValue();
		webCall();
		stopLoading();
	}


	$scope.genericFunction 	= function (vehid, index){
		startLoading();
		$scope.vehiname		= vehid;
		sessionValue($scope.vehiname, $scope.gName)
		angular.forEach($scope.vehicle_list[$scope.gIndex].vehicleLocations, function(val, key){
			if(vehid == val.vehicleId)
				$scope.shortNam	= val.shortName;
		})
		getUiValue();
	  //webServiceCall();
		webCall();
       stopLoading();
	}

}]);



