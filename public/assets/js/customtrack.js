app.directive('map', function($http, vamoservice) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {
   
           vamoservice.getDataCall(scope.url).then(function(data) {

		   	   var locs = data;
               var vehicType;
               var vehicIcon=[];
             
               vehicType=data.vehicleType;
               vehicIcon=vehiclesChange(vehicType); 

		   		var myOptions = {
					zoom: 13,zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
					center: new google.maps.LatLng(locs.latitude, locs.longitude),
					mapTypeId: google.maps.MapTypeId.ROADMAP/*,
					*/
            	};
            	scope.map = new google.maps.Map(document.getElementById(attrs.id), myOptions);
				google.maps.event.addListener(scope.map, 'click', function(event) {
								scope.clickedLatlng = event.latLng.lat() +','+ event.latLng.lng();
								$('#latinput').val(scope.clickedLatlng);
							});
				$('#vehiid span').text(locs.shortName);
				$('#toddist span span').text(locs.distanceCovered);
				// total = parseInt(locs.speed);
				$('#deviceVolt span').text(locs.deviceVolt);
				$('#vehdevtype span').text(locs.odoDistance);
				$('#mobno span').text(locs.overSpeedLimit);
				

				$('#graphsId #speed').text(locs.speed);
				$('#graphsId #fuel').text(locs.tankSize);
				tankSize 		 = parseInt(locs.tankSize);
				fuelLtr 		 = parseInt(locs.fuelLitre);
				total  			 = parseInt(locs.speed);
				
				if((data && data != '') && (data.address == null || data.address == undefined || data.address == ' '))
					scope.getLocation(locs.latitude, locs.longitude, function(count){
						$('#lastseentrack').text(count);
						data.address = count;
						scope.addres = count;  
 
                        if(data.position == 'M'){
			            	scope.histVal.unshift(data);
                        }else{
			            	scope.histVal[0]=data;
		                }

					});
				else{
						$('#lastseentrack').text(data.address);
						scope.addres = data.address;
                        
                        if(data.position == 'M'){
			            	scope.histVal.unshift(data);
                        }else{
			            	scope.histVal[0]=data;
		                }
					} 
				
				$('#positiontime').text(vamoservice.statusTime(locs).tempcaption);
				$('#regno span').text(vamoservice.statusTime(locs).temptime);
				
				
			   	scope.speedval.push(data.speed);
          	  	scope.path.push(new google.maps.LatLng(data.latitude, data.longitude));

				var labelAnchorpos = new google.maps.Point(20, -30);
				var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
				
				scope.startlatlong = new google.maps.LatLng(data.latitude, data.longitude);
				scope.endlatlong = new google.maps.LatLng(data.latitude, data.longitude);

		 		if(scope.path.length>1){
			 		var latLngBounds = new google.maps.LatLngBounds();
					latLngBounds.extend(scope.path[scope.path.length-1]);
				}
				
       

	 function lineDraw(lat, lan){
             
     			 // if(data.isOverSpeed=='N'){
			     // var strokeColorvar = '#00b3fd';
				 // }else{
				 // var strokeColorvar = '#ff0000';
				 // }
				 // var latlng1 = new google.maps.LatLng(lat, lan);
				 // console.log(' value -->'+ latlng1);
					
				 scope.slatlong = new google.maps.LatLng(lat, lan);

                 np.push(new google.maps.LatLng(lat, lan));

				 scope.polyline = new google.maps.Polyline({
								map: scope.map,
								path: [scope.elatlong, scope.slatlong],
								strokeColor: '#00b3fd',
								strokeOpacity: 0.7,
								strokeWeight: 5,
								
								clickable: true
						  	});
				 scope.elatlong = scope.slatlong;
		    }
			    // scope.startlatlong = scope.endlatlong;
		 
  (function latlan(){

	vamoservice.getDataCall(scope.urlVeh).then(function(response) {

	    if(response.latLngOld != undefined){
                 for (var i = 0; i < response.latLngOld.length; i++) {
				    			sp = response.latLngOld[i].split(',');
                       		    lineDraw(sp[0], sp[1]);
				    		}

                    npl=np.length; 
                    npls=np.length;

                 for(var i=0;i<npl;i++){

                        if(npls>2){

                           if((np[npls-2].lat()+','+np[npls-2].lng())!=(np[npls-1].lat()+','+np[npls-1].lng())){

                              nplen=npls-2;
                              break; 
                            }
                            else{
                              npls=npls-1;
                           }
                        }
                        else if(npls==2){

                              if((np[npls-2].lat()+','+np[npls-2].lng())!=(np[npls-1].lat()+','+np[npls-1].lng())){

                                nplen=npls-1;
                                break;
                              }
                              else{
                                
                                npls=npls-1;

                              }

                            }
                         else{

                         	if((np[npls-1].lat()+','+np[npls-1].lng())!=(scope.path[0].lat()+','+scope.path[0].lng())){

                                    nplen=npls-1;
                                     break;
                                    	}
                         
                            else{

                             nplen=null;

                             scope.marker = new MarkerWithLabel({
			   	                   position: myLatlng, 
				                   map: scope.map,
			                     //icon: vamoservice.iconURL(data),
				                   icon: 
				                    {
				                      path:vehicIcon[0],
				                      scale:vehicIcon[1],
						              strokeWeight: 1,
				                    //fillColor: $scope.polylinearr[lineCount],
				                      fillColor:'#6dd538',
				                      fillOpacity: 1,
				                      anchor:vehicIcon[2],
				                    //rotation: rotationd,				 
				                    },
				                  labelContent: data.shortName,
			                      labelAnchor: labelAnchorpos,
			                    //labelAnchor: vehicIcon[2],
				                  labelClass: "labels", 
				                  labelInBackground: false
				               });

            	              google.maps.event.addListener(scope.marker, "click", function(e) {
					             infowindow.open(scope.map, scope.marker);
				                });
      	             	   // }
                        }
                    }
              
                 }//for ends... 

         
        if(nplen!=null) {
 
                var rotationd=getBearing(np[nplen].lat(),np[nplen].lng(),scope.path[0].lat(),scope.path[0].lng());

                scope.marker = new MarkerWithLabel({
			   	   position: myLatlng, 
				   map: scope.map,
			     //icon: vamoservice.iconURL(data),
				   icon: 
				        {
				          path:vehicIcon[0],
				          scale:vehicIcon[1],
						  strokeWeight: 1,
				        //fillColor: $scope.polylinearr[lineCount],
				          fillColor:'#6dd538',
				          fillOpacity: 1,
				          anchor:vehicIcon[2],
				          rotation: rotationd,				 
				         },
				   labelContent: data.shortName,
			       labelAnchor: labelAnchorpos,
			     //labelAnchor: vehicIcon[2],
				   labelClass: "labels", 
				   labelInBackground: false
				});

            	google.maps.event.addListener(scope.marker, "click", function(e) {
					infowindow.open(scope.map, scope.marker);
				   });

                scope.path.push(new google.maps.LatLng(np[nplen].lat(),np[nplen].lng()));
             }
                 
          }
          else{

                 console.log('latlanOld data not found.....');
  
                    scope.marker = new MarkerWithLabel({
			   	    position: myLatlng, 
				    map: scope.map,
			      //icon: vamoservice.iconURL(data),
				    icon: 
				        {
				          path:vehicIcon[0],
				          scale:vehicIcon[1],
						  strokeWeight: 1,
				      //  fillColor: $scope.polylinearr[lineCount],
				          fillColor:'#6dd538',
				          fillOpacity: 1,
				          anchor:vehicIcon[2],
				    //    rotation: rotationd,				 
				         },
				    labelContent: data.shortName,
			        labelAnchor: labelAnchorpos,
			      //labelAnchor: vehicIcon[2],
				    labelClass: "labels", 
				    labelInBackground: false
				  });

            	   google.maps.event.addListener(scope.marker, "click", function(e) {
					  infowindow.open(scope.map, scope.marker);
				    });

       }//if else ends...     

     });//lanlan vamoservice ends...

 }());//latlan func ends...


	      // vamoservice.getDataCall(uro).then(function(response) {
	      //   if(response.latLngOld.length)
	      //      console.log(' response '+response);
		  //    })

			    var contentString = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
						+'<div style="width:200px; display:inline-block;"><b >Address</b> - <span>'+scope.addres+'</span></div></div>';

				var infowindow = new google.maps.InfoWindow({
				    content: contentString
				});

			/*  google.maps.event.addListener(scope.marker, "click", function(e){
					infowindow.open(scope.map, scope.marker);
				});*/

});//first vamoservice ends..... 


//Global section ...
$(document).on('pageshow', '#maploc', function(e, data){       
    google.maps.event.trigger(document.getElementById('	maploc'), "resize");
 });

 var np=[];
 var npl=null;
 var npls=null;
 var nplen=null;

 var linesCount=1;  

//Global section ends...

		setInterval(function() {
		    vamoservice.getDataCall(scope.url).then(function(data) {

                if(scope.path.length==1){
                     linesCount=0;
                    }

		   		/*	if(data != '' && data){
		   				if(data.position == 'M')
		   					scope.histVal.unshift(data);
		   				else
		   					scope.histVal[0]=data;
		   			}*/
		   			
		   			var locs = data;

                    var vehicType;
                    var vehicIcon=[];
             
                       vehicType=data.vehicleType;
                       vehicIcon=vehiclesChange(vehicType); 


					// var myOptions = {
					// 	zoom: 13,
					// 	center: new google.maps.LatLng(locs.latitude, locs.longitude),
					// 	mapTypeId: google.maps.MapTypeId.ROADMAP
	    //         	};
            		
            		$('#vehiid span').text(locs.shortName);
					$('#toddist span span').text(locs.distanceCovered);
					$('#vehstat span').text(locs.position);
					$('#deviceVolt span').text(locs.deviceVolt);
					// total = parseInt(locs.speed);
					$('#vehdevtype span').text(locs.odoDistance);
					$('#mobno span').text(locs.overSpeedLimit);
					
					$('#graphsId #speed').text(locs.speed);
					$('#graphsId #fuel').text(locs.tankSize);
					tankSize 		 = parseInt(locs.tankSize);
					fuelLtr 		 = parseInt(locs.fuelLitre);
					total  			 = parseInt(locs.speed);

					$('#positiontime').text(vamoservice.statusTime(locs).tempcaption);
					$('#regno span').text(vamoservice.statusTime(locs).temptime);
					// scope.getLocation(locs.latitude, locs.longitude, function(count){
					// 	$('#lastseentrack').text(count); 
					// });

					if((data && data != '') && (data.address == null || data.address == undefined || data.address == ' ')){
						scope.getLocation(locs.latitude, locs.longitude, function(count){
							$('#lastseentrack').text(count);
							data.address = count;
							scope.addres = count;  
                             
                            if(data.position == 'M'){
		   					    scope.histVal.unshift(data);
                            }
		   				    else{
		   					    scope.histVal[0]=data;
		   				    }

						});
					}
					else{
						$('#lastseentrack').text(data.address);
						scope.addres = data.address;

                         if(data.position == 'M'){
		   					scope.histVal.unshift(data);
                         }
		   				 else{
		   					scope.histVal[0]=data;
		   				}
                    }

                    scope.path.push(new google.maps.LatLng(data.latitude, data.longitude));
                      
           			
					if(scope.path.length>1){
					 	var latLngBounds = new google.maps.LatLngBounds();
						latLngBounds.extend(scope.path[scope.path.length-1]);
					}
					var labelAnchorpos = new google.maps.Point(-40, -30);
				    var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
				
					// scope.map.setCenter(scope.marker.getPosition());

	
  if((scope.path[linesCount].lat()+','+scope.path[linesCount].lng())!=(scope.path[linesCount+1].lat()+','+scope.path[linesCount+1].lng())){

	scope.marker.setMap(null);
    scope.rotationd=getBearing(scope.path[linesCount].lat(),scope.path[linesCount].lng(),scope.path[linesCount+1].lat(),scope.path[linesCount+1].lng());
 
		scope.marker = new MarkerWithLabel({
					   position: myLatlng, 
					   map: scope.map,
					// center: myLatlng,
				    // icon: vamoservice.iconURL(data),
					   icon: 
				        {
				          path:vehicIcon[0],
				          scale:vehicIcon[1],
						  strokeWeight: 1,
				      //  fillColor: $scope.polylinearr[lineCount],
				          fillColor:'#6dd538',
				          fillOpacity: 1,
				          anchor:vehicIcon[2],
				          rotation: scope.rotationd,				 
				         },
					   labelContent: data.shortName,
				       labelAnchor: labelAnchorpos,
					   labelClass: "labels",
					   labelInBackground: false
					});
    }
					scope.map.setCenter(myLatlng);
					var contentString = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
						+'<div style="width:200px; display:inline-block;"><b>Address</b> - <span>'+scope.addres+'</span></div></div>';

					var infowindow = new google.maps.InfoWindow({
					    content: contentString
					});


					google.maps.event.addListener(scope.marker, "click", function(e) {
						infowindow.open(scope.map, scope.marker);
					});
					
					scope.endlatlong = new google.maps.LatLng(data.latitude, data.longitude);
					
				  	if(data.isOverSpeed=='N'){
						var strokeColorvar = '#00b3fd';
					}else{
						var strokeColorvar = '#ff0000';
					}
         			
				  	var polyline = new google.maps.Polyline({
			            map: scope.map,
			            path: [scope.startlatlong, scope.endlatlong],
			            strokeColor: strokeColorvar,
			            strokeOpacity: 0.7,
			            strokeWeight: 5
			        });
			        
			        scope.startlatlong = scope.endlatlong;
			        google.maps.event.trigger(document.getElementById('maploc'), "resize");

			        if(scope.histVal.length > 200)
		   				scope.histVal = scope.histVal.slice(0, scope.histVal.length - 50);

               	linesCount++;	
		   		});

           },10000);
        }
    };
});
app.controller('mainCtrl',['$scope', '$http', 'vamoservice', '_global', function($scope, $http, vamoservice, GLOBAL){ 
	var res = document.location.href.split("?");
	$scope.vehicleno = getParameterByName('vehicleId');
	$scope.url = GLOBAL.DOMAIN_NAME+'/getSelectedVehicleLocation?'+res[1];
	$scope.urlVeh = GLOBAL.DOMAIN_NAME+'/getSelectedVehicleLocation1?'+res[1];
	$scope.path = [];
	$scope.firstPath=[];
	$scope.speedval =[];
	$scope.inter = 0;
	$scope.cityCircle=[];
	$scope.cityCirclecheck=false;
	$scope.histVal 	= [];
    $scope.rotationd=null;

	$('#graphsId').hide();




	$scope.getLocation = function(lat,lon, callback){
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(lat, lon);
		geocoder.geocode({'latLng': latlng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
			  if (results[1]) {
				if(typeof callback === "function") callback(results[1].formatted_address)
			  } else {
				//alert('No results found');
			  }
			} else {
			}
		  });
    };



	vamoservice.getDataCall($scope.url).then(function(data) {

	    $scope.locations = data;
	/*	var locs=data;
	             $scope.getLocation(locs.latitude, locs.longitude, function(count){
						data.address = count;
						//$scope.addres = count;  
 
                                   if(data.position == 'M'){
		   					             $scope.histVal.push(data);
                                            }
		   				                   else{
		   					                 $scope.histVal[0]=data;
		   				                    }
					});*/
	//	$scope.histVal.push(data);

		var url = GLOBAL.DOMAIN_NAME+'/getGeoFenceView?'+res[1];
				$scope.createGeofence(url);

				$scope.vehiclFuel=graphChange($scope.locations.fuel);
                  if($scope.vehiclFuel==true){
                     $('#graphsId').removeClass('graphsCls');
                  }else{
		             $('#graphsId').addClass('graphsCls');
	              }
                  $('#graphsId').show();
	});


    $scope.addMarker= function(pos){
	   var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
	   var marker = new google.maps.Marker({
			position: myLatlng, 
			map: $scope.map
		});
	}

	$scope.timems = function(t){
		
		    var cd = 24 * 60 * 60 * 1000,
		        ch = 60 * 60 * 1000,
		        d = Math.floor(t / cd),
		        h = Math.floor( (t - d * cd) / ch),
		        m = Math.round( (t - d * cd - h * ch) / 60000),
		        pad = function(n){ return n < 10 ? '0' + n : n; };
		  if( m === 60 ){
		    h++;
		    m = 0;
		  }
		  if( h === 24 ){
		    d++;
		    h = 0;
		  }
		  return [d+'d', pad(h)+'h', pad(m)+'m'].join(':');
		
	}


	$scope.enterkeypress = function(){
		if(checkXssProtection(document.getElementById('poival').value) == true){
			var poiUrl = GLOBAL.DOMAIN_NAME+'/setPOIName?vehicleId='+$scope.vehicleno+'&poiName='+document.getElementById('poival').value;
			if(document.getElementById('poival').value=='' || $scope.vehicleno==''){}else{
				vamoservice.getDataCall(poiUrl).then(function(data) {
				 	document.getElementById('poival').value='';
				});
			}
		}
	}
	$scope.trafficLayer = new google.maps.TrafficLayer();
	$scope.checkVal=false;
	$scope.clickflagVal =0;
	$scope.check = function(){
		if($scope.checkVal==false){
			$scope.trafficLayer.setMap($scope.map);
			$scope.checkVal = true;
		}else{
			$scope.trafficLayer.setMap(null);
			$scope.checkVal = false;
		}
	}

	$scope.createGeofence=function(url){
		
		if($scope.cityCirclecheck==false){
			$scope.cityCirclecheck=true;
		}
		if($scope.cityCirclecheck==true){
			for(var i=0; i<$scope.cityCircle.length; i++){
				$scope.cityCircle[i].setMap(null);
			}
		}
		vamoservice.getDataCall(url).then(function(data) {
			$scope.geoloc = data;
			if (typeof(data.geoFence) !== 'undefined' && data.geoFence.length) {	
				//alert(data.geoFence[0].proximityLevel);
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
					var image = 'assets/imgs/busgeo.png';
				  
				  	
				  
				  	var beachMarker = new google.maps.Marker({
				      position: centerPosition,
				      map: $scope.map,
				      icon: image
				  	});
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
		})
	}          
}]);

