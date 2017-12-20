app.controller('mainCtrl',['$scope','$http','vamoservice','$filter', '_global', function($scope, $http, vamoservice, $filter, GLOBAL){
	
  //global declaration
	$scope.uiDate 				=	{};
	$scope.uiValue	 			= 	{};
    $scope.sort                 = sortByDate('alarmTime');
    $scope.interval             = "";

    var tab = getParameterByName('tn');
       
	function getParameterByName(name) {
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	//global declartion
	$scope.locations = [];
	$scope.url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+getParameterByName('vg');
	$scope.gIndex =0;

  //$scope.locations01 = vamoservice.getDataCall($scope.url);
    $scope.trimColon = function(textVal) {

      if(textVal){
       var spltVal = textVal.split(":");
       return spltVal[0];
      }
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
	    var marktimestr	=''+hours+':'+minutes+':'+seconds;	    
	    return marktimestr;
    };

    // millesec to day, hours, min, sec
    $scope.msToTime = function(ms) 
    {
        days = Math.floor(ms / (24 * 60 * 60 * 1000));
	  	daysms = ms % (24 * 60 * 60 * 1000);
		hours = Math.floor((ms) / (60 * 60 * 1000));
		hoursms = ms % (60 * 60 * 1000);
		minutes = Math.floor((hoursms) / (60 * 1000));
		minutesms = ms % (60 * 1000);
		seconds = Math.floor((minutesms) / 1000);
		// if(days==0)
		// 	return hours +" h "+minutes+" m "+seconds+" s ";
		// else
	  return hours +":"+minutes+":"+seconds;
	}
   	
	var delayed4 = (function () {
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

   	function google_api_call_Event(tempurlEvent, index4, latEvent, lonEvent) {
   		vamoservice.getDataCall(tempurlEvent).then(function(data) {
			$scope.addressEvent[index4] = data.results[0].formatted_address;
			//console.log(' address '+$scope.addressEvent[index4])
			//var t = vamo_sysservice.geocodeToserver(latEvent,lonEvent,data.results[0].formatted_address);
		})
	};

	$scope.recursiveEvent 	= 	function(locationEvent, indexEvent)
	{
		var index4 = 0;
		angular.forEach(locationEvent, function(value ,primaryKey){
			//console.log(' primaryKey '+primaryKey)
			index4 = primaryKey;
			if(locationEvent[index4].address == undefined)
			{
				var latEvent		 =	locationEvent[index4].latitude;
			 	var lonEvent		 =	locationEvent[index4].longitude;
				var tempurlEvent =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latEvent+','+lonEvent+"&sensor=true";
				delayed4(2000, function (index4) {
				      return function () {
				        google_api_call_Event(tempurlEvent, index4, latEvent, lonEvent);
				      };
				    }(index4));
			}
		})
	}


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


    function webCall(){

     if((checkXssProtection($scope.uiDate.fromdate) == true) && ((checkXssProtection($scope.uiDate.fromtime) == true) && (checkXssProtection($scope.uiDate.todate) == true) && (checkXssProtection($scope.uiDate.totime) == true))) {

       $scope.fuelUrl = GLOBAL.DOMAIN_NAME+'/getVehicleFuelHistory4Mobile?vehicleId='+$scope.vehIds+'&fromDateUTC='+utcFormat($scope.uiDate.fromdate,convert_to_24h($scope.uiDate.fromtime))+'&toDateUTC='+utcFormat($scope.uiDate.todate,convert_to_24h($scope.uiDate.totime))+'&fuelInterval='+$scope.interval;
     //var fuelUrl2='http://188.166.244.126:9000/getVehicleFuelHistory4Mobile?userId=ULTRA&vehicleId=ULTRA-TN66M0417&fromDateUTC=1511721000000&toDateUTC=1511807399000&fuelInterval=1';
        
         console.log($scope.fuelUrl);
     }
        $scope.fuelData=[];

        $http.get($scope.fuelUrl).success(function(data){
         
         $scope.fuelData = data;
           //console.log(data);

            if(data.history4Mobile!=null){
               fuelFillData(data.history4Mobile);
            }
          stopLoading();
	    }); 
    }

	// initial method
	$scope.$watch("url", function (val) {
		vamoservice.getDataCall($scope.url).then(function(data) {
           
          //startLoading();
            $scope.selectVehiData = [];
			$scope.vehicle_group=[];
			$scope.vehicle_list = data;

			if(data.length){
				$scope.vehiname	= getParameterByName('vid');
				$scope.uiGroup 	= $scope.trimColon(getParameterByName('vg'));
				$scope.gName 	= getParameterByName('vg');
				angular.forEach(data, function(val, key){
                  //$scope.vehicle_group.push({vgName:val.group,vgId:val.rowId});
					if($scope.gName == val.group){
						$scope.gIndex = val.rowId;
 
						angular.forEach(data[$scope.gIndex].vehicleLocations, function(value, keys){

                            $scope.selectVehiData.push({label:value.shortName,id:value.vehicleId});

							if($scope.vehiname == value.vehicleId){
							    $scope.shortNam	= value.shortName;
						        $scope.vehIds   = value.vehicleId;
						    }
						})
						
					}
			    });

		  //console.log($scope.selectVehiData);
		    sessionValue($scope.vehiname, $scope.gName)
			}
			
			var dateObj 			  =  new Date();
			$scope.fromNowTS		  =	 new Date(dateObj.setDate(dateObj.getDate()));
			$scope.uiDate.fromdate    =	 getTodayDate($scope.fromNowTS);
		  	$scope.uiDate.fromtime    =	 '12:00 AM';
		  	$scope.uiDate.todate	  =	 getTodayDate($scope.fromNowTS);
		    $scope.uiDate.totime 	  =	 formatAMPM($scope.fromNowTS.getTime());
          //$scope.uiDate.totime 	  =  '11:59 PM';
		
		    startLoading();
		    webCall();
		  //stopLoading();
		});	
	});

    
   
  	$scope.groupSelection 	= function(groupName, groupId) {
		startLoading();
		$scope.gName 	= 	groupName;
		$scope.uiGroup 	= 	$scope.trimColon(groupName);
		$scope.gIndex	=	groupId;
		var url  		= 	GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+groupName;

		vamoservice.getDataCall(url).then(function(response){
		
			$scope.vehicle_list = response;
			$scope.shortNam		= response[$scope.gIndex].vehicleLocations[0].shortName;
			$scope.vehiname		= response[$scope.gIndex].vehicleLocations[0].vehicleId;
			sessionValue($scope.vehiname, $scope.gName);
            $scope.selectVehiData=[];
            //console.log(response);
            	angular.forEach(response, function(val, key){
					if($scope.gName == val.group){
					//	$scope.gIndex = val.rowId;
                        angular.forEach(response[$scope.gIndex].vehicleLocations, function(value, keys){

                            $scope.selectVehiData.push({label:value.shortName,id:value.vehicleId});

							if($scope.vehiname == value.vehicleId){
							    $scope.shortNam	= value.shortName;
							    $scope.vehIds = value.vehicleId;
					    	}
						})
				    }
				})

			getUiValue();
			webCall();
		  //stopLoading();
		});

	}


	$scope.genericFunction 	= function (vehid, index){
		startLoading();
		$scope.vehiname	= vehid;
		sessionValue($scope.vehiname, $scope.gName)
		angular.forEach($scope.vehicle_list[$scope.gIndex].vehicleLocations, function(val, key){
			if(vehid == val.vehicleId){
				$scope.shortNam	= val.shortName;
			    $scope.vehIds   = val.vehicleId;
			}
		});
		getUiValue();
		webCall();
	}

  	$scope.submitFunction 	=	function(){
	  startLoading();
	  getUiValue();
	  webCall();
	//webServiceCall();
    //stopLoading();
	}

	$scope.exportData = function (data) {
		// console.log(data);
		var blob = new Blob([document.getElementById(data).innerHTML], {
           	type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };

    $scope.exportDataCSV = function (data) {
		// console.log(data);
		CSV.begin('#'+data).download(data+'.csv').go();
    };

	$('#minus').click(function(){
		$('#menu').toggle(1000);
	});

function fuelFillData(data){


    var fueltrs    = [];
    var fuelDates  = [];

    try{

      if(data.length){
        for (var i = 0; i < data.length; i++) {
          if(data[i].fuelLitr !='0' || data[i].fuelLitr !='0.0')
          {
            fueltrs.push(parseFloat(data[i].fuelLitr));
            var dat = $filter('date')(data[i].dt, "dd/MM/yyyy HH:mm:ss");
            fuelDates.push(dat);
          }
        };
      }

    } catch (err) {
      console.log(err.message)
    }

$(function() {
	//console.log(fueltrs);
        // console.log(fuelDates);

 if(fueltrs.length){   
/*
Highcharts.createElement('link', {
   href: 'https://fonts.googleapis.com/css?family=Signika:400,700',
   rel: 'stylesheet',
   type: 'text/css'
}, null, document.getElementsByTagName('head')[0]);

// Add the background image to the container
Highcharts.wrap(Highcharts.Chart.prototype, 'getContainer', function (proceed) {
   proceed.call(this);
   this.container.style.background =
      'url(assets/imgs/sand.png)';
});


Highcharts.theme = {
   colors: ['#f45b5b', '#8085e9', '#8d4654', '#7798BF', '#aaeeee',
      '#ff0066', '#eeaaee', '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],
   chart: {
      backgroundColor: null,
      style: {
         fontFamily: 'Signika, serif'
      }
   },
   title: {
      style: {
         color: 'black',
         fontSize: '16px',
         fontWeight: 'bold'
      }
   },
   subtitle: {
      style: {
         color: 'black'
      }
   },
   tooltip: {
      borderWidth: 0
   },
   legend: {
      itemStyle: {
         fontWeight: 'bold',
         fontSize: '13px'
      }
   },
   xAxis: {
      labels: {
         style: {
            color: '#6e6e70'
         }
      }
   },
   yAxis: {
      labels: {
         style: {
            color: '#6e6e70'
         }
      }
   },
   plotOptions: {
      series: {
         shadow: true
      },
      candlestick: {
         lineColor: '#C0C0C8'
      },
      map: {
         shadow: false
      }
   },

   // Highstock specific
   navigator: {
      xAxis: {
         gridLineColor: '#D0D0D8'
      }
   },
   rangeSelector: {
      buttonTheme: {
         fill: 'white',
         stroke: '#C0C0C8',
         'stroke-width': 1,
         states: {
            select: {
               fill: '#D0D0D8'
            }
         }
      }
   },
   scrollbar: {
      trackBorderColor: '#C0C0C8'
   },

   // General
   background2: '#E0E0E8'

};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);*/

Highcharts.chart('container', {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: 'Fuel Report'
        },
        credits: {
          enabled: false
        },
       /* subtitle: {
            text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
        },*/
        xAxis: {
            categories: fuelDates
        },
        yAxis: {
            title: {
                text: 'Fuel (Ltrs)'
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
            type: 'area',
            name: 'Fuel Level',
            data:  fueltrs
        }]
    });

}


  });

}

}]);








