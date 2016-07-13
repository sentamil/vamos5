app.controller('mainCtrl',function($scope, $http, vamoservice){

//Global Variables
$scope.selectScreen 		= ['screen1','screen2', 'screen3', 'screen4'];
getVehicleLocations 		= 'http://'+globalIP+context+'/public//getVehicleLocations';
getSelectedVehicleLocation  = 'http://'+globalIP+context+'/public/getSelectedVehicleLocation?vehicleId='
getSelectedVehicleLocation1 = 'http://'+globalIP+context+'/public/getSelectedVehicleLocation1?vehicleId='
path1 =[]
path2 =[]
path3 =[]
path4 =[]

$scope.update = function(){
	startLoading();
	vamoservice.getDataCall(getVehicleLocations+'?group='+$scope.vehicleSelected+":SMP").then(function(data){
		angular.forEach(data, function(groupVehicle){
			if($scope.vehicleSelected+":SMP" == groupVehicle.group)
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





var getJoke = function(addressUrl){
   var fetchingAddress = '';
	  $.ajax({
	    url:addressUrl, 
	    async: false,   
	    success:function(dat) {
	      fetchingAddress = dat.results[0].formatted_address
	    }
	})
  return fetchingAddress;
 }


function resolveAddress(response)
{	
	try
	{
		var addressUrl 	= "http://maps.googleapis.com/maps/api/geocode/json?latlng="+response.latitude+','+response.longitude+"&sensor=true"
		var address = (response.address==undefined? getJoke(addressUrl) : response.address)
		return address
	} 
	catch (err) 
	{
		stopLoading();
		console.log(' error '+err)
	}
	
}

function markerDrop(response, screen, marker){
	marker.setPosition(new google.maps.LatLng(response.latitude,response.longitude));
	marker.setMap(screen);
	marker.set('labelAnchor', (new google.maps.Point(0,0)));
	marker.set('labelClass', "multi");
	marker.set('labelInBackground', false);
	marker.setIcon(vamoservice.iconURL(response));
	stopLoading();
}

function selectingScreen(screen, response, vehid){
	switch (screen){
		case 'screen1':
			$scope.screen1 = new google.maps.Map(document.getElementById("maploc1"),mapProp);
			marker1 = new MarkerWithLabel({labelContent: 'Vehicle Name - '+response.shortName+'<br>'+'Speed - '+response.speed+' kms/h'+'<br>'+'odoDistance - '+response.odoDistance+'<br>'+'Address - '+resolveAddress(response)});
			markerDrop(response, $scope.screen1, marker1);
			emptyJob(screen);
			path1.push(response.latitude,response.longitude);
			obj1 = response;
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

function selectMap(screen){
	switch (screen){
		case 'screen1':
			return $scope.screen1;
			break;
		case 'screen2':
			return $scope.screen2;
			break;
		case 'screen3':
			return $scope.screen3;
			break;
		case 'screen4':
			return $scope.screen4;
			break;
		default:
			break;
	}
}

function lineDraw(lat, lan, screen){
	$scope.slatlong = new google.maps.LatLng(lat, lan);
	$scope.polyline = new google.maps.Polyline({
		map: selectMap(screen),
		path: [$scope.elatlong,$scope.slatlong],
		strokeColor: '#00b3fd',
		strokeOpacity: 0.7,
		strokeWeight: 5,
		clickable: true
	});
	$scope.elatlong = $scope.slatlong;
	
}


function serviceCall(screen, url, urlVehi, vehicleid)
{
	
	vamoservice.getDataCall(url).then(function(response){
		mapProp = {
    		center: new google.maps.LatLng(response.latitude,response.longitude),
    		zoom:13,
	    	zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		vamoservice.getDataCall(urlVehi).then(function(res){
			var sp;
			var latlan = [];
			$scope.slatlong = {};
			$scope.elatlong = {};
			$scope.polyline = {};
			// var latLngOld= ["20.345136,74.180436","20.344211,74.18148","20.343227,74.182578","20.342202,74.183724","20.3412,74.184862","20.34022,74.185991","20.339247,74.187102","20.338271,74.188196","20.337422,74.189111","20.336598,74.190031","20.336598,74.190031"]
			for (var i = 0; i < res.latLngOld.length; i++) {
				sp = res.latLngOld[i].split(',');
				lineDraw(sp[0], sp[1], screen);
			}
		})
		selectingScreen(screen, response, vehicleid)
			
	})


}


(function init() {
	var res = document.location.href.split("=");
	var vehicleno = res[1].trim();
	$scope.getVehiLoc = getVehicleLocations;
	var url = getSelectedVehicleLocation+vehicleno;
	var urlVeh = getSelectedVehicleLocation1+vehicleno;
	try{
		startLoading();
		serviceCall('screen1', url, urlVeh, vehicleno)
		vamoservice.getDataCall($scope.getVehiLoc).then(function(data){
			$scope.sliceGroup = filterGroup(data)
			// $scope.getVehicle = data
		})
	} catch (err){
		console.log('print err'+err)
		stopLoading();
	}
}());

function emptyJob(screenNo)
{
	switch (screenNo){
		case 'screen1':
			path1 =[];
			obj1 =[];
			break;
		case 'screen2':
			path2 =[];
			obj2 =[];
			break;
		case 'screen3':
			path3 =[];
			obj3 =[];
			break;
		case 'screen4':
			path4 =[];
			obj4 =[];
			break;
		default:
			break;
	}
}

$scope.multiTracking = function(vehicle, screenNo)
{	startLoading();
	try
	{
		emptyJob(screenNo);
		serviceCall(screenNo, getSelectedVehicleLocation+vehicle.vehicleId, getSelectedVehicleLocation1+vehicle.vehicleId, vehicle.vehicleId);
	} catch (er) {
		console.log(' print error '+er)
		stopLoading();
	}
}

// for connect two lines
function drawLine(loc1, loc2, maps){
	var flightPlanCoordinates = [loc1, loc2];
	var flightPath = new google.maps.Polyline({
		map: maps,
		path: flightPlanCoordinates,
		strokeColor: '#00b3fd',
		strokeOpacity: 0.7,
		strokeWeight: 5,
		clickable: true
	});
	
}

function intervalServiceCall(obj, marker, maps, path, screen){
	// marker.setMap(null);
	try{
		vamoservice.getDataCall(getSelectedVehicleLocation+obj.vehicleId).then(function(response){
			// console.log(' hi arun nice coding '+obj.vehicleId)
			drawLine(new google.maps.LatLng(path[0], path[1]), new google.maps.LatLng(response.latitude,response.longitude), maps);
			marker.setPosition(new google.maps.LatLng(response.latitude,response.longitude));
			// marker.set({labelContent: ' '});
			// marker.set({labelContent:   'Vehicle Name - '+response.shortName+'<br>'+'Speed - '+response.speed+' kms/h'+'<br>'+'odoDistance - '+response.odoDistance+'<br>'+'Address - '+response.address});
			marker.labelContent = 'Vehicle Name - '+response.shortName+'<br>'+'Speed - '+response.speed+' kms/h'+'<br>'+'odoDistance - '+response.odoDistance+'<br>'+'Address - '+resolveAddress(response);
			marker.setMap(maps)
			maps.setCenter(new google.maps.LatLng(response.latitude,response.longitude));
				
			// maps.setZoom(13);
			// path.push(response.latitude,response.longitude);
			switch (screen){
				case 'screen1':
					path1 =[];
					path1.push(response.latitude,response.longitude);
					break;
				case 'screen2':
					path2 =[];
					path2.push(response.latitude,response.longitude);
					break;
				case 'screen3':
					path3 =[];
					path3.push(response.latitude,response.longitude);
					break;
				case 'screen4':
					path4 =[];
					path4.push(response.latitude,response.longitude);
					break;
				default:
					break;
			}
		})

	} catch (err){
		console.log(' print err  '+err);
	}
	
	
}

setInterval(function() {
	// marker4.setMap(null);
	if(typeof obj1.vehicleId === 'string')
		intervalServiceCall(obj1, marker1, $scope.screen1, path1, 'screen1');
	if(typeof obj2 !== 'undefined')
		if(typeof obj2.vehicleId === 'string')
			intervalServiceCall(obj2,marker2, $scope.screen2, path2, 'screen2');
	if(typeof obj3 !== 'undefined')
		if(typeof obj3.vehicleId === 'string')
			intervalServiceCall(obj3, marker3, $scope.screen3, path3, 'screen3');
	if(typeof obj4 !== 'undefined')
		if(typeof obj4.vehicleId === 'string')
			intervalServiceCall(obj4, marker4, $scope.screen4, path4, 'screen4');
},10000);

});
