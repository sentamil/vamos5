//comment by satheesh ++...
var total = 0;
var chart = null;

var gmarkers=[];
var ginfowindow=[];
var geomarker=[];
var geoinfo=[];
var app = angular.module('mapApp',[])
.controller('mainCtrl',['$scope', '$http','vamoservice', function($scope, $http, vamoservice){
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
	$scope.polylinelive=new google.maps.Polyline();
	$scope.polylinelivearr=[];
	$scope.cityCirclecheck=false;
	$scope.markerClicked=false;
	$scope.endlatlong = new google.maps.LatLng();
	$scope.startlatlong = new google.maps.LatLng();
	$scope.url = 'http://'+globalIP+'/vamo/public/getVehicleLocations';
	$scope.historyfor='';
	
	$scope.locations01 = vamoservice.getDataCall($scope.url)
						
	$scope.$watch("url", function (val) {
		vamoservice.getDataCall($scope.url).then(function(data) {
			$scope.locations = data;
			$scope.zoomLevel = parseInt(data[$scope.gIndex].zoomLevel);
		});
		                
		
	});
	
	$scope.map =  null;
	$scope.flightpathall = []; 
	$scope.clickflag = false;
	$scope.flightPath = new google.maps.Polyline();
	
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
			  }
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
		 $scope.selected=0;
		 $scope.url = 'http://'+globalIP+'/vamo/public/getVehicleLocations?group=' + groupname;
		 $scope.gIndex = groupid;
		 gmarkers=[];
		 ginfowindow=[];
	}
	
	$scope.infoBoxed = function(map, marker, vehicleID){
		var contentString = '<div style="padding:10px; width:150px; height:40px;"><a href="../public/track?vehicleId='+vehicleID+'" target="_blank">Track '+vehicleID+'</a>&nbsp;&nbsp;</div>';
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
	}
	
	$scope.addMarker= function(pos){
	   var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
	   
		var pinImage;
		var labelAnchorpos = new google.maps.Point(12, 50);
		if(pos.data.color =='P' || pos.data.color =='N' || pos.data.color =='A'){
			if(pos.data.color =='A'){
				pinImage = 'assets/imgs/orangeB.png';
			}else{
				pinImage = 'assets/imgs/'+pos.data.color+'.png';
			}
		}else{
			pinImage = 'assets/imgs/'+pos.data.color+'_'+pos.data.direction+'.png';
		}
		$scope.marker = new MarkerWithLabel({
		   position: myLatlng, 
		   map: $scope.map,
		   icon: pinImage,
		   labelContent: pos.data.shortName,
		   labelAnchor: labelAnchorpos,
		   labelClass: "labels", 
		   labelInBackground: false
		 });
		if(pos.data.vehicleId==$scope.vehicleno){
			$('#vehiid span').text(pos.data.vehicleId + " (" +pos.data.shortName+")");
			$('#toddist span span').text(pos.data.distanceCovered);
			$('#vehstat span').text(pos.data.position);
			total = parseInt(pos.data.speed);
			$('#vehdevtype span').text(pos.data.odoDistance);
			$('#mobno span').text(pos.data.overSpeedLimit);
			
			$('#positiontime').text(vamoservice.statusTime(pos.data).tempcaption);
				$('#regno span').text(vamoservice.statusTime(pos.data).temptime);
			$scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
				$('#lastseen').text(count);
			});		
		}
		gmarkers.push($scope.marker);
		google.maps.event.addListener(gmarkers[gmarkers.length-1], "click", function(e){	
				$scope.vehicleno = pos.data.vehicleId;
				$scope.startlatlong= new google.maps.LatLng();
				$scope.endlatlong= new google.maps.LatLng();
				
				for(var i=0; i<$scope.polylinelive.length;i++){		
					 $scope.polylinelive[i].setMap(null);
				}
				
				$('#vehiid span').text(pos.data.vehicleId + " (" +pos.data.shortName+")");
				$('#toddist span span').text(pos.data.distanceCovered);
				$('#vehstat span').text(pos.data.position);
				total = parseInt(pos.data.speed);
				$('#vehdevtype span').text(pos.data.odoDistance);
				$('#mobno span').text(pos.data.overSpeedLimit);
				
				$('#positiontime').text(vamoservice.statusTime(pos.data).tempcaption);
				$('#regno span').text(vamoservice.statusTime(pos.data).temptime);
				$('#lstseendate').html('<strong>Last Seen Date & time :</strong> '+ pos.data.lastSeen);
				$scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
				$('#lastseen').text(count); 
				try { 
					var reversegeourl = 'http://'+globalIP+'/geocode/public/store?geoLocation='+locs.latitude+','+locs.longitude+'6&geoAddress='+count;
				    vamoservice.getDataCall(reversegeourl);
				}
				catch(err) {
			    	console.log(err);
				}
			});
			if($scope.selected!=undefined){
				$scope.map.setCenter(gmarkers[$scope.selected].getPosition()); 	
			}
        });
	}
	$scope.enterkeypress = function(){
		var url = 'http://'+globalIP+'/vamo/public/setPOIName?vehicleId='+$scope.vehicleno+'&poiName='+document.getElementById('poival').value;
		if(document.getElementById('poival').value=='' || $scope.vehicleno==''){}else{
			vamoservice.getDataCall(url).then(function(data) {
			 	document.getElementById('poival').value='';
			});
		}
	}
	
	$scope.createGeofence=function(url){
		if($scope.cityCirclecheck==false){
			$scope.cityCirclecheck=true;
		}
		if($scope.cityCirclecheck==true){
			for(var i=0; i<$scope.cityCircle.length; i++){
				$scope.cityCircle[i].setMap(null);
			}
			for(var i=0; i<geomarker.length; i++){
				geomarker[i].setMap(null);
				geoinfo[i].setMap(null);
			}
		}
		vamoservice.getDataCall(url).then(function(data) {
			$scope.geoloc = data;
			if (typeof(data.geoFence) !== 'undefined' && data.geoFence.length) {	
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
				  	geomarker.push(beachMarker);
					var myOptions = { content: labelText, boxStyle: {textAlign: "center", fontSize: "9pt", fontColor: "#ff0000", width: "100px"},
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
					geoinfo.push(labelinfo);
				}
			}
		});
	}
	
	$scope.removeTask=function(vehicleno){
		var temp = $scope.locations[$scope.gIndex].vehicleLocations;
		$scope.vehicleno = vehicleno;
		$scope.map.setZoom(13);
		$scope.endlatlong = new google.maps.LatLng();
		$scope.startlatlong = new google.maps.LatLng();
		
		for(var i=0; i<$scope.polylinelivearr.length;i++){		
			 $scope.polylinelivearr[i].setMap(null);
		}
		 
		for(var i=0; i<temp.length;i++){
			$scope.polylinelive.setMap(null);
			if(temp[i].vehicleId==$scope.vehicleno){
				
				
				$scope.map.setCenter(gmarkers[i].getPosition());
				
				$('#vehiid span').text(temp[i].vehicleId + " (" +temp[i].shortName+")");
				$('#toddist span span').text(temp[i].distanceCovered);
				$('#vehstat span').text(temp[i].position);
				total = parseInt(temp[i].speed);
				$('#vehdevtype span').text(temp[i].odoDistance);
				$('#mobno span').text(temp[i].overSpeedLimit);
				
				$('#positiontime').text(vamoservice.statusTime(temp[i]).tempcaption);
				$('#regno span').text(vamoservice.statusTime(temp[i]).temptime);
				$('#lstseendate').html('<strong>Last Seen Date & time :</strong> '+ temp[i].lastSeen);		
				$scope.getLocation(temp[i].latitude, temp[i].longitude, function(count){ 
					$('#lastseen').text(count); 
				});
				var url = 'http://'+globalIP+'/vamo/public/getGeoFenceView?vehicleId='+$scope.vehicleno;
				$scope.createGeofence(url);
			}
		}
		
	};
}]).factory('vamoservice', function($http, $q){
	return {
        timeCalculate: function(duration){
            var milliseconds = parseInt((duration%1000)/100), seconds = parseInt((duration/1000)%60);
			var minutes = parseInt((duration/(1000*60))%60), hours = parseInt((duration/(1000*60*60))%24);
			hours = (hours < 10) ? "0" + hours : hours;
			minutes = (minutes < 10) ? "0" + minutes : minutes;
			seconds = (seconds < 10) ? "0" + seconds : seconds;
			temptime = hours + " H : " + minutes +' M';
			return temptime;
        },
        dayhourmin:function(t){
        	
		    var cd = 24 * 60 * 60 * 1000,
		        ch = 60 * 60 * 1000,
		        d = Math.floor(t / cd),
		        h = Math.floor( (t - d * cd) / ch),
		        m = Math.round( (t - d * cd - h * ch) / 60000),
		        pad = function(n){ return n < 10 ? '0' + n : n; };
		  if( m === 60 ){
		    h++;
		    m = 0;
		  }
		  if( h === 24 ){
		    d++;
		    h = 0;
		  }
		  return [d+'D', pad(h)+'H', pad(m)+'M'].join(':');
		  console.log([d+'D', pad(h)+'H', pad(m)+'M'].join(':'));
		},
        getDataCall: function(url){
        	var defdata = $q.defer();
        	$http.get(url).success(function(data){
            	 defdata.resolve(data);
			}).error(function() {
                    defdata.reject("Failed to get data");
            });
			return defdata.promise;
        },
        statusTime:function(arrVal){
        	console.log(arrVal);
        	var posTime={};
        	var temptime = 0;
			var tempcaption = 'Position';
        	if(arrVal.parkedTime!=0){
					temptime = this.dayhourmin(arrVal.parkedTime);
					tempcaption = 'Parked';
				}else if(arrVal.movingTime!=0){
					temptime = this.dayhourmin(arrVal.movingTime);
					tempcaption = 'Moving';
				}else if(arrVal.idleTime!=0){
					temptime = this.dayhourmin(arrVal.idleTime);
					tempcaption = 'Idle';
				}else if(arrVal.noDataTime!=0){
					temptime = this.dayhourmin(arrVal.noDataTime);
					tempcaption = 'No data';
				}
				posTime['temptime'] = temptime;
				posTime['tempcaption'] = tempcaption;
				return posTime;
        }  
    }  
}).directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });
                event.preventDefault();
            }
        });
    };
}).directive('map', function($http, vamoservice) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs){
        	scope.$watch("url", function(val){
        		vamoservice.getDataCall(scope.url).then(function(data) {
					var locs = data;
					if($('.nav-second-level li').eq(scope.selected).children('a').hasClass('active')){
					}else{
						$('.nav-second-level li').eq(scope.selected).children('a').addClass('active');
					}
					
					scope.locations = data;
					scope.polylinelive.setMap(null);
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
												
						//$('#latinput').val((event.latLng.lat()).toFixed(6) +', '+ (event.latLng.lng()).toFixed(6));
						if(scope.clickflag==true){
							if(scope.clickflagVal ==0){
								scope.firstLoc = event.latLng;
								scope.clickflagVal =1;
							}else if(scope.clickflagVal==1){
								scope.drawLine(scope.firstLoc, event.latLng);
								scope.firstLoc = event.latLng;
							}
						}else if(scope.nearbyflag==true){
							$('#status02').show(); 
							$('#preloader02').show(); 
							var tempurl = 'http://'+globalIP+'/vamo/public/getNearByVehicles?lat='+event.latLng.lat()+'&lng='+event.latLng.lng();
							$http.get(tempurl).success(function(data){
								scope.nearbyLocs = data;
								$('#status02').fadeOut(); 
								$('#preloader02').delay(350).fadeOut('slow');
								if(scope.nearbyLocs.fromAddress==''){}else{
									$('.nearbyTable').delay(350).show();
								}
							});
						}
					});
					google.maps.event.addListener(scope.map, 'bounds_changed', function() {
						var bounds = scope.map.getBounds();
						
						//scope.selected=undefined;
						searchBox.setBounds(bounds);
					});
					scope.startlatlong= new google.maps.LatLng();
					scope.endlatlong= new google.maps.LatLng();
					var length = locs[scope.gIndex].vehicleLocations.length;
					for (var i = 0; i < length; i++) {
						var lat = locs[scope.gIndex].vehicleLocations[i].latitude;
						var lng =  locs[scope.gIndex].vehicleLocations[i].longitude;
						scope.addMarker({ lat: lat, lng: lng , data: locs[scope.gIndex].vehicleLocations[i]});
						scope.infoBoxed(scope.map,gmarkers[i], locs[scope.gIndex].vehicleLocations[i].vehicleId);
						if(locs[scope.gIndex].vehicleLocations[i].vehicleId==scope.vehicleno){
							scope.endlatlong = new google.maps.LatLng(lat, lng);
							scope.startlatlong = new google.maps.LatLng(lat, lng);
							if(locs[scope.gIndex].vehicleLocations[i].isOverSpeed=='N'){
								var strokeColorvar = '#00b3fd';
							}else{
								var strokeColorvar = '#ff0000';
							}
							scope.polylinelive = new google.maps.Polyline({
					            map: scope.map,
					            path: [scope.startlatlong, scope.endlatlong],
					            strokeColor: strokeColorvar,
					            strokeOpacity: 0.7,
					            strokeWeight: 5
					        });
					    	scope.startlatlong = scope.endlatlong;
						}
					}
				});
				if(scope.selected>-1 && gmarkers[scope.selected]!=undefined){
					scope.map.setCenter(gmarkers[scope.selected].getPosition()); 	
				}
				$(document).on('pageshow', '#maploc', function(e, data){       
                	google.maps.event.trigger(document.getElementById('	maploc'), "resize");
           		});
			});
			scope.$watch("url", function(val) {
				setInterval(function() {
					vamoservice.getDataCall(scope.url).then(function(data) {
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
							scope.addMarker({ lat: lat, lng: lng , data: locs[scope.gIndex].vehicleLocations[i]});
							scope.infoBoxed(scope.map,gmarkers[i], locs[scope.gIndex].vehicleLocations[i].vehicleId);
							
							if(locs[scope.gIndex].vehicleLocations[i].vehicleId==scope.vehicleno){
								scope.endlatlong = new google.maps.LatLng(lat, lng);
								if(locs[scope.gIndex].vehicleLocations[i].isOverSpeed=='N'){
									var strokeColorvar = '#00b3fd';
								}else{
									var strokeColorvar = '#ff0000';
								}
								scope.polylinelive = new google.maps.Polyline({
						            map: scope.map,
						            path: [scope.startlatlong, scope.endlatlong],
						            strokeColor: strokeColorvar,
						            strokeOpacity: 0.7,
						            strokeWeight: 5
						        });
						        scope.polylinelivearr.push(scope.polylinelive);
						    	scope.startlatlong = scope.endlatlong;		
							}
						}
						//google.maps.event.trigger(document.getElementById('	maploc'), "resize");
						if(scope.selected>-1 && gmarkers[scope.selected]!=undefined){
							scope.map.setCenter(gmarkers[scope.selected].getPosition()); 	
						}
					});	
									
				}, 10000);
    		});
	    }
	};
});
$(window).load(function() {
		$('#status').fadeOut(); 
		$('#preloader').delay(350).fadeOut('slow');
		$('body').delay(350).css({'overflow':'visible'});
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
	$('.contentbClose').click(function(){ $('.bottomContent').fadeOut(100); });
	
    var gaugeOptions = {

        chart: {
            type: 'solidgauge',
            backgroundColor:'rgba(255, 255, 255, 0)'
        },

        title: null,

        pane: {
            center: ['50%', '85%'],
            size: '200%',
            startAngle: -90,
            endAngle: 90,
            background: {
                innerRadius: '60%',
                outerRadius: '100%',
                shape: 'arc'
            }
        },
        tooltip: {
            enabled: false
        },
        yAxis: {
            stops: [
                [0.1, '#55BF3B'], 
                [0.5, '#DDDF0D'], 
                [0.9, '#DF5353'] 
            ],
            lineWidth: 0,
            minorTickInterval: null,
            tickPixelInterval: 400,
            tickWidth: 0,
            title: {
                y: -50
            },
            labels: {
                y: -100
            }
        },

        plotOptions: {
            solidgauge: {
                dataLabels: {
                    y: 5,
                    borderWidth: 0,
                    useHTML: true
                }
            }
        }
    };

    // The speed gauge
    $('#container-speed').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
            min: 0,
            max: 100,
            title: {
                text: ''
            }
        },

        credits: {
            enabled: false
        },

        series: [{
            name: 'Speed',
            data: [total],
            dataLabels: {
                format: '<div style="text-align:center"><span style="font-size:12px; font-weight:normal;color: #196481'+ '">Speed - {y} km</span><br/>',
                 y: 25
            },
            tooltip: {
                valueSuffix: ' km/h'
            }
        }]

    }));
  // Bring life to the dials
    setInterval(function () {
        // Speed
        var chart = $('#container-speed').highcharts(), point;

        if (chart) {
            point = chart.series[0].points[0];
            point.update(total);
        }

        
    }, 1000);

});
