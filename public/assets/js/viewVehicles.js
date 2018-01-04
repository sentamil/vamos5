var app = angular.module('myApp', []);

app.factory('vamoservice', function($http, $q){
  
    return {
        
        timeCalculate: function(duration){
            
            var milliseconds = parseInt((duration%1000)/100), seconds = parseInt((duration/1000)%60);
            var minutes = parseInt((duration/(1000*60))%60), hours = parseInt((duration/(1000*60*60))%24);
            
            hours    = (hours < 10) ? "0" + hours : hours;
            minutes  = (minutes < 10) ? "0" + minutes : minutes;
            seconds  = (seconds < 10) ? "0" + seconds : seconds;
            temptime = hours + " H : " + minutes +' M';
      
        return temptime;
        },
        
        dayhourmin:function(t){
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

          return [d+'D', pad(h)+'H', pad(m)+'M'].join(':');
        },

        geocodeToserver: function (lat, lng, address) {
         
         try { 
        // var reversegeourl = 'http://'+globalIP+context+'/public/store?geoLocation='+lat+','+lng+'&geoAddress='+address;
        // return this.getDataCall(reversegeourl);
         }
         catch(err){ console.log(err); }
      
        },

        getDataCall: function(url){
          var defdata = $q.defer();
          $http.get(url).success(function(data){
               defdata.resolve(data);
            }).error(function() {
                    defdata.reject("Failed to get data");
            });

          return defdata.promise;
        },

        
        statusTime:function(arrVal){

          var posTime={};
          var temptime = 0;
          var tempcaption = 'Position';

          if(arrVal.parkedTime!=0){
            temptime = this.dayhourmin(arrVal.parkedTime);
            tempcaption = 'Parked';

          }else if(arrVal.movingTime!=0){
            temptime = this.dayhourmin(arrVal.movingTime);
            tempcaption = 'Moving';

          }else if(arrVal.idleTime!=0){
            temptime = this.dayhourmin(arrVal.idleTime);
            tempcaption = 'Idle';

          }else if(arrVal.noDataTime!=0){
            temptime = this.dayhourmin(arrVal.noDataTime);
            tempcaption = 'No data';

          }
        
            posTime['temptime'] = temptime;
            posTime['tempcaption'] = tempcaption;
        
          return posTime;
        },
        
        iconURL:function(temp){

          var pinImage;
          
          if(temp.color =='P' || temp.color =='N' || temp.color =='A'){
            
            if(temp.color =='A'){
               pinImage = 'assets/imgs/orangeB.png';

            }else{
               pinImage = 'assets/imgs/'+temp.color+'.png';
            }

          } else if(temp.position == 'N') {
               pinImage =  'assets/imgs/trans.png';

          } else if(temp.position=="M" && temp.ignitionStatus=="OFF"){
               pinImage = 'assets/imgs/P.png'; 

          } else{
               pinImage = 'assets/imgs/'+temp.color+'_'+temp.direction+'.png';
          }
      
          return pinImage;
        }, 

        markerImage:function(temp){

            var pinImage;

            if(temp.color =='P' || temp.color =='N' || temp.color =='A'){

                if(temp.color =='A'){
                   // pinImage = 'assets/imgs/orangeB.png';
                      pinImage = 'assets/imgs/vehicle-marker/'+temp.vehicleType+'_Idle.png';

                }else{
                   // pinImage = 'assets/imgs/'+temp.color+'.png';
                      pinImage = 'assets/imgs/vehicle-marker/'+temp.vehicleType+'_'+temp.color+'.png';
                }

              } else if(temp.position == 'N') {
                     pinImage =  'assets/imgs/trans.png';
                 //  pinImage =  'assets/imgs/vehicle-marker/'+temp.vehicleType+'_N.png';
              } else if(temp.position=="M" && temp.ignitionStatus=="OFF"){
                // pinImage = 'assets/imgs/P.png';
                   pinImage = 'assets/imgs/vehicle-marker/'+temp.vehicleType+'_P.png';
               
              } else{
               //  pinImage = 'assets/imgs/'+temp.color+'_'+temp.direction+'.png';
                   pinImage = 'assets/imgs/vehicle-marker/'+temp.vehicleType+'_'+temp.color+'.png';
              }

          return pinImage;
        },


    }  
});

app.constant("globe", {
  'DOMAIN_NAME' : '//'+globalIP+context+'/public', 
});



