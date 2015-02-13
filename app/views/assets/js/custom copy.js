var app = angular.module('mapApp',[]);
var gmarkers=[];
var ginfowindow=[];
app.controller('mainCtrl',function($scope, $http){
	$scope.locations = [];
	$scope.val = 5;	
	$scope.gIndex = 0;
	$scope.alertstrack = 0;
   // $scope.url = 'getVehicleLocation.php?userId=demouser1&group=personal';
   $scope.url =  'http://128.199.175.189:8080/laravel/public/getVehicleLocations?userId=demouser1&group=personal';
	$scope.historyfor='';
	$http.get($scope.url).success(function(data){
		$scope.locations = data;
	}).error(function(){ alert('error'); });
	$scope.map =  null;
	$scope.map2 = null;
	$scope.livetrack =  null;
	$scope.distanceCovered= 0;
	$scope.onHistoryClick = function(vehicleno){
		$scope.currentTab = 'history';
		$scope.historyfor = 'history for - ' + vehicleno;
	}
	$scope.genericFunction = function(vehicleno){	
		if($scope.currentTab=='map'){
			$scope.removeTask(vehicleno);		
		}else if($scope.currentTab=='history'){
			$scope.onHistoryClick(vehicleno);
		}else{
		}
	}
	$scope.tabs = [{ title: 'Live Tracking', tabSelected: 'map'
        }, {
            title: 'History',
            tabSelected: 'history'
        }, {
            title: 'Alerts',
            tabSelected: 'alert'
    }];
	$scope.currentTab = 'map';
	
	$scope.isActiveTab = function(tabUrl) {
		return tabUrl == $scope.currentTab;
    }
	
	$scope.onClickTab = function (tab) {
		var center = $scope.map2.getCenter();
        google.maps.event.trigger($scope.map2, "resize");
        $scope.map2.setCenter(center);
		
        $scope.currentTab = tab.tabSelected;
    }
	
	$scope.groupSelection = function(groupname, groupid){
		$scope.alertstrack=0;
		$scope.distanceCovered =0;
		alert($scope.gIndex);
		$scope.url = 'http://128.199.175.189:8080/laravel/public/getVehicleLocations?userId=demouser1&group'+groupname;
		$scope.gIndex = groupid;
		gmarkers=[];
		ginfowindow=[];
	}
	
	setInterval(function(){
		$scope.$watch("url", function (val) {
			 	$http.get($scope.url).success(function(data){
					$scope.locations = data;
				}).error(function(){ alert('error'); });
		});
	},5000);
		
	$scope.addMarker= function(pos){
	   var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);   
	   if(pos.data.alert!=''){
			$scope.alertstrack = parseInt($scope.alertstrack) + 1;
			var animation = google.maps.Animation.BOUNCE;
			var pinImage = 'imgs/marker-l-red.png';
		}else{
			var animation = google.maps.Animation.DROP;
			var pinImage = 'imgs/marker-l-mer.png';
			
		}
	   $scope.marker = new google.maps.Marker({
			position: myLatlng, 
			map: $scope.map,
			icon: pinImage,
            animation: animation
		});
		 gmarkers.push($scope.marker);
	}
	
	$scope.removeTask=function(vehicleno){
		var temp = $scope.locations[$scope.gIndex].vehicleLocations;
		for(var i=0; i<temp.length;i++){
			  ginfowindow[i].close();
			  gmarkers[i].setAnimation(null);
		}
		for(var i=0; i<temp.length;i++){
			if(temp[i].vehicleId==vehicleno){
				
				google.maps.event.trigger(gmarkers[i], "click");
				 	if (gmarkers[i].getAnimation() != null) {
						gmarkers[i].setAnimation(null);
		  			} else {
						gmarkers[i].setAnimation(google.maps.Animation.BOUNCE);
						setInterval(function() {
						 gmarkers[i].setAnimation(null);
						}, 5000);
		  			}
				break;	
			}
		}
	};
	var char;
	$scope.infoBox = function(map, marker, data){
       var contentString = '<div id="content"><h3>'+data.vehicleId+'</h3><div class="row"><div class="col-lg-3"><h6>Speed</h6><h4>'+ data.speed +'</h4><h6>Status</h6><h4>'+ data.status +'</h4><h6>Mobile</h6><h4>'+ data.mobileNumber +'</h4><a href="#"><img src="imgs/historyIco.png" width="30" height="25"/></a></div><div class="col-lg-6" align="left"><div class="container01" style="min-width: 250px; max-width: 300px; height: 200px; margin: 0 auto"></div></div><div class="col-lg-3"><h6>Distance</h6><h4>'+ data.distanceCovered +'</h4><h6>Name</h6><h4>'+ data.operatorName +'</h4><a href="#"><img src="imgs/alertsIco.png"/></a></div></div></div>';
	   
		var infoWindow = new google.maps.InfoWindow({content: contentString});
		 var  str1 = data.speed.replace ( /[^\d.]/g, '' ); 
			var total = parseInt(str1);
			
		 google.maps.event.addListener(infoWindow, "domready", function(e){
		 char =	$('.container01').highcharts({
				chart: {
					type: 'gauge',
					plotBackgroundColor: null,
					plotBackgroundImage: null,
					plotBorderWidth: 0,
					plotShadow: false
				},
				title: {
					text: ''
				},
				pane: {
				startAngle: -150,
				endAngle: 150,
				background: [{
				backgroundColor: {
				linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
				stops: [
				[0, '#FFF'],
				[1, '#333']
				]
				},
				borderWidth: 0,
				outerRadius: '109%'
				}, {
				backgroundColor: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
					stops: [
					[0, '#333'],
					[1, '#FFF']
					]
				},
				borderWidth: 1,
				outerRadius: '107%'
				}, {
				// default background
				}, {
				backgroundColor: '#DDD',
				borderWidth: 0,
				outerRadius: '105%',
				innerRadius: '103%'
				}]
				},
				// the value axis
				yAxis: {
				min: 0,
				max: 200,
				minorTickInterval: 'auto',
				minorTickWidth: 1,
				minorTickLength: 10,
				minorTickPosition: 'inside',
				minorTickColor: '#666',
				tickPixelInterval: 30,
				tickWidth: 2,
				tickPosition: 'inside',
				tickLength: 10,
				tickColor: '#666',
				labels: {
				step: 2,
				rotation: 'auto'
				},
				title: {
				text: 'km/h'
				},
				plotBands: [{
				from: 0,
				to: 120,
				color: '#55BF3B' // green
				}, {
				from: 120,
				to: 160,
				color: '#DDDF0D' // yellow
				}, {
				from: 160,
				to: 200,
				color: '#DF5353' // red
				}]
				},
				series: [{
				name: 'Speed',
				data: [total],
				tooltip: {
				valueSuffix: ' km/h'
				}
				}]
				},
				// Add some life
				function (chart) {
					
				});
		 });
		   
		ginfowindow.push(infoWindow);
        google.maps.event.addListener(marker, "click", function(e){
            infoWindow.open(map, marker);	
        });
        (function(marker, data, contentString) {
          google.maps.event.addListener(marker, "click", function(e) {
            infoWindow.open(map, marker);
          });	
        })(marker, data);
		
	};
});

