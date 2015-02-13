var total = 0;
var chart = null;
var app = angular.module('mapApp',[]);
var gmarkers=[];
var ginfowindow=[];
app.controller('mainCtrl',function($scope, $http){
	$scope.locations = [];
	$scope.val = 5;	
	$scope.gIndex = 0;
	$scope.alertstrack = 0;
	$scope.totalVehicles = 0;
	$scope.vehicleOnline = 0;
	$scope.distanceCovered= 0;
	$scope.attention= 0;
	$scope.vehicleno=0;
	
	$scope.markerClicked=false;
	$scope.url = 'http://128.199.175.189:8080/laravel/public/getVehicleLocations?userId=demouser1&group=personal';
	$scope.historyfor='';
	
	$scope.$watch("url", function (val) {
		 	$http.get($scope.url).success(function(data){
				$scope.locations = data;
			$scope.zoomLevel = parseInt(data[$scope.gIndex].zoomLevel);
				
			}).error(function(){ alert('error'); });
	});
	
	$scope.map =  null;
	$scope.onHistoryClick = function(vehicleno){
		
		$scope.currentTab = 'history';
		$scope.historyfor = 'history for - ' + vehicleno;
	}
	
	$scope.genericFunction = function(vehicleno, index){
		$scope.selected = index;
		
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
	
	
	$scope.groupSelection = function(groupname, groupid){
		 $scope.url = 'http://128.199.175.189:8080/laravel/public/getVehicleLocations?userId=demouser1&group=' + groupname;
		 $scope.gIndex = groupid;
		 
		 gmarkers=[];
		 ginfowindow=[];
		 
	}
	
	$scope.addMarker= function(pos){
		
	   var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
	   if(pos.data.alert!=''){
			var pinImage = 'imgs/marker-l-red.png';
		}else{
			var pinImage = 'imgs/marker-l-mer.png';
		}
		
		/*$scope.marker = new google.maps.Marker({
			position: myLatlng, 
			map: $scope.map,
			icon: pinImage
		});*/
		$scope.marker = new MarkerWithLabel({
		   position: myLatlng, 
		   map: $scope.map,
		   icon: pinImage,
		   labelContent: pos.data.shortName,
		   labelAnchor: new google.maps.Point(12, 38),
		   labelClass: "labels", // the CSS class for the label
		   labelInBackground: false
		 });
		
		 gmarkers.push($scope.marker);
		
		if(pos.data.vehicleId==$scope.vehicleno){
			$('#vehiid h3').text(pos.data.vehicleId);
			$('#toddist h3').text(pos.data.distanceCovered);
			$('#vehstat h3').text(pos.data.status);
			total = parseInt(pos.data.speed);
			$('#vehdevtype h3').text(pos.data.deviceType);
			$('#mobno h3').text(pos.data.mobileNumber);
			$('#regno h3').text(pos.data.regNumber);		
		}
		
		 google.maps.event.addListener($scope.marker, "click", function(e){	
		      
				$('.bottomContent').fadeIn(100); 			
				$scope.vehicleno = pos.data.vehicleId;
				$('#vehiid h3').text(pos.data.vehicleId);
				$('#toddist h3').text(pos.data.distanceCovered);
				$('#vehstat h3').text(pos.data.status);
				total = parseInt(pos.data.speed);
				$('#vehdevtype h3').text(pos.data.deviceType);
				$('#mobno h3').text(pos.data.mobileNumber);
				$('#regno h3').text(pos.data.regNumber);
				$scope.map.setCenter($scope.marker.getPosition());
				
        });
		
        (function(marker, data, contentString) {
          google.maps.event.addListener($scope.marker, "click", function(e) {
           $scope.map.setCenter($scope.marker.getPosition());
          });
        })($scope.marker, pos.data);
	}
	
	$scope.removeTask=function(vehicleno){
		$('.bottomContent').fadeIn(100);
		var temp = $scope.locations[$scope.gIndex].vehicleLocations;
		$scope.vehicleno = vehicleno;
		$scope.map.setCenter($scope.marker.getPosition());
		for(var i=0; i<temp.length;i++){
			if(temp[i].vehicleId==$scope.vehicleno){
				$('#vehiid h3').text(temp[i].vehicleId);
				$('#toddist h3').text(temp[i].distanceCovered);
				$('#vehstat h3').text(temp[i].status);
				total = parseInt(temp[i].speed);
				$('#vehdevtype h3').text(temp[i].deviceType);
				$('#mobno h3').text(temp[i].mobileNumber);
				$('#regno h3').text(temp[i].regNumber);			
			}
		}
	};
});

app.directive('map', function($http) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {
			scope.$watch("url", function (val) {
			$http.get(scope.url).success(function(data){
					var locs = data;
					scope.locations = data;
					scope.distanceCovered =locs[scope.gIndex].distance;
					scope.alertstrack = locs[scope.gIndex].alerts;
					scope.totalVehicles  =locs[scope.gIndex].totalVehicles;
					scope.attention  =locs[scope.gIndex].attention;
					scope.vehicleOnline =locs[scope.gIndex].online;
					var lat = locs[scope.gIndex].latitude;
					var long = locs[scope.gIndex].longitude;
					var myOptions = { zoom: scope.zoomLevel, center: new google.maps.LatLng(lat, long), mapTypeId: google.maps.MapTypeId.ROADMAP};
					scope.map = new google.maps.Map(document.getElementById(attrs.id), myOptions);
					var length = locs[scope.gIndex].vehicleLocations.length;
					
					for (var i = 0; i < length; i++) {
						var lat = locs[scope.gIndex].vehicleLocations[i].latitude;
						var lng =  locs[scope.gIndex].vehicleLocations[i].longitude;
						scope.addMarker({ lat: lat, lng: lng , data: locs[scope.gIndex].vehicleLocations[i]});
					}
				}).error(function(){ alert('error'); });
			});
			setInterval(function () {
				$http.get(scope.url).success(function(data){
					var locs = data;
					scope.locations = data;
					scope.distanceCovered =locs[scope.gIndex].distance;
					scope.alertstrack = locs[scope.gIndex].alerts;
					scope.totalVehicles  =locs[scope.gIndex].totalVehicles;
					scope.attention  =locs[scope.gIndex].attention;
					scope.vehicleOnline =locs[scope.gIndex].online;
					//alert(locs[scope.gIndex].vehicleLocations.length);
					var length = locs[scope.gIndex].vehicleLocations.length;
					for (var i = 0; i < gmarkers.length; i++) {
						gmarkers[i].setMap(null);
					  }
					gmarkers=[];
		 			ginfowindow=[];
					for (var i = 0; i < length; i++) {
						var lat = locs[scope.gIndex].vehicleLocations[i].latitude;
						var lng =  locs[scope.gIndex].vehicleLocations[i].longitude;
						scope.addMarker({ lat: lat, lng: lng , data: locs[scope.gIndex].vehicleLocations[i]});
					}
					if(scope.selected!=undefined){
						scope.map.setCenter(gmarkers[scope.selected].getPosition()); 
					}
				}).error(function(){ alert('error'); });
				}, 3000);
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
	$('.bottomContent').hide();
	$('.contentbClose').click(function(){ $('.bottomContent').fadeOut(100); });
	
	
	chart =	$('.container01').highcharts({
				chart: {
						type: 'gauge',
						plotBackgroundColor: null,
						plotBackgroundImage: null,
						plotBorderWidth: 0,
						plotShadow: false,
						backgroundColor:'transparent'
				},
				title: {
					text: ''
				},
				pane: {
            startAngle: -90,
            endAngle: 90,
            size: [20],
             center: ['50%', '100%'],
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
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#666'],
                        [1, '#0c0c0c']
                    ]
                },
                borderWidth: 1,
                outerRadius: '107%'
            }, {
                backgroundColor: '#DDD',
                borderWidth: 1,
                outerRadius: '105%',
                innerRadius: '104%'
            }]
        },            
        plotOptions: {
        gauge: {
            dial: {
                baseWidth: 4,
                backgroundColor: '#C33',
                borderColor: '#900',
                borderWidth: 1,
                rearLength: 20,
                baseLength: 10,
                radius: 80
            }                
        }
        },
    
        yAxis: [{
            min: 0,
            max: 100,
            lineColor: '#fff',
            tickColor: '#fff',
            minorTickColor: '#fff',
            minorTickPosition: 'inside',
            tickLength: 10,
            tickWidth: 4,
            minorTickLength: 5,
            offset: -2,
            lineWidth: 2,
            labels: {
                distance: -25,
                rotation: 0,
                style: {
                    color: '#fff',
                    size: '120%',
                    fontWeight: 'bold'
                }
            },
            endOnTick: false,
            plotBands: [{
                from: 0,
                to: 100,
                innerRadius: '40%',
                outerRadius: '65%',
                color: {linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#fff'],
                        [1, '#fff']
                    ]} // green
            }, {
                from: 0,
                to: 50,
                innerRadius: '45%',
                outerRadius: '60%',
                color: '#000' // red
            }]
        }],
    
        series: [{
			data: [10],
            name: 'Speed',
            dataLabels: {
                /*formatter: function () {
                    var kmh = this.y;
                    return '<span style="color:#339">'+ kmh + '%</span>';
                },*/
                backgroundColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, '#DDD'],
                        [1, '#FFF']
                    ]
                },
                offset: 10
            },
            tooltip: {
                valueSuffix:'kmh'
            }
        }]
    
    },
				// Add some life
				function (chart) {
					setInterval(function () {
						chart.series[0].setData([total]);
					},1000);
				});	
});