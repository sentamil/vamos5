app.controller('mainCtrl',['$scope','$http','vamoservice','$filter', '_global', function($scope, $http, vamoservice, $filter, GLOBAL){
	
  //global declaration
    $scope.showBanner           =    0;
	$scope.uiDate 				=	{};
	$scope.uiValue	 			= 	{};
	$scope.geoFence             =   "";
    $scope.timeChanges          =  'All';
    $scope.selected             = undefined;
  //$scope.stopData             =   [];
  //$scope.sort = sortByDate('startTime');

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
			// var t = vamo_sysservice.geocodeToserver(latEvent,lonEvent,data.results[0].formatted_address);
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


    function webCall(){

      $scope.stopData=[];
      if((checkXssProtection($scope.uiDate.fromdate) == true) && ((checkXssProtection($scope.uiDate.fromtime) == true) && (checkXssProtection($scope.uiDate.todate) == true) && (checkXssProtection($scope.uiDate.totime) == true))) {

           var stopUrl = GLOBAL.DOMAIN_NAME+'/getStoppageReport?fromDateUTC='+utcFormat($scope.uiDate.fromdate,convert_to_24h($scope.uiDate.fromtime))+'&toDateUTC='+utcFormat($scope.uiDate.todate,convert_to_24h($scope.uiDate.totime))+'&groupName='+$scope.gName;
        }
      //console.log(stopUrl);
     
        $http.get(stopUrl).success(function(data){
            $scope.stopData=data;
          //console.log($scope.stopData);
            $scope.filterLoc=data;
            $scope.showBanner=1;
            stopLoading();
		}); 
    }


    $scope.timeFilter_stop=function(data,fVal)
    {   
      var filterValues=fVal;
      var ret_obj=[];

     if(data){

      angular.forEach(data,function(value, key){

          ret_obj.push({startTime:value.startTime,endTime:value.endTime,shortName:value.shortName})   
          ret_obj[key].history = [];

        angular.forEach(value.history, function(val, secondKey){

          if(val.stoppageTime>0 && filterValues==100){
           
            if(val.stoppageTime>=3600000){

            	ret_obj[key].history.push(val);
          
            }

         }else if(val.stoppageTime >0 && filterValues==30){


            if(val.stoppageTime>=1800000){

            	ret_obj[key].history.push(val);
            }

          }else if(val.stoppageTime >0 && filterValues==15){

            if(val.stoppageTime>=900000){
            	ret_obj[key].history.push(val);
        
            }

          }else if(val.stoppageTime >0 && filterValues==10){

            if(val.stoppageTime>=600000){
                ret_obj[key].history.push(val);

            }
            
          }else if(val.stoppageTime >0 && filterValues==5){

            if(val.stoppageTime>=300000){
            	ret_obj[key].history.push(val);

            }

          }else if(val.stoppageTime >0 && filterValues==2){

            if(val.stoppageTime>=120000){
            	ret_obj[key].history.push(val);

            }

          }else if(val.stoppageTime >0 && filterValues==1){

            if(val.stoppageTime>=60000){
            	ret_obj[key].history.push(val);

            }

          }else if(val.stoppageTime >0 && filterValues=='All'){
 
            	ret_obj[key].history.push(val);

          }
        
       })
    })
   }
   return ret_obj;
 }

 $scope.filtersTime= function(val,name){

    $scope.filterValue=val;
    $scope.stopData=[];
    $scope.stopData=$scope.timeFilter_stop($scope.filterLoc, $scope.filterValue);
 }


	//get the value from the ui
	function getUiValue(){
		$scope.uiDate.fromdate 		=	$('#dateFrom').val();
	  	$scope.uiDate.fromtime		=	$('#timeFrom').val();
	  	$scope.uiDate.todate		=	$('#dateTo').val();
	  	$scope.uiDate.totime 		=	$('#timeTo').val();
 	
	}


// service call for the event report

/*	function webServiceCall(){
		$scope.siteData = [];
		if((checkXssProtection($scope.uiDate.fromdate) == true) && (checkXssProtection($scope.uiDate.fromtime) == true) && (checkXssProtection($scope.uiDate.todate) == true) && (checkXssProtection($scope.uiDate.totime) == true)) {
			
			var url 	= GLOBAL.DOMAIN_NAME+"/getActionReport?vehicleId="+$scope.vehiname+"&fromDate="+$scope.uiDate.fromdate+"&fromTime="+convert_to_24h($scope.uiDate.fromtime)+"&toDate="+$scope.uiDate.todate+"&toTime="+convert_to_24h($scope.uiDate.totime)+"&interval="+$scope.interval+"&stoppage="+$scope.uiValue.stop+"&stopMints="+$scope.uiValue.stopmins+"&idle="+$scope.uiValue.idle+"&idleMints="+$scope.uiValue.idlemins+"&notReachable="+$scope.uiValue.notreach+"&notReachableMints="+$scope.uiValue.notreachmins+"&overspeed="+$scope.uiValue.speed+"&speed="+$scope.uiValue.speedkms+"&location="+$scope.uiValue.locat+"&site="+$scope.uiValue.site+'&fromDateUTC='+utcFormat($scope.uiDate.fromdate,convert_to_24h($scope.uiDate.fromtime))+'&toDateUTC='+utcFormat($scope.uiDate.todate,convert_to_24h($scope.uiDate.totime));
			vamoservice.getDataCall(url).then(function(responseVal){
				$scope.recursiveEvent(responseVal, 0);
				$scope.eventData = responseVal;
				var entry=0,exit=0; 
				angular.forEach(responseVal, function(val, key){
					if(val.state == 'SiteExit')
						exit++ 
					else if (val.state == 'SiteEntry')
						entry++
				})
				$scope.siteEntry 	=	entry;
				$scope.siteExit 	=	exit;
				stopLoading();
			});
		}
		stopLoading();
	}*/

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

							if($scope.vehiname == value.vehicleId)
							$scope.shortNam	= value.shortName;
						})
						
					}
			    })

		  //console.log($scope.selectVehiData);
		    sessionValue($scope.vehiname, $scope.gName)
			}
			var dateObj 			= 	new Date();
			$scope.fromNowTS		=	new Date(dateObj.setDate(dateObj.getDate()-1));
			$scope.uiDate.fromdate 		=	getTodayDate($scope.fromNowTS);
		  	$scope.uiDate.fromtime		=	'12:00 AM';
		  	$scope.uiDate.todate		=	getTodayDate($scope.fromNowTS);
		  //$scope.uiDate.totime 		=	formatAMPM($scope.fromNowTS.getTime());
            $scope.uiDate.totime 		=   '11:59 PM';
		  //webServiceCall();
		    startLoading();
		    webCall();
		  //stopLoading();
		});	
	});

    $scope.selectVehiModel = [];

    $scope.example14settings = {
        scrollableHeight: '400px',
        scrollable: true,
        enableSearch: true
    };

   $scope.example2settings = {
        displayProp: 'id'
    };


   
   $scope.vehiFilter = function(data,filtVal){

    var ret_obj=[];
    var firstVal=0;
     // console.log(data);
     // console.log(filtVal);

     if(data){

      angular.forEach(data,function(value, key){

        angular.forEach(filtVal, function(sval, sKey){

         if(value.id==sval.vehicleId){

         	       ret_obj.push({startTime:sval.startTime,endTime:sval.endTime,shortName:sval.shortName})   
                   ret_obj[firstVal].history = [];

               angular.forEach(sval.history, function(tval, tKey){

                 //  console.log(tval);
               	   ret_obj[firstVal].history.push(tval);
                   
                }) 

               firstVal++;
            }    

         })

      })

    //  console.log(ret_obj);

     return ret_obj;
     }
   }

   $scope.selectVehicle = function(selectVal){

      if(selectVal.length != 0){
      //$scope.stopData=[];
        $scope.stopData=$scope.vehiFilter(selectVal,$scope.filterLoc);
       //console.log($scope.stopData);

      }else if(selectVal){

   	    $scope.stopData=$scope.filterLoc;
      }
    }

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

							if($scope.vehiname == value.vehicleId)
							$scope.shortNam	= value.shortName;
						})
				    }
				})

			getUiValue();
			webCall();
		  //webServiceCall();
	      //stopLoading();
		});

	}


/*	$scope.genericFunction 	= function (vehid, index){
		startLoading();
		$scope.vehiname		= vehid;
		sessionValue($scope.vehiname, $scope.gName)
		angular.forEach($scope.vehicle_list[$scope.gIndex].vehicleLocations, function(val, key){
			if(vehid == val.vehicleId)
				$scope.shortNam	= val.shortName;
		})
		getUiValue();
	//	webServiceCall();

	}*/

  	$scope.submitFunction 	=	function(){
	  startLoading();
	  $scope.timeChanges          =  'All';
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
	})

}]);
