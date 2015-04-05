var getIP	=	globalIP;
var app = angular.module('mapApp',['ui.bootstrap']);
app.controller('mainCtrl',function($scope, $http, $filter){
	//$scope.tabActive = true;
	
	$scope.maddress	=	[];
	$scope.sort = {       
        sortingOrder : 'id',
        reverse : false
    };
	
    $scope.url 				= 	'http://'+getIP+'/vamo/public/getVehicleLocations';
    $scope.sid 				= 	getParameterByName('sid');
    
    $scope.rid 				= 	getParameterByName('rid');
    $scope.vvid 			= 	getParameterByName('vid');
    
    $scope.fd 				= 	getParameterByName('fd');
    $scope.ft 				= 	getParameterByName('ft');
    $scope.td 				= 	getParameterByName('td');
    $scope.tt	 			= 	getParameterByName('tt');
   
    
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
	
	function getParameterByName(name) {
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	
	
	$scope.$watch('sid', function() {
		 //alert(1);
   		 if($scope.rid=='executive') {
		 	$scope.loading			=	true;
   		 	var gurl				=	'http://'+getIP+'/vamo/public/getExecutiveReport?groupId='+$scope.sid+'&fromDate='+$scope.fd+'&toDate='+$scope.td;
			$http.get(gurl).success(function(gdata){
				$scope.loading			=	false;
				$scope.execGroupReportData	=	gdata.execReportData;
				if($scope.vvid)
					$scope.execGroupReportData	=	($filter('filter')($scope.execGroupReportData, {'vehicleId':$scope.vvid}));
				$scope.recursive($scope.execGroupReportData,0);	
			});
   		 }
   		 else { 
   		 	if($scope.vvid) {
	   		 	var gurl		=	'http://'+getIP+'/vamo/public/getGeoFenceReport?vehicleId='+$scope.vvid+'&fromDate='+$scope.fd+'&fromTime='+convert_to_24h($scope.ft)+'&toDate='+$scope.td+'&toTime='+convert_to_24h($scope.tt);
				$scope.vid		=	$scope.vvid;
				$scope.loading	=	true;
				$http.get(gurl).success(function(gdata){				
					$scope.dataGeofence(gdata.gfTrip);	
					//console.log(gdata.gfTrip);
					$scope.loading		=	false;
				});
			}
   		 }   		 
   	});
   	
   	$scope.recursive   = function(location,index,$timeout){
   		//console.log(location);
		if(location.length<=index){
			return;
		}else{
			var geo		 =	location[index].topSpeedGeoLocation;
			if(!geo)
				$scope.recursive(location, ++index);
			var tempurl	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+geo+"&sensor=true";
			$http.get(tempurl).success(function(data){	
				$scope.locationname		=	data.results[0].formatted_address;
				$scope.maddress[index]	=	data.results[0].formatted_address;
				setTimeout(function() {
				      $scope.recursive(location, ++index);
				}, 2000);					
			});
		}
	}
   	
	$scope.$watch("url", function (val) {
		$scope.loading	=	true;
	 	$http.get($scope.url).success(function(data){
			$scope.locations 	= 	data;
			angular.forEach(data, function(value, key) {			  
				  if(value.totalVehicles) {
				  		$scope.data1		=	data[key];
				  }
			});				
			//console.log($scope.data1.group);
			$scope.loading			=	false;	
			$scope.fromNowTS		=	new Date();
			$scope.toNowTS			=	new Date().getTime() - 86400000;
			$scope.fromdate			=	$scope.getTodayDate($scope.fromNowTS.setDate($scope.fromNowTS.getDate()-7));
			$scope.todate			=	$scope.getTodayDate($scope.toNowTS);
			$scope.fromtime			=	formatAMPM($scope.fromNowTS);
   			$scope.totime			=	formatAMPM($scope.toNowTS);
			$scope.plotHist();
		}).error(function(){ /*alert('error'); */});
	});
	
	
	$scope.groupSelection = function(groupname, groupid){
		 //$scope.tabActive = true;
		 $scope.donut		=	false;
		 $scope.bar			=	true;
		 $scope.url = 'http://'+getIP+'/vamo/public/getVehicleLocations?group='+groupname;
	}
	
	$scope.getLocation	=	function(lat,lon) {	
		var tempurl	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon+"&sensor=true";
		//console.log(tempurl);
		$scope.loading		=	true;
		$http.get(tempurl).success(function(data){
			$scope.loading			=	false;
			$scope.locationname = data.results[0].formatted_address;
		});
	};
	
	$scope.getGeo	=	function(geo,ind) {			
		var tempurl	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+geo+"&sensor=true";
		//console.log(tempurl);
		$scope.loading			=	true;
		$http.get(tempurl).success(function(data){
			$scope.loading			=	false;
			$scope.geoname = data.results[0].formatted_address;
			$scope.maddress[ind] = data.results[0].formatted_address;
		});
	};
	
	$scope.plotHist			=		function() {
		if($scope.whichdata) {
			
			var gurl		=	'http://'+getIP+'/vamo/public/getExecutiveReport?groupId='+$scope.data1.group+'&fromDate='+$scope.fromdate+'&toDate='+$scope.todate;
			//console.log(gurl);
			$scope.loading			=	true;
			$http.get(gurl).success(function(gdata){	
				$scope.execGroupReportData	=	gdata.execReportData; 
				if($scope.vid)
					$scope.execGroupReportData	=	($filter('filter')($scope.execGroupReportData, {'vehicleId':$scope.vid}));
				$scope.texecGroupReportData		=	$scope.execGroupReportData;				
				$scope.donutLoad(gdata);
				$scope.barLoad($scope.vid);
				$scope.loading			=	false;
				$scope.recursive($scope.execGroupReportData,0);	
			});
		}
		else {			
			var gurl		=	'http://'+getIP+'/vamo/public/getGeoFenceReport?vehicleId='+$scope.vid+'&fromDate='+$scope.fromdate+'&fromTime='+convert_to_24h($scope.fromtime)+'&toDate='+$scope.todate+'&toTime='+convert_to_24h($scope.totime);
			//console.log(gurl);			
			if(!$scope.sid) {
				$scope.loading			=	true; 
				$http.get(gurl).success(function(gdata){
				//console.log(gdata);	
					$scope.dataGeofence(gdata.gfTrip);	
					$scope.loading			=	false;
				});
			}
			
		}	
    };
    
    $scope.donutLoad		=		function(data) {
    	$scope.barArray		=		[];
    	angular.forEach(JSON.parse(data.distanceCoveredAnalytics), function(value, key) {
   			//console.log(value, key);
   			$scope.barArray.push([key, value]);
		}); 
    	
    	
   		c3.generate({
   			bindto: '#chart1',
		    data: {
		        columns: [
		            ['Consolidate RunningTime', data.consolidateRunningTime],
		            ['Consolidated IdleTime', data.consolidatedIdleTime],
		            ['Consolidated NoDataTime', data.consolidatedNoDataTime],
		            ['Consolidated ParkedTime', data.consolidatedParkedTime],
		        ],
		        type : 'donut',
		    },
		    
		    donut: {
		        title: "Time"
		    }
		});
		
		c3.generate({
   			bindto: '#chart2',
		    data: {
		        columns: $scope.barArray,
		        type : 'donut',
		    },
		    donut: {
		        title: "Distance Covered Analytics"
		    }
		});
   	};
   	
   	$scope.barLoad	=		function(vehicleId) {
   		//console.log(vehicleId);
   		$scope.barArray1	=	[];
   		$scope.barArray2	=	[];
   		$scope.barArray1.push(["X", "Date vs Distance"]);
   		$scope.barArray2.push(["X", "Date vs Overspeed"]);
   		$scope.data			=	($filter('filter')($scope.execGroupReportData, {'vehicleId':vehicleId}));
   		angular.forEach($scope.data, function(value, key) {
   			$scope.barArray1.push([value.date, value.distanceToday]);
   			$scope.barArray2.push([value.date, value.topSpeed]);
		}); 
		//console.log($scope.barArray1);
   		c3.generate({
   			bindto: '#chart3',
		    data: {
		    	x: 'X',
		        columns: $scope.barArray1,
		        type: 'bar'
		    },
		   	 axis: {
		        x: {
		        	type : 'category',
		            label:{		            	
		            	text : 'Date',
		            	position: 'outer-right'	
		            }
		        },
		        y: {
		            label:{
		            	text : 'Distance Today',
		            	position: 'outer-middle'	
		            }
		        }
		    }
		});
		
		c3.generate({
   			bindto: '#chart4',
		    data: {
		    	x: 'X',
		        columns: $scope.barArray2,
		        type: 'bar'
		    },
		   	 axis: {
		        x: {
		        	type : 'category',
		            label:{		            	
		            	text : 'Date',
		            	position: 'outer-right'	
		            }
		        },
		        y: {
		            label:{
		            	text : 'Topspeed',
		            	position: 'outer-middle'	
		            }
		        }
		    }
		});				
   	};
	
	$scope.exportData = function (data) {
    	//console.log(data);
		var blob = new Blob([document.getElementById(data).innerHTML], {
           	type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };
    
    $scope.exportDataCSV = function (data) {
		//console.log(data);
		CSV.begin('#'+data).download(data+'.csv').go();
    };
    
    $scope.genericFunction = function(vehicleno, index){ 	
    	//$scope.tabActive	= 	false;
    	//$scope.test			= 	true;
    	$scope.execGroupReportData			=	($filter('filter')($scope.texecGroupReportData, {'vehicleId':vehicleno}));
    	$scope.vid							=	vehicleno;
    	$scope.vehiclenull					=	vehicleno;
    	
    	
    	if($scope.downloadid=='executive'){
    		$scope.donut					=	true;
    		$scope.bar						=	false;  	
    	}
    			
		var gurl			=	'http://'+getIP+'/vamo/public/getGeoFenceReport?vehicleId='+vehicleno+'&fromDate='+$scope.fromdate+'&fromTime='+convert_to_24h($scope.fromtime)+'&toDate='+$scope.todate+'&toTime='+convert_to_24h($scope.totime);
		//console.log(gurl);
		$scope.loading			=	true;
		$http.get(gurl).success(function(gdata){	
			//console.log(gdata);
			$scope.barLoad(vehicleno);
			$scope.dataGeofence(gdata.gfTrip);
			$scope.loading			=	false;
		});
	}
	
	$scope.dataGeofence 		= 		function(data) {
		$scope.geofencedata		=   	data;
	}
	
	$scope.alertMe		=	function(data) {	
		//console.log(data); 
		switch(data) {
			case 'executive':			
				$scope.donut		=		false;
				$scope.bar			=		true;
				/*if($scope.vid!=null) {
					$scope.donutLoad($scope.texecGroupReportData);
				}*/
				$scope.timeslot		=		true;
				$scope.whichdata	=		true;
				$scope.downloadid	=		'executive';
				break;
			case 'geofence':
				//console.log('test');
				if($scope.vid==null) {
					$scope.vehiclenull	=	null;
				}
				$scope.timeslot		=		false;
				$scope.whichdata	=		false;
				$scope.donut		=		true;
				$scope.bar			=		true;
				$scope.downloadid	=		'geofencereport';	
				break;			
			default:
				break;
		}
		//console.log($scope.downloadid);
	};
	
	function convert_to_24h(time_str) {
 		
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
	

});

app.directive("getLocation", function () {
  return {
    restrict: "A",
    replace: true,    
    link: function (scope, element, attrs) {
	    angular.element(element).on('click', function(){
	    	var lat = attrs.lat;
	    	var lon = attrs.lon;
	    	var ind = attrs.index;
			scope.getLocation(lat,lon,ind);
		});
    }
  };
});


app.directive("getGeo", function () {
  return {
    restrict: "A",
    replace: true,    
    link: function (scope, element, attrs) {
	    angular.element(element).on('click', function(){
	    	var geo = attrs.geo;
	    	var ind = attrs.index;
			scope.getGeo(geo,ind);
		});
    }
  };
});


/* sorting */
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
