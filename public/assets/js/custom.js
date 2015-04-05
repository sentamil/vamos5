//comment by satheesh ++...
var setintrvl;
app.controller('mainCtrl',['$scope', '$http','vamoservice', function($scope, $http, vamoservice){
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
	$scope.map =  null;
	$scope.flightpathall = []; 
	$scope.clickflag = false;
	$scope.flightPath = new google.maps.Polyline();
	$scope.trafficLayer = new google.maps.TrafficLayer();
	$scope.checkVal=false;
	$scope.clickflagVal =0;
	$scope.nearbyflag = false;
	
	var tempdistVal = 0;
	
	$scope.locations01 = vamoservice.getDataCall($scope.url)
						
	$scope.$watch("url", function (val) {
		vamoservice.getDataCall($scope.url).then(function(data) {
			$scope.locations = data;
			$scope.zoomLevel = parseInt(data[$scope.gIndex].zoomLevel);
		});
	});
	
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
		 for(var i=0; i<ginfowindow.length;i++){		
		 	ginfowindow[i].setMap(null);
		 }
		 ginfowindow=[];
		 clearInterval(setintrvl);
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
	    var labelAnchorpos = new google.maps.Point(12, 50);	
		$scope.marker = new MarkerWithLabel({
		   position: myLatlng, 
		   map: $scope.map,
		   icon: vamoservice.iconURL(pos.data),
		   labelContent: pos.data.shortName,
		   labelAnchor: labelAnchorpos,
		   labelClass: "labels", 
		   labelInBackground: false
		 });
		
		if(pos.data.vehicleId==$scope.vehicleno){
			$scope.assignValue(pos.data);
		    $scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
				$('#lastseen').text(count);
				var t = vamoservice.geocodeToserver(pos.data.latitude,pos.data.longitude,count);
									
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
			$scope.assignValue(pos.data);
			console.log(pos.data.address);
			$scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
				$('#lastseen').text(count); 
				var t = vamoservice.geocodeToserver(pos.data.latitude,pos.data.longitude,count);
									
			});
			if($scope.selected!=undefined){
				$scope.map.setCenter(gmarkers[$scope.selected].getPosition()); 	
			}
       });
	}
	$scope.assignValue=function(dataVal){
		
			$('#vehiid span').text(dataVal.vehicleId + " (" +dataVal.shortName+")");
				$('#toddist span span').text(dataVal.distanceCovered);
				$('#vehstat span').text(dataVal.position);
				total = parseInt(dataVal.speed);
				$('#vehdevtype span').text(dataVal.odoDistance);
				$('#mobno span').text(dataVal.overSpeedLimit);
				
				$('#positiontime').text(vamoservice.statusTime(dataVal).tempcaption);
				$('#regno span').text(vamoservice.statusTime(dataVal).temptime);
				$('#lstseendate').html('<strong>Last Seen Date & time :</strong> '+ dataVal.lastSeen);	
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
	
	$scope.initilize = function(ID){
		vamoservice.getDataCall($scope.url).then(function(data) {
			var locs = data;
			if($('.nav-second-level li').eq($scope.selected).children('a').hasClass('active')){
			}else{
				$('.nav-second-level li').eq($scope.selected).children('a').addClass('active');
			}
			
			$scope.locations = data;
			$scope.polylinelive.setMap(null);
			$scope.assignHeaderVal(data);
			
			var lat = locs[$scope.gIndex].latitude;
			var lng = locs[$scope.gIndex].longitude;
			var myOptions = { zoom: $scope.zoomLevel, center: new google.maps.LatLng(lat, lng), mapTypeId: google.maps.MapTypeId.ROADMAP};
			$scope.map = new google.maps.Map(document.getElementById(ID), myOptions);
			
			var input = document.getElementById('pac-input');
			var searchBox = new google.maps.places.SearchBox(input);
			
			google.maps.event.addListener(searchBox, 'places_changed', function() {
				var places = searchBox.getPlaces();
				if (places.length == 0) { return; }
				var bounds = new google.maps.LatLngBounds();
				for (var i = 0, place; place = places[i]; i++) {
					bounds.extend(place.geometry.location);
				}
				$scope.map.fitBounds(bounds);
				$scope.map.setZoom(13);
			});
			
			google.maps.event.addListener($scope.map, 'click', function(event) {
				if($scope.clickflag==true){
					if($scope.clickflagVal ==0){
						$scope.firstLoc = event.latLng;
						$scope.clickflagVal =1;
					}else if($scope.clickflagVal==1){
						$scope.drawLine($scope.firstLoc, event.latLng);
						$scope.firstLoc = event.latLng;
					}
				}else if($scope.nearbyflag==true){
					$('#status02').show(); 
					$('#preloader02').show(); 
					var tempurl = 'http://'+globalIP+'/vamo/public/getNearByVehicles?lat='+event.latLng.lat()+'&lng='+event.latLng.lng();
					$http.get(tempurl).success(function(data){
						$scope.nearbyLocs = data;
						$('#status02').fadeOut(); 
						$('#preloader02').delay(350).fadeOut('slow');
						if($scope.nearbyLocs.fromAddress==''){}else{
							$('.nearbyTable').delay(350).show();
						}
					});
				}
			});
			google.maps.event.addListener($scope.map, 'bounds_changed', function() {
				var bounds = $scope.map.getBounds();
				searchBox.setBounds(bounds);
			});
			$scope.startlatlong= new google.maps.LatLng();
			$scope.endlatlong= new google.maps.LatLng();
			var length = locs[$scope.gIndex].vehicleLocations.length;
			for (var i = 0; i < length; i++) {
				var lat = locs[$scope.gIndex].vehicleLocations[i].latitude;
				var lng =  locs[$scope.gIndex].vehicleLocations[i].longitude;
				$scope.addMarker({ lat: lat, lng: lng , data: locs[$scope.gIndex].vehicleLocations[i]});
				$scope.infoBoxed($scope.map,gmarkers[i], locs[$scope.gIndex].vehicleLocations[i].vehicleId);
				if(locs[$scope.gIndex].vehicleLocations[i].vehicleId==$scope.vehicleno){
					$scope.endlatlong = new google.maps.LatLng(lat, lng);
					$scope.startlatlong = new google.maps.LatLng(lat, lng);
					if(locs[$scope.gIndex].vehicleLocations[i].isOverSpeed=='N'){
						var strokeColorvar = '#00b3fd';
					}else{
						var strokeColorvar = '#ff0000';
					}
					$scope.polylinelive = new google.maps.Polyline({
			            map: $scope.map,
			            path: [$scope.startlatlong, $scope.endlatlong],
			            strokeColor: strokeColorvar,
			            strokeOpacity: 0.7,
			            strokeWeight: 5
			        });
			    	$scope.startlatlong = $scope.endlatlong;
				}
			}
		});
		if($scope.selected>-1 && gmarkers[$scope.selected]!=undefined){
			$scope.map.setCenter(gmarkers[$scope.selected].getPosition()); 	
		}
		$(document).on('pageshow', '#maploc', function(e, data){       
        	google.maps.event.trigger(document.getElementById('	maploc'), "resize");
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
				$scope.assignValue(temp[i]);
				$scope.getLocation(temp[i].latitude, temp[i].longitude, function(count){ 
					$('#lastseen').text(count); 
					
				});
				var url = 'http://'+globalIP+'/vamo/public/getGeoFenceView?vehicleId='+$scope.vehicleno;
				$scope.createGeofence(url);
			}
		}
	};
	$scope.assignHeaderVal = function(data){
		$scope.locations = data;
		$scope.distanceCovered =data[$scope.gIndex].distance;
		$scope.alertstrack = data[$scope.gIndex].alerts;
		$scope.totalVehicles  =data[$scope.gIndex].totalVehicles;
		$scope.attention  =data[$scope.gIndex].attention;
		$scope.vehicleOnline =data[$scope.gIndex].online;
	}
}]).directive('ngEnter', function () {
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
        		scope.initilize(attrs.id);
			});
			scope.$watch("url", function(val) {
				setintrvl = setInterval(function() {
					vamoservice.getDataCall(scope.url).then(function(data) {
						
						var locs = data;
						
						scope.assignHeaderVal(data);
						ginfowindow=[];
						console.log(gmarkers.length);
						for (var i = 0; i < gmarkers.length; i++) {
							var temp = locs[scope.gIndex].vehicleLocations[i];
							var lat = temp.latitude;
							var lng =  temp.longitude;
							var latlng = new google.maps.LatLng(lat,lng);
							
							gmarkers[i].icon = vamoservice.iconURL(temp);
							//gmarkers[i].setShape();
							gmarkers[i].setPosition(latlng);
							gmarkers[i].setMap(scope.map);
							if(temp.vehicleId==scope.vehicleno){
								scope.assignValue(temp);
								scope.getLocation(lat, lng, function(count){
									$('#lastseen').text(count);
									var t = vamoservice.geocodeToserver(lat,lng,count);
								});		
							}
							
							google.maps.event.addListener(gmarkers[i], "click", function(e){	
								scope.vehicleno = temp.vehicleId;
								scope.startlatlong= new google.maps.LatLng();
								scope.endlatlong= new google.maps.LatLng();
								for(var i=0; i<scope.polylinelive.length;i++){		
									 scope.polylinelive[i].setMap(null);
								}
								if(temp.vehicleId==scope.vehicleno){
									scope.assignValue(temp);
									
									scope.getLocation(lat, lng, function(count){
										$('#lastseen').text(count);
										var t = vamoservice.geocodeToserver(lat,lng,count);
									});		
								}
								
								if(scope.selected!=undefined){
									scope.map.setCenter(gmarkers[scope.selected].getPosition()); 	
								}
				        	});
						}
						var length = locs[scope.gIndex].vehicleLocations.length;
						for (var i = 0; i < length; i++) {
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

    $('#container-speed').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
            min: 0,
            max: 100,
            title: { text: '' }
        },
        credits: { enabled: false },
        series: [{
            name: 'Speed',
            data: [total],
            dataLabels: {
                format: '<div style="text-align:center"><span style="font-size:12px; font-weight:normal;color: #196481'+ '">Speed - {y} km</span><br/>',
                 y: 25
            },
            tooltip: { valueSuffix: ' km/h'}
        }]
    }));
    setInterval(function () {
      var chart = $('#container-speed').highcharts(), point;
        if (chart) {
            point = chart.series[0].points[0];
            point.update(total);
        }
    }, 1000);
});

/**
 * JavaScript Client Detection
 * (C) viazenetti GmbH (Christian Ludwig)
 */
(function (window) {
    {
        var unknown = '-';

        // screen
        var screenSize = '';
        if (screen.width) {
            width = (screen.width) ? screen.width : '';
            height = (screen.height) ? screen.height : '';
            screenSize += '' + width + " x " + height;
        }

        //browser
        var nVer = navigator.appVersion;
        var nAgt = navigator.userAgent;
        var browser = navigator.appName;
        var version = '' + parseFloat(navigator.appVersion);
        var majorVersion = parseInt(navigator.appVersion, 10);
        var nameOffset, verOffset, ix;

        // Opera
        if ((verOffset = nAgt.indexOf('Opera')) != -1) {
            browser = 'Opera';
            version = nAgt.substring(verOffset + 6);
            if ((verOffset = nAgt.indexOf('Version')) != -1) {
                version = nAgt.substring(verOffset + 8);
            }
        }
        // MSIE
        else if ((verOffset = nAgt.indexOf('MSIE')) != -1) {
            browser = 'Microsoft Internet Explorer';
            version = nAgt.substring(verOffset + 5);
        }
        // Chrome
        else if ((verOffset = nAgt.indexOf('Chrome')) != -1) {
            browser = 'Chrome';
            version = nAgt.substring(verOffset + 7);
        }
        // Safari
        else if ((verOffset = nAgt.indexOf('Safari')) != -1) {
            browser = 'Safari';
            version = nAgt.substring(verOffset + 7);
            if ((verOffset = nAgt.indexOf('Version')) != -1) {
                version = nAgt.substring(verOffset + 8);
            }
        }
        // Firefox
        else if ((verOffset = nAgt.indexOf('Firefox')) != -1) {
            browser = 'Firefox';
            version = nAgt.substring(verOffset + 8);
        }
        // MSIE 11+
        else if (nAgt.indexOf('Trident/') != -1) {
            browser = 'Microsoft Internet Explorer';
            version = nAgt.substring(nAgt.indexOf('rv:') + 3);
        }
        // Other browsers
        else if ((nameOffset = nAgt.lastIndexOf(' ') + 1) < (verOffset = nAgt.lastIndexOf('/'))) {
            browser = nAgt.substring(nameOffset, verOffset);
            version = nAgt.substring(verOffset + 1);
            if (browser.toLowerCase() == browser.toUpperCase()) {
                browser = navigator.appName;
            }
        }
        // trim the version string
        if ((ix = version.indexOf(';')) != -1) version = version.substring(0, ix);
        if ((ix = version.indexOf(' ')) != -1) version = version.substring(0, ix);
        if ((ix = version.indexOf(')')) != -1) version = version.substring(0, ix);

        majorVersion = parseInt('' + version, 10);
        if (isNaN(majorVersion)) {
            version = '' + parseFloat(navigator.appVersion);
            majorVersion = parseInt(navigator.appVersion, 10);
        }

        // mobile version
        var mobile = /Mobile|mini|Fennec|Android|iP(ad|od|hone)/.test(nVer);

        // cookie
        var cookieEnabled = (navigator.cookieEnabled) ? true : false;

        if (typeof navigator.cookieEnabled == 'undefined' && !cookieEnabled) {
            document.cookie = 'testcookie';
            cookieEnabled = (document.cookie.indexOf('testcookie') != -1) ? true : false;
        }

        // system
        var os = unknown;
        var clientStrings = [
            {s:'Windows 3.11', r:/Win16/},
            {s:'Windows 95', r:/(Windows 95|Win95|Windows_95)/},
            {s:'Windows ME', r:/(Win 9x 4.90|Windows ME)/},
            {s:'Windows 98', r:/(Windows 98|Win98)/},
            {s:'Windows CE', r:/Windows CE/},
            {s:'Windows 2000', r:/(Windows NT 5.0|Windows 2000)/},
            {s:'Windows XP', r:/(Windows NT 5.1|Windows XP)/},
            {s:'Windows Server 2003', r:/Windows NT 5.2/},
            {s:'Windows Vista', r:/Windows NT 6.0/},
            {s:'Windows 7', r:/(Windows 7|Windows NT 6.1)/},
            {s:'Windows 8.1', r:/(Windows 8.1|Windows NT 6.3)/},
            {s:'Windows 8', r:/(Windows 8|Windows NT 6.2)/},
            {s:'Windows NT 4.0', r:/(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/},
            {s:'Windows ME', r:/Windows ME/},
            {s:'Android', r:/Android/},
            {s:'Open BSD', r:/OpenBSD/},
            {s:'Sun OS', r:/SunOS/},
            {s:'Linux', r:/(Linux|X11)/},
            {s:'iOS', r:/(iPhone|iPad|iPod)/},
            {s:'Mac OS X', r:/Mac OS X/},
            {s:'Mac OS', r:/(MacPPC|MacIntel|Mac_PowerPC|Macintosh)/},
            {s:'QNX', r:/QNX/},
            {s:'UNIX', r:/UNIX/},
            {s:'BeOS', r:/BeOS/},
            {s:'OS/2', r:/OS\/2/},
            {s:'Search Bot', r:/(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/}
        ];
        for (var id in clientStrings) {
            var cs = clientStrings[id];
            if (cs.r.test(nAgt)) {
                os = cs.s;
                break;
            }
        }

        var osVersion = unknown;

        if (/Windows/.test(os)) {
            osVersion = /Windows (.*)/.exec(os)[1];
            os = 'Windows';
        }

        switch (os) {
            case 'Mac OS X':
                osVersion = /Mac OS X (10[\.\_\d]+)/.exec(nAgt)[1];
                break;

            case 'Android':
                osVersion = /Android ([\.\_\d]+)/.exec(nAgt)[1];
                break;

            case 'iOS':
                osVersion = /OS (\d+)_(\d+)_?(\d+)?/.exec(nVer);
                osVersion = osVersion[1] + '.' + osVersion[2] + '.' + (osVersion[3] | 0);
                break;
        }
        
        // flash (you'll need to include swfobject)
        /* script src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js" */
        var flashVersion = 'no check';
        if (typeof swfobject != 'undefined') {
            var fv = swfobject.getFlashPlayerVersion();
            if (fv.major > 0) {
                flashVersion = fv.major + '.' + fv.minor + ' r' + fv.release;
            }
            else  {
                flashVersion = unknown;
            }
        }
    }

    window.jscd = {
        screen: screenSize,
        browser: browser,
        browserVersion: version,
        mobile: mobile,
        os: os,
        osVersion: osVersion,
        cookies: cookieEnabled,
        flashVersion: flashVersion
    };
}(this));
if(jscd.os=='Mac OS X'){}else{
	//$('body').addClass('zoomMS');
}
 