app.controller('mainCtrl',['$scope','vamoservice','$filter','_global', function($scope, vamoservice, $filter, GLOBAL){

 // trip summary , 	site report, trip report , rfid Report, multiple sites
 // global declaration

    $scope.g_Url 	=	GLOBAL.DOMAIN_NAME;

	$scope.locations = [];
	$scope.url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+getParameterByName('vg');

	$scope.addressFuel 			= 	[];
	$scope.uiDate 				=	{};
	
  	$scope.siteEntry 			=	0;
	$scope.siteExit 			=	0;
	
    $scope._site1               =  {}; 
    $scope._site2               =  {};
	$scope._site1.siteName      =  '';
    $scope._site2.siteName      =  '';
    $scope.mSiteError           =   0;

    $scope.msToTime		=	function(ms) {
		if (ms == undefined || ms == null || ms == '')
			return' ';
		else{
			days = Math.floor(ms / (24*60*60*1000));
		    daysms=ms % (24*60*60*1000);
		    hours = Math.floor((ms)/(60*60*1000));
		    hoursms=ms % (60*60*1000);
		    minutes = Math.floor((hoursms)/(60*1000));
		    minutesms=ms % (60*1000);
		    sec = Math.floor((minutesms)/(1000));
		    return hours+":"+minutes+":"+sec;
		}
    }


  $scope.convert_to_24hrs = function(time_str) {

   if(time_str != undefined){
      
      var str   = time_str.split(' ');
      var stradd  = str[0].concat(":00");
      var strAMPM = stradd.concat(' '+str[1]);
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

      hours   = hours < 10 ? '0'+hours : hours;
      minutes = minutes < 10 ? '0'+minutes : minutes;  
      seconds = seconds < 10 ? '0'+seconds : seconds; 

      var marktimestr = ''+hours+':'+minutes+':'+seconds;   
    }

  return marktimestr;
  };

    //chants for temperature

	// $(function () {
function plottinGraphs(valueGraph, timeData){
   

    $('#temperatureChart').highcharts({
    	                                             // This is for all plots, change Date axis to local timezone
   		title: {
            text: ' '
        },

        xAxis: {
			
            type: 'datetime',
            // labels: {
            //     overflow: 'justify'
            // },
            // startOnTick: true,
            // showFirstLabel: true,
            // endOnTick: true,
            // showLastLabel: true,
            categories: timeData,
            // tickInterval: 10,
            // labels: {
            //     formatter: function() {
            //         return this.value.toString().substring(0, 6);
            //     },
            //     rotation: 0.1,
            //     align: 'left',
            //     step: 10,
            //     enabled: true
            // },
            style: {
                fontSize: '8px'
            }
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

	var tab = getParameterByName('tn');
	$scope.sort = sortByDate('date');
	
	/*
		THIS TWO VARIABLES FOR MUITLE SITE AND SITE TRIP REPORT ONLY
	*/
	$scope.caption 		= 'Multiple Site Report';
	$scope.hideShow 	= false;

    if(tab == 'tripkms' || tab == 'site' || tab == 'multiSite'){
    	$scope.sort 	= 	sortByDate('startTime')        						
    }
	else if (tab == 'rfid'){
		$scope.sort 	= 	sortByDate('fromTime')
	}
	else if (tab == 'rfidNew'){
		$scope.sort 	= 	sortByDate('dateTime')
	}
    else if(tab == 'alarm'){
    	$scope.sort 	= 	sortByDate('alarmTime')
    }
    else if(tab == 'tripSite'){
    	$scope.caption 	= 'Site Trip';
    	$scope.hideShow = true;	
	} else if(tab == 'dailyFuel') {
		$scope.caption 	= 'Daily Fuel Burn';
    	$scope.sort 	= 	sortByDate('time');
	}
	

	//$scope.locations01 = vamoservice.getDataCall($scope.url);
	$scope.trimColon = function(textVal){
		return (textVal != undefined) ? textVal.split(":")[0].trim() : textVal;
	}


	function sessionValue(vid, gname){
		sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
		$("#testLoad").load("../public/menu");
	}
	
	// function getTodayDate(date) {
 //     	var date = new Date(date);
 //    	return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
 //    };

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

	
	function getSite(){
		var data;
		var urlSite = GLOBAL.DOMAIN_NAME+'/viewSite';  

        console.log('urlSite called....');

		$.ajax({
	    	url:urlSite, 
	    	async: false,   
	    	success:function(response) {
	      		data = response;
	      	}
		})

		console.log(data);

	 return data;
	}
	
	function urlReport(){
		var urlWebservice;
		switch  (tab){
			case  'site' : 
				urlWebservice 	= 	$scope.g_Url+"/getSiteReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime)+"&interval="+$scope.interval+"&site=true";
				break;
			case 'trip' :
				urlWebservice 	= 	$scope.g_Url+"/getTripReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime)+"&interval="+$scope.interval;
				break;
			case 'tripkms' :
				urlWebservice 	= $scope.g_Url+"/getTripSummary?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime);
				break;
			case 'load' :           
				urlWebservice 	=	$scope.g_Url+"/getLoadReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime);
				break;
			case 'temperature': case 'temperatureDev':
				urlWebservice 	= 	$scope.g_Url+"/getTemperatureReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime)+"&interval=-1";
				if (tab == 'temperatureDev')
					$scope.tempCation 	= "Temperature Deviation";
				else if (tab == 'temperature')
					$scope.tempCation 	= "Temperature Report";
				break;
			case 'alarm' :
				urlWebservice   =  $scope.g_Url+"/getAlarmReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime);
				break;
			case 'rfid' :
				urlWebservice   =  $scope.g_Url+"/getRfidReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime);
				break;
			case 'rfidNew' :
				urlWebservice   =  $scope.g_Url+"/getRfidReportNew?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime);
				break;	
			case 'multiSite' :
				try{
					//startLoading();
					urlWebservice   =  $scope.g_Url+"/getSiteSummary?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime)+"&stopTime="+$scope.interval+"&language=en"+"&site1="+$scope._site1.siteName+"&site2="+$scope._site2.siteName;
				}
				catch (e){
					stopLoading();
					return
				}
				break;
			case 'tripSite':
				urlWebservice   =  $scope.g_Url+"/getSiteTripReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime)+"&language=en"+"&site1="+$scope._site1.siteName+"&site2="+$scope._site2.siteName;				
				break;
			case 'dailyFuel':
				urlWebservice   =  $scope.g_Url+"/getFuelReportDaily?vehicleId="+$scope.vehiname;
				// urlWebservice   =  $scope.g_Url+"/getFuelReportDaily?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime)+"&language=en"+"&site1="+$scope._site1.siteName+"&site2="+$scope._site2.siteName;				
				break;
			default :
				break;
			}

	return urlWebservice;
	}

	var delayed6 = (function () {
  		var queue = [];

	  	function processQueue() {
		    if (queue.length > 0) {
		      setTimeout(function () {
		        queue.shift().cb();
		        processQueue();
		      }, queue[0].delay);
		    }
	  	}

	  	return function delayed(delay, cb) {
	    	queue.push({ delay: delay, cb: cb });

	    	if (queue.length === 1) {
	      	processQueue();
	    	}
	  	};
	}());

	function google_api_call(tempurlFuel, index6, latFuel, lonFuel) {
		vamoservice.getDataCall(tempurlFuel).then(function(data){
			$scope.addressFuel[index6] = data.results[0].formatted_address;
		})
	};

	// for address in alarm report in address resolving
	$scope.recursive 	= 	function(locationFuel, indexFuel)
	{
		var index6 = 0;
		angular.forEach(locationFuel, function(value ,primaryKey){
			//console.log(' primaryKey '+primaryKey)
			index6 = primaryKey;
			if(locationFuel[index6].address == undefined)
			{
				var latFuel		 =	locationFuel[index6].lat;
			 	var lonFuel		 =	locationFuel[index6].lng;
				var tempurlFuel =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latFuel+','+lonFuel+"&sensor=true";
				delayed6(2000, function (index6) {
				      return function () {
				        google_api_call(tempurlFuel, index6, latFuel, lonFuel);
				      };
				    }(index6));
			}
		})
	}

	//get the value
	$scope.rfidSplit 	= 	function(value, index){
		var _tageValue = value.split(';');
		return (index == 'one') ? _tageValue[0] :  _tageValue[1];
	}

	//get the value
	$scope.rfidSplitNew 	= 	function(value){
		var spValue = value.split(';');
	  return spValue;
	}

	function rfidNewFunc(data){
		
		$scope.rfiData=[];
        var dataVar=[];
        //console.log(data);

        for(var i=0;i<data.gd.length;i++) {
           //console.log(data.gd[i]);
            var tagVal=$scope.rfidSplitNew(data.gd[i].tagDetail);
            dataVar.push({tagNo:tagVal[0],tagNam:tagVal[1],dateTime:tagVal[2],address:data.gd[i].address,lat:data.gd[i].lat,lng:data.gd[i].lng});
        }

        $scope.rfiData=dataVar;
          //console.log($scope.rfiData);
	}


	$scope.histRedirect 	=	function(obj, ind){
		var url_Hist 		= '../public/track?maps=replay&vehicleId='+$scope.vehiname+'&gid='+$scope.gName;
		var ind_list 		= (ind % 2) ? [ind-1, ind] : [ind, ind+1];
		window.open(url_Hist+'&fTime='+obj[ind_list[0]].startTime+'&tTime='+obj[ind_list[1]].endTime, '_blank');
	}


	// service call for the event report

	function webServiceCall(){
		
		var url = urlReport();
		var graphList = [];
		var graphTime = [];		
		$scope.siteData = [];
		var urlUTC;
			urlUTC = url+'&fromDateUTC='+utcFormat($scope.uiDate.fromdate,convert_to_24h($scope.uiDate.fromtime))+'&toDateUTC='+utcFormat($scope.uiDate.todate,convert_to_24h($scope.uiDate.totime));
		if ((url != undefined) && (checkXssProtection($scope.uiDate.fromdate) == true) && (checkXssProtection($scope.uiDate.fromtime) == true) && (checkXssProtection($scope.uiDate.todate) == true) && (checkXssProtection($scope.uiDate.totime) == true)){
			vamoservice.getDataCall(urlUTC).then(function(responseVal){

              //console.log(responseVal);

				try {

                    if(tab == 'rfidNew'){
                    	rfidNewFunc(responseVal);
                    }  

					if(tab == 'alarm')
						try{
							$scope.recursive(responseVal.alarmList,0);
						} catch (er){
							console.log(' address Solving '+er);
						}
				    $scope.siteData = responseVal;
				    $scope.mSiteError=1;
				    stopLoading();

					/*
						FOR TEMPORARY LOAD
					*/

					if(tab == 'load')
	                {
	                    $scope.siteData ={};
	                    $scope.siteData.load =[];
	                    var spLoading ;
	                    try
	                    {


	                            angular.forEach(responseVal.load, function(value, keyLoad){
	                            	spLoading = splitColon(value.load);
	                                $scope.siteData.load.push({'date':value.date, 'lat':value.lat, 'lng': value.lng, 'Axle1': spLoading[0], 'Axle2': spLoading[1], 'Axle3': spLoading[2], 'Axle4': spLoading[3], 'Axle5': spLoading[4], 'Axle6': spLoading[5], 'Axle7': spLoading[6], 'Axle8': spLoading[7],  'LoadTruck': spLoading[8], 'LoadTrailer': spLoading[9], 'TotalLoadTruck': spLoading[10], 'TotalLoadTrailer': spLoading[11]})
	                            })

	                    }
	                    catch(err)
	                    {

	                    }
	                }

				
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

					if(tab == 'temperature' || tab == 'temperatureDev'){
						$scope.siteData = [];
						angular.forEach(responseVal.temperature, function(graphValue, graphKey){
						//var time = moment(graphValue.date).format("DD-MM-YYYY h:mm:ss");
							if(graphValue.isdeviation == 'No' && tab == 'temperature'){
								
								graphList.push(Number(graphValue.temperature));
								graphTime.push(moment(graphValue.date).format("DD-MM-YYYY h:mm:ss"));
								$scope.siteData.push(graphValue);
								// plottinGraphs(temperature);
							} else if(graphValue.isdeviation == 'Yes' && tab == 'temperatureDev'){
								
								graphList.push(Number(graphValue.temperature));
								graphTime.push(moment(graphValue.date).format("DD-MM-YYYY h:mm:ss"));
								$scope.siteData.push(graphValue);
							}	
						})
						plottinGraphs(graphList, graphTime);
					} 
					// else if(tab == 'temperatureDev'){
					// 	$scope.siteData = [];

					// }

					$scope.siteEntry 	=	entry;
					$scope.siteExit 	=	exit;

					stopLoading();	
				} catch (err){
					console.log(' print err '+err);
					stopLoading();	
				}
				
			});
		} else
			stopLoading();
	}

	// initial method

	$scope.$watch("url", function (val) {
	  startLoading();
		vamoservice.getDataCall($scope.url).then(function(data) {
			$scope.vehicle_list = data;
			var siteNames 	= 	[];
			if(data.length){
				$scope.vehiname	= getParameterByName('vid');
				$scope.uiGroup 	= $scope.trimColon(getParameterByName('vg'));
				$scope.gName 	= getParameterByName('vg');
				angular.forEach(data, function(val, key){
					if($scope.gName == val.group){
						$scope.gIndex = val.rowId;
						angular.forEach(data[$scope.gIndex].vehicleLocations, function(value, keys){
							if($scope.vehiname == value.vehicleId){
								$scope.shortNam	= value.shortName;
								if(tab == 'multiSite' || tab == 'tripSite')
								{
									$scope.orgId 	= value.orgId;
									var siteValue = getSite();
									angular.forEach(JSON.parse(siteValue).siteParent, function(val, key){
						      			if(val.orgId == $scope.orgId){
						      				if(val.site.length > 0){
						      					// if(tab == 'multiSite')
						      						siteNames.push({'siteName':'All'});
						      					angular.forEach(val.site, function(siteName, keys){
							      					siteNames.push(siteName);

							      				})
							      				
							      				$scope.siteName = siteNames;
							      				$scope._site1 	=	siteNames[0];
							      				$scope._site2 	=	siteNames[0];
						      				}
						      			}
						      				
						      		})
								}
							}
 						})
						
					}
						
				})
				
				sessionValue($scope.vehiname, $scope.gName)
			}
			$scope.interval				= 	10;
			$scope.fromNowTS			=	new Date();
			$scope.uiDate.fromdate 		=	getTodayDate($scope.fromNowTS);
		  	$scope.uiDate.fromtime		=	'12:00 AM';
		  	$scope.uiDate.todate		=	getTodayDate($scope.fromNowTS);
		  	$scope.uiDate.totime 		=	formatAMPM($scope.fromNowTS.getTime());
		  	if(tab == 'tripSite' ){
		  		$scope.fromNowTS1			=	new Date().getTime() - 86400000;
		  		$scope.uiDate.fromdate 		=	getTodayDate($scope.fromNowTS1);
			  //$scope.uiDate.fromdate 	    =	getTodayDate($scope.fromNowTS.setDate($scope.fromNowTS.getDate()-7));
			  	$scope.uiDate.fromtime		=	'12:00 AM';
			  	$scope.uiDate.todate		=	getTodayDate($scope.fromNowTS1);
			  	$scope.uiDate.totime 		=	'11:59 PM';
		  	}else if(tab == 'dailyFuel'){
		  		$scope.fromNowTS1			=	new Date().getTime() - 86400000;
		  	  //$scope.uiDate.fromdate 	    =	getTodayDate($scope.fromNowTS);
				$scope.uiDate.fromdate 		=	getTodayDate($scope.fromNowTS.setDate($scope.fromNowTS.getDate()-7));
			  	$scope.uiDate.fromtime		=	'12:00 AM';
			  	$scope.uiDate.todate		=	getTodayDate($scope.fromNowTS1);
			  	$scope.uiDate.totime 		=	'11:59 PM';
		  		// $ new Date().getTime() - 86400000
		  		// console.log(' dailyFuel condition ');
		  		// console.log(' dailyFuel condition '+ $scope.uiDate.fromdate);
		  	}
		  	webServiceCall();
		  	//stopLoading();
		});	
	});


	$scope.groupSelection 	= function(groupName, groupId) {
		startLoading();
		$scope.mSiteError  =   0;
		$scope.gName 	= 	groupName;
		$scope.uiGroup 	= 	$scope.trimColon(groupName);
		$scope.gIndex	=	groupId;
		var url  		= 	$scope.g_Url+"/getVehicleLocations?group="+groupName;
		vamoservice.getDataCall(url).then(function(response){
			//stopLoading();
			$scope.vehicle_list = response;
			$scope.shortNam		= response[$scope.gIndex].vehicleLocations[0].shortName;
			$scope.vehiname		= response[$scope.gIndex].vehicleLocations[0].vehicleId;
			$scope.orgId		= response[$scope.gIndex].vehicleLocations[0].orgId;
			sessionValue($scope.vehiname, $scope.gName);
			webServiceCall();
		});
	}


	$scope.genericFunction 	= function (vehid, index){
		startLoading();
		$scope.mSiteError   =   0;
		$scope.vehiname		= vehid;
           
        $scope.vehTripName = vehid;

		sessionValue($scope.vehiname, $scope.gName)
		angular.forEach($scope.vehicle_list[$scope.gIndex].vehicleLocations, function(val, key){
			if(vehid == val.vehicleId)
				$scope.shortNam	= val.shortName;
				$scope.orgId = val.orgId;
		})
		getUiValue();
		webServiceCall();

	}

	$scope.submitFunction 	=	function(){
		startLoading();
		$scope.mSiteError   =   0;
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

	

var map, myLatlng;

function initialize(latLngVal) {

	console.log('init..');
	
	myLatlng = latLngVal;
    var mapOptions = {
      zoom: 14,	
      zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP },
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);

    $('#mapmodals').on('shown.bs.modal', function () {
      google.maps.event.trigger(map, "resize");
      map.setCenter(myLatlng);
  });
}


//if(tab == 'tripkms')
	//initialize();
//google.maps.event.addDomListener(window, 'load', initialize);

  //start of modal google map
  

  // 	jQuery('#mapmodals')
 	// .on('shown.bs.modal',
  //     function(){
  //       google.maps.event.trigger(map,'resize',{});
  //       map.setCenter(myLatlng);
  //    });

 	var latLanPath =[];
 	var marker, markerList =[];

    $scope.drawLine = function(loc1, loc2){
    	$scope.startlatlong = new google.maps.LatLng(loc1, loc2);

    	if(map==null){
		  initialize($scope.startlatlong);
	    }
		
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
        var url 	= 	$scope.g_Url+"/getVehicleHistory?vehicleId="+vehicleDetails.vehicleId+"&fromDate="+startDate+"&fromTime="+startTime+"&toDate="+endDate+"&toTime="+endTime+'&fromDateUTC='+utcFormat(startDate,startTime)+'&toDateUTC='+utcFormat(endDate,endTime);
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

  	$(function () {
                $('#dateFrom, #dateTo').datetimepicker({
                    format:'YYYY-MM-DD',
                    useCurrent:true,
                    pickTime: false
                });
                $('#timeFrom').datetimepicker({
                    pickDate: false,
                    
                });
                $('#timeTo').datetimepicker({
                    pickDate: false,
                    
                });
        });      

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