app.directive('map', function($http) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {
			
			scope.$watch("url", function (val) {
				
				scope.locations = $http.get(scope.url).success(function(data){
				var locs = data;
				var zoomLevel = parseInt(locs[scope.gIndex].zoomLevel);
				
				var lat = locs[scope.gIndex].latitude;
				var long = locs[scope.gIndex].longitude;
				var myOptions = { zoom: zoomLevel, center: new google.maps.LatLng(lat, long), mapTypeId: google.maps.MapTypeId.ROADMAP};
				scope.map = new google.maps.Map(document.getElementById(attrs.id), myOptions);
				var length = locs[scope.gIndex].vehicleLocations.length;
		  		var datam  = locs[scope.gIndex].vehicleLocations;
				scope.livetrack = length;
				for (var i = 0; i < length; i++) {
					var lat = locs[scope.gIndex].vehicleLocations[i].latitude;
					var lng =  locs[scope.gIndex].vehicleLocations[i].longitude;
					scope.addMarker({ lat: lat, lng: lng , data: locs[scope.gIndex].vehicleLocations[i]});
					scope.infoBox(scope.map, gmarkers[i], locs[scope.gIndex].vehicleLocations[i]);
					scope.distanceCovered =	scope.distanceCovered+ parseInt(locs[scope.gIndex].vehicleLocations[i].distanceCovered);
					
				}
			}).error(function(){ alert('error'); });
				
			});
			
        }
    };
});

app.directive('map2', function($http) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {
			scope.$watch("url", function (val) {
				scope.locations = $http.get(scope.url).success(function(data){
				var locs = data;
				var zoomLevel = parseInt(locs[scope.gIndex].zoomLevel);
				
				var lat = parseInt(locs[scope.gIndex].latitude);
				var long = parseInt(locs[scope.gIndex].longitude)
				var myOptions = { zoom: 13, center: new google.maps.LatLng(lat, long), mapTypeId: google.maps.MapTypeId.ROADMAP};
				scope.map2 = new google.maps.Map(document.getElementById(attrs.id), myOptions);
				
				var flightPlanCoordinates = [
					new google.maps.LatLng(13.030594, 80.209136),
					new google.maps.LatLng(13.031594, 80.209236),
					new google.maps.LatLng(13.042594, 80.209336),
					new google.maps.LatLng(13.053594, 80.209436)
				  ];
				  var myLatlng1= flightPlanCoordinates[0];
				  var myLatlng2= flightPlanCoordinates[1];
				  var myLatlng3= flightPlanCoordinates[2];
				  var myLatlng4= flightPlanCoordinates[3];
				  
				  new google.maps.Marker({position: myLatlng1, map: scope.map2});
				  new google.maps.Marker({position: myLatlng2, map: scope.map2});
				  new google.maps.Marker({position: myLatlng3, map: scope.map2});
				  new google.maps.Marker({position: myLatlng4, map: scope.map2});
				  
				  var flightPath = new google.maps.Polyline({
					path: flightPlanCoordinates,
					geodesic: true,
					strokeColor: '#FF0000',
					strokeOpacity: 0.8,
					strokeWeight: 5
				  });
				
				  flightPath.setMap(scope.map2);
			}).error(function(){ alert('error'); });
			});
			
        }
    };
});

$(document).ready(function(e) {
    $('.contentClose').click(function(){
		$('.topContent').fadeOut(100);
		$('.contentexpand').show();
		
	});
	$('.contentexpand').click(function(){
		$('.topContent').fadeIn(100);
		$('.contentexpand').hide();
		
	});
		
});
$(function () {

    
});