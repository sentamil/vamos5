app.controller('mainCtrl',['$scope', 'vamoservice','_global', function($scope, vamoservice, GLOBAL){

//Global Variables
$scope.selectScreen 		= ['screen1'];
getVehicleLocations 		= GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
getSelectedVehicleLocation  = GLOBAL.DOMAIN_NAME+'/getSelectedVehicleLocation?vehicleId='
getSelectedVehicleLocation1 = GLOBAL.DOMAIN_NAME+'/getSelectedVehicleLocation1?vehicleId='
path1 =[];
path2 =[];
path3 =[];
path4 =[];

var _mapsDetails 	= 	{}
var _pathDetails	=	{}
function getParameterByName(name) {
      name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
      var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
          results = regex.exec(location.search);
      return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  }

$scope.update = function(){
	startLoading();
	vamoservice.getDataCall(getVehicleLocations+'?group='+$scope.vehicleSelected+':'+$scope.fcode).then(function(data){
		angular.forEach(data, function(groupVehicle){
			if($scope.vehicleSelected+':'+$scope.fcode == groupVehicle.group)
				if(groupVehicle.vehicleLocations){
					$scope.vehicles = groupVehicle.vehicleLocations
					stopLoading();
				}
		})
	})
}

function filterGroup(groups){
	var filter = [];
	var splitValue ='';
	angular.forEach(groups, function(value){
		if (value.group)
			splitValue = value.group.split(":");
			filter.push(splitValue[0])
	})
	return filter
}





// var getJoke = function(addressUrl){
//    var fetchingAddress = '';
// 	  $.ajax({
// 	    url:addressUrl, 
// 	    async: false,   
// 	    success:function(dat) {
// 	      fetchingAddress = dat.results[0].formatted_address
// 	    }
// 	})
//   return fetchingAddress;
//  }


function resolveAddress(response)
{	var address = ' ';
	try
	{
		var addressUrl 	= 	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+response.latitude+','+response.longitude+"&sensor=true"
		address = (response.address==undefined? getJoke(addressUrl) : response.address)
		return address;
	} 
	catch (err) 
	{
		return address;
		stopLoading();
		console.log(' error '+err)
	}
	
}

function markerDrop(response, screen, marker){
	marker.setPosition(new google.maps.LatLng(response.latitude,response.longitude));

	marker.setMap(screen);
	// screen.setCenter(new google.maps.LatLng(response.latitude,response.longitude));
		marker.set('labelAnchor', (new google.maps.Point(0,0)));
		marker.set('labelClass', "multi");
		marker.set('labelInBackground', false);
	marker.setIcon(vamoservice.iconURL(response));
	
	_mapsDetails[response.vehicleId] = marker;
	stopLoading();
}

function selectingScreen(screen, response, vehid){
	switch (screen){
		case 'screen1':
			// $scope.screen1 = new google.maps.Map(document.getElementById("maploc1"),mapProp);
			marker1 = new MarkerWithLabel({labelContent: 'Vehicle Name - '+response.shortName+'<br>'+'Speed - '+response.speed+' kms/h'+'<br>'+'odoDistance - '+response.odoDistance+'<br>'+'Address - '+resolveAddress(response)});
			markerDrop(response, $scope.screen1, marker1);
			// emptyJob(screen);
			// path1.push(response.latitude,response.longitude);
			// obj1 = response;
			break;
		case 'screen2':
			$scope.screen2 = new google.maps.Map(document.getElementById("maploc2"),mapProp);
			marker2 = new MarkerWithLabel({labelContent: 'Vehicle Name - '+response.shortName+'<br>'+'Speed - '+response.speed+' kms/h'+'<br>'+'odoDistance - '+response.odoDistance+'<br>'+'Address - '+resolveAddress(response)});
			markerDrop(response, $scope.screen2, marker2);
			emptyJob(screen);
			path2.push(response.latitude,response.longitude);
			obj2 = response;
			break;
		case 'screen3':
			$scope.screen3 = new google.maps.Map(document.getElementById("maploc3"),mapProp);
			marker3 = new MarkerWithLabel({labelContent: 'Vehicle Name - '+response.shortName+'<br>'+'Speed - '+response.speed+' kms/h'+'<br>'+'odoDistance - '+response.odoDistance+'<br>'+'Address - '+resolveAddress(response)});
			markerDrop(response, $scope.screen3, marker3);
			emptyJob(screen);
			path3.push(response.latitude,response.longitude);
			obj3 = response;
			break;
		case 'screen4':
			$scope.screen4 = new google.maps.Map(document.getElementById("maploc4"),mapProp);
			marker4 = new MarkerWithLabel({labelContent: 'Vehicle Name - '+response.shortName+'<br>'+'Speed - '+response.speed+' kms/h'+'<br>'+'odoDistance - '+response.odoDistance+'<br>'+'Address - '+resolveAddress(response)});
			markerDrop(response, $scope.screen4, marker4);
			emptyJob(screen);
			path4.push(response.latitude,response.longitude);
			obj4 = response;
			break;
		default:
			break;
	}
	stopLoading();
}

// function selectMap(screen){
// 	switch (screen){
// 		case 'screen1':
// 			return $scope.screen1;
// 			break;
// 		case 'screen2':
// 			return $scope.screen2;
// 			break;
// 		case 'screen3':
// 			return $scope.screen3;
// 			break;
// 		case 'screen4':
// 			return $scope.screen4;
// 			break;
// 		default:
// 			break;
// 	}
// }
var pathlist = []
function lineDraw(lat, lan, screen, vehicleid){
	$scope.slatlong = new google.maps.LatLng(lat, lan);
	$scope.polyline = new google.maps.Polyline({
		map: $scope.screen1,
		path: [$scope.elatlong,$scope.slatlong],
		strokeColor: '#00b3fd',
		strokeOpacity: 0.7,
		strokeWeight: 5,
		
		clickable: true
	
	});

	$scope.elatlong = 	$scope.slatlong;
	// _pathDetails[vehicleid] = pathlist.push($scope.slatlong);
}


function joinLine(urlVehi, vehicleid)
{
	vamoservice.getDataCall(urlVehi).then(function(res){
		var sp;
		var latlan = [];
		$scope.slatlong = {};
		$scope.elatlong = {};
		$scope.polyline = {};
		// var latLngOld= ["20.345136,74.180436","20.344211,74.18148","20.343227,74.182578","20.342202,74.183724","20.3412,74.184862","20.34022,74.185991","20.339247,74.187102","20.338271,74.188196","20.337422,74.189111","20.336598,74.190031","20.336598,74.190031"]
		for (var i = 0; i < res.latLngOld.length; i++) {
			sp = res.latLngOld[i].split(',');
			lineDraw(sp[0], sp[1], screen, vehicleid);
		}
		// selectingScreen('screen1', res, vehicleno)
	})
}

function serviceCall(screen, url, urlVehi, vehicleid)
{
	
	// vamoservice.getDataCall(url).then(function(response){
	// 	mapProp = {
 //    		center: new google.maps.LatLng(response.latitude,response.longitude),
 //    		zoom:13,
	//     	zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
	// 		mapTypeId: google.maps.MapTypeId.ROADMAP
	// 	};
	// 	$scope.screen1 = new google.maps.Map(document.getElementById("maploc1"),mapProp);
	// 	joinLine(urlVehi);
	// 	// selectingScreen(screen, response, vehicleid)
			
	// })
joinLine(urlVehi, vehicleid);

}


(function init() {
	var vehicleno = getParameterByName('vehicleId');
	var url = getSelectedVehicleLocation+vehicleno;
	var urlVeh = getSelectedVehicleLocation1+vehicleno;
	try{
		startLoading();
		
		vamoservice.getDataCall(getVehicleLocations).then(function(data){
			vamoservice.getDataCall(url).then(function(response){
				mapProp = {
		    		center: new google.maps.LatLng(response.latitude,response.longitude),
		    		zoom:7,
			    	zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				$scope.screen1 = new google.maps.Map(document.getElementById("maploc1"),mapProp);
				
				selectingScreen('screen1', response, vehicleno)
				serviceCall('screen1', url, urlVeh, vehicleno)

			})
			$scope.sliceGroup = filterGroup(data)
			groupName	 	=	data[0].group.split(':');
			$scope.fcode 	=	groupName[1];
			// $scope.getVehicle = data
			stopLoading();
		})
	} catch (err){
		console.log('print err'+err)
		
	}
}());


// (function (){
// 	console.log(' hello world ');
// }())



// function emptyJob(screenNo)
// {
// 	switch (screenNo){
// 		case 'screen1':
// 			path1 =[];
// 			obj1 =[];
// 			break;
// 		case 'screen2':
// 			path2 =[];
// 			obj2 =[];
// 			break;
// 		case 'screen3':
// 			path3 =[];
// 			obj3 =[];
// 			break;
// 		case 'screen4':
// 			path4 =[];
// 			obj4 =[];
// 			break;
// 		default:
// 			break;
// 	}
// }

$scope.multiTracking = function(vehicle)
{	startLoading();
	try
	{
		// var vehiclelist = [];
		// console.log($scope.vehicles);
		// for (key in $scope.vehicles){
		// 	if($scope.vehicles[key].vehicleId == vehicle.vehicleId){
		// 		delete $scope.vehicles[key];
		// 		 console.log($scope.vehicles);
		// 	}
		// 	$scope.vehicles.push($scope.vehicles[key]) 
		// }
		// $scope.vehicles = null;
		// $scope.vehicles = vehiclelist;
		var status =  0;
		for(key in _mapsDetails)
			if (vehicle.vehicleId == key)
				status = 1;

			if(status == 0)
				vamoservice.getDataCall(getSelectedVehicleLocation+vehicle.vehicleId).then(function(response){
					selectingScreen('screen1', response, vehicle.vehicleId);
					serviceCall('screen1', getSelectedVehicleLocation+vehicle.vehicleId, getSelectedVehicleLocation1+vehicle.vehicleId, vehicle.vehicleId);
				});
		// emptyJob('screen1');
		
		stopLoading();
	} catch (er) {
		console.log(' print error '+er)
		stopLoading();
	}
}

// // for connect two lines
// function drawLine(loc1, loc2, maps){
// 	var flightPlanCoordinates = [loc1, loc2];
// 	var flightPath = new google.maps.Polyline({
// 		map: maps,
// 		path: flightPlanCoordinates,
// 		strokeColor: '#00b3fd',
// 		strokeOpacity: 0.7,
// 		strokeWeight: 5,
// 		clickable: true
// 	});
	
// }

// function intervalServiceCall(obj, marker, maps, path, screen){
// 	// marker.setMap(null);
// 	try{
// 		vamoservice.getDataCall(getSelectedVehicleLocation+obj.vehicleId).then(function(response){
// 			// console.log(' hi arun nice coding '+obj.vehicleId)
// 			drawLine(new google.maps.LatLng(path[0], path[1]), new google.maps.LatLng(response.latitude,response.longitude), maps);
// 			marker.setPosition(new google.maps.LatLng(response.latitude,response.longitude));
// 			// marker.set({labelContent: ' '});
// 			// marker.set({labelContent:   'Vehicle Name - '+response.shortName+'<br>'+'Speed - '+response.speed+' kms/h'+'<br>'+'odoDistance - '+response.odoDistance+'<br>'+'Address - '+response.address});
// 			marker.labelContent = 'Vehicle Name - '+response.shortName+'<br>'+'Speed - '+response.speed+' kms/h'+'<br>'+'odoDistance - '+response.odoDistance+'<br>'+'Address - '+resolveAddress(response);
// 			marker.setMap(maps)
// 			maps.setCenter(new google.maps.LatLng(response.latitude,response.longitude));
				
// 			// maps.setZoom(13);
// 			// path.push(response.latitude,response.longitude);
// 			switch (screen){
// 				case 'screen1':
// 					path1 =[];
// 					path1.push(response.latitude,response.longitude);
// 					break;
// 				case 'screen2':
// 					path2 =[];
// 					path2.push(response.latitude,response.longitude);
// 					break;
// 				case 'screen3':
// 					path3 =[];
// 					path3.push(response.latitude,response.longitude);
// 					break;
// 				case 'screen4':
// 					path4 =[];
// 					path4.push(response.latitude,response.longitude);
// 					break;
// 				default:
// 					break;
// 			}
// 		})

// 	} catch (err){
// 		console.log(' print err  '+err);
// 	}
	
function joinTwoLine(loc1, loc2, vehicleid){
	// $scope.slatlong = new google.maps.LatLng(lat, lan);
	$scope.polyline = new google.maps.Polyline({
		map: $scope.screen1,
		path: [loc1,loc2],
		strokeColor: '#00b3fd',
		strokeOpacity: 0.7,
		strokeWeight: 5,
		
		clickable: true
	});
	// $scope.elatlong = 	$scope.slatlong;
	// _pathDetails[vehicleid] = pathlist.push($scope.slatlong);
}
	
// }

// setInterval(function() {
// 	// marker4.setMap(null);
// 	if(typeof obj1.vehicleId === 'string')
// 		intervalServiceCall(obj1, marker1, $scope.screen1, path1, 'screen1');
// 	if(typeof obj2 !== 'undefined')
// 		if(typeof obj2.vehicleId === 'string')
// 			intervalServiceCall(obj2,marker2, $scope.screen2, path2, 'screen2');
// 	if(typeof obj3 !== 'undefined')
// 		if(typeof obj3.vehicleId === 'string')
// 			intervalServiceCall(obj3, marker3, $scope.screen3, path3, 'screen3');
// 	if(typeof obj4 !== 'undefined')
// 		if(typeof obj4.vehicleId === 'string')
// 			intervalServiceCall(obj4, marker4, $scope.screen4, path4, 'screen4');
// },10000);
// function markerDrop1(response, screen, marker){
// 	marker.setPosition(new google.maps.LatLng(12.499287, 78.569536));

// 	marker.setMap(screen);
// 	screen.setCenter(new google.maps.LatLng(12.499287, 78.569536));
// 		// marker.set('labelAnchor', (new google.maps.Point(0,0)));
// 		// marker.set('labelClass', "multi");
// 		// marker.set('labelInBackground', false);
// 	marker.setIcon(vamoservice.iconURL(response));
	
// 	_mapsDetails[response.vehicleId] = marker;
// 	stopLoading();
// }


function getMarkerDetails(vId, marker){
	console.log(' inside ')
	// joinLine(getSelectedVehicleLocation+vId, vId);
	vamoservice.getDataCall(getSelectedVehicleLocation+vId, vId).then(function(res){
		marker.labelContent = 'Vehicle Name - '+res.shortName+'<br>'+'Speed - '+res.speed+' kms/h'+'<br>'+'odoDistance - '+res.odoDistance+'<br>'+'Address - '+resolveAddress(res);
// 			marker.setMap(maps)
		// var sp;
		// var latlan = [];
		// $scope.slatlong = {};
		// $scope.elatlong = {};
		// $scope.polyline = {};
		// var value =res.position == 'M'? lineDraw(res.latitude, res.longitude, 'screen1', vId) : false
		// var latLngOld= ["20.345136,74.180436","20.344211,74.18148","20.343227,74.182578","20.342202,74.183724","20.3412,74.184862","20.34022,74.185991","20.339247,74.187102","20.338271,74.188196","20.337422,74.189111","20.336598,74.190031","20.336598,74.190031"]
		// for (var i = 0; i < res.latLngOld.length; i++) {
			// sp = res.latLngOld[i].split(',');
			// if(res.position == 'M'){
				// _mapsDetails[key].position.lat(), _mapsDetails[key].position.lng()
				// marker.position.lat()  marker.position.lng()
				joinTwoLine(new google.maps.LatLng(marker.position.lat(), marker.position.lng()),  new google.maps.LatLng(res.latitude, res.longitude), vId)
				markerDrop(res, $scope.screen1, marker)

				// console.log(' _mapsDetails[key].position.lat() ')
			// }
			// lineDraw(res.latitude, res.longitude, 'screen1', vId);
		// }
		// selectingScreen('screen1', res, vehicleno)
	})
}


setInterval(function(){
	for(key in _mapsDetails){
		getMarkerDetails(key, _mapsDetails[key]);
	}
}, 10000)

}]);
