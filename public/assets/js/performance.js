//comment by satheesh ++...
var setintrvl;
app.filter('statusfilter', function(){
	return function(obj, param){
		 var out = [];
	   if(param=='ALL'){
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
}})
.directive('modalDialog', function() {
  return {
    restrict: 'E',
    scope: {
      show: '='
    },
    replace: true, // Replace with the template below
    transclude: true, // we want to insert custom content inside the directive
    link: function(scope, element, attrs) {
      scope.dialogStyle = {};
      if (attrs.width)
        scope.dialogStyle.width = attrs.width;
      if (attrs.height)
        scope.dialogStyle.height = attrs.height;
      scope.hideModal = function() {
        scope.show = false;
      };
    },
    template: "<div class='ng-modal' ng-show='show'><div class='ng-modal-overlay' ng-click='hideModal()'></div><div class='ng-modal-dialog' ng-style='dialogStyle'><div class='ng-modal-close' ng-click='hideModal()'>X</div><div class='ng-modal-dialog-content' ng-transclude></div></div>" 
	};
})
.controller('mainCtrl',['$scope', '$http','vamoservice', function($scope, $http, vamoservice, $filter, statusfilter){
	$scope.locations = [];
	$scope.nearbyLocs =[];
	$scope.val = 5;	
	$scope.gIndex = 0;
	$scope.alertstrack = 0;
	$scope.totalVehicles = 0;
	$scope.vehicleOnline = 0;
	$scope.distanceCovered= 0;
	$scope.attention= 0;
	$scope.cityCircle=[];
	$scope.cityCirclecheck=false;
	$scope.markerClicked=false;
	$scope.url = 'http://'+globalIP+'/vamo/public/getVehicleLocations';
	$scope.historyfor='';
	$scope.map =  null;
	$scope.flightpathall = []; 
	$scope.clickflag = false;
	// $scope.flightPath = new google.maps.Polyline();
	// $scope.trafficLayer = new google.maps.TrafficLayer();
	$scope.checkVal=false;
	$scope.clickflagVal =0;
	$scope.nearbyflag = false;
	$scope.groupOverall = '';
	var tempdistVal = 0;
	//$scope.locations01 = vamoservice.getDataCall($scope.url);
	$scope.trimColon = function(textVal){
		return textVal.split(":")[0].trim();
	}				
	$scope.$watch("url", function (val) {
		vamoservice.getDataCall($scope.url).then(function(data) {
			$scope.selected       =  undefined;
			$scope.locations02    =  data;
			$scope.initMethod(data);
			$scope.groupOverall          =  data[0].group;
			//console.log('g name '+groupOverall)
			// if(data.length){
			// 	$scope.vehiname	= data[0].vehicleLocations[0].vehicleId;
			// 	//$scope.locations = $scope.statusFilter($scope.locations02[0].vehicleLocations, $scope.vehicleStatus);
			// 	//$scope.zoomLevel = parseInt(data[$scope.gIndex].zoomLevel);
			// 	//$scope.initilize('map_canvas');
			// }
		});	
	});
	//console.log($scope.locations02)
	// $scope.$watch("vehicleStatus", function (val) {
	// 	if($scope.locations02!=undefined){
	// 		$scope.selected=undefined;
	// 		$scope.locations = $scope.statusFilter($scope.locations02[0].vehicleLocations, val);
	// 		$scope.initilize('map_canvas');
	// 	}
	// });
	
	
	
	// $scope.statusFilter = function(obj, param){
	//  	var out = [];
	//    	if(param=='ALL'){
	//    		out= obj;
	//    		return out;  
	//    	}else if(param=='ON' || param=='OFF'){
	// 	    for(var i=0; i<obj.length; i++){
	// 		    if(obj[i].status == param){
	// 		    	out.push(obj[i]);
	// 		    }
	// 	    }
	// 	    return out;  
	//    	}else{
	//    		if(param=='O'){
	//    			for(var i=0; i<obj.length; i++){
	// 			    if(obj[i].isOverSpeed == 'Y'){
	// 			    	out.push(obj[i]);
	// 			    }
	// 		 	}
	// 		 	return out;
	//    		}else{
	// 	   		for(var i=0; i<obj.length; i++){
	// 			    if(obj[i].position == param){
	// 			    	out.push(obj[i]);
	// 			    }
	// 		 	}
	// 		 	return out;
	// 		}
	//    }
	// }
	// $scope.drawLine = function(loc1, loc2){
	// 	var flightPlanCoordinates = [loc1, loc2];
	// 	$scope.flightPath = new google.maps.Polyline({
	// 		path: flightPlanCoordinates,
	// 		geodesic: true,
	// 		strokeColor: "#ff0000",
	// 		strokeOpacity: 1.0,
	// 		strokeWeight:2
	// 	});
		
	// 	$scope.flightPath.setMap($scope.map);
	// 	$scope.flightpathall.push($scope.flightPath);
	// 	tempdistVal = parseFloat($('#distanceVal').val()) + parseFloat((google.maps.geometry.spherical.computeDistanceBetween(loc1,loc2)/1000).toFixed(2))
	// 	$('#distanceVal').val(tempdistVal.toFixed(2));
	// }
	
	// $scope.genericFunction = function(vehicleno, index){
	// 	$scope.selected = index;
	// 	$scope.removeTask(vehicleno);
		
	// }
	
	// $scope.check = function(){
	// 	if($scope.checkVal==false){
	// 		$scope.trafficLayer.setMap($scope.map);
	// 		$scope.checkVal = true;
	// 	}else{
	// 		$scope.trafficLayer.setMap(null);
	// 		$scope.checkVal = false;
	// 	}
	// }
	
	// $('.nearbyTable').hide();
	// $scope.nearBy = function(){
	// 	if($scope.nearbyflag == false){
	// 		$('#myModal').modal();	
	// 		$scope.nearbyflag=true;
	// 	}else{
	// 		$('.nearbyTable').hide();
	// 		$scope.nearbyflag=false;
	// 	}
	// }
	
	// $scope.getLocation = function(lat,lon, callback){
	// 	geocoder = new google.maps.Geocoder();
	// 	var latlng = new google.maps.LatLng(lat, lon);
	// 	geocoder.geocode({'latLng': latlng}, function(results, status) {
	// 		if (status == google.maps.GeocoderStatus.OK) {
	// 		  if (results[1]) {
	// 			if(typeof callback === "function") callback(results[1].formatted_address)
	// 		  }
	// 		}
	// 	});
 //    };
	
	// $scope.distance = function(){
	// 	$scope.nearbyflag=false;
	// 	$('.nearbyTable').hide();
	// 	if($scope.clickflag==true){
	// 		$scope.clickflagVal = 0;
	// 		$('#distanceVal').val(0);
	// 		$scope.clickflag=false;
	// 		for(var i=0; i<$scope.flightpathall.length; i++){
	// 			$scope.flightpathall[i].setMap(null);	
	// 		}
	// 	}else{
	// 		$scope.clickflag=true;	
	// 	}
	// }
	
	// $scope.groupSelection = function(groupname, groupid){
	// 	 $scope.selected=undefined;
	// 	 $scope.url = 'http://'+globalIP+'vamo/public/getVehicleLocations?group=' + groupname;
	// 	 $scope.gIndex = groupid;
	// 	 gmarkers=[];
	// 	 for(var i=0; i<ginfowindow.length;i++){		
	// 	 	ginfowindow[i].setMap(null);
	// 	 }
	// 	 ginfowindow=[];
	// 	 clearInterval(setintrvl);
	// 	 $scope.locations01 = vamoservice.getDataCall($scope.url);
	// }
	
	// $scope.infoBoxed = function(map, marker, vehicleID, lat, lng, data){
	// 	var tempoTime = vamoservice.statusTime(data);
	// 	if(data.ignitionStatus=='ON'){
	// 		var classVal = 'green';
	// 	}else{
	// 		var classVal = 'red';
	// 	}
	// 	var contentString = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
	// 	+'<div><b style="width:100px; display:inline-block;">Vehicle ID</b> - '+vehicleID+'<span style="font-weight:bold;">('+data.shortName+')</span></div>'
	// 	+'<div><b style="width:100px; display:inline-block;">Speed</b> - '+data.speed+' <span style="font-size:10px;font-weight:bold;">kmph</span></div>'
	// 	+'<div><b style="width:100px; display:inline-block;">ODO Distance</b> - '+data.odoDistance+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
	// 	+'<div><b style="width:100px; display:inline-block;">Today Distance</b> - '+data.distanceCovered+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
	// 	+'<div><b style="width:100px; display:inline-block;">ACC Satus</b> - <span style="color:'+classVal+'; font-weight:bold;">'+data.ignitionStatus+'</span> </div>'
	// 	+'<div><b style="width:100px; display:inline-block;">'+tempoTime.tempcaption+' Time</b> - '+tempoTime.temptime+'</div><br>'
	// 	+'<div><a href="../public/track?vehicleId='+vehicleID+'" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/replay?vehicleId='+vehicleID+'" target="_self">History</a></div>'
	// 	+'</div>';
		
	// 	var infowindow = new InfoBubble({
	// 	maxWidth: 400,	
	// 	maxHeight:170,
	// 	 content: contentString
	// 	});
	// 	ginfowindow.push(infowindow);
	//   	(function(marker) {
	// 		google.maps.event.addListener(marker, "click", function(e) {
	// 			for(var j=0; j<ginfowindow.length;j++){
	// 				ginfowindow[j].close();
	// 			}
	// 			infowindow.open(map,marker);
	//    		});	
	// 	})(marker);
	// }
	
	// $scope.addMarker= function(pos){
	    
	//     var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
	//     var labelAnchorpos = new google.maps.Point(12, 37);	
	// 	$scope.marker = new MarkerWithLabel({
	// 	   position: myLatlng, 
	// 	   map: $scope.map,

	// 	   icon: vamoservice.iconURL(pos.data),
	// 	   labelContent: pos.data.shortName,
	// 	   labelAnchor: labelAnchorpos,
	// 	   labelClass: "labels", 
	// 	   labelInBackground: false
	// 	 });
		
	// 	if(pos.data.vehicleId==$scope.vehicleno){
	// 		$scope.assignValue(pos.data);
	// 	    $scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
	// 			$('#lastseen').text(count);
	// 			var t = vamoservice.geocodeToserver(pos.data.latitude,pos.data.longitude,count);
									
	// 		});		
	// 	}
	// 	gmarkers.push($scope.marker);
	// 	google.maps.event.addListener(gmarkers[gmarkers.length-1], "click", function(e){	
			
	// 		$scope.vehicleno = pos.data.vehicleId;
	// 		//$scope.startlatlong= new google.maps.LatLng();
	// 		//$scope.endlatlong= new google.maps.LatLng();
	// 		$scope.assignValue(pos.data);
	// 		$scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
	// 			$('#lastseen').text(count); 
	// 			var t = vamoservice.geocodeToserver(pos.data.latitude,pos.data.longitude,count);
									
	// 		});
	// 		if($scope.selected!=undefined){
	// 			$scope.map.setCenter(gmarkers[$scope.selected].getPosition()); 	
	// 		}
 //       });
	// }
	
	// $scope.removeTask=function(vehicleno){
	// 	$scope.vehicleno = vehicleno;
	// 	var temp = $scope.locations;
	// 	//$scope.endlatlong = new google.maps.LatLng();
	// 	//$scope.startlatlong = new google.maps.LatLng();
	// 	$scope.map.setZoom(19);
		
	// 	for(var i=0; i<temp.length;i++){
	// 		if(temp[i].vehicleId==$scope.vehicleno){
				
	// 			$scope.selected=i;
				
	// 			$scope.map.setCenter(gmarkers[i].getPosition());
				
	// 			$scope.assignValue(temp[i]);
	// 			$scope.getLocation(temp[i].latitude, temp[i].longitude, function(count){ 
	// 				$('#lastseen').text(count); 
	// 			});
	// 			$scope.infowindowShow={};				
	// 			for(var j=0; j<ginfowindow.length;j++){
	// 				ginfowindow[j].close();
	// 			}
	// 			ginfowindow[i].open($scope.map,gmarkers[i]);
	// 			var url = 'http://'+globalIP+'/vamo/public/getGeoFenceView?vehicleId='+$scope.vehicleno;
	// 			$scope.createGeofence(url);
				
	// 		}
	// 	}
	// };
			
			
			$scope.single=false;
			$scope.group=true;
			var today = new Date();
			var aMonth = 0;
			aMonth = today.getMonth();
			aMonth=aMonth-1;
			today = new Date(today.setMonth(today.getMonth() - 1));
			var s=''+today
			var a = s.split(' ')
			var date1='';
			var subString='';
			for(var sp in a)
			{
				date1=a[1]+","+a[3];
			}
			
			$scope.fromdate=date1;
			
			var months= [];
			//var today = new Date();
			//var aMonth = today.getMonth();
			var month = new Array('Jan', 'Feb', 'Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			for (var i=0; i<11; i++) 
			{
				months.push(month[aMonth]);
				aMonth--; 
				if (aMonth < 0) 
				{
					aMonth = 11;
				}
			}
	$scope.value=[];
	
		
	// performance report search by date . . .
	$scope.monthYear=function()
	{
		var splitComma='';
		splitComma=$scope.fromdate.split(',');
		$scope.month=splitComma[0];
		$scope.year=splitComma[1];
		return $scope.month,$scope.year;
	}
	
	//list for table
	$scope.tableValue	=	[];
	var dataTableList	=	[];
	$scope.vSingle		=	'';
	$scope.gSingle		=	'';
	$scope.iSingle		=	'';
	$scope.genericFunction1 = function(vehicleno, groupname, index)
	{
		$scope.vSingle		=	vehicleno;
		$scope.gSingle		=	groupname;
		$scope.iSingle		=	index;
		$scope.tableValue=[];
		var dataTableList=[];
		$scope.monthYear($scope.month,$scope.year);
		$scope.single=true;
		$scope.group=false;
		var acc = '';
		var totalsuddenBreak=[];
		var SuddenAcc=[];
		var OverSpeed=[];
		var sparkAlarm=[];
		var kiloMeter=[];
		var timestamp=0;
		$scope.tableValue=[];
		var monthList="";
		
		months=[];
		var monthIndex=0;
		monthIndex=month.indexOf($scope.month);
		for (var j=0; j<12; j++) 
		{
			months.push(month[monthIndex]);
			monthIndex--; 
			if (monthIndex < 0) 
			{
				monthIndex = 11;
			}
		}
		var tempurl='http://'+globalIP+'/vamo/public/getIndividualDriverPerformance?groupId='+groupname+'&vehicleId='+vehicleno+'&month='+$scope.month+'&year='+$scope.year;
		$http.get(tempurl).success(function(data){
		console.log(tempurl)
		for(var i=0; i<data.length; i++)
		{
			totalsuddenBreak.push(data[i].weightedBreakAnalysis);
			SuddenAcc.push(data[i].weightedAccelAnalysis);
			OverSpeed.push(data[i].weightedSpeedAnalysis);
			sparkAlarm.push(data[i].weightedShockAlarmAnalysis);
			kiloMeter.push(data[i].distance);
			dataTableList.push({'month': months[i],'data': data[i]});
			console.log('value----->'+data[i].weightedBreakAnalysis)
		}
		$scope.tableValue=dataTableList;
		$('#container').highcharts({
			
		//charts
		
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Individual Driver Performance chart'
        },
		subtitle: {
            align: 'right',
			x: 10,
            verticalAlign: 'top',
			y: 30,
            text: 'Total Distance',
			style: {
                    color: Highcharts.getOptions().colors[2]
                }
				
        },
        
		 xAxis: [{
                categories: months,
                //reversed: false,
                labels: {
                    step: 1
                }
            }, { // mirror axis on right side
                opposite: true,
               // reversed: false,
                categories: kiloMeter,
                linkedTo: 0,
                labels: {
                    step: 1,
					 //format: vehiclename,
					
                },
			 }],
        yAxis: {
            min: 0,
            /*title: {
                text: vehicleno
            }*/
        },
			
		tooltip: {
				shared: true,
				//tooltip: 

     // formatter: function() { return ' ' +
       
        // 'Unlocked: ' +point.data+ '<br />' +
        // 'Potential: ' + this.x;
     // }

				},
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal'
            }
        },
        series: [{
					name: 'Break Analysis',
					data: totalsuddenBreak
					
				}, {
					name: 'Speed Analysis',
					data: OverSpeed
					
				}, {
					name: 'Shock Analysis',
					data: sparkAlarm
				}, {
					name: 'Acceleration Analysis',
					data: SuddenAcc
				}]
				});
		
		})
	}
	

	//onload calling ng-init	
	$scope.initMethod= function (group)
	{
		$scope.tableValue=[];
		var dataTableList=[];
		$scope.single=false;
		$scope.group=true;
		$scope.monthYear($scope.month,$scope.year);
		$scope.groupName=group[0].group;
		var tempurl1='http://'+globalIP+'/vamo/public/getOverallDriverPerformance?groupId='+$scope.groupName+'&month='+$scope.month+'&year='+$scope.year;
		OverallDriverPerformance(tempurl1)
	}

	$scope.sumbitAction=function(date)
	{
		if($scope.group == true)
		{
			$scope.monthYear($scope.month,$scope.year);
			var urlOver = 'http://'+globalIP+'/vamo/public/getOverallDriverPerformance?groupId='+$scope.groupOverall+'&month='+$scope.month+'&year='+$scope.year;
			OverallDriverPerformance(urlOver);	
		}
		else
		{
			console.log('    group false   ')
			$scope.genericFunction1($scope.vSingle, $scope.gSingle, $scope.iSingle);
		// 	$scope.vSingle		=	vehicleno;
		// $scope.gSingle		=	groupname;
		// $scope.iSingle		=	index;
		}
	}	

	var OverallDriverPerformance = function(tempurl1)
	{
		var totalsuddenBreak=[];
		var SuddenAcc=[];
		var OverSpeed=[];
		var sparkAlarm=[];
		var vehiclename=[];
		var kiloMeter=[];
		$scope.value=[];
		var viewTable=false;
		var i=0;
		//console.log('inside the url')
		$http.get(tempurl1).success(function(data)
		{
			$scope.value=data;
			for(i; i<data.length; i++)
			{
				vehiclename.push(data[i].vehicleId);
				totalsuddenBreak.push(data[i].weightedBreakAnalysis);
				SuddenAcc.push(data[i].weightedAccelAnalysis);
				OverSpeed.push(data[i].weightedSpeedAnalysis);
				sparkAlarm.push(data[i].weightedShockAlarmAnalysis);
				kiloMeter.push(data[i].distance);
				dataTableList.push({'month':data[i].vehicleId,'data': data[i]});
			}
			$scope.tableValue=dataTableList;
			//console.log($scope.tableValue[1])
			//group value charts
			$('#container1').highcharts({
				chart: {
					type: 'bar'
				},
				title: {
					text: 'Drivers Performance chart'
				},
				subtitle: {
					align: 'right',
					x: 10,
					verticalAlign: 'top',
					y: 30,
					text: 'Total Distance',
					style: {
							color: Highcharts.getOptions().colors[2]
						}
					
				},
				xAxis: [{
					categories: vehiclename,
					reversed: false,
					labels: {
						step: 1
					}
					}, { // mirror axis on right side
					opposite: true,
					reversed: false,
					categories: kiloMeter,
					linkedTo: 0,
					labels: {
						step: 1,
						 //format: vehiclename,
						
					},
				 }],
				yAxis: {
					min: 0,
					
					/*title: {
						text: vehicleno
					}*/
				},
				tooltip: {
				shared: true,
				},
			
				legend: {
					reversed: true
				},
				/*plotOptions: {

					series: {
						stacking: 'normal'
					}
				},*/
				plotOptions: {
					series: {
					  stacking: 'normal',
					  cursor: 'pointer',
						   
					   
					}
				},
				series: [{
					name: 'Break Analysis',
					data: totalsuddenBreak
				}, {
					name: 'Speed Analysis',
					data: OverSpeed
					
				}, {
					name: 'Shock Analysis',
					data: sparkAlarm
				}, {
					name: 'Acceleration Analysis',
					data: SuddenAcc
				}]
			});
		})
	}



	// click the group 
	$scope.groupSelection1 = function(groupname, groupid, index)
	{
		$scope.tableValue=[];
		var dataTableList=[];
		var totalsuddenBreak=[];
		var SuddenAcc=[];
		var OverSpeed=[];
		var sparkAlarm=[];
		var vehiclename=[];
		var kiloMeter=[];
		$scope.value=[];
		$scope.monthYear($scope.month,$scope.year);
		tempurl1='http://'+globalIP+'/vamo/public/getOverallDriverPerformance?groupId='+groupname+'&month='+$scope.month+'&year='+$scope.year;
		OverallDriverPerformance(tempurl1);
		$scope.single=false;
		$scope.group=true;
		//console.log(tempurl1)
		// $http.get(tempurl1).success(function(data)
		// {
		// 	$scope.value=data;
		// 	for(var i=0; i<data.length; i++)
		// 	{
		// 		vehiclename.push(data[i].vehicleId);
		// 		totalsuddenBreak.push(data[i].weightedBreakAnalysis);
		// 		SuddenAcc.push(data[i].weightedAccelAnalysis);
		// 		OverSpeed.push(data[i].weightedSpeedAnalysis);
		// 		sparkAlarm.push(data[i].weightedShockAlarmAnalysis);
		// 		kiloMeter.push(data[i].distance);
		// 		dataTableList.push({'month':data[i].vehicleId,'data': data[i]});
		// 		//console.log('data here--------->'+dataTableList[i].data.vehicleId)
		// 	}
		// 	$scope.tableValue=dataTableList;
		// 	$('#container1').highcharts({
		// 	chart: {
		// 		type: 'bar'
		// 	},
		// 	title: {
		// 		text: 'Drivers Performance chart'
		// 	},
		// 	subtitle: {
		// 		align: 'right',
		// 		x: 10,
		// 		verticalAlign: 'top',
		// 		y: 30,
		// 		text: 'Total Distance',
		// 		style: {
		// 				color: Highcharts.getOptions().colors[2]
		// 			}
				
		// 	},
		// 	xAxis: [{
		// 		categories: vehiclename,
		// 		reversed: false,
		// 		labels: {
		// 			step: 1
		// 		}
		// 		}, { // mirror axis on right side
		// 		opposite: true,
		// 		reversed: false,
		// 		categories: kiloMeter,
		// 		linkedTo: 0,
		// 		labels: {
		// 			step: 1,
		// 			 //format: vehiclename,
					
		// 		},
		// 	 }],
		// 	yAxis: {
		// 		min: 0,
				
		// 		/*title: {
		// 			text: vehicleno
		// 		}*/
		// 	},
		// 	tooltip: {
		// 	shared: true,
		// 	},
		
		// 	legend: {
		// 		reversed: true
		// 	},
		// 	/*plotOptions: {

		// 		series: {
		// 			stacking: 'normal'
		// 		}
		// 	},*/
		// 	plotOptions: {
		// 		series: {
		// 		  stacking: 'normal',
		// 		  cursor: 'pointer',
					   
				   
		// 		}
		// 	},
		// 	series: [{
		// 			name: 'Break Analysis',
		// 			data: totalsuddenBreak
		// 		}, {
		// 			name: 'Speed Analysis',
		// 			data: OverSpeed
					
		// 		}, {
		// 			name: 'Shock Analysis',
		// 			data: sparkAlarm
		// 		}, {
		// 			name: 'Acceleration Analysis',
		// 			data: SuddenAcc
		// 		}]
		// 	});
		// });
		
	};
	
	
	

	// popup
	$scope.modalShown = false;
	//var address;
	$scope.toggleModal = function(user) {
		//console.log('arun---------->'+user.data.vehicleId)
		$scope.detailedView=user;
		$scope.modalShown = !$scope.modalShown;
		var obj = $scope.detailedView.data.suddenBreakList.normal.historySuddenBrk;
		//console.log(obj)
		var latitude=0;
		var longutide=0;
		var tempurl1='';
		var splitting='';
		var speed=0;
		var slow=0;
		var detailedJson=[];
		$scope.Values=[];
		$scope.aggressive=[];
		$scope.aggressiveCount=0;
		$scope.harsh=[];
		$scope.id=$scope.detailedView.data.vehicleId;
		$scope.harshCount=0;
		$scope.normal=0;
		$scope.locationname='';
		//normal breaks for loop
		angular.forEach(obj, function(value, key) 
		{	
			splitting=value.split(',');
			//console.log('inthe for each---1--->'+splitting[0])
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
			
		});
		$scope.Values=detailedJson;
		$scope.normal=$scope.detailedView.data.suddenBreakList.normal.subTotalSuddenBreak;
		
		
		
		
		var obj1 = $scope.detailedView.data.suddenBreakList.aggressive.historySuddenBrk;
		var detailedJson1=[];
		//aggresive breaks for loop
		angular.forEach(obj1, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson1.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
			//console.log('second for----->'+obj1)
		});
		$scope.aggressive=detailedJson1;
		$scope.aggressiveCount=$scope.detailedView.data.suddenBreakList.aggressive.subTotalSuddenBreak;
		
		//harsh break
		var obj2 = $scope.detailedView.data.suddenBreakList.harsh.historySuddenBrk;
		var detailedJson2=[];
		angular.forEach(obj2, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson2.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
			//console.log('third for----->'+obj2)
		});
		$scope.harsh=detailedJson2;
		$scope.harshCount=$scope.detailedView.data.suddenBreakList.harsh.subTotalSuddenBreak;
		//very harsh break
		var obj3 = $scope.detailedView.data.suddenBreakList.veryharsh.historySuddenBrk;
		var detailedJson3=[];
		angular.forEach(obj3, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson3.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
			//console.log('third for----->'+obj3)
		});
		$scope.Veryharsh=detailedJson3;
		$scope.veryHarsh=$scope.detailedView.data.suddenBreakList.veryharsh.subTotalSuddenBreak;
		// worst performer
		var obj4 = $scope.detailedView.data.suddenBreakList.worst.historySuddenBrk;
		var detailedJson4=[];
		angular.forEach(obj4, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson4.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
			//console.log('third for----->'+obj4)
		});
		$scope.worst=detailedJson4;
		$scope.worstCount=$scope.detailedView.data.suddenBreakList.worst.subTotalSuddenBreak;
	};
	
	// popup
	
	$scope.modalShown1 = false;
	//var address;
	$scope.excellent='';
	$scope.toggleModal1 = function(user) 
	{
		console.log('modal two popup-------->'+user.historySpeedAnalysis)
		$scope.excellentCount=0;
		$scope.excellentSpeed=0;
		$scope.bestCount=0;
		$scope.bestSpeed=0;
		$scope.averageCount=0;
		$scope.averageSpeed=0;
		$scope.worstCount=0;
		$scope.worstSpeed=0;
		$scope.redlinerCount=0;
		$scope.redlinerSpeed=0;
		$scope.topSpeed=0;
		var splitValue='';
		$scope.id='';
		$scope.modalShown1 = !$scope.modalShown1;
		$scope.id=user.data.vehicleId;
		//$scope.topSpeed=user.topSpeed;
		$scope.excellentCount=user.data.historySpeedAnalysis.Excellent.split(',')[0];
		$scope.excellentSpeed=user.data.historySpeedAnalysis.Excellent.split(',')[1];
		$scope.bestCount=user.data.historySpeedAnalysis.Best.split(',')[0];
		$scope.bestSpeed=user.data.historySpeedAnalysis.Best.split(',')[1];
		$scope.averageCount=user.data.historySpeedAnalysis.Average.split(',')[0];
		$scope.averageSpeed=user.data.historySpeedAnalysis.Average.split(',')[1];
		//console.log('in the second popup------>'+$scope.averageSpeed)
		$scope.worstCount=user.data.historySpeedAnalysis.Aggressive.split(',')[0];
		$scope.worstSpeed=user.data.historySpeedAnalysis.Aggressive.split(',')[1];
		$scope.redlinerCount=user.data.historySpeedAnalysis.RedLiner.split(',')[0];
		$scope.redlinerSpeed=user.data.historySpeedAnalysis.RedLiner.split(',')[1];
	};
	//$('#example').DataTable();
	// popup
	$scope.modalShown2 = false;
	//var address;
	$scope.toggleModal2 = function(user) {
		$scope.modalShown2 = !$scope.modalShown2;
		$scope.id=user.data.vehicleId;
		//$scope.topSpeed=user.data.topSpeedAlarm;
		$scope.excellentCount=user.data.historyShockAlarm.Excellent.split(',')[0];
		$scope.excellentSpeed=user.data.historyShockAlarm.Excellent.split(',')[1];
		$scope.bestCount=user.data.historyShockAlarm.Best.split(',')[0];
		$scope.bestSpeed=user.data.historyShockAlarm.Best.split(',')[1];
		$scope.averageCount=user.data.historyShockAlarm.Average.split(',')[0];
		$scope.averageSpeed=user.data.historyShockAlarm.Average.split(',')[1];
		$scope.worstCount=user.data.historyShockAlarm.Aggressive.split(',')[0];
		$scope.worstSpeed=user.data.historyShockAlarm.Aggressive.split(',')[1];
		$scope.redlinerCount=user.data.historyShockAlarm.RedLiner.split(',')[0];
		$scope.redlinerSpeed=user.data.historyShockAlarm.RedLiner.split(',')[1];
	};
	
	// popup
	$scope.modalShown3 = false;
	//var address;
	$scope.toggleModal3 = function(user) {
		$scope.detailedView=user;
		$scope.modalShown3 = !$scope.modalShown3;
		//console.log('arun')
		
		var obj = $scope.detailedView.data.suddenAcceleration.normal.historySuddenAcceleration;
		//console.log(obj)
		var latitude=0;
		var longutide=0;
		var tempurl1='';
		var splitting='';
		var speed=0;
		var slow=0;
		var detailedJson=[];
		$scope.Values=[];
		$scope.aggressive=[];
		$scope.aggressiveCount=0;
		$scope.harsh=[];
		console.log(1)
		$scope.id=$scope.detailedView.data.vehicleId;
		$scope.harshCount=0;
		$scope.normal=0;
		$scope.locationname='';
		//normal breaks for loop
		angular.forEach(obj, function(value, key) 
		{	
			splitting=value.split(',');
			console.log('inthe for each---1--->'+splitting[0])
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
			
		});
		$scope.Values=detailedJson;
		$scope.normal=$scope.detailedView.data.suddenAcceleration.normal.totalSudAcceleration;
		
		
		
		
		var obj1 = $scope.detailedView.data.suddenAcceleration.aggressive.historySuddenAcceleration;
		var detailedJson1=[];
		//aggresive breaks for loop
		angular.forEach(obj1, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson1.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
		});
		$scope.aggressive=detailedJson1;
		$scope.aggressiveCount=$scope.detailedView.data.suddenAcceleration.aggressive.totalSudAcceleration;
		
		//harsh break
		var obj2 = $scope.detailedView.data.suddenAcceleration.harsh.historySuddenAcceleration;
		var detailedJson2=[];
		angular.forEach(obj2, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson2.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
		});
		$scope.harsh=detailedJson2;
		$scope.harshCount=$scope.detailedView.data.suddenAcceleration.harsh.totalSudAcceleration;
		//very harsh break
		var obj3 = $scope.detailedView.data.suddenAcceleration.veryharsh.historySuddenBrk;
		var detailedJson3=[];
		angular.forEach(obj3, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson3.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
		});
		$scope.Veryharsh=detailedJson3;
		$scope.veryHarsh=$scope.detailedView.data.suddenAcceleration.veryharsh.subTotalSuddenBreak;
		// worst performer
		var obj4 = $scope.detailedView.data.suddenAcceleration.worst.historySuddenBrk;
		var detailedJson4=[];
		angular.forEach(obj4, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson4.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
		});
		$scope.worst=detailedJson4;
		$scope.worstCount=$scope.detailedView.data.suddenAcceleration.worst.subTotalSuddenBreak;
	};
	
	// $scope.assignValue=function(dataVal){
	// 	$('#vehiid span').text(dataVal.vehicleId + " (" +dataVal.shortName+")");
	// 	$('#toddist span span').text(dataVal.distanceCovered);
	// 	$('#vehstat span').text(dataVal.position);
	// 	total = parseInt(dataVal.speed);
	// 	$('#vehdevtype span').text(dataVal.odoDistance);
	// 	$('#mobno span').text(dataVal.overSpeedLimit);
	// 	$('#positiontime').text(vamoservice.statusTime(dataVal).tempcaption);
	// 	$('#regno span').text(vamoservice.statusTime(dataVal).temptime);
	// 	$('#lstseendate').html('<strong>Last Seen Date & time :</strong> '+ dataVal.lastSeen);	
	// }
	
	// $scope.enterkeypress = function(){
	// 	var url = 'http://'+globalIP+'/vamo/public/setPOIName?vehicleId='+$scope.vehicleno+'&poiName='+document.getElementById('poival').value;
	// 	if(document.getElementById('poival').value=='' || $scope.vehicleno==''){}else{
	// 		vamoservice.getDataCall(url).then(function(data) {
	// 		 	document.getElementById('poival').value='';
	// 		});
	// 	}
	// }
	

	// $scope.createGeofence=function(url){
	// 	if($scope.cityCirclecheck==false){
	// 		$scope.cityCirclecheck=true;
	// 	}
	// 	if($scope.cityCirclecheck==true){
	// 		for(var i=0; i<$scope.cityCircle.length; i++){
	// 			$scope.cityCircle[i].setMap(null);
	// 		}
	// 		for(var i=0; i<geomarker.length; i++){
	// 			geomarker[i].setMap(null);
				
	// 		}
	// 		for(var i=0; i<geoinfo.length; i++){
	// 			geoinfo[i].setMap(null);
	// 		}
			
	// 	}
	// 	vamoservice.getDataCall(url).then(function(data) {
	// 		$scope.geoloc = data;
	// 		if (typeof(data.geoFence) !== 'undefined' && data.geoFence.length) {	
	// 			for(var i=0; i<data.geoFence.length; i++){
	// 				if(data.geoFence[i]!=null){
	// 				var populationOptions = {
	// 						  strokeColor: '#FF0000',
	// 						  strokeOpacity: 0.8,
	// 						  strokeWeight: 2,
	// 						  fillColor: '#FF0000',
	// 						  fillOpacity: 0.02,
	// 						  map: $scope.map,
	// 						  center: new google.maps.LatLng(data.geoFence[i].latitude,data.geoFence[i].longitude),
	// 						  radius: parseInt(data.geoFence[i].proximityLevel)
	// 				};
	// 				$scope.cityCircle[i] = new google.maps.Circle(populationOptions);
	// 				var centerPosition = new google.maps.LatLng(data.geoFence[i].latitude, data.geoFence[i].longitude);
	// 				var labelText = data.geoFence[i].poiName;
	// 				var image = 'assets/imgs/busgeo.png';
				  
	// 			  	var beachMarker = new google.maps.Marker({
	// 			      position: centerPosition,
	// 			      map: $scope.map,
	// 			      icon: image
	// 			  	});
	// 			  	geomarker.push(beachMarker);
	// 				}
	// 				var myOptions = { 
	// 				 	 content: labelText, 
	// 				 	 boxStyle: {
	// 				 	 	textAlign: "center", 
	// 				 	 	fontSize: "9pt", 
	// 				 	 	fontColor: "#ff0000", 
	// 				 	 	width: "100px"
	// 				 	 },
	// 					 disableAutoPan: true,
	// 					 pixelOffset: new google.maps.Size(-50, 0),
	// 					 position: centerPosition,
	// 					 closeBoxURL: "",
	// 					 isHidden: false,
	// 					 pane: "mapPane",
	// 					 enableEventPropagation: true
	// 				 };
	// 				 var labelinfo = new InfoBox(myOptions);
	// 				 labelinfo.open($scope.map);
	// 				// labelinfo.setPosition($scope.cityCircle[i].getCenter());
	// 				 geoinfo.push(labelinfo);
	// 			}
	// 		}
	// 	});
	// }
	
	// $scope.initial02 = function(){
	// 	$scope.assignHeaderVal($scope.locations02);
	// 	var locs = $scope.locations;
	//  	var parkedCount = 0;
	// 	var movingCount = 0;
	// 	var idleCount = 0;
	// 	var overspeedCount = 0;

	// 	for (var i = 0; i < $scope.locations02[$scope.gIndex].vehicleLocations.length; i++) {
	// 		if($scope.locations02[$scope.gIndex].vehicleLocations[i].position=="P"){
	// 			parkedCount=parkedCount+1;
	// 		}else  if($scope.locations02[$scope.gIndex].vehicleLocations[i].position=="M"){
	// 			movingCount=movingCount+1;
	// 		}else if($scope.locations02[$scope.gIndex].vehicleLocations[i].position=="S"){
	// 			idleCount=idleCount+1;
	// 		}
	// 		if($scope.locations02[$scope.gIndex].vehicleLocations[i].isOverSpeed=='Y'){
	// 			overspeedCount=overspeedCount+1;
	// 		}
	// 	}
		
	// 	$scope.parkedCount = parkedCount;
	// 	$scope.movingCount = movingCount;
	// 	$scope.idleCount  = idleCount;
	// 	$scope.overspeedCount = overspeedCount;
		
	// 	 for (var i = 0; i < gmarkers.length; i++) {
	// 		var temp = $scope.locations[i];	 
	// 		 var lat = temp.latitude;
	// 		 var lng =  temp.longitude;
	// 		 var latlng = new google.maps.LatLng(lat,lng);
	// 		 gmarkers[i].icon = vamoservice.iconURL(temp);
	// 		 gmarkers[i].setPosition(latlng);
	// 		 gmarkers[i].setMap($scope.map);
	// 		 if(temp.vehicleId==$scope.vehicleno){
	// 			 $scope.assignValue(temp);
	// 			 $scope.selected=i;
	// 			 $scope.getLocation(lat, lng, function(count){
	// 				 $('#lastseen').text(count);
	// 				 var t = vamoservice.geocodeToserver(lat,lng,count);
	// 			 });	
	// 		 }
	// 		 //$scope.infoBoxed($scope.map,gmarkers[i], temp.vehicleId, lat, lng, temp);
	// 	 }	 	
	// 	if($scope.selected!=undefined){
	// 		$scope.map.setCenter(gmarkers[$scope.selected].getPosition()); 	
	// 	}
	// }
	
	// $scope.initilize = function(ID){
		
	// 	vamoservice.getDataCall($scope.url).then(function(location02) {
			
	// 		if($('.nav-second-level li').eq($scope.selected).children('a').hasClass('active')){
	// 		}else{
	// 			$('.nav-second-level li').eq($scope.selected).children('a').addClass('active');
	// 		}
	// 		var locs = $scope.locations;
	// 		$scope.assignHeaderVal(location02);
			
	// 		var lat = location02[$scope.gIndex].latitude;
	// 		var lng = location02[$scope.gIndex].longitude;
	// 		var myOptions = { zoom: $scope.zoomLevel, center: new google.maps.LatLng(lat, lng), mapTypeId: google.maps.MapTypeId.ROADMAP,styles: [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]};
	// 		$scope.map = new google.maps.Map(document.getElementById(ID), myOptions);
			
	// 		google.maps.event.addListener($scope.map, 'click', function(event) {
	// 			if($scope.clickflag==true){
	// 				if($scope.clickflagVal ==0){
	// 					$scope.firstLoc = event.latLng;
	// 					$scope.clickflagVal =1;
	// 				}else if($scope.clickflagVal==1){
	// 					$scope.drawLine($scope.firstLoc, event.latLng);
	// 					$scope.firstLoc = event.latLng;
	// 				}
	// 			}else if($scope.nearbyflag==true){
	// 				$('#status02').show(); 
	// 				$('#preloader02').show(); 
	// 				var tempurl = 'http://'+globalIP+'/vamo/public/getNearByVehicles?lat='+event.latLng.lat()+'&lng='+event.latLng.lng();
					
	// 				$http.get(tempurl).success(function(data){
	// 					$scope.nearbyLocs = data;
	// 					$('#status02').fadeOut(); 
	// 					$('#preloader02').delay(350).fadeOut('slow');
	// 					if($scope.nearbyLocs.fromAddress==''){}else{
	// 						$('.nearbyTable').delay(350).show();
	// 					}
	// 				});
	// 			}
	// 		});
	// 		google.maps.event.addListener($scope.map, 'bounds_changed', function() {
	// 			var bounds = $scope.map.getBounds();
	// 		});
	// 		var parkedCount = 0;
	// 		var movingCount = 0;
	// 		var idleCount = 0;
	// 		var overspeedCount =0;
			
	// 		for (var i = 0; i < location02[$scope.gIndex].vehicleLocations.length; i++) {
	// 			if(location02[$scope.gIndex].vehicleLocations[i].position=="P"){
	// 				parkedCount=parkedCount+1;
	// 			}else  if(location02[$scope.gIndex].vehicleLocations[i].position=="M"){
	// 				movingCount=movingCount+1;
	// 			}else if(location02[$scope.gIndex].vehicleLocations[i].position=="S"){
	// 				idleCount=idleCount+1;
	// 			}
	// 			if($scope.locations02[$scope.gIndex].vehicleLocations[i].isOverSpeed=='Y'){
	// 				overspeedCount=overspeedCount+1;
	// 			}
	// 		}
			
	// 		$scope.parkedCount = parkedCount;
	// 		$scope.movingCount = movingCount;
	// 		$scope.idleCount  = idleCount;
	// 		$scope.overspeedCount = overspeedCount;
			
	// 		var length = locs.length;
	// 		gmarkers=[];
	// 		ginfowindow=[];
	// 		for (var i = 0; i < length; i++) {
	// 			var lat = locs[i].latitude;
	// 			var lng =  locs[i].longitude;
	// 			$scope.addMarker({ lat: lat, lng: lng , data: locs[i]});
	// 			$scope.infoBoxed($scope.map,gmarkers[i], locs[i].vehicleId, lat, lng, locs[i]);
	// 		}
	// 	});
	// 	$scope.loading	=	false;
	// 	if($scope.selected>-1 && gmarkers[$scope.selected]!=undefined){
	// 		$scope.map.setCenter(gmarkers[$scope.selected].getPosition()); 	
	// 	}
	// 	$(document).on('pageshow', '#maploc', function(e){       
 //        	google.maps.event.trigger(document.getElementById('	maploc'), "resize");
 //   		});
	// }
	
	// $scope.removeTask=function(vehicleno){
	// 	$scope.vehicleno = vehicleno;
	// 	var temp = $scope.locations;
	// 	//$scope.endlatlong = new google.maps.LatLng();
	// 	//$scope.startlatlong = new google.maps.LatLng();
	// 	$scope.map.setZoom(19);
		
	// 	for(var i=0; i<temp.length;i++){
	// 		if(temp[i].vehicleId==$scope.vehicleno){
				
	// 			$scope.selected=i;
				
	// 			$scope.map.setCenter(gmarkers[i].getPosition());
				
	// 			$scope.assignValue(temp[i]);
	// 			$scope.getLocation(temp[i].latitude, temp[i].longitude, function(count){ 
	// 				$('#lastseen').text(count); 
	// 			});
	// 			$scope.infowindowShow={};				
	// 			for(var j=0; j<ginfowindow.length;j++){
	// 				ginfowindow[j].close();
	// 			}
	// 			ginfowindow[i].open($scope.map,gmarkers[i]);
	// 			var url = 'http://'+globalIP+'/vamo/public/getGeoFenceView?vehicleId='+$scope.vehicleno;
	// 			$scope.createGeofence(url);
				
	// 		}
	// 	}
	// };
	
	// $scope.infowindowshowFunc = function(){
	// 	for(var j=0; j<ginfowindow.length;j++){
	// 		ginfowindow[j].close();
	// 	}
	// 	var tempoTime = vamoservice.statusTime($scope.infowindowShow.dataTempVal);
	// 	var contentString = '<div style="padding:10px; width:200px; height:auto;">'
	// 	+'<div><b>Vehicle ID</b> - '+$scope.infowindowShow.dataTempVal.vehicleId+'('+$scope.infowindowShow.dataTempVal.shortName+')</div>'
	// 	+'<div><b>Speed</b> - '+$scope.infowindowShow.dataTempVal.speed+'</div>'
	// 	+'<div><b>odoDistance</b> - '+$scope.infowindowShow.dataTempVal.odoDistance+'</div>'
	// 	+'<div><b>Distance Covered</b> - '+$scope.infowindowShow.dataTempVal.distanceCovered+'</div>'
	// 	+'<div><b>'+tempoTime.tempcaption+' Time</b> - '+tempoTime.temptime+'</div><br>'
	// 	+'<div><a href="../public/track?vehicleId='+$scope.infowindowShow.dataTempVal.vehicleId+'" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/replay?vehicleId='+$scope.infowindowShow.dataTempVal.vehicleId+'" target="_self">History</a></div>'
	// 	+'</div>';
	// 	$scope.infowindowShow.currinfo.setContent(contentString);
	// 	$scope.infowindowShow.currinfo.open($scope.map,$scope.infowindowShow.currmarker);
	// }
	
	// $scope.infowindowshowFunc1 = function(){
		
		
	// 	var contentString = '<div style="padding:10px; width:200px; height:auto;">'
	// 	+'<div><b>Vehicle ID</b> - '+'arun welcome'+'</div>';
	// 	//console.log('arun here')
		
	// }
	
	// $scope.assignHeaderVal = function(data){
	// 	$scope.distanceCovered =data[$scope.gIndex].distance;
	// 	$scope.alertstrack = data[$scope.gIndex].alerts;
	// 	$scope.totalVehicles  =data[$scope.gIndex].totalVehicles;
	// 	$scope.attention  =data[$scope.gIndex].attention;
	// 	$scope.vehicleOnline =data[$scope.gIndex].online;
	// }
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
})
// .directive('map', function($http, vamoservice) {
//     return {
//         restrict: 'E',
//         replace: true,
//         template: '<div></div>',
//         link: function(scope, element, attrs){
//         	scope.$watch("url", function (val) {
// 			setintrvl = setInterval(function(){
// 				vamoservice.getDataCall(scope.url).then(function(data) {
// 					if(data.length){
// 						scope.selected=undefined;
// 						scope.locations02 = data;
// 						//scope.vehiname	= data[scope.gIndex].vehicleLocations[scope.selected].vehicleId;
// 						scope.locations = scope.statusFilter(scope.locations02[scope.gIndex].vehicleLocations, scope.vehicleStatus);
// 						scope.zoomLevel = scope.zoomLevel;
// 						//scope.initial02();
// 						scope.loading	=	true;
// 						//scope.initilize('map_canvas');
// 						scope.initial02();
// 					}
// 				}); 
// 			},60000);
// 	  	}); 
// 	    }
// 	};
	

