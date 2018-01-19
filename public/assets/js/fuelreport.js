// var app = angular.module('fuelapp',['ui.bootstrap']);
app.controller('mainFuel', function($scope, $http, $filter){
 // console.log('  inside the js file  ')

	$scope.url 				= 	'http://'+globalIP+context+'/public//getVehicleLocations';
	$scope.gIndex 			=	 0;
	$scope.alertData 		=	'time';
 // $scope.hrs    = 1;
 // $scope.kms 	  = 10;

	// function getParameterByName(name) {
    //   name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	//   var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	//   results = regex.exec(location.search);
	// return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	// }

	var tabValue  =	 getParameterByName('tab');

 // console.log(' value in tabValue '+$scope.tabValue);

	$scope.sort = {       
        sortingOrder : 'date',
        reverse : true
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

	$scope.trimColon = function(textVal){
		return textVal.split(":")[0].trim();
	}

	// $scope.fuelCon 		= 	[];
	// $scope.trip 		= 	[];
	// $scope.duration 	= 	[];
	// $scope.timeList		=	[];

	function sessionValue(vid, gname){
		sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
		$("#testLoad").load("../public/menu");
	}


	function graphList(list)
	{	
		$scope.fuelCon 		= 	[];
		$scope.trip 		= 	[];
		$scope.duration 	= 	[];
		$scope.timeList		=	[];
		if(list[0])
		{
			if(undefined==list[0].timeHistory)
   				list[0].timeHistory =[];
   			if(undefined==list[0].distanceHistory)
   				list[0].distanceHistory=[];
   			var getLength = Math.max(list[0].distanceHistory.length, list[0].timeHistory.length)
			for (var i = 0; i < getLength; i++) {
			//console.log(i)
				if(list[0].distanceHistory.length>i)
				{
					$scope.fuelCon.push(list[0].distanceHistory[i].fuelConsume)
					$scope.trip.push(list[0].distanceHistory[i].tripDistance)
					//console.log('push'+i)	
				}
				if(list[0].timeHistory.length>i)
				{
					$scope.duration.push(list[0].timeHistory[i].fuelConsume)
					$scope.timeList.push($scope.msToTime(list[0].timeHistory[i].duration))
				}
				
			};
		}
		
	}


	//check undefined
	function isUndefined(isUndef){
		try{
			if(isUndef)
				return false;
		} catch (err){
			return true;
		}
	}


	function graphData(val){
		try{
			graphList(val);
		}
		catch (err){
			console.log('No Data'+err)
		}
		
	$(function () {
   
        $('#container1').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: ''
            },
          
             xAxis: {
            categories: $scope.timeList
        		},
            
            yAxis: {
                title: {
                    text: 'Fuel'
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
                name: 'Fuel Consume',
                data: $scope.duration
            }]
        });

});
	 $('#container').highcharts({
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: ''
        },
       
       xAxis: {
                labels:
                {
                    enabled: false
                }
            },
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value} ltr',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: 'Fuel',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: 'Distance',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} kms',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 120,
            verticalAlign: 'top',
            y: 100,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [{
            name: 'Distance',
            type: 'column',
            yAxis: 1,
            data: $scope.trip,
            tooltip: {
                valueSuffix: ' kms'
            }

        }, {
            name: 'Fuel',
            type: 'spline',
            data: $scope.fuelCon,
            tooltip: {
                valueSuffix: 'ltr'
            }
        }]
    });
}


	function convert_to_24h(time_str) {
		
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
	$scope.getTodayDate  =	function(date) {
     	var date = new Date(date);
    	return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
    };
    $scope.interval 	=  1;
    
	$scope.$watch("url", function (val) {
   		$http.get(val).success(function(data){
			$scope.locations 	= 	data;

		 // console.log(data[0].vehicleLocations[0].vehicleId);

			if(data.length){
				$scope.vehiname		=	data[$scope.gIndex].vehicleLocations[0].vehicleId;
				$scope.shortName    =   data[$scope.gIndex].vehicleLocations[0].shortName;
				$scope.shortNam     =   $scope.shortName;
				$scope.gName 		= 	data[$scope.gIndex].group;
				$scope.groupName 	=	$scope.trimColon(data[$scope.gIndex].group);
				sessionValue($scope.vehiname, $scope.gName)
			    
			    angular.forEach(data, function(value, key) {	

		            if(value.totalVehicles){
				  		$scope.data1  =	data[key];
				    }
			    });		
			}

			var fromNow 		= 	new Date();
		 // var toNow 		    = 	new Date(data.toDateTime.replace('IST',''));
			$scope.fromNowTS	=	fromNow.getTime();
		 // $scope.toNowTS	    =	fromNow.setTime(0,0,0,0);	
			$scope.fromtime		=	"12:00 AM";
   			$scope.totime		=	formatAMPM($scope.fromNowTS);
			$scope.fromdate		=	$scope.getTodayDate($scope.fromNowTS);
			$scope.todate		=	$scope.getTodayDate($scope.fromNowTS);
			distanceTime();		
			
	    }).error(function(){ /*alert('error'); */});
	});

	//function for group selection
	$scope.groupSelection 	=	function(groupname, gid) {

		$scope.gName 		= 	groupname;
		$scope.gIndex		=	gid;
		var urlGroup 		= 	'http://'+globalIP+context+'/public//getVehicleLocations?group='+groupname;
		$http.get(urlGroup).success(function(data){
			$scope.locations 	= 	data;
			$scope.vehiname		=	data[$scope.gIndex].vehicleLocations[0].vehicleId;
			$scope.shortName    =   data[$scope.gIndex].vehicleLocations[0].shortName;
			$scope.shortNam     =   $scope.shortName;
			$scope.gName 		= 	data[$scope.gIndex].group;
			$scope.groupName 	=	$scope.trimColon(data[$scope.gIndex].group);
			sessionValue($scope.vehiname, $scope.gName)
		});
	}

	//page loaded function
	function distanceTime()	{	
		$("#fill").hide();
    	$("#eventReport").show();
    	var distanceTimeUrl = ' ';
		document.getElementById ("stop").checked = true;
    	document.getElementById ("idlecheck").checked = true;
    	var stoppage 		= 	document.getElementById ("stop").checked;
    	var idleEvent 		= 	document.getElementById ("idlecheck").checked;
    	var kms 			=   document.getElementsByClassName("kms")[0].value;
    	var hrs 			=   document.getElementsByClassName("hrs")[0].value;
		
		if(tabValue == "fuelfill"){
			$('#fuelValue').val('fill');
			distanceTimeUrl 	= 	'http://'+globalIP+context+'/public/getFuelDropFillReport?vehicleId='+$scope.vehiname+'&interval='+$scope.interval+'&fromDate='+$scope.fromdate+'&fromTime='+convert_to_24h($scope.fromtime)+'&toDate='+$scope.todate+'&toTime='+convert_to_24h($scope.totime)+'&fuelDrop=false'+'&fuelFill=true'+'&fromDateUTC='+utcFormat($scope.fromdate,convert_to_24h($scope.fromtime))+'&toDateUTC='+utcFormat($scope.todate,convert_to_24h($scope.totime));
			serviceCall(distanceTimeUrl);
			$("#eventReport").hide(1000);
    		$("#fill").show(1000);
		} else{
			distanceTimeUrl     =   'http://'+globalIP+context+'/public/getDistanceTimeFuelReport?vehicleId='+$scope.vehiname+'&interval='+$scope.interval+'&fromDate='+$scope.fromdate+'&fromTime='+convert_to_24h($scope.fromtime)+'&toDate='+$scope.todate+'&toTime='+convert_to_24h($scope.totime)+'&distanceEnable='+stoppage+'&timeEnable='+idleEvent+'&intervalTime='+hrs+'&distance='+kms+'&fromDateUTC='+utcFormat($scope.fromdate,convert_to_24h($scope.fromtime))+'&toDateUTC='+utcFormat($scope.todate,convert_to_24h($scope.totime));
			serviceCall(distanceTimeUrl);
		}
	}
	
	// service call
	function serviceCall(url){
		// $('#perloader').show();
		// $('#preloader02').show();

		$http.get(url).success(function(data){
			// $('#status02').fadeOut(); 
			// $('#preloader02').delay(350).fadeOut('slow');
			if(data.length>0){
				$scope.fuelTotal 	= 	data;
				graphData(data);
			}else{
				graphData(null);
				$scope.fuelTotal	= 	[];
			}
			stopLoading();
		});
	}

  //button click event
	$scope.plotHist 		= function(){
		$scope.getValue('button');
	}

	$scope.genericFunction  = function(single, index, shortname){
		$scope.shortName 	= 	shortname;
		$scope.shortNam 	= 	shortname;
		$scope.vehiname 	= 	single;
		sessionValue($scope.vehiname, $scope.gName);
		$scope.getValue('vehicle');
	}
	
	$scope.getValue 	= function(data){
		// $('#status02').show(); 
		// $('#preloader02').show(); 
		startLoading();
		switch(data)
		{
			case 'vehicle':
				console.log('vehicle');
				$scope.seperate();
				break;
			case 'button':
				console.log('button');
				$scope.seperate();
				break;
			default:
				break;

		}
	}
	
	$scope.exportData = function (data) {
    var blob = new Blob([document.getElementById(data).innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };
    $scope.exportDataCSV = function (data) {
    	CSV.begin('#'+data).download(data+'.csv').go();
    };

	$scope.seperate 	= function(){
		var stoppage 		= 	document.getElementById ("stop").checked;
    	var idleEvent 		= 	document.getElementById ("idlecheck").checked;
    	var fill 			= 	document.getElementById ("fillfuel").checked;
    	var drop 			= 	document.getElementById ("drop").checked;
    	var fromd 			= 	document.getElementById ("dateFrom").value;
    	var fromt 			= 	document.getElementById ("timeFrom").value
    	var tod 			=   document.getElementById ("dateTo").value;
    	var tot 			=   document.getElementById ("timeTo").value;
    	$scope.report 		=	document.getElementById	("fuelValue").value

    	var kms 			=   document.getElementsByClassName("kms")[0].value;
    	var hrs 			=   document.getElementsByClassName("hrs")[0].value;
    	if((checkXssProtection(fromd) == true) && (checkXssProtection(fromt) == true) && (checkXssProtection(tod) == true) && (checkXssProtection(tot) == true)){
	    	if($scope.report=="distance")
	    	{
	    		var distanceUrl 	= 	'http://'+globalIP+context+'/public/getDistanceTimeFuelReport?vehicleId='+$scope.vehiname+'&interval='+$scope.interval+'&fromDate='+fromd+'&fromTime='+convert_to_24h(fromt)+'&toDate='+tod+'&toTime='+convert_to_24h(tot)+'&distanceEnable='+stoppage+'&timeEnable='+idleEvent+'&intervalTime='+hrs+'&distance='+kms+'&fromDateUTC='+utcFormat(fromd,convert_to_24h(fromt))+'&toDateUTC='+utcFormat(tod,convert_to_24h(tot));
	    		serviceCall(distanceUrl);
	    		$("#fill").hide(1000);
	    		$("#eventReport").show(1000);
	    		
	    	}
	    	else if($scope.report == "fill" || $scope.report == "drops")
	    	{
               var repFill,repDrop;

                if($scope.report=="fill"){
                    repFill=true;
                    repDrop=false;  
                }
                else if($scope.report=="drops"){
                    repFill=false;
                    repDrop=true; 
                }

	    		var distanceUrl = 'http://'+globalIP+context+'/public/getFuelDropFillReport?vehicleId='+$scope.vehiname+'&interval='+$scope.interval+'&fromDate='+fromd+'&fromTime='+convert_to_24h(fromt)+'&toDate='+tod+'&toTime='+convert_to_24h(tot)+'&fuelDrop='+repDrop+'&fuelFill='+repFill+'&fromDateUTC='+utcFormat(fromd,convert_to_24h(fromt))+'&toDateUTC='+utcFormat(tod,convert_to_24h(tot));
	    		serviceCall(distanceUrl);
	    		$("#eventReport").hide(1000);
	    		$("#fill").show(1000);
	    	}
	    } else
	    	stopLoading();
	}
	$(window).load(function() {
		$('#status').hide(); 
		$('#preloader').hide('slow');
		$('body').delay(350).css({'overflow':'visible'});
	});

	$('#minus').click(function(){
		$('#chart1').toggle(1000);
	})
	$('#minus1').click(function(){
		$('#chart2').toggle(1000);
	})
});

