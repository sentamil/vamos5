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
});
 
app.controller('mainCtrl',['$scope', '$compile','$http','vamoservice','$filter', '_global', function($scope,$compile, $http, vamoservice, $filter, GLOBAL){
	
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
	$scope.url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
	$scope.historyfor='';
	$scope.map =  null;
	$scope.flightpathall = []; 
	$scope.clickflag = false;
	$scope._addPoi 	= 	false;
	$scope.checkVal=false;
	$scope.clickflagVal =0;
	$scope.nearbyflag = false;
	$scope.groupMap=false;
	$scope.vehiclFuel=true;
	var tempdistVal = 0;
	// var mcOptions={};
	var markerCluster;
	var vehicleids=[];
	var polygenList=[];
	
	$scope.orgIds 	= [];

	var mcOptions = {
    maxZoom: 11,
    styles: [
      {
      height: 53,
      url: "assets/imgs/m1.png",
      width: 53
      },
      {
      height: 56,
      url: "assets/imgs/m2.png",
      width: 56
      },
      {
      height: 66,
      url: "assets/imgs/m3.png",
      width: 66
      }
    ]
  };
	
	//var menuVid;
	
	$scope.trimColon = function(textVal){
		return textVal.split(":")[0].trim();
	}

	$scope.sort = {       
                sortingOrder : 'lastSeen',
                reverse : false
            };

    function sessionValue(vid, gname){
		sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
		$("#testLoad").load("../public/menu");
	}

var markerSearch = new google.maps.Marker({});
// var markerSearch =[];


  
  
  

	$scope.$watch("url", function (val) {
		vamoservice.getDataCall($scope.url).then(function(data) {
			$scope.selected=undefined;
			$scope.vehicle_list=[];
			$scope.fcode=[];
			$scope.locations02 = data;
			listVehicleName(data);
			// menuGroup(data);
			if(data.length){

				$scope.mapTable = data[$scope.gIndex].vehicleLocations;
				// console.log(document.getElementById('one').innerText)
				$scope.vehiname	= data[$scope.gIndex].vehicleLocations[0].vehicleId;
				$scope.gName 	= data[$scope.gIndex].group;
				sessionValue($scope.vehiname, $scope.gName)
				$scope.locations = $scope.statusFilter($scope.locations02[$scope.gIndex].vehicleLocations, $scope.vehicleStatus);
				$scope.zoomLevel = 6;
				$scope.support = data[$scope.gIndex].supportDetails;
				$scope.initilize('maploc');
				// if($scope.makerType == undefined)
				markerChange($scope.makerType);
			}
		});	
	});
    

	$scope.$watch("vehicleStatus", function (val) {
		if($scope.locations02!=undefined){
			$scope.selected=undefined;
			$scope.locations = $scope.statusFilter($scope.locations02[$scope.gIndex].vehicleLocations, val);
			$scope.initilize('maploc');
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


	function fetchingAddress(pos){

		if(pos.address == null || pos.address == undefined || pos.address == ' ')
			$scope.getLocation(pos.latitude, pos.longitude, function(count){ 
				$('#lastseen').text(count); 
			});
		else
			$('#lastseen').text(pos.address.split('<br>Address :')[1] ? pos.address.split('<br>Address :')[1] : pos.address);
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
	

	$scope.valueCheck = function(vale)
	{
		if(vale == 'nill' || vale == '0.0')return '--';
		else if (vale !='nill' || vale != '0.0')return vale;
	}

	$scope.genericFunction = function(vehicleno, index){
		// angular.forEach($scope.locations, function(value, key){
			$scope.selected = index;
			var individualVehicle = $filter('filter')($scope.locations, { vehicleId:  vehicleno});
			if (individualVehicle[0].position === 'N' || individualVehicle[0].position === 'Z')
			{
				
				$('#status').fadeOut(); 
				$('#preloader').delay(350).fadeOut('slow');
				$('body').delay(350).css({'overflow':'visible'});
				alert(individualVehicle[0].address);
			}
			else
			{
				$scope.removeTask(vehicleno);
				sessionValue(vehicleno, $scope.gName);

				$scope.vehiclFuel=graphChange(individualVehicle[0].fuelLitre);
				if($scope.vehiclFuel==true){
                    $('#graphsId').removeClass('graphsCls');
                }else{
					$('#graphsId').addClass('graphsCls');
			    }
                $('#graphsId').show();

				editableValue();
			}
		// })	
		
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
		if((checkXssProtection($scope.ododis) == true) && (checkXssProtection($scope.vehShort) == true) && (checkXssProtection($scope.spLimit) == true) && (checkXssProtection($scope.driName) == true) && (checkXssProtection($scope.mobileNo) == true) && (checkXssProtection($scope.refname) == true))
		{	
			var URL_ROOT = "vdmVehicles/"; 
			$.post( URL_ROOT+'updateLive/'+$scope.vehicleid, {
	        '_token': $('meta[name=csrf-token]').attr('content'),
	  		'shortName':$scope.vehShort,
	        'odoDistance': $scope.ododis,
	  	    'overSpeedLimit':$scope.spLimit,
	        'driverName': $scope.driName,
	        'mobileNo' :$scope.mobileNo,
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
	}


	function updateCall()
	{
		var url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
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
		var mcClusterIconFolder = "assets/imgs";
  var mcOptions = {
    maxZoom: 11,
    styles: [
      {
      height: 53,
      url: mcClusterIconFolder + "/m1.png",
      width: 53
      },
      {
      height: 56,
      url: mcClusterIconFolder + "/m2.png",
      width: 56
      },
      {
      height: 66,
      url: mcClusterIconFolder + "/m3.png",
      width: 66
      },
      {
      height: 78,
      url: mcClusterIconFolder + "/m4.png",
      width: 78
      },
      {
      height: 90,
      url: mcClusterIconFolder + "/m5.png",
      width: 90
      }
    ]
  };
		// var mcOptions = {gridSize: 50,maxZoom: 15, imagePath: 'https://raw.githubusercontent.com/googlemaps/js-marker-clusterer/gh-pages/images/m1.png'}
		
		// var markerCluster 	= new MarkerClusterer($scope.map, gmarkers, { 
  //   		imagePath: 'assets/imgs/m1.png' 
		// });	
		 var markerCluster 	= new MarkerClusterer($scope.map, gmarkers,{
    imagePath: 'https://rawgit.com/googlemaps/js-marker-clusterer/gh-pages/images/m1.png' 
});
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
		$('#status').show();
    	$('#preloader').show();
		$scope.selected=undefined;
		$scope.dynamicvehicledetails1=false;
		$scope.url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group=' + groupname;
		
		$scope.gIndex = groupid;
		
		$scope.mapTable = $scope.locations02[groupid].vehicleLocations;
		// gmarkers=[];
		for(var i=0; i<ginfowindow.length;i++){		
			ginfowindow[i].setMap(null);
		}
		// ginfowindow=[];
		clearInterval(setintrvl);
		markerChange($scope.makerType);
		//$scope.locations02 = vamoservice.getDataCall($scope.url);
		// $('#status').fadeOut(); 
		// $('#preloader').delay(350).fadeOut('slow');
		// $('body').delay(350).css({'overflow':'visible'});
	}
	var modal = document.getElementById('poi');
	var span = document.getElementsByClassName("poi_close")[0];
	
	function popUp_Open_Close(){

		modal.style.display = "block";
		modal.style.zIndex= 99999;
		span.onclick = function() {
		    modal.style.display = "none";
		}
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

			+'<div><a href="../public/track?vehicleId='+vehicleID+'&track=single&maps=single" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/track?maps=replay&vehicleId='+vehicleID+'&gid='+$scope.gName+'" target="_blank">History</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+vehicleID+'&track=multiTrack&maps=mulitple" target="_blank">MultiTrack</a>&nbsp;&nbsp; <a href="#" ng-click="addPoi('+lat+','+lng+')">Save Site</a>'
			+'</div>';
			 var compiled = $compile(contentString)($scope);
			// var	drop1 = document.getElementById("ddlViewBy");
			// var drop_value1= drop1.options[drop1.selectedIndex].value;
			var infowindow = new InfoBubble({
			maxWidth: 400,	
			maxHeight:170,
			 content: compiled[0]
			});

			ginfowindow.push(infowindow);
		  	if(marker!=undefined)
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
		$scope.fcode.push(data[$scope.gIndex]);

	}


	function calcLatLongForDrawShapes(longitude, lat, distance, bearing) {
	 var EARTH_RADIUS_EQUATOR = 6378140.0;
     var RADIAN = 180 / Math.PI;

 	 var b = bearing / RADIAN;
 	 var lon = longitude / RADIAN;
 	 var lat = lat / RADIAN;
 	 var f = 1/298.257;
 	 var e = 0.08181922;
 		
 	 var R = EARTH_RADIUS_EQUATOR * (1 - e * e) / Math.pow( (1 - e*e * Math.pow(Math.sin(lat),2)), 1.5);	
 	 var psi = distance/R;
 	 var phi = Math.PI/2 - lat;
 	 var arccos = Math.cos(psi) * Math.cos(phi) + Math.sin(psi) * Math.sin(phi) * Math.cos(b);
 	 var latA = (Math.PI/2 - Math.acos(arccos)) * RADIAN;

 	 var arcsin = Math.sin(b) * Math.sin(psi) / Math.sin(phi);
 	 var longA = (lon - Math.asin(arcsin)) * RADIAN;

 	 return latA+':'+longA;
 	};


 	 //create save site
  $scope.markPoi   =   function(textValue, latlanList)
  {
   	
   	$scope.toast    = '';
    if(checkXssProtection(textValue) == true)
    try
    {

      var URL_ROOT    = "AddSiteController/";    /* Your website root URL */
      var text        = textValue;
      var drop        = 'Home Site';
      var org         = $scope.orgIds[0];
      
      // post request
      if(text && drop && latlanList.length>=3 && org)
      {
      	$.ajax({
	        async: false,
	        method: 'POST', 
	        'url' : URL_ROOT+'store',
	        data: {'_token': $('meta[name=csrf-token]').attr('content'), 'siteName': text, 'siteType': drop, 'org':org, 'latLng': latlanList},
	        success: function (response) {
	          console.log("Sucess");
	          $scope.toast = "Sucessfully Created ...";
	          
	          toastMsg();
	          stopLoading();
	        }
      	}).fail(function() {
          console.log("fail");
          stopLoading();
        });
       
      } else {
      	$scope.toast = "Enter all the field / Mark the Site ";
      }

    } catch (err)
    {
      console.log(err)
      $scope.toast = "Enter all the field / Mark the Site ";
      toastMsg();
      stopLoading();
    }
    stopLoading();
    
  }

	//split methods
	$scope.split_fcode = function(fcode){
		var str = $scope.fcode[0].group;
		var strFine = str.substring(str.lastIndexOf(':'));
		while(strFine.charAt(0)===':')
		strFine = strFine.substr(1);
		return strFine;
	}
	
	$scope.addPoi 	= function(lat, lng){

		$scope.poiLat	=	lat;
		$scope.poiLng	=	lng;
		popUp_Open_Close();

	}

	$scope.submitPoi	= function(poiName){

		var width 	= 1000;
		var latlngList 	= [];

		if($scope.map.getZoom()>5 && $scope.map.getZoom()<=8)
			width = 1000;
		else if($scope.map.getZoom()>8 && $scope.map.getZoom()<=12)
			width = 100;
		else if($scope.map.getZoom()>12 && $scope.map.getZoom()<=15)
			width = 10;
		else if($scope.map.getZoom()>15)
			width = 1;
			
		var radius = (Math.sqrt (2 * (width * width))) / 2;
		
		latlngList[0] 	= calcLatLongForDrawShapes($scope.poiLng, $scope.poiLat, radius, 45)
		latlngList[1] 	= calcLatLongForDrawShapes($scope.poiLng, $scope.poiLat, radius, -45)
		latlngList[2] 	= calcLatLongForDrawShapes($scope.poiLng, $scope.poiLat, radius, -135)
		latlngList[3] 	= calcLatLongForDrawShapes($scope.poiLng, $scope.poiLat, radius, 45)

		console.log(latlngList);

		$scope.markPoi(poiName, latlngList)

		modal.style.display = "none";

	}

	$scope.getMailIdPhoneNo = function(vehi, days)
	{
		//console.log('inside the methods')
		var mailId = document.getElementById("mail").value;
		var phone  = document.getElementById("phone").value;
		if((checkXssProtection(mailId) == true) && (checkXssProtection(phone) == true)){
			if(vehi == 0 && days ==0)
				console.log('select correctly')	
			else
			{
				$scope.split_fcode($scope.fcode[0].group);
				var f_code = $scope.split_fcode($scope.fcode[0].group);
				var f_code_url = GLOBAL.DOMAIN_NAME+'/getVehicleExp?vehicleId='+vehi+'&fcode='+f_code+'&days='+days+'&mailId='+mailId+'&phone='+phone;
				var ecrypt_code_url = '';
				$http.get(f_code_url).success(function(result){
					
					$scope.final_data = result;
					
	    			var url='../public/track?vehicleId='+result.trim()+'&maps=track'+'&userID='+sp1[1];
					window.open(url,'_blank');
					
				})
			}
		} 

	}
	

	

	
	$scope.addMarker= function(pos){
	    
	    var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
	    var labelAnchorpos = new google.maps.Point(19, 0);	///12, 37
	    if(pos.data.position != 'N')
	    {
	    	 $scope.marker = new MarkerWithLabel({
			   position: myLatlng, 
			   map: $scope.map,
			   icon: vamoservice.iconURL(pos.data),
			   labelContent: pos.data.shortName,
			   labelAnchor: labelAnchorpos,
			   labelClass: "labels", 
			   labelInBackground: false
			});
	    }
	    else 
	    {
	    	 $scope.marker = new MarkerWithLabel({
			   position: myLatlng, 
			   map: $scope.map,
			   icon: vamoservice.iconURL(pos.data),
			   labelInBackground: false
			});
	    }
	   
	    
		if(pos.data.vehicleId==$scope.vehicleno){
			$scope.assignValue(pos.data);

			// if(pos.data.address == null || pos.data.address == undefined || pos.data.address == ' ')
			// 		$scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){ 
			// 			$('#lastseen').text(count); 
			// 		});
			// 	else
			// 		$('#lastseen').text(pos.data.address);
			fetchingAddress(pos.data);

		 //    $scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
			// 	$('#lastseen').text(count);
			// 	var t = vamoservice.geocodeToserver(pos.data.latitude,pos.data.longitude,count);
									
			// });		
		}
		gmarkers.push($scope.marker);
		// $scope.marl.push($scope.marker);
		google.maps.event.addListener(gmarkers[gmarkers.length-1], "click", function(e){	
			
			$scope.vehicleno = pos.data.vehicleId;
			$scope.assignValue(pos.data);
			$scope.$apply(function(){
		       $scope.vehiclFuel=graphChange(pos.data.fuelLitre);
            });

			if($scope.vehiclFuel==true){
               $('#graphsId').removeClass('graphsCls');
            }else{
			   $('#graphsId').addClass('graphsCls');
            }
            $('#graphsId').show();

			editableValue();

			fetchingAddress(pos.data);

			sessionStorage.setItem('user', JSON.stringify(pos.data.vehicleId+','+$scope.gName));

			// $scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
			// 	$('#lastseen').text(count); 
			// 	var t = vamoservice.geocodeToserver(pos.data.latitude,pos.data.longitude,count);
									
			// });
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
		$('#regNo span').text(dataVal.regNo);
		$('#vehitype span').text(dataVal.vehicleType);
		$('#mobNo span').text(dataVal.mobileNo);
		$('#graphsId #speed').text(dataVal.speed);
		$('#graphsId #fuel').text(dataVal.tankSize);
		tankSize 		 = parseInt(dataVal.tankSize);
		fuelLtr 		 = parseInt(dataVal.fuelLitre);
		total  			 = parseInt(dataVal.speed);
		$('#vehdevtype #val').text(dataVal.odoDistance);
		$('#mobno #val').text(dataVal.overSpeedLimit);
		$('#positiontime').text(vamoservice.statusTime(dataVal).tempcaption);
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
		var url = GLOBAL.DOMAIN_NAME+'/setPOIName?vehicleId='+$scope.vehicleno+'&poiName='+document.getElementById('poival').value;
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
			 // $scope.infoBoxed($scope.map,gmarkers[i], temp.vehicleId, lat, lng, temp);	
			 if(temp.vehicleId==$scope.vehicleno){
				 $scope.assignValue(temp);
				 $scope.selected=i;
				 fetchingAddress(temp);
				 // $scope.getLocation(lat, lng, function(count){
					//  $('#lastseen').text(count);
					//  var t = vamoservice.geocodeToserver(lat,lng,count);
				 // });	
			 }
			 //$scope.infoBoxed($scope.map,gmarkers[i], temp.vehicleId, lat, lng, temp);
		}
		if($scope.selected!=undefined){
			$scope.map.setCenter(gmarkers[$scope.selected].getPosition()); 	
		}
		
		if($scope.groupMap==true)
		{
			markerCluster.clearMarkers();
			// mcOptions = {gridSize: 50, maxZoom: 15};
			markerCluster 	= new MarkerClusterer($scope.map, gmarkers, mcOptions) 	
		}
		// else if($scope.groupMap == false)
		// {
		// 	markerCluster.clearMarkers(null);
		// }
		$scope.markerJump($scope.locations02[$scope.gIndex].vehicleLocations);


		// if($scope.makerType ==  "markerChange")
		// {
			markerChange($scope.makerType);
		// }

		
	}

function centerMarker(listMarker){
    var bounds = new google.maps.LatLngBounds();
    for (i = 0; i < listMarker.length; i++) {
          bounds.extend(listMarker[i]);
      }
    return bounds.getCenter()
  }

function colorChange(value){
	var color ='';
	switch(value){
		case 'Virugambakkam' :
			color 	= 'c17b97';
			break;
		case 'Kolathur' :
			color 	= 'f76c7c';
			break;
		case 'Egmore(SC)' :
			color 	= 'e746bc';
		 	break;
		case 'Thiyagaraya_Nagar' :
			color 	= '277f07';
			break;
		case 'Saidapet' :
			color 	= 'fad195';
			break;
		case 'Dr_Radhakrishnan_Nagar':
			color 	= '28909c';
			break;
		case 'Perambur' :
			color 	= 'f381a7';
			break;
		case 'Chepauk_Thiruvallikeni': 
			color 	= 'a05071';
			break;
		case 'Thiru_Vi_Ka_Nagar_(SC)' :
			color 	= '3d59be';
			break;
		case 'Harbour' :
			color 	= 'b28d53';
			break;
		case 'Royapuram' :
			color 	= '98beb6';
		 	break;
		case 'Mylapore' :
			color 	= '84c8b6';
			break;
		case 'Velachery' :
			color 	= '6f738d';
			break;
		case 'Thousand_Lights':
			color 	= '456d4d';
			break;
		case 'Anna_Nagar' :
			color 	= 'aca6b7';
			break;
		case 'Villivakkam': 
			color 	= 'f22af0';
			break;
		default:
			color 	= 'f22af0';
		   	break;
	}
	return '#'+color;

}

//draw polygen in map function
function polygenDrawFunction(list){
    
    	// if(list.length=>0){
      var sp;
      polygenList   = [];
      var split     = list.latLng.split(",");
      for(var i = 0; split.length>i; i++)
      {
          sp    = split[i].split(":");
          polygenList.push(new google.maps.LatLng(sp[0], sp[1]));
      }
      var labelAnchorpos = new google.maps.Point(19, 0);
      var polygenColor = new google.maps.Polygon({
            path: polygenList,
            strokeColor: "#000",   //7e7e7e
            strokeWeight: 0.7,
            fillColor: colorChange(list.siteName),//'#' + Math.floor(Math.random()*16777215).toString(16),//'#fe716d',
            //fillOpacity: ,
            map: $scope.map
        });
      
        ///12, 37
      $scope.marker = new MarkerWithLabel({
         position: centerMarker(polygenList), 
         map: $scope.map,
         icon: 'assets/image/area_img.png',
         color: '#fff',
        
         labelContent: list.siteName,
         labelAnchor: labelAnchorpos,
         labelClass: "maps", 
         labelInBackground: false
      });
      $scope.map.setCenter(centerMarker(polygenList)); 
      $scope.map.setZoom(14);  
    // }
}

//locations

function locat_address(locs) {
	var googleLatAndLong = new google.maps.LatLng(locs.lat, locs.lng);
	var labelAnchorpos = new google.maps.Point(70, 80);
	$scope.marker = new MarkerWithLabel({
        position: googleLatAndLong,
        icon: 'assets/imgs/area_loc.png', 
        map: $scope.map,
        color: '#fff',
       	labelClass: "labels1",
       	labelContent: locs.address,
        labelAnchor: labelAnchorpos, 
        labelInBackground: false
      });
      $scope.map.setCenter(googleLatAndLong); 
      $scope.map.setZoom(14);
}



	function siteInvoke(val){
		var url_site          = GLOBAL.DOMAIN_NAME+'/viewSite';
		vamoservice.getDataCall(url_site).then(function(data) {
			// console.log(data)
			if(data.siteParent && $scope._addPoi == false)
			 	angular.forEach(data.siteParent, function(value, key){
					//console.log(' value'+key)
					if(val == value.orgId){
						angular.forEach(value.site, function(vals, keys){
							//console.log('inside the for loop')
							polygenDrawFunction(vals);
						})

					if(value.location.length>0)
						angular.forEach(value.location, function(locs, ind){
							locat_address(locs);
						})
					}
				});
			if(data && data.orgIds != undefined && $scope._addPoi == true)
				$scope.orgIds 	= data.orgIds;

		})
	}


	// polygen draw function
	function polygenFunction(getVehicle){
		//console.log(' getVehicle ')
		var polygenOrgs 	=	[];
		var unique 			= 	new Set();
		polygenOrgs			=	($filter('filter')(getVehicle[$scope.gIndex].vehicleLocations, {'live': 'yes'}));
		for (var i=0; polygenOrgs.length > i; i++) {
			unique.add(polygenOrgs[i].orgId)
		};
		if(unique.size>0)
			angular.forEach(unique, function(value, key) {
				//service call to site details
				siteInvoke(value);
			});
		else {
			$scope._addPoi 	= 	true; 
			siteInvoke();

		}
	}


	//jump marker
	$scope.markerJump = function(getDat){
		//console.log(' val '+gmarkers.length)
		angular.forEach(gmarkers, function(val, key){
			if(getDat[key].live=='yes')
				if(getDat[key].insideGeoFence=='N')
					gmarkers[key].setAnimation(google.maps.Animation.BOUNCE);
				else if(getDat[key].insideGeoFence=='Y')
				 	gmarkers[key].setAnimation(null);
			else
				gmarkers[key].setAnimation(null);
			gmarkers[key].setMap($scope.map);
		})
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
					// $('#status02').show(); 
					// $('#preloader02').show(); 
					var tempurl = GLOBAL.DOMAIN_NAME+'/getNearByVehicles?lat='+event.latLng.lat()+'&lng='+event.latLng.lng();
					
					$http.get(tempurl).success(function(data){
						$scope.nearbyLocs = data;
						// $('#status02').fadeOut(); 
						// $('#preloader02').delay(350).fadeOut('slow');
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
				
					// console.log(' lat :'+lat+'lan :'+lng+'data :'+locs[i]);
					// console.log('marker :'+gmarkers[i]+' vehicle ID : '+ locs[i].vehicleId+'  lat  :'+ lat+'lng  :'+ lng);
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

		//marker jump
		$scope.markerJump(location02[$scope.gIndex].vehicleLocations);

		//for the polygen draw
		polygenFunction(location02);

  //  		$('#status').fadeOut(); 
		// $('#preloader').delay(350).fadeOut('slow');
		// $('body').delay(350).css({'overflow':'visible'});
	stopLoading();



	var input_value   =  document.getElementById('pac-input');
  	var sbox     =  new google.maps.places.SearchBox(input_value);
	// search box function
  	sbox.addListener('places_changed', function() {
	    markerSearch.setMap(null);
	    var places = sbox.getPlaces();
	    markerSearch = new google.maps.Marker({
	    	position: new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()),
	    	animation: google.maps.Animation.BOUNCE,
   			map: $scope.map,
    
  		});
	  	console.log(' lat lan  '+places[0].geometry.location.lat(), places[0].geometry.location.lng())
	    $scope.map.setCenter(new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()));
	    $scope.map.setZoom(13);
	  });


   	}
	
	//click and resolve address
	$scope.address_click = function(data, ind)
	{
		console.log(' inside the address function')
		var tdurl 		=	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+data.latitude+','+data.longitude+"&sensor=true"
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
		$scope.map.setZoom(16);
		$scope.dynamicvehicledetails1=true;
		for(var i=0; i<temp.length;i++){
			if(temp[i].vehicleId==$scope.vehicleno){
				
				$scope.selected=i;
				
				$scope.map.setCenter(gmarkers[i].getPosition());
				
				$scope.assignValue(temp[i]);
				fetchingAddress(temp[i]);
				// if(temp[i].address == null || temp[i].address == undefined || temp[i].address == ' ')
				// 	$scope.getLocation(temp[i].latitude, temp[i].longitude, function(count){ 
				// 		$('#lastseen').text(count); 
				// 	});
				// else
				// 	$('#lastseen').text(temp[i].address);

				$scope.infowindowShow={};				
				for(var j=0; j<ginfowindow.length;j++){
					ginfowindow[j].close();
				}
				ginfowindow[i].open($scope.map,gmarkers[i]);
				var url = GLOBAL.DOMAIN_NAME+'/getGeoFenceView?vehicleId='+$scope.vehicleno;
				$scope.createGeofence(url);
				
			}
		}
	};


	function markerChange(value){
		var icon , img;
			
		angular.forEach($scope.locations, function(valu, key){
			
			// img = ($scope.makerType == 'markerChange')? 'assets/imgs/'+'Car2'+'.png' : vamoservice.iconURL(valu)
			img = ($scope.makerType == 'markerChange')? 'assets/imgs/'+valu.vehicleType+'.png' : vamoservice.iconURL(valu);
			if($scope.makerType == 'markerChange')
				icon = {scaledSize: new google.maps.Size(30, 30),url: img} //scaledSize: new google.maps.Size(25, 25)
				// icon = {scaledSize: new google.maps.Size(30, 30),url: img} //scaledSize: new google.maps.Size(25, 25) valu.vehicleType
			else
				icon = {url: img}
			gmarkers[key].setIcon(icon);
			gmarkers[key].setMap($scope.map);

		});
		
	}


	$('#mapTable-mapList').hide()
	$("#homeImg").hide();
	$("#listImg").show();
	$("#single").hide();
	$("#cluster").show();
	$("#efullscreen").hide();
	$("#fullscreen").show();
	$('#graphsId').hide();
	$("#carMarker").show();
	$("#marker").hide();
	// $scope.idinvoke;
	//list view
	function listMap ()
	{
		setId();
		// $("#homeImg").show();
		$("#listImg").hide();
		$("#homeImg").show();
		$("#fullscreen").show();
		$('#mapTable-mapList').show(1000);
		// 
		document.getElementById($scope.idinvoke).setAttribute("id", "mapList");
		if(document.getElementById('talist')!=null)
		document.getElementById('talist').setAttribute("id", "mapTable-mapList");
	}

	//return home
	function homeMap ()
	{
		setId();
		// $("#listImg").show();
		$("#homeImg").hide();
		$("#listImg").show();
		$("#fullscreen").show();
		
		$("#contentmin").show(1000);
		$("#sidebar-wrapper").show(500);
		// document.getElementById($scope.idinvoke).setAttribute("id", "mapList")
		document.getElementById($scope.idinvoke).setAttribute("id", "wrapper");
	}

	
	// clusterMarker 
	function clusterMarker()
	{
		$("#cluster").hide();
		$("#single").show();
		$scope.groupMap=true;
		// markerCluster 	= new MarkerClusterer($scope.map, null, null)
		
		// mcOptions = {gridSize: 50,maxZoom: 15,styles: [ { height: 53, url: "assets/imgs/m1.png", width: 53}]}
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

	// changeMarker
	function changeMarker()
	{
		if($scope.makerType == undefined)
		{
			$("#carMarker").show();
			$("#marker").hide();	
		} else if ($scope.makerType == 'markerChange'){
			$("#carMarker").hide();
			$("#marker").show();	
		}
		
		// $scope.groupMap=true;
		// markerCluster 	= new MarkerClusterer($scope.map, null, null)
		
		// mcOptions = {gridSize: 50,maxZoom: 15,styles: [ { height: 53, url: "assets/imgs/m1.png", width: 53}]}
		// markerCluster 	= new MarkerClusterer($scope.map, gmarkers, mcOptions)	
	}


	function fullScreen()
	{
		setId();
		$("#efullscreen").show();
		$("#contentmin").show(1000);
		$("#sidebar-fullscreen").show(500);
		document.getElementById($scope.idinvoke).setAttribute("id", "sidebar-fullscreen");
		
		
	}

	function exitScreen()
	{
		setId();
		
		$("#fullscreen").show();

		// $("#listImg").show();
		// $("#homeImg").hide();
		$("#contentmin").show(1000);
		$("#sidebar-wrapper").show(500);
		document.getElementById($scope.idinvoke).setAttribute("id", "wrapper");

		

	}

	function setId()
	{
		$("#minmax").show();
		// $("#efullscreen").hide();
		// $("#sidebar-wrapper").hide(500);
		$("#fullscreen").hide();

		$("#efullscreen").hide();
		$('#mapTable-mapList').hide(500);
		// $("#efullscreen").hide();
		
		$("#contentmin").hide(500);
		$("#sidebar-wrapper").hide(500);
		if(document.getElementById("wrapper")!=null)
			$scope.idinvoke = document.getElementById("wrapper").id;
		else if(document.getElementById("sidebar-fullscreen")!=null)
			$scope.idinvoke = document.getElementById("sidebar-fullscreen").id;
		else if(document.getElementById("mapList")!=null)
			$scope.idinvoke = document.getElementById("mapList").id;
		else if(document.getElementById("mapTable-mapList")!=null)
			$scope.idinvoke = document.getElementById("mapTable-mapList").id;
		else if(document.getElementById("tablelist")!=null)
			$scope.idinvoke = document.getElementById("tablelist").id;

	}

	function fulltable()
	{
		setId();
		$("#minmax").hide();
		document.getElementById($scope.idinvoke).setAttribute("id", "tablelist");
		// $("#contentmin").show(1000);
		// $("#sidebar-wrapper").hide(500);
		// $("#sidebar-fullscreen").hide(500);
		// $('#mapTable-mapList').show(1000);
		// document.getElementById('menuView').setAttribute("id", "menuView");
		// $('#menuView').show(1000);

		document.getElementById('mapTable-mapList').setAttribute("id", "talist");
		
		$('#talist').show(500);

		// $("#contentmin").show(1000);
		// $("#sidebar-wrapper").show(500);
	}

	function graphView()
	{
		$('#graphsId').toggle(500);
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
			case 'tablefull' :
				fulltable();
				break;
			case 'graphs': 
				graphView();
				break;
			case 'markerChange':
				$scope.makerType =  "markerChange";
				changeMarker()
				markerChange('markerChange');
				break;
			case 'undefined':
				$scope.makerType =  undefined;
				changeMarker()
				markerChange(undefined);
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
// 	$(window).load(function() {
// 		$('#status').fadeOut(); 
// 		$('#preloader').delay(350).fadeOut('slow');
// 		$('body').delay(350).css({'overflow':'visible'});
// });

$scope.starSplit 	=	function(val){

	return val.split('<br>');
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
						scope.selected 		= undefined;
						scope.locations02	= data;
						scope.locations 	= scope.statusFilter(scope.locations02[scope.gIndex].vehicleLocations, scope.vehicleStatus);
						scope.zoomLevel 	= scope.zoomLevel;
						scope.loading		=	true;
						scope.initial02();
					}
				}); 
			},60000);
	  	}); 
	    }
	};
});
// $(window).load(function() {
// 		$('#status').fadeOut(); 
// 		$('#preloader').delay(350).fadeOut('slow');
// 		$('body').delay(350).css({'overflow':'visible'});
// });


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
        	chartFuel.yAxis[0].update({max:tankSize});
            point = chartFuel.series[0].points[0];
            point.update(fuelLtr);
//            if(tankSize==0)
 //           	tankSize =200;
 //           chartFuel.yAxis[0].update({
//			    max: tankSize,
//			}); 

        }
    }, 1000);
});
// app.directive('tooltipLoader', function() {
//         return function(scope, element, attrs) {

// 	        element.tooltip({
// 	        trigger:"hover",
// 	        placement: "top",
// 	        html: true,
// 	        animated : 'fade',
// 	        container: 'body',
// 	    });
//     };
// });



 function googleTranslateElementInit() 
    {
         new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
    }


    $(document).ready(function(){
        $('#minmax').click(function(){
            $('#contentmin').animate({
                height: 'toggle'
            },500);
        });
    });

    $("#editable").hide();
    $("#viewable").show();

    $(document).ready(function(){
      
        $('#draggable').hide(0);  
        $('#minmaxMarker').click(function(){
           
              $('#draggable').animate({
                height: 'toggle'
            },500);
        });
    

    });
    $("#inputEdit").click(function(e){
        $("#editable").show();
        $("#viewable").hide();
    })
    
    
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    $(document).ready(function(){
        if(navigator.appVersion.indexOf("MSIE")!=-1){
            document.body.style.zoom="90%";
        }
    });


