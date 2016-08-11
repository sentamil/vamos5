// var app = angular.module('mapApp', []);
var gmarkers=[];
var ginfowindow=[];
var gsmarker=[];
var gsinfoWindow=[];

var geoinfowindow=[];
var contentString = [];
var contentString01=[];
var id;
var geomarker=[], geoinfo=[];
app.directive('map', function($http) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {
        	scope.path=[];
        	function utcdateConvert(milliseconds){
				//var milliseconds=1440700484003;
				
				var offset='+10';
				var d = new Date(milliseconds);
				utc = d.getTime() + (d.getTimezoneOffset() * 60000);
				nd = new Date(utc + (3600000*offset));
				var result=nd.toLocaleString();
				return result;
			}
		 	scope.$watch("hisurl", function (val) {
		 		scope.startLoading(); 
		   		$http.get(scope.hisurl).success(function(data){
		   			var locs = data;
					scope.hisloc = locs;
					
					if(data.fromDateTime=='' || data.fromDateTime==undefined || data.fromDateTime=='NaN-aN-aN'){ 
						if(data.error==null){}else{
							$('.alert-danger').show();
							$('#myModal').modal();
						}
						$('#lastseen').html('<strong>From Date & time :</strong> -');
						$('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');
					}else{
						$('.alert-danger').hide();
						if(data.error==null){
							var fromNow =new Date(data.fromDateTime.replace('IST',''));
							var toNow 	= new Date(data.toDateTime.replace('IST',''));
							scope.fromNowTS		    =	fromNow.getTime();
							scope.toNowTS			=	toNow.getTime();	
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
							scope.fromtime			=	formatAMPM(scope.fromNowTS);
   							scope.totime			=	formatAMPM(scope.toNowTS);
							scope.fromdate			=	scope.getTodayDate(scope.fromNowTS);
							scope.todate			=	scope.getTodayDate(scope.toNowTS);
							
							$('#vehiid h3').text(locs.shortName);
							$('#toddist h3').text(scope.timeCalculate(locs.totalRunningTime));
							$('#vehstat h3').text(scope.timeCalculate(locs.totalIdleTime));
							$('#vehdevtype h3 span').text(locs.odoDistance);
							$('#mobno h3').text(scope.timeCalculate(locs.totalParkedTime));
							$('#regno h3 span').text(locs.tripDistance);
							
							$('#lastseen').html('<strong>From Date & time :</strong> '+ new Date(data.fromDateTimeUTC).toString().split('GMT')[0]);
							$('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> '+ new Date(data.toDateTimeUTC).toString().split('GMT')[0]);
							
							var myOptions = {
								zoom: Number(locs.zoomLevel),zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
								center: new google.maps.LatLng(data.vehicleLocations[0].latitude, data.vehicleLocations[0].longitude),
								mapTypeId: google.maps.MapTypeId.ROADMAP
								//styles: [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]
								
							
							};
	            			scope.map = new google.maps.Map(document.getElementById(attrs.id), myOptions);
/*
	            			 var input01 = (document.getElementById('pac-input'));
  							scope.map.controls[google.maps.ControlPosition.TOP_LEFT].push(input01);
  							var searchBox = new google.maps.places.SearchBox((input01));
  							
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
							  });
  							google.maps.event.addListener(scope.map, 'bounds_changed', function() {
							    var bounds = scope.map.getBounds();
							    searchBox.setBounds(bounds);
							  });
*/							


							
							//draw the geo code
							// (function geosite(){
								var geoUrl = 'http://'+globalIP+context+'/public/viewSite';
								// var myOptions = {
								// 	zoom: Number(6),zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
								// 	center: new google.maps.LatLng(0,0),
								// 	mapTypeId: google.maps.MapTypeId.ROADMAP
			
			
		
								// };
								// $scope.map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
								$http.get(geoUrl).success(function(response){
									polygenList =[];
									var latLanlist, seclat, seclan, sp; 

									function centerMarker(listMarker){
									    var bounds = new google.maps.LatLngBounds();
									    for (i = 0; i < listMarker.length; i++) {
									          bounds.extend(listMarker[i]);
									      }
									    return bounds.getCenter()
									}

									for (var listSite = 0; listSite < response.siteParent.length; listSite++) {
										
										var len = response.siteParent[listSite].site.length;
										for (var k = 0; k < len; k++) {
											console.log(' value  '+k);
										// if(response.siteParent[i].site.length)
										// {
											var orgName = response.siteParent[listSite].site[k].siteName;
											var splitLatLan = response.siteParent[listSite].site[k].latLng.split(",");
											
											polygenList = [];
											for(var j = 0; splitLatLan.length>j; j++)
								      		{
								      			sp 	  = splitLatLan[j].split(":");
								      			polygenList.push(new google.maps.LatLng(sp[0], sp[1]));
								      			// console.log(sp[0]+' ---- '+ sp[1])
								      			// latlanList.push(sp[0]+":"+sp[1]);
								      			// seclat        = sp[0];
								      			// seclan        = sp[1];
								      		}
											
								      		var polygenColor = new google.maps.Polygon({
								      			path: polygenList,
								      			strokeColor: "#282828",
								      			strokeWeight: 1,
								      			fillColor: '#808080',
								      			fillOpacity: 0.50,
								      			map: scope.map
								      		});
								      	
									      	var labelAnchorpos = new google.maps.Point(19, 0);  ///12, 37
									      	var marker = new MarkerWithLabel({
										      	position: centerMarker(polygenList), 
										      	map: scope.map,
										      	icon: 'assets/imgs/area_img.png',
										      	labelContent: orgName,
										      	labelAnchor: labelAnchorpos,
										      	labelClass: "labels", 
										      	labelInBackground: false
									      	});
									      // scope.map.setCenter(centerMarker(polygenList)); 
									      // scope.map.setZoom(14); 
								  		// }
								  		}
									};
								})
							// }());



							google.maps.event.addListener(scope.map, 'click', function(event) {
								scope.clickedLatlng = event.latLng.lat() +','+ event.latLng.lng();
								$('#latinput').val(scope.clickedLatlng);
							});
						    for(var i=0;i<locs.vehicleLocations.length;i++){
						   		scope.path.push(new google.maps.LatLng(locs.vehicleLocations[i].latitude, locs.vehicleLocations[i].longitude));
						   		
						   	  	if(locs.vehicleLocations[i].isOverSpeed=='Y'){
								   	    	var pscolorval = '#ff0000';
								   	   }else{
								   	   		var pscolorval = '#068b03';
								   	   }
						   	  scope.polylinearr.push(pscolorval);
						   	  
						   	  if(locs.vehicleLocations[i].mileKal=='Y'){
						   	  		scope.pointMarker(locs.vehicleLocations[i]);
						   	    	scope.pointinfowindow(scope.map, gsmarker[i], locs.vehicleLocations[i]);
						   	   }
					   		}
		  	  				var latLngBounds = new google.maps.LatLngBounds();
			  				var j=0;
			  				 var tempFlag=false;
			  				 for(var k=0;k<locs.vehicleLocations.length;k++){
				  				 if(locs.vehicleLocations[k].position =='M' && tempFlag==false){
				  				 	var firstval = k;
				  				 	
				  					 tempFlag=true;
							  	 }
						  	 }
					  			if(firstval==undefined){
					  				firstval=0;
					  			}
					  			
						  		for(var i = 0; i < scope.path.length; i++) {
									latLngBounds.extend(scope.path[i]);
									if(locs.vehicleLocations[i].position!=undefined){
										if(locs.vehicleLocations[i].position=='P' || locs.vehicleLocations[i].position=='S' || locs.vehicleLocations[i].insideGeoFence=='Y' ){
											
											scope.addMarker({ lat: locs.vehicleLocations[i].latitude, lng: locs.vehicleLocations[i].longitude , data: locs.vehicleLocations[i], path:scope.path[i]});
											scope.infoBox(scope.map, gmarkers[j], locs.vehicleLocations[i]);
											j++;
										}
										
									}
						  		}
					  		
			  				var lastval = locs.vehicleLocations.length-1;
			  					 scope.addMarkerstart({ lat: locs.vehicleLocations[firstval].latitude, lng: locs.vehicleLocations[firstval].longitude , data: locs.vehicleLocations[firstval], path:scope.path[firstval]});
							  	
							scope.addMarkerend({ 
								lat: locs.vehicleLocations[lastval].latitude, 
								lng: locs.vehicleLocations[lastval].longitude, 
								data: locs.vehicleLocations[lastval], 
								path:scope.path[lastval]
							});
							 var lineSymbol = {
						        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
						        scale: 3,
						        strokeColor: '#ff0000'
						    };
						  	scope.polyline = new google.maps.Polyline({
								map: scope.map,
								path: scope.path,
								strokeColor: '#068b03',
								strokeOpacity: 0.7,
								strokeWeight: 3,
								icons: [{
						            icon: lineSymbol,
						            offset: '100%'
						        }],
								clickable: true
						  	});
						  	
						  	if(scope.path.length>1){
					   			for(var i=0;i<scope.path.length-1;i++){
					   				scope.polyline1[i] = new google.maps.Polyline({
										map: scope.map,
										path: [scope.path[i], scope.path[i+1]],
										strokeColor: scope.polylinearr[i],
										strokeOpacity: 0.7,
										strokeWeight: 2,
										
								  	});
					   			}
					   		}
						  	
						  	scope.pointDistances=[];
						  	var sphericalLib = google.maps.geometry.spherical;
						  	var pointZero = scope.path[0];
						  
    						var wholeDist = sphericalLib.computeDistanceBetween(pointZero, scope.path[scope.path.length - 1]);
						  	for (var i = 0; i < scope.path.length; i++) {
						        scope.pointDistances[i] = 100 * sphericalLib.computeDistanceBetween(scope.path[i], pointZero) / wholeDist;
						    }
						    
						    window.setTimeout(function () {
						    	scope.animated();
						    },1000);
						    
						    $('#replaybutton').removeAttr('disabled');
			  				scope.map.fitBounds(latLngBounds);	
			  				
						}else{
							$('.error').show();
							$('#lastseen').html('<strong>From Date & time :</strong> -');
							$('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');
						}
					}
					var url = 'http://'+globalIP+context+'/public//getGeoFenceView?vehicleId='+scope.trackVehID;
		
				scope.createGeofence(url);
				scope.stopLoading();
		   		}).error(function(){ });
		 	});
        }
    };
});
app.controller('mainCtrl',function($scope, $http, $q){
	$scope.locations = [];
	$scope.path = [];
	$scope.polylinearr=[];
	$scope.polyline1=[];
	$scope.tempadd01='';
	$scope.cityCircle=[];
	$scope.geoMarkerDetails={};
	$scope.url = 'http://'+globalIP+context+'/public//getVehicleLocations';
	$scope.getTodayDate  =	function(date) {
		var date = new Date(date);
		return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
	};

	//loading start function
	$scope.startLoading		= function () {
		$('#status').show(); 
		$('#preloader').show();
	};

	//loading stop function
	$scope.stopLoading		= function () {
		$('#status').fadeOut(); 
		$('#preloader').delay(350).fadeOut('slow');
		$('body').delay(350).css({'overflow':'visible'});
	};


	function sessionValue(vid, gname){
		sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
		$("#testLoad").load("../public/menu");
	}

	$scope.loading	=	true;
	
	$http.get($scope.url).success(function(data){
		
		$scope.locations = data;
		$scope.groupname = data[0].group;
		$scope.vehicleId = data[0].vehicleLocations[0].vehicleId;
		sessionValue($scope.vehicleId, $scope.groupname)
		if(getParameterByName('vehicleId')!=undefined || getParameterByName('vehicleId')!=null){
			$scope.trackVehID =$scope.locations[0].vehicleLocations[0].vehicleId;
			$scope.shortVehiId =$scope.locations[0].vehicleLocations[0].shortName;
			$scope.selected=0;
		}else{
			$scope.trackVehID =getParameterByName('vehicleId');
			for(var i=0; i<$scope.locations[0].vehicleLocations.length;i++){
				if($scope.locations[0].vehicleLocations[i].vehicleId==$scope.trackVehID){
					$scope.selected=i;
				}
			}
		}
		
		$scope.hisurl = 'http://'+globalIP+context+'/public//getVehicleHistory?vehicleId='+$scope.trackVehID;
		$('.nav-second-level li').eq(0).children('a').addClass('active');
		$scope.loading	=	false;
	}).error(function(){ /*alert('error'); */});
	$scope.trimColon = function(textVal){
		return textVal.split(":")[0].trim();
	}
	$scope.createGeofence=function(url){
		//console.log('--->'+url)
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
		var defdata = $q.defer();
		$http.get(url).success(function(data){
			//console.log(' url '+data)
			$scope.geoloc = defdata.resolve(data);
			$scope.geoStop = data;
			geomarker=[];
			if (typeof(data.geoFence) !== 'undefined' ) {	
				
				if(data.geoFence!=null){
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
					$scope.infowin(data.geoFence[i]);
					
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
		}
		});
	}
	$scope.goeValueChange = function()
	{
		//$scope.geoStops;
		//console.log(' ---- >'+$scope.geoStops)
		$scope.map.setZoom(13)
		if($scope.geoStops!=0)
		{
			for(var i = 0; i < $scope.geoStop.geoFence.length; i++)
			{
				if($scope.geoStops==$scope.geoStop.geoFence[i].stopName)
				{
					//$scope.map.setZoom(18);
					$scope.map.setCenter(geomarker[i].getPosition());
					animateMapZoomTo($scope.map, 20);
			 	}
			}
		}
	}


function animateMapZoomTo(map, targetZoom) {
    var currentZoom = arguments[2] || map.getZoom();
    if (currentZoom != targetZoom) {
        google.maps.event.addListenerOnce(map, 'zoom_changed', function (event) {
            animateMapZoomTo(map, targetZoom, currentZoom + (targetZoom > currentZoom ? 1 : -1));
        });
        setTimeout(function(){ map.setZoom(currentZoom) }, 80);
    }
}


	function smoothZoom (map, max, cnt) {
    if (cnt >= max) {
            return;
        }
    else {
        z = google.maps.event.addListener(map, 'zoom_changed', function(event){
            google.maps.event.removeListener(z);
            smoothZoom(map, max, cnt + 1);
        });
        setTimeout(function(){map.setZoom(cnt)}, 80);
    }
}  
	$scope.infowin = function(data){
		
		var centerPosition = new google.maps.LatLng(data.latitude, data.longitude);
					var labelText = data.poiName;
					var stop = data.stopName;
					var image = 'assets/imgs/busgeo.png';
				  
				  	var beachMarker = new google.maps.Marker({
				      position: centerPosition,
				      title: stop,
				      map: $scope.map,
				      icon: image
				  	});
				  	geomarker.push(beachMarker);
					// var myOptions = { content: labelText, boxStyle: {textAlign: "center", fontSize: "9pt", fontColor: "#ff0000", width: "100px"},
						// disableAutoPan: true,
						// pixelOffset: new google.maps.Size(-50, 0),
						// position: centerPosition,
						// closeBoxURL: "",
						// isHidden: false,
						// pane: "mapPane",
						// enableEventPropagation: true
					// };
					// var labelinfo = new InfoBox(myOptions);
					// labelinfo.open($scope.map);
					// labelinfo.setPosition($scope.cityCircle[i].getCenter());
					// geoinfo.push(labelinfo);
										
					var contentString = '<h3 class="infoh3">Location</h3>'
					+'<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody>'
					+'<tr><td>Latlong</td><td>'+data.geoLocation+' </td></tr>'
					+'</table></div>';
					var infoWindow = new google.maps.InfoWindow({content:contentString});
					geoinfowindow.push(infoWindow);
					
					(function(marker) {
			    		google.maps.event.addListener(beachMarker, "click", function(e) {
			    			for(var j=0; j<geoinfowindow.length;j++){
								geoinfowindow[j].close();
							}
			    			infoWindow.open($scope.map, marker);
							
			   			});	
			  		})(beachMarker);
					
		
		
	}
	
	$scope.genericFunction = function(a,b,shortName){
		$scope.path = [];
		gmarkers=[];
		ginfowindow=[];
		contentString = [];
		$scope.trackVehID = a;
		$scope.shortVehiId = shortName;
		$scope.selected = b;
		$scope.plotting();
		sessionValue($scope.trackVehID, $scope.groupname);
	}
	
	$scope.groupSelection = function(groupname, groupid){
		 $scope.selected=0;
		 $scope.url = 'http://'+globalIP+context+'/public//getVehicleLocations?group=' + groupname;
		 $scope.gIndex = groupid;
		 gmarkers=[];
		 ginfowindow=[];
		 $scope.loading	=	true;
		 
		 for(var i=0; i<gmarkers.length; i++){
				gmarkers[i].setMap(null);
			}
			if($scope.polyline){
			for(var i=0; i<$scope.polyline1.length; i++){
				$scope.polyline1[i].setMap(null);
			}
			
			
			$scope.polyline.setMap(null);
}
if($scope.markerstart){
			$scope.markerstart.setMap(null);
			$scope.markerend.setMap(null);
			$scope.path = [];
			$scope.polylinearr = [];
			gmarkers=[];
			ginfowindow=[];
			contentString = [];
			gsmarker=[];
			gsinfoWindow=[];
			window.clearInterval(id);
			$('#replaybutton').attr('disabled','disabled');
			$('#lastseen').html('<strong>From Date & time :</strong> -');
			$('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');
}
		 $http.get($scope.url).success(function(data){
			$scope.locations = data;
			if(data.length)
				$scope.vehiname	= data[$scope.gIndex].vehicleLocations[0].vehicleId;
			$scope.groupname 	= data[$scope.gIndex].group;
			$scope.trackVehID 	= $scope.locations[$scope.gIndex].vehicleLocations[$scope.selected].vehicleId;
			sessionValue($scope.trackVehID, $scope.groupname);
			$scope.hisurl = 'http://'+globalIP+context+'/public//getVehicleHistory?vehicleId='+$scope.trackVehID;
			$('.nav-second-level li').eq(0).children('a').addClass('active');
			$scope.loading	=	false;
			
			
		}).error(function(){ /*alert('error'); */});
		
	}
	$scope.pointMarker=function(data){
		var line0Symbol = {
	        path: google.maps.SymbolPath.CIRCLE,
	        scale:3,
	        strokeColor: '#0645AD',
	        strokeWeight: 5
	    };
	    
	    
	    var marker = new google.maps.Marker({
            map: $scope.map,
            position: new google.maps.LatLng(data.latitude, data.longitude),
            icon:line0Symbol
	    });
	    gsmarker.push(marker);
	    var contentString01 = '<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody></tbody></table></div>';
			var infoWindow = new google.maps.InfoWindow({content: contentString01});
			gsinfoWindow.push(infoWindow);	
			
			(function(marker) {
			    google.maps.event.addListener(marker, "click", function(e) {
			   	$scope.infowindowShow={};
				$scope.infowindowShow['dataTempVal'] =  data;
				$scope.infowindowShow['currinfo'] = infoWindow;
				$scope.infowindowShow['currmarker'] = marker;
				for(var j=0; j<gsinfoWindow.length;j++){
					gsinfoWindow[j].close();
				}
				$scope.infowindowshowFunc();
			   });	
			  })(marker);
			  
	}
	$scope.infowindowshowFunc = function(){
		for(var j=0; j<gsinfoWindow.length;j++){
			gsinfoWindow[j].close();
		}
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(+$scope.infowindowShow.dataTempVal.latitude, +$scope.infowindowShow.dataTempVal.longitude);
		geocoder.geocode({'latLng': latlng}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
					  if (results[1]) {
					  	var contentString = '<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody>'
			+'<tr><td>Speed</td><td>'+$scope.infowindowShow.dataTempVal.speed+'</td></tr>'
			+'<tr><td>date & time</td><td>'+$scope.infowindowShow.dataTempVal.lastSeen+'</td></tr>'
			+'<tr><td>trip distance</td><td>'+$scope.infowindowShow.dataTempVal.tripDistance+'</td></tr>'
			+'<tr><td style="widthpx">location</td><td style="width:100px">'+results[1].formatted_address+'</td></tr>'
			+'</tbody></table></div>';
						$scope.infowindowShow.currinfo.setContent(contentString);
						$scope.infowindowShow.currinfo.open($scope.map,$scope.infowindowShow.currmarker);
					  }
					  
					}
		});
	}
	$scope.pointinfowindow=function(map, marker, data){
		
			if(gsmarker.length>0){
			
			
		}
	}
	$scope.locationname="";
	$scope.getLocation = function(lat,lon, callback){
		var tempurl01 =  "http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon;
		var t = $.ajax({
			dataType:"json",
			url: tempurl01,
			success:function(data){
				
				if(data.results[0]!=undefined){
				if(data.results[0].formatted_address==undefined){
					if(typeof callback === "function") callback('');
				}else{
					if(typeof callback === "function") callback(data.results[0].formatted_address);
				}
				}else{
					if(typeof callback === "function") callback('');
				}
			},
			error:function(xhr, status, error){
				//if(typeof callback === "function") callback('');
				console.log('error:' + status + error);
			}
		});
		
    };	
    
	$scope.timeCalculate = function(duration){
		var milliseconds = parseInt((duration%1000)/100), seconds = parseInt((duration/1000)%60);
		var minutes = parseInt((duration/(1000*60))%60), hours = parseInt((duration/(1000*60*60))%24);
		
		hours = (hours < 10) ? "0" + hours : hours;
		minutes = (minutes < 10) ? "0" + minutes : minutes;
		seconds = (seconds < 10) ? "0" + seconds : seconds;
		temptime = hours + " H : " + minutes +' M : ' +seconds +' S';

		var s=duration;
		function addZ(n) {
		    return (n<10? '0':'') + n;
		}

		var ms = s % 1000;
		s = (s - ms) / 1000;
		var secs = s % 60;
		s = (s - secs) / 60;
		var mins = s % 60;
		var hrs = (s - mins) / 60;

		var tempTim = addZ(hrs) + ' H : ' + addZ(mins) + ' M : ' + addZ(secs) + ' S '; //+ ms;
		return tempTim;
	}


	// function parseDate(str) {
	//     var mdy = str.split('/');
	//     return new Date(mdy[2], mdy[0]-1, mdy[1]);
	// }
	


	function utcdateConvert(milliseconds){
		//var milliseconds=1440700484003;
		var offset='+10';
		var d = new Date(milliseconds);
		utc = d.getTime() + (d.getTimezoneOffset() * 60000);
		nd = new Date(utc + (3600000*offset));
		var result=nd.toLocaleString();
		return result;
	}
	$scope.timeconversion= function(time){
		var time = time;
		var hours = Number(time.match(/^(\d+)/)[1]);
		var minutes = Number(time.match(/:(\d+)/)[1]);
		var AMPM = time.match(/\s(.*)$/)[1];
		if(AMPM == "PM" && hours<12) hours = hours+12;
		if(AMPM == "AM" && hours==12) hours = hours-12;
		var sHours = hours.toString();
		var sMinutes = minutes.toString();
		if(hours<10) sHours = "0" + sHours;
		if(minutes<10) sMinutes = "0" + sMinutes;
		return sHours+":"+sMinutes+":00";
	}
	$scope.plotting = function(){
		
		$scope.hisurlold = $scope.hisurl;
		var fromdate = document.getElementById('dateFrom').value;
		var todate = document.getElementById('dateTo').value;
		if(document.getElementById('timeFrom').value==''){
			var fromtime = "00:00:00";
		}else{
			var fromtime = $scope.timeconversion(document.getElementById('timeFrom').value);
		}
		if(document.getElementById('timeTo').value==''){
			var totime = "00:00:00";
		}else{
			var totime = $scope.timeconversion(document.getElementById('timeTo').value);
		}
		if(document.getElementById('dateFrom').value==''){
			if(document.getElementById('dateTo').value==''){
				$scope.hisurl = 'http://'+globalIP+context+'/public//getVehicleHistory?vehicleId='+$scope.trackVehID;
			}
		}else{
			if(document.getElementById('dateTo').value==''){
				$scope.hisurl = 'http://'+globalIP+context+'/public//getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+fromdate+'&fromTime='+fromtime;
			}else{
				var days =daydiff(new Date(fromdate), new Date(todate));
				if(days<3)
					$scope.hisurl = 'http://'+globalIP+context+'/public//getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+fromdate+'&fromTime='+fromtime+'&toDate='+todate+'&toTime='+totime+'&fromDateUTC='+utcFormat(fromdate,fromtime)+'&toDateUTC='+utcFormat(todate,totime);
				else
					$scope.hisurl = 'http://'+globalIP+context+'/public//getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+fromdate+'&fromTime='+fromtime+'&toDate='+todate+'&toTime='+totime+'&interval=1'+'&fromDateUTC='+utcFormat(fromdate,fromtime)+'&toDateUTC='+utcFormat(todate,totime);
			}
		}
		if($scope.hisurlold!=$scope.hisurl){	
			for(var i=0; i<gmarkers.length; i++){
				gmarkers[i].setMap(null);
			}
			for(var i=0; i<$scope.polyline1.length; i++){
				$scope.polyline1[i].setMap(null);
			}
			
			$scope.polyline.setMap(null);
			$scope.markerstart.setMap(null);
			$scope.markerend.setMap(null);
			$scope.path = [];
			$scope.polylinearr = [];
			gmarkers=[];
			ginfowindow=[];
			contentString = [];
			gsmarker=[];
			gsinfoWindow=[];
			window.clearInterval(id);
			$('#replaybutton').attr('disabled','disabled');
			$('#lastseen').html('<strong>From Date & time :</strong> -');
			$('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');
		}else{
			if($scope.hisloc.error!=null){
				$('#myModal').modal();
			}
		}
		
	}
	$scope.addMarker= function(pos){
		var myLatlng = new google.maps.LatLng(pos.lat, pos.lng);
		var labelAnchorpos = new google.maps.Point(12, 37);
		if(pos.data.insideGeoFence =='Y'){
			pinImage = 'assets/imgs/F_'+pos.data.direction+'.png';
		}else{	
			if(pos.data.position =='P'){
				//pinImage = 'assets/imgs/'+pos.data.position+'.png';
				pinImage = 'assets/imgs/flag.png';
			}else{
				//pinImage = 'assets/imgs/A_'+pos.data.direction+'.png';
				pinImage = 'assets/imgs/orange.png';
			}
		}
		$scope.marker = new MarkerWithLabel({
			   position: pos.path, 
			   map: $scope.map,
			   icon: pinImage,
			   labelContent: ''/*pos.data.position*/,
			   // labelAnchor: labelAnchorpos,
			   // labelClass: "labels", 
			   labelInBackground: true
		});
		gmarkers.push($scope.marker);	
	} 
	$scope.geocoder = function(lat, lng, callback){
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(lat, lng);
		geocoder.geocode({'latLng': latlng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
			  if (results[1]) {
			  	if(results[1].formatted_address==undefined){
			  		if(typeof callback === "function") callback("No Result");
			  	}else{
					if(typeof callback === "function") callback(results[1].formatted_address);
				}
			  } else {
				console.log('No results found');
			  }
			} else {
				
			  console.log('Geocoder failed due to: ' + status);
			}
		});
	}
	$scope.addMarkerstart= function(pos){
		
		var myLatlng = new google.maps.LatLng(pos.lat, pos.lng);
		pinImage = 'assets/imgs/startflag.png';
		$scope.markerstart = new MarkerWithLabel({
		   position: pos.path, 
		   map: $scope.map,
		   icon: pinImage
		});
		$scope.geocoder(pos.lat, pos.lng, function(count){
			$scope.tempadd = count;
		});
		
		var contentString = '<h3 class="infoh3">Vehicle Details ('+$scope.trackVehID+') </h3><div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody><!--<tr><td>Location</td><td>'+$scope.tempadd+'</td></tr>--><tr><td>Last seen</td><td>'+pos.data.lastSeen+'</td></tr><tr><td>Parked Time</td><td>'+$scope.timeCalculate(pos.data.parkedTime)+'</td></tr><tr><td>Trip Distance</td><td>'+pos.data.tripDistance+'</td></tr></table></div>';
		var infoWindow = new google.maps.InfoWindow({content: contentString});
		google.maps.event.addListener($scope.markerstart, "click", function(e){
			infoWindow.open($scope.map, $scope.markerstart);	
		});
		(function(marker, data, contentString) {
		  google.maps.event.addListener(marker, "click", function(e) {
			infoWindow.open($scope.map, marker);
		  });	
		})($scope.markerstart, pos.data);
	}
	$scope.addMarkerend= function(pos){
		var myLatlng = new google.maps.LatLng(pos.lat, pos.lng);
		pinImage = 'assets/imgs/endflag.png';
		$scope.markerend = new MarkerWithLabel({
		   position: pos.path, 
		   map: $scope.map,
		   icon: pinImage
		});
		$scope.geocoder(pos.lat, pos.lng, function(count){
			if(count!=undefined){
				$scope.tempadd = count;
			}else{
				$scope.tempadd = 'No Result';
			}
			
			var contentString = '<h3 class="infoh3">Vehicle Details ('+$scope.trackVehID+') </h3>'
			+'<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody>'
			+'<tr><td>Location</td><td>'+$scope.tempadd+'</td></tr><tr><td>Last seen</td><td>'+pos.data.lastSeen+'</td>'
			+'</tr><tr><td>Parked Time</td><td>'+$scope.timeCalculate(pos.data.parkedTime)+'</td></tr><tr><td>Trip Distance</td>'
			+'<td>'+pos.data.tripDistance+'</td></tr></table></div>';
			
			var infoWindow = new google.maps.InfoWindow({content: contentString});
			google.maps.event.addListener($scope.markerend, "click", function(e){
				infoWindow.open($scope.map, $scope.markerend);	
			});
			
			(function(marker, data, contentString) {
			  google.maps.event.addListener(marker, "click", function(e) {
				infoWindow.open($scope.map, marker);
			  });	
			})($scope.markerend, pos.data);
		});
		
	}
	$scope.tempadd="";
	$scope.printadd=function(a, b, c, d, marker, map, data){
		var posval='';
		
		if(data.position=='S'){
			posval = 'Idle Time';
		
		}else if(data.position=='P'){
			posval = 'Parked Time';
		}
		
		var contentString = '<h3 class="infoh3">Vehicle Details ('+$scope.trackVehID+') </h3><div class="nearbyTable02">'
		+'<div><table cellpadding="0" cellspacing="0"><tbody>'
		+'<tr><td>Location</td><td>'+d+'</td></tr>'
		+'<tr><td>Last seen</td><td>'+a+'</td></tr>'
		+'<tr><td>'+posval+'</td><td>'+$scope.timeCalculate(b)+'</td></tr>'
		+'<tr><td>Trip Distance</td><td>'+c+'</td></tr>'
		+'</table></div>';
		var infoWindow = new google.maps.InfoWindow({content: contentString});
		ginfowindow.push(infoWindow);
		google.maps.event.addListener(marker, "click", function(e){
			for(j=0;j<ginfowindow.length;j++){
				ginfowindow[j].close();
			}
			infoWindow.open(map, marker);	
		});
		(function(marker, data, contentString) {
		  google.maps.event.addListener(marker, "click", function(e) {
		  	for(j=0;j<ginfowindow.length;j++){
		  		
				ginfowindow[j].close();
			}
			infoWindow.open(map, marker);
		  });	
		})(marker, data);
	}
	$scope.imgsrc = function(img){
		return img;
	}
	$scope.infoBox = function(map, marker, data){
		 
		var t = $scope.getLocation(data.latitude, data.longitude, function(count){
			
			 if(data.position=='S'){
			 	var b = data.idleTime;
			 }else if(data.position=='P'){
			 	var b = data.parkedTime;
			 }
			$scope.printadd(data.lastSeen, b, data.tripDistance, count, marker, map, data); 
		});
	};
	$scope.gcount =0;
	$scope.gscount =0;
	$scope.animated = function(){
	    var count = 0;
	    var offset;
	    var sentiel = -1;
	    var i = 0;
	    window.clearInterval(id);
	    var tempint=$scope.speedval;
	    id = window.setInterval(function () {
	        count = (count + 1) % 200;
	        offset = count /2;
	        $scope.gcount = count;
	        var icons = $scope.polyline.get('icons');
	        icons[0].offset = (offset) + '%';
	        $scope.polyline.set('icons', icons);    
	        if ($scope.polyline.get('icons')[0].offset == "99.5%") {
	            icons[0].offset = '100%';
	            $scope.polyline.set('icons', icons);
	            window.clearInterval(id);
	        }
	    }, tempint);
	}
	
	$scope.pausehis= function(){
		$('#pauseButton').hide();
		$('#playButton').show();	
		 window.clearInterval(id);
		 $scope.gscount = $scope.gcount;
		 console.log(' val '+$scope.gscount)

		 // google.maps.event.addListener($scope.map, "click", function(e) {
			 console.log(" maps "+$scope.path)
		 //  })
	}
	
	$scope.speedchange=function(){
		window.clearInterval(id);
		 $scope.gscount = $scope.gcount;
		 $scope.playhis();
	}
	
	$scope.playhis=function(){
		$('#playButton').hide();
		$('#pauseButton').show();	
		var count = $scope.gscount;
	    var offset;
	    var sentiel = -1;
	     window.clearInterval(id);
	     var tempint=$scope.speedval;
	    id = window.setInterval(function () {
	    	
	        count = (count + 1) % 200;
	        offset = count /2;
	        $scope.gcount = count;
	        var icons = $scope.polyline.get('icons');
	        icons[0].offset = (offset) + '%';
	        $scope.polyline.set('icons', icons);
	        
	        if ($scope.polyline.get('icons')[0].offset == "99.5%") {
	            icons[0].offset = '100%';
	            $scope.polyline.set('icons', icons);
	            window.clearInterval(id);
	        }
	        tempint=$scope.speedval;
	    }, tempint);
	}
	
	$scope.stophis=function(){
		var count = 0;
	    var offset;
	    var sentiel = -1;
	    
	    window.clearInterval(id);
	    var tempint=$scope.speedval;
	    count = (count + 1) % 200;
	    offset = count /2;
	    var icons = $scope.polyline.get('icons');
	    icons[0].offset = (offset) + '%';
	    $scope.polyline.set('icons', icons);
	        
        if ($scope.polyline.get('icons')[0].offset == "99.5%") {
            icons[0].offset = '100%';
            $scope.polyline.set('icons', icons);
            window.clearInterval(id);
        }   
	}
	$(window).load(function() {
		$('#status').fadeOut(); 
		$('#preloader').delay(350).fadeOut('slow');
		$('body').delay(350).css({'overflow':'visible'});
});


	$(document).ready(function(){
        $('#minmax').click(function(){
            $('#contentmin').animate({
                height: 'toggle'
            },500);
        });
    });
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
    
        $(function () {
        $('#dateFrom, #dateTo').datetimepicker({
          format:'YYYY-MM-DD',
          useCurrent:true,
          pickTime: false
        });
        $('#timeFrom').datetimepicker({
          pickDate: false,
                    useCurrent:true,
        });
        $('#timeTo').datetimepicker({
          useCurrent:true,
          pickDate: false
        });
        });
	
});