app.controller('newPlaceCtrl',['$scope', '$http', 'globe', 'vamoservice',  '$filter', '$compile',function($scope, $http, GLOBAL, vamoservice,  $filter, $compile) {

 
$scope.days=0;
$scope.days1=0;
$scope.name = "Calvin";
//$scope.groupid = 0;
$scope.Filter = 'ALL';
$scope.marker = [];
$scope.zoom = 6;
$scope.vehiclFuel=true;
$scope._editValue_con   = false;
$scope._editValue   = {};

var res  = document.location.href;
var res2 = res.split('&');

var gNames=res2[2].split('=');
//var res3 = res2[1].split('=');

 //if(res3[1]==""){
//alert(res3[1]);
$scope.urlName = GLOBAL.DOMAIN_NAME+'/getVehiLocation?'+res2[1]+'&'+res2[2];
 //} else{
 //  $scope.urlName = GLOBAL.DOMAIN_NAME+'/getVehiLocation?'+res2[1];
 //}


//console.log($scope.urlName);

$scope.vehinames="";

$("#contentmin").hide();


 var startLoading    = function () {
     $('#statusLoad').show(); 
 };

// loading stop function
 var stopLoading   = function () {
    $('#statusLoad').fadeOut(); 
 };

$scope.trimColon = function(textVal){
    if (textVal != null || textVal != undefined ){
       var vals = textVal.split(":");
      return vals[0];
    }
}


       $http({
            method : "GET",
            url : $scope.urlName
        }).then(function mySuccess(response) {

           $scope.data = response.data;

          // console.log(response.data);
            for(i=0;i<$scope.data.length;i++){
               if( $scope.data[i].group == gNames[1] ){

                $scope.groupid = parseInt($scope.data[i].rowId);  

               }
            }

            
        /*  $scope.totalVehicles = response.data[$scope.groupid]['totalVehicles'];
            $scope.vehicleOnline = response.data[$scope.groupid]['online'];
            $scope.attention = response.data[$scope.groupid]['attention'];
            $scope.parkedCount = response.data[$scope.groupid]['totalParkedVehicles'];
            $scope.movingCount = response.data[$scope.groupid]['totalMovingVehicles'];
            $scope.idleCount = response.data[$scope.groupid]['totalIdleVehicles'];
            $scope.overspeedCount = response.data[$scope.groupid]['topSpeed'];*/
            
            $scope.vehiname = response.data[$scope.groupid].vehicleLocations[0].vehicleId;
            $scope.gName    = response.data[$scope.groupid].group;
            sessionValue($scope.vehiname, $scope.gName);
            $scope.apiKeys=response.data[$scope.groupid].apiKey;

            $scope.initilize('mapLoc');

        }, function myError(response) {
            $scope.myWelcome = response.statusText;
        });



    $scope.initilize = function(ID){
       
        $scope.locations04=[];
        $scope.vehicle_list=[];

        for(i=0;i<$scope.data.length;i++){
           val = $scope.data[i];
           $scope.locations04.push({rowId:val.rowId,group:val.group});

            if($scope.gName == val.group) { 

              $scope.groupid=val.rowId;

                angular.forEach(val.vehicleLocations, function(val, k){

                    $scope.vehicle_list.push({'vehiID' : val.vehicleId, 'vName' : val.shortName});

                    if(val.vehicleId == $scope.vehiname){
                      $scope.selected     = k;
                      $scope.shortVehiId  = val.shortName;
                    }
                });
       
            } 

           if(typeof $scope.locations04[$scope.groupid] != 'undefined')
           {
           $scope.locations04[$scope.groupid].vehicleLocations=[];
           
           }
           $scope.locations04.vehicleLocations = [];
        }

        stopLoading();

        var mapOptions={
          zoom: $scope.zoom,
          zoomControlOptions:{position: google.maps.ControlPosition.LEFT_TOP},
          center: new google.maps.LatLng($scope.data[$scope.groupid]['latitude'], $scope.data[$scope.groupid]['longitude']),
          mapTypeId: google.maps.MapTypeId.ROADMAP,
        };
       
        $scope.map = new google.maps.Map(document.getElementById(ID), mapOptions);

        $(document).on('pageshow', '#mapLoc', function(e){       
          google.maps.event.trigger(document.getElementById('mapLoc'), "resize");
        });


        //console.log($scope.data[$scope.groupid].vehicleLocations);

        $scope.setMarkers($scope.data[$scope.groupid].vehicleLocations);

        $("#contentmin").show();
   
   };


    $scope.groupSelection = function(val){
         
    //  console.log('hello world..');
     // console.log(val);

      $scope.vehinames="";
      $scope.zoom = 6;
      $scope.Filter = 'ALL';

      var grpNam = val;
     

      $scope.urlName = GLOBAL.DOMAIN_NAME+'/getVehiLocation?'+res2[1]+'&group='+grpNam;


          $http({
            method : "GET",
            url : $scope.urlName,
          }).then(function mySuccess(response) {

           // console.log(response.data);

            $scope.data=[];
            $scope.data=response.data;

            $scope.locations04  = [];
            $scope.vehicle_list = [];

            angular.forEach(response.data, function(value, key){

              $scope.locations04.push({rowId:value.rowId,group:value.group});

               if(grpNam == value.group){ 

             
                 $scope.groupid=value.rowId;

                angular.forEach(value.vehicleLocations, function(val, k){

                  $scope.vehicle_list.push({'vehiID' : val.vehicleId, 'vName' : val.shortName});

                      if(val.vehicleId == $scope.vehiname){
                           $scope.selected     = k;
                           $scope.shortVehiId  = val.shortName;
                       }
                });
          //$scope.locations = response;
               } 
             });     

            $scope.initilize('mapLoc');

        }, function myError(response) {
            $scope.myWelcome = response.statusText;
        });
};   


 $scope.genericFunction = function(val, rowId){

 // console.log(val);
        
    //angular.forEach($scope.locations, function(value, key){
    //if($scope.zohoReports==undefined){    
     
      $scope._editValue_con   =   true;

      individualVehicle = $filter('filter')($scope.data[$scope.groupid].vehicleLocations, { vehicleId:  val.vehiID});
      $scope.individualVehicle = individualVehicle[0];
       // console.log(individualVehicle);
        //$scope.clearMarkers();
       // console.log("Selected Value: " + $scope.vehicleStatus.id + "\nSelected Text: " + $scope.vehicleStatus.name);
      $scope.Filter = 'SINGLE';
      $scope.zoom = 15;
      $scope.setMarkers(individualVehicle, individualVehicle[0].address);

      //$scope.assignValue(individualVehicle[0]);

        if(individualVehicle[0].address == null || individualVehicle[0].address == undefined || individualVehicle[0].address == ' ')
          $scope.getLocation(individualVehicle[0].latitude, individualVehicle[0].longitude, function(count){ 
            $('#lastseen').text(count); 
          });
        else
          $('#lastseen').text(individualVehicle[0].address.split('<br>Address :')[1] ? individualVehicle[0].address.split('<br>Address :')[1] : individualVehicle[0].address);
       
  };   

 
 function sessionValue(vid, gname){
      sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
      $("#testLoad").load("../public/menu");
  }


$scope.onCategoryChange = function () {
    $scope.zoom = 6;
   $scope.clearMarkers();
   // console.log("Selected Value: " + $scope.vehicleStatus.id + "\nSelected Text: " + $scope.vehicleStatus.name);
   $scope.Filter = $scope.vehicleStatus;
   $scope.locations04[$scope.groupid].vehicleLocations=[];
  
   $scope.setMarkers($scope.data[$scope.groupid].vehicleLocations);
   

};  


$scope.clearMarkers = function(){
    for (var i = 0; i < $scope.marker.length; i++) {
          $scope.marker[i].setMap(null);
        }
    $scope.markerCluster.clearMarkers();
    $scope.marker = []; 
};
//view map


$scope.setMarkers = function(req_data, address){
        //var image = {
        //  url: 'assets/imgs/G_E.png'
      //  };
        
        $scope.markers = req_data.map(function(location, i) {
         if($scope.Filter != 'SINGLE' && $scope.Filter != 'ALL' && $scope.Filter != location.position && $scope.Filter != 'Y' && $scope.Filter != 'ON' && $scope.Filter != 'OFF')
            return 
         else if($scope.Filter != 'SINGLE' && $scope.Filter != 'ALL' && (($scope.Filter == 'ON' || $scope.Filter == 'OFF') && location.status != $scope.Filter))
            return;
        else if($scope.Filter != 'SINGLE' && $scope.Filter != 'ALL' && ($scope.Filter == 'Y' && location.isOverSpeed != $scope.Filter))
            return;
        if($scope.Filter != 'SINGLE'){
                $scope.locations04[$scope.groupid].vehicleLocations.push(location)
                $scope.locations04.vehicleLocations.push(location)
        }
          //}
         //ar infowindow = null;
          if (typeof infowindow != 'undefined') {
                infowindow.close();
            }
          var markertemp = new google.maps.Marker({
            position: new google.maps.LatLng(location['latitude'], location['longitude']),
            map: $scope.map,
            icon: vamoservice.iconURL(location),
            labelContent :location['shortName'],
            labelAnchor: new google.maps.Point(19, 0),
            labelClass: "maps", 
            labelInBackground: false
          });
          if($scope.Filter != 'SINGLE'){
            google.maps.event.addListener(markertemp, "click", function(e) {
                if (typeof infowindow != 'undefined') {
                infowindow.close();
            }
             $scope.$apply(function () {
                    $scope._editValue_con   =   true;
                  //$scope.individualVehicle = location;
                  //$scope.assignValue(location);
             });

            infowindow = new InfoBubble({
              minWidth: 240,  
              maxHeight: 140,
              content: $scope.infoContent(location)[0],

            });
            infowindow.open($scope.map, markertemp);
          //$scope.genericFunction(location['vehicleId'], location['rowId']);
            });
          }         
           $scope.marker.push(markertemp);
           if(address != undefined){
            infowindow = new InfoBubble({
              minWidth: 245,  
              maxHeight: 152,
              content: $scope.infoContent(location)[0]
            });
            infowindow.open($scope.map, markertemp);
           }
           $scope.map.setZoom($scope.zoom);
          $scope.map.setCenter(markertemp.position);
           return markertemp;
        })
        
        $scope.markerCluster = new MarkerClusterer($scope.map,  $scope.markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
    };  


$scope.getLocation = function(lat,lon, callback){
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(lat, lon);
        geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              if (results[1]) {
                if(typeof callback === "function") callback(results[1].formatted_address)
              }
            }
        });
    };


$scope.infoContent = function(data){


      var tempoTime = vamoservice.statusTime(data);
      if(data.ignitionStatus=='ON'){
        var classVal = 'green';
      }else{
        var classVal = 'red';
      }
      
      var contentString = '<div style="width:auto;font-family:Lato;min-height:auto;">'
      +'<div><b class="_info_caption" >Vehicle Name</b> - <span style="font-weight:bold;">'+data.shortName+'</span></div>'
    //+'<div><b >ODO Distance</b> - '+data.odoDistance+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
      +'<div><b class="_info_caption">Today Distance</b> - '+data.distanceCovered+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
      +'<div><b class="_info_caption">'+vamoservice.statusTime(data).tempcaption+'</span></b> - '+vamoservice.statusTime(data).temptime+'</div>'
      +'<div><b class="_info_caption">ACC Status</b> - <span style="color:'+classVal+'; font-weight:bold;">'+data.ignitionStatus+'</span> </div>'
      +'<div><b class="_info_caption">Loc Time</b> - <span>'+$filter('date')(data.date, "dd-MMM-yy HH:mm")+'</span> </div>'
      +'<div ><b class="_info_caption">Comm Time</b> - <span>'+$filter('date')(data.lastComunicationTime, "dd-MMM-yy HH:mm")+'</span> </div>'
    //+'<div style="padding-top:5px;"><a href="history?vid='+data.vehicleId+'&vg='+$scope.gName+'">Reports</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+data.vehicleId+'&track=single&maps=single" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/track?maps=replay&vehicleId='+data.vehicleId+'&gid='+$scope.gName+'" target="_blank">History</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+data.vehicleId+'&track=multiTrack&maps=mulitple" target="_blank">MultiTrack</a>&nbsp;&nbsp; <a href="#" ng-click="addPoi('+data.latitude+','+data.longitude+')">Site</a>'
    //+'<div style="padding-top:5px;"><a href="history?vid='+vehicleID+'&vg='+$scope.gName+'">Reports</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+vehicleID+'&track=single&maps=single" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/track?maps=replay&vehicleId='+vehicleID+'&gid='+$scope.gName+'" target="_blank">History</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+vehicleID+'&track=multiTrack&maps=mulitple" target="_blank">MultiTrack</a>&nbsp;&nbsp; <a href="#" ng-click="addPoi('+lat+','+lng+')">Save Site</a>'
      +'<div style="max-width:240px;word-break: break-all;text-align:center;padding-top:3px;border-top: 1px solid #eee;">'+data.address+'</div>'
      +'</div>';

        var compiled = $compile(contentString)($scope);
     // var  drop1 = document.getElementById("ddlViewBy");
     // var drop_value1= drop1.options[drop1.selectedIndex].value;
    return compiled;
};  


$scope.starSplit    =   function(val){

 var splitVal;  

    if(val!=undefined){
    splitVal=val.split('<br>');
    }else{
    splitVal='No Address';
    }

 return splitVal;  
}


}]);
  


