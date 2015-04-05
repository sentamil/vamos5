var app = angular.module('mapApp', []);
var gmarkers=[];
var ginfowindow=[];
var gsmarker=[];
var gsinfoWindow=[];
var contentString = [];
var contentString01=[];
var id;
app.directive('map', function($http) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {
        	scope.path=[];
		 	scope.$watch("hisurl", function (val) { 
		   		$http.get(scope.hisurl).success(function(data){
		   			var locs = data;
					scope.hisloc = locs;
					//alert(scope.hisurl);
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
							
							$('#vehiid h3').text(locs.vehicleId + " (" +locs.shortName+")");
							$('#toddist h3').text(scope.timeCalculate(locs.totalRunningTime));
							$('#vehstat h3').text(scope.timeCalculate(locs.totalIdleTime));
							$('#vehdevtype h3').text(locs.odoDistance);
							$('#mobno h3').text(scope.timeCalculate(locs.totalParkedTime));
							$('#regno h3 span').text(locs.tripDistance);
							
							$('#lastseen').html('<strong>From Date & time :</strong> '+ data.fromDateTime);
							$('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> '+ data.toDateTime);
				
							var myOptions = {
								zoom: 11,
								center: new google.maps.LatLng(data.vehicleLocations[0].latitude, data.vehicleLocations[0].longitude),
								mapTypeId: google.maps.MapTypeId.ROADMAP
							
							};
	            			scope.map = new google.maps.Map(document.getElementById(attrs.id), myOptions);
							
						    for(var i=0;i<locs.vehicleLocations.length;i++){
						   		scope.path.push(new google.maps.LatLng(locs.vehicleLocations[i].latitude, locs.vehicleLocations[i].longitude));
						   		
						   	    scope.pointMarker(locs.vehicleLocations[i].latitude, locs.vehicleLocations[i].longitude);
						   	  if(locs.vehicleLocations[i].isOverSpeed=='Y'){
								   	    	var pscolorval = '#ff0000';
								   	   }else{
								   	   		var pscolorval = '#068b03';
								   	   }
						   	  scope.polylinearr.push(pscolorval);
						        scope.pointinfowindow(scope.map, gsmarker[i], locs.vehicleLocations[i]);
					   		}
					   		
		  	  				var latLngBounds = new google.maps.LatLngBounds();
			  				var j=0;
			  				scope.addMarkerstart({ lat: locs.vehicleLocations[0].latitude, lng: locs.vehicleLocations[0].longitude , data: locs.vehicleLocations[0], path:scope.path[0]});
					  		for(var i = 0; i < scope.path.length; i++) {
								latLngBounds.extend(scope.path[i]);
								if(locs.vehicleLocations[i].position!=undefined){
									if(locs.vehicleLocations[i].position=='P' /*|| locs.vehicleLocations[i].position=='S'*/ || locs.vehicleLocations[i].insideGeoFence=='Y' ){
										scope.addMarker({ lat: locs.vehicleLocations[i].latitude, lng: locs.vehicleLocations[i].longitude , data: locs.vehicleLocations[i], path:scope.path[i]});
										scope.infoBox(scope.map, gmarkers[j], locs.vehicleLocations[i]);
										j++;
									}
								}
					  		}
			  				var lastval = locs.vehicleLocations.length-1;
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
								strokeWeight: 0,
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
		   		}).error(function(){ });
		 	});
        }
    };
});
app.controller('mainCtrl',function($scope, $http){ 
	$scope.locations = [];
	$scope.path = [];
	$scope.polylinearr=[];
	$scope.polyline1=[];
	$scope.tempadd01='';
	$scope.url = 'http://'+globalIP+'/vamo/public/getVehicleLocations';
	$scope.getTodayDate  =	function(date) {
		var date = new Date(date);
		return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
	};
	$scope.loading	=	true;
	$http.get($scope.url).success(function(data){
		$scope.locations = data;
		$scope.trackVehID =$scope.locations[0].vehicleLocations[0].vehicleId;
		$scope.hisurl = 'http://'+globalIP+'/vamo/public/getVehicleHistory?vehicleId='+$scope.trackVehID;
		$('.nav-second-level li').eq(0).children('a').addClass('active');
		$scope.loading	=	false;
	}).error(function(){ /*alert('error'); */});
	$scope.genericFunction = function(a,b){
		$scope.path = [];
		gmarkers=[];
		ginfowindow=[];
		contentString = [];
		$scope.trackVehID = a;
		$scope.selected = b;
		$scope.plotting();
	}
	$scope.groupSelection = function(groupname, groupid){
		 $scope.selected=0;
		 $scope.url = 'http://'+globalIP+'/vamo/public/getVehicleLocations?group=' + groupname;
		 $scope.gIndex = groupid;
		 gmarkers=[];
		 ginfowindow=[];
		 $scope.loading	=	true;
		 $http.get($scope.url).success(function(data){
			$scope.locations = data;
			$scope.trackVehID =$scope.locations[$scope.gIndex].vehicleLocations[$scope.selected].vehicleId;
			$scope.hisurl = 'http://'+globalIP+'/vamo/public/getVehicleHistory?vehicleId='+$scope.trackVehID;
			$('.nav-second-level li').eq(0).children('a').addClass('active');
			$scope.loading	=	false;
		}).error(function(){ /*alert('error'); */});
		
	}
	$scope.pointMarker=function(lat,lng){
		var line0Symbol = {
	        path: google.maps.SymbolPath.CIRCLE,
	        scale: 3,
	        strokeColor: '#999',
	        strokeWeight: 2
	    };
	    var marker = new google.maps.Marker({
            map: $scope.map,
            position: new google.maps.LatLng(lat, lng),
            icon:line0Symbol
	    });
	    gsmarker.push(marker);
	}
	$scope.pointinfowindow=function(map, marker, data){
		$scope.getLocation(data.latitude, data.longitude, function(count){
			if(count!=undefined){
				$scope.tempadd01 = count;
			}else{
				$scope.tempadd01='No result'
			}
			contentString01 = '<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody><tr><td>Speed</td><td>'+data.speed+'</td></tr><tr><td>date & time</td><td>'+data.lastSeen+'</td></tr><tr><td>trip distance</td><td>'+data.tripDistance+'</td></tr><tr><td style="width:80px">location</td><td style="width:100px">'+$scope.tempadd01+'</td></tr></tbody></table></div>';
			var infoWindow = new google.maps.InfoWindow({content: contentString01});
			gsinfoWindow.push(infoWindow);
			google.maps.event.addListener(marker, "mouseover", function(e){
				infoWindow.open(map, marker);	
			});
			google.maps.event.addListener(marker, "mouseout", function(e){
				infoWindow.close(map, marker);	
			});
			(function(marker, data, contentString01) {
				  google.maps.event.addListener(marker, "mouseover", function(e) {
					infoWindow.open(map, marker);
				  });
				  google.maps.event.addListener(marker, "mouseout", function(e) {
					infoWindow.close(map, marker);
				  });	
			})(marker, data);
		});	
	}
	$scope.locationname="";
	$scope.getLocation = function(lat,lon, callback){
		var tempurl01 =  "http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon;
		var t = $.ajax({
			dataType:"json",
			url: tempurl01,
			success:function(data){
				
				if(data.results[0].formatted_address==undefined){
					
					if(typeof callback === "function") callback('Not found');
				}else{
					if(typeof callback === "function") callback(data.results[0].formatted_address);
				}
			},
			error:function(xhr, status, error){
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
		temptime = hours + " H : " + minutes +' M';
		return temptime;
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
				$scope.hisurl = 'http://'+globalIP+'/vamo/public/getVehicleHistory?vehicleId='+$scope.trackVehID;
			}
		}else{
			if(document.getElementById('dateTo').value==''){
				$scope.hisurl = 'http://'+globalIP+'/vamo/public/getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+fromdate+'&fromTime='+fromtime;
			}else{
				$scope.hisurl = 'http://'+globalIP+'/vamo/public/getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+fromdate+'&fromTime='+fromtime+'&toDate='+todate+'&toTime='+totime;
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
		var labelAnchorpos = new google.maps.Point(12, 50);
		if(pos.data.insideGeoFence =='Y'){
			pinImage = 'assets/imgs/F_'+pos.data.direction+'.png';
			//var labelAnchorpos = new google.maps.Point(12, 50);
		}else{	
			if(pos.data.position =='P'){
				pinImage = 'assets/imgs/'+pos.data.position+'.png';
				//var labelAnchorpos = new google.maps.Point(2, 58);
			}else{
				pinImage = 'assets/imgs/A_'+pos.data.direction+'.png';
				//var labelAnchorpos = new google.maps.Point(12, 50);
			}
		}
		$scope.marker = new MarkerWithLabel({
			   position: pos.path, 
			   map: $scope.map,
			   icon: pinImage,
			   labelContent: pos.data.position,
			   labelAnchor: labelAnchorpos,
			   labelClass: "labels", 
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
				alert('No results found');
			  }
			} else {
			  alert('Geocoder failed due to: ' + status);
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
		
		var contentString = '<h3 class="infoh3">Vehicle Details ('+$scope.trackVehID+') </h3><div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody><!--<tr><td>Location</td><td>'+$scope.tempadd+'</td></tr>--><tr><td>Last seen</td><td>'+pos.data.lastSeen+'</td></tr><tr><td>Parked Time</td><td>'+$scope.timeCalculate(pos.data.timeConsumed)+'</td></tr><tr><td>Trip Distance</td><td>'+pos.data.tripDistance+'</td></tr></table></div>';
		var infoWindow = new google.maps.InfoWindow({content: contentString});
		google.maps.event.addListener($scope.markerstart, "click", function(e){
			infoWindow.open($scope.map, $scope.markerstart);	
		});
		(function(marker, data, contentString) {
		  google.maps.event.addListener(marker, "click", function(e) {
			infoWindow.open($scope.map, marker);
		  });	
		})($scope.markerstart, pos.data);
		
		//scope.infoBox(scope.map, gmarkers[j], locs.vehicleLocations[i]);
		
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
			var contentString = '<h3 class="infoh3">Vehicle Details ('+$scope.trackVehID+') </h3><div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody><tr><td>Location</td><td>'+$scope.tempadd+'</td></tr><tr><td>Last seen</td><td>'+pos.data.lastSeen+'</td></tr><tr><td>Parked Time</td><td>'+$scope.timeCalculate(pos.data.timeConsumed)+'</td></tr><tr><td>Trip Distance</td><td>'+pos.data.tripDistance+'</td></tr></table></div>';
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
		var contentString = '<h3 class="infoh3">Vehicle Details ('+$scope.trackVehID+') </h3><div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody><tr><td>Location</td><td>'+d+'</td></tr><tr><td>Last seen</td><td>'+a+'</td></tr><tr><td>'+posval+'</td><td>'+$scope.timeCalculate(b)+'</td></tr><tr><td>Trip Distance</td><td>'+c+'</td></tr></table></div>';
		var infoWindow = new google.maps.InfoWindow({content: contentString});
		ginfowindow.push(infoWindow);
		google.maps.event.addListener(marker, "click", function(e){
			infoWindow.open(map, marker);	
		});
		(function(marker, data, contentString) {
		  google.maps.event.addListener(marker, "click", function(e) {
			infoWindow.open(map, marker);
		  });	
		})(marker, data);
	}
	$scope.imgsrc = function(img){
		return img;
	}
	$scope.infoBox = function(map, marker, data){
		var t = $scope.getLocation(data.latitude, data.longitude, function(count){ 
			//var geourl = 'http://128.199.217.144/geocode/public/store?geoLocation='+data.latitude+','+data.longitude+'&geoAddress='+count
			//$http.get(geourl).success(function(data){
				
			//});
			
			$scope.printadd(data.lastSeen, data.timeConsumed, data.tripDistance, count, marker, map, data); 
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
	  //  var infoWindow = new google.maps.InfoWindow();
	    id = window.setInterval(function () {
	    //	infoWindow.close();
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
	      //  infoWindow.setPosition($scope.path[i])
	        //infoWindow.open($scope.map);
	      //  i++
	    }, tempint);
	}
	$scope.pausehis= function(){
		$('#pauseButton').hide();
		$('#playButton').show();
		
		 window.clearInterval(id);
		 $scope.gscount = $scope.gcount;
		 
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
 