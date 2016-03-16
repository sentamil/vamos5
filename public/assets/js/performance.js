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
	$scope.url = 'http://'+globalIP+context+'/public/getVehicleLocations';
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
			$scope.tableValue=[];
			dataTableList=[];
			$scope.selected       =  undefined;
			$scope.locations02    =  data;
			$scope.locations      =  data[$scope.gIndex].vehicleLocations;
			$scope.initMethod(data);
			$scope.groupOverall          =  data[$scope.gIndex].group;
		});	
	});
	
			
			
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
		$('#status').show(); 
		$('#preloader').show();
		$scope.vSingle		=	vehicleno;
		$scope.gSingle		=	groupname;
		$scope.iSingle		=	index;
		
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
		var tempurl='http://'+globalIP+context+'/public/getIndividualDriverPerformance?groupId='+groupname+'&vehicleId='+vehicleno+'&month='+$scope.month+'&year='+$scope.year;
		$http.get(tempurl).success(function(data){
		//console.log(tempurl)
		$scope.tableValue=[];
		dataTableList=[];
		for(var i=0; i<data.length; i++)
		{
			totalsuddenBreak.push(data[i].weightedBreakAnalysis);
			SuddenAcc.push(data[i].weightedAccelAnalysis);
			OverSpeed.push(data[i].weightedSpeedAnalysis);
			sparkAlarm.push(data[i].weightedShockAlarmAnalysis);
			kiloMeter.push(data[i].distance);
			dataTableList.push({'month': months[i],'data': data[i]});
			//console.log('value----->'+data[i].weightedBreakAnalysis)
		}
		$('#status').fadeOut(); 
		$('#preloader').delay(350).fadeOut('slow');
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
		dataTableList=[];
		$scope.single=false;
		$scope.group=true;
		$scope.monthYear($scope.month,$scope.year);
		$scope.groupName=group[$scope.gIndex].group;
		var tempurl1='http://'+globalIP+context+'/public/getOverallDriverPerformance?groupId='+$scope.groupName+'&month='+$scope.month+'&year='+$scope.year;
		OverallDriverPerformance(tempurl1)
	}

	$scope.sumbitAction=function(date)
	{
		if($scope.group == true)
		{
			$scope.monthYear($scope.month,$scope.year);
			var urlOver = 'http://'+globalIP+context+'/public/getOverallDriverPerformance?groupId='+$scope.groupOverall+'&month='+$scope.month+'&year='+$scope.year;
			OverallDriverPerformance(urlOver);	
		}
		else
		{
			//console.log('    group false   ')
			$scope.genericFunction1($scope.vSingle, $scope.gSingle, $scope.iSingle);
		// 	$scope.vSingle		=	vehicleno;
		// $scope.gSingle		=	groupname;
		// $scope.iSingle		=	index;
		}
	}	

	var OverallDriverPerformance = function(tempurl1)
	{
		$('#status').show(); 
		$('#preloader').show();
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
			$scope.tableValue=[];
			dataTableList=[];
			$scope.value=data;
			for(i; i<data.length; i++)
			{
				vehiclename.push(data[i].shortName);
				totalsuddenBreak.push(data[i].weightedBreakAnalysis);
				SuddenAcc.push(data[i].weightedAccelAnalysis);
				OverSpeed.push(data[i].weightedSpeedAnalysis);
				sparkAlarm.push(data[i].weightedShockAlarmAnalysis);
				kiloMeter.push(data[i].distance);
				dataTableList.push({'month':data[i].shortName,'data': data[i]});
			}
			$scope.tableValue=dataTableList;
			$('#status').fadeOut(); 
			$('#preloader').delay(350).fadeOut('slow');
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
		$scope.gIndex=groupid;
		$scope.url = 'http://'+globalIP+context+'/public//getVehicleLocations?group='+groupname;
		// $scope.tableValue=[];
		// dataTableList=[];
		// var totalsuddenBreak=[];
		// var SuddenAcc=[];
		// var OverSpeed=[];
		// var sparkAlarm=[];
		// var vehiclename=[];
		// var kiloMeter=[];
		// $scope.value=[];
		$scope.monthYear($scope.month,$scope.year);
		tempurl1='http://'+globalIP+context+'/public/getOverallDriverPerformance?groupId='+groupname+'&month='+$scope.month+'&year='+$scope.year;
		//OverallDriverPerformance(tempurl1);
		$scope.single=false;
		$scope.group=true;
		if($scope.groupOverall = groupname)
		{
			OverallDriverPerformance(tempurl1);
		}
		
		
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
		$scope.id=$scope.detailedView.data.shortName;
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
		//console.log('modal two popup-------->'+user.historySpeedAnalysis)
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
		$scope.id=user.data.shortName;
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
		$scope.Values=[];
		$scope.aggressive=[];
		$scope.aggressiveCount=0;
		$scope.excellentCount=0;
		$scope.excellentSpeed=0;
		$scope.normal=0;
		$scope.bestCount=0;
		$scope.bestSpeed=0;
		$scope.averageCount=0;
		$scope.averageSpeed=0;
		$scope.worstCount=0;
		$scope.worstSpeed=0;
		$scope.redlinerCount=0;
		$scope.redlinerSpeed=0;
		$scope.modalShown2 = !$scope.modalShown2;
		$scope.id=user.data.shortName;
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
		//console.log(1)
		$scope.id=$scope.detailedView.data.shortName;
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
	
   
});
