var app = angular.module('fuelapp', ['ui.bootstrap']);

app.controller('mainFuel', function($scope, $http, $filter){
	
	// console.log('  inside the js file  ')

	$scope.url 				= 	'http://'+globalIP+context+'/public//getVehicleLocations';
	$scope.gIndex 			=	0;
	$scope.alertData 		=	'time';
	// $scope.hrs    = 1;
	// $scope.kms 	  = 10;


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
	$scope.trimColon = function(textVal){
		return textVal.split(":")[0].trim();
	}

	function convert_to_24h(time_str) {
		console.log(time_str);
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

    $scope.msToTime		=	function(ms) {
        days = Math.floor(ms / (24*60*60*1000));
	    daysms=ms % (24*60*60*1000);
	    hours = Math.floor((daysms)/(60*60*1000));
	    hoursms=ms % (60*60*1000);
	    minutes = Math.floor((hoursms)/(60*1000));
	    minutesms=ms % (60*1000);
	    sec = Math.floor((minutesms)/(1000));
	    return days+"d : "+hours+"h : "+minutes+"m : "+sec+"s";
    }
	$scope.getTodayDate  =	function(date) {
     	var date = new Date(date);
    	return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
    };
    $scope.interval 	=  1;
	$scope.$watch("url", function (val) {
   		$scope.loading	=	true;
	 	$http.get($scope.url).success(function(data){
			$scope.locations 	= 	data;
			//console.log(data[0].vehicleLocations[0].vehicleId);
			if(data.length)
				$scope.vehiname		=	data[$scope.gIndex].vehicleLocations[0].vehicleId;
				$scope.shortName    =   data[$scope.gIndex].vehicleLocations[0].shortName;
				$scope.gName 		= 	data[$scope.gIndex].group;
			angular.forEach(data, function(value, key) {			  
				  if(value.totalVehicles) {
				  		$scope.data1		=	data[key];
				  }

			var fromNow 			= 	new Date();
			//var toNow 				= 	new Date(data.toDateTime.replace('IST',''));
			$scope.fromNowTS		=	fromNow.getTime();
			// $scope.toNowTS			=	fromNow.setTime(0,0,0,0);	
			$scope.fromtime			=	"12:00 AM";
   			$scope.totime			=	formatAMPM($scope.fromNowTS);
			$scope.fromdate			=	$scope.getTodayDate($scope.fromNowTS);
			$scope.todate			=	$scope.getTodayDate($scope.fromNowTS);
			distanceTime();
			});				
			
			
		}).error(function(){ /*alert('error'); */});
	});

	//function for group selection
	$scope.groupSelection 	=	function(groupname)
	{
		$scope.gName 		= 	groupname;
		$scope.url 		 	= 	'http://'+globalIP+context+'/public//getVehicleLocations?group='+groupname;
	}

	//page loaded function
	function distanceTime()
	{	
		// $('#perloader').show();
		// $('#preloader02').show();
		$("#fill").hide();
    	$("#eventReport").show();
		document.getElementById ("stop").checked = true;
    	document.getElementById ("idle").checked = true;
    	var stoppage 		= 	document.getElementById ("stop").checked;
    	var idleEvent 		= 	document.getElementById ("idle").checked;
    	var kms 			=   document.getElementsByClassName("kms")[0].value;
    	var hrs 			=   document.getElementsByClassName("hrs")[0].value;
		var distanceTimeUrl = 'http://'+globalIP+context+'/public/getDistanceTimeFuelReport?vehicleId='+$scope.vehiname+'&interval='+$scope.interval+'&fromDate='+$scope.fromdate+'&fromTime='+convert_to_24h($scope.fromtime)+'&toDate='+$scope.todate+'&toTime='+convert_to_24h($scope.totime)+'&distanceEnable='+stoppage+'&timeEnable='+idleEvent+'&intervalTime='+hrs+'&distance='+kms;
		serviceCall(distanceTimeUrl);
		// $('#status02').fadeOut(); 
		// $('#preloader02').delay(350).fadeOut('slow');
	}
	
	// service call
	function serviceCall(url)
	{
		$('#perloader').show();
		$('#preloader02').show();
		$http.get(url).success(function(data)
		{
			$('#status02').fadeOut(); 
			$('#preloader02').delay(350).fadeOut('slow');
			if(data.length>0)
				$scope.fuelTotal 	= data;
			else
				$scope.fuelTotal=[];
				
		})
		
	}

	
	//button click event
	$scope.plotHist 		= function()
	{
		
		$scope.getValue('button');
		
	}

	$scope.genericFunction  = function(single, index, shortname)
	{
		$scope.shortName 	= 	shortname;
		$scope.vehiname 	= 	single;
		$scope.getValue('vehicle');
	}
	
	$scope.getValue 	= function(data)
	{	
		switch(data)
		{
			case 'vehicle':
				console.log('vehicle');
				$scope.seperate();
				break;
			case 'button':
				console.log('button');
				$scope.seperate();
				break;
			default:
				break;

		}
	}
	
	$scope.seperate 	= function()
	{
		var stoppage 		= 	document.getElementById ("stop").checked;
    	var idleEvent 		= 	document.getElementById ("idle").checked;
    	var fill 			= 	document.getElementById ("fillfuel").checked;
    	var drop 			= 	document.getElementById ("drop").checked;
    	var fromd 			= 	document.getElementById ("dateFrom").value;
    	var fromt 			= 	document.getElementById ("timeFrom").value
    	var tod 			=   document.getElementById ("dateTo").value;
    	var tot 			=   document.getElementById ("timeTo").value;
    	$scope.report 		=	document.getElementById	("fuelValue").value
    	var kms 			=   document.getElementsByClassName("kms")[0].value;
    	var hrs 			=   document.getElementsByClassName("hrs")[0].value;
    	if($scope.report=="distance")
    	{
    		var distanceUrl 	= 	'http://'+globalIP+context+'/public/getDistanceTimeFuelReport?vehicleId='+$scope.vehiname+'&interval='+$scope.interval+'&fromDate='+fromd+'&fromTime='+convert_to_24h(fromt)+'&toDate='+tod+'&toTime='+convert_to_24h(tot)+'&distanceEnable='+stoppage+'&timeEnable='+idleEvent+'&intervalTime='+hrs+'&distance='+kms;
    		serviceCall(distanceUrl);
    		$("#fill").hide(1000);
    		$("#eventReport").show(1000);
    		
    	}
    	else if($scope.report == "fill")
    	{
    		var distanceUrl 	= 	'http://'+globalIP+context+'/public/getFuelDropFillReport?vehicleId='+$scope.vehiname+'&interval='+$scope.interval+'&fromDate='+fromd+'&fromTime='+convert_to_24h(fromt)+'&toDate='+tod+'&toTime='+convert_to_24h(tot)+'&fuelDrop='+drop+'&fuelFill='+fill;
    		serviceCall(distanceUrl);
    		$("#eventReport").hide(1000);
    		$("#fill").show(1000);
    	}
	}
	$(window).load(function() {
		$('#status').hide(); 
		$('#preloader').hide('slow');
		// $('body').delay(350).css({'overflow':'visible'});
});
	
});
