//alert(globalIP);
var getIP	=	globalIP;
var app = angular.module('hist',['ui.bootstrap']);

app.controller('histCtrl',function($scope, $http, $filter, vamo_sysservice){
	//$scope.getLocation1(13.0401945,80.2153889);
	
	$scope.overallEnable	= true;
	$scope.oaddress	    	=	[];
	$scope.maddress	    	=	[];
	$scope.maddress1    	=	[];
	$scope.saddress	    	=	[];
	$scope.addressIdle  	=   [];
	$scope.addressEvent  	=   [];
	$scope.saddressStop 	=   [];
	$scope.eventReportData 	= 	[];
	$scope.location	    	=	[];
	$scope.ltrs 			= 	[];
	$scope.fuelDate 		= 	[];
	
	$scope.interval	    	=	getParameterByName('interval')?getParameterByName('interval'):10;
	$scope.sort = {       
                sortingOrder : 'id',
                reverse : false
            };
     
     // $scope.itemsPerPage = 5;
  	 // $scope.currentPage = 0;
     // $scope.items = [];

     
     $scope.filteredTodos = [];
	 $scope.itemsPerPage = 10;
	 $scope.currentPage = 1;
     
 	/*$scope.gap = 5;
   	$scope.itemsPerPage = 5;
  	$scope.currentPage = 0;*/


    $scope.downloadid	=	'movementreport';
    var prodId 			= 	getParameterByName('vid');
   	$scope.vgroup 		= 	getParameterByName('vg');
   	$scope.dvgroup 		= 	getParameterByName('dvg');
   	$scope.vvid			=	getParameterByName('vvid');
    $scope.repId 		= 	getParameterByName('rid');
    $scope.fd			=	getParameterByName('fd');
    $scope.ft 			= 	getParameterByName('ft');
    $scope.td			=	getParameterByName('td');
    $scope.tt	 		= 	getParameterByName('tt');
  	
    $scope.todayhistory	=	[];
    
    $scope.getTodayDate  =	function(date) {
     	var date = new Date(date);
    	return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
    };
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


	function eventButton(eventdate)
	{
		$scope.buttonClick = eventdate;
		serviceCallEvent();
	}

	function serviceCallEvent()
    {
    	var stoppage 		= 	document.getElementById ("stop").checked
    	var idleEvent 		= 	document.getElementById ("idle").checked;
    	var notReachable 	= 	document.getElementById ("notreach").checked;
    	var overspeedEvent 	= 	document.getElementById ("overspeed").checked;
    	var stopMints 		= 	document.getElementById ("stop1").value;
    	var idleMints 		= 	document.getElementById ("idle1").value;
    	var notReachMints 	= 	document.getElementById ("notreach1").value;
    	var speedEvent 		= 	document.getElementById ("overspeed1").value
    	var locEvent 		=   document.getElementById ("location").checked;
    	var siteEvent 		= 	document.getElementById ("site").checked;
    	var urlEvent 	= "http://"+getIP+context+"/public//getActionReport?vehicleId="+prodId+"&fromDate="+$scope.fromdate+"&fromTime="+convert_to_24h($scope.fromtime)+"&toDate="+$scope.todate+"&toTime="+convert_to_24h($scope.totime)+"&interval="+$scope.interval+"&stoppage="+stoppage+"&stopMints="+stopMints+"&idle="+idleEvent+"&idleMints="+idleMints+"&notReachable="+notReachable+"&notReachableMints="+notReachMints+"&overspeed="+overspeedEvent+"&speed="+speedEvent+"&location="+locEvent+"&site="+siteEvent;
    	//console.log(' inside the method '+ urlEvent)

    	$http.get(urlEvent).success(function(eventRes){
    		$scope.eventReportData 		=	eventRes;
    		$('#status').fadeOut(); 
			$('#preloader').delay(350).fadeOut('slow');
    		if($scope.buttonClick==true)
    		{
    			$scope.alertMe_click($scope.downloadid);
				
    		}

    	})
    	
    }

	//for individual method for event report service call
	
	$scope.eventCall 		= 		function()
    {
    	document.getElementById ("stop").checked 				= true;
    	document.getElementById ("idle").checked 				= true;
    	document.getElementById ("notreach").checked 			= true;
    	document.getElementById ("overspeed").checked			= true;
    	document.getElementById ("stop1").defaultValue 			= 10;
    	document.getElementById ("idle1").defaultValue 			= 10;
    	document.getElementById ("notreach1").defaultValue 		= 10;
    	document.getElementById ("overspeed1").defaultValue		= 60;
    	document.getElementById ("location").checked 			= false;
    	document.getElementById ("site").checked 	 			= false;
    	//buttonClick = false;
    	serviceCallEvent();
    	// return $scope.eventReportData;
    }

    //site web service
    $scope.siteCall       =     function()
    {
    	var url ="http://"+getIP+context+"/public/getSiteReport?vehicleId="+prodId+"&fromDate="+$scope.fromdate+"&fromTime="+convert_to_24h($scope.fromtime)+"&toDate="+$scope.todate+"&toTime="+convert_to_24h($scope.totime)+"&interval="+$scope.interval+"&site=true";
    	// console.log(' asd '+url);
    	$http.get(url).success(function(siteval){
    		$scope.siteReport=[];
    		$scope.siteReport = siteval;
    		$('#status').fadeOut(); 
			$('#preloader').delay(350).fadeOut('slow');
    	});
    }
    $scope.$watch($scope.repId, function() {
    		$scope.id							=	$scope.vvid
		   switch($scope.repId) {
		   		case 'overspeedreport':
		   			$scope.overspeedreport		=	true;
		   			$scope.tableTitle			=	'Overspeed Report';
		   			$scope.downloadid	 		=	'overspeedreport';
		   			break;
		   		case 'movementreport':
		   			$scope.movementreport		=	true;
		   			$scope.tableTitle			=	'Movement Report';
		   			$scope.downloadid	 		=	'movementreport';
		   			break;
		   		case 'stoppedparkingreport':
		   			$scope.stoppedparkingreport	=	true;
		   			$scope.tableTitle			=	'Stopped Parking Report';
		   			$scope.downloadid	 		=	'stoppedparkingreport';
		   			break;
		   		case 'geofencereport':
		   			$scope.geofencereport		=	true;
		   			$scope.tableTitle			=	'Geo Fence Report';
		   			break;
		   		case 'idlereport':
		   			$scope.idlereport			= 	true;
		   			$scope.tableTitle			=	'Idle Report';
		   			$scope.downloadid           =   'idlereport';
		   			break;
		   		case 'eventReport':
		   			$scope.idlereport			= 	true;
		   			$scope.tableTitle			=	'Event Report';
		   			$scope.downloadid           =   'eventReport';
		   			break;
		   		case 'sitereport':
		   			$scope.idlereport			= 	true;
		   			$scope.tableTitle			=	'Site Report';
		   			$scope.downloadid           =   'sitereport';
		   			break;
		   		case 'loadreport':
		   			$scope.loadreport			= 	true;
		   			$scope.tableTitle 			=	'Load Report';
		   			$scope.downloadid 			= 	'loadreport';
		   			break;
		   		case 'fuel':
		   			$scope.loadreport			= 	true;
		   			$scope.tableTitle 			=	'Fuel Report';
		   			$scope.downloadid 			= 	'fuel';
		   			break;
		   		default:
		   			break;		
		   }
		   $scope.pdfHist();
   });
  
   	$scope.$watch(prodId, function() {
   		$scope.id	=	prodId;
   		var histurl	=	"http://"+getIP+context+"/public/getVehicleHistory?vehicleId="+prodId+"&interval="+$scope.interval;
   		$scope.loading	=	true;
		$http.get(histurl).success(function(data){
			$scope.loading			=	false;
			$scope.hist				=	data;			
			$scope.topspeedtime		=	data.topSpeedTime;
			$scope.dataArray(data.vehicleLocations);

			//$scope.dataGeofence(data.gfTrip);		
			var fromNow 			= 	new Date(data.fromDateTime.replace('IST',''));
			var toNow 				= 	new Date(data.toDateTime.replace('IST',''));
			$scope.fromNowTS		=	fromNow.getTime();
			$scope.toNowTS			=	toNow.getTime();	
			$scope.fromtime			=	formatAMPM($scope.fromNowTS);
   			$scope.totime			=	formatAMPM($scope.toNowTS);
			$scope.fromdate			=	$scope.getTodayDate($scope.fromNowTS);
			$scope.todate			=	$scope.getTodayDate($scope.toNowTS);
			$scope.eventCall();
			$scope.siteCall();
		});   
   	});
   	
   	//for initial loading
   	$scope.dataArray			=	function(data) {
   		$scope.parkeddata		=	($filter('filter')(data, {'position':"P"}));
		$scope.overspeeddata	=	($filter('filter')(data, {'isOverSpeed':"Y"}));
		$scope.movementdata		=	($filter('filter')(data, {'position':"M"}));
		$scope.idlereport       =   ($filter('filter')(data, {'position':"S"}));
		$scope.loadreport 		= 	($filter('filter')(data, {'loadTruck': "!undefined"}));
		$scope.fuelValue 		= 	($filter('filter')(data, {'fuelLitre': "!undefined"}));
		$scope.recursive1($scope.movementdata,0);
		//console.log(' data----> '+$scope.downloadid)
   	};

   	// for submit button click
   	$scope.dataArray_click		=	function(data) {
   		$scope.parkeddata		=	($filter('filter')(data, {'position':"P"}));
		$scope.overspeeddata	=	($filter('filter')(data, {'isOverSpeed':"Y"}));
		$scope.movementdata		=	($filter('filter')(data, {'position':"M"}));
		$scope.idlereport       =   ($filter('filter')(data, {'position':"S"}))
		$scope.loadreport 		= 	($filter('filter')(data, {'loadTruck': "!undefined"}))
		$scope.fuelValue=[];
		if(data)
		$scope.fuelValue 		= 	($filter('filter')(data, {'fuelLitre': "!undefined"}));
		$scope.alertMe_click($scope.downloadid);
   	};


   	$scope.alertMe_click		=	function(value){
   		switch(value){
   			case 'movementreport':
   				$scope.recursive1($scope.movementdata,0);
   				break;
   			case 'overspeedreport':
   				$scope.recursive($scope.overspeeddata,0);
   				break;
   			case 'stoppedparkingreport':
   				$scope.recursiveStop($scope.parkeddata,0);
   				break;
   			case 'idlereport':
   				$scope.recursiveIdle($scope.idlereport,0);
   				break;
   			case 'eventReport':
   				$scope.recursiveEvent($scope.eventReportData,0);
   				break;
   			case 'fuel':
   				$scope.fuelChart($scope.fuelValue);
   				break;
   			default:
   				break;
   		}
   	}

   	$scope.recursive   = function(location_over,index){
   		var indexs = 0;
   		angular.forEach(location_over, function(value, primaryKey){
    		indexs = primaryKey;
    		if(location_over[indexs].address == undefined)
				{
					//console.log(' address over speed'+indexs)
					var latOv		 =	location_over[indexs].latitude;
				 	var lonOv		 =	location_over[indexs].longitude;
					var tempurlOv	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+latOv+','+lonOv+"&sensor=true";
					//console.log(' in overspeed '+indexs)
					delayed(3000, function (indexs) {
					      return function () {
					        google_api_call_Over(tempurlOv, indexs, latOv, lonOv);
					      };
					    }(indexs));
				}
    	})

	}

	

	function google_api_call_Over(tempurlOv, indexs, latOv, lonOv){
		$http.get(tempurlOv).success(function(data){
			$scope.oaddress[indexs] = data.results[0].formatted_address;
			var t = vamo_sysservice.geocodeToserver(latOv,lonOv,data.results[0].formatted_address);
		})
	}


	function google_api_call(tempurlMo, index1, latMo, lonMo) {
		$http.get(tempurlMo).success(function(data){
			$scope.maddress1[index1] = data.results[0].formatted_address;
			var t = vamo_sysservice.geocodeToserver(latMo,lonMo,data.results[0].formatted_address);
		})
	};
	$scope.recursive1   =   function(locations, indes)
	{
		var index1  =  0;
		angular.forEach(locations, function(value, primaryKey){
    		index1 = primaryKey;
    		if(locations[index1].address == undefined)
				{
					//console.log(' address movementreport'+index1)
					var latMo		 =	locations[index1].latitude;
				 	var lonMo		 =	locations[index1].longitude;
					var tempurlMo	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+latMo+','+lonMo+"&sensor=true";
					//console.log('  movement report  '+index1)
					delayed1(3000, function (index1) {
					      return function () {
					        google_api_call(tempurlMo, index1, latMo, lonMo);
					      };
					    }(index1));
				}
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
	var delayed2 = (function () {
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
	var delayed3 = (function () {
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
	var delayed4 = (function () {
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

	$scope.address_click = function(data, ind)
	{
		var urlAddress 		=	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+data.latitude+','+data.longitude+"&sensor=true"
		$http.get(urlAddress).success(function(response)
		{
			data.address 	=	response.results[0].formatted_address;
			var t 			= 	vamo_sysservice.geocodeToserver(data.latitude,data.longitude,response.results[0].formatted_address);
		});
	}


	function google_api_call_stop(tempurlStop, index2, latStop, lonStop) {
		$http.get(tempurlStop).success(function(data){
			$scope.saddressStop[index2] = data.results[0].formatted_address;
			var t = vamo_sysservice.geocodeToserver(latStop,lonStop,data.results[0].formatted_address);
		})
	};

	$scope.recursiveStop   = function(locationStop,indexStop){
   		var index2 = 0;
   		angular.forEach(locationStop, function(value, primaryKey){
    		index2 = primaryKey;
    		if(locationStop[index2].address == undefined)
				{
					//console.log(' address stop'+index2)
					var latStop		 =	locationStop[index2].latitude;
				 	var lonStop		 =	locationStop[index2].longitude;
					var tempurlStop	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+latStop+','+lonStop+"&sensor=true";
					//console.log('  stopped or parked '+index2)
					delayed2(3000, function (index2) {
					      return function () {
					        google_api_call_stop(tempurlStop, index2, latStop, lonStop);
					      };
					    }(index2));
				}
    	})
	}
	function google_api_call_Idle(tempurlIdle, index3, latIdle, lonIdle) {
		$http.get(tempurlIdle).success(function(data){
			$scope.addressIdle[index3] = data.results[0].formatted_address;
			var t = vamo_sysservice.geocodeToserver(latIdle,lonIdle,data.results[0].formatted_address);
		})
	};
	function google_api_call_Event(tempurlEvent, index4, latEvent, lonEvent) {
		$http.get(tempurlEvent).success(function(data){
			$scope.addressEvent[index4] = data.results[0].formatted_address;
			console.log(' address '+$scope.addressEvent[index4])
			var t = vamo_sysservice.geocodeToserver(latEvent,lonEvent,data.results[0].formatted_address);
		})
	};
	$scope.recursiveIdle   = function(locationIdle,indexIdle){
		var index3 = 0;
		angular.forEach(locationIdle, function(value, primaryKey){
    		index3 = primaryKey;
    		if(locationIdle[index3].address == undefined)
				{
					//console.log(' address idle'+index3)
					var latIdle		 =	locationIdle[index3].latitude;
				 	var lonIdle		 =	locationIdle[index3].longitude;
					var tempurlIdle	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+latIdle+','+lonIdle+"&sensor=true";
					delayed3(3000, function (index3) {
					      return function () {
					        google_api_call_Idle(tempurlIdle, index3, latIdle, lonIdle);
					      };
					    }(index3));
				}
    	})
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
				var tempurlEvent =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+latEvent+','+lonEvent+"&sensor=true";
				delayed4(2000, function (index4) {
				      return function () {
				        google_api_call_Event(tempurlEvent, index4, latEvent, lonEvent);
				      };
				    }(index4));
			}
		})
	}
	$scope.getParkedCorrectHours	=	function(data) {
			return $scope.msToTime(data);
   	}
	
	function getParameterByName(name) {
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
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
	
	$scope.url = 'http://'+getIP+context+'/public//getVehicleLocations';	
	$scope.$watch("url", function (val) {
	 	$http.get($scope.url).success(function(data){
			$scope.locations 	= 	data;
			if(data.length)
				$scope.vehiname		=	data[0].vehicleLocations[0].vehicleId;
				angular.forEach(data, function(value, key) {
				  	if(value.totalVehicles) {
				  		$scope.data1		=	data[key];
				  	}
				});				
			}).error(function(){ /*alert('error'); */ });
		});
	
	$scope.groupSelection = function(groupname, groupid){
		$scope.url = 'http://'+getIP+context+'/public//getVehicleLocations?group='+groupname;
		
	};
	
	$scope.getLocation	=	function(lat,lon,ind) {	
		//alert(ind);
		switch($scope.downloadid) {
			case 'overspeedreport':
				$scope.recursive($scope.overspeeddata,ind);
				break;
			case 'movementreport':
				$scope.recursive($scope.movementdata,ind);
				break;
			case 'stoppedparkingreport':
				$scope.recursive($scope.parkeddata,ind);
				break;
			default:
				break;
		}
	};
	
	
	
	$scope.alertMe		=	function(data) {	
		console.log(data);
		switch(data) {
			case 'Overspeed':
				$scope.downloadid	 =	'overspeedreport';
				$scope.overallEnable =	true;
				$scope.recursive($scope.overspeeddata,0);
				break;
			case 'Movement':
				$scope.downloadid	 =	'movementreport';
				$scope.overallEnable =	true;
				//clearTimeout(promis);
				$scope.recursive1($scope.movementdata,0);
				break;
			case 'Stopped/Parked':
				$scope.downloadid	 =	'stoppedparkingreport';
				$scope.overallEnable =	true;
				$scope.recursiveStop($scope.parkeddata,0);
				break;
			case 'Geo Fence':
				$scope.downloadid	 =	'geofencereport';
				$scope.overallEnable =	false;
				break;
			case 'idlereport':
				$scope.downloadid    =  'idlereport';
				$scope.overallEnable =  true;
				$scope.recursiveIdle($scope.idlereport,0);
				break;
			case 'eventReport':
	   			$scope.downloadid    =  'eventReport';
	   			$scope.overallEnable = 	true;
	   			$scope.recursiveEvent($scope.eventReportData,0);
	   			break;
	   		case 'sitereport':
	   			$scope.overallEnable = 	true;
	   			$scope.downloadid    =  'sitereport';
		   		break;
		   	case 'loadreport':
		   		$scope.downloadid    =  'loadreport';
	   			$scope.overallEnable = 	true;
	   			break;
	   		case 'fuel':
	   			$scope.downloadid    =  'fuel';
	   			$scope.overallEnable = 	true;
	   			$scope.fuelChart($scope.fuelValue);
	   			break;
			default:
				break;
		}
	};
	

	$scope.fuelChart 	= 	function(data){
		var ltrs 		=	[];
		var fuelDate 	=	[];
		for (var i = 0; i < data.length; i++) {
			if(data[i].fuelLitre !='0' || data[i].fuelLitre !='0.0')
			{
				ltrs.push(data[i].fuelLitre);
				var dar = $filter('date')(data[i].date, "dd/MM/yyyy HH:mm:ss");
				fuelDate.push(dar)
			}
			
		};
		 $(function () {
   
        $('#container').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Fuel Report'
            },
          
             xAxis: {
            categories: fuelDate
        		},
            
            yAxis: {
                title: {
                    text: 'Fuel'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },

            series: [{
                type: 'area',
                name: 'Fuel Level',
                data: ltrs
            }]
        });

});
	}

	$scope.exportData = function (data) {
		console.log(data);
		var blob = new Blob([document.getElementById(data).innerHTML], {
           	type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };
    
    $scope.exportDataCSV = function (data) {
		console.log(data);
		CSV.begin('#'+data).download(data+'.csv').go();
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
    //submit button click function
    $scope.buttonClick;
    $scope.plotHist			=	function() {
    	$scope.siteCall();
    	$scope.loading		=	true;
    	$('#status').show();
    	$('#preloader').show();
    	var valueas 		=   $('#txtv').val();
		
		// console.log(histurl)
		if($scope.downloadid == 'eventReport')
		{

			// var eventUrl 	= "http://"+getIP+"/vamo/public//getActionReport?vehicleId="+prodId+"&fromDate="+$scope.fromdate+"&fromTime="+convert_to_24h($scope.fromtime)+"&toDate="+$scope.todate+"&toTime="+convert_to_24h($scope.totime)+"&interval="+$scope.interval+"&stoppage="+$scope.stoppage+"&stopMints="+$scope.stopMints+"&idle="+$scope.idleEvent+"&idleMints="+$scope.idleMints+"&notReachable="+$scope.notReachable+"&notReachableMints="+$scope.notReachMints+"&overspeed="+$scope.overspeedEvent+"&speed="+$scope.speedEvent+"&location="+$scope.locationEvent+"&site="+$scope.siteEvent;
			// console.log(' inside the if '+eventUrl)
			$scope.buttonClick 	= true;
			$scope.eventReportData=[];
			$scope.loading	=	false;
			eventButton($scope.buttonClick);
			
			//console.log(' value '+$scope.eventReportData.length)
			
		}
		else if($scope.downloadid == 'sitereport')
		{
			 $scope.siteCall();
			 $scope.loading	=	false;
		} 
		else
		{
			var histurl			=	"http://"+getIP+context+"/public//getVehicleHistory?vehicleId="+prodId+"&fromDate="+$scope.fromdate+"&fromTime="+convert_to_24h($scope.fromtime)+"&toDate="+$scope.todate+"&toTime="+convert_to_24h($scope.totime)+"&interval="+$scope.interval;
			$http.get(histurl).success(function(data){
				
				$scope.loading			=	false;
				$scope.hist				=	data;
				$scope.topspeedtime		=	data.topSpeedTime;
				$scope.dataArray_click(data.vehicleLocations);
				$('#status').fadeOut(); 
				$('#preloader').delay(350).fadeOut('slow');	
			});
			// $scope.loading	=	false;
		}
		console.log(' true or false  '+$scope.buttonClick)
     }
     
   
     //pdf method
     $scope.pdfHist			=		function() {  	
		var histurl	=	"http://"+getIP+context+"/public//getVehicleHistory?vehicleId="+$scope.vvid+"&fromDate="+$scope.fd+"&fromTime="+convert_to_24h($scope.ft)+"&toDate="+$scope.td+"&toTime="+convert_to_24h($scope.tt)+"&interval="+$scope.interval;			
		//console.log(histurl);		

		$http.get(histurl).success(function(data){
			$scope.hist				=	data;
			$scope.dataArray(data.vehicleLocations);
			switch($scope.repId) {
			case 'movementreport':
				$scope.recursive1($scope.movementdata,0);
				break;
			case 'stoppedparkingreport':
				$scope.recursiveStop($scope.parkeddata,0);
				break;
			case 'idlereport':
				$scope.recursiveIdle($scope.idlereport,0);
				break; 
			default:
				break;
			}			
		});
		
		
     }
     
     
 	
 	
 	function dateStringFormat(d) {
 		var s 		= 		d.split(' ');
 		var t 		= 		s[0].split('-');
		var ds 		= 		(t[2].concat('-'+t[1]).concat('-'+t[0])).concat(' '+s[1]);
		return new Date(ds).getTime();
 	}
 	

	 $scope.figureOutTodosToDisplay = function() {
	    var begin = (($scope.currentPage - 1) * $scope.itemsPerPage);
	    var end = begin + $scope.itemsPerPage;
	    $scope.filteredTodos = $scope.movementdata.slice(begin, end);
		};
	  
	  	
		$scope.pageChanged = function() {
	    $scope.figureOutTodosToDisplay();
	  };
	  
  $(window).load(function() {
		$('#status').fadeOut(); 
		$('#preloader').delay(350).fadeOut('slow');
		$('body').delay(350).css({'overflow':'visible'});
});

});
app.factory('vamo_sysservice', function($http, $q){
	return {
		geocodeToserver: function (lat, lng, address) {
		  try { 
				var reversegeourl = 'http://'+globalIP+'/vamo/public/store?geoLocation='+lat+','+lng+'&geoAddress='+address;
			    return this.getDataCall(reversegeourl);
			}
			catch(err){ console.log(err); }
		  
		},
        getDataCall: function(url){
        	var defdata = $q.defer();
        	$http.get(url).success(function(data){
            	 defdata.resolve(data);
			}).error(function() {
                    defdata.reject("Failed to get data");
            });
			return defdata.promise;
        }
    }
});
app.directive("customSort", function() {
return {
    restrict: 'A',
    transclude: true,    
    scope: {
      order: '=',
      sort: '='
    },
    template : 
      ' <a ng-click="sort_by(order)" style="color: #555555;">'+
      '    <span ng-transclude></span>'+
      '    <i ng-class="selectedCls(order)"></i>'+
      '</a>',
    link: function(scope) {
                
    // change sorting order
    scope.sort_by = function(newSortingOrder) {       
        var sort = scope.sort;
        
        if (sort.sortingOrder == newSortingOrder){
            sort.reverse = !sort.reverse;
        }                    

        sort.sortingOrder = newSortingOrder;        
    };
    
   
    scope.selectedCls = function(column) {
        if(column == scope.sort.sortingOrder){
            return ('icon-chevron-' + ((scope.sort.reverse) ? 'down' : 'up'));
        }
        else{            
            return'icon-sort' 
        } 
    };      
  }// end link
}
});

app.directive("getLocation", function () {
  return {
    restrict: "A",
    replace: true,    
    link: function (scope, element, attrs) {
	    angular.element(element).on('click', function(){    //mouseenter
	    	var lat = attrs.lat;
	    	var lon = attrs.lon;
	    	var ind	= attrs.index;
	    	console.log(ind);
			scope.getLocation(lat,lon,ind);
		});
    }
  };
});


