//alert(globalIP);
var getIP	=	globalIP;
var app = angular.module('hist',['ui.bootstrap']);

/*app.filter('offset', function () {
    return function (input, start) {
        start = parseInt(start, 10);
        return input.slice(start);
    };
});*/

app.controller('histCtrl',function($scope, $http, $filter){
	//$scope.getLocation1(13.0401945,80.2153889);
	$scope.location	=	[];
	$scope.sort = {       
                sortingOrder : 'id',
                reverse : false
            };
     
     $scope.filteredTodos = [];
	 $scope.itemsPerPage = 5;
	 $scope.currentPage = 1;
     
 	/*$scope.gap = 5;
   	$scope.itemsPerPage = 5;
  	$scope.currentPage = 0;*/


    $scope.downloadid	=	'overspeedreport';
    var prodId 			= 	getParameterByName('vid');
    $scope.repId 		= 	getParameterByName('rid');
  
  	
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
		   switch($scope.repId) {
		   		case 'overspeedreport':
		   			$scope.overspeedreport		=	true;
		   			$scope.tableTitle			=	'Overspeed Report';
		   			break;
		   		case 'movementreport':
		   			$scope.movementreport		=	true;
		   			$scope.tableTitle			=	'Movement Report';
		   			break;
		   		case 'stoppedparkingreport':
		   			$scope.stoppedparkingreport	=	true;
		   			$scope.tableTitle			=	'Stopped Parking Report';
		   			break;
		   		case 'geofencereport':
		   			$scope.geofencereport		=	true;
		   			$scope.tableTitle			=	'Geo Fence Report';
		   			break;
		   		default:
		   			break;		
		   }
   });
  
   	$scope.$watch(prodId, function() {
   		$scope.id	=	prodId;
   		var histurl	=	"http://"+getIP+"/vamo/public/getVehicleHistory?vehicleId="+prodId;
		$http.get(histurl).success(function(data){
			$scope.hist				=	data;
			$scope.dataArray(data.vehicleLocations);	
			$scope.dataGeofence(data.gfTrip);		
			var fromNow 			= 	new Date(data.fromDateTime.replace('IST',''));
			var toNow 				= 	new Date(data.toDateTime.replace('IST',''));
			$scope.fromNowTS		=	fromNow.getTime();
			$scope.toNowTS			=	toNow.getTime();	
			$scope.fromtime			=	formatAMPM($scope.fromNowTS);
   			$scope.totime			=	formatAMPM($scope.toNowTS);
			$scope.fromdate			=	$scope.getTodayDate($scope.fromNowTS);
			$scope.todate			=	$scope.getTodayDate($scope.toNowTS);
			//$scope.plotHist();	
			//$scope.getLocation();
		});    
   	});
   	
   	$scope.dataArray			=		function(data) {
   		$scope.parkeddata		=	($filter('filter')(data, {'position':"P"}));
		$scope.overspeeddata	=	($filter('filter')(data, {'isOverSpeed':"Y"}));
		$scope.movementdata		=	($filter('filter')(data, {'position':"M"}));
   	};
	
	$scope.dataGeofence 		= 		function(data) {
		$scope.geofencedata		=   data;	
		 $scope.figureOutTodosToDisplay();
		// console.log($scope.geofencedata);
		
	}
	
	function getParameterByName(name) {
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	$scope.url = 'http://'+getIP+'/vamo/public/getVehicleLocations';	
	$scope.$watch("url", function (val) {
	 	$http.get($scope.url).success(function(data){
			$scope.locations 	= 	data;
			angular.forEach(data, function(value, key) {
				  console.log(value.totalVehicles, key);				  
				  if(value.totalVehicles) {
				  		$scope.data1		=	data[key];
				  }
			});				
			console.log($scope.data1);
		}).error(function(){ /*alert('error'); */ });
	});
	
	$scope.groupSelection = function(groupname, groupid){
		$scope.url = 'http://'+getIP+'/vamo/public/getVehicleLocations?group='+groupname;
		
	};
	
	$scope.getLocation	=	function(lat,lon) {	
		//console.log(lat,lon);
		//$scope.testdemo	= "hiii";
		//var tempurl = "http://"+getIP+"/vamo/public/reverseGeoLocation?lat="+lat+"&lng="+lon;
		
		var tempurl	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon+"&sensor=true";
		console.log(tempurl);
		$http.get(tempurl).success(function(data){	
			//console.log(data);
			$scope.locationname = data.results[0].formatted_address;
		});
	};
	
	$scope.alertMe		=	function(data) {	
		//console.log(data);
		switch(data) {
			case 'Overspeed':
				$scope.downloadid	=	'overspeedreport';
				
				break;
			case 'Movement':
				$scope.downloadid	=	'movementreport';
				
				break;
			case 'Stopped/Parked':
				$scope.downloadid	=	'stoppedparkingreport';
				
				break;
			case 'Geo Fence':
				$scope.downloadid	=	'geofencereport';
				
				break;
			default:
				break;
		}
		//console.log($scope.downloadid);
	};
	
	$scope.exportData = function (data) {
		console.log(data);
		var blob = new Blob([document.getElementById(data).innerHTML], {
           	type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };
    
    $scope.msToTime		=	function(duration) {
        var milliseconds = parseInt((duration%1000)/100)
            , seconds = parseInt((duration/1000)%60)
            , minutes = parseInt((duration/(1000*60))%60)
            , hours = parseInt((duration/(1000*60*60))%24);

        hours = (hours < 10) ? "0" + hours : hours;
        minutes = (minutes < 10) ? "0" + minutes : minutes;
        seconds = (seconds < 10) ? "0" + seconds : seconds;

        return hours + "." + minutes + "Hrs";
    }
    
    $scope.plotHist			=		function() {
    	$scope.temp			=		[];
    	$scope.tempgeo		=		[];
    	$scope.fromrange 	=		new Date($scope.fromdate.concat(' '+$scope.fromtime)).getTime();
    	$scope.torange 		=		new Date($scope.todate.concat(' '+$scope.totime)).getTime();
    	console.log($scope.fromrange, $scope.torange)
 		angular.forEach($scope.hist.vehicleLocations, function(value, key) {
 			$scope.betweentime	=	dateStringFormat(value.lastSeen);
 			if($scope.torange >= $scope.betweentime && $scope.fromrange <= $scope.betweentime)	{
 				$scope.temp.push($scope.hist.vehicleLocations[key]);
 				
 			}
 			// console.log($scope.betweentime);	
		});	
		$scope.dataArray($scope.temp);
		angular.forEach($scope.hist.gfTrip, function(value, key) {
 			$scope.geotime	= new Date(value.inTime).getTime();
 			if($scope.torange >= $scope.geotime && $scope.fromrange <= $scope.geotime)	{
 				$scope.tempgeo.push($scope.hist.gfTrip[key]);
 				
 			}
 			
		});	
		$scope.dataGeofence($scope.tempgeo);
		console.log($scope.tempgeo);	
     }
     
     
 	
 	
 	function dateStringFormat(d) {
 		 var s 		= 		d.split(' ');
 		 var t 		= 		s[0].split('-');
		 var ds 	= 		(t[2].concat('-'+t[1]).concat('-'+t[0])).concat(' '+s[1]);
		 return new Date(ds).getTime();
 	}
 	// /* next prev */
 	
	 	$scope.figureOutTodosToDisplay = function() {
	    var begin = (($scope.currentPage - 1) * $scope.itemsPerPage);
	    var end = begin + $scope.itemsPerPage;
	    $scope.filteredTodos = $scope.geofencedata.slice(begin, end);
	    console.log($scope.currentPage);
	  };
 	
	  	$scope.pageChanged = function() {
	    $scope.figureOutTodosToDisplay();
	    
	  };
 	 
    /*  $scope.range = function () {
            var rangeSize = 5;
            var ret = [];
            var start;
 
            start = $scope.currentPage;
            if (start > $scope.pageCount() - rangeSize) {
                start = $scope.pageCount() - rangeSize + 1;
            }
 
            for (var i = start; i < start + rangeSize; i++) {
                ret.push(i);
            }
            return ret;
        };
 
        $scope.prevPage = function () {
            if ($scope.currentPage > 0) {
                $scope.currentPage--;
            }
        };
 
        $scope.pageCount = function () {
            return Math.ceil($scope.overspeeddata.length / $scope.itemsPerPage) - 1;
        };
 
        $scope.nextPage = function () {
            if ($scope.currentPage < $scope.pageCount()) {
                $scope.currentPage++;
            }
        };
 
        $scope.setPage = function (n) {
            $scope.currentPage = n;
        };*/

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
	    angular.element(element).on('mouseenter', function(){
	    	var lat = attrs.lat;
	    	var lon = attrs.lon;
			scope.getLocation(lat,lon);
		});
    }
  };
});
