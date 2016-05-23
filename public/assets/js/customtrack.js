app.directive('map', function($http, vamoservice) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {
           vamoservice.getDataCall(scope.url).then(function(data) {
		   		var locs = data;
		   		var myOptions = {
					zoom: 13,zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
					center: new google.maps.LatLng(locs.latitude, locs.longitude),
					mapTypeId: google.maps.MapTypeId.ROADMAP/*,
					styles: [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}] */
            	};
            	scope.map = new google.maps.Map(document.getElementById(attrs.id), myOptions);
				google.maps.event.addListener(scope.map, 'click', function(event) {
								scope.clickedLatlng = event.latLng.lat() +','+ event.latLng.lng();
								$('#latinput').val(scope.clickedLatlng);
							});
				$('#vehiid span').text(locs.shortName);
				$('#toddist span span').text(locs.distanceCovered);
				// total = parseInt(locs.speed);
				$('#vehdevtype span').text(locs.odoDistance);
				$('#mobno span').text(locs.overSpeedLimit);
				

				$('#graphsId #speed').text(locs.speed);
				$('#graphsId #fuel').text(locs.tankSize);
				tankSize 		 = parseInt(locs.tankSize);
				fuelLtr 		 = parseInt(locs.fuelLitre);
				total  			 = parseInt(locs.speed);
				


				scope.getLocation(locs.latitude, locs.longitude, function(count){
					$('#lastseentrack').text(count); 
					// var t = vamoservice.geocodeToserver(locs.latitude, locs.longitude, count);
									
				});
				
				$('#positiontime').text(vamoservice.statusTime(locs).tempcaption);
				$('#regno span').text(vamoservice.statusTime(locs).temptime);
				
				
			   	scope.speedval.push(data.speed);
           		scope.path.push(new google.maps.LatLng(data.latitude, data.longitude));
				var labelAnchorpos = new google.maps.Point(0, 0);
				var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
				
				scope.startlatlong = new google.maps.LatLng(data.latitude, data.longitude);
				scope.endlatlong = new google.maps.LatLng(data.latitude, data.longitude);
				
				
				scope.marker = new MarkerWithLabel({
			   		position: myLatlng, 
				   map: scope.map,
				   icon: vamoservice.iconURL(data),
				   labelContent: data.shortName,
				   labelAnchor: labelAnchorpos,
				   labelClass: "labels", 
				   labelInBackground: false
				});
		 		if(scope.path.length>1){
			 		var latLngBounds = new google.maps.LatLngBounds();
					latLngBounds.extend(scope.path[scope.path.length-1]);
				}
				

				function lineDraw(lat, lan){
					// if(data.isOverSpeed=='N'){
					// 	var strokeColorvar = '#00b3fd';
					// }else{
					// 	var strokeColorvar = '#ff0000';
					// }
					// var latlng1 = new google.maps.LatLng(lat, lan);
					// console.log(' value -->'+ latlng1);
					scope.slatlong = new google.maps.LatLng(lat, lan);
					
					scope.polyline = new google.maps.Polyline({
								map: scope.map,
								path: [scope.elatlong, scope.slatlong],
								strokeColor: '#00b3fd',
								strokeOpacity: 0.7,
								strokeWeight: 5,
								
								clickable: true
						  	});
					scope.elatlong = scope.slatlong;
				}
			    // scope.startlatlong = scope.endlatlong;




			    
			    (function latlan(){
			    	vamoservice.getDataCall(scope.urlVeh).then(function(response) {
			    		if(response.latLngOld != undefined)
				    		for (var i = 0; i < response.latLngOld.length; i++) {
				    			sp = response.latLngOld[i].split(',');
				    			lineDraw(sp[0], sp[1]);
				    		};
			    		});
			    }());	
			    // vamoservice.getDataCall(uro).then(function(response) {
			    // 	if(response.latLngOld.length)
			    // 		console.log(' response '+response);
			    // })

			    var contentString = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
						+'<div style="width:200px; display:inline-block;"><b >Address</b> - <span>'+data.address+'</span></div></div>';

				var infowindow = new google.maps.InfoWindow({
				    content: contentString
				});


				google.maps.event.addListener(scope.marker, "click", function(e) {
					infowindow.open(scope.map, scope.marker);
				});
		  });
		  $(document).on('pageshow', '#maploc', function(e, data){       
                	google.maps.event.trigger(document.getElementById('	maploc'), "resize");
           		 });
		  	
		  setInterval(function() {
		   		vamoservice.getDataCall(scope.url).then(function(data) {
		   			var locs = data;
					// var myOptions = {
					// 	zoom: 13,
					// 	center: new google.maps.LatLng(locs.latitude, locs.longitude),
					// 	mapTypeId: google.maps.MapTypeId.ROADMAP
	    //         	};
            		
            		$('#vehiid span').text(locs.shortName);
					$('#toddist span span').text(locs.distanceCovered);
					$('#vehstat span').text(locs.position);
					// total = parseInt(locs.speed);
					$('#vehdevtype span').text(locs.odoDistance);
					$('#mobno span').text(locs.overSpeedLimit);
					
					$('#graphsId #speed').text(locs.speed);
					$('#graphsId #fuel').text(locs.tankSize);
					tankSize 		 = parseInt(locs.tankSize);
					fuelLtr 		 = parseInt(locs.fuelLitre);
					total  			 = parseInt(locs.speed);

					$('#positiontime').text(vamoservice.statusTime(locs).tempcaption);
					$('#regno span').text(vamoservice.statusTime(locs).temptime);
					scope.getLocation(locs.latitude, locs.longitude, function(count){
						$('#lastseentrack').text(count); 
						var t = vamoservice.geocodeToserver(locs.latitude, locs.longitude, count);
									
					});
           			
           			scope.path.push(new google.maps.LatLng(data.latitude, data.longitude));
					
					if(scope.path.length>1){
					 	var latLngBounds = new google.maps.LatLngBounds();
						latLngBounds.extend(scope.path[scope.path.length-1]);
					}
					var labelAnchorpos = new google.maps.Point(0, 0);
					
					var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
					scope.marker.setMap(null);
					// scope.map.setCenter(scope.marker.getPosition());
					
					
					scope.marker = new MarkerWithLabel({
					   position: myLatlng, 
					   map: scope.map,
					   icon: vamoservice.iconURL(data),
					   labelContent: data.shortName,
					   labelAnchor: labelAnchorpos,
					   labelClass: "labels",
					   labelInBackground: false
					});
					
					var contentString = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
						+'<div style="width:200px; display:inline-block;"><b>Address</b> - <span>'+data.address+'</span></div></div>';

					var infowindow = new google.maps.InfoWindow({
					    content: contentString
					});


					google.maps.event.addListener(scope.marker, "click", function(e) {
						infowindow.open(scope.map, scope.marker);
					});
					
					scope.endlatlong = new google.maps.LatLng(data.latitude, data.longitude);
					
				  	if(data.isOverSpeed=='N'){
						var strokeColorvar = '#00b3fd';
					}else{
						var strokeColorvar = '#ff0000';
					}
         			
				  	var polyline = new google.maps.Polyline({
			            map: scope.map,
			            path: [scope.startlatlong, scope.endlatlong],
			            strokeColor: strokeColorvar,
			            strokeOpacity: 0.7,
			            strokeWeight: 5
			        });
			        
			        scope.startlatlong = scope.endlatlong;
			        google.maps.event.trigger(document.getElementById('maploc'), "resize");
		   		
		   		});
		   }, 10000);
        }
    };
});
app.controller('mainCtrl',function($scope, $http, vamoservice){ 
	var res = document.location.href.split("?");
	$scope.vehicleno = res[1].trim();
	$scope.url = 'http://'+globalIP+context+'/public/getSelectedVehicleLocation?'+res[1];
	$scope.urlVeh = 'http://'+globalIP+context+'/public/getSelectedVehicleLocation1?'+res[1];
	$scope.path = [];
	$scope.speedval =[];
	$scope.inter = 0;
	$scope.cityCircle=[];
	$scope.cityCirclecheck=false;
	vamoservice.getDataCall($scope.url).then(function(data) {
		$scope.locations = data;
		var url = 'http://'+globalIP+context+'/public//getGeoFenceView?'+res[1];
				$scope.createGeofence(url)
	});
    $scope.addMarker= function(pos){
	   var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
	   var marker = new google.maps.Marker({
			position: myLatlng, 
			map: $scope.map
		});
	}

	

	$scope.getLocation = function(lat,lon, callback){
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(lat, lon);
		geocoder.geocode({'latLng': latlng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
			  if (results[1]) {
				if(typeof callback === "function") callback(results[1].formatted_address)
			  } else {
				//alert('No results found');
			  }
			} else {
			}
		  });
    };
	$scope.enterkeypress = function(){
		var url = 'http://'+globalIP+context+'/public//setPOIName?vehicleId='+$scope.vehicleno+'&poiName='+document.getElementById('poival').value;
		if(document.getElementById('poival').value=='' || $scope.vehicleno==''){}else{
			vamoservice.getDataCall(url).then(function(data) {
			 	document.getElementById('poival').value='';
			});
		}
	}
	$scope.trafficLayer = new google.maps.TrafficLayer();
	$scope.checkVal=false;
	$scope.clickflagVal =0;
	$scope.check = function(){
		if($scope.checkVal==false){
			$scope.trafficLayer.setMap($scope.map);
			$scope.checkVal = true;
		}else{
			$scope.trafficLayer.setMap(null);
			$scope.checkVal = false;
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
		}
		vamoservice.getDataCall(url).then(function(data) {
			$scope.geoloc = data;
			if (typeof(data.geoFence) !== 'undefined' && data.geoFence.length) {	
				//alert(data.geoFence[0].proximityLevel);
				for(var i=0; i<data.geoFence.length; i++){
					if(data.geoFence[i]!=null){
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
					var image = 'assets/imgs/busgeo.png';
				  
				  	
				  
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
			}
		})
	}          
});

$(document).ready(function(e) {
	$('#container-speed').highcharts({
	
	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false,
	        spacingBottom: 10,
	        spacingTop: -60,
	        spacingLeft: -20,
	        spacingRight: -20,
	    },
	    
	    title: {
	        text: ''
	    },
	    
	    pane: {
	        startAngle: -90,
	        endAngle: 90,
	        center:['50%', '100%'],
	        size: '100%',
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
	    credits: { enabled: false },
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
	            // text: 'km/h'
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
	
	});


    var gaugeOptions = {
        chart: {
            type: 'solidgauge',
            // backgroundColor:'rgba(255, 255, 255, 0)',
            spacingBottom: -10,
	        spacingTop: -40,
	        spacingLeft: 0,
	        spacingRight: 0,
        },
        title: null,
        pane: {
            center: ['50%', '90%'],
            size: '110%',
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

    $('#container-fuel').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
            min: 0,
            max: 300,
            title: { text: '' }
        },
        credits: { enabled: false },
        series: [{
            name: 'Speed',
            data: [fuelLtr],
            dataLabels: {
                format: '<div style="text-align:center"><span style="font-size:12px; font-weight:normal;color: #196481'+ '">Fuel - {y} Ltr</span><br/>',
                 // y: 25
            },
            tooltip: { valueSuffix: ' Ltr'}
        }]
    }));
    setInterval(function () {
      var chart = $('#container-speed').highcharts(), point;
        if (chart) {
            point = chart.series[0].points[0];
            point.update(total);
        }
       var chartFuel = $('#container-fuel').highcharts(), point;
        if (chartFuel) {
            point = chartFuel.series[0].points[0];
            point.update(fuelLtr);
            if(tankSize==0)
            	tankSize =200;
            chartFuel.yAxis[0].update({
			    max: tankSize,
			}); 

        }
    }, 1000);
});
 
