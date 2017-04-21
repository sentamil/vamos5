//alert(globalIP);
var getIP	=	globalIP;
//var app = angular.module('hist',['ui.bootstrap']);

app.controller('histCtrl',['$scope', '$http', '$filter', '_global', function($scope, $http, $filter, GLOBAL){
	//$scope.getLocation1(13.0401945,80.2153889);


	// $location.path('http://localhost/vamo/public/track?maps=replay&vehicleId=MSS-TN52-W-9969&gid=MSS-BUS:SMP').replace().reload(false)

	$scope.overallEnable	= true;
	$scope.oaddress	    	=	[];
	$scope.maddress	    	=	[];
	$scope.maddress1    	=	[];
	$scope.saddress	    	=	[];
	$scope.addressIdle  	=   [];
	$scope.addressEvent  	=   [];
	$scope.saddressStop 	=   [];
	$scope.eventReportData 	= 	[];
	$scope.addressLoad 		= 	[];
	$scope.addressFuel 		= 	[];
	//$scope.location	    	=	[];
	$scope.ltrs 			= 	[];
	$scope.fuelDate 	= 	[];
	$scope.tabactive 	=	true;
	$scope.interval	  =	getParameterByName('interval')?getParameterByName('interval'):10;
	$scope.sort = sortByDate('date');
  $scope.timings='all'; 
     
     // $scope.itemsPerPage = 5;
  	 // $scope.currentPage = 0;
     // $scope.items = [];

     
  $scope.filteredTodos = [];
	$scope.itemsPerPage = 10;
	$scope.currentPage = 1;
     
 	/*$scope.gap = 5;
   	$scope.itemsPerPage = 5;
  	$scope.currentPage = 0;*/
  
    $scope.timeChange='All';
    $scope.timeChanges='All';
   // $scope.timeDrops=[{'timeId' :'Above 5 mins' },{'timeId' :'Above 10 mins' },{'timeId' :'Above 15 mins' },{'timeId' :'Above 30 mins' },{'timeId' :'Above 1 hrs' }];

   // console.log($scope.timeChange);

    $scope.downloadid	=	'movementreport';
    var prodId 			= 	getParameterByName('vid');
    var tabId 			= 	getParameterByName('tn');
    $scope.tab_val 		=	getParameterByName('tn');
    $scope.vgroup 		= 	getParameterByName('vg');
   	$scope.dvgroup 		= 	getParameterByName('dvg');
   	$scope.vvid			=	getParameterByName('vvid');
    $scope.repId 		= 	getParameterByName('rid');
    $scope.fd			=	getParameterByName('fd');
    $scope.ft 			= 	getParameterByName('ft');
    $scope.td			=	getParameterByName('td');
    $scope.tt	 		= 	getParameterByName('tt');
  	var ignitionValue 	= 	[];
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
    	var urlEvent 	= GLOBAL.DOMAIN_NAME+"/getActionReport?vehicleId="+prodId+"&fromDate="+$scope.fromdate+"&fromTime="+convert_to_24h($scope.fromtime)+"&toDate="+$scope.todate+"&toTime="+convert_to_24h($scope.totime)+"&interval="+$scope.interval+"&stoppage="+stoppage+"&stopMints="+stopMints+"&idle="+idleEvent+"&idleMints="+idleMints+"&notReachable="+notReachable+"&notReachableMints="+notReachMints+"&overspeed="+overspeedEvent+"&speed="+speedEvent+"&location="+locEvent+"&site="+siteEvent;
    	//console.log(' inside the method '+ urlEvent)

    	$http.get(urlEvent).success(function(eventRes){
    		$scope.eventReportData 		=	eventRes;
   //  		$('#status').fadeOut(); 
			// $('#preloader').delay(350).fadeOut('slow');
			stopLoading();
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
    	var url =GLOBAL.DOMAIN_NAME+"/getSiteReport?vehicleId="+prodId+"&fromDate="+$scope.fromdate+"&fromTime="+convert_to_24h($scope.fromtime)+"&toDate="+$scope.todate+"&toTime="+convert_to_24h($scope.totime)+"&interval="+$scope.interval+"&site=true";
    	// console.log(' asd '+url);
    	$http.get(url).success(function(siteval){
    		$scope.siteReport=[];
    		$scope.siteReport = siteval;
   //  		$('#status').fadeOut(); 
			// $('#preloader').delay(350).fadeOut('slow');
			stopLoading();
    	});
    }

    
    $scope.$watch("tab_val", function (newval, oldval) {
    // $scope.$watch("tabId", function (val) {
			$scope.tabmovement 			=	false;
			$scope.taboverspeed			=	false;
			$scope.tabparked			=	false;
			$scope.tabidle  			=	false;
			$scope.tabevent 			=	false;
			$scope.tabsite  			=	false;
			$scope.tabload  			=	false;
			$scope.tabfuel  			=	false;
			$scope.tabignition 			=	false;
			$scope.tabac 				= 	false;
      $scope.tabStop      =  false;
    	switch(tabId){
    		case 'movement':
    			$scope.tabmovement 			=	true;
    			break;
    		case 'overspeed':
    			$scope.taboverspeed			=	true;
    			break;
    		case 'parked':
    			$scope.tabparked			=	true;
    			break;
    		case 'idle':
    			$scope.tabidle  			=	true;
    			break;
    		case 'event':
    			$scope.tabevent 			=	true;
    			break;
    		case 'site':
    			$scope.tabsite  			=	true;
    			break;
    		case 'load':
    			$scope.tabload  			=	true;
    			break;
    		case 'fuel':
    			$scope.tabfuel  			=	true;
    			break;
    		case 'ignition':
    			$scope.tabignition 			=	true;
    			break;
    		case 'acreport':
    			$scope.tabac 				= 	true;
    			break;
        case 'stopReport':
          $scope.tabStop        =   true;
          break;
    		default:
    			$scope.tabmovement 			=	true; 
    			break;
    	}
    }, true);


   	
   	(function initial(prodId){
   		console.log(' new  '+prodId)
   		startLoading();

   		$scope.id	=	prodId;
   		var histurl	=	GLOBAL.DOMAIN_NAME+"/getVehicleHistory?vehicleId="+getParameterByName('vid')+"&interval="+$scope.interval;
   		$scope.loading	=	true;
   		try{
	   		$http.get(histurl).success(function(data){
	   			if(data.vehicleLocations != null){

					$scope.loading			=	false;
					$scope.hist				=	data;		
          $scope.filterLoc  = data.vehicleLocations;	
					$scope.topspeedtime		=	data.topSpeedTime;
//					$scope.dataArray(data.vehicleLocations);

					//$scope.dataGeofence(data.gfTrip);		
					var fromNow 			  = new Date(data.fromDateTime.replace('IST',''));
					var toNow 				  = new Date(data.toDateTime.replace('IST',''));
					$scope.fromNowTS		=	data.fromDateTimeUTC;
					$scope.toNowTS			=	data.toDateTimeUTC;	
					$scope.fromtime			=	formatAMPM($scope.fromNowTS);
		   		$scope.totime			  =	formatAMPM($scope.toNowTS);
					$scope.fromdate			=	$scope.getTodayDate($scope.fromNowTS);
					$scope.todate			  =	$scope.getTodayDate($scope.toNowTS);
	   			
	   			}
				// $scope.eventCall();
				// $scope.siteCall();
				stopLoading()
			});
		} catch (errr){
			// $('#status').fadeOut(); 
			// $('#preloader').delay(350).fadeOut('slow');	
			console.log(' error '+errr);
			stopLoading()
		}  
   	}(prodId));
   	
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
   			case 'acreport':
   				// $scope.recursiveLoad($scope.loadreport,0);
   				break;
   			case 'fuelreport':
   				$scope.fuelChart($scope.fuelValue);
   				$scope.recursiveFuel($scope.fuelValue,0);
   				break;
   			default:
   				break;
   		}
   	}

   	function _pairFilter(_data, _yes, _no, _status)
   	{
   		var _checkStatus =_no ,_pairList 	= [];
   		angular.forEach(_data, function(value, key){
        
   			if(_pairList.length <= 0){
              if(value[_status] == _yes)
              	_pairList.push(value)
            } else if(_pairList.length >0 )
            {
              if(value[_status] == _checkStatus){
                  _pairList.push(value)
                  if(_pairList[_pairList.length-1][_status] == _yes)
                      _checkStatus = _no;
                  else
                      _checkStatus = _yes
              }
            }

   		});

   		if(_pairList.length>1)
	   		if(_pairList.length%2==0)
	   			return _pairList;
	   		else{
	   			 _pairList.pop();
	   			return _pairList;
	   	}

   	}


   	// function ignitionFilter(ignitionValue)
   	// {

   	// 	$scope.ignitionData 	=_pairFilter(ignitionValue, 'ON', 'OFF', 'ignitionStatus');

   	// }


   	// function acFilter(_acData){

   	// 	$scope.acReport 	=_pairFilter(_acData, 'yes', 'no', 'vehicleBusy');
   	// }


   function loadReportApi(url){
   		//var loadUrl = "http://"+getIP+context+"/public/getLoadReport?vehicleId="+prodId;
   		$http.get(url).success(function(loadresponse){
   			$scope.loadreport 		= 	 loadresponse;
   			console.log(' log '+$scope.tabload)
   		});
   	}

  /* 	function filter(obj){
   		var _returnObj = [];
   		if(obj)
   			angular.forEach(obj,function(val, key){
   				if(val.fuelLitre >0)
   					_returnObj.push(val)
   			})
   		return _returnObj;
   	}
*/

    function filter(obj,name){
      var _returnObj = [];
      if(name=='fuel'){
        angular.forEach(obj,function(val, key){

          if(val.fuelLitre >0)
          {
            _returnObj.push(val)
          }


        })

      }
      else if(name=='stoppage'){

        angular.forEach(obj,function(val, key){

          if(val.stoppageTime >0)
          {
            _returnObj.push(val)
          }
        })
      }
      else if(name=='ovrspd'){

        angular.forEach(obj,function(val, key){

          if(val.overSpeedTime >0)
          {
            _returnObj.push(val)
          }
        })
      }
    return _returnObj;
    }


   	function _globalFilter(data)
   	{	
      $scope.parkeddata       = [];
      $scope.overspeeddata    = [];
      $scope.movementdata     = [];
      $scope.idlereport       = [];
      $scope.temperatureData  = [];
      $scope.fuelValue        = [];
      $scope.ignitionData     = [];
      $scope.acReport         = [];
      $scope.stopReport       = [];

   		try{
        
	   		if(data || data.length > 0)
	   		{
  		    $scope.parkeddata		   =	($filter('filter')(data, {'position':"P"}));
  				//$scope.overspeeddata=	($filter('filter')(data, {'isOverSpeed':"Y"}));
          $scope.overspeeddata   =   filter(data,'ovrspd');
  				$scope.movementdata		 =	($filter('filter')(data, {'position':"M"}));
  				$scope.idlereport      =  ($filter('filter')(data, {'position':"S"}));
  				$scope.temperatureData = 	($filter('filter')(data, {'temperature': "0"}));
  				$scope.fuelValue 		   = 	filter(data,'fuel');
  				ignitionValue		 	     = 	($filter('filter')(data, {'ignitionStatus': "!undefined"}))
          $scope.ignitionData    =  _pairFilter(ignitionValue, 'ON', 'OFF', 'ignitionStatus');
          $scope.acReport        =  _pairFilter(data, 'yes', 'no', 'vehicleBusy');
          $scope.stopReport      =  filter(data,'stoppage');
  				// ignitionFilter(ignitionValue);
  				// acFilter(data)
			}
		} catch (error) {
			stopLoading();
		}
   	}



    $scope.timeFilter_park=function(data,fVal)
    {   
      var filterValues=fVal;
      var ret_obj=[];
     if(data){


          angular.forEach(data,function(val, key){

          if(val.parkedTime >0 && filterValues==100){

            if(val.parkedTime>=3600000){

              ret_obj.push(val);
            }

          }else if(val.parkedTime >0 && filterValues==30){


            if(val.parkedTime>=1800000){

              ret_obj.push(val);
            }

          }else if(val.parkedTime >0 && filterValues==15){

            if(val.parkedTime>=900000){

              ret_obj.push(val);
            }

          }else if(val.parkedTime >0 && filterValues==10){

            if(val.parkedTime>=600000){

              ret_obj.push(val);
            }
          }else if(val.parkedTime >0 && filterValues==5){


            if(val.parkedTime>=300000){

              ret_obj.push(val);
            }
          }else if(val.parkedTime >0 && filterValues==2){


            if(val.parkedTime>=120000){

              ret_obj.push(val);
            }
          }
          else if(val.parkedTime >0 && filterValues==1){


            if(val.parkedTime>=60000){

              ret_obj.push(val);
            }
          }else if(val.parkedTime >0 && filterValues=='All'){

              ret_obj.push(val);
          }
        

        })
      }
   
   return ret_obj;
    }




    $scope.timeFilter_stop=function(data,fVal)
    {   
      var filterValues=fVal;
      var ret_obj=[];
     if(data){


          angular.forEach(data,function(val, key){

          if(val.stoppageTime>0 && filterValues==100){

            if(val.stoppageTime>=3600000){

              ret_obj.push(val);
            }

          }else if(val.stoppageTime >0 && filterValues==30){


            if(val.stoppageTime>=1800000){

              ret_obj.push(val);
            }

          }else if(val.stoppageTime >0 && filterValues==15){

            if(val.stoppageTime>=900000){

              ret_obj.push(val);
            }

          }else if(val.stoppageTime >0 && filterValues==10){

            if(val.stoppageTime>=600000){

              ret_obj.push(val);
            }
          }else if(val.stoppageTime >0 && filterValues==5){

            if(val.stoppageTime>=300000){

              ret_obj.push(val);
            }

          }else if(val.stoppageTime >0 && filterValues==2){

            if(val.stoppageTime>=120000){

              ret_obj.push(val);
            }

          }
          else if(val.stoppageTime >0 && filterValues==1){

            if(val.stoppageTime>=60000){

              ret_obj.push(val);
            }

          }else if(val.stoppageTime >0 && filterValues=='All'){

              ret_obj.push(val);
          }
        

        })
      }
   
   return ret_obj;
    }

 $scope.filtersTime= function(val,name){

  $scope.filterValue=val;

  if(name=='park'){
  $scope.parkeddata=[];

  console.log('park');
  $scope.parkeddata=$scope.timeFilter_park($scope.filterLoc, $scope.filterValue);

  }else if(name=='stop'){
   console.log('stop');
     $scope.stopReport=[];
     $scope.stopReport=$scope.timeFilter_stop($scope.filterLoc, $scope.filterValue);
   }

}


   	//for initial loading
   	$scope.dataArray			=	function(data) {
   		
   		_globalFilter(data);


		if(tabId == 'fuel')
		{
			$scope.fuelChart($scope.fuelValue);
			$scope.recursiveFuel($scope.fuelValue, 0);
		}
		
		// loadReportApi("http://"+getIP+context+"/public/getLoadReport?vehicleId="+prodId);
		$scope.recursive1($scope.movementdata,0);
		//console.log(' data----> '+$scope.downloadid)
   	};

   	// for submit button click
   	$scope.dataArray_click		=	function(data) {
   		
      _globalFilter(data)
   		$scope.alertMe_click($scope.downloadid);
		
   	};


   	

   	$scope.recursive   = function(location_over,index){
   		var indexs = 0;
   		angular.forEach(location_over, function(value, primaryKey){
    		indexs = primaryKey;
    		if(location_over[indexs].address == undefined)
				{
					//console.log(' address over speed'+indexs)
					var latOv		 =	location_over[indexs].latitude;
				 	var lonOv		 =	location_over[indexs].longitude;
					var tempurlOv	 =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latOv+','+lonOv+"&sensor=true";
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
			// var t = vamo_sysservice.geocodeToserver(latOv,lonOv,data.results[0].formatted_address);
		})
	}


	function google_api_call(tempurlMo, index1, latMo, lonMo) {
		$http.get(tempurlMo).success(function(data){
			$scope.maddress1[index1] = data.results[0].formatted_address;
			// var t = vamo_sysservice.geocodeToserver(latMo,lonMo,data.results[0].formatted_address);
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
					var tempurlMo	 =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latMo+','+lonMo+"&sensor=true";
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

	var delayed5 = (function () {
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

	var delayed6 = (function () {
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
		var urlAddress 		=	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+data.latitude+','+data.longitude+"&sensor=true"
		$http.get(urlAddress).success(function(response)
		{
			data.address 	=	response.results[0].formatted_address;
			// var t 			= 	vamo_sysservice.geocodeToserver(data.latitude,data.longitude,response.results[0].formatted_address);
		});
	}


	function google_api_call_stop(tempurlStop, index2, latStop, lonStop) {
		$http.get(tempurlStop).success(function(data){
			$scope.saddressStop[index2] = data.results[0].formatted_address;
			// var t = vamo_sysservice.geocodeToserver(latStop,lonStop,data.results[0].formatted_address);
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
					var tempurlStop	 =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latStop+','+lonStop+"&sensor=true";
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
			// var t = vamo_sysservice.geocodeToserver(latIdle,lonIdle,data.results[0].formatted_address);
		})
	};
	function google_api_call_Event(tempurlEvent, index4, latEvent, lonEvent) {
		$http.get(tempurlEvent).success(function(data){
			$scope.addressEvent[index4] = data.results[0].formatted_address;
			console.log(' address '+$scope.addressEvent[index4])
			// var t = vamo_sysservice.geocodeToserver(latEvent,lonEvent,data.results[0].formatted_address);
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
					var tempurlIdle	 =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latIdle+','+lonIdle+"&sensor=true";
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
				var tempurlEvent =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latEvent+','+lonEvent+"&sensor=true";
				delayed4(2000, function (index4) {
				      return function () {
				        google_api_call_Event(tempurlEvent, index4, latEvent, lonEvent);
				      };
				    }(index4));
			}
		})
	}

	function google_api_call_Load(tempurlLoad, index5, latLoad, lonLoad) {
		$http.get(tempurlLoad).success(function(data){
			$scope.addressLoad[index5] = data.results[0].formatted_address;
			
		})
	};

	$scope.recursiveLoad 	= 	function(locationLoad, indexLoad)
	{
		var index5 = 0;
		angular.forEach(locationLoad, function(value ,primaryKey){
			//console.log(' primaryKey '+primaryKey)
			index5 = primaryKey;
			if(locationLoad[index5].address == undefined)
			{
				var latLoad		 =	locationLoad[index5].latitude;
			 	var lonLoad		 =	locationLoad[index5].longitude;
				var tempurlLoad =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latLoad+','+lonLoad+"&sensor=true";
				delayed5(2000, function (index5) {
				      return function () {
				        google_api_call_Load(tempurlLoad, index5, latLoad, lonLoad);
				      };
				    }(index5));
			}
		})
	}

	function google_api_call_Fuel(tempurlFuel, index6, latFuel, lonFuel) {
		$http.get(tempurlFuel).success(function(data){
			$scope.addressFuel[index6] = data.results[0].formatted_address;
		})
	};

	$scope.recursiveFuel 	= 	function(locationFuel, indexFuel)
	{
		var index6 = 0;
		angular.forEach(locationFuel, function(value ,primaryKey){
			//console.log(' primaryKey '+primaryKey)
			index6 = primaryKey;
			if(locationFuel[index6].address == undefined)
			{
				var latFuel		 =	locationFuel[index6].latitude;
			 	var lonFuel		 =	locationFuel[index6].longitude;
				var tempurlFuel =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latFuel+','+lonFuel+"&sensor=true";
				delayed6(2000, function (index6) {
				      return function () {
				        google_api_call_Fuel(tempurlFuel, index6, latFuel, lonFuel);
				      };
				    }(index6));
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
	

    function sessionValue(vid, gname){
		sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
		$("#testLoad").load("../public/menu");
	}

	$("#testLoad").load("../public/menu");
	// $scope.url = 'http://'+getIP+context+'/public//getVehicleLocations';
	$scope.url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+getParameterByName('vg');

	$scope.$watch("url", function (val) {
	 	$http.get($scope.url).success(function(data){
	 		$scope.locations 	= 	data;
	 		$scope.vehiname	= getParameterByName('vid');
				// $scope.uiGroup 	= $scope.trimColon(getParameterByName('vg'));
				$scope.gName 	= getParameterByName('vg');
				angular.forEach(data, function(val, key){
					if($scope.gName == val.group){
						$scope.gIndex = val.rowId;
						angular.forEach(data[$scope.gIndex].vehicleLocations, function(value, keys){
							if($scope.vehiname == value.vehicleId)
							$scope.shortNam	= value.shortName;
						})
						
					}
						
				})
				
				sessionValue($scope.vehiname, $scope.gName)


			
			// if(data.length){
			// 	$scope.vehiname		=	data[0].vehicleLocations[0].vehicleId;
			// 	$scope.gName 		= 	data[0].group; 
			// 	// sessionValue($scope.vehiname, $scope.gName);
			// 	angular.forEach(data, function(value, key) {
			// 	  	if(value.totalVehicles) {
			// 	  		$scope.data1		=	data[key];
			// 	  	}
			// 	});		
			// }
		}).error(function(){ /*alert('error'); */ });
	});
	
	$scope.genericFunction = function(vehid, index){
		sessionValue(vehid, $scope.gName);
	}

	$scope.groupSelection = function(groupname, groupid){
		startLoading()
		var urlGroup = GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+groupname;
		$http.get(urlGroup).success(function(data){
			$scope.vehiname		=	data[groupid].vehicleLocations[0].vehicleId;
			$scope.gName 		= 	data[groupid].group; 
			$scope.locations 	= 	data;
			sessionValue($scope.vehiname, $scope.gName);
			stopLoading()
		});
		
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
	   			//$scope.recursiveLoad($scope.loadreport,0);
	   			break;
	   		case 'fuelreport':
	   			$scope.downloadid    =  'fuelreport';
	   			$scope.overallEnable = 	true;
	   			$scope.fuelChart($scope.fuelValue);
	   			$scope.recursiveFuel($scope.fuelValue, 0);
	   			break;
	   		case 'ignitionreport':
	   			$scope.downloadid 	=	'ignitionreport';
	   			break;
	   		case 'acreport':
	   			$scope.downloadid 	=	'acreport';
	   			break;
        case 'stopReport':
          $scope.downloadid   = 'stopReport';
          break;

			default:
				break;
		}
	};
	
	
	$scope.fuelChart 	= 	function(data){
		var ltrs 		=	[];
		var fuelDate 	=	[];
		try{
			if(data.length)
				for (var i = 0; i < data.length; i++) {
					if(data[i].fuelLitre !='0' || data[i].fuelLitre !='0.0')
					{
						ltrs.push(data[i].fuelLitre);
						var dar = $filter('date')(data[i].date, "dd/MM/yyyy HH:mm:ss");
						fuelDate.push(dar)
					}
				};
		}
		catch (err){
			console.log(err.message)
		}
		
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
	    hours = Math.floor((ms)/(60*60*1000));
	    hoursms=ms % (60*60*1000);
	    minutes = Math.floor((hoursms)/(60*1000));
	    minutesms=ms % (60*1000);
	    sec = Math.floor((minutesms)/(1000));
      // return days+"d : "+hours+"h : "+minutes+"m : "+sec+"s";
	    return hours+":"+minutes+":"+sec;
    }
    //submit button click function
    $scope.buttonClick;
  $scope.plotHist			=	function() {
    if((checkXssProtection($scope.fromdate) == true) && (checkXssProtection($scope.todate) == true) && (checkXssProtection($scope.fromtime) == true) && (checkXssProtection($scope.totime) == true))
  	{
      startLoading();
      var valueas     =   $('#txtv').val();
      var histurl     = GLOBAL.DOMAIN_NAME+"/getVehicleHistory?vehicleId="+prodId+"&interval="+$scope.interval+'&fromDateUTC='+utcFormat($scope.fromdate,convert_to_24h($scope.fromtime))+'&toDateUTC='+utcFormat($scope.todate,convert_to_24h($scope.totime));
      // var histurl      = "http://"+getIP+context+"/public//getVehicleHistory?vehicleId="+prodId+"&fromDate="+$scope.fromdate+"&fromTime="+convert_to_24h($scope.fromtime)+"&toDate="+$scope.todate+"&toTime="+convert_to_24h($scope.totime)+"&interval="+$scope.interval+'&fromDateUTC='+utcFormat($scope.fromdate,convert_to_24h($scope.fromtime))+'&toDateUTC='+utcFormat($scope.todate,convert_to_24h($scope.totime));
      //var loadUrl     =   "http://"+getIP+context+"/public//getLoadReport?vehicleId="+prodId+"&fromDate="+$scope.fromdate+"&fromTime="+convert_to_24h($scope.fromtime)+"&toDate="+$scope.todate+"&toTime="+convert_to_24h($scope.totime);
    try{
      $http.get(histurl).success(function(data){
        
        //$scope.loading      = false;
        $scope.hist       = data;
        $scope.filterLoc  = data.vehicleLocations;
        $scope.topspeedtime   = data.topSpeedTime;
        // loadReportApi(loadUrl);
        $scope.dataArray_click(data.vehicleLocations);
        // $('#status').fadeOut(); 
        // $('#preloader').delay(350).fadeOut('slow');
        stopLoading();  
      });
    }
    catch (err){
      console.log(' err '+err);
      // $('#status').fadeOut(); 
      //  $('#preloader').delay(350).fadeOut('slow');
      stopLoading();  
    }
    }
    


  }
     
   
     //pdf method
     $scope.pdfHist			=		function() {  	
		var histurl	=	GLOBAL.DOMAIN_NAME+"/getVehicleHistory?vehicleId="+$scope.vvid+"&fromDate="+$scope.fd+"&fromTime="+convert_to_24h($scope.ft)+"&toDate="+$scope.td+"&toTime="+convert_to_24h($scope.tt)+"&interval="+$scope.interval;			
		//console.log(histurl);		

		$http.get(histurl).success(function(data){
			$scope.hist				=	data;
      $scope.filterLoc  = data.vehicleLocations;
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
	
}]);
// app.factory('vamo_sysservice', function($http, $q){
// 	return {
// 		geocodeToserver: function (lat, lng, address) {
// 		  try { 
// 				var reversegeourl = GLOBAL.DOMAIN_NAME+'/store?geoLocation='+lat+','+lng+'&geoAddress='+address;
// 			    return this.getDataCall(reversegeourl);
// 			}
// 			catch(err){ console.log(err); }
		  
// 		},
//         getDataCall: function(url){
//         	var defdata = $q.defer();
//         	$http.get(url).success(function(data){
//             	 defdata.resolve(data);
// 			}).error(function() {
//                     defdata.reject("Failed to get data");
//             });
// 			return defdata.promise;
//         }
//     }
// });


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


