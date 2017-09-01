//comment by satheesh ++...
var getIP	=	globalIP;
app.controller('mainCtrl',['$scope', '$http', '$timeout', '$interval', '_global', function($scope, $http, $timeout, $interval, GLOBAL){
	
  // $("#testLoad").load("../public/menu");
	 var getUrl  =   document.location.href;
  // var index   =   getUrl.split("=")[1];
	 var index=getParameterByName('ind');
	
	if( index == 1){
		$scope.actTab 	=	true;
		$(window).load(function(){
        	$('#myModal').modal('show');
    	});
	
	} else if( index == 2 ){
		$scope.siteTab 		=	true;
		$scope.sort = sortByDate('startTime');
	
	} else if( index == 3 ){
		$scope.overTab 		=	true;
	
	} else if( index == 4 ){
		$scope.newSiteTab 	=	true;
	
	} else {
		$scope.actTab 	    =	false;
		$scope.siteTab 	    =	false;
		$scope.overTab      =   false;
		$scope.newSiteTab 	=	false;
	}
 
    $scope.msgShow      =   0;
    $scope.cardMsgShow  =   false;
    $scope.tab 			=	true;
	$scope.vvid			=	getParameterByName('vid');
	$scope.mainlist		=	[];
	$scope.newAddr      = 	{};
	$scope.groupId 		=   0;
	$scope.url 			= 	GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
	
	$scope.getTodayDate1  =	function(date) {
     	var date = new Date(date);
	 return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
    };	

    $scope.siteSplitName  =	function(data,num) {
     //console.log(num);
       var splitRetValue;

      if(data)
         {
         splitRetValue = data.split(/[:]+/);
            // splitRetValue = splitValue[0];
       
         switch(num){ 
        	case 1:
     	      return splitRetValue[0];
     	    break;
     	    case 2:
     	      return splitRetValue[1];
     	    break;
     	    case 3:
     	      return splitRetValue[2];
     	    break;
     	    case 4:
     	     return splitRetValue[3];
     	    break; 
     	    case 5:
     	     return splitRetValue[4];
     	    break;      
          }

       }else{
            return splitRetValue ="";        
          }
    }
    
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

    $scope.convert_to_24hrs=function(time_str) {
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

        hours   = hours < 10 ? '0'+hours : hours;
	    minutes = minutes < 10 ? '0'+minutes : minutes;  
	    seconds = seconds < 10 ? '0'+seconds : seconds; 

	    var marktimestr	=	''+hours+':'+minutes+':'+seconds;	    
	return marktimestr;
    };

    function format24hrs(date)
    {
    	var date1 = new Date(date);
    	var hrs   = date.getHours();
    	var min   = date.getMinutes();
    	var sec   = date.getSeconds();
    	//console.log(hrs+':'+min+':'+sec)
    	return hrs+':'+min+':'+sec;
    }

    $scope.trimColon = function(data){

        var splitValue;
    	if(data){

          var splitValue = data.split(/[:]+/);

        return splitValue[0];  
		}else{

        return splitValue="";
		}
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
    
   $scope.vehiLen = function(data){

   	  $scope.totVehiLen  =  0 ;
   	  $scope.expVehiName = [] ;

        angular.forEach(data.vehicleLocations,function(val, key){
      
               if(val.expired=="No"){

               	  $scope.expVehiName.push(val.shortName);
                  $scope.totVehiLen++;
               }
               else{
               	console.log('Expired-'+val.shortName+'');
               }
        });
         
      //  console.log( $scope.expVehiName ); 
      //  console.log( $scope.totVehiLen );
    }
   

   $scope.filterExpire = function(data){

    //console.log(data);
     var ret_obj=[];
        
      angular.forEach(data,function(val, key){

            ret_obj.push({group:val.group,totalVehicles:$scope.totVehiLen});
            ret_obj[key].vehicleLocations=[];  

        angular.forEach(val.vehicleLocations,function(sval,skey){

        	if(sval.expired=="No"){

               ret_obj[key].vehicleLocations.push(sval);
            // console.log(sval.vehicleId);
            }
          })
       })   
    return ret_obj[$scope.groupId];
    }

     $scope.vehiSidebar = function(data){

   	  var ret_obj=[];

       angular.forEach(data,function(val, key){

       	   ret_obj.push({rowId:val.rowId,group:val.group});
       	   ret_obj[key].vehicleLocations=[]

          angular.forEach(val.vehicleLocations,function(sval,skey){

          	if(sval.expired == "No"){ 
          
             ret_obj[key].vehicleLocations.push(sval);
             }
             else if(sval.expired == "Yes"){

             ret_obj[key].vehicleLocations.push({status:sval.status,rowId:sval.rowId,shortName:sval.shortName,vehicleId:sval.vehicleId,vehicleType:sval.vehicleType});
             }
          })
       })

    return ret_obj;    
    } 

	//$scope.url 			  =   GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
	$scope.fromTime       =   '12:00 AM';
	$scope.vehigroup;
	$scope.consoldateData =   [];
	$scope.today 		  =   new Date();
	$("#testLoad").load("../public/menu");
	 

	$scope._globalInit 	= function() {
	  //console.log('global...');
	    startLoading();
	 	$http.get($scope.url).success(function(data){
	 		if(data.length >0 && data != ''){
	 			$scope.locations 	=   [];
				//$scope.locations 	= 	data;

                $scope.locations 	= $scope.vehiSidebar(data);

				$scope.vehigroup    =   data[$scope.groupId].group;
				window.localStorage.setItem("groupname",$scope.vehigroup);
				$scope.vehiname		=	data[$scope.groupId].vehicleLocations[0].vehicleId;
				sessionStorage.setItem('user', JSON.stringify($scope.vehiname+','+$scope.vehigroup));
				angular.forEach(data, function(value, key) {
					   if(value.totalVehicles) {
					  	    $scope.data2 = data[key];
					  	    $scope.cardMsgShow = true;
					  		$scope.vehiLen(data[key]);
                        //  console.log(data[key]);
                        //  console.log( $scope.filterExpire(data) );
                            $scope.data1 = $scope.filterExpire(data);
					  }
				});				
				if($scope.siteTab == true)
				{
					$scope.consoldateTrip();
					$scope.siteTab == false
				}
				if($scope.overTab == true)
				{
					$scope.consOverspeed(' ');
				  //$scope.overTab == false;
				}
                if($scope.newSiteTab == true)
				{
					$scope.siteLocFunc(' ');
				}

			$scope.recursive($scope.data1.vehicleLocations,0);
			}
			stopLoading();
		}).error(function(){stopLoading();});

	};
	
	var promise;
	$scope.startTime 	= function(sName, val){

		$scope.sort 	= sortByDate(sName);
		if(val == 'reload' || val == undefined)
			$scope.siteTab 	= false, $scope.actTab 	= false;
		promise  = $interval( function(){ $scope._globalInit(); /*$scope.geoVehLocations();*/}, 60000);
	}

	$scope.startTime('shortName', index);

    $scope.$watch("url", function (val) {
  		
  		$scope._globalInit();

	});

    // address resolving with delay function
	$scope.selectMe = function(consoldateData)
    {
    	angular.forEach(consoldateData, function(value, primaryKey){
    		var index1 = primaryKey;
    		angular.forEach(value.historyConsilated, function(value, secondKey){
    			var index2 = secondKey;
    			if(value.state == 'S' || value.state == 'P' )
    			{
    				if(value.address == undefined)
    				{
    					var url_address	 =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+value.latitude+','+value.longitude+"&sensor=true";
    					var latCon       =  value.latitude;
    					var loncon		 =  value.longitude;
    					delayed(3000, function (index1, index2) {
					      return function () {
					        $scope.getAddressFromGAPI(url_address, index1, index2, latCon, loncon);
					      };
					    }(index1, index2));
    				}
    			}
    		})
    	})
    }

    var delayed = (function () {
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


$scope.geoVehLocations = function(){

           $scope.sitesDataGroup=[];

         	var userMasterId  = window.localStorage.getItem("userMasterName");
         	var groupMasterId = window.localStorage.getItem("groupname");
         	if (userMasterId != null)
         	{
         	var splitValue    = userMasterId.split(',');
         	var userName      = splitValue[1];
         }
      
            var requestUrl    =  GLOBAL.DOMAIN_NAME+'/getSitewiseVehicleCount?groupId='+groupMasterId;
         // console.log(requestUrl);

        $http.get(requestUrl).success(function(data)
			{	
               $scope.getGeoFence    = data.siteDetails;

              if($scope.getGeoFence != null){
                $scope.verifyGeoCount = $scope.getGeoFence.length;
               }

			});
	}

	$scope.filtConData=function(data){

		$scope.filtData02 = [];
        angular.forEach(data,function(val, key){

            $scope.filtData02.push({vehiName:val.vehicleName});

               $scope.filtData02[key].vehiData=[];   

               $scope.filtData02[key].vehiData.push(val);
        /* console.log(val.vehicleName);
           angular.forEach(val.historyConsilated,function(sval,skey){
           }) */
       })

       // console.log($scope.filtData02);
      }

    $scope.filtConExpire =function(data){

      	var ret_obj=[];
      	var new_ret_obj=[];

        var expLen=$scope.expVehiName.length;

             for(var i=0;i<expLen;i++){

             	for(var j=0;j<data.length;j++){

                  if($scope.expVehiName[i]==data[j].vehiName){

                  	ret_obj.push(data[j].vehiData);

                    //console.log(data[j].vehiName);
                   // console.log(data[j].vehiData);

                  }
             }
           }

            angular.forEach(ret_obj,function(val, key){

               new_ret_obj.push(val[0]);
            });
      // console.log(new_ret_obj);

   return new_ret_obj;        
  }

    // web service call in the consoldate report
	var service = function(conUrl)
	{
		$http.get(conUrl).success(function(data)
		{
		  //console.log(data);
			$scope.filtConData(data);
		  //console.log($scope.filtConExpire($scope.filtData02));
            
          //$scope.consoldateData = data;
            $scope.consoldateData=$scope.filtConExpire($scope.filtData02);
          //console.log();

			$('#preloader').fadeOut(); 
			$('#preloader02').delay(350).fadeOut('slow');
			if($scope.consoldateData)
				$scope.selectMe($scope.consoldateData);
			else
			{
				$scope.connSlow = "No Data found! Please contact our admin.";
				$('#connSlow').modal('show');
			}
		});
	}
  	 
	$scope.getAddressFromGAPI = function(url, index1, index2, lat, lan) {
		$http.get(url).success(function(data) {
    		
    		// Declare the new property address to the existing list
			$scope.consoldateData[index1].historyConsilated[index2].address = " ";
			$scope.consoldateData[index1].historyConsilated[index2].address = data.results[0].formatted_address;
			// var t = vamo_sysservice.geocodeToserver(lat, lan, data.results[0].formatted_address);
    	})
	};


	$scope.stop = function() {

		// console.log(value);
      	$interval.cancel(promise);
    };

	$scope.consoldate1 =  function()
	{
		// $('#preloader').show(); 
		// $('#preloader02').show();
		startLoading();
		$scope.stop();
		$scope.fromdate1   =  document.getElementById("dateFrom").value;
		$scope.fromTime    =  document.getElementById("timeFrom").value;
		$scope.todate1     =  document.getElementById("dateTo").value;
		$scope.totime      =  document.getElementById("timeTo").value;
		if((checkXssProtection($scope.fromdate1) == true) && (checkXssProtection($scope.fromTime) == true) && (checkXssProtection($scope.todate1) == true) && (checkXssProtection($scope.totime) == true)){

			var conUrl1        =  GLOBAL.DOMAIN_NAME+'/getOverallVehicleHistory?group='+$scope.vehigroup+'&fromDate='+$scope.fromdate1+'&fromTime='+convert_to_24h($scope.fromTime)+'&toDate='+$scope.todate1+'&toTime='+convert_to_24h($scope.totime)+'&fromDateUTC='+utcFormat($scope.fromdate1,convert_to_24h($scope.fromTime))+'&toDateUTC='+utcFormat($scope.todate1,convert_to_24h($scope.totime));
			var days = daydiff(new Date($scope.fromdate1), new Date($scope.todate1));
			if(days <= 3)
				service(conUrl1);
			else {
				// $('#preloader').fadeOut(); 
				// $('#preloader02').delay(350).fadeOut('slow');
				stopLoading()
				$scope.connSlow = "The date range should be less than or equal to 3 days.";
				$('#connSlow').modal('show');
			}
		}
	}

	$scope.dateFunction = function()
	{
		$scope.fromNowTS1		=	new Date();
		$scope.fromdate1		=	$scope.getTodayDate1($scope.fromNowTS1.setDate($scope.fromNowTS1.getDate()));
		$scope.todate1			=	$scope.getTodayDate1($scope.fromNowTS1.setDate($scope.fromNowTS1.getDate()));
		$scope.totime		    =	$scope.fromNowTS1.toLocaleTimeString(navigator.language, {hour: '2-digit', minute:'2-digit'});
		$scope.checkBox.loc 	=   true;
		$scope.checkBox.site 	=   true;
	}
	
	$scope.consoldate =  function()
	{
		// $('#preloader').show(); 
		// $('#preloader02').show();
		startLoading();
		$scope.stop();
		$scope.dateFunction();
		var conUrl      =   GLOBAL.DOMAIN_NAME+'/getOverallVehicleHistory?group='+$scope.vehigroup+'&fromDate='+$scope.fromdate1+'&fromTime='+convert_to_24h($scope.fromTime)+'&toDate='+$scope.todate1+'&toTime='+convert_to_24h($scope.totime)+'&fromDateUTC='+utcFormat($scope.fromdate1,convert_to_24h($scope.fromTime))+'&toDateUTC='+utcFormat($scope.todate1,convert_to_24h($scope.totime));
		service(conUrl);
	}

    $scope.filterTripData=function(data){

    	var ret_obj=[];

    	//console.log('filterTripData');
    	//console.log($scope.expVehiName);
    	//console.log(data[0].vehicleName);

    	if(data){

    	  for(var i=0;i<$scope.expVehiName.length;i++){

    	    for(var j=0;j<data.length;j++){	
     
                if($scope.expVehiName[i]==data[j].vehicleName)
                {
                        //console.log(data[j].vehicleName);
                        ret_obj.push(data[j]);

                 }
            }
          }
        }

       var new_ret_obj=[];

       new_ret_obj.push({});
       new_ret_obj[0].mulitple=[];

       angular.forEach(ret_obj,function(val, key){
           new_ret_obj[0].mulitple.push(val);  
       });

     //  console.log(ret_obj);
     //  console.log(new_ret_obj[0]);

     return new_ret_obj[0];
    }


	function serviceCallTrip (url){
		
		$http.get(url).success(function(response){

		//	console.log(response);
        //  $scope.tripData = response;
            $scope.tripData = [];
			$scope.tripData = $scope.filterTripData(response.mulitple);
			$scope.msgShow  = 1;

			stopLoading();
			// $('#preloader').fadeOut(); 
			// $('#preloader02').delay(350).fadeOut('slow');
		})
	}


	$scope.checkBox =	{};
	$scope.consoldateTrip = function(valu)
	{	
		startLoading();
		$scope.stop();
		$scope.sort = sortByDate('startTime');

		// $('#preloader').show(); 
		// $('#preloader02').show();
		// $scope.tripValu = valu;
		// console.log(' trip '+valu+'---->'+$scope.checkBox.site+$scope.checkBox.loc);
		if(valu == 'tripButon')
		{
			$scope.fromDateSite   =  document.getElementById("tripDatefrom").value;
			$scope.fromTimeSite   =  document.getElementById("tripTimeFrom").value;
			$scope.toDateSite     =  document.getElementById("tripDateTo").value;
			$scope.toTimeSite     =  document.getElementById("tripTimeTo").value;

	    } else  {	

            $scope.checkBox.site =  true;
            $scope.checkBox.loc  =  false;

		    var dateObj 		  =  new Date();
			$scope.fromNowTS	  =  new Date(dateObj.setDate(dateObj.getDate()));
			$scope.fromDateSite   =	 getTodayDate($scope.fromNowTS);
		    $scope.fromTimeSite	  =	 '12:00 AM';
		    $scope.toDateSite	  =	 getTodayDate($scope.fromNowTS);
	        $scope.toTimeSite	  =	 formatAMPM($scope.fromNowTS.getTime());
		}

       // var daysDiff = daydiff(new Date($scope.fromDateSite), new Date($scope.toDateSite));

          //  console.log( utcFormat($scope.fromDateSite,convert_to_24h($scope.fromTimeSite)) );
              // console.log( utcFormat($scope.toDateSite,convert_to_24h($scope.toTimeSite))  );

            var fromTms = utcFormat($scope.fromDateSite,convert_to_24h($scope.fromTimeSite));
               var toTms   = utcFormat($scope.toDateSite,convert_to_24h($scope.toTimeSite));
				
            var totalTms=toTms-fromTms;
             // console.log(  $scope.msToTime(totalTms));

                  var splitTimes=$scope.msToTime(totalTms).split(':');
                    //  console.log(splitTimes[0]);
                   
                  var timesDiff=splitTimes[0];

      if( timesDiff <= 48 ) {
      	 if( ($scope.checkBox.loc == true && $scope.checkBox.site == true ) || ($scope.checkBox.loc == true && $scope.checkBox.site == false ) || ($scope.checkBox.loc == false && $scope.checkBox.site == true )){

		    var conUrl  =   GLOBAL.DOMAIN_NAME+'/getOverallSiteLocationReport?group='+$scope.vehigroup+'&fromDate='+$scope.fromDateSite+'&fromTime='+convert_to_24h($scope.fromTimeSite)+'&toDate='+$scope.toDateSite+'&toTime='+convert_to_24h($scope.toTimeSite)+'&location='+$scope.checkBox.loc+'&site='+$scope.checkBox.site+'&fromDateUTC='+utcFormat($scope.fromDateSite,convert_to_24h($scope.fromTimeSite))+'&toDateUTC='+utcFormat($scope.toDateSite,convert_to_24h($scope.toTimeSite));
		
		    if(checkXssProtection($scope.fromDateSite) == true && checkXssProtection($scope.fromTimeSite) == true && checkXssProtection($scope.toDateSite) == true && checkXssProtection($scope.toTimeSite) == true){

			 serviceCallTrip(conUrl);
		    }
	   } else {

	   	   $scope.tripData  = [];
           alert('Please select.. either Site (or) Location (or) both..');
           $scope.msgShow  = 1;
        stopLoading();
	   } 
	} else {
           
           $scope.tripData  = [];
		   alert('Time should be within 24 hrs!');
		   $scope.msgShow  = 1;

        stopLoading();
	   }
	}
 
	// $scope.consoldateTripButton = function(){
	// 	console.log('  consoldate trip '+$scope.fromdate1 +$scope.fromTime+$scope.todate1 +$scope.totime);
	// }
   /* function getOvrValues(){
		$scope.fromdate2 	=	$('#ovrFrom').val();
	  	$scope.fromTime2	=	$('#ovrTimeFrom').val();
	  	$scope.todate2 		=	$('#ovrTo').val();
	    $scope.totime2 		=	$('#ovrTimeTo').val();
 	
	}*/

	$scope.filterSpeedData=function(data){

    	var ret_obj=[];
    	if(data != null && $scope.expVehiName != null){
    	
    	   var dataLen=data.length;
           var expVehiLen=$scope.expVehiName.length;
    //	console.log('filterTripData');
    //	console.log($scope.expVehiName);
    //	console.log(data);
      	for(var i=0;i<expVehiLen;i++){
    	
    	 for(var j=0;j<dataLen;j++){	

          	if($scope.expVehiName[i]==data[j].shortName)
                {
                       //console.log(data[j].shortName);
                         ret_obj.push(data[j]);
                 }
             }
        }
     }
    // console.log( ret_obj );
     return ret_obj;   
    }


/*    $scope.siteLocFunc = function(valu)
	{	
		// startLoading();
		// $scope.ovrData 	=	[];
		// $scope.stop();
	    // $scope.sort = sortByDate('startTime');
		// $('#preloader').show(); 
		// $('#preloader02').show();
		// $scope.tripValu = valu;
		// console.log(' trip '+valu+'---->'+$scope.checkBox.site+$scope.checkBox.loc);
		if(valu === 'newSiteTab')
		{
	    // console.log('ovrButon');
		   $scope.fromDateSite    	=	$('#tripDateFrom').val();
	  	   $scope.fromTimeSite	    =	$('#tripTimeFrom').val();
	  	   $scope.toDateSite		=	$('#tripDateTo').val();
	       $scope.toTimeSite		=	$('#tripTimeTo').val();
		}
		else
		{	
		    var dateObj 		  =  new Date();
			$scope.fromNowTS	  =  new Date(dateObj.setDate(dateObj.getDate()-1));
			$scope.fromDateSite   =	 getTodayDate($scope.fromNowTS);
		    $scope.fromTimeSite	  =	 '12:00 AM';
		    $scope.toDateSite	  =	 getTodayDate($scope.fromNowTS);
		  //$scope.toTimeSite 	  =	 formatAMPM($scope.fromNowTS.getTime());
            $scope.toTimeSite 	  =  '11:59 PM';
		}
	
        if((checkXssProtection($scope.fromDateSite) == true) && ((checkXssProtection($scope.fromTimeSite) == true) && (checkXssProtection($scope.toDateSite) == true) && (checkXssProtection($scope.toTimeSite) == true))) {
         // var newSiteUrl =  http://128.199.159.130:9000/getOverallSiteLocation?userId=SBLT&groupId=SBLT:SMP&fromTimeUtc=1500489000000&toTimeUtc=1500575400000
            var newSiteUrl =  GLOBAL.DOMAIN_NAME+'/getOverallSiteLocation?groupId='+$scope.vehigroup+'&fromTimeUtc='+utcFormat($scope.fromDateSite,convert_to_24h($scope.fromTimeSite))+'&toTimeUtc='+utcFormat($scope.toDateSite,convert_to_24h($scope.toTimeSite));
        }

        console.log(newSiteUrl);
        $http.get(newSiteUrl).success(function(data){
           startLoading();
             $scope.tripSiteLocData = [];
             $scope.tripSiteLocData = data;

             console.log($scope.tripSiteLocData);
           stopLoading();
		}); 
	} */

	$scope.consOverspeed = function(valu)
	{	
	 startLoading();
		$scope.ovrData 	=	[];
		// $scope.stop();
	    // $scope.sort = sortByDate('startTime');
		// $('#preloader').show(); 
		// $('#preloader02').show();
		// $scope.tripValu = valu;
		// console.log(' trip '+valu+'---->'+$scope.checkBox.site+$scope.checkBox.loc);
		if(valu === 'ovrButon')
		{
	  //console.log('ovrButon');
		$scope.fromdate2 	=	$('#ovrFrom').val();
	  	$scope.fromTime2	=	$('#ovrTimeFrom').val();
	  	$scope.todate2 		=	$('#ovrTo').val();
	    $scope.toTime2 		=	$('#ovrTimeTo').val();
	
		}
		else
		{	
		    var dateObj 			= 	new Date();
			$scope.fromNowTS		=	new Date(dateObj.setDate(dateObj.getDate()-1));
			$scope.fromdate2 		=	getTodayDate($scope.fromNowTS);
		  	$scope.fromTime2		=	'12:00 AM';
		  	$scope.todate2		=	getTodayDate($scope.fromNowTS);
		  //$scope.totime2 		=	formatAMPM($scope.fromNowTS.getTime());
            $scope.toTime2 		=   '11:59 PM';
		}
	
      if((checkXssProtection($scope.fromdate2) == true) && ((checkXssProtection($scope.fromTime2) == true) && (checkXssProtection($scope.todate2) == true) && (checkXssProtection($scope.toTime2) == true))) {
      
          var ovrUrl = GLOBAL.DOMAIN_NAME+'/getOverSpeedReport?fromDateUTC='+utcFormat($scope.fromdate2,convert_to_24h($scope.fromTime2))+'&toDateUTC='+utcFormat($scope.todate2,convert_to_24h($scope.toTime2))+'&groupName='+$scope.vehigroup;
      }
      
    //  console.log(ovrUrl);
        $http.get(ovrUrl).success(function(data){
           startLoading();
        // console.log(data);
        // $scope.ovrData=data;  
           $scope.ovrData=$scope.filterSpeedData(data);
           stopLoading();
		}); 
	}


	$scope.dialogBox 	=	function()
	{
		$scope.stop();
		$scope.tab = false;
		$scope.fromdate1   =  document.getElementById("dateFrom").value;
		$scope.fromTime    =  document.getElementById("timeFrom").value;
		$scope.todate1     =  document.getElementById("dateTo").value;
		$scope.totime      =  document.getElementById("timeTo").value;
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

    $scope.address_click = function(data, ind)
	{
		var urlAddress 		=	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+data.latitude+','+data.longitude+"&sensor=true"
		$http.get(urlAddress).success(function(response)
		{
			data.address 	=	response.results[0].formatted_address;
			// var t 			= 	vamo_sysservice.geocodeToserver(data.latitude,data.longitude,response.results[0].formatted_address);
		});
	}

    // millesec to day, hours, min, sec
    $scope.msToTime = function(ms) 
    {
    	if(ms != null){
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
	}
	
	$scope.recursive   = function(location,index){
		var index3 = 0;
		angular.forEach(location, function(value, primaryKey){
    		index3 = primaryKey;
    		if(location[index3].address == undefined)
				{
					//console.log(' address idle'+index3)
					var latIdle		 =	location[index3].latitude;
				 	var lonIdle		 =	location[index3].longitude;
					var tempurlIdle	 =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latIdle+','+lonIdle+"&sensor=true";
					//console.log(' Idle report  '+index3)
					delayed1(3000, function (index3) {
					      return function () {
					        google_api_call(tempurlIdle, index3, latIdle, lonIdle);
					      };
					    }(index3));
				}
    	})
	}
	
	var delayed1 = (function () {
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
	
	$scope.$watch('vvid', function () {
		if($scope.vvid) {
			$scope.getStatusReport();
		}
	});
	
	function google_api_call(url, index1, lat, lan) {
		$http.get(url).success(function(data) {
    		
    		// Declare the new property address to the existing list
			$scope.mainlist[index1]= data.results[0].formatted_address;
			// var t = vamo_sysservice.geocodeToserver(lat, lan, data.results[0].formatted_address);
    	})
	};

 	$scope.getLocation	=	function(lat,lon,ind) {
		console.log(' calling function ')
		var tempurl	 =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon+"&sensor=true";
		$scope.loading	=	true;
		$http.get(tempurl).success(function(data){	
			$scope.locationname = data.results[0].formatted_address;
			$scope.mainlist[ind]=	data.results[0].formatted_address;
			$scope.loading	=	false;
			// var t = vamo_sysservice.geocodeToserver(lat, lon, data.results[0].formatted_address);
		});
	};
	
	$scope.getStatusReport		=		function() {
		 $scope.url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+$scope.vvid;
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
	
    $scope.genericFunction = function(vehicleno, index){
		sessionStorage.setItem('user', JSON.stringify(vehicleno+','+$scope.vehigroup));
		$("#testLoad").load("../public/menu");
	}
	
	var newGroupSelectionName="";
	var oldGroupSelectionName="";
	var initValss=0;
	
	$scope.groupSelection = function(groupname, groupid){

       if(initValss>0){
        oldGroupSelectionName=newGroupSelectionName;
       }

        newGroupSelectionName=groupname;
        initValss++;

      if(oldGroupSelectionName != newGroupSelectionName){

		$scope.groupId 	= 	groupid;
		$scope.vehigroup = groupname;
		$scope.url     	= 	GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+$scope.vehigroup;
		$('#preloader').show(); 
		$('#preloader02').show();
		if($('#consoldate').attr('id')=='consoldate')
			$scope.consoldate1();
		else if($('#tripTab').attr('id')=='tripTab')
			$scope.consoldateTrip('tripButon');

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
		//console.log('---->'+data);
		CSV.begin('#'+data).download(data+'.csv').go();
    };
    
    
}]);

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


