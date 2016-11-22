//comment by satheesh ++...
var getIP	=	globalIP;
app.controller('mainCtrl',function($scope, $http, $timeout, $interval){
	
	//$("#testLoad").load("../public/menu");
	var getUrl  =   document.location.href;
	var index   =   getUrl.split("=")[1];
	if(index == 1){
		$scope.actTab 	=	true;
		$(window).load(function(){
        	$('#myModal').modal('show');
    	});
	}
	else if(index ==2 )
		$scope.siteTab 		=	true;

		
	$scope.tab 		=	true;
	$scope.vvid			=	getParameterByName('vid');
	$scope.mainlist		=	[];
	$scope.newAddr      = 	{};
	$scope.groupId 		=   0;
	$scope.url 			= 	'http://'+getIP+context+'/public/getVehicleLocations';
	
	$scope.getTodayDate1  =	function(date) {
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

    function format24hrs(date)
    {
    	var date1 = new Date(date);
    	var hrs   = date.getHours();
    	var min   = date.getMinutes();
    	var sec   = date.getSeconds();
    	//console.log(hrs+':'+min+':'+sec)
    	return hrs+':'+min+':'+sec;
    }

    $scope.trimColon = function(textVal){
		return textVal.split(":")[0].trim();
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

	

	$scope.url 			  =   'http://'+getIP+context+'/public//getVehicleLocations';
	$scope.fromTime       =   '12:00 AM';
	$scope.vehigroup;
	$scope.consoldateData =   [];
	$scope.today 		  =   new Date();
	 
	$scope.sort = {       
                sortingOrder : 'id',
                reverse : false
            };
    $scope.$watch("url", function (val) {
  		startLoading();
	 	$http.get($scope.url).success(function(data){
	 		if(data.length >0 && data != ''){

				$scope.locations 	= 	data;
				$scope.vehigroup    =   data[$scope.groupId].group;
				$scope.vehiname		=	data[$scope.groupId].vehicleLocations[0].vehicleId;
				sessionStorage.setItem('user', JSON.stringify($scope.vehiname+','+$scope.vehigroup));
				$("#testLoad").load("../public/menu");
				angular.forEach(data, function(value, key) {
					   if(value.totalVehicles) {
					  		$scope.data1		=	data[key];
					  }
				});				
				if($scope.siteTab == true)
					{$scope.consoldateTrip();$scope.siteTab == false}
				
				$scope.recursive($scope.data1.vehicleLocations,0);
			}
			stopLoading();
		}).error(function(){stopLoading();});
	});
    // address resolving with delay function
	$scope.selectMe = function(consoldateData)
    {
    	angular.forEach(consoldateData, function(value, primaryKey){
    		var index1 = primaryKey;
    		angular.forEach(value.historyConsilated, function(value, secondKey){
    			var index2 = secondKey;
    			if(value.state == 'S' || value.state == 'P' )
    			{
    				if(value.address == undefined)
    				{
    					var url_address	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+value.latitude+','+value.longitude+"&sensor=true";
    					var latCon       =  value.latitude;
    					var loncon		 =  value.longitude;
    					delayed(3000, function (index1, index2) {
					      return function () {
					        $scope.getAddressFromGAPI(url_address, index1, index2, latCon, loncon);
					      };
					    }(index1, index2));
    				}
    			}
    		})
    	})
    }

    var delayed = (function () {
  		var queue = [];

	  	function processQueue() {
		    if (queue.length > 0) {
		      setTimeout(function () {
		        queue.shift().cb();
		        processQueue();
		      }, queue[0].delay);
		    }
	  	}

	  	return function delayed(delay, cb) {
	    	queue.push({ delay: delay, cb: cb });

	    	if (queue.length === 1) {
	      	processQueue();
	    	}
	  	};
	}());

    // web service call in the consoldate report
	var service = function(conUrl)
	{
		$http.get(conUrl).success(function(data)
		{
			$scope.consoldateData = data;
			$('#preloader').fadeOut(); 
			$('#preloader02').delay(350).fadeOut('slow');
			if($scope.consoldateData)
				$scope.selectMe($scope.consoldateData);
			else
			{
				$scope.connSlow = "No Data found! Please contact our admin.";
				$('#connSlow').modal('show');
			}
		});
	}
  	 
	$scope.getAddressFromGAPI = function(url, index1, index2, lat, lan) {
		$http.get(url).success(function(data) {
    		
    		// Declare the new property address to the existing list
			$scope.consoldateData[index1].historyConsilated[index2].address = " ";
			$scope.consoldateData[index1].historyConsilated[index2].address = data.results[0].formatted_address;
			// var t = vamo_sysservice.geocodeToserver(lat, lan, data.results[0].formatted_address);
    	})
	};


	

	$scope.consoldate1 =  function()
	{
		// $('#preloader').show(); 
		// $('#preloader02').show();
		startLoading();
		$scope.fromdate1   =  document.getElementById("dateFrom").value;
		$scope.fromTime    =  document.getElementById("timeFrom").value;
		$scope.todate1     =  document.getElementById("dateTo").value;
		$scope.totime      =  document.getElementById("timeTo").value;
		var conUrl1        =  'http://'+getIP+context+'/public/getOverallVehicleHistory?group='+$scope.vehigroup+'&fromDate='+$scope.fromdate1+'&fromTime='+convert_to_24h($scope.fromTime)+'&toDate='+$scope.todate1+'&toTime='+convert_to_24h($scope.totime)+'&fromDateUTC='+utcFormat($scope.fromdate1,convert_to_24h($scope.fromTime))+'&toDateUTC='+utcFormat($scope.todate1,convert_to_24h($scope.totime));
		var days = daydiff(new Date($scope.fromdate1), new Date($scope.todate1));
		if(days <= 3)
			service(conUrl1);
		else {
			// $('#preloader').fadeOut(); 
			// $('#preloader02').delay(350).fadeOut('slow');
			stopLoading()
			$scope.connSlow = "Sorry for the inconvenience, Its a three days report"
			$('#connSlow').modal('show');
		}
	}

	$scope.dateFunction = function()
	{
		$scope.fromNowTS1		=	new Date();
		$scope.fromdate1		=	$scope.getTodayDate1($scope.fromNowTS1.setDate($scope.fromNowTS1.getDate()));
		$scope.todate1			=	$scope.getTodayDate1($scope.fromNowTS1.setDate($scope.fromNowTS1.getDate()));
		$scope.totime		    =	$scope.fromNowTS1.toLocaleTimeString(navigator.language, {hour: '2-digit', minute:'2-digit'});
		$scope.checkBox.loc 	= true;
		$scope.checkBox.site 	= true;
	}
	
	$scope.consoldate =  function()
	{
		
		// $('#preloader').show(); 
		// $('#preloader02').show();
		startLoading();
		$scope.dateFunction();
		var conUrl              =   'http://'+getIP+context+'/public/getOverallVehicleHistory?group='+$scope.vehigroup+'&fromDate='+$scope.fromdate1+'&fromTime='+convert_to_24h($scope.fromTime)+'&toDate='+$scope.todate1+'&toTime='+convert_to_24h($scope.totime)+'&fromDateUTC='+utcFormat($scope.fromdate1,convert_to_24h($scope.fromTime))+'&toDateUTC='+utcFormat($scope.todate1,convert_to_24h($scope.totime));
		service(conUrl);
	}
	
	function serviceCallTrip (url){
		$http.get(url).success(function(response){
			$scope.tripData = response;
			stopLoading()
			// $('#preloader').fadeOut(); 
			// $('#preloader02').delay(350).fadeOut('slow');
		})
	}


	$scope.checkBox =	{}
	$scope.consoldateTrip = function(valu)
	{	
		startLoading();
		// $('#preloader').show(); 
		// $('#preloader02').show();
		// $scope.tripValu = valu;
		// console.log(' trip '+valu+'---->'+$scope.checkBox.site+$scope.checkBox.loc);
		if(valu == 'tripButon')
		{
			$scope.fromdate1   =  document.getElementById("tripfrom").value;
			$scope.fromTime    =  '12:00 AM';
			// $scope.todate1     =  document.getElementById("tripto").value;
			$scope.totime      =  '11:59 PM';
		}
			
		else
			$scope.dateFunction(); 
		var conUrl              =   'http://'+getIP+context+'/public/getOverallSiteLocationReport?group='+$scope.vehigroup+'&fromDate='+$scope.fromdate1+'&fromTime='+convert_to_24h($scope.fromTime)+'&toDate='+$scope.fromdate1+'&toTime='+convert_to_24h($scope.totime)+'&location='+$scope.checkBox.loc+'&site='+$scope.checkBox.site+'&fromDateUTC='+utcFormat($scope.fromdate1,convert_to_24h($scope.fromTime))+'&toDateUTC='+utcFormat($scope.todate1,convert_to_24h($scope.totime));
		serviceCallTrip(conUrl);
		console.log('  consoldate trip '+$scope.fromdate1 +$scope.fromTime+$scope.todate1 +$scope.totime);
		
	}

	// $scope.consoldateTripButton = function(){
	// 	console.log('  consoldate trip '+$scope.fromdate1 +$scope.fromTime+$scope.todate1 +$scope.totime);
	// }



	$scope.dialogBox 	=	function()
	{
		$scope.tab = false;
		$scope.fromdate1   =  document.getElementById("dateFrom").value;
		$scope.fromTime    =  document.getElementById("timeFrom").value;
		$scope.todate1     =  document.getElementById("dateTo").value;
		$scope.totime      =  document.getElementById("timeTo").value;
		
	}

	

	$scope.exportData = function (data) {
		var blob = new Blob([document.getElementById(data).innerHTML], {
           	type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };
    $scope.exportDataCSV = function (data) {
		CSV.begin('#'+data).download(data+'.csv').go();
    };

    $scope.address_click = function(data, ind)
	{
		var urlAddress 		=	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+data.latitude+','+data.longitude+"&sensor=true"
		$http.get(urlAddress).success(function(response)
		{
			data.address 	=	response.results[0].formatted_address;
			// var t 			= 	vamo_sysservice.geocodeToserver(data.latitude,data.longitude,response.results[0].formatted_address);
		});
	}

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
	
	$scope.recursive   = function(location,index){
		var index3 = 0;
		angular.forEach(location, function(value, primaryKey){
    		index3 = primaryKey;
    		if(location[index3].address == undefined)
				{
					//console.log(' address idle'+index3)
					var latIdle		 =	location[index3].latitude;
				 	var lonIdle		 =	location[index3].longitude;
					var tempurlIdle	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+latIdle+','+lonIdle+"&sensor=true";
					//console.log(' Idle report  '+index3)
					delayed1(3000, function (index3) {
					      return function () {
					        google_api_call(tempurlIdle, index3, latIdle, lonIdle);
					      };
					    }(index3));
				}
    	})
	}
	
	var delayed1 = (function () {
  		var queue = [];

	  	function processQueue() {
		    if (queue.length > 0) {
		      setTimeout(function () {
		        queue.shift().cb();
		        processQueue();
		      }, queue[0].delay);
		    }
	  	}

	  	return function delayed(delay, cb) {
	    	queue.push({ delay: delay, cb: cb });

	    	if (queue.length === 1) {
	      	processQueue();
	    	}
	  	};
	}());
	
	$scope.$watch('vvid', function () {
		if($scope.vvid) {
			$scope.getStatusReport();
		}
	});
	
	function google_api_call(url, index1, lat, lan) {
		$http.get(url).success(function(data) {
    		
    		// Declare the new property address to the existing list
			$scope.mainlist[index1]= data.results[0].formatted_address;
			// var t = vamo_sysservice.geocodeToserver(lat, lan, data.results[0].formatted_address);
    	})
	};
	$scope.getLocation	=	function(lat,lon,ind) {
		console.log(' calling function ')
		var tempurl	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon+"&sensor=true";
		$scope.loading	=	true;
		$http.get(tempurl).success(function(data){	
			$scope.locationname = data.results[0].formatted_address;
			$scope.mainlist[ind]=	data.results[0].formatted_address;
			$scope.loading	=	false;
			// var t = vamo_sysservice.geocodeToserver(lat, lon, data.results[0].formatted_address);
		});
	};
	
	$scope.getStatusReport		=		function() {
		 $scope.url = 'http://'+getIP+context+'/public//getVehicleLocations?group='+$scope.vvid;
	}
	
	$scope.getTodayDate		=		function() {
		var currentDate = new Date();
	    var day = currentDate.getDate();
	    var month = currentDate.getMonth() + 1;
	    var year = currentDate.getFullYear();
	    if(day<10) {
		    day='0'+day;
		} 		
		if(month<10) {
		    month='0'+month;
		} 
	  	return year + "-" + month + "-" + day;		
	}
	
	function getParameterByName(name) {
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	

	
	$scope.genericFunction = function(vehicleno, index){
		sessionStorage.setItem('user', JSON.stringify(vehicleno+','+$scope.vehigroup));
		$("#testLoad").load("../public/menu");
	}
	
	
	
	$scope.groupSelection = function(groupname, groupid){
		$scope.groupId 	= 	groupid;
		$scope.vehigroup = groupname;
		$scope.url     	= 	'http://'+getIP+context+'/public//getVehicleLocations?group='+$scope.vehigroup;
		$('#preloader').show(); 
		$('#preloader02').show();
		if($('#consoldate').attr('id')=='consoldate')
			$scope.consoldate1();
		else if($('#tripTab').attr('id')=='tripTab')
			$scope.consoldateTrip('tripButon');
	}
	
	
	
	
	$scope.exportData = function (data) {
    	//console.log(data);
		var blob = new Blob([document.getElementById(data).innerHTML], {
           	type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };
    
    $scope.exportDataCSV = function (data) {
		//console.log('---->'+data);
		CSV.begin('#'+data).download(data+'.csv').go();
    };
    
    
});

app.directive("getLocation", function () {
  return {
    restrict: "A",
    replace: true,    
    link: function (scope, element, attrs) {
	    angular.element(element).on('click', function(){
	    	var lat = attrs.lat;
	    	var lon = attrs.lon;
	    	var ind	= attrs.index;
	    	scope.getLocation(lat,lon,ind);
		});
    }
  };
});