$(document).ready(function(e) {
	$('#container-speed').highcharts({
	
	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false,
	        spacingBottom: 10,
	        spacingTop: -60,
	        spacingLeft: -20,
	        spacingRight: -20,
	    },
	    
	    title: {
	        text: ''
	    },
	    
	    pane: {
	        startAngle: -90,
	        endAngle: 90,
	        center:['50%', '100%'],
	        size: '100%',
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
	    },
	    credits: { enabled: false },
	    // the value axis
	    yAxis: {
	        min: 0,
	        max: 200,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 10,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 10,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
	        title: {
	            // text: 'km/h'
	        },
	        plotBands: [{
	            from: 0,
	            to: 120,
	            color: '#55BF3B' // green
	        }, {
	            from: 120,
	            to: 160,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: 160,
	            to: 200,
	            color: '#DF5353' // red
	        }]        
	    },
	
	    series: [{
	        name: 'Speed',
	        data: [total],
	        tooltip: {
	            valueSuffix: ' km/h'
	        }
	    }]
	
	});


  /*  var gaugeOptions = {
        chart: {
            type: 'solidgauge',
            // backgroundColor:'rgba(255, 255, 255, 0)',
            spacingBottom: -10,
	        spacingTop: -40,
	        spacingLeft: 0,
	        spacingRight: 0,
        },
        title: null,
        pane: {
            center: ['50%', '90%'],
            size: '110%',
            startAngle: -90,
            endAngle: 90,
            background: {
                innerRadius: '60%',
                outerRadius: '100%',
                shape: 'arc'
            }
        },
        tooltip: {
            enabled: false
        },
        yAxis: {
            stops: [
                [0.1, '#55BF3B'], 
                [0.5, '#DDDF0D'], 
                [0.9, '#DF5353'] 
            ],
            lineWidth: 0,
            minorTickInterval: null,
            tickPixelInterval: 400,
            tickWidth: 0,
            title: {
                y: -50
            },
            labels: {
                y: -100
            }
        },
        plotOptions: {
            solidgauge: {
                dataLabels: {
                    y: 5,
                    borderWidth: 0,
                    useHTML: true
                }
            }
        }
    };

    $('#container-fuel').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
            min: 0,
            max: 300,
            title: { text: '' }
        },
        credits: { enabled: false },
        series: [{
            name: 'Speed',
            data: [fuelLtr],
            dataLabels: {
                format: '<div style="text-align:center"><span style="font-size:12px; font-weight:normal;color: #196481'+ '">Fuel - {y} Ltr</span><br/>',
                 // y: 25
            },
            tooltip: { valueSuffix: ' Ltr'}
        }]
    }));
    setInterval(function () {
      var chart = $('#container-speed').highcharts(), point;
        if (chart) {
            point = chart.series[0].points[0];
            point.update(total);
        }
       var chartFuel = $('#container-fuel').highcharts(), point;
        if (chartFuel) {
            point = chartFuel.series[0].points[0];

            console.log(point );

            point.update(fuelLtr);
            
            console.log(point);

            if(tankSize==0)
            	tankSize =200;
                chartFuel.yAxis[0].update({
			    max: tankSize,
			}); 

        }
    }, 1000);*/
    
  var gaugeOptions = {

    chart: {
        type: 'solidgauge',
          spacingBottom: -10,
	        spacingTop: -40,
	        spacingLeft: 0,
	        spacingRight: 0,
    },

    title: null,

    pane: {
        center: ['50%', '90%'],
        size: '110%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#55BF3B'], // green
            [0.5, '#DDDF0D'], // yellow
            [0.9, '#DF5353'] // red
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: 2,
        title: {
            y: -50
        },
        labels: {
            y: -100
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 5,
                borderWidth: 0,
                useHTML: true
            }
        }
    }
};



   $('#container-fuel').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
            min: 0,
            max: 300,
            title: { text: '' }
        },
        credits: { enabled: false },
        series: [{
            name: 'Speed',
            data: [fuelLtr],
            dataLabels: {
                format: '<div style="text-align:center"><span style="font-size:12px; font-weight:normal;color: #196481'+ '">Fuel - {y} Ltr</span><br/>',
                 // y: 25
            },
            tooltip: { valueSuffix: ' Ltr'}
        }]
    }));
    setInterval(function () {
      var chart = $('#container-speed').highcharts(), point;
        if (chart) {
            point = chart.series[0].points[0];
            point.update(total);
        }
       var chartFuel = $('#container-fuel').highcharts(), point;
        if (chartFuel) {
        	chartFuel.yAxis[0].update({max:tankSize});
            point = chartFuel.series[0].points[0];
            point.update(fuelLtr);
//            if(tankSize==0)
 //           	tankSize =200;
 //           chartFuel.yAxis[0].update({
//			    max: tankSize,
//			}); 

        }
    }, 1000);

    $(document).ready(function(){
        $('#minmax1').click(function(){
            $('#contentreply').animate({
                height: 'toggle'
            },2000);
        });
    });
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
});
 
