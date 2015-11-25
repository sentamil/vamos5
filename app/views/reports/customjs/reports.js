//comment by satheesh ++...
//var total = 0;
//var chart = null;
//http://128.199.175.189/
var getIP	=	globalIP;
var app = angular.module('mapApp',['ui.bootstrap']);
//var gmarkers=[];
//var ginfowindow=[];
app.controller('mainCtrl',function($scope, $http){
	/*$scope.locations = [];
	$scope.nearbyLocs =[];
	$scope.val = 5;	
	$scope.gIndex = 0;
	$scope.alertstrack = 0;
	$scope.totalVehicles = 0;
	$scope.vehicleOnline = 0;
	$scope.distanceCovered= 0;
	$scope.attention= 0;
	$scope.vehicleno=0;
	
	$scope.markerClicked=false;*/
	$scope.vvid			=	getParameterByName('vid');
	//alert($scope.vvid)
	$scope.mainlist		=	[];

	$scope.url 			= 	'http://'+getIP+'/vamo/public/getVehicleLocations';
	
	//console.log(' url '+$scope.conUrl)
	$scope.getTodayDate1  =	function(date) {
     	var date = new Date(date);
		return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
    };

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

	

	$scope.url 			= 	'http://'+getIP+'/vamo/public//getVehicleLocations';



	// $scope.fromDate;
	// $scope.todate;
	$scope.fromTime ='00:00:00';
	$scope.totime='11:59:00';
	$scope.vehigroup;
	$scope.consoldateData=[];

// 	var today = new Date();
// var dd = today.getDate();
// var mm = today.getMonth()+1; //January is 0!
// var yyyy = today.getFullYear();

// if(dd<10) {
//     dd='0'+dd
// } 

// if(mm<10) {
//     mm='0'+mm
// } 

// today = dd+'/'+mm+'/'+yyyy;
// var day = Date.parse(today);
// $scope.fromdate = day;
// console.log(day)
	//console.log(day[0].moment)
	// $scope.getTodayDate  =	function(date) {
 //     	var date = new Date(date);
	// 	return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
 //    };
	//$scope.historyfor='';
	//console.log(' group name '+groupname)
	 
	$scope.sort = {       
                sortingOrder : 'id',
                reverse : false
            };
    $scope.$watch("url", function (val) {
		//$scope.today =	$scope.getTodayDate();
		//alert(111);
		$scope.loading	=	true;
	 	$http.get($scope.url).success(function(data){
			$scope.locations 	= 	data;
			$scope.vehigroup    =   data[0].group;
			$scope.consoldate(data[0].group);
			if(data.length)
				$scope.vehiname		=	data[0].vehicleLocations[0].vehicleId;
			angular.forEach(data, function(value, key) {
				   if(value.totalVehicles) {
				  		$scope.data1		=	data[key];
				  }
			});				
			
		//$scope.zoomLevel = parseInt(data[$scope.gIndex].zoomLevel);
		$scope.loading	=	false;
		$scope.recursive($scope.data1.vehicleLocations,0);
		}).error(function(){ /*alert('error'); */});
	});
	
	// var checkNulValue = function(result)
	// {
	// 	angular.forEach(data, function(value, key){
	// 		if(value==null){console.log('null')}else{$scope.consoldateData = data;}
	// 	})
	// }
	
    $scope.consoldate1 =  function()
	{
		$scope.fromdate1   = document.getElementById("dateFrom").value;
		var ftime          = document.getElementById("timeFrom").value;
		$scope.fromTime    = ftime.split(" ")[0]+':00';
		$scope.todate1     = document.getElementById("dateTo").value;
		var ttime          = document.getElementById("timeTo").value;
		$scope.totime      = ttime.split(" ")[0]+':00';
		$scope.loading	   = true;
		var conUrl1        = 'http://'+getIP+'/vamo/public/getOverallVehicleHistory?group='+$scope.vehigroup+'&fromDate='+$scope.fromdate1+'&fromTime='+$scope.fromTime+'&toDate='+$scope.todate1+'&toTime='+$scope.totime;
		$http.get(conUrl1).success(function(data)
		{
			$scope.consoldateData = data;
			
		});
		$scope.loading	=	false;
	}

	$scope.consoldate =  function(group)
	{
		$scope.fromNowTS1		=	new Date();
	//$scope.toNowTS1			=	new Date().getTime() - 86400000;
	$scope.fromdate1		=	$scope.getTodayDate1($scope.fromNowTS1.setDate($scope.fromNowTS1.getDate()));
	$scope.todate1			=	$scope.getTodayDate1($scope.fromNowTS1.setDate($scope.fromNowTS1.getDate()));
	$scope.fromtime1		=	formatAMPM($scope.fromNowTS1);
	$scope.totime1			=	formatAMPM($scope.toNowTS1);

		$scope.loading	=	true;
		var conUrl       =   'http://'+getIP+'/vamo/public/getOverallVehicleHistory?group='+group+'&fromDate='+$scope.fromdate1+'&fromTime='+$scope.fromTime+'&toDate='+$scope.todate1+'&toTime='+$scope.totime;
		$http.get(conUrl).success(function(data)
		{$scope.consoldateData = data;
		});
		$scope.loading	=	false;
	}
		

	$scope.exportData = function (data) {
		//console.log(data);
		var blob = new Blob([document.getElementById(data).innerHTML], {
           	type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };
    $scope.exportDataCSV = function (data) {
		//console.log(data);
		CSV.begin('#'+data).download(data+'.csv').go();
    };

// $scope.getTodayDate1  =	function(date) {
//      	var date = new Date(date);
//     	return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
//     };

      $scope.msToTime = function(ms) {
       
    // var x = ms / 1000;
    // var seconds = Math.round(x % 60);
    // x /= 60;
    // var minutes = Math.round(x % 60);
    // x /= 60;
    // var hours = Math.round(x % 24);
    // x /= 24;
    // var days = Math.round(x);
  days = Math.floor(ms / (24 * 60 * 60 * 1000));
  daysms = ms % (24 * 60 * 60 * 1000);
  hours = Math.floor((daysms) / (60 * 60 * 1000));
  hoursms = ms % (60 * 60 * 1000);
  minutes = Math.floor((hoursms) / (60 * 1000));
  minutesms = ms % (60 * 1000);
  seconds = Math.floor((minutesms) / 1000);
  //var rep = days + ":" + hours + ":" + minutes + ":" + seconds;
  
//     var value = 42838395/3600000;
//     var i = '';
//     i = value;	
//     console.log(value);
// var name = i.split(".")[0];
//     console.log(' total value '+ms+' hrs '+extensionRemoved)

    return hours +" hrs "+minutes+" min "+seconds+" sec ";


        
    }
	
	$scope.recursive   = function(location,index){
		if(location.length<=index){
			return;
		}else{
			
			var lat		 =	location[index].latitude;
			var lon		 =	location[index].longitude;
			if(!lat || !lon)
				$scope.recursive(location, ++index);
			var tempurl	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon+"&sensor=true";
			$http.get(tempurl).success(function(data){	
				$scope.locationname		=	data.results[0].formatted_address;
				$scope.mainlist[index]	=	data.results[0].formatted_address;
				setTimeout(function() {
				      $scope.recursive(location, ++index);
				}, 2000);
			});
		}
	}
	
	
	$scope.$watch('vvid', function () {
		if($scope.vvid) {
			$scope.getStatusReport();
		}
	});
	
	$scope.getLocation	=	function(lat,lon,ind) {	
		var tempurl	 =	"http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon+"&sensor=true";
		//Fconsole.log(tempurl);
		$scope.loading	=	true;
		$http.get(tempurl).success(function(data){	
			//console.log(data);
			$scope.locationname = data.results[0].formatted_address;
			$scope.mainlist[ind]=	data.results[0].formatted_address;
			$scope.loading	=	false;
		});
	};
	
	$scope.getStatusReport		=		function() {
		 $scope.url = 'http://'+getIP+'/vamo/public//getVehicleLocations?group='+$scope.vvid;
	}
	
	$scope.getTodayDate		=		function() {
		var currentDate = new Date();
	    var day = currentDate.getDate();
	    var month = currentDate.getMonth() + 1;
	    var year = currentDate.getFullYear();
	    if(day<10) {
		    day='0'+day;
		} 		
		if(month<10) {
		    month='0'+month;
		} 
	  	return year + "-" + month + "-" + day;		
	}
	
	function getParameterByName(name) {
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	

	/*$scope.map =  null;
	$scope.flightpathall = []; 
	$scope.clickflag = false;
	$scope.flightPath = new google.maps.Polyline();
	$scope.onHistoryClick = function(vehicleno){
		$scope.currentTab = 'history';
		$scope.historyfor = 'history for - ' + vehicleno;
	}
	var tempdistVal = 0;
	$scope.drawLine = function(loc1, loc2){
		var flightPlanCoordinates = [loc1, loc2];
		
		//$scope.flightPath.setMap(null);
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
	}*/
	
	$scope.genericFunction = function(vehicleno, index){
		
		/*$scope.selected = index;
		
		if($scope.currentTab=='map'){
			$scope.removeTask(vehicleno);		
		}else if($scope.currentTab=='history'){
			$scope.onHistoryClick(vehicleno);
		}else{
		
		}*/
	}
	
	/*$scope.trafficLayer = new google.maps.TrafficLayer();
	$scope.checkVal=false;
	$scope.clickflagVal =0;
	$scope.nearbyflag = false;
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
			alert('click location you want to find nearby Vehicles');	
			$scope.nearbyflag=true;
		}else{
			$('.nearbyTable').hide();
			$scope.nearbyflag=false;
		}
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
	$scope.tabs = [{ title: 'Live Tracking', tabSelected: 'map'
        }, {
            title: 'History',
            tabSelected: 'history'
        }, {
            title: 'Alerts',
            tabSelected: 'alert'
    }];
	
	$scope.currentTab = 'map';
	$scope.isActiveTab = function(tabUrl) {
		return tabUrl == $scope.currentTab;
    }*/
	
	$scope.groupSelection = function(groupname, groupid){
		 $scope.url = 'http://'+getIP+'/vamo/public//getVehicleLocations?group='+groupname;
		 /*$scope.gIndex = groupid;
		 $scope.selected=undefined; 
		 gmarkers=[];
		 ginfowindow=[];*/
	}
	
	
	/*$scope.addMarker= function(pos){
	   var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
	   if(pos.data.alert!=''){
			var pinImage = 'assets/imgs/marker-l-red.png';
		}else{
			var pinImage = 'assets/imgs/marker-l-mer.png';
		}
		
		$scope.marker = new MarkerWithLabel({
		   position: myLatlng, 
		   map: $scope.map,
		   icon: pinImage,
		   labelContent: pos.data.shortName,
		   labelAnchor: new google.maps.Point(12, 38),
		   labelClass: "labels", // the CSS class for the label
		   labelInBackground: false
		 });
		 gmarkers.push($scope.marker);
		
		if(pos.data.vehicleId==$scope.vehicleno){
			$('#vehiid h3').text(pos.data.vehicleId + " (" +pos.data.shortName+")");
			$('#toddist h3').text(pos.data.distanceCovered);
			$('#vehstat h3').text(pos.data.position);
			total = parseInt(pos.data.speed);
			$('#vehdevtype h3').text(pos.data.deviceType);
			$('#mobno h3').text(pos.data.mobileNumber);
			$('#regno h3').text(pos.data.regNumber);
			//$('#lastseen').text(pos.data.currentAddress);		
		}
		
		 google.maps.event.addListener($scope.marker, "click", function(e){	
				$('.bottomContent').fadeIn(100); 			
				$scope.vehicleno = pos.data.vehicleId;
				$('#vehiid h3').text(pos.data.vehicleId + " (" +pos.data.shortName+")");
				$('#toddist h3').text(pos.data.distanceCovered);
				$('#vehstat h3').text(pos.data.position);
				total = parseInt(pos.data.speed);
				$('#vehdevtype h3').text(pos.data.deviceType);
				$('#mobno h3').text(pos.data.mobileNumber);
				$('#regno h3').text(pos.data.regNumber);
				//$('#lastseen').text(pos.data.currentAddress);	
				$scope.map.setCenter($scope.marker.getPosition());
        });
		
        (function(marker, data, contentString) {
        	google.maps.event.addListener($scope.marker, "click", function(e) {
           		$scope.map.setCenter($scope.marker.getPosition());
          	});
        })($scope.marker, pos.data);
	}*/
	
	/*$scope.removeTask=function(vehicleno){
		$('.bottomContent').fadeIn(100);
		var temp = $scope.locations[$scope.gIndex].vehicleLocations;
		$scope.vehicleno = vehicleno;
		$scope.map.setCenter($scope.marker.getPosition());
		for(var i=0; i<temp.length;i++){
			if(temp[i].vehicleId==$scope.vehicleno){
				$('#vehiid h3').text(temp[i].vehicleId + " (" +temp[i].shortName+")");
				$('#toddist h3').text(temp[i].distanceCovered);
				$('#vehstat h3').text(temp[i].position);
				total = parseInt(temp[i].speed);
				$('#vehdevtype h3').text(temp[i].deviceType);
				$('#mobno h3').text(temp[i].mobileNumber);
				$('#regno h3').text(temp[i].regNumber);			
				var tempurl = "http://128.199.175.189:8087/vamosgps/public//reverseGeoLocation?lat="+temp[i].latitude +"&lng="+temp[i].longitude;
				$http.get(tempurl).success(function(data){
					$('#lastseen').text(data);
				}).error(function(){});
			}
		}		
	};*/
	
	$scope.exportData = function (data) {
    	//console.log(data);
		var blob = new Blob([document.getElementById(data).innerHTML], {
           	type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };
    
    $scope.exportDataCSV = function (data) {
		//console.log('---->'+data);
		CSV.begin('#'+data).download(data+'.csv').go();
    };
    
    
});

app.directive("getLocation", function () {
  return {
    restrict: "A",
    replace: true,    
    link: function (scope, element, attrs) {
	    angular.element(element).on('click', function(){
	    	var lat = attrs.lat;
	    	var lon = attrs.lon;
	    	var ind	= attrs.index;
			scope.getLocation(lat,lon,ind);
		});
    }
  };
});

app.directive("customSort", function() {
return {
    restrict: 'A',
    transclude: true,    
    scope: {
      order: '=',
      sort: '='
    },
    template : 
      ' <a ng-click="sort_by(order)" style="color: #555555;">'+
      '    <span ng-transclude></span>'+
      '    <i ng-class="selectedCls(order)"></i>'+
      '</a>',
    link: function(scope) {
                
    // change sorting order
    scope.sort_by = function(newSortingOrder) {       
        var sort = scope.sort;
        
        if (sort.sortingOrder == newSortingOrder){
            sort.reverse = !sort.reverse;
        }                    

        sort.sortingOrder = newSortingOrder;        
    };
    
   
    scope.selectedCls = function(column) {
        if(column == scope.sort.sortingOrder){
            return ('icon-chevron-' + ((scope.sort.reverse) ? 'down' : 'up'));
        }
        else{            
            return'icon-sort' 
        } 
    };      
  }// end link
}
});
/*app.directive('map', function($http) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {	
			scope.$watch("url", function (val) {
				
				$http.get(scope.url).success(function(data){
					var locs = data;
					
					scope.locations = data;
					scope.distanceCovered =locs[scope.gIndex].distance;
					scope.alertstrack = locs[scope.gIndex].alerts;
					scope.totalVehicles  =locs[scope.gIndex].totalVehicles;
					scope.attention  =locs[scope.gIndex].attention;
					scope.vehicleOnline =locs[scope.gIndex].online;
					var lat = locs[scope.gIndex].latitude;
					var long = locs[scope.gIndex].longitude;
					var myOptions = { zoom: scope.zoomLevel, center: new google.maps.LatLng(lat, long), mapTypeId: google.maps.MapTypeId.ROADMAP, styles: [{featureType:"administrative",stylers:[{visibility:"off"}]},{featureType:"poi",stylers:[{visibility:"simplified"}]},{featureType:"road",elementType:"labels",stylers:[{visibility:"simplified"}]},{featureType:"water",stylers:[{visibility:"simplified"}]},{featureType:"transit",stylers:[{visibility:"simplified"}]},{featureType:"landscape",stylers:[{visibility:"simplified"}]},{featureType:"road.highway",stylers:[{visibility:"off"}]},{featureType:"road.local",stylers:[{visibility:"on"}]},{featureType:"road.highway",elementType:"geometry",stylers:[{visibility:"on"}]},{featureType:"water",stylers:[{color:"#84afa3"},{lightness:52}]},{stylers:[{saturation:-17},{gamma:0.36}]},{featureType:"transit.line",elementType:"geometry",stylers:[{color:"#3f518c"}]}]
					};
					scope.map = new google.maps.Map(document.getElementById(attrs.id), myOptions);
					
					var input = document.getElementById('pac-input');
					var input01 = document.getElementById('pac-input01');
					
  					scope.map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
					scope.map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input01);
					
					var searchBox = new google.maps.places.SearchBox(input);
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
						scope.map.setZoom(scope.zoomLevel);
					});
					
					google.maps.event.addListener(scope.map, 'click', function(event) {
						if(scope.clickflag==true){
							if(scope.clickflagVal ==0){
								scope.firstLoc = event.latLng;
								scope.clickflagVal =1;
							}else if(scope.clickflagVal==1){
								scope.drawLine(scope.firstLoc, event.latLng);
								scope.firstLoc = event.latLng;
							}
						}else if(scope.nearbyflag==true){
							$('.nearbyTable').show();
							var tempurl = "http://128.199.175.189:8087/vamosgps/public//getNearByVehicles?lat="+event.latLng.lat()+"&lng="+event.latLng.lng();
							$http.get(tempurl).success(function(data){
								scope.nearbyLocs = data;
							}).error(function(){ });
						}
					});
					google.maps.event.addListener(scope.map, 'bounds_changed', function() {
						var bounds = scope.map.getBounds();
						scope.selected=undefined;
						searchBox.setBounds(bounds);
					});
					
					var length = locs[scope.gIndex].vehicleLocations.length;
					for (var i = 0; i < length; i++) {
						var lat = locs[scope.gIndex].vehicleLocations[i].latitude;
						var lng =  locs[scope.gIndex].vehicleLocations[i].longitude;
						scope.addMarker({ lat: lat, lng: lng , data: locs[scope.gIndex].vehicleLocations[i]});
					}
				}).error(function(){});
			});
			setInterval(function () {
				$http.get(scope.url).success(function(data){
					var locs = data;
					scope.locations = data;
					scope.distanceCovered =locs[scope.gIndex].distance;
					scope.alertstrack = locs[scope.gIndex].alerts;
					scope.totalVehicles  =locs[scope.gIndex].totalVehicles;
					scope.attention  =locs[scope.gIndex].attention;
					scope.vehicleOnline =locs[scope.gIndex].online;
					var length = locs[scope.gIndex].vehicleLocations.length;
					for (var i = 0; i < gmarkers.length; i++) {
						gmarkers[i].setMap(null);
					}
					gmarkers=[];
		 			ginfowindow=[];
					for (var i = 0; i < length; i++) {
						var lat = locs[scope.gIndex].vehicleLocations[i].latitude;
						var lng =  locs[scope.gIndex].vehicleLocations[i].longitude;
						scope.addMarker({ lat: lat, lng: lng , data: locs[scope.gIndex].vehicleLocations[i]});
					}
					if(scope.selected!=undefined){
						scope.map.setCenter(gmarkers[scope.selected].getPosition()); 
					}
				}).error(function(){ });
				}, 3000);
        }
    };
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
	$('.bottomContent').hide();
	$('.contentbClose').click(function(){ $('.bottomContent').fadeOut(100); });
	chart =	$('.container01').highcharts({
				chart: {
						type: 'gauge',
						plotBackgroundColor: null,
						plotBackgroundImage: null,
						plotBorderWidth: 0,
						plotShadow: false,
						backgroundColor:'transparent'
				},
				title: {
					text: ''
				},
				pane: {
            startAngle: -90,
            endAngle: 90,
            size: [20],
             center: ['50%', '100%'],
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
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#666'],
                        [1, '#0c0c0c']
                    ]
                },
                borderWidth: 1,
                outerRadius: '107%'
            }, {
                backgroundColor: '#DDD',
                borderWidth: 1,
                outerRadius: '105%',
                innerRadius: '104%'
            }]
        },            
        plotOptions: {
			gauge: {
				dial: {
					baseWidth: 4,
					backgroundColor: '#C33',
					borderColor: '#900',
					borderWidth: 1,
					rearLength: 20,
					baseLength: 10,
					radius: 80
				}                
			}
        },
        yAxis: [{
            min: 0,
            max: 200,
            lineColor: '#fff',
            tickColor: '#fff',
            minorTickColor: '#fff',
            minorTickPosition: 'inside',
            tickLength: 10,
            tickWidth: 4,
            minorTickLength: 5,
            offset: -2,
            lineWidth: 2,
            labels: {
                distance: -25,
                rotation: 0,
                style: {
                    color: '#fff',
                    size: '120%',
                    fontWeight: 'bold'
                }
            },
            endOnTick: false,
            plotBands: [{
                from: 0,
                to: 100,
                innerRadius: '40%',
                outerRadius: '65%',
                color: {linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#fff'],
                        [1, '#fff']
                    ]} // green
            }, {
                from: 0,
                to: 50,
                innerRadius: '45%',
                outerRadius: '60%',
                color: '#000' 
            }]
        }],    
        series: [{
			data: [10],
            name: 'Speed',
            dataLabels: {
                backgroundColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, '#DDD'],
                        [1, '#FFF']
                    ]
                },
                offset: 10
            },
            tooltip: {
                valueSuffix:'kmh'
            }
        }]    
    },
	function (chart) {
		setInterval(function () {
			chart.series[0].setData([total]);
		},1000);
	});	
	
});
*/