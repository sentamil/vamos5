app.controller('mainCtrl', ['$scope', '$filter','vamoservice', function($scope, $filter, vamoservice){

// tab view
var getUrl  =   document.location.href;
var tabId 	= 	'executive';
var index   =   getUrl.split("=")[1];
	if(index) {
		tabId 		= 	'poi';
		$scope.actTab 	=	true;
	}
	

//Global Variable declaration 
$scope.url 			= 	'http://'+globalIP+context+'/public//getVehicleLocations';
var clickStatus 	= 	'groupButton';
$scope.donut 		= 	false;
$scope.bar 			=	true;
$('#singleDiv').hide();
var avoidOnload		=	false;

$scope.downloadid 	= 	'executive';
var vehicleSelected =	'';





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

$scope.getTodayDate  =	function(date) {
 	var date = new Date(date);
	return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
};

function sessionValue(vid, gname){
	sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
	$("#testLoad").load("../public/menu");
}


//vehicle list
$scope.$watch("url", function(val){
	vamoservice.getDataCall(val).then(function(data) {
		console.log(0)
		$scope.vehicleList 		= 	data;
		$scope.fromNowTS		=	new Date();
		$scope.toNowTS			=	new Date().getTime() - 86400000;
		$scope.fromdate			=	$scope.getTodayDate($scope.fromNowTS.setDate($scope.fromNowTS.getDate()-7));
		$scope.todate			=	$scope.getTodayDate($scope.toNowTS);
		$scope.fromtime			=	formatAMPM($scope.fromNowTS);
   		$scope.totime			=	formatAMPM($scope.toNowTS);
   		$scope.trackVehID       =   data[0].vehicleLocations[0].vehicleId;
   		$scope.groupname 		=	data[0].group;
   		sessionValue($scope.trackVehID, $scope.groupname);

		angular.forEach(data, function(value, key){
			//console.log(value)
			if(value.totalVehicles) {
				$scope.viewGroup = 	value;
				serviceCall();
				avoidOnload		=	true;
			}
				
		})
		
	})
})

//group click
$scope.groupSelection 	=	function(groupName, groupId) {
	vehicleSelected 		=	'';
	$scope.viewGroup.group 	= 	groupName;
	startLoading();
	var groupUrl 			= 	'http://'+globalIP+context+'/public//getVehicleLocations?group='+groupName;
	vamoservice.getDataCall(groupUrl).then(function(groupResponse){
		$scope.trackVehID       =   groupResponse[groupId].vehicleLocations[0].vehicleId;
   		$scope.groupname 		=	groupName;
   		sessionValue($scope.trackVehID, $scope.groupname)
   		$scope.vehicleList 	=  	groupResponse;
		serviceCall();
		stopLoading();
	})
	
}

//vehicleId click
$scope.genericFunction 	= 	function(vehicleID, index){
	startLoading();
	vehicleSelected		= 	vehicleID;
	sessionValue(vehicleSelected,$scope.groupname);
	
	serviceCall();
}

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


//trim colon function
$scope.trimColon = function(textVal) {
		return textVal.split(":")[0].trim();
	}


//vehicle level graphs

function barLoad(vehicleId) {
   		//console.log(vehicleId);
   		$scope.barArray1	=	[];
   		$scope.barArray2	=	[];
   		$scope.barArray1.push(["X", "Date vs Distance"]);
   		$scope.barArray2.push(["X", "Date vs Overspeed"]);
   		$scope.data			=	($filter('filter')($scope.execGroupReportData, {'vehicleId':vehicleId}));
   		angular.forEach($scope.data, function(value, key) {
   			$scope.barArray1.push([value.date, value.distanceToday]);
   			$scope.barArray2.push([value.date, value.topSpeed]);
		}); 
		//console.log($scope.barArray1);
   		c3.generate({
   			bindto: '#chart3',
   			data: {
		    	x: 'X',
		        columns: $scope.barArray1,
		        type: 'bar'
		    },
		   	 axis: {
		        x: {
		        	type : 'category',
		            label:{		            	
		            	text : 'Date',
		            	position: 'outer-right'	
		            }
		        },
		        y: {
		            label:{
		            	text : 'Distance Today',
		            	position: 'outer-middle'	
		            }
		        }
		    }
		});
		
		c3.generate({
   			bindto: '#chart4',
		    data: {
		    	x: 'X',
		        columns: $scope.barArray2,
		        type: 'bar'
		    },
		   	 axis: {
		        x: {
		        	type : 'category',
		            label:{		            	
		            	text : 'Date',
		            	position: 'outer-right'	
		            }
		        },
		        y: {
		            label:{
		            	text : 'Topspeed',
		            	position: 'outer-middle'	
		            }
		        }
		    }
		});				
   	};
	

//group level graph 

function donutLoad(data) {
	$scope.barArray	=	[];
	var vehiName='';
	angular.forEach(JSON.parse(data.distanceCoveredAnalytics), function(value, key) {
		angular.forEach(data.execReportData, function(val, key1){
			if(val.vehicleId == key)
			{
				vehiName = val.shortName;
				//console.log(' inside the for loop ---->'+key+'--->'+val.vehicleId)
				return;
			}
		})	
		$scope.barArray.push([vehiName, value]);
	}); 
	$('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Total Distance'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '8px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Distance Travelled'
            }
        },
        legend: {
            enabled: false
        },
        // tooltip: {
        //     pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>'
        // },
        series: [{
            name: 'Distance',
            data: $scope.barArray,
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#003366',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '9px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
};


$scope.msToTime = function(ms) 
    {
        days = Math.floor(ms / (24 * 60 * 60 * 1000));
	  	daysms = ms % (24 * 60 * 60 * 1000);
		hours = Math.floor((daysms) / (60 * 60 * 1000));
		hoursms = ms % (60 * 60 * 1000);
		minutes = Math.floor((hoursms) / (60 * 1000));
		minutesms = ms % (60 * 1000);
		seconds = Math.floor((minutesms) / 1000);
		if(days==0)
			return hours +" h "+minutes+" m "+seconds+" s ";
		else
			return days+" d "+hours +" h "+minutes+" m "+seconds+" s ";
	}
	

function serviceCall(){
	// $scope.execGroupReportData 	=	[];
	if(tabId ==	'executive'){
		var groupUrl		=	'http://'+globalIP+context+'/public//getExecutiveReport?groupId='+$scope.viewGroup.group+'&fromDate='+$scope.fromdate+'&toDate='+$scope.todate;
		vamoservice.getDataCall(groupUrl).then(function(responseGroup){
			if(vehicleSelected){
				$scope.donut 	= 	true;
				$scope.bar 		=	false;
				$('#singleDiv').show(500);
				$scope.execGroupReportData	=	($filter('filter')(responseGroup.execReportData, {'vehicleId':vehicleSelected}));
				barLoad(vehicleSelected);
			} else {
				$scope.donut 	= 	false;
				$scope.bar 		=	true;
				$('#singleDiv').hide();
				$scope.execGroupReportData	=	responseGroup.execReportData;
				donutLoad(responseGroup);
			}
			stopLoading();
		})
	}else if(tabId == 'poi' || $scope.actTab == true){
		$scope.donut 		= 	true;
		$scope.bar 			= 	true;
		$('#singleDiv').hide();
		var poiUrl 			=	'http://'+globalIP+context+'/public//getPoiHistory?groupId='+$scope.viewGroup.group+'&fromDate='+$scope.fromdate+'&toDate='+$scope.todate;
		vamoservice.getDataCall(poiUrl).then(function(responsePoi){
			$scope.geofencedata			=		[];
			if(responsePoi.history.length>0)
				$scope.geofencedata		=   	responsePoi.history;
			stopLoading();
		})
	}
}

$scope.plotHist 	=	function()
{
	serviceCall();
}

//tab click method
$scope.alertMe 		= 	function(tabClick)
{	
	if(avoidOnload == true)
	switch (tabClick){
		case 'executive':
			tabId 	=	'executive';
			//console.log('executive');
			$scope.downloadid 	= 	'executive';
			startLoading();
			serviceCall();
			break;
		case 'poi':
			tabId	=	'poi';
			$scope.downloadid 	= 	'poi';
			startLoading();
			serviceCall();
			//console.log('poi');
			break;
		default :
			break;
	}
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


}]);

