//comment by satheesh ++...
var total = 0;
var chart = null;

var gmarkers=[];
var ginfowindow=[];
var app = angular.module('mapApp',[])
.controller('mainCtrl',['$scope', '$http', function($scope, $http){
	$scope.locations = [];
	$scope.nearbyLocs =[];
	$scope.val = 5;	
	$scope.gIndex = 0;
	$scope.alertstrack = 0;
	$scope.totalVehicles = 0;
	$scope.vehicleOnline = 0;
	$scope.distanceCovered= 0;
	$scope.attention= 0;
	$scope.vehicleno='';
	$scope.cityCircle=[];
	$scope.cityCirclecheck=false;
	$scope.markerClicked=false;
	$scope.url = 'http://'+globalIP+'/vamo/public/getVehicleLocations';
	$scope.historyfor='';
	
	$scope.$watch("url", function (val) {
		$http.get($scope.url).success(function(data){
			$scope.locations = data;
			$scope.zoomLevel = parseInt(data[$scope.gIndex].zoomLevel);
		}).error(function(){});
	});
	
	$scope.map =  null;
	$scope.flightpathall = []; 
	$scope.clickflag = false;
	$scope.flightPath = new google.maps.Polyline();
	$scope.onHistoryClick = function(vehicleno){
		$scope.currentTab = 'history';
		$scope.historyfor = 'history for - ' + vehicleno;
	}
	var tempdistVal = 0;
	$scope.drawLine = function(loc1, loc2){
		var flightPlanCoordinates = [loc1, loc2];
		
		$scope.flightPath = new google.maps.Polyline({
			path: flightPlanCoordinates,
			geodesic: true,
			strokeColor: "#ff0000",
			strokeOpacity: 1.0,
			strokeWeight:2
		});
		
		$scope.flightPath.setMap($scope.map);
		$scope.flightpathall.push($scope.flightPath);
		tempdistVal = parseFloat($('#distanceVal').val()) + parseFloat((google.maps.geometry.spherical.computeDistanceBetween(loc1,loc2)/1000).toFixed(2))
		$('#distanceVal').val(tempdistVal.toFixed(2));
	}
	
	$scope.genericFunction = function(vehicleno, index){
		$scope.selected = index;
		
		$scope.removeTask(vehicleno);
	}
	
	$scope.trafficLayer = new google.maps.TrafficLayer();
	$scope.checkVal=false;
	$scope.clickflagVal =0;
	$scope.nearbyflag = false;
	$scope.check = function(){
		if($scope.checkVal==false){
			$scope.trafficLayer.setMap($scope.map);
			$scope.checkVal = true;
		}else{
			$scope.trafficLayer.setMap(null);
			$scope.checkVal = false;
		}
	}
	$('.nearbyTable').hide();
	$scope.nearBy = function(){
		
		if($scope.nearbyflag == false){
			//alert('click location you want to find nearby Vehicles');
			$('#myModal').modal();	
			$scope.nearbyflag=true;
		}else{
			$('.nearbyTable').hide();
			$scope.nearbyflag=false;
		}
	}
	$scope.getLocation = function(lat,lon, callback){
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(lat, lon);
		geocoder.geocode({'latLng': latlng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
			  if (results[1]) {
				if(typeof callback === "function") callback(results[1].formatted_address)
			  } else {
				alert('No results found');
			  }
			} else {
			  alert('Geocoder failed due to: ' + status);
			}
		  });
    };
	$scope.distance = function(){
		$scope.nearbyflag=false;
		$('.nearbyTable').hide();
		if($scope.clickflag==true){
			$scope.clickflagVal = 0;
			$('#distanceVal').val(0);
			$scope.clickflag=false;
			for(var i=0; i<$scope.flightpathall.length; i++){
				$scope.flightpathall[i].setMap(null);	
			}
		}else{
			$scope.clickflag=true;	
		}
	}
	
	
	$scope.groupSelection = function(groupname, groupid){
		 $scope.url = 'http://'+globalIP+'/vamo/public/getVehicleLocations?group=' + groupname;
		 $scope.gIndex = groupid;
		 $scope.selected=undefined; 
		 gmarkers=[];
		 ginfowindow=[];
	}
	
	$scope.infoBoxed = function(map, marker, vehicleID){
		//var url = 'http://'+globalIP+'/vamo/public/getGeoFenceView?vehicleId='+$scope.vehicleno;
		var contentString = '<div style="padding:10px; width:130px; height:40px;"><a href="../public/track?vehicleId='+vehicleID+'" target="_blank">Track '+vehicleID+'</a>&nbsp;&nbsp;</div>';
		var infowindow = new google.maps.InfoWindow({
		 content: contentString
		});
		ginfowindow.push(infowindow);
		google.maps.event.addListener(marker, "click", function(e){
			infowindow.open(map, marker);	
		});
		(function(marker) {
		  google.maps.event.addListener(marker, "click", function(e) {
			infowindow.open(map, marker);
		  });	
		})(marker);
		//infowindow.open(map,marker);
	}
	
	$scope.addMarker= function(pos){
	   var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
	   
		var pinImage;
		var labelAnchorpos = new google.maps.Point(12, 50);
		if(pos.data.color =='P' || pos.data.color =='N' || pos.data.color =='A'){
			pinImage = 'assets/imgs/'+pos.data.color+'.png';
			//var labelAnchorpos = new google.maps.Point(2, 58);
			if(pos.data.color =='N'){
				//var labelAnchorpos = new google.maps.Point(12, 50);
			}
			if(pos.data.color =='A'){
				pinImage = 'assets/imgs/orangeB.png';
				//var labelAnchorpos = new google.maps.Point(12, 50);
			}
		}else{
			pinImage = 'assets/imgs/'+pos.data.color+'_'+pos.data.direction+'.png';
			//var labelAnchorpos = new google.maps.Point(12, 50);
		}
		$scope.marker = new MarkerWithLabel({
		   position: myLatlng, 
		   map: $scope.map,
		   icon: pinImage,
		   labelContent: pos.data.shortName,
		   labelAnchor: labelAnchorpos,
		   labelClass: "labels", // the CSS class for the label
		   labelInBackground: false
		 });
		if(pos.data.vehicleId==$scope.vehicleno){
			$('#vehiid span').text(pos.data.vehicleId + " (" +pos.data.shortName+")");
			$('#toddist span span').text(pos.data.distanceCovered);
			$('#vehstat span').text(pos.data.position);
			total = parseInt(pos.data.speed);
			$('#vehdevtype span').text(pos.data.odoDistance);
			$('#mobno span').text(pos.data.mobileNumber);
			
			if(pos.data.parkedTime!=0){
				var temptime = $scope.timeCalculate(pos.data.parkedTime);
			}else if(pos.data.movingTime!=0){
				var temptime = $scope.timeCalculate(pos.data.movingTime);
			}else if(pos.data.idleTime!=0){
				var temptime = $scope.timeCalculate(pos.data.idleTime);
			}else if(pos.data.noDataTime!=0){
				var temptime = $scope.timeCalculate(pos.data.noDataTime);
			}else{
				var temptime =0;
			}
			
			$('#regno span').text(temptime);			
		}
		gmarkers.push($scope.marker);
		google.maps.event.addListener(gmarkers[gmarkers.length-1], "click", function(e){	
				$scope.vehicleno = pos.data.vehicleId;
				
				$('#vehiid span').text(pos.data.vehicleId + " (" +pos.data.shortName+")");
				$('#toddist span span').text(pos.data.distanceCovered);
				$('#vehstat span').text(pos.data.position);
				total = parseInt(pos.data.speed);
				$('#vehdevtype span').text(pos.data.odoDistance);
				$('#mobno span').text(pos.data.overSpeedLimit);
				if(pos.data.parkedTime!=0){
					var temptime = $scope.timeCalculate(pos.data.parkedTime);
				}else if(pos.data.movingTime!=0){
					var temptime = $scope.timeCalculate(pos.data.movingTime);
				}else if(pos.data.idleTime!=0){
					var temptime = $scope.timeCalculate(pos.data.idleTime);
				}else if(pos.data.noDataTime!=0){
					var temptime = $scope.timeCalculate(pos.data.noDataTime);
				}else{
					var temptime =0;
				}
					
				$('#regno span').text(temptime);
				$('#lstseendate').html('<strong>Last Seen Date & time :</strong> '+ pos.data.lastSeen);
				$scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
					$('#lastseen').text(count); 
				});
				
				if($scope.selected!=undefined){
					$scope.map.setCenter(gmarkers[$scope.selected].getPosition()); 	
				}
				
				
        });
	}
	$scope.timeCalculate = function(duration){
		var milliseconds = parseInt((duration%1000)/100), seconds = parseInt((duration/1000)%60);
		var minutes = parseInt((duration/(1000*60))%60), hours = parseInt((duration/(1000*60*60))%24);
		hours = (hours < 10) ? "0" + hours : hours;
		minutes = (minutes < 10) ? "0" + minutes : minutes;
		seconds = (seconds < 10) ? "0" + seconds : seconds;
		temptime = hours + " H : " + minutes +' M';
		return temptime;
	}
	$scope.setzomlevel = function(zoom){
		var curZoom = parseInt(zoom);
		var zoomInterval;
		
		zoomInterval = setInterval(function () {
			curZoom += 1;
			$scope.map.setZoom(curZoom);
			if (curZoom === 13) {
				clearInterval(zoomInterval);
			}
		}, 10);
	}
	$scope.createGeofence=function(url){
		
		if($scope.cityCirclecheck==false){
			$scope.cityCirclecheck=true;
		}
		if($scope.cityCirclecheck==true){
			for(var i=0; i<$scope.cityCircle.length; i++){
				$scope.cityCircle[i].setMap(null);
			}
		}
		$http.get(url).success(function(data){
			$scope.geoloc = data;
			if (typeof(data.geoFence) !== 'undefined' && data.geoFence.length) {	
				//alert(data.geoFence[0].proximityLevel);
				for(var i=0; i<data.geoFence.length; i++){
					var populationOptions = {
							  strokeColor: '#FF0000',
							  strokeOpacity: 0.8,
							  strokeWeight: 2,
							  fillColor: '#FF0000',
							  fillOpacity: 0.02,
							  map: $scope.map,
							  center: new google.maps.LatLng(data.geoFence[i].latitude,data.geoFence[i].longitude),
							  radius: parseInt(data.geoFence[i].proximityLevel)
					};
					$scope.cityCircle[i] = new google.maps.Circle(populationOptions);
					var centerPosition = new google.maps.LatLng(data.geoFence[i].latitude, data.geoFence[i].longitude);
					var labelText = data.geoFence[i].poiName;
					var image = 'assets/imgs/bus.png';
				  
				  	var beachMarker = new google.maps.Marker({
				      position: centerPosition,
				      map: $scope.map,
				      icon: image
				  	});
					var myOptions = {
						content: labelText,
						boxStyle: {textAlign: "center", fontSize: "9pt", fontColor: "#ff0000", width: "100px"
						},
						disableAutoPan: true,
						pixelOffset: new google.maps.Size(-50, 0),
						position: centerPosition,
						closeBoxURL: "",
						isHidden: false,
						pane: "mapPane",
						enableEventPropagation: true
					};
					var labelinfo = new InfoBox(myOptions);
					labelinfo.open($scope.map);
					labelinfo.setPosition($scope.cityCircle[i].getCenter());
				}
			}
		}).error(function(){});
	}
	$scope.removeTask=function(vehicleno){
		var temp = $scope.locations[$scope.gIndex].vehicleLocations;
		$scope.vehicleno = vehicleno;
		for(var i=0; i<temp.length;i++){
			if(temp[i].vehicleId==$scope.vehicleno){
				$scope.map.setCenter(gmarkers[i].getPosition());
				//$scope.setzomlevel($scope.locations[$scope.gIndex].zoomLevel);
				$scope.map.setZoom(13);
				$('#vehiid span').text(temp[i].vehicleId + " (" +temp[i].shortName+")");
				$('#toddist span span').text(temp[i].distanceCovered);
				$('#vehstat span').text(temp[i].position);
				total = parseInt(temp[i].speed);
				$('#vehdevtype span').text(temp[i].odoDistance);
				$('#mobno span').text(temp[i].overSpeedLimit);
				if(temp[i].parkedTime!=0){
					var temptime = $scope.timeCalculate(temp[i].parkedTime);
				}else if(temp[i].movingTime!=0){
					var temptime = $scope.timeCalculate(temp[i].movingTime);
				}else if(temp[i].idleTime!=0){
					var temptime = $scope.timeCalculate(temp[i].idleTime);
				}else if(temp[i].noDataTime!=0){
					var temptime = $scope.timeCalculate(temp[i].noDataTime);
				}else{
					var temptime =0;
				}
				
				$('#regno span').text(temptime);
				$('#lstseendate').html('<strong>Last Seen Date & time :</strong> '+ temp[i].lastSeen);		
				$scope.getLocation(temp[i].latitude, temp[i].longitude, function(count){ 
					$('#lastseen').text(count); 
				});
				
				var url = 'http://'+globalIP+'/vamo/public/getGeoFenceView?vehicleId='+$scope.vehicleno;
				$scope.createGeofence(url)
				
			}
		}
	};
}])
 .directive('map', function($http) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {	
		
			scope.$watch("url", function (val) {
				
				$http.get(scope.url).success(function(data){
					var locs = data;
					if($('.nav-second-level li').eq(scope.selected).children('a').hasClass('active')){
					}else{
						$('.nav-second-level li').eq(scope.selected).children('a').addClass('active');
					}
		
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
					
					var input = document.getElementById('pac-input');
					var searchBox = new google.maps.places.SearchBox(input);
					
					google.maps.event.addListener(searchBox, 'places_changed', function() {
						var places = searchBox.getPlaces();
						if (places.length == 0) {
						 return;
						}
						var bounds = new google.maps.LatLngBounds();
						for (var i = 0, place; place = places[i]; i++) {
							bounds.extend(place.geometry.location);
						}
						scope.map.fitBounds(bounds);
						scope.map.setZoom(13);
					});
					
					
					google.maps.event.addListener(scope.map, 'click', function(event) {
												
						$('#latinput').val((event.latLng.lat()).toFixed(6) +', '+ (event.latLng.lng()).toFixed(6));
						if(scope.clickflag==true){
							if(scope.clickflagVal ==0){
								scope.firstLoc = event.latLng;
								scope.clickflagVal =1;
							}else if(scope.clickflagVal==1){
								scope.drawLine(scope.firstLoc, event.latLng);
								scope.firstLoc = event.latLng;
							}
						}else if(scope.nearbyflag==true){
							$('#status02').show(); // will first fade out the loading animation
							$('#preloader02').show(); // will fade out the white DIV that covers the website.
							var tempurl = 'http://'+globalIP+'/vamo/public/getNearByVehicles?lat='+event.latLng.lat()+'&lng='+event.latLng.lng();
							$http.get(tempurl).success(function(data){
								scope.nearbyLocs = data;
								$('#status02').fadeOut(); // will first fade out the loading animation
								$('#preloader02').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
									
								if(scope.nearbyLocs.fromAddress==''){
									
								}else{
									$('.nearbyTable').delay(350).show();
								}
							}).error(function(){ /*alert('error'); */});
						}
					});
					google.maps.event.addListener(scope.map, 'bounds_changed', function() {
						var bounds = scope.map.getBounds();
						scope.selected=undefined;
						searchBox.setBounds(bounds);
					});
					
					var length = locs[scope.gIndex].vehicleLocations.length;
					for (var i = 0; i < length; i++) {
						var lat = locs[scope.gIndex].vehicleLocations[i].latitude;
						var lng =  locs[scope.gIndex].vehicleLocations[i].longitude;
						scope.addMarker({ lat: lat, lng: lng , data: locs[scope.gIndex].vehicleLocations[i]});
						scope.infoBoxed(scope.map,gmarkers[i], locs[scope.gIndex].vehicleLocations[i].vehicleId);
					}
					
					if(scope.selected!=undefined){
							scope.map.setCenter(gmarkers[scope.selected].getPosition()); 	
						}
				}).error(function(){});
				
				$(document).on('pageshow', '#maploc', function(e, data){       
                	google.maps.event.trigger(document.getElementById('	maploc'), "resize");
           		 });
					
			});
			scope.$watch("url", function(val) {
				setInterval(function() {
					$http.get(scope.url).success(function(data){
						var locs = data;
						scope.locations = data;
						scope.distanceCovered =locs[scope.gIndex].distance;
						scope.alertstrack = locs[scope.gIndex].alerts;
						scope.totalVehicles  =locs[scope.gIndex].totalVehicles;
						scope.attention  =locs[scope.gIndex].attention;
						scope.vehicleOnline =locs[scope.gIndex].online;
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
							scope.infoBoxed(scope.map,gmarkers[i], locs[scope.gIndex].vehicleLocations[i].vehicleId);
						}
						if(scope.selected!=undefined){
							//scope.map.setCenter(locs[scope.gIndex].vehicleLocations[scope.selected].latitude,  locs[scope.gIndex].vehicleLocations[scope.selected].longitude); 	
						}
					}).error(function(){ });
					if(scope.selected!=undefined){
							scope.map.setCenter(gmarkers[scope.selected].getPosition()); 	
						}
				}, 10000);
    		});
	    }
    
	};
	
});
$(window).load(function() { // makes sure the whole site is loaded
		$('#status').fadeOut(); // will first fade out the loading animation
		$('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
		$('body').delay(350).css({'overflow':'visible'});
	})
$(document).ready(function(e) {
    $('.contentClose').click(function(){
		$('.topContent').fadeOut(100);
		$('.contentexpand').show();	
	});
	$('.contentexpand').click(function(){
		$('.topContent').fadeIn(100);
		$('.contentexpand').hide();
	});
	//$('.bottomContent').hide();
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
            max: 200,
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
                color: '#000' 
            }]
        }],    
        series: [{
			data: [10],
            name: 'Speed',
            dataLabels: {
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
	function (chart) {
		setInterval(function () {
			chart.series[0].setData([total]);
		},1000);
	});	
});
