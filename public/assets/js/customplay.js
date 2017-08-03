//var app = angular.module('mapApp', []);
var gmarkers=[];
var ginfowindow=[];
var gsmarker=[];
var gsinfoWindow=[];
var geoinfowindow=[];
var contentString = [];
var contentString01=[];
var id;
var geomarker=[], geoinfo=[];

app.directive('map', ['$http', '_global', function($http, GLOBAL) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {
        	scope.path=[];
        	var geoUrl = GLOBAL.DOMAIN_NAME+'/viewSite';
            var polygenColors,labelAnchorpos,markers;

        	function utcdateConvert(milliseconds){
			  //var milliseconds=1440700484003;
				var offset='+10';
				var d = new Date(milliseconds);
				utc = d.getTime() + (d.getTimezoneOffset() * 60000);
				nd = new Date(utc + (3600000*offset));
				var result=nd.toLocaleString();
				return result;
			}
		 		
		 		scope.$watch("hisurl", function (val) {
		 		//startLoading();
		 		scope.path = [];

		 		if(scope.hisurl != undefined)
		   		$http.get(scope.hisurl).success(function(data){
		   			var locs = data;
		 			scope.hisloc = locs;
					scope._tableValue(locs);
					if(data.fromDateTime=='' || data.fromDateTime==undefined || data.fromDateTime=='NaN-aN-aN'){ 
						if(data.error==null){}else{
							$('.alert-danger').show();
							// $('#myModal').modal();
						}
						$('#lastseen').html('<strong>From Date & time :</strong> -');
						$('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');
					}else{
						$('.alert-danger').hide();
						if(data.error==null){
							// var fromNow =new Date(data.fromDateTime.replace('IST',''));
							// var toNow 	= new Date(data.toDateTimeUTC);
							scope.fromNowTS		    =	data.fromDateTimeUTC;
							scope.toNowTS			=	data.toDateTimeUTC;	
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
							scope.fromtime			=	formatAMPM(scope.fromNowTS);
   							scope.totime			=	formatAMPM(scope.toNowTS);
							scope.fromdate			=	scope.getTodayDate(scope.fromNowTS);
							scope.todate			=	scope.getTodayDate(scope.toNowTS);
							
							$('#vehiid h3').text(locs.shortName);
							// $('#toddist h6').text(scope.timeCalculate(locs.totalRunningTime));
							// $('#vehstat h6').text(scope.timeCalculate(locs.totalIdleTime));
							// $('#vehdevtype h6 span').text(locs.odoDistance);
							// $('#mobno h6').text(scope.timeCalculate(locs.totalParkedTime));
							// $('#regno h6 span').text(locs.tripDistance);
							
							$('#lastseen').html('<strong>From Date & time :</strong> '+ new Date(data.fromDateTimeUTC).toString().split('GMT')[0]);
							$('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> '+ new Date(data.toDateTimeUTC).toString().split('GMT')[0]);

						
								if (data.vehicleLocations != null || data.vehicleLocations.length > 0 ){

										var myOptions = {
								
												zoom: Number(locs.zoomLevel),zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
												center: new google.maps.LatLng(data.vehicleLocations[0].latitude, data.vehicleLocations[0].longitude),
												mapTypeId: google.maps.MapTypeId.ROADMAP
											    //styles: [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]
											
											};

                                     scope.map = new google.maps.Map(document.getElementById(attrs.id), myOptions);

								} else {
									alert("Data Not Found!..");
									console.log('vehicleLocations not found!...'+data);
								}

	            			

						    //draw the geo code
							// (function geosite(){
							//	var geoUrl = GLOBAL.DOMAIN_NAME+'/viewSite';
								// var myOptions = {
								// 	zoom: Number(6),zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
								// 	center: new google.maps.LatLng(0,0),
								// 	mapTypeId: google.maps.MapTypeId.ROADMAP
			
				
								// };
								// $scope.map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
						/*		$http.get(geoUrl).success(function(response){
									polygenList =[];
									var latLanlist, seclat, seclan, sp; 

									function centerMarker(listMarker){
									    var bounds = new google.maps.LatLngBounds();
									    for (i = 0; i < listMarker.length; i++) {
									          bounds.extend(listMarker[i]);
									      }
									    return bounds.getCenter()
									}

									for (var listSite = 0; listSite < response.siteParent.length; listSite++) {
										
										var len = response.siteParent[listSite].site.length;
										for (var k = 0; k < len; k++) {
										// if(response.siteParent[i].site.length)
										// {
											var orgName = response.siteParent[listSite].site[k].siteName;
											var splitLatLan = response.siteParent[listSite].site[k].latLng.split(",");
											
											polygenList = [];
											for(var j = 0; splitLatLan.length>j; j++)
								      		{
								      			sp 	  = splitLatLan[j].split(":");
								      			polygenList.push(new google.maps.LatLng(sp[0], sp[1]));
								      			// console.log(sp[0]+' ---- '+ sp[1])
								      			// latlanList.push(sp[0]+":"+sp[1]);
								      			// seclat        = sp[0];
								      			// seclan        = sp[1];
								      		}
											
								      		var polygenColor = new google.maps.Polygon({
								      			path: polygenList,
								      			strokeColor: "#282828",
								      			strokeWeight: 1,
								      			fillColor: '#808080',
								      			fillOpacity: 0.50,
								      			map: scope.map
								      		});
								      	
									      	var labelAnchorpos = new google.maps.Point(19, 0);  ///12, 37
									      	var marker = new MarkerWithLabel({
										      	position: centerMarker(polygenList), 
										      	map: scope.map,
										      	icon: 'assets/imgs/area_img.png',
										      	labelContent: orgName,
										      	labelAnchor: labelAnchorpos,
										      	labelClass: "labels", 
										      	labelInBackground: false
									      	});
									      // scope.map.setCenter(centerMarker(polygenList)); 
									      // scope.map.setZoom(14); 
								  		// }
								  		}
									};
								});
							// }());*/

							google.maps.event.addListener(scope.map, 'click', function(event) {
								scope.clickedLatlng = event.latLng.lat() +','+ event.latLng.lng();
								$('#latinput').val(scope.clickedLatlng);
							});
						    
						    for(var i=0;i<locs.vehicleLocations.length;i++){

						   		scope.path.push(new google.maps.LatLng(locs.vehicleLocations[i].latitude, locs.vehicleLocations[i].longitude));
						   		
						   	  	if(locs.vehicleLocations[i].isOverSpeed=='Y'){
								   	    	var pscolorval = '#ff0000';
								   	   }else{
								   	   		var pscolorval = '#6dd538';
								   	   		// var pscolorval = '#38d552';
								   	   		// var pscolorval = '#068b03';
								   	   }
						   	  scope.polylinearr.push(pscolorval);
						   	  // if(locs.vehicleLocations[i].mileKal=='Y'){
						   	  // 		scope.pointMarker(locs.vehicleLocations[i]);
						   	  //   	scope.pointinfowindow(scope.map, gsmarker[i], locs.vehicleLocations[i]);
						   	  //  }
					   		}

						   	scope.polylineCtrl();
					   		console.log(scope.path.length);

					   		if(scope.getValue != undefined){

					   	    	scope.getValueCheck(scope.getValue);
					      	}
							
		  	  			  	scope.pointDistances=[];
						  	var sphericalLib = google.maps.geometry.spherical;
						  	var pointZero = scope.path[0];
						  
							var wholeDist = sphericalLib.computeDistanceBetween(pointZero, scope.path[scope.path.length - 1]);
							
							// commented by Mayank START
							//alert(scope.path.length);

						  	// for (var i = 0; i < scope.path.length; i++) {
						    //     scope.pointDistances[i] = 100 * sphericalLib.computeDistanceBetween(scope.path[i], pointZero) / wholeDist;
						    // }
							// commented by Mayank END
								
							
						    // window.setTimeout(function () {
						    // 	scope.animated();
						    // },1000);
						    
						    $('#replaybutton').removeAttr('disabled');
			  				// scope.map.fitBounds(latLngBounds);	
			  				
						}else{
							$('.error').show();
							$('#lastseen').html('<strong>From Date & time :</strong> -');
							$('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');
						}
					}
					var url = GLOBAL.DOMAIN_NAME+'/getGeoFenceView?vehicleId='+scope.trackVehID;
		
				scope.createGeofence(url);
				stopLoading();
		   		}).error(function(){ stopLoading();});
		 	});

			 scope.SiteCheckbox = {
       			 value1 : true,
      			 value2 : 'YES'
     			}


    scope.getValueCheck = function(getStatus){

     		scope.getValue = getStatus;

     		    if(scope.getValue == 'YES')
     			{
              	scope.hideMe = false;
              	scope.hideMarker= false;

     			(function(){

                 	var siteLength;

                  	$http.get(geoUrl).success(function(response){
									polygenList =[];
									var latLanlist, seclat, seclan, sp; 

									function centerMarker(listMarker){
									    var bounds = new google.maps.LatLngBounds();
									    for (i = 0; i < listMarker.length; i++) {
									          bounds.extend(listMarker[i]);
									      }
									    return bounds.getCenter()
									}
                                 
                                 if(response.siteParent!=null){
                                    siteLength = response.siteParent.length;
                                    polygenList.push(new google.maps.LatLng(11, 11));
                                  }

                           			  for (var listSite = 0; listSite < siteLength; listSite++) {
										
										var len = response.siteParent[listSite].site.length;
										for (var k = 0; k < len; k++) {
										// if(response.siteParent[i].site.length)
										// {
											var orgName = response.siteParent[listSite].site[k].siteName;
											var splitLatLan = response.siteParent[listSite].site[k].latLng.split(",");
											
											polygenList = [];
											for(var j = 0; splitLatLan.length>j; j++)
								      		{
								      			sp 	= splitLatLan[j].split(":");
								      			polygenList.push(new google.maps.LatLng(sp[0], sp[1]));
								      			// console.log(sp[0]+' ---- '+ sp[1])
								      			// latlanList.push(sp[0]+":"+sp[1]);
								      			// seclat        = sp[0];
								      			// seclan        = sp[1];
								      		}
											
								      		 polygenColors = new google.maps.Polygon({
								      			path: polygenList,
								      			strokeColor: "#282828",
								      			strokeWeight: 1,
								      			fillColor: '#808080',
								      			fillOpacity: 0.50,
								      			//map: scope.map
								      		});
								      		 polygenColors.setMap(scope.map);
								      	
									      	 labelAnchorpos = new google.maps.Point(19, 0);  ///12, 37

									          markers = new MarkerWithLabel({

										      	position: centerMarker(polygenList), 
										     // map: scope.map,
										      	icon: 'assets/imgs/area_img.png',
										      	labelContent: orgName,
										      	labelAnchor: labelAnchorpos,
										      	labelClass: "labels", 
										      	labelInBackground: false
									      	});

									          markers.setMap(scope.map);
									      // scope.map.setCenter(centerMarker(polygenList)); 
									      // scope.map.setZoom(14); 
								  		// }
								  		}
									}
							});
                    }())
     		}
         else if (scope.getValue == 'NO')
         {
           scope.hideMe = true;

           }
        }
      }
    };
}]);

