app.controller('mainCtrl',['$scope', '$http','vamoservice', '_global', function($scope, $http, vamoservice, GLOBAL){
	//console.log(' inside the controller ')
	//Global Variable
	$scope.url 			= 	GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
	$scope.gIndex		=	0;
	$scope.fromTime 	=   '12:00 AM';
	$scope.totime 	    =   '11:59 PM';
	var arrangeMonthList=[];
	var monthNames 		= ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	// $scope.daily 		= false;
	var status 			= 	'';
	
	$scope.trimColon = function(textVal){

		if(textVal != null || textVal != undefined){
		return textVal.split(":")[0].trim();
    	}
	}

	$scope.sort = {       
                sortingOrder : 'id',
                reverse : false
            };

	//loading start function
	// var startLoading		= function () {
	// 	$('#status').show(); 
	// 	$('#preloader').show();
	// };

	//loading stop function
	// var stopLoading		= function () {
	// 	$('#status').fadeOut(); 
	// 	$('#preloader').delay(350).fadeOut('slow');
	// 	$('body').delay(350).css({'overflow':'visible'});
	// };

	// function getParameterByName(name) {
 //    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	//     var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	//         results = regex.exec(location.search);
	//     return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	// }


	//initial functions

	$scope.$watch("url", function (val) {
		startLoading();
		vamoservice.getDataCall(val).then(function(data) {
			$scope.vehicleId 	  	= data[$scope.gIndex].vehicleLocations[0].vehicleId;
			$scope.groupName	  	= data[$scope.gIndex].group;
			sessionValue($scope.vehicleId, $scope.groupName);
			$scope.vehicleDetails 	=  data;
			
			$scope.initMethod();
			// $scope.locations      =  data[$scope.gIndex].vehicleLocations;
		});	
	});


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

	var performanceView 	=	getParameterByName('tab');
	if(performanceView == 'daily') {
		$scope.daily = true;
		$scope.monthly = false;
		status = 'daily';
	} else {
		$scope.daily = false;
		$scope.monthly = true;
		status = 'monthly';
	}

	function sessionValue(vid, gname){
		sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
		$("#testLoad").load("../public/menu");
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


	function arrangeMonth(){

		var monthIndex=0;
		arrangeMonthList =[];
		monthIndex=monthNames.indexOf($scope.month);
		for (var j=0; j<12; j++) 
		{
			arrangeMonthList.push(monthNames[monthIndex]);
			monthIndex--; 
			if (monthIndex < 0) 
			{
				monthIndex = 11;
			}
		}
		return arrangeMonthList;
	};

	var OverallDriverPerformance = function(url)
	{
		var totalsuddenBreak=[];
		var SuddenAcc=[];
		var OverSpeed=[];
		var sparkAlarm=[];
		var vehiclename=[];
		var kiloMeter=[];
		var i=0;
		//console.log('inside the url')
		
		$http.get(url).success(function(data)
		{	
			// console.log(arrangeMonth());
			// if($scope.groupVeh == false)
				
			$scope.tableValue=[];
			dataTableList=[];
			for(i; i<data.length; i++)
			{
				if(!data[i].error || data[i].error == null){
					if($scope.groupVeh == true)
					vehiclename.push(data[i].shortName);
					else if(status == 'daily')
						vehiclename.push(data[i].shortName);
					else{
						arrangeMonth();
						vehiclename.push(arrangeMonthList[i]);
					}
					totalsuddenBreak.push(data[i].weightedBreakAnalysis);
					SuddenAcc.push(data[i].weightedAccelAnalysis);
					OverSpeed.push(data[i].weightedSpeedAnalysis);
					sparkAlarm.push(data[i].weightedShockAlarmAnalysis);
					kiloMeter.push(data[i].distance);
					dataTableList.push({'month':data[i].shortName,'data': data[i]});	
				}	
				
				
			}
			$scope.tableValue=dataTableList;
			stopLoading();
			//group value charts
			$('#container').highcharts({
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
					name: 'Brake Analysis',
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



	$scope.nullValue = function(value_Data){
		//console.log(' 1 ');
		if(value_Data.month==undefined)
		return true;
	}



	function getTodayDate(date) {
     	var date = new Date(date);
    	return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
    };

	function dailyFunction(){
		var dateObj 			= 	new Date();
		$scope.fromNowTS		=	new Date(dateObj.setDate(dateObj.getDate()-1));
	//	$scope.totime 			= 	formatAMPM($scope.fromNowTS.getTime());
		$scope.fromdateDaily	=	getTodayDate($scope.fromNowTS);
	//  $scope.todateDaily	    =	getTodayDate($scope.fromNowTS);
		$scope.todateDaily		=   $scope.fromdateDaily;
		var webServiceUrl   	=   GLOBAL.DOMAIN_NAME+'/getDailyDriverPerformance?groupId='+$scope.groupName+'&fromUtcTime='+utcFormat($scope.fromdateDaily,convert_to_24h($scope.fromTime))+'&toUtcTime='+utcFormat($scope.todateDaily,convert_to_24h($scope.totime));
		OverallDriverPerformance(webServiceUrl);
	}

	function monthlyFunction(){
		var webServiceUrl 	=	'';
		var date 			= 	new Date();
		$scope.year 		=	date.getFullYear();
		if(date.getMonth()	== 0)
			$scope.year 		=	date.getFullYear() -1;
		$scope.month 		= 	monthNames[(date.getMonth() == 0) ? 11 : date.getMonth()-1];
		$scope.fromdate 	=	$scope.month+','+$scope.year;
		if($scope.groupVeh 	== true)	{
			webServiceUrl 	= 	GLOBAL.DOMAIN_NAME+'/getOverallDriverPerformance?groupId='+$scope.groupName+'&month='+$scope.month+'&year='+$scope.year;
		} else {
			webServiceUrl 	=	GLOBAL.DOMAIN_NAME+'/getIndividualDriverPerformance?groupId='+$scope.groupName+'&vehicleId='+$scope.vehicleId+'&month='+$scope.month+'&year='+$scope.year;
		}
		OverallDriverPerformance(webServiceUrl);
	}

	
	//for group selection

	$scope.groupSelection 	=	function(groupname, rowId, index){
		startLoading();
		$scope.gIndex		=	rowId;
		$scope.groupName 	=	groupname;
		$scope.groupVeh		=	true;
		var groupUrl 		= 	GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+groupname;
		vamoservice.getDataCall(groupUrl).then(function(data) {
			sessionValue($scope.vehicleId, $scope.groupName);
			$scope.vehicleId 	  = data[$scope.gIndex].vehicleLocations[0].vehicleId;
			$scope.vehicleDetails =  data;
			$scope.initMethod();
			// $scope.locations      =  data[$scope.gIndex].vehicleLocations;
		});
	}

	$scope.genericFunction 	=	function(vehiid, groupname, index){
		// status 				=	'singleVehicle';
		startLoading();
		$scope.gIndex		=	index;
		$scope.groupName 	=	groupname;
		$scope.vehicleId 	= 	vehiid;
		$scope.groupVeh		=	false;
		sessionValue($scope.vehicleId, $scope.groupName);
		// $scope.initMethod();
		if(status == 'monthly')
			monthlyFunction();
		else if(status == 'daily')
			stopLoading();
			// dailyFunction();
	}


	$scope.initMethod	= 	function ()
	{
		switch(status){
			case 'daily':
				$scope.groupVeh			=	false;
				dailyFunction();
				break;
			case 'monthly':
				$scope.groupVeh			=	true;
				monthlyFunction();
				break;
			default :
				break;
		}
	}

	function monthYear(){

		var split  = $scope.fromdate.split(',');
		$scope.month = split[0];
		$scope.year  = split[1]; 
	}

	$scope.submitButton 	= 	function(){
		if((checkXssProtection($scope.fromdate) == true) || ((checkXssProtection($scope.fromdateDaily) == true) && (checkXssProtection($scope.fromTime) == true) && (checkXssProtection($scope.fromTime) == true) && (checkXssProtection($scope.todateDaily) == true))) {
			
			startLoading();
			var webServiceUrl   = 	'';
			$scope.todateDaily  = $scope.fromdateDaily;
			if(status == 'daily')
				webServiceUrl   	=   GLOBAL.DOMAIN_NAME+'/getDailyDriverPerformance?groupId='+$scope.groupName+'&fromUtcTime='+utcFormat($scope.fromdateDaily,convert_to_24h($scope.fromTime))+'&toUtcTime='+utcFormat($scope.todateDaily,convert_to_24h($scope.totime));
				// webServiceUrl   	=   'http://'+globalIP+context+'/public/getDailyDriverPerformance?groupId='+$scope.groupName+'&fromDate='+$scope.fromdateDaily+'&fromTime='+convert_to_24h($scope.fromTime)+'&toDate='+$scope.todateDaily+'&toTime='+convert_to_24h($scope.totime)+'&fromDateUTC='+utcFormat($scope.fromdateDaily,convert_to_24h($scope.fromTime))+'&toDateUTC='+utcFormat($scope.todateDaily,convert_to_24h($scope.totime));
			else if(status == 'monthly') 
				if($scope.groupVeh == true)
				{
					monthYear();
					webServiceUrl 	= 	GLOBAL.DOMAIN_NAME+'/getOverallDriverPerformance?groupId='+$scope.groupName+'&month='+$scope.month+'&year='+$scope.year;
				}  
				else
				{
					monthYear();
					webServiceUrl 	=	GLOBAL.DOMAIN_NAME+'/getIndividualDriverPerformance?groupId='+$scope.groupName+'&vehicleId='+$scope.vehicleId+'&month='+$scope.month+'&year='+$scope.year;		
				}
					
			OverallDriverPerformance(webServiceUrl);
		}
	}

	// $scope.modalSpeed = false; 
	// $scope.toggleSpeed 		=	function(user){
	// 	$scope.modalSpeed = true; 
	// 	console.log(' modal Speed function ')
	// }

	$scope.toggleSpeed = function(user, status) 
	{
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
		$scope.id=user.data.shortName;
		$scope.titleName = '';
		//$scope.topSpeed=user.topSpeed;
		if(status == 'speed'){
			$scope.titleName = 'Speed Analysis';
			if(undefined != user.data.historySpeedAnalysis.Excellent)
			{
				$scope.excellentCount=user.data.historySpeedAnalysis.Excellent.split(',')[0];
				$scope.excellentSpeed=user.data.historySpeedAnalysis.Excellent.split(',')[1];
			}
			if(undefined != user.data.historySpeedAnalysis.Best)
			{
				$scope.bestCount=user.data.historySpeedAnalysis.Best.split(',')[0];
				$scope.bestSpeed=user.data.historySpeedAnalysis.Best.split(',')[1];
			}
			if(undefined != user.data.historySpeedAnalysis.Average) 
			{
				$scope.averageCount=user.data.historySpeedAnalysis.Average.split(',')[0];
				$scope.averageSpeed=user.data.historySpeedAnalysis.Average.split(',')[1];
			}
			if(undefined != user.data.historySpeedAnalysis.Aggressive)
			{
				$scope.worstCount=user.data.historySpeedAnalysis.Aggressive.split(',')[0];
				$scope.worstSpeed=user.data.historySpeedAnalysis.Aggressive.split(',')[1];
			}
			if(undefined != user.data.historySpeedAnalysis.RedLiner)
			{
				$scope.redlinerCount=user.data.historySpeedAnalysis.RedLiner.split(',')[0];
				$scope.redlinerSpeed=user.data.historySpeedAnalysis.RedLiner.split(',')[1];	
			}
			
		} else if(status == 'shock'){
			$scope.titleName = 'Shock Alarm';
			if(undefined != user.data.historyShockAlarm.Excellent)
			{
				$scope.excellentCount=user.data.historyShockAlarm.Excellent.split(',')[0];
				$scope.excellentSpeed=user.data.historyShockAlarm.Excellent.split(',')[1];
			}
			if(undefined != user.data.historyShockAlarm.Best)
			{
				$scope.bestCount=user.data.historyShockAlarm.Best.split(',')[0];
				$scope.bestSpeed=user.data.historyShockAlarm.Best.split(',')[1];
			}
			if(undefined != user.data.historyShockAlarm.Average) 
			{
				$scope.averageCount=user.data.historyShockAlarm.Average.split(',')[0];
				$scope.averageSpeed=user.data.historyShockAlarm.Average.split(',')[1];
			}
			if(undefined != user.data.historyShockAlarm.Aggressive)
			{
				$scope.worstCount=user.data.historyShockAlarm.Aggressive.split(',')[0];
				$scope.worstSpeed=user.data.historyShockAlarm.Aggressive.split(',')[1];
			}
			if(undefined != user.data.historyShockAlarm.RedLiner)
			{
				$scope.redlinerCount=user.data.historyShockAlarm.RedLiner.split(',')[0];
				$scope.redlinerSpeed=user.data.historyShockAlarm.RedLiner.split(',')[1];	
			}
		}
	};

	
	$scope.breakWeight = function(user, status) {
		$scope.detailedView=user;
		// $scope.modalShown3 = !$scope.modalShown3;
		//console.log('arun')
		var obj 	= [];
		var obj1 	= [];
		var obj2	= [];
		var obj3 	= [];
		var obj4 	= [];
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
		$scope.harshCount =0;
		$scope.veryHarshCount =0;
		$scope.veryharsh =[];
		$scope.harsh=[];
		//console.log(1)
		$scope.id=$scope.detailedView.data.shortName;
		$scope.harshCount=0;
		$scope.normal=0;
		$scope.worst =[];
		$scope.worstCount=0;
		$scope.locationname='';
		// try{
			if(status == 'break') {
				$scope.titleName = 'Sudden Breaks';
				$scope.high = 'Speed';
				$scope.low = 'Slow';
				if(undefined != $scope.detailedView.data.suddenBreakList.normal)
				{
					obj 	= $scope.detailedView.data.suddenBreakList.normal.historySuddenBrk;
					$scope.normal = $scope.detailedView.data.suddenBreakList.normal.subTotalSuddenBreak;
				}
				

				if(undefined != $scope.detailedView.data.suddenBreakList.aggressive)
				{
					obj1 	= $scope.detailedView.data.suddenBreakList.aggressive.historySuddenBrk;
					$scope.aggressiveCount = $scope.detailedView.data.suddenBreakList.aggressive.subTotalSuddenBreak;	
				}
				

				if(undefined != $scope.detailedView.data.suddenBreakList.harsh)
				{
					obj2 	= $scope.detailedView.data.suddenBreakList.harsh.historySuddenBrk;
					$scope.harshCount = $scope.detailedView.data.suddenBreakList.harsh.subTotalSuddenBreak;	
				}
				

				if(undefined != $scope.detailedView.data.suddenBreakList.veryharsh)
				{
					obj3 	= $scope.detailedView.data.suddenBreakList.veryharsh.historySuddenBrk;
					$scope.veryHarshCount = $scope.detailedView.data.suddenBreakList.veryharsh.subTotalSuddenBreak;	
				}
				

				if(undefined != $scope.detailedView.data.suddenBreakList.worst)
				{
					obj4 	= $scope.detailedView.data.suddenBreakList.worst.historySuddenBrk;
					$scope.worstCount = $scope.detailedView.data.suddenBreakList.worst.subTotalSuddenBreak;	
				}
				
			} else if(status == 'accleration') {
				$scope.titleName = 'Sudden Acceleration'
				$scope.high = 'Slow';
				$scope.low = 'Speed';
				if(undefined != $scope.detailedView.data.suddenAcceleration.normal)
				{
					obj = $scope.detailedView.data.suddenAcceleration.normal.historySuddenAcceleration;
					$scope.normal = $scope.detailedView.data.suddenAcceleration.normal.totalSudAcceleration;
				}
				

				if(undefined != $scope.detailedView.data.suddenAcceleration.aggressive)
				{
					obj1 	= $scope.detailedView.data.suddenAcceleration.aggressive.historySuddenAcceleration;
					$scope.aggressiveCount = $scope.detailedView.data.suddenAcceleration.aggressive.totalSudAcceleration;	
				}
				

				if(undefined != $scope.detailedView.data.suddenAcceleration.harsh)
				{
					obj2	= $scope.detailedView.data.suddenAcceleration.harsh.historySuddenAcceleration;
					$scope.harshCount = $scope.detailedView.data.suddenAcceleration.harsh.totalSudAcceleration;	
				}
				

				if(undefined != $scope.detailedView.data.suddenAcceleration.veryharsh)
				{
					obj3 	= $scope.detailedView.data.suddenAcceleration.veryharsh.historySuddenBrk;
					$scope.veryHarshCount = $scope.detailedView.data.suddenAcceleration.veryharsh.subTotalSuddenBreak;	
				}
				

				if(undefined != $scope.detailedView.data.suddenAcceleration.worst)
				{
					obj4 	= $scope.detailedView.data.suddenAcceleration.worst.historySuddenBrk;
					$scope.worstCount = $scope.detailedView.data.suddenAcceleration.worst.subTotalSuddenBreak;	
				}
				
			}
		
		
		
		
		// var obj = $scope.detailedView.data.suddenAcceleration.normal.historySuddenAcceleration;
		//console.log(obj)
		
		//normal breaks for loop
		angular.forEach(obj, function(value, key) 
		{	
			splitting=value.split(',');
			//console.log('inthe for each---1--->'+splitting[0])
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
			
		});
		$scope.Values=detailedJson;
		
		
		
		var detailedJson1=[];
		//aggresive breaks for loop
		angular.forEach(obj1, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson1.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
		});
		$scope.aggressive=detailedJson1;
		
		
		//harsh break
		// var obj2 = $scope.detailedView.data.suddenAcceleration.harsh.historySuddenAcceleration;
		var detailedJson2=[];
		angular.forEach(obj2, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson2.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
		});
		$scope.harsh=detailedJson2;
		
		//very harsh break
		
		var detailedJson3=[];
		angular.forEach(obj3, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson3.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
		});
		$scope.veryharsh=detailedJson3;
		
		// worst performer
		// var obj4 = $scope.detailedView.data.suddenAcceleration.worst.historySuddenBrk;
		var detailedJson4=[];
		angular.forEach(obj4, function(value, key) 
		{	
			splitting=value.split(',');
			latitude = splitting[0]
			longutide = splitting[1]
			speed=splitting[3]
			slow=splitting[2];
			tempurl1 = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+latitude+','+longutide+"&sensor=true";
			detailedJson4.push({'address': tempurl1,'speed1' : speed,'slow' : slow,'latitude': latitude,'longutide': longutide,'time':splitting[4]});
		});
		$scope.worst=detailedJson4;
		
	};

	
}]);

