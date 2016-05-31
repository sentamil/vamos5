app.controller('mainCtrl',['$scope','vamoservice','$filter', function($scope, vamoservice, $filter){

// trip summary , 	site report, trip report 

	//global declaration
	
	$scope.uiDate 				=	{};
	$scope.interval				= 	10;
  	$scope.siteEntry 			=	0;
	$scope.siteExit 			=	0;
	//loading start function
	var startLoading		= function () {
		$('#status').show(); 
		$('#preloader').show();
	};

	//loading stop function
	var stopLoading		= function () {
		$('#status').fadeOut(); 
		$('#preloader').delay(350).fadeOut('slow');
		$('body').delay(350).css({'overflow':'visible'});
	};

	$scope.msToTime		=	function(ms) {
		days = Math.floor(ms / (24*60*60*1000));
	    daysms=ms % (24*60*60*1000);
	    hours = Math.floor((daysms)/(60*60*1000));
	    hoursms=ms % (60*60*1000);
	    minutes = Math.floor((hoursms)/(60*1000));
	    minutesms=ms % (60*1000);
	    sec = Math.floor((minutesms)/(1000));
	    return days+"d : "+hours+"h : "+minutes+"m : "+sec+"s";
    }


    //chants for temperature

	// $(function () {
function plottinGraphs(valueGraph){
    // $scope.averages = [
    //         [1246406400000, 21.5],
    //         [1246492800000, 22.1],
    //         [1246579200000, 23],
    //         [1246665600000, 23.8],
    //         [1246752000000, 21.4],
    //         [1246838400000, 21.3],
    //         [1246924800000, 18.3],
    //         [1247011200000, 15.4],
    //         [1247097600000, 16.4],
    //         [1247184000000, 17.7],
    //         [1247270400000, 17.5],
    //         [1247356800000, 17.6],
    //         [1247443200000, 17.7],
    //         [1247529600000, 16.8],
    //         [1247616000000, 17.7],
    //         [1247702400000, 16.3],
    //         [1247788800000, 17.8],
    //         [1247875200000, 18.1],
    //         [1247961600000, 17.2],
    //         [1248048000000, 14.4],
    //         [1248134400000, 13.7],
    //         [1248220800000, 15.7],
    //         [1248307200000, 14.6],
    //         [1248393600000, 15.3],
    //         [1248480000000, 15.3],
    //         [1248566400000, 15.8],
    //         [1248652800000, 15.2],
    //         [1248739200000, 14.8],
    //         [1248825600000, 14.4],
    //         [1248912000000, 15],
    //         [1248998400000, 13.6]
    //     ];


    $('#temperatureChart').highcharts({

        title: {
            text: ' '
        },

        xAxis: {
            type: 'datetime'
        },

        yAxis: {
            title: {
                text: null
            }
        },

        tooltip: {
            crosshairs: true,
            shared: true,
            valueSuffix: '°C'
        },

        legend: {
        },

        series: [{
            name: 'Temperature',
            
            data: valueGraph,
            zIndex: 1,
            color: Highcharts.getOptions().colors[0],
            marker: {
            	enabled: true,
                fillColor: 'white',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[0],
                symbol: 'circle'
            }
        }]
    });
};

	function getParameterByName(name) {
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	$scope.sort = {       
                sortingOrder : 'id',
                reverse : false
            };
	//global declartion

	$scope.locations = [];
	$scope.url = 'http://'+globalIP+context+'/public/getVehicleLocations?group='+getParameterByName('vg');
	var tab = getParameterByName('tn');

	//$scope.locations01 = vamoservice.getDataCall($scope.url);
	$scope.trimColon = function(textVal){
		return textVal.split(":")[0].trim();
	}


	function sessionValue(vid, gname){
		sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
		$("#testLoad").load("../public/menu");
	}
	
	function getTodayDate(date) {
     	var date = new Date(date);
    	return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
    };

    function convert_to_24h(time_str) {
		//console.log(time_str);
 		var str		=	time_str.split(' ');
 		var stradd	=	str[0].concat(":00");
 		var strAMPM	=	stradd.concat(' '+str[1]);
 		var time = strAMPM.match(/(\d+):(\d+):(\d+) (\w)/);
	    var hours = Number(time[1]);
	    var minutes = Number(time[2]);
	    var seconds = Number(time[2]);
	    var meridian = time[4].toLowerCase();
	
	    if (meridian == 'p' && hours < 12) {
	      hours = hours + 12;
	    }
	    else if (meridian == 'a' && hours == 12) {
	      hours = hours - 12;
	    }	    
	    var marktimestr	=	''+hours+':'+minutes+':'+seconds;	    
	    return marktimestr;
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

	//get the value from the ui

	function getUiValue(){
		$scope.uiDate.fromdate 		=	$('#dateFrom').val();
	  	$scope.uiDate.fromtime		=	$('#timeFrom').val();
	  	$scope.uiDate.todate		=	$('#dateTo').val();
	  	$scope.uiDate.totime 		=	$('#timeTo').val();
	  	
	}


	function urlReport(){
		var urlWebservice;
		switch  (tab){
			case  'site' : 
				urlWebservice 	= 	"http://"+globalIP+context+"/public/getSiteReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime)+"&interval="+$scope.interval+"&site=true";
				break;
			case 'trip' :
				urlWebservice 	= 	"http://"+globalIP+context+"/public/getTripReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime)+"&interval="+$scope.interval;
				break;
			case 'tripkms' :
				urlWebservice 	= 	"http://"+globalIP+context+"/public/getTripSummary?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime);
				break;
			case 'load' :           
				urlWebservice 	=	"http://"+globalIP+context+"/public/getLoadReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime);
				break;
			case 'temperature' :
				urlWebservice 	= 	"http://"+globalIP+context+"/public/getTemperatureReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime)+"&interval=-1";
			default :
				break;
			}
			return urlWebservice;

	}

	// service call for the event report

	function webServiceCall(){
		
		var url = urlReport();
		var graphList = [];		
		$scope.siteData = [];
		vamoservice.getDataCall(url).then(function(responseVal){
			try{
				$scope.siteData = responseVal;
			var entry=0,exit=0; 
			if (tab == 'site')
			angular.forEach(responseVal, function(val, key){
				if(tab == 'site'){
					if(val.state == 'SiteExit')
						exit++ 
					else if (val.state == 'SiteEntry')
						entry++
				}
			})

			if(tab == 'temperature'){
				angular.forEach(responseVal.temperature, function(graphValue, graphKey){

					graphList.push([graphValue.date, Number(graphValue.temperature)]);
						// plottinGraphs(temperature);
				})
				plottinGraphs(graphList);
			}

			$scope.siteEntry 	=	entry;
			$scope.siteExit 	=	exit;

			stopLoading();	
			} catch (err){
				console.log(' print err '+err);
				stopLoading();	
			}
			
		});
	}

	// initial method

	$scope.$watch("url", function (val) {
		vamoservice.getDataCall($scope.url).then(function(data) {
			$scope.vehicle_list = data;
			if(data.length){
				$scope.vehiname	= getParameterByName('vid');
				$scope.uiGroup 	= $scope.trimColon(getParameterByName('vg'));
				$scope.gName 	= getParameterByName('vg');
				angular.forEach(data, function(val, key){
					if($scope.gName == val.group){
						$scope.gIndex = val.rowId;
						angular.forEach(data[$scope.gIndex].vehicleLocations, function(value, keys){
							if($scope.vehiname == value.vehicleId)
							$scope.shortNam	= value.shortName;
						})
						
					}
						
				})
				
				sessionValue($scope.vehiname, $scope.gName)
			}
			$scope.fromNowTS		=	new Date();
			$scope.uiDate.fromdate 		=	getTodayDate($scope.fromNowTS);
		  	$scope.uiDate.fromtime		=	'12:00 AM';
		  	$scope.uiDate.todate		=	getTodayDate($scope.fromNowTS);
		  	$scope.uiDate.totime 		=	formatAMPM($scope.fromNowTS.getTime());
		  	webServiceCall();
		  	stopLoading();
		});	
	});


	$scope.groupSelection 	= function(groupName, groupId) {
		startLoading();
		$scope.gName 	= 	groupName;
		$scope.uiGroup 	= 	$scope.trimColon(groupName);
		$scope.gIndex	=	groupId;
		var url  		= 	'http://'+globalIP+context+'/public//getVehicleLocations?group='+groupName;
		vamoservice.getDataCall(url).then(function(response){
			stopLoading();
			$scope.vehicle_list = response;
			$scope.shortNam		= response[$scope.gIndex].vehicleLocations[0].shortName;
			$scope.vehiname		= response[$scope.gIndex].vehicleLocations[0].vehicleId;
			sessionValue($scope.vehiname, $scope.gName);
			webServiceCall();
		});
	}


	$scope.genericFunction 	= function (vehid, index){
		startLoading();
		$scope.vehiname		= vehid;
		sessionValue($scope.vehiname, $scope.gName)
		angular.forEach($scope.vehicle_list[$scope.gIndex].vehicleLocations, function(val, key){
			if(vehid == val.vehicleId)
				$scope.shortNam	= val.shortName;
		})
		getUiValue();
		webServiceCall();

	}

  	
	$scope.submitFunction 	=	function(){
		startLoading();
		getUiValue();
		webServiceCall();
	}

	$scope.exportData = function (xlsVal) {
		// console.log(data);
		var blob = new Blob([document.getElementById(xlsVal).innerHTML], {
           	type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, xlsVal+".xls");
    };

    $scope.exportDataCSV = function (data) {
		// console.log(data);
		CSV.begin('#'+data).download(data+'.csv').go();
    };

	$('#minus').click(function(){
		$('#menu').toggle(1000);
	})

	

var map;

function initialize() {
	myLatlng = new google.maps.LatLng(12.996250, 80.194750);
    var mapOptions = {
      center: myLatlng,
      zoom: 14,
      mapTypeControl: false,
      center:myLatlng,
      panControl:false,
      rotateControl:false,
      streetViewControl: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
}
if(tab == 'tripkms')
google.maps.event.addDomListener(window, 'load', initialize);

  //start of modal google map
  $('#mapmodals').on('shown.bs.modal', function () {
      google.maps.event.trigger(map, "resize");
      map.setCenter(myLatlng);
  });

  	jQuery('#mapmodals')
 	.on('shown.bs.modal',
      function(){
        google.maps.event.trigger(map,'resize',{});
        map.setCenter(myLatlng);
     });

 	var latLanPath =[];
 	var marker, markerList =[];

    $scope.drawLine = function(loc1, loc2){
    	$scope.startlatlong = new google.maps.LatLng(loc1, loc2);
		
		var flightPlanCoordinates = [$scope.startlatlong, $scope.endlatlong];
		
		$scope.flightPath = new google.maps.Polyline({
			path: flightPlanCoordinates,
			geodesic: true,
			strokeColor: "#00c4ff",
			strokeOpacity: 0.7,
			strokeWeight: 5,
			map: map,
		});
		$scope.endlatlong =$scope.startlatlong;
		latLanPath.push($scope.flightPath);
		// console.log(' value '+loc1+'-----'+loc2);
		map.setCenter($scope.startlatlong);
	}
	

	// clear the polyline function
	function clearMap(){
		$scope.endlatlong = null;
		$scope.startlatlong = null;
		for (var i=0; i<latLanPath.length; i++){
        	latLanPath[i].setMap(null);
       	}
       
       	for (var i = 0; i < markerList.length; i++) {
       		markerList[i].setMap(null);
       	};
	}

	
	//marker function in maps
	function startEndMarker(lat, lan, ind){
	var image = '';
		if(ind == 0)
			image = 'assets/imgs/startflag.png'
		else
			image = 'assets/imgs/endflag.png'
	marker = new google.maps.Marker({
		    position: new google.maps.LatLng(lat, lan),
		    map: map,
		    icon: image
		  });
	markerList.push(marker);
	}

  //end of modal google map
	$scope.getInput = function(inputValue, vehicleDetails){
		clearMap(latLanPath);
        var startDate, endDate, startTime, endTime;
        startDate	= 	$filter('date')(inputValue.startTime, 'yyyy-MM-dd');
        endDate 	= 	$filter('date')(inputValue.endTime, 'yyyy-MM-dd');
        startTime	= 	$filter('date')(inputValue.startTime, 'HH:mm:ss');
        endTime		= 	$filter('date')(inputValue.endTime, 'HH:mm:ss');
        var url 	= 	"http://"+globalIP+context+"/public/getVehicleHistory?vehicleId="+vehicleDetails.vehicleId+"&fromDate="+startDate+"&fromTime="+startTime+"&toDate="+endDate+"&toTime="+endTime;
 		vamoservice.getDataCall(url).then(function(dataGet){
 			// if(dataGet.vehicleLocations[0] || dataGet.vehicleLocations[dataGet.vehicleLocations.length-1])
 			// 	startEndMarker(val.latitude, val.longitude);
 			var len = dataGet.vehicleLocations.length;
 			angular.forEach(dataGet.vehicleLocations, function(val, key){
 				if(key == 0 || key == len-1)
 					startEndMarker(val.latitude, val.longitude, key);
 					// console.log(' key value ---'+key);
        		$scope.drawLine(val.latitude, val.longitude);

        	});
        });

       
    }



// });

// if(tab === 'temperature')
// setInterval(function () {
//       var chart = $('#temperatureChart').highcharts(), point;
//         if (chart) {
//             point = chart.series[0].points[0];
//             point.update(total);
//         }
//        var chartFuel = $('#container-fuel').highcharts(), point;
//         if (chartFuel) {
//             point = chartFuel.series[0].points[0];
//             point.update(fuelLtr);
//             if(tankSize==0)
//             	tankSize =200;
//             chartFuel.yAxis[0].update({
// 			    max: tankSize,
// 			}); 

//         }
//     }, 1000);

}]);
