//comment by satheesh ++...
var setintrvl;
app.filter('statusfilter', function(){
	return function(obj, param){
		 var out = [];
	   if(param=='ALL'){
	   		out= obj;
	   		return out;  
	   }else if(param=='ON' || param=='OFF'){
		    for(var i=0; i<obj.length; i++){
			    if(obj[i].status == param){
			    	out.push(obj[i]);
			    }
		    }
		    return out;  
	   }else{
	   		if(param=='O'){
	   			for(var i=0; i<obj.length; i++){
				    if(obj[i].isOverSpeed == 'Y'){
				    	out.push(obj[i]);
				    }
			 	}
			 	return out;
	   		}else{
		   		for(var i=0; i<obj.length; i++){
				    if(obj[i].position == param){
				    	out.push(obj[i]);
				    }
			 	}
			 	return out;
			}
	   }
  	}
}).directive('trafficLayer', function() {
	return {
		restrict:'E',
		replace:true,
		template:'<input type="button" id="traffic" ng-click="trafficEnable()" value="Traffic" />',
		controller:function($scope){
			$scope.trafficLayer = new google.maps.TrafficLayer();
			$scope.checkVal=false;
			$scope.trafficEnable = function(){
				if($scope.checkVal==false){
					$scope.trafficLayer.setMap($scope.map);
					$scope.checkVal = true;
				}else{
					$scope.trafficLayer.setMap(null);
					$scope.checkVal = false;
				}
			}
		}
	}
})
.controller('mainCtrl',['$scope', '$http','vamoservice', function($scope, $http, vamoservice, $filter, statusfilter){
	$scope.locations = [];
	$scope.nearbyLocs =[];
	$scope.mapTable =[];
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
	$scope.url = 'http://'+globalIP+context+'/public//getVehicleLocations';
	$scope.historyfor='';
	$scope.map =  null;
	$scope.flightpathall = []; 
	$scope.clickflag = false;
	$scope.flightPath = new google.maps.Polyline();
	$scope.trafficLayer = new google.maps.TrafficLayer();
	$scope.checkVal=false;
	$scope.clickflagVal =0;
	$scope.nearbyflag = false;
	$scope.groupMap=false;
	var tempdistVal = 0;
	var mcOptions={};
	var markerCluster;
	//$scope.locations01 = vamoservice.getDataCall($scope.url);
	$scope.trimColon = function(textVal){
		return textVal.split(":")[0].trim();
	}				
	$scope.$watch("url", function (val) {
		vamoservice.getDataCall($scope.url).then(function(data) {
			$scope.selected=undefined;
			$scope.vehicle_list=[];
			$scope.fcode=[];
			$scope.locations02 = data;
			listVehicleName(data);
			if(data.length){
				$scope.mapTable = data[$scope.gIndex].vehicleLocations;
				$scope.vehiname	= data[$scope.gIndex].vehicleLocations[0].vehicleId;
				$scope.locations = $scope.statusFilter($scope.locations02[$scope.gIndex].vehicleLocations, $scope.vehicleStatus);
				$scope.zoomLevel = 6;
				$scope.support = data[$scope.gIndex].supportDetails;
				$scope.initilize('map_canvas');
			}
		});	
	});
	// console.log($scope.locations02)
	$scope.$watch("vehicleStatus", function (val) {
		if($scope.locations02!=undefined){
			$scope.selected=undefined;
			$scope.locations = $scope.statusFilter($scope.locations02[$scope.gIndex].vehicleLocations, val);
			$scope.initilize('map_canvas');
		}
	});
	
	$scope.statusFilter = function(obj, param){
	 	var out = [];
	   	if(param=='ALL'){
	   		out= obj;
	   		return out;  
	   	}else if(param=='ON' || param=='OFF'){
		    for(var i=0; i<obj.length; i++){
			    if(obj[i].status == param){
			    	out.push(obj[i]);
			    }
		    }
		    return out;  
	   	}else{
	   		if(param=='O'){
	   			for(var i=0; i<obj.length; i++){
				    if(obj[i].isOverSpeed == 'Y'){
				    	out.push(obj[i]);
				    }
			 	}
			 	return out;
	   		}else{
		   		for(var i=0; i<obj.length; i++){
				    if(obj[i].position == param){
				    	out.push(obj[i]);
				    }
			 	}
			 	return out;
			}
	   }
	}
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
		editableValue();
	}
	//for edit details in the right side div
	document.getElementById("inputEdit").disabled = true;
	function editableValue()
	{
		document.getElementById("inputEdit").disabled = false;
	}

	//update function in the right side div
	$scope.updateDetails	= 	function()
	{
		var URL_ROOT = "vdmVehicles/"; 
		$.post( URL_ROOT+'updateLive/'+$scope.vehicleid, {
        '_token': $('meta[name=csrf-token]').attr('content'),
  		'shortName':$scope.vehShort,
        'odoDistance': $scope.ododis,
  	    'overSpeedLimit':$scope.spLimit,
        'driverName': $scope.driName,
        'regNo': $scope.refname,
		'vehicleType':$scope.vehType,
		})
	    .done(function(data) {
    	updateCall();
        console.log("Sucess");
	    })
	    .fail(function() {
 		updateCall();
        console.log("fail");
 	    });
 	    
		document.getElementById("inputEdit").disabled = false;
		$("#editable").hide();
		$("#viewable").show();
	}


	function updateCall()
	{
		var url = 'http://'+globalIP+context+'/public//getVehicleLocations';
		$http.get(url).success(function(response){
			for (var i = 0; i < response[$scope.gIndex].vehicleLocations.length; i++) 
			{
				if($scope.vehicleid == response[$scope.gIndex].vehicleLocations[i].vehicleId)
				$scope.assignValue(response[$scope.gIndex].vehicleLocations[i])
			};
		})
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
	
	// clusterMarker 
	$scope.clusterMarker = function()
	{
		$scope.groupMap=true;
		var mcOptions = {gridSize: 50,maxZoom: 15}
		var markerCluster 	= new MarkerClusterer($scope.map, gmarkers, mcOptions)	
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
	function utcdateConvert(milliseconds){
		//var milliseconds=1440700484003;
		var offset='+10';
		var d = new Date(milliseconds);
		utc = d.getTime() + (d.getTimezoneOffset() * 60000);
		nd = new Date(utc + (3600000*offset));
		var result=nd.toLocaleString();
		return result;
	}
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
		$scope.selected=undefined;
		$scope.dynamicvehicledetails1=false;
		$scope.url = 'http://'+globalIP+context+'/public//getVehicleLocations?group=' + groupname;
		$scope.gIndex = groupid;
		
		$scope.mapTable = $scope.locations02[groupid].vehicleLocations;
		gmarkers=[];
		for(var i=0; i<ginfowindow.length;i++){		
			ginfowindow[i].setMap(null);
		}
		ginfowindow=[];
		clearInterval(setintrvl);
		//$scope.locations02 = vamoservice.getDataCall($scope.url);
		 	
	}
	

	$scope.infoBoxed = function(map, marker, vehicleID, lat, lng, data){
		var tempoTime = vamoservice.statusTime(data);
		if(data.ignitionStatus=='ON'){
			var classVal = 'green';
		}else{
			var classVal = 'red';
		}
			var contentString = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
		+'<div><b style="width:100px; display:inline-block;">Vehicle Name</b> - <span style="font-weight:bold;">'+data.shortName+'</span></div>'
		
		+'<div><b style="width:100px; display:inline-block;">ODO Distance</b> - '+data.odoDistance+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
		+'<div><b style="width:100px; display:inline-block;">Today Distance</b> - '+data.distanceCovered+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
		+'<div><b style="width:100px; display:inline-block;">ACC Status</b> - <span style="color:'+classVal+'; font-weight:bold;">'+data.ignitionStatus+'</span> </div>'

		+'<div><a href="../public/track?vehicleId='+vehicleID+'" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/replay?vehicleId='+vehicleID+'" target="_self">History</a>&nbsp;&nbsp;'
		+'</div>';
		
		// var	drop1 = document.getElementById("ddlViewBy");
		// var drop_value1= drop1.options[drop1.selectedIndex].value;
		var infowindow = new InfoBubble({
		maxWidth: 400,	
		maxHeight:170,
		 content: contentString
		});
		ginfowindow.push(infowindow);
	  	(function(marker) {
			google.maps.event.addListener(marker, "click", function(e) {
				for(var j=0; j<ginfowindow.length;j++){
					ginfowindow[j].close();
				}
				infowindow.open(map,marker);
	   		});	
		})(marker);
	}

	// for new window track
	$scope.days=0;
	$scope.days1=0;
	$scope.vehicle_list=[];
	$scope.fcode=[];
	$scope.final_data;
	// for list of vehicles
	// $http.get('http://'+globalIP+'/vamo/public//getVehicleLocations').success(function(data)
	function listVehicleName(data)
	{
		for (var i = 0; i < data[$scope.gIndex].vehicleLocations.length; i++) 
		{
			$scope.vehicle_list.push({'vehiID' : data[$scope.gIndex].vehicleLocations[i].vehicleId, 'vName' : data[$scope.gIndex].vehicleLocations[i].shortName})
		};
		$scope.fcode.push(data[$scope.gIndex])
	}


	//split methods
	$scope.split_fcode = function(fcode){
		var str = $scope.fcode[0].group;
		var strFine = str.substring(str.lastIndexOf(':'));
		while(strFine.charAt(0)===':')
		strFine = strFine.substr(1);
		return strFine;
	}
	
	//encryt url
	function encrypt_window(url)
	{
		$http.get(url).success(function(data){
				// console.log('---->'+url)
				// console.log(' encript code '+data)
				// //$scope.final_data = data;
			})
			//ecrypt_code_url = 'http://'+globalIP+'/vamo/public/getPublicTracking?enryptedID='+result;
	}
	//
	$scope.getMailIdPhoneNo = function(vehi, days)
	{
		//console.log('inside the methods')
		var mailId = document.getElementById("mail").value;
		var phone  = document.getElementById("phone").value;
		if(vehi == 0 && days ==0)
		console.log('select correctly')	
		else
		{
			$scope.split_fcode($scope.fcode[0].group);
			var f_code = $scope.split_fcode($scope.fcode[0].group);
			var f_code_url ='http://'+globalIP+context+'/public/getVehicleExp?vehicleId='+vehi+'&fcode='+f_code+'&days='+days+'&mailId='+mailId+'&phone='+phone;
			var ecrypt_code_url = '';
			$http.get(f_code_url).success(function(result){
				
				$scope.final_data = result;
				
    			var url='../public/track?vehicleId='+result.trim();
				window.open(url,'_blank');
				
			})
	}
}
	
				
 //    			var url='../public/track?vehicleId='+result.trim();
	// 			window.open(url,'_blank');
	// 			//$('body').append(atag);
	// 			//$('#sam').trigger('click');
	// 			//document.location.href="/live_track?encyID="+result;
	// 			//encrypt_window(ecrypt_code_url);
	// 		})
	
	$scope.addMarker= function(pos){
	    
	    var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
	    var labelAnchorpos = new google.maps.Point(19, 0);	///12, 37
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
		// $scope.marl.push($scope.marker);
		google.maps.event.addListener(gmarkers[gmarkers.length-1], "click", function(e){	
			
			$scope.vehicleno = pos.data.vehicleId;
			$scope.assignValue(pos.data);
			editableValue();
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
		$scope.vehicleid = dataVal.vehicleId;
		$scope.vehShort  = dataVal.shortName;
		$scope.ododis 	 = dataVal.odoDistance;
		$scope.spLimit   = dataVal.overSpeedLimit;
		$scope.driName 	 = dataVal.driverName;
		$scope.refname 	 = dataVal.regNo;
		$scope.vehType   = dataVal.vehicleType;
		$('#vehiid #val').text(dataVal.shortName);
		$('#toddist #val').text(dataVal.distanceCovered);
		$('#vehstat #val').text(dataVal.position);
		total = parseInt(dataVal.speed);
		$('#vehdevtype #val').text(dataVal.odoDistance);
		$('#mobno #val').text(dataVal.overSpeedLimit);
		$('#positiontime #val').text(vamoservice.statusTime(dataVal).tempcaption);
		$('#regno #val').text(vamoservice.statusTime(dataVal).temptime);
		$('#driverName #val').text(dataVal.driverName);
		//var t0 = new Date(utcdateConvert(dataVal.date)).toString();
		// var t1 = Date.parse(t0.toUTCString().replace('GMT', ''));
    	//var t2 = (2 * t0) - t1;
    //	var ldt = new Date(dataVal.date).toString().split('GMT')
    //	console.log(t[0]);
    //	console.log(Date(t2).toString());
    
		$('#lstseendate').html(new Date(dataVal.date).toString().split('GMT')[0])
	//	new Date(now.toUTCString())
	//	$('#lstseendate').html(utcdateConvert(dataVal.date));
	}
	
	$scope.enterkeypress = function(){
		var url = 'http://'+globalIP+context+'/public//setPOIName?vehicleId='+$scope.vehicleno+'&poiName='+document.getElementById('poival').value;
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
				
			}
			for(var i=0; i<geoinfo.length; i++){
				geoinfo[i].setMap(null);
			}
			
		}
		vamoservice.getDataCall(url).then(function(data) {
			$scope.geoloc = data;
			if (typeof(data.geoFence) !== 'undefined' && data.geoFence.length) {	
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
				  	geomarker.push(beachMarker);
					}
					var myOptions = { 
					 	 content: labelText, 
					 	 boxStyle: {
					 	 	textAlign: "center", 
					 	 	fontSize: "9pt", 
					 	 	fontColor: "#ff0000", 
					 	 	width: "100px"
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
					// labelinfo.setPosition($scope.cityCircle[i].getCenter());
					 geoinfo.push(labelinfo);
				}
			}
		});
	}
	
	$scope.initial02 = function(){
		//console.log(' marker click ')
		$scope.assignHeaderVal($scope.locations02);
		var locs = $scope.locations;
	 	var parkedCount = 0;
		var movingCount = 0;
		var idleCount = 0;
		var overspeedCount = 0;
		
		

		for (var i = 0; i < $scope.locations02[$scope.gIndex].vehicleLocations.length; i++) {
			if($scope.locations02[$scope.gIndex].vehicleLocations[i].position=="P"){
				parkedCount=parkedCount+1;
			}else  if($scope.locations02[$scope.gIndex].vehicleLocations[i].position=="M"){
				movingCount=movingCount+1;
			}else if($scope.locations02[$scope.gIndex].vehicleLocations[i].position=="S"){
				idleCount=idleCount+1;
			}
			if($scope.locations02[$scope.gIndex].vehicleLocations[i].isOverSpeed=='Y'){
				overspeedCount=overspeedCount+1;
			}
		}
		
		$scope.parkedCount = parkedCount;
		$scope.movingCount = movingCount;
		$scope.idleCount  = idleCount;
		$scope.overspeedCount = overspeedCount;
		
		for (var i = 0; i < gmarkers.length; i++) {
			var temp = $scope.locations[i];	 
			 var lat = temp.latitude;
			 var lng =  temp.longitude;
			 var latlng = new google.maps.LatLng(lat,lng);
			 gmarkers[i].icon = vamoservice.iconURL(temp);
			 gmarkers[i].setPosition(latlng);
			 gmarkers[i].setMap($scope.map);
			 if(temp.vehicleId==$scope.vehicleno){
				 $scope.assignValue(temp);
				 $scope.selected=i;
				 $scope.getLocation(lat, lng, function(count){
					 $('#lastseen').text(count);
					 var t = vamoservice.geocodeToserver(lat,lng,count);
				 });	
			 }
			 //$scope.infoBoxed($scope.map,gmarkers[i], temp.vehicleId, lat, lng, temp);
		}	
		 markerCluster.clearMarkers();
		if($scope.groupMap==true)
		{
			
			mcOptions = {gridSize: 50, maxZoom: 15};
			markerCluster 	= new MarkerClusterer($scope.map, gmarkers, mcOptions) 	
		}
		// else if($scope.groupMap == false)
		// {
		// 	markerCluster.clearMarkers(null);
		// }
		if($scope.selected!=undefined){
			$scope.map.setCenter(gmarkers[$scope.selected].getPosition()); 	
		}
	}
	
	$scope.initilize = function(ID){
		
	//	vamoservice.getDataCall($scope.url).then(function(location02) {
			var location02 = $scope.locations02;
			if($('.nav-second-level li').eq($scope.selected).children('a').hasClass('active')){
			}else{
				$('.nav-second-level li').eq($scope.selected).children('a').addClass('active');
			}
			var locs = $scope.locations;
			$scope.assignHeaderVal(location02);
			
			var lat = location02[$scope.gIndex].latitude;
			var lng = location02[$scope.gIndex].longitude;
			 var myOptions = { zoom: $scope.zoomLevel, zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, center: new google.maps.LatLng(lat, lng), mapTypeId: google.maps.MapTypeId.ROADMAP/*,styles: [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]*/};
			$scope.map = new google.maps.Map(document.getElementById(ID), myOptions);
			
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
					var tempurl = 'http://'+globalIP+context+'/public//getNearByVehicles?lat='+event.latLng.lat()+'&lng='+event.latLng.lng();
					
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
			});
			var parkedCount = 0;
			var movingCount = 0;
			var idleCount = 0;
			var overspeedCount =0;
			
			for (var i = 0; i < location02[$scope.gIndex].vehicleLocations.length; i++) {
				if(location02[$scope.gIndex].vehicleLocations[i].position=="P"){
					parkedCount=parkedCount+1;
				}else  if(location02[$scope.gIndex].vehicleLocations[i].position=="M"){
					movingCount=movingCount+1;
				}else if(location02[$scope.gIndex].vehicleLocations[i].position=="S"){
					idleCount=idleCount+1;
				}
				if($scope.locations02[$scope.gIndex].vehicleLocations[i].isOverSpeed=='Y'){
					overspeedCount=overspeedCount+1;
				}
			}
			
			$scope.parkedCount = parkedCount;
			$scope.movingCount = movingCount;
			$scope.idleCount  = idleCount;
			$scope.overspeedCount = overspeedCount;
			
			var length = locs.length;
			gmarkers=[];
			ginfowindow=[];
			for (var i = 0; i < length; i++) {
				var lat = locs[i].latitude;
				var lng =  locs[i].longitude;
				$scope.addMarker({ lat: lat, lng: lng , data: locs[i]});
				$scope.infoBoxed($scope.map,gmarkers[i], locs[i].vehicleId, lat, lng, locs[i]);
			}
	//	});
		$scope.loading	=	false;
		if($scope.selected>-1 && gmarkers[$scope.selected]!=undefined){
			$scope.map.setCenter(gmarkers[$scope.selected].getPosition()); 	
		}
		$(document).on('pageshow', '#maploc', function(e){       
        	google.maps.event.trigger(document.getElementById('	maploc'), "resize");
   		});
   		// var mcOptions = {gridSize: 50, maxZoom: 15};
//    		var mcOptions = {styles: [{
// height: 100,
// url: "assets/imgs/c1.png",
// width: 100
// },
// {
// height: 100,
// url: "assets/imgs/c2.png",
// width: 100
// },
// {
// height: 100,
// url: "assets/imgs/c3.png",
// width: 100
// },
// {
// height: 100,
// url: "assets/imgs/c4.png",
// width: 100
// },
// {
// height: 100,
// url: "assets/imgs/c5.png",
// width: 100
// }]}
	// if($scope.groupMap==true)
	// 		{
	// 			var mcOptions={};
	// 			var markerCluster = null;
	// 			markerCluster.setMap(null);
	// 			markerCluster.setGmarkers(null);
	// 			mcOptions = {gridSize: 50, maxZoom: 15};
	// 			markerCluster 	= new MarkerClusterer($scope.map, gmarkers, mcOptions) 	
	// 		}
		
	}
	
	//click and resolve address
	$scope.address_click = function(data, ind)
	{
		console.log(' inside the address function')
		var tdurl 		=	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+data.latitude+','+data.longitude+"&sensor=true"
		vamoservice.getDataCall(tdurl).then(function(response) {
			console.log(response)
			// data.address 	=	response.results[0].formatted_address;
			// var t 			= 	vamo_sysservice.geocodeToserver(data.latitude,data.longitude,response.results[0].formatted_address);
		});
	}

	$scope.removeTask=function(vehicleno){
		$scope.vehicleno = vehicleno;
		var temp = $scope.locations;
		//$scope.endlatlong = new google.maps.LatLng();
		//$scope.startlatlong = new google.maps.LatLng();
		$scope.map.setZoom(19);
		$scope.dynamicvehicledetails1=true;
		for(var i=0; i<temp.length;i++){
			if(temp[i].vehicleId==$scope.vehicleno){
				
				$scope.selected=i;
				
				$scope.map.setCenter(gmarkers[i].getPosition());
				
				$scope.assignValue(temp[i]);
				$scope.getLocation(temp[i].latitude, temp[i].longitude, function(count){ 
					$('#lastseen').text(count); 
				});
				$scope.infowindowShow={};				
				for(var j=0; j<ginfowindow.length;j++){
					ginfowindow[j].close();
				}
				ginfowindow[i].open($scope.map,gmarkers[i]);
				var url = 'http://'+globalIP+context+'/public//getGeoFenceView?vehicleId='+$scope.vehicleno;
				$scope.createGeofence(url);
				
			}
		}
	};
	$('#mapTable-mapList').hide()
	$("#homeImg").hide();
	$("#listImg").show();
	$("#single").hide();
	$("#cluster").show();
	$("#efullscreen").hide();
	$("#fullscreen").show();
	// $scope.idinvoke;
	//list view
	function listMap ()
	{
		setId();
		$("#listImg").hide();
		$("#homeImg").show();
		$("#efullscreen").hide();
		$("#fullscreen").show();
		$('#mapTable-mapList').show(1000);
		$("#contentmin").hide(500);
		$("#sidebar-wrapper").hide(500);
		document.getElementById($scope.idinvoke).setAttribute("id", "mapList");
	}

	//return home
	function homeMap ()
	{
		setId();
		$("#listImg").show();
		$("#homeImg").hide();
		$("#efullscreen").hide();
		$("#fullscreen").show();
		$('#mapTable-mapList').hide(500);
		$("#contentmin").show(1000);
		$("#sidebar-wrapper").show(500);
		document.getElementById($scope.idinvoke).setAttribute("id", "wrapper");
	}

	
	// clusterMarker 
	function clusterMarker()
	{
		$("#cluster").hide();
		$("#single").show();
		$scope.groupMap=true;
		// markerCluster 	= new MarkerClusterer($scope.map, null, null)

		mcOptions = {gridSize: 50,maxZoom: 15}
		markerCluster 	= new MarkerClusterer($scope.map, gmarkers, mcOptions)	
	}

	//sigle
	function sigleMarker()
	{
		$("#single").hide();
		$("#cluster").show();
		$scope.groupMap=false;
		markerCluster.clearMarkers();
		console.log(gmarkers.length)
		for(var i=0; i<gmarkers.length; i++)
		{
			gmarkers[i].setMap($scope.map);
		}
	}

	function fullScreen()
	{
		setId();
		$("#fullscreen").hide();
		$("#efullscreen").show();

		document.getElementById($scope.idinvoke).setAttribute("id", "sidebar-fullscreen");
		$("#contentmin").hide(1000);
		$("#sidebar-wrapper").hide(500);
		$("#sidebar-fullscreen").show(500);
	}

	function exitScreen()
	{
		setId();
		$("#efullscreen").hide();
		$("#fullscreen").show();
		// $("#listImg").show();
		// $("#homeImg").hide();
		document.getElementById($scope.idinvoke).setAttribute("id", "wrapper");
		$("#contentmin").show(1000);
		$("#sidebar-wrapper").show(500);

	}

	function setId()
	{
		if(document.getElementById("wrapper")!=null)
			$scope.idinvoke = document.getElementById("wrapper").id;
		else if(document.getElementById("sidebar-fullscreen")!=null)
			$scope.idinvoke = document.getElementById("sidebar-fullscreen").id;
		else if(document.getElementById("mapList")!==null)
			$scope.idinvoke = document.getElementById("mapList").id;


	}

	//view map
	$scope.mapView 	=	function(value)
	{
		switch(value){
			case 'listMap' :
				listMap();
				break;
			case 'home' :
				homeMap();
				break;
			case 'cluster' :
				clusterMarker();
			 	break;
			case 'single' :
				sigleMarker();
				break;
			case 'fscreen' :
				fullScreen();
				break;
			case 'escreen':
				exitScreen();
				break;
			default:
		   		break;
		}
	}

	//mouse over in list view table
	$scope.mouseJump  = function(user)
	{
		for(var i = 0; i <gmarkers.length; i++)
		{
	   		if(gmarkers[i].labelContent == user.shortName)
	   		gmarkers[i].setAnimation(google.maps.Animation.BOUNCE);
	   		gmarkers[i].setAnimation(null);
	   		
	   }
	}
	
	// table td click 
	$scope.tabletd = function(val)
	{
		for (var i = 0; i <gmarkers.length; i++) 
		{

			if(gmarkers[i].labelContent == val.shortName)
			{
				$scope.map.setZoom(19);
				$scope.map.setCenter(gmarkers[i].getPosition());
				gmarkers[i].setAnimation(google.maps.Animation.BOUNCE);
	   			gmarkers[i].setAnimation(null);
			}
		};
	}

	$scope.infowindowshowFunc = function(){
		for(var j=0; j<ginfowindow.length;j++){
			ginfowindow[j].close();
		}
		var tempoTime = vamoservice.statusTime($scope.infowindowShow.dataTempVal);
		var contentString = '<div style="padding:10px; width:200px; height:auto;">'
		+'<div><b>Vehicle ID</b> - '+$scope.infowindowShow.dataTempVal.vehicleId+'('+$scope.infowindowShow.dataTempVal.shortName+')</div>'
		+'<div><b>Speed</b> - '+$scope.infowindowShow.dataTempVal.speed+'</div>'
		+'<div><b>odoDistance</b> - '+$scope.infowindowShow.dataTempVal.odoDistance+'</div>'
		+'<div><b>Distance Covered</b> - '+$scope.infowindowShow.dataTempVal.distanceCovered+'</div>'
		+'<div><b>'+tempoTime.tempcaption+' Time</b> - '+tempoTime.temptime+'</div><br>'
		+'<div><a href="../public/track?vehicleId='+$scope.infowindowShow.dataTempVal.vehicleId+'" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/replay?vehicleId='+$scope.infowindowShow.dataTempVal.vehicleId+'" target="_self">History</a></div>'
		+'</div>';
		$scope.infowindowShow.currinfo.setContent(contentString);
		$scope.infowindowShow.currinfo.open($scope.map,$scope.infowindowShow.currmarker);
	}
	$scope.assignHeaderVal = function(data){
		$scope.distanceCovered =data[$scope.gIndex].distance;
		$scope.alertstrack = data[$scope.gIndex].alerts;
		$scope.totalVehicles  =data[$scope.gIndex].totalVehicles;
		$scope.attention  =data[$scope.gIndex].attention;
		$scope.vehicleOnline =data[$scope.gIndex].online;
	}
}])

.directive('ngEnter', function () {
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
        	scope.$watch("url", function (val) {
			setintrvl = setInterval(function(){
				vamoservice.getDataCall(scope.url).then(function(data) {
					if(data.length){
						scope.selected=undefined;
						scope.locations = scope.statusFilter(scope.locations02[scope.gIndex].vehicleLocations, scope.vehicleStatus);
						scope.zoomLevel = scope.zoomLevel;
						//scope.initial02();
						scope.loading	=	true;
						//scope.initilize('map_canvas');
						scope.initial02();
					}
				}); 
			},600000);
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
            center: ['50%', '90%'],
            size: '180%',
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
            max: 120,
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