// });
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
	
    // var gaugeOptions = {
    //     chart: {
    //         type: 'solidgauge',
    //         backgroundColor:'rgba(255, 255, 255, 0)'
    //     },
    //     title: null,
    //     pane: {
    //         center: ['50%', '90%'],
    //         size: '180%',
    //         startAngle: -90,
    //         endAngle: 90,
    //         background: {
    //             innerRadius: '60%',
    //             outerRadius: '100%',
    //             shape: 'arc'
    //         }
    //     },
    //     tooltip: {
    //         enabled: false
    //     },
    //     yAxis: {
    //         stops: [
    //             [0.1, '#55BF3B'], 
    //             [0.5, '#DDDF0D'], 
    //             [0.9, '#DF5353'] 
    //         ],
    //         lineWidth: 0,
    //         minorTickInterval: null,
    //         tickPixelInterval: 400,
    //         tickWidth: 0,
    //         title: {
    //             y: -50
    //         },
    //         labels: {
    //             y: -100
    //         }
    //     },
    //     plotOptions: {
    //         solidgauge: {
    //             dataLabels: {
    //                 y: 5,
    //                 borderWidth: 0,
    //                 useHTML: true
    //             }
    //         }
    //     }
    // };

    // $('#container-speed').highcharts(Highcharts.merge(gaugeOptions, {
    //     yAxis: {
    //         min: 0,
    //         max: 120,
    //         title: { text: '' }
    //     },
    //     credits: { enabled: false },
    //     series: [{
    //         name: 'Speed',
    //         data: [total],
    //         dataLabels: {
    //             format: '<div style="text-align:center"><span style="font-size:12px; font-weight:normal;color: #196481'+ '">Speed - {y} km</span><br/>',
    //              y: 25
    //         },
    //         tooltip: { valueSuffix: ' km/h'}
    //     }]
    // }));
    // setInterval(function () {
    //   var chart = $('#container-speed').highcharts(), point;
    //     if (chart) {
    //         point = chart.series[0].points[0];
    //         point.update(total);
    //     }
    // }, 1000);
});
