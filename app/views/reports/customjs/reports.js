//comment by satheesh ++...
//var total = 0;
//var chart = null;
//http://128.199.175.189/
var getIP	=	globalIP;
var app = angular.module('mapApp',['ui.bootstrap']);
//var gmarkers=[];
//var ginfowindow=[];
app.controller('mainCtrl',function($scope, $http, $timeout, $interval, vamo_sysservice){
	
	$scope.vvid			=	getParameterByName('vid');
	$scope.mainlist		=	[];
	$scope.newAddr      = 	{};
	$scope.url 			= 	'http://'+getIP+'/vamo/public/getVehicleLocations';
	
	$scope.getTodayDate1  =	function(date) {
     	var date = new Date(date);
		return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
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

	

	$scope.url 			  =   'http://'+getIP+'/vamo/public//getVehicleLocations';
	$scope.fromTime       =   '00:00:00';
	$scope.vehigroup;
	$scope.consoldateData =   [];

	 
	$scope.sort = {       
                sortingOrder : 'id',
                reverse : false
            };
    $scope.$watch("url", function (val) {
  		$scope.loading	=	true;
	 	$http.get($scope.url).success(function(data){
			$scope.locations 	= 	data;
			$scope.vehigroup    =   data[0].group;
			$scope.consoldate(data[0].group);
			if(data.length)
				$scope.vehiname		=	data[0].vehicleLocations[0].vehicleId;
			angular.forEach(data, function(value, key) {
				   if(value.totalVehicles) {
				  		$scope.data1		=	data[key];
				  }
			});				
		$scope.loading	=	false;
		$scope.recursive($scope.data1.vehicleLocations,0);
		}).error(function(){ /*alert('error'); */});
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


	$scope.getAddressFromGAPI = function(url, index1, index2, lat, lan) {
		$http.get(url).success(function(data) {
    		
    		// Declare the new property address to the existing list
			$scope.consoldateData[index1].historyConsilated[index2].address = " ";
			$scope.consoldateData[index1].historyConsilated[index2].address = data.results[0].formatted_address;
			var t = vamo_sysservice.geocodeToserver(lat, lan, data.results[0].formatted_address);
    	})
	};

	//address resolving in table td click
	// $("#table_address").on("click", "td", function(){
	// 	console.log(' get the value from the td '+$(this).text());
	// });
	
	// $("#table_address").on("click", "td", function() {
 //     alert($( this ).text());
 //   });
	// $scope.address_click = function(address, ind)
	// {
	// 	$scope.mainlist[ind]='arun';
	// 	console.log(' address '+address.latitude)
	// }
    $scope.consoldate1 =  function()
	{
		$('#preloader').show(); 
		$('#preloader02').show();
		$scope.fromdate1   =  document.getElementById("dateFrom").value;
		$scope.fromTime    =  document.getElementById("timeFrom").value;
		$scope.todate1     =  document.getElementById("dateTo").value;
		$scope.totime      =  document.getElementById("timeTo").value;
		var conUrl1        =  'http://'+getIP+'/vamo/public/getOverallVehicleHistory?group='+$scope.vehigroup+'&fromDate='+$scope.fromdate1+'&fromTime='+$scope.fromTime+'&toDate='+$scope.todate1+'&toTime='+$scope.totime;
		service(conUrl1);
	}

	
	$scope.consoldate =  function(group)
	{
		$scope.fromNowTS1		=	new Date();
		$scope.fromdate1		=	$scope.getTodayDate1($scope.fromNowTS1.setDate($scope.fromNowTS1.getDate()));
		$scope.todate1			=	$scope.getTodayDate1($scope.fromNowTS1.setDate($scope.fromNowTS1.getDate()));
		$scope.totime		    =	format24hrs($scope.fromNowTS1);
		var conUrl              =   'http://'+getIP+'/vamo/public/getOverallVehicleHistory?group='+group+'&fromDate='+$scope.fromdate1+'&fromTime='+$scope.fromTime+'&toDate='+$scope.todate1+'&toTime='+$scope.totime;
		service(conUrl);
	}
		
	// web service call in the consoldate report
	var service = function(conUrl)
	{
		$http.get(conUrl).success(function(data)
		{
			$scope.consoldateData = data;
			$('#preloader').fadeOut(); 
			$('#preloader02').delay(350).fadeOut('slow');

			// To get the address details from Google API
			$scope.selectMe($scope.consoldateData);
		});
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
		console.log(' address --->'+data+'---->'+ind)
		var urlAddress 		=	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+data.latitude+','+data.longitude+"&sensor=true"
		$http.get(urlAddress).success(function(response)
		{
			data.address 	=	response.results[0].formatted_address;
			var t 			= 	vamo_sysservice.geocodeToserver(data.latitude,data.longitude,response.results[0].formatted_address);
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
		// if(location.length<=index){
		// 	return;
		// }else{
			
		// 	var lat		 =	location[index].latitude;
		// 	var lon		 =	location[index].longitude;
		// 	if(!lat || !lon && location[index].address != undefined)
		// 		$scope.recursive(location, ++index);
		// 	var tempurl	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon+"&sensor=true";
		// 	$http.get(tempurl).success(function(data){	
		// 		$scope.locationname		=	data.results[0].formatted_address;
		// 		$scope.mainlist[index]	=	data.results[0].formatted_address;
		// 		var t = vamo_sysservice.geocodeToserver(lat, lon, data.results[0].formatted_address);
		// 		setTimeout(function() {
		// 			$scope.recursive(location, ++index);
		// 		}, 3000);
		// 	});
		// }
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
			var t = vamo_sysservice.geocodeToserver(lat, lan, data.results[0].formatted_address);
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
			var t = vamo_sysservice.geocodeToserver(lat, lon, data.results[0].formatted_address);
		});
	};
	
	$scope.getStatusReport		=		function() {
		 $scope.url = 'http://'+getIP+'/vamo/public//getVehicleLocations?group='+$scope.vvid;
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
	}
	
	
	
	$scope.groupSelection = function(groupname, groupid){
		 $scope.url = 'http://'+getIP+'/vamo/public//getVehicleLocations?group='+groupname;
		
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
