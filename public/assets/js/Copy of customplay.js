var app = angular.module('mapApp', []);
var gmarkers=[];
var ginfowindow=[];
var gsmarker=[];
var gsinfoWindow=[];
var contentString = [];
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
							$('#vehdevtype h3').text(locs.vehicleType);
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
							pImage = 'assets/imgs/bluedot.png';
						   for(var i=0;i<locs.vehicleLocations.length;i++){
						   		scope.path.push(new google.maps.LatLng(locs.vehicleLocations[i].latitude, locs.vehicleLocations[i].longitude));
						   		
						   		/*var marker = new google.maps.Marker({
						            map: scope.map,
						            position: new google.maps.LatLng(locs.vehicleLocations[i].latitude, locs.vehicleLocations[i].longitude),
						            icon:pImage
						        });
						        gsmarker.push(marker);
						        var infoWindowContent = '<div class="info_content">' +
								        '<h3>Speed :'+locs.vehicleLocations[i].speed+'</h3>' +
								        '<p>Address :'+locs.vehicleLocations[i].address+'</p>' +
								    '</div>';
								var infoWindow = new google.maps.InfoWindow({
								        content: infoWindowContent
								});
								gsinfoWindow.push(infoWindow);
								gsinfoWindow[gsinfoWindow.length-1].open();*/
					   		}
					   		 
		  	  				var latLngBounds = new google.maps.LatLngBounds();
			  				var j=0;
			  				scope.addMarkerstart({ lat: locs.vehicleLocations[0].latitude, lng: locs.vehicleLocations[0].longitude , data: locs.vehicleLocations[0], path:scope.path[0]});
					  		for(var i = 0; i < scope.path.length; i++) {
								latLngBounds.extend(scope.path[i]);
								if(locs.vehicleLocations[i].position=='P' || locs.vehicleLocations[i].position=='S' || locs.vehicleLocations[i].insideGeoFence=='Y' ){
									scope.addMarker({ lat: locs.vehicleLocations[i].latitude, lng: locs.vehicleLocations[i].longitude , data: locs.vehicleLocations[i], path:scope.path[i]});
									scope.infoBox(scope.map, gmarkers[j], locs.vehicleLocations[i]);
									j++;
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
						        path: google.maps.SymbolPath.CIRCLE,
						        scale: 6,
						        strokeColor: '#393'
						    };
						  	scope.polyline = new google.maps.Polyline({
								map: scope.map,
								path: scope.path,
								strokeColor: '#068b03',
								strokeOpacity: 0.7,
								strokeWeight: 2,
								icons: [{
						            icon: lineSymbol,
						            offset: '100%'
						        }],
								clickable: true
						  	});
						  	scope.pointDistances=[];
						  	var sphericalLib = google.maps.geometry.spherical;
						  	var pointZero = scope.path[0];
						  //	alert(pointZero);
    						var wholeDist = sphericalLib.computeDistanceBetween(pointZero, scope.path[scope.path.length - 1]);
						  	for (var i = 0; i < scope.path.length; i++) {
						        scope.pointDistances[i] = 100 * sphericalLib.computeDistanceBetween(scope.path[i], pointZero) / wholeDist;
						       // console.log('pointDistances[' + i + ']: ' + path[i]);
						    }
						    window.setTimeout(function () {
						    scope.animated();
						    },2000);
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
	$scope.url = 'http://'+globalIP+'/vamo/public/getVehicleLocations';
	$scope.getTodayDate  =	function(date) {
		var date = new Date(date);
		return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
	};
	$http.get($scope.url).success(function(data){
		$scope.locations = data;
		$scope.trackVehID =$scope.locations[0].vehicleLocations[0].vehicleId;
		$scope.hisurl = 'http://'+globalIP+'/vamo/public/getVehicleHistory?vehicleId='+$scope.trackVehID;
		$('.nav-second-level li').eq(0).children('a').addClass('active');
	}).error(function(){ /*alert('error'); */});
	$scope.genericFunction = function(a,b){
		$scope.path = [];
		gmarkers=[];
		ginfowindow=[];
		contentString = [];
		$scope.trackVehID = a;
		$scope.plotting();
	}
	$scope.locationname="";
	$scope.getLocation = function(lat,lon, callback){
		var tempurl01 =  "http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon;
		var t = $.ajax({
			dataType:"json",
			url: tempurl01,
			success:function(data){
				if(typeof callback === "function") callback(data.results[0].formatted_address);
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
			$scope.polyline.setMap(null);
			$scope.markerstart.setMap(null);
			$scope.markerend.setMap(null);
			$scope.path = [];
			gmarkers=[];
			ginfowindow=[];
			contentString = [];
			
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
		if(pos.data.insideGeoFence =='Y'){
			pinImage = 'assets/imgs/F_'+pos.data.direction+'.png';
			var labelAnchorpos = new google.maps.Point(12, 50);
		}else{	
			if(pos.data.position =='P'){
				pinImage = 'assets/imgs/'+pos.data.position+'.png';
				var labelAnchorpos = new google.maps.Point(2, 58);
			}else{
				pinImage = 'assets/imgs/A_'+pos.data.direction+'.png';
				var labelAnchorpos = new google.maps.Point(12, 50);
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
				if(typeof callback === "function") callback(results[1].formatted_address)
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
			$scope.tempadd = count;
		});
		var contentString = '<h3 class="infoh3">Vehicle Details ('+$scope.trackVehID+') </h3><div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody><!--<tr><td>Location</td><td>'+$scope.tempadd+'</td></tr>--><tr><td>Last seen</td><td>'+pos.data.lastSeen+'</td></tr><tr><td>Parked Time</td><td>'+$scope.timeCalculate(pos.data.timeConsumed)+'</td></tr><tr><td>Trip Distance</td><td>'+pos.data.tripDistance+'</td></tr></table></div>';
		var infoWindow = new google.maps.InfoWindow({content: contentString});
		google.maps.event.addListener($scope.markerend, "click", function(e){
			infoWindow.open($scope.map, $scope.markerend);	
		});
		(function(marker, data, contentString) {
		  google.maps.event.addListener(marker, "click", function(e) {
			infoWindow.open($scope.map, marker);
		  });	
		})($scope.markerend, pos.data);
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
			$scope.printadd(data.lastSeen, data.timeConsumed, data.tripDistance, count, marker, map, data); 
		});
	};
	$scope.animated = function(){
	
    var count = 0;
    var offset;
    var sentiel = -1;
    
    id = window.setInterval(function () {
    	
        count = (count + 1) % 2000;
        offset = count /2;
        
        
        // we have only one icon
        var icons = $scope.polyline.get('icons');
        icons[0].offset = (offset) + '%';
        $scope.polyline.set('icons', icons);
        
        if ($scope.polyline.get('icons')[0].offset == "99.5%") {
            icons[0].offset = '100%';
            $scope.polyline.set('icons', icons);
            window.clearInterval(id);
        }
        
    }, 200);
	}
});