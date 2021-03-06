app.directive('map', function($http, vamoservice) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {
   
        }
    };
});

app.directive('maposm', function($http, vamoservice) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs){

        // console.log('osm map directive...');

        }
    }
});

app.controller('mainCtrl',['$scope', '$http', 'vamoservice', '_global', function($scope, $http, vamoservice, GLOBAL){ 

    
    
    $scope.lineCount_osm = 0;
    $scope.newArr        = [];
    $scope.mapInitOsm    = 0;
    $scope.polyInit      = 0;
    $scope.osmPaths      = [];
    //$scope.polyline      = [];

    var lineCountOsm     = 0;
    var markerInit       = 0;


    var mapsVal=sessionStorage.getItem('mapNo');
    //console.log(mapsVal);

    if(mapsVal==0){
       $scope.maps_no  = 0;
       $scope.mapsHist = 0;

       document.getElementById("map_canvas2").style.display="none"; 
         document.getElementById("map_canvas").style.display="block"; 
    }else if(mapsVal==1){
       $scope.maps_no  = 1;
       $scope.mapsHist = 1;

       document.getElementById("map_canvas").style.display="none"; 
         document.getElementById("map_canvas2").style.display="block"; 
    }


    

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
    
    var np  = [];
    var npl  = null;
    var npls  = null;
    var nplen  = null;
    var linesCount = 1;  
    var initsvalss = 0;
	
	$('#graphsId').hide();

    mapInit();

	function lineDraw(lat, lan) {
             
         // if(data.isOverSpeed=='N'){
	     // var strokeColorvar = '#00b3fd';
		 // }else{
		 // var strokeColorvar = '#ff0000';
		 // }
		 // var latlng1 = new google.maps.LatLng(lat, lan);
		 // console.log(' value -->'+ latlng1);
					
				 $scope.slatlong = new google.maps.LatLng(lat, lan);

                 np.push(new google.maps.LatLng(lat, lan));

				 $scope.polyline = new google.maps.Polyline({
								map: $scope.map,
								path: [$scope.elatlong, $scope.slatlong],
								strokeColor: '#00b3fd',
								strokeOpacity: 0.7,
								strokeWeight: 5,
								
								clickable: true
						  	});
				 //$scope.polylines.setMap($scope.map);
				 $scope.elatlong = $scope.slatlong;
    }

    
    function timesInterval(){

		    vamoservice.getDataCall($scope.url).then(function(data) {

                if($scope.path.length==1){
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
					// zoom: 13,
					// center: new google.maps.LatLng(locs.latitude, locs.longitude),
					// mapTypeId: google.maps.MapTypeId.ROADMAP
	                // };
            		
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
						$scope.getLocation(locs.latitude, locs.longitude, function(count){
							$('#lastseentrack').text(count);
							data.address = count;
							$scope.addres = count;  
                             
                            if(data.position == 'M'){
		   					    $scope.histVal.unshift(data);
                            }
		   				    else{
		   					    $scope.histVal[0]=data;
		   				    }

						});
					}
					else{
						$('#lastseentrack').text(data.address);
						$scope.addres = data.address;

                         if(data.position == 'M'){
		   					$scope.histVal.unshift(data);
                         }
		   				 else{
		   					$scope.histVal[0]=data;
		   				}
                    }

                    $scope.path.push(new google.maps.LatLng(data.latitude, data.longitude));
                      
           			
					if($scope.path.length>1){
					 	var latLngBounds = new google.maps.LatLngBounds();
						latLngBounds.extend($scope.path[$scope.path.length-1]);
					}
					var labelAnchorpos = new google.maps.Point(-40, -30);
				    var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
				
					// scope.map.setCenter(scope.marker.getPosition());

	
  if(($scope.path[linesCount].lat()+','+$scope.path[linesCount].lng())!=($scope.path[linesCount+1].lat()+','+$scope.path[linesCount+1].lng())){

	$scope.marker.setMap(null);
    $scope.rotationd=getBearing($scope.path[linesCount].lat(),$scope.path[linesCount].lng(),$scope.path[linesCount+1].lat(),$scope.path[linesCount+1].lng());
 
		$scope.marker = new MarkerWithLabel({
					   position: myLatlng, 
					   map: $scope.map,
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
				          rotation: $scope.rotationd,				 
				         },
					   labelContent: data.shortName,
				       labelAnchor: labelAnchorpos,
					   labelClass: "labels",
					   labelInBackground: false
					});
                }

					$scope.map.setCenter(myLatlng);
					var contentString = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
						+'<div style="width:200px; display:inline-block;"><b>Address</b> - <span>'+$scope.addres+'</span></div></div>';

					var infowindow = new google.maps.InfoWindow({
					    content: contentString
					});


					google.maps.event.addListener($scope.marker, "click", function(e) {
						infowindow.open($scope.map, $scope.marker);
					});
					
					$scope.endlatlong = new google.maps.LatLng(data.latitude, data.longitude);
					
				  	if(data.isOverSpeed=='N'){
						var strokeColorvar = '#00b3fd';
					}else{
						var strokeColorvar = '#ff0000';
					}
         			
				  	$scope.polyline = new google.maps.Polyline({
			            map: $scope.map,
			            path: [$scope.startlatlong, $scope.endlatlong],
			            strokeColor: strokeColorvar,
			            strokeOpacity: 0.7,
			            strokeWeight: 5
			        });
			       // $scope.polylines.setMap($scope.map);
			        
			        $scope.startlatlong = $scope.endlatlong;
			        google.maps.event.trigger(document.getElementById('maploc'), "resize");

			        if($scope.histVal.length > 200){
		   				$scope.histVal = $scope.histVal.slice(0, $scope.histVal.length - 50);
			        }

               	linesCount++;	
		   		});

      




      }

          

        function lineDraw_Osm(data) {
        	
        	//console.log(data.length);

        	for(var i=0;i<data.length;i++){

        		//console.log(i);

        		if(i!=data.length-1){

        		var lat1=data[i];
        		  var lat2=data[i+1];

        		}else{
                   
                 var lat1=data[i];
        		  var lat2=data[i];
        		}

    		  var nVars     = [lat1,lat2];
			  var polylines = L.polyline(nVars, {color:'#00b3fd'}).addTo($scope.map_osm);
            }
       	}

         

	    function mapInit() {

	       // console.log('googMapInit......');

            vamoservice.getDataCall($scope.url).then(function(data) {

              	//console.log(data);

		   	    var locs     = data;
		   	    $scope.locss = data;
               
				$('#vehiid span').text(locs.shortName);
				$('#toddist span span').text(locs.distanceCovered);
				// total = parseInt(locs.speed);
				$('#deviceVolt span').text(locs.deviceVolt);
				$('#vehdevtype span').text(locs.odoDistance);
				$('#mobno span').text(locs.overSpeedLimit);
				
				$('#graphsId #speed').text(locs.speed);
				$('#graphsId #fuel').text(locs.tankSize);

				tankSize   = parseInt(locs.tankSize);
				fuelLtr    = parseInt(locs.fuelLitre);
				total  	   = parseInt(locs.speed);
				
				if((data && data != '') && (data.address == null || data.address == undefined || data.address == ' ')){
					$scope.getLocation(locs.latitude, locs.longitude, function(count){
						$('#lastseentrack').text(count);
						data.address = count;
						$scope.addres = count;  
 
                        if(data.position == 'M'){
			            	$scope.histVal.unshift(data);
                        }else{
			            	$scope.histVal[0]=data;
		                }

					});
			    } else {
						$('#lastseentrack').text(data.address);
						$scope.addres = data.address;
                        
                        if(data.position == 'M'){
			            	$scope.histVal.unshift(data);
                        }else{
			            	$scope.histVal[0]=data;
		                }
					} 
				
				$('#positiontime').text(vamoservice.statusTime(locs).tempcaption);
				$('#regno span').text(vamoservice.statusTime(locs).temptime);
				
				
			   	$scope.speedval.push(data.speed);

           
        if($scope.maps_no==0){ 

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
            	
            	$scope.map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);

				google.maps.event.addListener($scope.map, 'click', function(event) {
					$scope.clickedLatlng = event.latLng.lat() +','+ event.latLng.lng();
					$('#latinput').val($scope.clickedLatlng);
				});


            $scope.path.push(new google.maps.LatLng(data.latitude, data.longitude));

			var labelAnchorpos  = new google.maps.Point(20, -30);
		    var myLatlng        = new google.maps.LatLng(data.latitude, data.longitude);
				
			$scope.startlatlong = new google.maps.LatLng(data.latitude, data.longitude);
			$scope.endlatlong   = new google.maps.LatLng(data.latitude, data.longitude);

		 	if($scope.path.length>1){
			 	var latLngBounds = new google.maps.LatLngBounds();
				latLngBounds.extend($scope.path[$scope.path.length-1]);
			}

	      (function latlan(){

	       vamoservice.getDataCall($scope.urlVeh).then(function(response) {

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

                         	if((np[npls-1].lat()+','+np[npls-1].lng())!=($scope.path[0].lat()+','+$scope.path[0].lng())){

                               nplen=npls-1;
                               break;

                             } else {

                             nplen=null;

                             $scope.marker = new MarkerWithLabel({
			   	                   position: myLatlng, 
				                   map: $scope.map,
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

            	                google.maps.event.addListener($scope.marker, "click", function(e) {
					                 infowindow.open($scope.map, $scope.marker);
				                });
      	             	   // }
                        }
                    }
              
                 }//for ends... 

         
        if(nplen!=null) {
 
                var rotationd=getBearing(np[nplen].lat(),np[nplen].lng(),$scope.path[0].lat(),$scope.path[0].lng());

                $scope.marker = new MarkerWithLabel({
			   	   position: myLatlng, 
				   map: $scope.map,
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

            	google.maps.event.addListener($scope.marker, "click", function(e) {
					infowindow.open($scope.map, $scope.marker);
				   });

                $scope.path.push(new google.maps.LatLng(np[nplen].lat(),np[nplen].lng()));
             }
                 
          } else {

                 //console.log('latlanOld data not found.....');
  
                    $scope.marker = new MarkerWithLabel({
			   	    position: myLatlng, 
				    map: $scope.map,
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
				      //  rotation: rotationd,				 
				         },
				    labelContent: data.shortName,
			        labelAnchor: labelAnchorpos,
			     // labelAnchor: vehicIcon[2],
				    labelClass: "labels", 
				    labelInBackground: false
				  });

            	    google.maps.event.addListener($scope.marker, "click", function(e) {
					  infowindow.open($scope.map, $scope.marker);
				    });

            } // if else ends...     

        }); // lanlan vamoservice ends...

    }()); // latlan func ends...


	    var contentString = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
						+'<div style="width:200px; display:inline-block;"><b >Address</b> - <span>'+$scope.addres+'</span></div></div>';

		var infowindow = new google.maps.InfoWindow({
				    content: contentString
		});

		 /* google.maps.event.addListener(scope.marker, "click", function(e){
				infowindow.open(scope.map, scope.marker);
			}); */

     // Global section ...

        $(document).on('pageshow', '#maploc', function(e, data){       
            google.maps.event.trigger(document.getElementById('	maploc'), "resize");
        });

// Global section ends...

   $scope.timesIntervalss = setInterval(function(){ timesInterval() }, 10000);


} else if($scope.maps_no == 1){

	//console.log('osm map tracking.....');


       var mapLink = '<a href="http://207.154.194.241/nominatim/lf.html">OpenStreeetMap</a>'
        $scope.map_osm = new L.map('map_canvas2',{ center:new L.LatLng($scope.locss.latitude, $scope.locss.longitude),/* minZoom: 4,*/zoom: 13 });

          new L.tileLayer(
            'http://207.154.194.241/osm_tiles/{z}/{x}/{y}.png', {
             attribution: '&copy; '+mapLink+' Contributors',
          // maxZoom: 18,
          }).addTo($scope.map_osm); 

        $scope.myLatLngOsm = new L.LatLng($scope.locss.latitude, $scope.locss.longitude);

        $scope.markerss = new L.marker().bindLabel($scope.locss.shortName, { noHide: true });
        $scope.infowin_osm = new L.popup({maxWidth:800});

        var myIcon1 = L.icon({
            iconUrl:vamoservice.iconURL($scope.locss),
            iconSize: [40,40], 
            iconAnchor: [20,40],
            popupAnchor: [-1, -40],
        });

        var conString_osm = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
						+'<div style="width:200px;overflow-wrap: break-word; display:inline-block;"><b >Address</b> - <span>'+$scope.addres+'</span></div></div>';

        $scope.infowin_osm.setContent(conString_osm);
        $scope.markerss.bindPopup($scope.infowin_osm);
        $scope.markerss.setIcon(myIcon1);

        $scope.markerss.addEventListener('click', function(e) {
         
           $scope.markerss.openPopup();
        });

        (function latlan_osm(){

	        vamoservice.getDataCall($scope.urlVeh).then(function(response) {

	            if(response.latLngOld != undefined){

	                for (var i = 0; i < response.latLngOld.length; i++) {

                      var sp_osm = response.latLngOld[i].split(',');
				      $scope.newArr.push( new L.LatLng(sp_osm[0], sp_osm[1]) );
				    }

				    lineDraw_Osm($scope.newArr);

				    var setLatlng = $scope.newArr[$scope.newArr.length-1];
				 
                    $scope.markerss.setLatLng(setLatlng);
                    $scope.markerss.addTo($scope.map_osm);
                    $scope.map_osm.setView(setLatlng); 
		        } else {

                    $scope.markerss.setLatLng($scope.myLatLngOsm);
                    $scope.markerss.addTo($scope.map_osm);
                    $scope.map_osm.setView($scope.myLatLngOsm); 

		        }

            }); // lanlan vamoservice ends...

    }()); // latlan func ends...

    $scope.osmTimeInterval = setInterval(function(){ osmIntervals() }, 10000);

  }

 }); // first vamoservice ends..... 

}


    function osmIntervals(){

        vamoservice.getDataCall($scope.url).then(function(data) {


		   		 /* if(data != '' && data){
		   				if(data.position == 'M')
		   					scope.histVal.unshift(data);
		   				else
		   					scope.histVal[0]=data;
		   			} */
		   			
		   			var locs = data;
     
            		$('#vehiid span').text(locs.shortName);
					$('#toddist span span').text(locs.distanceCovered);
					$('#vehstat span').text(locs.position);
					$('#deviceVolt span').text(locs.deviceVolt);
					$('#vehdevtype span').text(locs.odoDistance);
					$('#mobno span').text(locs.overSpeedLimit);
					$('#graphsId #speed').text(locs.speed);
					$('#graphsId #fuel').text(locs.tankSize);
					
					tankSize  =  parseInt(locs.tankSize);
					fuelLtr   =  parseInt(locs.fuelLitre);
					total  	  =  parseInt(locs.speed);

					$('#positiontime').text(vamoservice.statusTime(locs).tempcaption);
					$('#regno span').text(vamoservice.statusTime(locs).temptime);
				 
				 // $scope.getLocation(locs.latitude, locs.longitude, function(count){
					// $('#lastseentrack').text(count); 
				 // });

					if((data && data != '') && (data.address == null || data.address == undefined || data.address == ' ')){
						$scope.getLocation(locs.latitude, locs.longitude, function(count){
							$('#lastseentrack').text(count);
							data.address  = count;
							$scope.addres = count;  
                             
                            if(data.position == 'M') {
		   					    $scope.histVal.unshift(data);
                            } else {
		   					    $scope.histVal[0]=data;
		   				    }

						});
					
					} else {

						$('#lastseentrack').text(data.address);
						$scope.addres = data.address;

                        if(data.position == 'M') {
		   					$scope.histVal.unshift(data);
                        } else {
		   					$scope.histVal[0]=data;
		   				}
                    }

                  //  console.log('osm intervals.....');

                  if($scope.polyInit==0){
                     if($scope.newArr.length>0){
                        $scope.osmPaths.push($scope.newArr[$scope.newArr.length-1]);
                     }
                     $scope.polyInit++;
                   }  

                    $scope.osmPaths.push(new L.LatLng(data.latitude,data.longitude));
                    var markerLatLng  = new L.LatLng(data.latitude,data.longitude);


                    if(markerInit==0){
                    	var myIcons = L.icon({
                          iconUrl:vamoservice.iconURL(data),
                          iconSize: [40,40], 
                          iconAnchor: [20,40],
                          popupAnchor: [-1, -40],
                        });

 
                       $scope.markerss.setIcon(myIcons);
                       $scope.markerss.setLatLng(markerLatLng);
                       $scope.markerss.addTo($scope.map_osm);

                   markerInit++;
                  }

 
    if($scope.osmPaths.length>1){

    	if( $scope.osmPaths[lineCountOsm].lat!=$scope.osmPaths[lineCountOsm+1].lat && $scope.osmPaths[lineCountOsm].lng!=$scope.osmPaths[lineCountOsm+1].lng ){

            var myIcons = L.icon({
                iconUrl:vamoservice.iconURL(data),
                iconSize: [40,40], 
                iconAnchor: [20,40],
                popupAnchor: [-1, -40],
            });

            var conString_osm = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
						+'<div style="width:200px;overflow-wrap: break-word; display:inline-block;"><b >Address</b> - <span>'+$scope.addres+'</span></div></div>';

		    $scope.infowin_osm.setContent(conString_osm);
            $scope.markerss.bindPopup($scope.infowin_osm);

            $scope.markerss.setIcon(myIcons);
            $scope.markerss.setLatLng(markerLatLng);

            var nVarss=[$scope.osmPaths[lineCountOsm],$scope.osmPaths[lineCountOsm+1]];
            var polylines  = L.polyline(nVarss, {color:'#00b3fd'}).addTo($scope.map_osm);
   
              
        $scope.map_osm.panTo(markerLatLng);
      }

    lineCountOsm++;

    }

   });
                   
 }


    $scope.mapChanges=function(val){

        $scope.maps_no = val;

        if($scope.maps_no == 0){

        	//console.log('google...');

              clearInterval($scope.osmTimeInterval);
              $scope.timesIntervalss = setInterval(function(){ timesInterval() }, 10000);

              document.getElementById("map_canvas2").style.display="none"; 
              document.getElementById("map_canvas").style.display="block"; 

        } else if($scope.maps_no == 1) {

         /* if($scope.map_osm != null){
                 $scope.map_osm.remove();
            } */


            if($scope.mapInitOsm!=0){

                clearInterval($scope.timesIntervalss);
                $scope.osmTimeInterval = setInterval(function(){ osmIntervals() }, 10000);
            }

            if($scope.mapInitOsm==0){
            	clearInterval($scope.timesIntervalss);
                mapInit();
              $scope.mapInitOsm++; 
            }

            document.getElementById("map_canvas").style.display="none"; 
              document.getElementById("map_canvas2").style.display="block"; 

        }
    }


    var markerSearch  = new google.maps.Marker({});
    var input_value   =  document.getElementById('pac-inputs');
    var sbox          =  new google.maps.places.SearchBox(input_value);
  // search box function
    sbox.addListener('places_changed', function() {

    if($scope.timesIntervalss){
      window.clearInterval($scope.timesIntervalss);	
    }  

    if($scope.osmTimeInterval){
      window.clearInterval($scope.osmTimeInterval);	
    } 

    markerSearch.setMap(null);
    var places = sbox.getPlaces();
    markerSearch = new google.maps.Marker({
        position: new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()),
        animation: google.maps.Animation.BOUNCE,
        map: $scope.map,
    });
      console.log(' lat lan  '+places[0].geometry.location.lat(), places[0].geometry.location.lng())
      $scope.map.setCenter(new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()));
      $scope.map.setZoom(13);
    });


	function saveAddressFunc(val, lat, lan){
    //console.log(val);
      var saveAddUrl = GLOBAL.DOMAIN_NAME+'/saveAddress?address='+encodeURIComponent(val)+'&lattitude='+lat+'&longitude='+lan+'&status=web';
    //console.log(saveAddUrl);

      $http({
        method: 'GET',
        url: saveAddUrl
      }).then(function successCallback(response) {
          if(response.status==200){
              console.log("Save address successfully!..");
          }
      }, function errorCallback(response) {
         console.log(response.status);
      });

   }


	$scope.getLocation = function(lat, lon, callback){
		geocoder   = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(lat,lon);
		geocoder.geocode({'latLng': latlng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
                if(results[0]) {
                  var newVals = vamoservice.googleAddress(results[0]);   
                     saveAddressFunc(newVals, lat, lon);
                } 

			  if (results[1]) {
				if(typeof callback === "function") callback(results[1].formatted_address)
			  } else {
				//alert('No results found');
			  }
			} 
		  });
    };


	vamoservice.getDataCall($scope.url).then(function(data) {

	    $scope.locations = data;
	/*	var locs=data;
	        $scope.getLocation(locs.latitude, locs.longitude, function(count){
				data.address = count;
			 // $scope.addres = count;  
 
                    if(data.position == 'M'){
		   			    $scope.histVal.push(data);
                    }
		   		    else{
		   			    $scope.histVal[0]=data;
		   		    }
			}); */

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
	
	$scope.checkme = function(val){
		if($scope.checkVal==false){
			document.getElementById(val).style.backgroundColor = "yellow"
			$scope.trafficLayer.setMap($scope.map);
			$scope.checkVal = true;
		}else{
			document.getElementById(val).style.backgroundColor = "#FFFFFF"
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
    //  if(tankSize==0)
    //     tankSize =200;
    //     chartFuel.yAxis[0].update({
    //	   max: tankSize,
    //  }); 

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


