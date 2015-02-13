var total = 0;
var chart = null;
var app = angular.module('mapApp', []);
app.directive('map', function($http) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {
           $http.get(scope.url).success(function(data){
		   		var locs = data;
				var myOptions = {
					zoom: 13,
					center: new google.maps.LatLng(locs.latitude, locs.longitude),
					mapTypeId: google.maps.MapTypeId.ROADMAP
            	};
            	scope.map = new google.maps.Map(document.getElementById(attrs.id), myOptions);
				
				$('#vehiid span').text(locs.vehicleId + " (" +locs.shortName+")");
				$('#toddist span span').text(locs.distanceCovered);
				//$('#vehstat span').text(locs.position);
				total = parseInt(locs.speed);
				$('#vehdevtype span').text(locs.odoDistance);
				$('#mobno span').text(locs.mobileNumber);
				if(locs.parkedTime!=0){
					var temptime = scope.timeCalculate(locs.parkedTime);
				}else if(locs.movingTime!=0){
					var temptime = scope.timeCalculate(locs.movingTime);
				}else if(locs.idleTime!=0){
					var temptime = scope.timeCalculate(locs.idleTime);
				}else if(locs.noDataTime!=0){
					var temptime = scope.timeCalculate(locs.noDataTime);
				}else{
					var temptime =0;
				}
				
				$('#regno span').text(temptime);
			   	
			   	
			   	scope.speedval.push(data.speed);
           		scope.path.push(new google.maps.LatLng(data.latitude, data.longitude));
				var labelAnchorpos = new google.maps.Point(12, 50);
				var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
				
				scope.startlatlong = new google.maps.LatLng(data.latitude, data.longitude);
				scope.endlatlong = new google.maps.LatLng(data.latitude, data.longitude);
				
				var pinImage;
				if(data.color =='P' || data.color =='N' || data.color =='A'){
					pinImage = 'assets/imgs/'+data.color+'.png';
					//var labelAnchorpos = new google.maps.Point(2, 58);
					if(data.color =='N'){
					//	var labelAnchorpos = new google.maps.Point(12, 50);
					}
					if(data.color =='A'){
						pinImage = 'assets/imgs/orangeB.png';
					//	var labelAnchorpos = new google.maps.Point(12, 50);
					}
				}else{
					pinImage = 'assets/imgs/'+data.color+'_'+data.direction+'.png';
					//var labelAnchorpos = new google.maps.Point(12, 50);
				}
				scope.marker = new MarkerWithLabel({
			   		position: myLatlng, 
				   map: scope.map,
				   icon: pinImage,
				   labelContent: data.shortName,
				   labelAnchor: labelAnchorpos,
				   labelClass: "labels", 
				   labelInBackground: false
				 });
		 		if(scope.path.length>1){
			 		var latLngBounds = new google.maps.LatLngBounds();
					latLngBounds.extend(scope.path[scope.path.length-1]);
				}
				
				if(data.isOverSpeed=='N'){
					var strokeColorvar = '#068b03';
				}else{
					var strokeColorvar = '#ff0000';
				}
				
				/*if(data.speed>-1 && data.speed<40){
			         	var strokeColorvar = '#068b03';
			         }else if(data.speed>39 && data.speed<60){
			         	var strokeColorvar = '#e5e203';
			         }else if(data.speed>59 && data.speed<90){
			         	var strokeColorvar = '#e58203';
			         }else if(data.speed<89){
			         	var strokeColorvar = '#ff0000';
			         }
         */
				  	var polyline = new google.maps.Polyline({
			            map: scope.map,
			            path: [scope.startlatlong, scope.endlatlong],
			            strokeColor: strokeColorvar,
			            strokeOpacity: 0.7,
			            strokeWeight: 2
			        });
			        scope.startlatlong = scope.endlatlong;
			         
			        /*for (var i = 0; i < scope.path.length-1; i++) {
			        	
			        if(scope.speedval[i]>-1 && scope.speedval[i]<40){
			         	scope.strokeColorvar = '#068b03';
			         }else if(scope.speedval[i]>39 && scope.speedval[i]<60){
			         	scope.strokeColorvar = '#57ff04';
			         }else if(scope.speedval[i]>59 && scope.speedval[i]<90){
			         	scope.strokeColorvar = '#e58203';
			         	alert('')
			         }else if(scope.speedval[i]<89){
			         	scope.strokeColorvar = '#ff0000';
			         }
					 var polyline = new google.maps.Polyline({
					    path: [scope.path[i], scope.path[i+1]],
					    strokeColor: scope.strokeColorvar,
					    strokeOpacity: 0.7,
					    strokeWeight: 2,
					    map: scope.map
					  });
					}*/
			        
		  	}).error(function(){ });
		  	setInterval(function() {
		   		$http.get(scope.url).success(function(data){
		   			var locs = data;
					var myOptions = {
						zoom: 13,
						center: new google.maps.LatLng(locs.latitude, locs.longitude),
						mapTypeId: google.maps.MapTypeId.ROADMAP
	            	};
            		$('#vehiid span').text(locs.vehicleId + " (" +locs.shortName+")");
					$('#toddist span span').text(locs.distanceCovered);
					$('#vehstat span').text(locs.position);
					total = parseInt(locs.speed);
					$('#vehdevtype span').text(locs.odoDistance);
					$('#mobno span').text(locs.mobileNumber);
					if(locs.parkedTime!=0){
						var temptime = scope.timeCalculate(locs.parkedTime);
					}else if(locs.movingTime!=0){
						var temptime = scope.timeCalculate(locs.movingTime);
					}else if(locs.idleTime!=0){
						var temptime = scope.timeCalculate(locs.idleTime);
					}else if(locs.noDataTime!=0){
						var temptime = scope.timeCalculate(locs.noDataTime);
					}else{
						var temptime =0;
					}
					
					$('#regno span').text(temptime);
           			scope.path.push(new google.maps.LatLng(data.latitude, data.longitude));
					if(scope.path.length>1){
					 	var latLngBounds = new google.maps.LatLngBounds();
						latLngBounds.extend(scope.path[scope.path.length-1]);
					}
					var labelAnchorpos = new google.maps.Point(12, 50);
					var pinImage;
					if(data.color =='P' || data.color =='N' || data.color =='A'){
						pinImage = 'assets/imgs/'+data.color+'.png';
						//var labelAnchorpos = new google.maps.Point(2, 58);
						if(data.color =='N'){
							//var labelAnchorpos = new google.maps.Point(12, 50);
						}
						if(data.color =='A'){
							pinImage = 'assets/imgs/orangeB.png';
							//var labelAnchorpos = new google.maps.Point(12, 50);
						}
					}else{
						pinImage = 'assets/imgs/'+data.color+'_'+data.direction+'.png';
						//var labelAnchorpos = new google.maps.Point(12, 50);
					}
					var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
					scope.marker.setMap(null);
					scope.map.setCenter(scope.marker.getPosition());
					scope.marker = new MarkerWithLabel({
					   position: myLatlng, 
					   map: scope.map,
					   icon: pinImage,
					   labelContent: data.shortName,
					   labelAnchor: labelAnchorpos,
					   labelClass: "labels",
					   labelInBackground: false
					});
					
					scope.endlatlong = new google.maps.LatLng(data.latitude, data.longitude);
			        if(data.isOverSpeed=='N'){
						var strokeColorvar = '#068b03';
					}else{
						var strokeColorvar = '#ff0000';
					}
         
         			
				  	var polyline = new google.maps.Polyline({
			            map: scope.map,
			            path: [scope.startlatlong, scope.endlatlong],
			            strokeColor: strokeColorvar,
			            strokeOpacity: 0.7,
			            strokeWeight: 4
			        });
			        scope.startlatlong = scope.endlatlong;
		   		}).error(function(){ });
		   }, 10000);
        }
    };
});
app.controller('mainCtrl',function($scope, $http){ 
	var res = document.location.href.split("?");
	$scope.url = 'http://'+globalIP+'/vamo/public/getSelectedVehicleLocation?'+res[1];
	$scope.path = [];
	$scope.speedval =[];
	$scope.inter = 0;
	$scope.cityCircle=[];
	$scope.cityCirclecheck=false;
	$http.get($scope.url).success(function(data){
		$scope.locations = data;
		var url = 'http://'+globalIP+'/vamo/public/getGeoFenceView?'+res[1];
				$scope.createGeofence(url)
	}).error(function(){});
    $scope.addMarker= function(pos){
	   var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
	   var marker = new google.maps.Marker({
			position: myLatlng, 
			map: $scope.map
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
						boxStyle: {textAlign: "center", fontSize: "8pt", fontColor: "#0031c4", width: "100px"
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
});
$(document).ready(function(e) {
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
    		},{
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
