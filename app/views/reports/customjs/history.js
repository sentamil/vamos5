//alert(globalIP);
var getIP	=	globalIP;
var app = angular.module('hist',['ui.bootstrap']);

app.controller('histCtrl',function($scope, $http, $filter, vamo_sysservice){
	//$scope.getLocation1(13.0401945,80.2153889);
	
	$scope.overallEnable= true;
	$scope.oaddress	    =	[];
	$scope.maddress	    =	[];
	$scope.maddress1    =	[];
	$scope.saddress	    =	[];
	$scope.addressIdle  =   [];
	$scope.saddressStop =   [];
	$scope.location	    =	[];
	$scope.interval	    =	getParameterByName('interval')?getParameterByName('interval'):1;
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
		   		default:
		   			break;		
		   }
		   $scope.pdfHist();
   });
  
   	$scope.$watch(prodId, function() {
   		$scope.id	=	prodId;
   		var histurl	=	"http://"+getIP+"/vamo/public/getVehicleHistory?vehicleId="+prodId+"&interval="+$scope.interval;
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
			
		});   
   	});
   	
   	$scope.dataArray			=		function(data) {
   		$scope.parkeddata		=	($filter('filter')(data, {'position':"P"}));
		$scope.overspeeddata	=	($filter('filter')(data, {'isOverSpeed':"Y"}));
		$scope.movementdata		=	($filter('filter')(data, {'position':"M"}));
		$scope.idlereport       =   ($filter('filter')(data, {'position':"S"}))
		$scope.recursive1($scope.movementdata,0);
		//console.log(' data----> '+$scope.downloadid)
   	};

   	$scope.dataArray_click			=		function(data) {
   		$scope.parkeddata		=	($filter('filter')(data, {'position':"P"}));
		$scope.overspeeddata	=	($filter('filter')(data, {'isOverSpeed':"Y"}));
		$scope.movementdata		=	($filter('filter')(data, {'position':"M"}));
		$scope.idlereport       =   ($filter('filter')(data, {'position':"S"}))
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

  //  		if(location.length<=index){
		// 	return;
		// }else{
		// 	var lat		 =	location[index].latitude;
		// 	var lon		 =	location[index].longitude;
		// 	if(!lat || !lon)
		// 		$scope.recursive(location, ++index);
		// 	var tempurl	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon+"&sensor=true";
		// 	$http.get(tempurl).success(function(data){
		// 		//console.log(data.status);
		// 		$scope.locationname = data.results[0].formatted_address;
		// 		if($scope.downloadid=='overspeedreport')
		// 			$scope.oaddress[index]	= data.results[0].formatted_address;
		// 		else if($scope.downloadid=='movementreport')
		// 			$scope.maddress[index]	= data.results[0].formatted_address;
		// 		else if($scope.downloadid=='stoppedparkingreport')
		// 			$scope.saddress[index]	= data.results[0].formatted_address;

		// 		// address to backend
		// 		var t = vamo_sysservice.geocodeToserver(lat,lon,data.results[0].formatted_address);

		// 		setTimeout(function() {
		// 		      $scope.recursive(location, ++index);
		// 		}, 4000);
		// 	}).error(function(){ /*alert('error'); */});
		// }
	}

	// $(document).on('click', '#tableId td', function(events){
	// 	var td_value	=	$(this);
	// 	var content		=	td_value.text().trim();
	// 	console.log(' value   '+content+'  events   '+events)
	// })

	// this method for click and resolve the address
	// $(document).on('click','.add_td',function(events){


	// 	var id = $(this).closest("tr").find('td:eq(3)').text();
	// 	console.log(' id '+$('td:eq(3)').text().trim());
	// 	//$("#element td:nth-child(2)").empty();
	// 	//$(".add_td").(this).empty();
	// })

	$(document).on('click','.add_td',function(){
		var id 				= 	$(this).closest("tr").find('td:eq(2)').text();
		var split_comma 	=	id.split(',');
		var row_index 		= 	$(this).index();
		var col_index 		= 	$(this).parent().index();
		console.log(' index '+col_index)
		//console.log('lat----->'+split_comma[0]+'lat----->'+split_comma[1])
		var url 			= 	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+split_comma[0]+','+split_comma[1]+"&sensor=true";

		var address 		=	'';
		//console.log(address);
		//var colIndex = $('.lat_lan').text();
    	//var rowIndex = $(this).parent().parent().children().index($(this).parent());
    	//var value = $(this).find(".add_td").eq(2).html()
    	//console.log(id);
    	$http.get(url).success(function(value){
			//console.log(url)
			address 		=	value.results[0].formatted_address;
			//var va =$('#tabl tr:nth-child('+row_index+') .add_td:nth-child('+col_index+')').html(address);
			//alert(' alert '+$('#tabl tr:nth-child('+row_index+') .add_td:nth-child('+col_index+')').Append(address));
			
			$(document).ready(function(){
			    $('#tabl tr:nth-child('+row_index+') .add_td:nth-child('+col_index+')').html('foo');
			});
			//console.log(' list '+$('#tabl tr:nth-child('+row_index+') .add_td:nth-child('+col_index+')').html(address))
			//var $cell = $(this);
			//$("#tabl").children()[row_index].children[col_index].innerHTML = "H!";
			//$('#tabl').find('tr#' + row_index).find('add_td:eq('+col_index+')').html(address);
			//return address_Click;
			// var arr = $('#tabl > tbody > tr').map(function ()
			// {
			//     return $(this).children().map(function ()
			//     {
			//         return $(this);
			//     });
			// });
			// arr[3][3].text('address');

		})
	});
	// var address_Click 	=	''; 
	// function click_resolve(url)
	// {	
		
		
		
	// }

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

	$scope.address_click = function(data, ind)
	{
		console.log(' address --->'+data+'---->'+ind)
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
					//console.log(' Idle report  '+index3)
					delayed3(3000, function (index3) {
					      return function () {
					        google_api_call_Idle(tempurlIdle, index3, latIdle, lonIdle);
					      };
					    }(index3));
				}
    	})
	}

	/*$scope.dataGeofence 		= 		function(data) {
		$scope.geofencedata		=   	data;		
		 // console.log($scope.geofencedata);	
	}*/
	
	$scope.getParkedCorrectHours	=	function(data) {
		//angular.forEach($scope.parkeddata, function(value, key) {
   			return $scope.msToTime(data);
   		//});
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

    	// Convert a string like 10:05:23 PM to 24h format, returns like [22,5,23]
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
	
	$scope.url = 'http://'+getIP+'/vamo/public//getVehicleLocations';	
	$scope.$watch("url", function (val) {
	 	$http.get($scope.url).success(function(data){
			$scope.locations 	= 	data;
			//console.log($scope.locations);
			if(data.length)
				$scope.vehiname		=	data[0].vehicleLocations[0].vehicleId;
			angular.forEach(data, function(value, key) {
				  // console.log(value.totalVehicles, key);				  
				  if(value.totalVehicles) {
				  		$scope.data1		=	data[key];
				  }
			});				
			//console.log($scope.data1);
		}).error(function(){ /*alert('error'); */ });
	});
	
	$scope.groupSelection = function(groupname, groupid){
		$scope.url = 'http://'+getIP+'/vamo/public//getVehicleLocations?group='+groupname;
		
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
			default:
				break;
		}
	};
	
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
    
    $scope.plotHist			=		function() {
    	$scope.loading	=	true;
		var histurl	=	"http://"+getIP+"/vamo/public//getVehicleHistory?vehicleId="+prodId+"&fromDate="+$scope.fromdate+"&fromTime="+convert_to_24h($scope.fromtime)+"&toDate="+$scope.todate+"&toTime="+convert_to_24h($scope.totime)+"&interval="+$scope.interval;
		$http.get(histurl).success(function(data){
			$scope.loading			=	false;
			$scope.hist				=	data;
			$scope.topspeedtime		=	data.topSpeedTime;
			$scope.dataArray_click(data.vehicleLocations);	
		}); 
     }
     
     //pdf method
     $scope.pdfHist			=		function() {  	
		var histurl	=	"http://"+getIP+"/vamo/public//getVehicleHistory?vehicleId="+$scope.vvid+"&fromDate="+$scope.fd+"&fromTime="+convert_to_24h($scope.ft)+"&toDate="+$scope.td+"&toTime="+convert_to_24h($scope.tt)+"&interval="+$scope.interval;			
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
		var ds 	= 		(t[2].concat('-'+t[1]).concat('-'+t[0])).concat(' '+s[1]);
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