app.controller('mainCtrl',['$scope', '$http', '$q', '$filter','_global',function($scope, $http, $q, $filter, GLOBAL){
	$scope.locations = [];
	$scope.path = [];
	$scope.polylinearr=[];
	$scope.polyline1=[];
	$scope.tempadd01='';
	$scope.cityCircle=[];
	$scope.geoMarkerDetails={};
	$scope.popupmarker;
	$scope.url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
	$scope.url_site    = GLOBAL.DOMAIN_NAME+'/viewSite';
    $scope.vehicle_list=[];
	$scope.markerValue=0;
	var VehiType;
    var vehicIcon=[];

    $('.hideClass').hide();
    $('#pauseButton').hide();
    $('#playButton').hide();      
    $('#stopButton').hide();   
    $('#replayButton').hide(); 

	$scope.getTodayDate  =	function(date) {
		var date = new Date(date);
		return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
	};

    $scope.parseInts=function(data){
     return parseInt(data);
     }
    $scope.alladdress    	=	[];
	$scope.moveaddress    	=	[];
	//$scope.overaddress    =	[];
	$scope.parkaddress     	=	[];
	$scope.idleaddress    	=	[];
//	$scope.fueladdress  	=   [];
	$scope.igniaddress  	=   [];
	//$scope.acc_address   	=   [];
    $scope.stop_address   	=   [];
	// //loading start function
	// $scope.startLoading		= function () {
	// 	$('#status').show(); 
	// 	$('#preloader').show();
	// };

	// //loading stop function
	// $scope.stopLoading		= function () {
	// 	$('#status').fadeOut(); 
	// 	$('#preloader').delay(350).fadeOut('slow');
	// 	$('body').delay(350).css({'overflow':'visible'});
	// };

	function sessionValue(vid, gname){
		sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
		$("#testLoad").load("../public/menu");
	}

	$scope.loading	=	true;
	
	function _pairFilter(_data, _yes, _no, _status)
   	{
   		var _checkStatus =_no ,_pairList 	= [];
   		angular.forEach(_data, function(value, key){
        
   			if(_pairList.length <= 0){
              if(value[_status] == _yes)
              	_pairList.push(value)
            } else if(_pairList.length >0 )
            {
              if(value[_status] == _checkStatus){
                  _pairList.push(value)
                  if(_pairList[_pairList.length-1][_status] == _yes)
                      _checkStatus = _no;
                  else
                      _checkStatus = _yes
              }
            }

   		});

   		if(_pairList.length>1)
	   		if(_pairList.length%2==0)
	   			return _pairList;
	   		else{
	   			 _pairList.pop();
	   			return _pairList;
	   	}

   	}

/*   	function filter(obj){
   		var _returnObj = [];
   		if(obj)
   			angular.forEach(obj,function(val, key){
   				if(val.fuelLitre >0)
   					_returnObj.push(val)
   			})
   		return _returnObj;
   	}*/


   	function filter(obj,name){
   		var _returnObj = [];
   	/*	if(name=='fuel'){
   			angular.forEach(obj,function(val, key){

   				if(val.fuelLitre >0)
   				{
   					_returnObj.push(val)
   				}
   			})
   		}*/
   		
   		if(name=='stoppage'){

   			angular.forEach(obj,function(val, key){

   				if(val.stoppageTime >0)
   				{
   					_returnObj.push(val)
   				}
   			})
   		}
   	/*	else if(name=='ovrspd'){

   			angular.forEach(obj,function(val, key){

   				if(val.overSpeedTime >0)
   				{
   					_returnObj.push(val)
   				}
   			})
   		}*/
   	return _returnObj;
   	}

/*  	$scope.fuelChart 	= 	function(data){
		var ltrs 		=	[];
		var fuelDate 	=	[];
		try{
			if(data.length)
				for (var i = 0; i < data.length; i++) {
					if(data[i].fuelLitre !='0' || data[i].fuelLitre !='0.0')
					{
						ltrs.push(data[i].fuelLitre);
						var dar = $filter('date')(data[i].date, "dd/MM/yyyy HH:mm:ss");
						fuelDate.push(dar)
					}
				};
		}
		catch (err){
			console.log(err.message)
		}
		
	$(function () {
   
        $('#container').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Fuel Report'
            },
          
             xAxis: {
            categories: fuelDate
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
                name: 'Fuel Level',
                data: ltrs
            }]
        });

});
	}


	$scope.speedKms 	= 	function(data){
		var ltrs 		=	[];
		var fuelDate 	=	[];
		try{
			if(data.length)
				for (var i = 0; i < data.length; i++) {
					if(data[i].speed !='0')
					{
						ltrs.push(data[i].speed);
						var dar = $filter('date')(data[i].date, "dd/MM/yyyy HH:mm:ss");
						fuelDate.push(dar)
					}
				};
		}
		catch (err){
			console.log(err.message)
		}
		
	$(function () {
   
        $('#speedGraph').highcharts({
            chart: {
                zoomType: 'x'
            },
        title: {
            text: ''
        },
       subtitle: {
            text: 'Speed km/h'
        },
        xAxis: {
            categories: fuelDate
        },
        yAxis: {
            title: {
                text: ''
            }
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
        // plotOptions: {
        //     line: {
        //         dataLabels: {
        //             enabled: false
        //         },
        //         enableMouseTracking: true
        //     }
        // },
        // legend: {
        //     enabled: false
        // },

        series: [{
        	type: 'area',
            name: 'km/h',
            data: ltrs
        }]
        });

	});
}
*/
	$scope._tableValue = function(_value){
		// if(_value && _value.vehicleLocations != null){
		$scope.moveaddress    	=	[];
		$scope.alladdress    	=	[];
	//	$scope.overaddress    	=	[];
		$scope.parkaddress     	=	[];
		$scope.idleaddress    	=	[];
	//	$scope.fueladdress  	=   [];
		$scope.igniaddress  	=   [];
	//	$scope.acc_address   	=   [];
		$scope.stop_address     =   [];
		$scope.parkeddata		=	[];
	//	$scope.overspeeddata	=	[];
	    $scope.allData          =	[];
		$scope.movementdata		=	[];
		$scope.idlereport       =   [];
		$scope.ignitValue 		= 	[];
	//	$scope.acReport 		=	[];
		$scope.stopReport 		=	[];
	//	$scope.fuelValue 		= 	[];

		if(_value && _value.vehicleLocations != null) {

			var ignitionValue		= 	($filter('filter')(_value.vehicleLocations, {'ignitionStatus': "!undefined"}))

			$scope.parkeddata		=	($filter('filter')(_value.vehicleLocations, {'position':"P"}));
		//	$scope.overspeeddata	=	($filter('filter')(_value.vehicleLocations, {'isOverSpeed':"Y"}));
		//	$scope.overspeeddata	=	filter(_value.vehicleLocations,'ovrspd');
		    $scope.allData          =   ($filter('filter')(_value.vehicleLocations, {}));
			$scope.movementdata		=	($filter('filter')(_value.vehicleLocations, {'position':"M"}));
			$scope.idlereport       =   ($filter('filter')(_value.vehicleLocations, {'position':"S"}));
			$scope.ignitValue 		= 	_pairFilter(ignitionValue, 'ON', 'OFF', 'ignitionStatus');
		//	$scope.acReport 		=	_pairFilter(_value.vehicleLocations, 'yes', 'no', 'vehicleBusy');
		//	$scope.fuelValue 		= 	filter(_value.vehicleLocations);
		//  $scope.fuelValue 		= 	filter(_value.vehicleLocations,'fuel');
			$scope.stopReport 		=   filter(_value.vehicleLocations,'stoppage');
			
			// console.log($scope.ignitValue);
		}
	//	$scope.speedKms($scope.movementdata);
	//	$scope.fuelChart($scope.fuelValue);
}

	function google_api_call(tempurlMo, index1, _stat) {
			// console.log(' temperature ')
			// 	$scope.av = "adasdas"
	// 	$scope.moveaddress    	=	[];
	// $scope.overaddress    	=	[];
	// $scope.parkaddress     	=	[];
	// $scope.idleaddress    	=	[];
	// $scope.fueladdress  	=   [];
	// $scope.igniaddress  	=   [];
	// $scope.acc_address   	=   [];
		$http.get(tempurlMo).success(function(data){
			// $.ajax({

   //          async: false,
   //          method: 'GET', 
   //          url: tempurlMo,
   //          success: function (data) {
               // console.log(data)
                // aUthName = response;
                // sessionStorage.setItem('apiKey', JSON.stringify(aUthName[0]));
                // sessionStorage.setItem('userIdName', JSON.stringify('username'+","+aUthName[1]));
                switch (_stat){
				case 'all':
					console.log(_stat);
					$scope.alladdress[index1] = data.results[0].formatted_address;
				break;
				case 'movement':
					console.log(_stat);
					$scope.moveaddress[index1] = data.results[0].formatted_address;
				break;
				case 'parked':
					console.log(_stat);
					$scope.parkaddress[index1] = data.results[0].formatted_address;
				break;
				case 'idle':
					console.log(_stat);
					$scope.idleaddress[index1] = data.results[0].formatted_address;
				break;
				case 'ignition':
					console.log(_stat);
					$scope.igniaddress[index1] = data.results[0].formatted_address;
				break;
			    case 'stoppage':
					console.log(_stat);
					$scope.stop_address[index1] = data.results[0].formatted_address;
				break;
			}
        //     }
        // })
			
			// $scope.moveaddress[index1] = data.results[0].formatted_address;
			// var t = vamo_sysservice.geocodeToserver(latMo,lonMo,data.results[0].formatted_address);
		})
	};
	
var queue1 = [];
	var delaying = (function () {
  		
  		

	  	function processQueue1() {
		    if (queue1.length > 0) {
		      setTimeout(function () {
		        queue1.shift().cb();
		        processQueue1();
		      }, queue1[0].delay);
		    }
	  	}

	  	return function delayed(delay, cb) {
	    	queue1.push({ delay: delay, cb: cb });

	    	if (queue1.length === 1) {
	      	processQueue1();
	    	}
	  	};

	}());

	$scope.recursive   = function(location_over, _stat, _address){
   		// console.log(va)
   		var indexs = 0;
   		angular.forEach(location_over, function(value, primaryKey){
    		indexs = primaryKey;
    		if(location_over[indexs].address == undefined && _address[indexs] == undefined)
				{
					//console.log(' address over speed'+indexs)
					var latOv		 =	location_over[indexs].latitude;
				 	var lonOv		 =	location_over[indexs].longitude;
					var tempurlOv	 =	"https://maps.googleapis.com/maps/api/geocode/json?latlng="+latOv+','+lonOv+"&sensor=true";
					//console.log(' in overspeed '+indexs)
					delaying(3000, function (indexs) {
					      return function () {
					        google_api_call(tempurlOv, indexs, _stat);
					      };
					    }(indexs));
				}
    	})
	}

	$scope.addressResolve 	= function(tabVal){
		
		queue1 = [];
		switch (tabVal){
			case 'all':
				$scope.recursive($scope.allData, tabVal, $scope.alladdress);
			break;

            case 'movement':
				$scope.recursive($scope.movementdata, tabVal, $scope.moveaddress);
			break;

			case 'parked': 
				$scope.recursive($scope.parkeddata, tabVal, $scope.parkaddress);
			break;

			case 'idle':
				$scope.recursive($scope.idlereport, tabVal, $scope.idleaddress);
			break;

			case 'ignition':
				$scope.recursive($scope.ignitValue, tabVal, $scope.igniaddress);
			break;

			case 'stoppage':
				$scope.recursive($scope.stopReport, tabVal, $scope.stop_address);
			break;
		}
	}

	// $http.get($scope.url).success(function(data){
		
	// 	$scope.locations = data;
	// 	$scope.groupname = data[0].group;
	// 	$scope.vehicleId = data[0].vehicleLocations[0].vehicleId;
	// 	sessionValue($scope.vehicleId, $scope.groupname)
	// 	if(getParameterByName('vehicleId')=='' && getParameterByName('gid')==''){
	// 		$scope.trackVehID =$scope.locations[0].vehicleLocations[3].vehicleId;
	// 		$scope.shortVehiId =$scope.locations[0].vehicleLocations[3].shortName;
	// 		$scope.selected=0;
	// 	}else{
	// 		$scope.trackVehID =getParameterByName('vehicleId');
	// 		for(var i=0; i<$scope.locations[0].vehicleLocations.length;i++){
	// 			if($scope.locations[0].vehicleLocations[i].vehicleId==$scope.trackVehID){
	// 				$scope.selected=i;
	// 			}
	// 		}
	// 	}
	// }).error(function(){ /*alert('error'); */});

	$scope.getOrd 	= function()
	{
		$scope.error = "";
		$scope.routeName = "";
		$scope.selectedOrdId = "";

		getRouteNames();
	}

	function showRoutes(setRoute){

		if(setRoute != '' && setRoute == 'routes'){
			$("#myModal1").modal();
			$scope.getOrd();
		}
	}

	function addZero(i) {
      if (i < 10) {
        i = "0" + i;
      }
    return i;
    }

   function timeNow24Hrs(){

     var d = new Date();
     var h = addZero(d.getHours());
     var m = addZero(d.getMinutes());
     var s = addZero(d.getSeconds());

    //return h + ":" + m + ":" + s;
   return h+":00:00";
   }

   function getTodayDatess() {
     var date = new Date();
     return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
   };

    
    (function init(){
		startLoading()
		var url;
		$scope.groupname = getParameterByName('gid');
		url =(getParameterByName('vehicleId')=='' && getParameterByName('gid')=='')?$scope.url:$scope.url+'?group='+$scope.groupname;
		$http.get(url).success(function(response){

			$scope.locations 	= response;

			if (getParameterByName('gid') == '' && getParameterByName('vehicleId') == '') {

				$scope.groupname 	= response[0].group;
				$scope.trackVehID 	= $scope.locations[0].vehicleLocations[0].vehicleId;
				$scope.shortVehiId 	= $scope.locations[0].vehicleLocations[0].shortName;
				$scope.selected 	= 0;
		      //$('#vehiid h3').text($scope.shortVehiId);
				VehiType = $scope.locations[0].vehicleLocations[0].vehicleType;
			
			} else { 

				$scope.trackVehID  	= getParameterByName('vehicleId');
				angular.forEach(response, function(value, key){
					if($scope.groupname 	== value.group)
					{	
						angular.forEach(value.vehicleLocations, function(val, k){

                            $scope.vehicle_list.push({'vehiID' : val.vehicleId, 'vName' : val.shortName});

							if(val.vehicleId == $scope.trackVehID){
								$scope.selected 	=	k;
								$scope.shortVehiId  = val.shortName;
							  //$('#vehiid h3').text(val.shortName);
								VehiType = val.vehicleType;
							}
						})
					//$scope.locations = response;
					}	
				})

			}
		sessionValue($scope.trackVehID, $scope.groupname)
	  //$scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID;
         

			var todayTime = new Date();
				var currentTimeRaw = new Date();
				
				todayTime.setHours(todayTime.getHours()-1);
    			todayTime.setMinutes(todayTime.getMinutes()-59);
    			var timeShow = $filter('date')(todayTime, 'HH:mm:ss');

        $scope.fromTimes = timeShow; 
      //$scope.toTimes   = timeNow24Hrs();
        $scope.fromDates = getTodayDatess();
      //$scope.toDates   = getTodayDatess();
        
         if((checkXssProtection($scope.fromDates) == true) && (checkXssProtection($scope.fromTimes) == true)){
		   //$scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+$scope.fromDates+'&fromTime='+$scope.fromTimes+'&toDate='+$scope.toDates+'&toTime='+$scope.toTimes+'&fromDateUTC='+utcFormat($scope.fromDates,$scope.fromTimes)+'&toDateUTC='+utcFormat($scope.toDates,$scope.toTimes);
		     $scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+$scope.fromDates+'&interval=1&fromTime='+$scope.fromTimes+'&fromDateUTC='+utcFormat($scope.fromDates,$scope.fromTimes);
		 }

		$('.nav-second-level li').eq(0).children('a').addClass('active');
		// stopLoading();
		})
		// } else{

		// }
		
	}());

	$scope.trimColon = function(textVal){
		return textVal.split(":")[0].trim();
	}

	$scope.createGeofence=function(url){
		//console.log('--->'+url)
		if($scope.cityCirclecheck==false){
			$scope.cityCirclecheck=true;
		}
		if($scope.cityCirclecheck==true){
			for(var i=0; i<$scope.cityCircle.length; i++){
				$scope.cityCircle[i].setMap(null);
			}
			for(var i=0; i<geomarker.length; i++){
				geomarker[i].setMap(null);
				geoinfo[i].setMap(null);
			}
		}
		var defdata = $q.defer();
		$http.get(url).success(function(data){
			//console.log(' url '+data)
			$scope.geoloc = defdata.resolve(data);
			$scope.geoStop = data;
			geomarker=[];
			if (typeof(data.geoFence) !== 'undefined' ) {	
				
				if(data.geoFence!=null){
				for(var i=0; i<data.geoFence.length; i++){
					if(data.geoFence[i]!=null){
					var populationOptions = {
							  strokeColor: '#FF0000',
							  strokeOpacity: 0.8,
							  strokeWeight: 2,
							  fillColor: '#FF0000',
							  fillOpacity: 0.02,
							  map: $scope.map,
							  center: new google.maps.LatLng(data.geoFence[i].latitude,data.geoFence[i].longitude),
							  radius: parseInt(data.geoFence[i].proximityLevel)
					};
					
					$scope.cityCircle[i] = new google.maps.Circle(populationOptions);
					var centerPosition = new google.maps.LatLng(data.geoFence[i].latitude, data.geoFence[i].longitude);
					var labelText = data.geoFence[i].poiName;
					$scope.infowin(data.geoFence[i]);
					
					var myOptions = {
						content: labelText,
						boxStyle: {textAlign: "center", fontSize: "8pt", fontColor: "#0031c4", width: "100px"
						},
						disableAutoPan: true,
						pixelOffset: new google.maps.Size(-50, 0),
						position: centerPosition,
						closeBoxURL: "",
						isHidden: false,
						pane: "mapPane",
						enableEventPropagation: true
					};

					  var labelinfo = new InfoBox(myOptions);
					  labelinfo.open($scope.map);
					  labelinfo.setPosition($scope.cityCircle[i].getCenter());
				     }
				}
			}
		}
	});
}

	$scope.goeValueChange = function()
	{
		//$scope.geoStops;
		//console.log(' ---- >'+$scope.geoStops)
		$scope.map.setZoom(13)
		if($scope.geoStops!=0)
		{
			for(var i = 0; i < $scope.geoStop.geoFence.length; i++)
			{
				if($scope.geoStops==$scope.geoStop.geoFence[i].stopName)
				{
					//$scope.map.setZoom(18);
					$scope.map.setCenter(geomarker[i].getPosition());
					animateMapZoomTo($scope.map, 20);
			 	}
			}
		}
	}

 // getting Org ids
	$scope.$watch("url_site", function (val) {

	    $http.get($scope.url_site).success(function(response){
		    $scope.orgIds 	= response.orgIds;
		    showRoutes(getParameterByName('rt'))
	    });
	});
	
	function getRouteNames(){

		if($scope.orgIds)
			$.ajax({
				
				async: false,
		        method: 'GET', 
		        url: "storeOrgValues/val",
		        data: {"orgId":$scope.orgIds},
		        success: function (response) {

		        	$scope.routedValue = response;
 
		        }
	      	})
	}

    
	
    $scope.getMap = function(routesMap){

       $scope.windowRouteName=routesMap;
       
       if(!routesMap =='' && $scope.orgIds.length>0 && !$scope.orgIds==''){
             $.ajax({
                       async:false,
                       method:'GET',
                       url:"storeOrgValues/mapHistory",
                       data:{"maproute":routesMap,"organId":$scope.orgIds},
                       success:function(response){

                                 $scope.mapValues =response;

                                 getMapArray($scope.mapValues);
                       }

               })
        }        
    }

var latLanpath=[];
var markerList=[];
var pathCoords=[];


function clearMap(path){
		
	for (var i=0; i<latLanpath.length; i++){
    	latLanpath[i].setMap(null);

   	}

   	for (var i = 0; i < markerList.length; i++) {
   		markerList[i].setMap(null);
   	}
}

function getMapArray(values){

    var latSplit ;
   	var latLangs=values;
   	clearMap(pathCoords);
    pathCoords=[];

  	for(i=0;i<latLangs.length;i++){

        latSplit = latLangs[i].split(",");
        pathCoords.push({"lat": latSplit[0],"lng": latSplit[1]});
    }

	dvMap.setCenter(new google.maps.LatLng(pathCoords[0].lat,pathCoords[0].lng)); 
	autoRefresh(dvMap);
}

function myMap(){

	var mapCanvas=document.getElementById("dvMap");
	var mapOptions={
		center: new google.maps.LatLng(0,0),
	    zoom: 8,
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	dvMap=new google.maps.Map(mapCanvas,mapOptions);
}
myMap();

$('#myModal2').on('shown.bs.modal', function () {
	google.maps.event.trigger(dvMap, "resize");
});

function moveMarker(marker, latlng) {
	
	marker.setPosition(latlng);
}

function autoRefresh(map) {
	var i, route, marker;
	
	route = new google.maps.Polyline({
		path: [],
		geodesic : true,
		strokeColor: '#FF0000',
		strokeOpacity: 1.0,
		strokeWeight: 2,
		editable: false,
		map:map
	});
                
	latLanpath.push(route);
    marker = new google.maps.Marker({map:map,icon:""});
    markerList.push(marker);
	for (i=0; i<pathCoords.length; i++) {
		
		setTimeout(function (coords)
			{
				var latlng = new google.maps.LatLng(coords.lat, coords.lng);
				route.getPath().push(latlng);
                moveMarker( marker, latlng);
                map.panTo(latlng);
            
            }, 0* i, pathCoords[i]);
		}
}
    /*
		show table in view
	*/
$( "#historyDetails" ).hide();
	$scope.hideShowTable 	= function(){
		var btValue = ($("#btnValue").text()=='ShowDetails')?'HideDetails':'ShowDetails';
		$('#btnValue').text(btValue);
		$( "#historyDetails" ).fadeToggle("slow");
	}
 
	/*
		Store the routes in redis
	*/
	$scope.routesSubmit =  function(){
		$scope.error = "";
		console.log(' get org ids ')
		var fromdate 	= document.getElementById('dateFrom').value;
		var todate 		= document.getElementById('dateTo').value;
		var fromtime 	= document.getElementById('timeFrom').value;
		var totime 		= document.getElementById('timeTo').value;
		if((checkXssProtection(fromdate) == true) && (checkXssProtection(todate) == true) && (checkXssProtection(fromtime) == true) && (checkXssProtection(totime) == true) && (checkXssProtection($scope.routeName) == true))
			try{
				$scope.error = (!fromdate || !todate || !fromtime || !totime)? "Date Required/Please fill all the field":  "";
				if($scope.error == "")
					{
						var utcFrom 	= utcFormat(fromdate, $scope.timeconversion(fromtime));
						var utcTo 		= utcFormat(todate, $scope.timeconversion(totime));
						var _routeUrl 	= GLOBAL.DOMAIN_NAME+'/addRoutesForOrg?vehicleId='+$scope.trackVehID+'&fromDateUTC='+utcFrom+'&toDateUTC='+utcTo+'&routeName='+removeSpace_Join($scope.routeName);
						if(!$scope.trackVehID == "" && !$scope.routeName == "")
							$http.get(_routeUrl).success(function(response){
								if(response.trim()== "false"){
									$scope.error = "* Already having this Route Name"
								} else if (response.trim()== "true"){
									$scope.error = "* Successfully Stored"
								} else
									$scope.error = "* Try again"
							})
						else
							throw $scope.error;

					} else 
						throw $scope.error;
					getRouteNames();
			}catch(err){
				console.log(' error --> '+err)
				$scope.error  = "* Date Required/Please fill all the field";
			}
	}

	$scope.popUpMarkerNull =function()
	{
		if($scope.popupmarker !== undefined){
	    	$scope.popupmarker.setMap(null);
		    $scope.infowindow.close();
			$scope.infowindow .setMap(null);
		}
	}

	
	$scope.markerPoup 	= 	function(val)
	{
		$scope.popUpMarkerNull();
        $scope.popupmarker = new google.maps.Marker({
	        icon: 'assets/imgs/popup.png',
	  	});

        var latLngs=new google.maps.LatLng(val.latitude, val.longitude);

        $scope.popupmarker.setMap($scope.map);
        $scope.popupmarker.setPosition(latLngs);

        var contentString = '<div style="padding:2px; padding-top:3px; width:190px;">'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;color:#666463;">Date&amp;Time</b> <span style="padding-left:9px;">-</span> <span style="font-size:10px;padding-left:3px;color:#666463;font-weight:bold;border-bottom:0.5px;">'+dateFormat(val.date)+'</span></div>'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;color:#666463;">Speed</b> <span style="padding-left:9px;">-</span> <span style="font-size:10px;padding-left:3px;color:#666463;font-weight:bold;border-bottom:0.5px;">'+ val.speed +'</span><span style="font-size:10px;padding-left:10px;border-bottom:0.5px;">kmph</span></div>'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;color:#666463;">OdoDist</b> <span style="padding-left:9px;">-</span> <span style="font-size:10px;padding-left:3px;color:#666463;font-weight:bold;border-bottom:0.5px;">'+val.odoDistance+'</span><span style="font-size:10px;padding-left:10px;border-bottom:0.5px;">kms</span></div>'
			+'<div style="text-align:center;padding-top:3px;"><span style="font-size:10px;width:100px;">'+$scope.trimComma(val.address)+'</span></div>'
		 // +'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
			+'</div>';

	    /* $scope.infowindow = new InfoBubble({
	  		maxWidth: 400,	
			maxHeight:170,
	  		content: contentString,
		});*/

		$scope.infowindow = new google.maps.InfoWindow({ maxWidth: 200,	maxHeight:150 });

		$scope.infowindow.setContent(contentString); 
        $scope.infowindow.setPosition(latLngs);
        $scope.infowindow.open($scope.map, $scope.popupmarker);

	    google.maps.event.addListener($scope.infowindow,'closeclick',function(){
		   $scope.popUpMarkerNull();
		}); 

	}

	$scope.deleteRouteName 	= function(deleteValue){
	 // $scope.routeName = deleteValue;
		console.log(deleteValue);
		try{

			if(!deleteValue =='' && $scope.orgIds.length>0 && !$scope.orgIds=='')
				$.ajax({
					
					async: false,
			        method: 'GET', 
			        url: "storeOrgValues/deleteRoutes",
			        data: {"delete":deleteValue, "orgIds":$scope.orgIds},
			        success: function (response) {

			        	// $scope.routedValue = response;
	 
			        }
		    })
		    getRouteNames();
		    $scope.error =	"* Deleted Successfully"

		} catch (err){

			$scope.error =	"* Not Deleted Successfully"
		}
		// console.log($(this).parent().parent().find('td').text())
		// console.log($('.editAction').closest('tr').children('td:eq(0)').text());

	}

	

$('.dynData').on("click", "#editAction", function(event){
    var target 		= $(this).closest('tr').children('td:eq(0)')
    $scope.error 	=	""
    // $(target).html($('<input />',{'value' : target.text()}).val(target.text()));

    // $(this).siblings().each(
    //     function(){
            // if the td elements contain any input tag
            if(!target.text() == '');
            if ($(target).find('input').length){
                // sets the text content of the tag equal to the value of the input
                $(target).text($(target).find('input').val());
            }
            else {
                // removes the text, appends an input and sets the value to the text-value
                var t = $(target).text();
                $(target).html($('<input />',{'value' : target.text()}).val(target.text()));
            }
        // });
    
});

	$('.dynData').on("change", $(this).closest('tr').children('td:eq(0) input'), function(event){
		console.log(' new value '+event.target.value)
		$scope.error =	""
		var _newValue = event.target.value;
		var _oldValue = event.target.defaultValue;
		try{

			if(!_newValue =='' && $scope.orgIds.length>0 && !$scope.orgIds=='')
				$.ajax({
					
					async: false,
			        method: 'GET', 
			        url: "storeOrgValues/editRoutes",
			        data: {"newValue":_newValue, "oldValue":_oldValue, "orgIds":$scope.orgIds},
			        success: function (response) {

			        	// $scope.routedValue = response;
			        	$scope.error =	"* Edited Successfully"
	 
			        }
		    })

		} catch (err){

			$scope.error =	"* Not Edited Successfully"
		}
		

	})

 // $(document).ready(function() {
 //                $('.dynData table tbody tr td input').change(function() {
 //                    var rowEdit = $(this).parents('tr');
 //                    alert(rowEdit);
 //                    console.log($(rowEdit));
 //                    $(rowEdit).children('.sub').html('Success');
 //                })
 //            })

function animateMapZoomTo(map, targetZoom) {
    var currentZoom = arguments[2] || map.getZoom();
    if (currentZoom != targetZoom) {
        google.maps.event.addListenerOnce(map, 'zoom_changed', function (event) {
            animateMapZoomTo(map, targetZoom, currentZoom + (targetZoom > currentZoom ? 1 : -1));
        });
        setTimeout(function(){ map.setZoom(currentZoom) }, 80);
    }
}

function smoothZoom (map, max, cnt) {
    if (cnt >= max) {
            return;
        }
    else {
        z = google.maps.event.addListener(map, 'zoom_changed', function(event){
            google.maps.event.removeListener(z);
            smoothZoom(map, max, cnt + 1);
        });
        setTimeout(function(){map.setZoom(cnt)}, 80);
    }
}  

	$scope.infowin = function(data){
		
		var centerPosition = new google.maps.LatLng(data.latitude, data.longitude);
					var labelText = data.poiName;
					var stop = data.stopName;
					var image = 'assets/imgs/busgeo.png';
				  
				  	var beachMarker = new google.maps.Marker({
				      position: centerPosition,
				      title: stop,
				      map: $scope.map,
				      icon: image
				  	});
				  	geomarker.push(beachMarker);
					// var myOptions = { content: labelText, boxStyle: {textAlign: "center", fontSize: "9pt", fontColor: "#ff0000", width: "100px"},
						// disableAutoPan: true,
						// pixelOffset: new google.maps.Size(-50, 0),
						// position: centerPosition,
						// closeBoxURL: "",
						// isHidden: false,
						// pane: "mapPane",
						// enableEventPropagation: true
					// };
					// var labelinfo = new InfoBox(myOptions);
					// labelinfo.open($scope.map);simple
					// labelinfo.setPosition($scope.cityCircle[i].getCenter());
					// geoinfo.push(labelinfo);
										
					var contentString = '<h3 class="infoh3">Location</h3>'
					+'<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody>'
					+'<tr><td>Latlong</td><td>'+data.geoLocation+' </td></tr>'+'<tr><td>StopName</td><td>'+data.stopName+' </td></tr>'
					+'</table></div>';
					var infoWindow = new google.maps.InfoWindow({content:contentString});
					geoinfowindow.push(infoWindow);
					
					(function(marker) {
			    		google.maps.event.addListener(beachMarker, "click", function(e) {
			    			for(var j=0; j<geoinfowindow.length;j++){
								geoinfowindow[j].close();
							}
			    			infoWindow.open($scope.map, marker);
							
			   			});	
			  		})(beachMarker);
	}
	
	
	$scope.genericFunction = function(a,b,shortName){
		startLoading();
		$scope.path = [];
		gmarkers=[];
		ginfowindow=[];
		contentString = [];
		$scope.trackVehID = a;
		$scope.shortVehiId = shortName;
		$scope.selected = b;
		$scope.plotting();
		sessionValue($scope.trackVehID, $scope.groupname);
		// stopLoading();
	}
	
	$scope.groupSelection = function(groupname, groupid){
	
		startLoading();
		 $scope.selected=0;
		 $scope.url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group=' + groupname;
		 $scope.gIndex = groupid;
		 gmarkers=[];
		 ginfowindow=[];
		 $scope.loading	=	true;
		 
		 for(var i=0; i<gmarkers.length; i++){
				gmarkers[i].setMap(null);
			}
			
			if($scope.polyline){
			  
			  for(var i=0; i<$scope.polyline1.length; i++){
				$scope.polyline1[i].setMap(null);
			  }
			
				$scope.polyline.setMap(null);
            }

            if($scope.markerstart){
			  
			  $scope.markerstart.setMap(null);
			  $scope.markerend.setMap(null);
			  $scope.path = [];
			  $scope.polylinearr = [];
			  gmarkers=[];
			  ginfowindow=[];
		      contentString = [];
			  gsmarker=[];
			  gsinfoWindow=[];
			  window.clearInterval(id);
			  $('#replaybutton').attr('disabled','disabled');
			  $('#lastseen').html('<strong>From Date & time :</strong> -');
			  $('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');
            }

		    $http.get($scope.url).success(function(data){
			   
			   $scope.locations = data;
			   
			   if(data.length)
				$scope.vehiname	= data[$scope.gIndex].vehicleLocations[0].vehicleId;
			    $scope.groupname 	= data[$scope.gIndex].group;
			    $scope.trackVehID 	= $scope.locations[$scope.gIndex].vehicleLocations[$scope.selected].vehicleId;
			    sessionValue($scope.trackVehID, $scope.groupname);
			    $scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID;
			    $('.nav-second-level li').eq(0).children('a').addClass('active');
			    $scope.loading	=	false;
						
                //stopLoading();
		    }).error(function(){ stopLoading();});
	}

	$scope.pointMarker=function(data){
	
		var line0Symbol = {
	        path: google.maps.SymbolPath.CIRCLE,
	        scale:3,
	        strokeColor: '#0645AD',
	        strokeWeight: 5
	    };
	    
	    var marker = new google.maps.Marker({
            map: $scope.map,
            position: new google.maps.LatLng(data.latitude, data.longitude),
            icon:line0Symbol
	    });
	
	    gsmarker.push(marker);
	
	    var contentString01 = '<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody></tbody></table></div>';
			var infoWindow = new google.maps.InfoWindow({content: contentString01});
			gsinfoWindow.push(infoWindow);	
			
			(function(marker) {
			    google.maps.event.addListener(marker, "click", function(e) {
			   	$scope.infowindowShow={};
				$scope.infowindowShow['dataTempVal'] =  data;
				$scope.infowindowShow['currinfo'] = infoWindow;
				$scope.infowindowShow['currmarker'] = marker;
				for(var j=0; j<gsinfoWindow.length;j++){
					gsinfoWindow[j].close();
				}
				$scope.infowindowshowFunc();
			   });	
			  })(marker);
	}

	$scope.infowindowshowFunc = function(){
		for(var j=0; j<gsinfoWindow.length;j++){
			gsinfoWindow[j].close();
		}
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(+$scope.infowindowShow.dataTempVal.latitude, +$scope.infowindowShow.dataTempVal.longitude);
		geocoder.geocode({'latLng': latlng}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
					  if (results[1]) {
					  	var contentString = '<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody>'
			+'<tr><td>Speed </td><td>'+$scope.infowindowShow.dataTempVal.speed+'</td></tr>'
			+'<tr><td>date & time</td><td>'+dateFormat($scope.infowindowShow.dataTempVal.date)+'</td></tr>'
			+'<tr><td>trip distance</td><td>'+$scope.infowindowShow.dataTempVal.tripDistance+'</td></tr>'
			+'<tr><td style="widthpx">location</td><td style="width:100px">'+results[1].formatted_address+'</td></tr>'
			+'</tbody></table></div>';
						$scope.infowindowShow.currinfo.setContent(contentString);
						$scope.infowindowShow.currinfo.open($scope.map,$scope.infowindowShow.currmarker);
					  }
					  
					}
		});
	}

	$scope.pointinfowindow=function(map, marker, data){
		
			if(gsmarker.length>0){
	   }
	}

	$scope.locationname="";
	$scope.getLocation = function(lat,lon, callback){
		var tempurl01 =  "https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon;
		var t = $.ajax({
			dataType:"json",
			url: tempurl01,
			success:function(data){
				
				if(data.results[0]!=undefined){
				if(data.results[0].formatted_address==undefined){
					if(typeof callback === "function") callback('');
				}else{
					if(typeof callback === "function") callback(data.results[0].formatted_address);
				}
				}else{
					if(typeof callback === "function") callback('');
				}
			},
			error:function(xhr, status, error){
				//if(typeof callback === "function") callback('');
				console.log('error:' + status + error);
			}
		});
		
    };	
    
	$scope.timeCalculate = function(duration){
		var milliseconds = parseInt((duration%1000)/100), seconds = parseInt((duration/1000)%60);
		var minutes = parseInt((duration/(1000*60))%60), hours = parseInt((duration/(1000*60*60))%24);
		
		hours = (hours < 10) ? "0" + hours : hours;
		minutes = (minutes < 10) ? "0" + minutes : minutes;
		seconds = (seconds < 10) ? "0" + seconds : seconds;
		temptime = hours + " H : " + minutes +' M : ' +seconds +' S';

		var s=duration;
		function addZ(n) {
		    return (n<10? '0':'') + n;
		}

		var ms = s % 1000;
		s = (s - ms) / 1000;
		var secs = s % 60;
		s = (s - secs) / 60;
		var mins = s % 60;
		var hrs = (s - mins) / 60;

		var tempTim = addZ(hrs) + ' H : ' + addZ(mins) + ' M : ' + addZ(secs) + ' S '; //+ ms;
		return tempTim;
	}

	// function parseDate(str) {
	//     var mdy = str.split('/');
	//     return new Date(mdy[2], mdy[0]-1, mdy[1]);
	// }
	
	$scope.msToTime		=	function(ms) {
        days = Math.floor(ms / (24*60*60*1000));
	    daysms=ms % (24*60*60*1000);
	    hours = Math.floor((ms)/(60*60*1000));
	    hoursms=ms % (60*60*1000);
	    minutes = Math.floor((hoursms)/(60*1000));
	    minutesms=ms % (60*1000);
	    sec = Math.floor((minutesms)/(1000));
	    return hours+":"+minutes+":"+sec;
    }

	function utcdateConvert(milliseconds){
		//var milliseconds=1440700484003;
		var offset='+10';
		var d = new Date(milliseconds);
		utc = d.getTime() + (d.getTimezoneOffset() * 60000);
		nd = new Date(utc + (3600000*offset));
		var result=nd.toLocaleString();
		return result;
	}

	$scope.timeconversion= function(time){
		var time = time;
		var hours = Number(time.match(/^(\d+)/)[1]);
		var minutes = Number(time.match(/:(\d+)/)[1]);
		var AMPM = time.match(/\s(.*)$/)[1];
		if(AMPM == "PM" && hours<12) hours = hours+12;
		if(AMPM == "AM" && hours==12) hours = hours-12;
		var sHours = hours.toString();
		var sMinutes = minutes.toString();
		if(hours<10) sHours = "0" + sHours;
		if(minutes<10) sMinutes = "0" + sMinutes;
		return sHours+":"+sMinutes+":00";
	}


    $scope.showPlot = function(totime,todate,fromtime,fromdate)
	{
		$scope.hideButton = false;
	}

	$scope.plotting = function(val,parameter){

		$scope.hideButton = true;

		(function(){
			$scope.hideButton = false;
		},5000);

		if (val != 1){
			var fromdate = document.getElementById('dateFrom').value;
			var todate = document.getElementById('dateTo').value;
		}

		if((checkXssProtection(fromdate) == true) && (checkXssProtection(todate) == true) && (checkXssProtection(document.getElementById('timeTo').value) == true) && (checkXssProtection(document.getElementById('timeFrom').value) == true)){
			startLoading();
		//	$scope.popUpMarkerNull();
			$scope.hisurlold = $scope.hisurl;

            if (val ==1){

				var todayTime = new Date();
				var currentTimeRaw = new Date();
				var currentTime = $filter('date')(currentTimeRaw,'HH:mm:ss');
				var fromDateRaw = $filter('date')(currentTimeRaw,'yyyy-MM-dd');
				todayTime.setHours(todayTime.getHours()-4);
    			todayTime.setMinutes(todayTime.getMinutes()-59);
    			$scope.HHmmss = $filter('date')(todayTime, 'HH:mm:ss');
    			
    			var fromtime = $scope.HHmmss;
    			var totime = currentTime ;
    			var fromdate = fromDateRaw;
    			var todate = fromDateRaw;

			} else if (val == 2) {	

				var todayTime  = new Date();
				var newYesDate = new Date();

				var currentTimeRaw = new Date();
				var currentTime = $filter('date')(currentTimeRaw,'HH:mm:ss');
				var fromDateRaw = $filter('date')(currentTimeRaw,'yyyy-MM-dd');
				todayTime.setHours(todayTime.getHours()-11);
    			todayTime.setMinutes(todayTime.getMinutes()-59);
    			var previousDate = newYesDate.setDate(newYesDate.getDate()-1);
    			$scope.HHmmss = $filter('date')(todayTime, 'HH:mm:ss');
    			// alert($scope.HHmmss);

    			var fromtime = $scope.HHmmss;
    			var totime = currentTime ;
    			var fromdate = $filter('date')(previousDate,'yyyy-MM-dd');
    			var todate = fromDateRaw;

			} else if (val == 3)	{

				var todayTime  = new Date();
				var newYesDate = new Date();

				var currentTimeRaw = new Date();
				var currentTime = $filter('date')(currentTimeRaw,'HH:mm:ss');
				var fromDateRaw = $filter('date')(currentTimeRaw,'yyyy-MM-dd');
				todayTime.setHours(todayTime.getHours()-23);
    			todayTime.setMinutes(todayTime.getMinutes()-59);
    			var previousDate = newYesDate.setDate(newYesDate.getDate()-1);
    			$scope.HHmmss = $filter('date')(todayTime, 'HH:mm:ss');
    			// alert($scope.HHmmss);

    			var fromtime = $scope.HHmmss;
    			var totime = currentTime ;
    			var fromdate = $filter('date')(previousDate,'yyyy-MM-dd');
    			var todate = fromDateRaw;

			}

			else if (val == 4)	{

				var todayTime  = new Date();
				var newYesDate = new Date();

				var currentTimeRaw = new Date();
				var currentTime = $filter('date')(currentTimeRaw,'HH:mm:ss');
				var fromDateRaw = $filter('date')(currentTimeRaw,'yyyy-MM-dd');
				todayTime.setHours(todayTime.getHours()-1);
    			todayTime.setMinutes(todayTime.getMinutes()-59);
    			var previousDate = newYesDate.setDate(newYesDate.getDate()-2);
    			var fromYesDate = newYesDate.setDate(newYesDate.getDate()-1);
    			$scope.HHmmss = $filter('date')(todayTime, 'HH:mm:ss');
    			var fromtime ="00:00:00";
    			var totime = "23:59:59";
    			var fromdate = $filter('date')(previousDate,'yyyy-MM-dd');
    			var todate = $filter('date')(fromDateRaw,'yyyy-MM-dd');

			} else	{

    			if(document.getElementById('timeFrom').value==''){
				   var fromtime = "00:00:00";
			    }else{
				   var fromtime = $scope.timeconversion(document.getElementById('timeFrom').value);
			    }
			    if(document.getElementById('timeTo').value==''){
				   var totime = "00:00:00";
			    }else{
				   var totime = $scope.timeconversion(document.getElementById('timeTo').value);
			    }
            }
            
            if(document.getElementById('dateFrom').value==''){
				if(document.getElementById('dateTo').value==''){
					$scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID;
				}
			}else{
				if(document.getElementById('dateTo').value==''){
					$scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID+'&interval=1&fromDate='+fromdate+'&fromTime='+fromtime;
				}else{
					var days =daydiff(new Date(fromdate), new Date(todate));
					if(days<3)
						$scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID+'&interval=1&fromDate='+fromdate+'&fromTime='+fromtime+'&toDate='+todate+'&toTime='+totime+'&fromDateUTC='+utcFormat(fromdate,fromtime)+'&toDateUTC='+utcFormat(todate,totime);
					else if(days < 7)  
						$scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+fromdate+'&fromTime='+fromtime+'&toDate='+todate+'&toTime='+totime+'&interval=1'+'&fromDateUTC='+utcFormat(fromdate,fromtime)+'&toDateUTC='+utcFormat(todate,totime);
					else{
						alert('Please select less than 7 days !...');
						stopLoading();
					}
				}
			}

			if($scope.hisurlold!=$scope.hisurl){	
				for(var i=0; i<gmarkers.length; i++){
					gmarkers[i].setMap(null);
				}
				for(var i=0; i<$scope.polyline1.length; i++){
					$scope.polyline1[i].setMap(null);
				}

				$scope.path = [];
				$scope.polylinearr = [];
				gmarkers=[];
				ginfowindow=[];
				contentString = [];
				gsmarker=[];
				gsinfoWindow=[];
           
            if($scope.mhValue==1){
	           window.clearInterval(intervalPoly);	
                if($scope.markerValue != 1){
	             //$scope.infosWindows.close();
	               $scope.infosWindows=null;
		           $scope.markerhead.setMap(null);
                 }
		    }  

			if($scope.markerValue==1){
                window.clearInterval(timeInterval);
			    $scope.polyline.setMap(null);
				$scope.markerstart.setMap(null);
				$scope.markerend.setMap(null);
                $scope.infosWindow.close();
                $scope.infosWindow=null;
		        $scope.markerheads.setMap(null);
		        $scope.markerValue=0;
               }

				$('#replaybutton').attr('disabled','disabled');
				$('#lastseen').html('<strong>From Date & time :</strong> -');
				$('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');

			}else{
				if($scope.hisloc.error!=null || $scope.hisloc.error == undefined){
					// $('#myModal').modal();
					// alert('Please selected date before 7 days / No data found')
					//stopLoading();
				}
			}
		}
	//stopLoading();
	}
	
	$scope.addMarker= function(pos){
	
		var myLatlng = new google.maps.LatLng(pos.lat, pos.lng);
		var labelAnchorpos = new google.maps.Point(12, 37);
	
		if(pos.data.insideGeoFence =='Y'){
			//pinImage = 'assets/imgs/F_'+pos.data.direction+'.png';
			  pinImage = 'assets/imgs/trans.png';
		}else{	

			if(pos.data.position =='P') {
			  
			  //pinImage = 'assets/imgs/'+pos.data.position+'.png';
				pinImage = 'assets/imgs/flag.png';

			} else if(pos.data.position =='S') {
			  
			  //pinImage = 'assets/imgs/A_'+pos.data.direction+'.png';
				pinImage = 'assets/imgs/orange.png';
			}
		}
		
		$scope.marker = new MarkerWithLabel({
			   position: pos.path, 
			   map: $scope.map,
			   icon: pinImage,
			   labelContent: ''/*pos.data.position*/,
			// labelAnchor: labelAnchorpos,
			// labelClass: "labels", 
			   labelInBackground: true
		});

	 gmarkers.push($scope.marker);	
	} 

	$scope.geocoder = function(lat, lng, callback){
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(lat, lng);
		geocoder.geocode({'latLng': latlng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
			  if (results[1]) {
			  	if(results[1].formatted_address==undefined){
			  		if(typeof callback === "function") callback("No Result");
			  	}else{
					if(typeof callback === "function") callback(results[1].formatted_address);
				}
			  } else {
				 console.log('No results found');
			  }
			} else {
				 console.log('Geocoder failed due to: ' + status);
			}
		});
	}

	$scope.addMarkerstart= function(pos){
		
		var myLatlng = new google.maps.LatLng(pos.lat, pos.lng);
		pinImage = 'assets/imgs/startflag.png';

		$scope.markerstart = new MarkerWithLabel({
		   position: pos.path, 
		   map: $scope.map,
		   icon: pinImage
		});
		$scope.geocoder(pos.lat, pos.lng, function(count){
			$scope.tempadd = count;
		});

		var contentString = '<h3 class="infoh3">Vehicle Details ('+$scope.shortVehiId+') </h3><div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody><!--<tr><td>Location</td><td>'+$scope.tempadd+'</td></tr>--><tr><td>Last seen</td><td>'+dateFormat(pos.data.date)+'</td></tr><tr><td>Parked Time</td><td>'+$scope.timeCalculate(pos.data.parkedTime)+'</td></tr><tr><td>Trip Distance</td><td>'+pos.data.tripDistance+'</td></tr></table></div>';
		var infoWindow = new google.maps.InfoWindow({content: contentString});
		google.maps.event.addListener($scope.markerstart, "click", function(e){
			infoWindow.open($scope.map, $scope.markerstart);	
		});
		(function(marker, data, contentString) {
		  google.maps.event.addListener(marker, "click", function(e) {
			infoWindow.open($scope.map, marker);
		  });	
		})($scope.markerstart, pos.data);
	}

	$scope.addMarkerend= function(pos){
		var myLatlng = new google.maps.LatLng(pos.lat, pos.lng);
		pinImage = 'assets/imgs/endflag.png';
		$scope.markerend = new MarkerWithLabel({
		   position: pos.path, 
		   map: $scope.map,
		   icon: pinImage
		});
		$scope.geocoder(pos.lat, pos.lng, function(count){
			if(count!=undefined){
				$scope.tempadd = count;
			}else{
				$scope.tempadd = 'No Result';
			}
			
			var contentString = '<h3 class="infoh3">Vehicle Details ('+$scope.shortVehiId+') </h3>'
			+'<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody>'
			+'<tr><td>Location</td><td>'+$scope.tempadd+'</td></tr><tr><td>Last seen</td><td>'+dateFormat(pos.data.date)+'</td>'
			+'</tr><tr><td>Parked Time</td><td>'+$scope.timeCalculate(pos.data.parkedTime)+'</td></tr><tr><td>Trip Distance</td>'
			+'<td>'+pos.data.tripDistance+'</td></tr></table></div>';
			
			var infoWindow = new google.maps.InfoWindow({content: contentString});
			google.maps.event.addListener($scope.markerend, "click", function(e){
				infoWindow.open($scope.map, $scope.markerend);	
			});
			
			(function(marker, data, contentString) {
			  google.maps.event.addListener(marker, "click", function(e) {
				infoWindow.open($scope.map, marker);
			  });	
			})($scope.markerend, pos.data);
		});
	}
	$scope.tempadd="";

	function dateFormat(date) { return $filter('date')(date, "yyyy-MM-dd HH:mm:ss");}

	$scope.printadd=function(a, b, c, d, marker, map, data){
		var posval='';
		
		if(data.position=='S'){
			posval = 'Idle Time';
		
		}else if(data.position=='P'){
			posval = 'Parked Time';
		}
		
		var contentString = '<h3 class="infoh3">Vehicle Details('+$scope.shortVehiId+') </h3><div class="nearbyTable02">'
		+'<div><table cellpadding="0" cellspacing="0"><tbody>'
		+'<tr><td>Location</td><td>'+d+'</td></tr>'
		+'<tr><td>Last seen</td><td>'+a+'</td></tr>'
		+'<tr><td>'+posval+'</td><td>'+$scope.timeCalculate(b)+'</td></tr>'
		+'<tr><td>Trip Distance</td><td>'+c+'</td></tr>'
		+'</table></div>';
		var infoWindow = new google.maps.InfoWindow({content: contentString});
		ginfowindow.push(infoWindow);
		google.maps.event.addListener(marker, "click", function(e){
			for(j=0;j<ginfowindow.length;j++){
				ginfowindow[j].close();
			}
			infoWindow.open(map, marker);	
		});
		// (function(marker, data, contentString) {
		//   google.maps.event.addListener(marker, "click", function(e) {
		//   	for(j=0;j<ginfowindow.length;j++){
		  		
		// 		ginfowindow[j].close();
		// 	}
		// 	infoWindow.open(map, marker);
		//   });	
		// })(marker, data);
	}

	$scope.imgsrc = function(img){
	   return img;
	}

	$scope.infoBox = function(map, marker, data){
		 
		var t = $scope.getLocation(data.latitude, data.longitude, function(count){
			
			 if(data.position=='S'){
			 	var b = data.idleTime;
			 }else if(data.position=='P'){
			 	var b = data.parkedTime;
			 }
			$scope.printadd(dateFormat(data.date), b, data.tripDistance, count, marker, map, data); 
		});
	};

    $scope.trimComma = function(textVal){

      var strValues;
    //console.log(textVal);

      if(textVal!=undefined){
    	var splitValue = textVal.split(/[,]+/);
        var strLen=splitValue.length;

        switch(strLen){
              case 0:
               strValues='No Data';
               break;
              case 1:
               strValues=splitValue[0];
               break;
              case 2:
               strValues=splitValue[0]+','+splitValue[1];
               break;
              default:
               strValues=splitValue[0]+','+splitValue[1];
               break;
            }
      }else{
        strValues='No Address';
      }

    return  strValues;
	}

	$scope.hideMarkerVal = true;
	$scope.hideMarker = {
       	value1 : true,
      	value2 : 'YES'
    }
     			
	$scope.addRemoveMarkers=function(val){

	   if (val == 'YES')
		{
		     $scope.hideMarkerVal = false;
		}
		else
		{
			 $scope.hideMarkerVal = true;
		}
	}

    var markerhead, intervalPoly;
    var timeInterval;

	$scope.polylineCheck 	= false;
	$scope.markerPark 		= [];

	function myStopFunction() {
	    clearInterval(intervalPoly);
	}

	function markerClear(){

		for(var i=0; i<gmarkers.length; i++){
				gmarkers[i].setMap(null);
			}
			if($scope.polylineLoad)
		if($scope.polylineLoad.length >0)
			for (var i = $scope.polylineLoad[0].length - 1; i >= 0; i--) {
				$scope.polylineLoad[0][i].setMap(null);
			}

	}

	var pcount    = 0;
	var lenCount2 = 0;
    $scope.timeDelay=180;
    $scope.speedVal=0;
    $scope.pasVal=0;

    function timerSet(){

    	if($scope.hisloc.vehicleLocations.length!= pcount+1){

	       	var latlngs = new google.maps.LatLng($scope.hisloc.vehicleLocations[pcount+1].latitude,$scope.hisloc.vehicleLocations[pcount+1].longitude);
    	 //  if(($scope.hisloc.vehicleLocations[pcount].latitude+''+$scope.hisloc.vehicleLocations[pcount].longitude)!=($scope.hisloc.vehicleLocations[pcount+1].latitude+''+$scope.hisloc.vehicleLocations[pcount+1].latitude)){
             $scope.rotationsd = getBearing($scope.hisloc.vehicleLocations[pcount].latitude,$scope.hisloc.vehicleLocations[pcount].longitude,$scope.hisloc.vehicleLocations[pcount+1].latitude,$scope.hisloc.vehicleLocations[pcount+1].longitude);                
	                 
                                 
				                $scope.markerheads.setIcon({
                                      path:vehicIcon[0],
				                      scale:vehicIcon[1],
						              strokeWeight: 1,
						              fillColor:$scope.polylinearr[pcount+1],
				                      fillOpacity: 1,
				                      anchor:vehicIcon[2],
				                      rotation:$scope.rotationsd,
				                });

				                if(pcount==0){
				                	$scope.markerheads.setMap($scope.map);
				                }

                                $scope.markerheads.setPosition(latlngs);


			var contenttString = '<div style="padding:2px; padding-top:3px; width:175px;">'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">LocTime</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+dateFormat($scope.hisloc.vehicleLocations[pcount+1].date)+'</span> </div>'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">Speed</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+ $scope.hisloc.vehicleLocations[pcount+1].speed+'</span><span style="font-size:10px;padding-left:10px;">kmph</span></div>'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">DistCov</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+$scope.hisloc.vehicleLocations[pcount+1].distanceCovered+'</span><span style="font-size:10px;padding-left:10px;">kms</span></div>'
			+'<div style="text-align:center;padding-top:3px;"><span style="font-size:10px;width:100px;">'+$scope.trimComma($scope.hisloc.vehicleLocations[pcount+1].address)+'</span></div>'
			// +'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
			+'</div>';

				                /* var contenttString ='<table class="infoTables">'
				                        +'<tbody>'

	                                        +'<tr>'+'<td>'+'Odo (kms):'+'</td>'+'<td>'+$scope.hisloc.vehicleLocations[pcount+1].odoDistance+'</td>'+'</tr>'
				                                             
				                            +'<tr>'+'<td>'+'Dist (kms):'+'</td>'+'<td>'+$scope.hisloc.vehicleLocations[pcount+1].distanceCovered+'</td>'+'</tr>'      
				                                      
					                        +'<tr>'+'<td>'+'Speed (kmph):'+'</td>'+'<td>'+$scope.hisloc.vehicleLocations[pcount+1].speed+'</td>'+'</tr>'
				                                                 
				                            +'<tr>'+'<td>'+'Last Seen:'+'</td>'+'<td>'+$scope.hisloc.vehicleLocations[pcount+1].lastSeen+'</td>'+'</tr>'
				                                          
				                            +'<tr style="max-width:150px;min-width:150px;">'+'<td colspan="2" style="max-height:60px;min-height:60px;">'+$scope.trimComma($scope.hisloc.vehicleLocations[pcount+1].address)+'</td>'+'<td style="max-height:60px;min-height:60px;">'+'</td>'+'</tr>'			                                            
				           		       
				           		        +'</tbody>'
				                       +'</table>';  */

				             $scope.infosWindow.setContent(contenttString);
				             $scope.infosWindow.setPosition(latlngs);

				          /* if(pcount==0){
                                $scope.infosWindow.open($scope.map,$scope.markerheads);
                             }   */
       
                            $scope.map.panTo(latlngs);
    // }
      pcount++;
     }
     else{

       if(lenCount2 == 0 ){

       	  if($scope.hisloc.vehicleLocations.length==1){

               	var latlngs = new google.maps.LatLng($scope.hisloc.vehicleLocations[0].latitude,$scope.hisloc.vehicleLocations[0].longitude);
    	    //  if(($scope.hisloc.vehicleLocations[pcount].latitude+''+$scope.hisloc.vehicleLocations[pcount].longitude)!=($scope.hisloc.vehicleLocations[pcount+1].latitude+''+$scope.hisloc.vehicleLocations[pcount+1].latitude)){
            //  $scope.rotationsd = getBearing($scope.hisloc.vehicleLocations[pcount].latitude,$scope.hisloc.vehicleLocations[pcount].longitude,$scope.hisloc.vehicleLocations[pcount+1].latitude,$scope.hisloc.vehicleLocations[pcount+1].longitude);                
	                 
                                 
				                $scope.markerheads.setIcon({
                                      path:vehicIcon[0],
				                      scale:vehicIcon[1],
						              strokeWeight: 1,
						              fillColor:$scope.polylinearr[0],
				                      fillOpacity: 1,
				                      anchor:vehicIcon[2],
				                   // rotation:$scope.rotationsd,
				                });

				                $scope.markerheads.setMap($scope.map);
                                $scope.markerheads.setPosition(latlngs);
                            

            var contenttString = '<div style="padding:2px; padding-top:3px; width:175px;">'
            +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">LocTime</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+dateFormat($scope.hisloc.vehicleLocations[0].date)+'</span> </div>'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">Speed</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+ $scope.hisloc.vehicleLocations[0].speed+'</span><span style="font-size:10px;padding-left:10px;">kmph</span></div>'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">DistCov</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+$scope.hisloc.vehicleLocations[0].distanceCovered+'</span><span style="font-size:10px;padding-left:10px;">kms</span></div>'
			+'<div style="text-align:center;padding-top:3px;"><span style="font-size:10px;width:100px;">'+$scope.trimComma($scope.hisloc.vehicleLocations[0].address)+'</span></div>'
			// +'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
			+'</div>';

				             $scope.infosWindow.setContent(contenttString);
				             $scope.infosWindow.setPosition(latlngs);
                          // $scope.infosWindow.open($scope.map,$scope.markerheads);
       	  }

        lenCount2++;
        }

          $('#pauseButton').hide();
          $('#playButton').hide();      
          $('#stopButton').hide();   
          $('#replayButton').show(); 
          $scope.speedVal=1; 

     } 
}

	$scope.polylineCtrl 	= function(){

	   //console.log($scope.hisloc);
       if($scope.hisloc.vehicleLocations!=null){

		startLoading();
		myStopFunction();
     // $scope.polylinearr = [];
		markerClear();
		gmarkers=[];
		ginfowindow=[];
		// contentString = [];
		// gsmarker=[];
		// gsinfoWindow=[];
		var j =0;

		$('.radioBut').hide(0).delay(5000).show(100);
		// $('.hideClass').hide();
		/*
			loadall in map polyline
		*/
  
       vehicIcon=vehiclesChange(VehiType);

		if($scope.polylineCheck == false){

			$('.hideClass').hide();
			   $('#pauseButton').hide();
                 $('#playButton').hide();      
                   $('#stopButton').hide();   
                     $('#replayButton').hide(); 

//			var lineCount 	=	 0;
			// markerClear();
			// var lineSymbol = {
		 //        path: 'M 0.5,-1 0.5,1 M -0.5,-1 -0.5,1',
		 //        strokeOpacity: 1,
		 //        strokeWeight: 1,
		 //        scale: 3
		 //    };
			  var doubleLine = {
		       	path: 'M 0,-1 0,1',
		        strokeOpacity: 1,
		        strokeWeight: 3,
		        scale: 5
		    //     path: 'M 0.5,-1 0.5,1 M -0.5,-1 -0.5,1',
		    // strokeOpacity: 0.6,
		    // strokeWeight: 2,
		    // scale: 5

		    };

            if($scope.markerValue==1){
                window.clearInterval(timeInterval);
                 $scope.infosWindow.close();
                 $scope.infosWindow=null;
				   $scope.markerheads.setMap(null);
                   $scope.markerheads=null;
                   $scope.markerValue=0;
            }

	    	if($scope.polyline && $scope.markerstart && $scope.markerend){
					
				$scope.polyline.setMap(null);
				$scope.markerstart.setMap(null);
				$scope.markerend.setMap(null);
			}
	    				
			$scope.polylineLoad 	=[];
			//vehicIcon=vehiclesChange(VehiType); 

	function myTimer() {

		if($scope.path.length == lineCount+1){
			  		myStopFunction();
				}
				
		if($scope.path.length != lineCount+1){

	    var rotationd = getBearing($scope.path[lineCount].lat(), $scope.path[lineCount].lng(), $scope.path[lineCount+1].lat(), $scope.path[lineCount+1].lng());
				
			/*	markerhead = new google.maps.Marker({
				        position: $scope.path[lineCount+1],
				        icon: 
				        {
				          path:vehicIcon[0],
				          scale:vehicIcon[1],
						  strokeWeight: 1,
				          fillColor: $scope.polylinearr[lineCount],
				          fillOpacity: 1,
				          anchor:vehicIcon[2],
				          rotation: rotationd,
				        /*path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
				          scale: 5,
				          strokeWeight: 1,
				          fillColor: $scope.polylinearr[lineCount],
				        //fillColor: '#a8d6e9',
				          fillOpacity: 1,
				          anchor: new google.maps.Point(0, 2.6),*/
				    /*    },

			    	}); */

			    	   $scope.markerhead.setIcon({
                                path:vehicIcon[0],
				                scale:vehicIcon[1],
						        strokeWeight: 1,
				                fillColor: $scope.polylinearr[lineCount],
				                fillOpacity: 1,
				                anchor:vehicIcon[2],
				                rotation: rotationd,
				                });

                 if(lineCount == 0){
			         $scope.markerhead.setMap($scope.map);
                  }
                    $scope.markerhead.setPosition($scope.path[lineCount+1]);

			    // for(var i=0;i<$scope.path.length-1;i++){
   					$scope.polyline1[lineCount] = new google.maps.Polyline({
						map: $scope.map,
						path: [$scope.path[lineCount], $scope.path[lineCount+1]],
						strokeColor: $scope.polylinearr[lineCount],
						strokeOpacity: 0,
					  //strokeOpacity: 1,
						strokeWeight: 3,
						icons: [{
				            icon: doubleLine,
				            offset: '50%',
				            repeat: '15px'
				        }],

						clickable: true
				    });

               
            var contenttString = '<div style="padding:2px; padding-top:3px; width:175px;">'
            +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">LocTime</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+dateFormat($scope.hisloc.vehicleLocations[lineCount+1].date)+'</span> </div>'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">Speed</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+ $scope.hisloc.vehicleLocations[lineCount+1].speed+'</span><span style="font-size:10px;padding-left:10px;">kmph</span></div>'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">DistCov</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+$scope.hisloc.vehicleLocations[lineCount+1].distanceCovered+'</span><span style="font-size:10px;padding-left:10px;">kms</span></div>'
			+'<div style="text-align:center;padding-top:3px;"><span style="font-size:10px;width:100px;">'+$scope.trimComma($scope.hisloc.vehicleLocations[lineCount+1].address)+'</span></div>'
		  //+'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
			+'</div>';


				            $scope.infosWindows.setContent(contenttString);
				            $scope.infosWindows.setPosition($scope.path[lineCount+1]);

                         /* if(lineCount == 0){
                              $scope.infosWindows.open($scope.map,$scope.markerhead);
                            } */

            				$scope.map.panTo($scope.path[lineCount]);

   				var latLngBounds = new google.maps.LatLngBounds();
			  		// for(var i = 0; i < $scope.path.length; i++) {
						latLngBounds.extend($scope.path[lineCount]);
						if($scope.hisloc.vehicleLocations[lineCount])
					    if($scope.hisloc.vehicleLocations[lineCount].position!=undefined){
							if($scope.hisloc.vehicleLocations[lineCount].position=='P' || $scope.hisloc.vehicleLocations[lineCount].position=='S' || $scope.hisloc.vehicleLocations[lineCount].insideGeoFence=='Y' ){
								
							$scope.addMarker({ lat: $scope.hisloc.vehicleLocations[lineCount].latitude, lng: $scope.hisloc.vehicleLocations[lineCount].longitude , data: $scope.hisloc.vehicleLocations[lineCount], path:$scope.path[lineCount]});
							$scope.infoBox($scope.map, gmarkers[j], $scope.hisloc.vehicleLocations[lineCount]);
			  			  //$scope.markerPark.push($scope.marker)
							j++;
						  }
						}
			  		// }

			  		$scope.polylineLoad.push($scope.polyline1);
			  	lineCount++;
			  	
				} else if($scope.path.length == 1){

					if(lenCount == 0){
							//console.log(vehicIcon[0]);
							          $scope.markerhead.setIcon({
                                path:vehicIcon[0],
				                scale:vehicIcon[1],
						        strokeWeight: 1,
				                fillColor: $scope.polylinearr[0],
				                fillOpacity: 1,
				                anchor:vehicIcon[2],
				            //    rotation: rotationd,
				                });

			         $scope.markerhead.setMap($scope.map);
                     $scope.markerhead.setPosition($scope.path[0]);

			    // for(var i=0;i<$scope.path.length-1;i++){
   					$scope.polyline1[lineCount] = new google.maps.Polyline({
						map: $scope.map,
						path: [$scope.path[0], $scope.path[0]],
						strokeColor: $scope.polylinearr[0],
						strokeOpacity: 0,
					  //strokeOpacity: 1,
						strokeWeight: 3,
						icons: [{
				            icon: doubleLine,
				            offset: '50%',
				            repeat: '15px'
				        }],

						clickable: true
				    });

            var contenttString = '<div style="padding:2px; padding-top:3px;width:175px;">'
            +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">LocTime</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+dateFormat($scope.hisloc.vehicleLocations[0].date)+'</span> </div>'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">Speed</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+ $scope.hisloc.vehicleLocations[0].speed+'</span><span style="font-size:10px;padding-left:10px;">kmph</span></div>'
			+'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">DistCov</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+$scope.hisloc.vehicleLocations[0].distanceCovered+'</span><span style="font-size:10px;padding-left:10px;">kms</span></div>'
			+'<div style="text-align:center;padding-top:3px;"><span style="font-size:10px;width:100px;">'+$scope.trimComma($scope.hisloc.vehicleLocations[0].address)+'</span></div>'
		  //+'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
			+'</div>';

				            $scope.infosWindows.setContent(contenttString);
				            $scope.infosWindows.setPosition($scope.path[0]);
                          //$scope.infosWindows.open($scope.map,$scope.markerhead);

                lenCount++;
				}

			 }
           }
			    var lineCount =	0;
			    var lenCount  = 0;

			    $scope.markerhead   = new google.maps.Marker();
                $scope.infosWindows = new google.maps.InfoWindow({maxWidth:180}); 

			    $scope.mhValue=1;

     		    $scope.markerhead.addListener('click', function() {
				   $scope.infosWindows.open($scope.map,$scope.markerhead);
			    });

	   	intervalPoly = setInterval(function(){ myTimer() }, 600);
    }
    else{
		// var j =0;
		 $('.hideClass').show();
	     $('#playButton').hide();
         $('#replayButton').show();
         $('#pauseButton').show(); 
         $('#stopButton').show();
 
          if($scope.markerhead){
          	      $scope.infosWindows.close();
                  $scope.infosWindows=null;
				  $scope.markerhead.setMap(null);
				  $scope.markerhead=null;
            }
			
						 var tempFlag=false;
			  				 for(var k=0;k<$scope.hisloc.vehicleLocations.length;k++){
				  				 if($scope.hisloc.vehicleLocations[k].position =='M' && tempFlag==false){
				  				 	var firstval = k;
				  				 	
				  					 tempFlag=true;
							  	 }
						  	 }
					  			if(firstval==undefined){
					  				firstval=0;
					  			}
				    var lastval = $scope.hisloc.vehicleLocations.length-1;
			  		$scope.addMarkerstart({ lat: $scope.hisloc.vehicleLocations[firstval].latitude, lng: $scope.hisloc.vehicleLocations[firstval].longitude , data: $scope.hisloc.vehicleLocations[firstval], path:$scope.path[firstval]});
					$scope.addMarkerend({ lat: $scope.hisloc.vehicleLocations[lastval].latitude, lng: $scope.hisloc.vehicleLocations[lastval].longitude, data: $scope.hisloc.vehicleLocations[lastval], path:$scope.path[lastval] });
							
						/*	var lineSymbol = {
						      path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
						      scale: 3,
						    };*/
						  	$scope.polyline = new google.maps.Polyline({
								map: $scope.map,
								path: $scope.path,
								strokeColor: '#068b03',
								strokeOpacity: 0.7,
								strokeWeight: 3,
								/*icons: [{
						            icon: lineSymbol,
						           offset: '100%'
						        }],*/
								clickable: true
						  	});

   				    $scope.markerheads = new google.maps.Marker();
				    $scope.infosWindow = new google.maps.InfoWindow({maxWidth:180});

                    $scope.markerheads.addListener('click', function() {
					   $scope.infosWindow.open($scope.map,$scope.markerheads);
					});

				    $scope.markerValue=1;

                    pcount    = 0;
                    lenCount2 = 0;
                    timeInterval=setInterval(function(){ timerSet() }, $scope.timeDelay);
			
			
					   var latLngBounds = new google.maps.LatLngBounds();
						    for(var i = 0; i < $scope.path.length; i++) {
								latLngBounds.extend($scope.path[i]);
									if($scope.hisloc.vehicleLocations[i])
									if($scope.hisloc.vehicleLocations[i].position!=undefined){
										if($scope.hisloc.vehicleLocations[i].position=='P' || $scope.hisloc.vehicleLocations[i].position=='S' || $scope.hisloc.vehicleLocations[i].insideGeoFence=='Y' ){
											
										$scope.addMarker({ lat: $scope.hisloc.vehicleLocations[i].latitude, lng: $scope.hisloc.vehicleLocations[i].longitude , data: $scope.hisloc.vehicleLocations[i], path:$scope.path[i]});
										$scope.infoBox($scope.map, gmarkers[j], $scope.hisloc.vehicleLocations[i]);
									  //$scope.markerPark.push($scope.marker);
											j++;
										}
										
									}
						  		}
		    }
		   // console.log($scope.path);
		   // console.log($scope.polylineCheck);
		stopLoading();
	  }
	}

	$scope.replays= function(){

	    $('#playButton').hide();
	    $('#replayButton').show();
		$('#stopButton').show();	
	    $('#pauseButton').show();

        window.clearInterval(timeInterval);               

        pcount=0; 
        timeInterval=setInterval(function(){ timerSet() }, $scope.timeDelay);
	}
	
	$scope.pausehis= function(){

	   $scope.pasVal=1;	
       $('#stopButton').hide();	
	   $('#pauseButton').hide();
	   $('#replayButton').hide();
	   $('#playButton').show();
		   
        window.clearInterval(timeInterval);
    }

	$scope.speedchange=function(){

		if($scope.speedVal!==1){
	        $('#playButton').hide();
			$('#replayButton').show();
		    $('#stopButton').show();	
			$('#pauseButton').show();
        }

        if($scope.pasVal==1){
         	$('#playButton').hide();
			$('#replayButton').show();
		    $('#stopButton').show();	
			$('#pauseButton').show();
			$scope.pasVal=0;
         }

		window.clearInterval(timeInterval);
    	timeInterval=setInterval(function(){ timerSet() }, $scope.timeDelay );

	}
	
	$scope.playhis=function(){

	    	$('#playButton').hide();
			$('#replayButton').show();
		    $('#stopButton').show();	
			$('#pauseButton').show();

	    window.clearInterval(timeInterval);		  
        timeInterval=setInterval(function(){ timerSet() }, $scope.timeDelay );
	}

	$scope.stophis=function(){

         $('#stopButton').hide();	
			  $('#pauseButton').hide();
         	      $('#replayButton').show();
         	         $('#playButton').show();

    window.clearInterval(timeInterval);
	}

	$(document).ready(function(){
        $('#minmax1').click(function(){
            $('#contentreply').animate({
                height: 'toggle'
            },500);
        });
    });
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
    
        $(function () {
        $('#dateFrom, #dateTo').datetimepicker({
          format:'YYYY-MM-DD',
          useCurrent:true,
          pickTime: false
        });
        $('#timeFrom').datetimepicker({
          pickDate: false,
                    useCurrent:true,
        });
        $('#timeTo').datetimepicker({
          useCurrent:true,
          pickDate: false
        });
        });
        
	$(document).ready(function(){
        $('#editAction').click(function(){
            // $('#contentmin').animate({
            //     height: 'toggle'
            // },500);
            console.log(' edit action ')
        });
    });
	$('.legendlist').hide()
    $(document).ready(function() {
    	$('.viewList').click(function(){
    		$('.legendlist').animate({
    			height: 'toggle'
    		})
    	})
    });

  $('ul.tabs').each(function(){
  // For each set of tabs, we want to keep track of
  // which tab is active and its associated content
  var $active, $content, $links = $(this).find('a');

  // If the location.hash matches one of the links, use that as the active tab.
  // If no match is found, use the first link as the initial active tab.
  $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
  $active.addClass('current');

  $content = $($active[0].hash);

  // Hide the remaining content
  $links.not($active).each(function () {
    $(this.hash).hide();
  });

  // Bind the click event handler
  $(this).on('click', 'a', function(e){
    // Make the old tab inactive.
    $active.removeClass('current');
    $content.hide();

    // Update the variables with the new link and content
    $active = $(this);
    $content = $(this.hash);

    // Make the tab active.
    $active.addClass('current');
    $content.show();

    // Prevent the anchor's default click action
    e.preventDefault();
  });
});

}]);

