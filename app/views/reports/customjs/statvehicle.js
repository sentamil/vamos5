var getIP	=	globalIP;
var app = angular.module('mapApp',['ui.bootstrap']);
app.controller('mainCtrl',function($scope, $http){
	
	$scope.sort = {       
                sortingOrder : 'id',
                reverse : false
            };
	
	
    $scope.vechID	= 	getParameterByName('vid');
    $scope.url 		= 	'http://'+getIP+':8087/vamosgps/public//getVehicleLocations';
	
	$scope.getTodayDate  =	function(date) {
     	var date = new Date(date);
    	return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
    };
    
	$scope.$watch("url", function (val) {
	 	$http.get($scope.url).success(function(data){
			$scope.locations 	= 	data;
			angular.forEach(data, function(value, key) {			  
				  if(value.totalVehicles) {
				  		$scope.data1		=	data[key];
				  }
			});				
			console.log($scope.data1.group);
			$scope.fromNowTS		=	new Date();
			$scope.toNowTS			=	new Date().getTime() - 86400000;
			$scope.fromdate			=	$scope.getTodayDate($scope.fromNowTS.setDate($scope.fromNowTS.getDate()-6));
			$scope.todate			=	$scope.getTodayDate($scope.toNowTS);
			$scope.plotHist();
		}).error(function(){ /*alert('error'); */});
	});
	
	$scope.$watch($scope.vechID, function() {
   		
   	});
	
	$scope.groupSelection = function(groupname, groupid){
		 $scope.url = 'http://'+getIP+':8087/vamosgps/public//getVehicleLocations?group='+groupname;
	}
	
	$scope.getLocation	=	function(lat,lon) {			
		var tempurl	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon+"&sensor=true";
		console.log(tempurl);
		$http.get(tempurl).success(function(data){	
			//console.log(data);
			$scope.locationname = data.results[0].formatted_address;
		});
	};
	
	$scope.getGeo	=	function(geo) {			
		var tempurl	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+geo+"&sensor=true";
		console.log(tempurl);
		$http.get(tempurl).success(function(data){
			$scope.geoname = data.results[0].formatted_address;
		});
	};
	
	$scope.exportData = function (data) {
    	//console.log(data);
		var blob = new Blob([document.getElementById(data).innerHTML], {
           	type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };
    
    function getParameterByName(name) {
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	
	$scope.plotHist			=		function() {
    	var vurl	=	'http://'+getIP+':8087/vamosgps/public//getExecutiveReport?vehicleId='+$scope.vechID+'&fromDate='+$scope.fromdate+'&toDate='+$scope.todate;
		$http.get(vurl).success(function(vdata){	
			$scope.execVechReportData	=	vdata.execReportData;
		});	
    }
	
	$scope.genericFunction = function(group, vehicleno, index){
		console.log(group, vehicleno, index)
		$scope.vechID	=	vehicleno;
		$scope.plotHist();
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


app.directive("getGeo", function () {
  return {
    restrict: "A",
    replace: true,    
    link: function (scope, element, attrs) {
	    angular.element(element).on('mouseenter', function(){
	    	var geo = attrs.geo;
			scope.getGeo(geo);
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
