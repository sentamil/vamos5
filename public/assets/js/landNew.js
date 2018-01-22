var globalIP = document.location.host;
 var context = '/gps';
 
 var total    = 0;
 var tankSize = 0;
 var fuelLtr  = 0;
 var chart    = null;

 var apps = angular.module('mapApps', []);

 apps.factory('vamoservice', function($http, $q){
  
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

        googleAddress:function(data) {

            var tempVar = data.address_components;
            var strNo  = 'sta:null';
            var rotNam = 'rot:null';
            var locs   = 'loc:null';
            var add1   = 'ad1:null';
            var add2   = 'ad2:null';
            var coun   = 'con:null';
            var postal = 'pin:null';

          if(tempVar!=null || tempVar.length!=0){    

            for(var i=0;i<tempVar.length;i++){
             //console.log(newVarr[i].types);
              for(var j=0;j<tempVar[i].types.length;j++){
               //console.log(newVarr[i].types[j]);
                //console.log(newVarr[i].long_name);
              var valType = tempVar[i].types[j];
              var valName = tempVar[i].long_name;
        
              switch(valType){
        
                case "street_number":
                 //console.log("stn : "+valName);
                  strNo ='sta:'+valName;
                break;
                case "route":
                 //console.log("rot : "+valName);
                  rotNam='rot:'+valName;
                break;
                case "neighborhood":
                  //console.log("neigh : "+valName);
                  //retVar+='nei:'+valName;
                break;
                /*case "sublocality":
                  //console.log("loc : "+valName);
                  retVar+='loc:'+valName+' ';
                break;*/
                case "locality":
                  //console.log("loc : "+valName);
                  locs='loc:'+valName;
                break;
                case "administrative_area_level_1":
                  //console.log("ad1 : "+valName);
                  add1='ad1:'+valName;
                break;
                case "administrative_area_level_2":
                  //console.log("ad2 : "+valName);
                  add2='ad2:'+valName;
                break;
                case "country":
                  //console.log("con : "+valName);
                  coun='con:'+valName;
                break;
                case "postal_code":
                  //console.log("pin : "+valName);
                  postal='pin:'+valName;
                break;
              }
        
             }
           }

          }

         var retVar = strNo+' '+rotNam+' '+locs+' '+add1+' '+add2+' '+coun+' '+postal;
          //console.log(retVar);

        return retVar;
        },

    }  
});

apps.constant("globe", {
  'DOMAIN_NAME' : '//'+globalIP+context+'/public', 
});

apps.controller('mainCtrls', ['$scope', '$http','vamoservice', 'globe', '$filter', '$compile', function($scope, $http, vamoservice, GLOBAL, $filter, $compile){

$scope.days        =  0;
$scope.days1       =  0;
$scope.fcode       =  [];
$scope.final_data;
$scope.name        =  "Calvin";
$scope.groupid     =  0;
$scope.Filter      =  'ALL';
$scope.marker      =  [];
$scope.markerLabel      =  [];
$scope.zoom        =  6;
$scope.vehiclFuel  =  true;
$scope.checkVal        =  false;
$scope.clickflag       =  false;
$scope.clickflagVal    =  0;
$scope.nearbyflag      =  false;
$scope._editValue_con  =  false;
$scope._editValue      =  {};
$scope._editValue._vehiTypeList = ['Truck', 'Car', 'Bus', 'Bike'];
$scope.labeldisplay = true;
var markerSearch;
var geocoderVar; 
$scope.flightpathall = []; 
var tempdistVal = 0;
$scope.parseInt = parseInt;

$('#notifyMsg').hide();

$scope.sort = sortByDate('date');
$scope.column      = 'shortName'; // set the default sort type
$scope.reverse   = true;  // set the default sort order
$scope.sortGroup = 'shortName';
// called on header click
 $scope.sortColumn = function(col){
  $scope.column = col;
  if($scope.reverse){
   $scope.reverse = false;
   $scope.reverseclass = 'icon-chevron-up';
  }else{
   $scope.reverse = true;
   $scope.reverseclass = 'icon-chevron-down';
  }
 };
 
 // remove and change class
 $scope.sortClass = function(col){
  if($scope.column == col ){
   if($scope.reverse){
    return 'icon-chevron-down'; 
   }else{
    return 'icon-chevron-up';
   }
  }else{
   return 'icon-sort';
  }
 } 

 var startLoading    = function () {
     $('#statusLoad').show(); 
 };

// loading stop function
 var stopLoading   = function () {
    $('#statusLoad').fadeOut(); 
 };

function sortByDate(field){
  var sort = {sortingOrder : field, reverse : false };
    return sort;
}

function graphChange(vehifuel){
  return vehifuel == 'yes' ? true : false;
}

$scope.trimColon = function(textVal){

    if (textVal != null || textVal != undefined ){
     return textVal.split(":")[0].trim();
    }
}; 


 function checkXssProtection(str){

  var regex     = /<\/?[^>]+(>|$)/g;
  var enc       = encodeURI(str);
  var dec       = decodeURI(str);
  var replaced  = enc.search(regex) >= 0;
  var replaced1 = dec.search(regex) >= 0;

  return (replaced == false && replaced1 == false) ? true : false ;
 }


$scope.updateDetails    =   function(){
        if((checkXssProtection($scope._editValue._odoDista) == true) && (checkXssProtection($scope._editValue._shortName) == true) && (checkXssProtection($scope._editValue._overSpeed) == true) && (checkXssProtection($scope._editValue._driverName) == true) && (checkXssProtection($scope._editValue._mobileNo) == true) && (checkXssProtection($scope._editValue._regNo) == true))
        {   
        // $scope._editValue._shortName     = dataVal.shortName;
        // $scope._editValue._odoDista      = dataVal.odoDistance;
        // $scope._editValue._overSpeed     = dataVal.overSpeedLimit;
        // $scope._editValue._driverName    = dataVal.driverName;
        // $scope._editValue._mobileNo      = dataVal.mobileNo;
        // $scope._editValue._regNo         = dataVal.regNo;
        // $scope._editValue.vehType        = dataVal.vehicleType;

            var URL_ROOT = "vdmVehicles/"; 
            $.post( URL_ROOT+'updateLive/'+$scope.vehicleid, {
            '_token': $('meta[name=csrf-token]').attr('content'),
            'shortName':$scope._editValue._shortName,
            'odoDistance': $scope._editValue._odoDista,
            'overSpeedLimit':$scope._editValue._overSpeed,
            'driverName': $scope._editValue._driverName,
            'mobileNo' :$scope._editValue._mobileNo,
            'regNo': $scope._editValue._regNo,
            'vehicleType':$scope._editValue.vehType,
            'routeName':$scope._editValue.routeName,
            })
            .done(function(data) {
            // updateCall();
            console.log("Success");
            })
            .fail(function() {
            // updateCall();
            console.log("fail");
            });
            
            // document.getElementById("inputEdit").disabled = false;
            // $("#editable").hide();
            // $("#viewable").show();
        }
    }

var timeOutVar;   

function setsTimeOuts() {
      //alert('timeOut');
      $("#notifyS").hide(1200);
      $("#notifyF").hide(1200); 
        if(timeOutVar!=null){
          //console.log('timeOutVar'+timeOutVar);
            clearTimeout(timeOutVar);
        }
    }



$scope.updateSafePark=function(){
   console.log('updateSafePark.....');

    var spVal;
     
    if($scope.sparkType=='Yes') {
      spVal = 'yes';
      $('#safePark span').text($scope.sparkType);
    } else if($scope.sparkType=='No') {
      spVal = 'no';
      $('#safePark span').text($scope.sparkType);
    }

    var saferParkUrl  = GLOBAL.DOMAIN_NAME+'/configureSafetyParkingAlarm?vehicleId='+ $scope.vehicleid+'&enableOrDisable='+spVal;

  //console.log(saferParkUrl);

   

  (function(){

    $.ajax({
      async: false,
      method: 'GET', 
      url: saferParkUrl,
      success: function (response){
     //   console.log(response);
        if(response=="success") {
          $('#notifyMsg').show();
          $('#notifyS span').text('Successfully updated!..');
          $("#notifyS").show(500);
          $("#notifyF").hide(); 
          timeOutVar = setTimeout(setsTimeOuts, 2000);

        } else if(response=="fail") {

          $('#notifyMsg').show();

          $('#notifyS span').text(response+'!..');
          $("#notifyS").show(500);
          $("#notifyF").hide();
          timeOutVar = setTimeout(setsTimeOuts, 2000);

        } else {

          $('#notifyMsg').show();

          $('#notifyF span').text(response);
          $("#notifyF").show(500);
          $("#notifyS ").hide();
          timeOutVar = setTimeout(setsTimeOuts, 2000);
        }
        //$scope.toast = response;
        //toastMsg();
      }
    });

 })();

}


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

  function saveAddress(lat, lon){
  
    var latlng = new google.maps.LatLng(lat, lon);
     geocoderVar.geocode({'latLng': latlng}, function(results, status) {
   
      if(status == google.maps.GeocoderStatus.OK) {
        if(results[0]) {
          var newVal = vamoservice.googleAddress(results[0]);   
            saveAddressFunc(newVal, lat, lon);
        }
      }
  });

}

    function fetchAddress(dataVal){

       if(dataVal.address == null || dataVal.address == undefined || dataVal.address == ' ')
          $scope.getLocation(dataVal.latitude, dataVal.longitude, function(count){ 
            $('#lastseen').text(count); 
          });
        else{
          $('#lastseen').text(dataVal.address.split('<br>Address :')[1] ? dataVal.address.split('<br>Address :')[1] : dataVal.address);
            //saveAddress(dataVal.latitude, dataVal.longitude);
        }
    }


    $scope.assignValue=function(dataVal){

        $scope.vehicleid = dataVal.vehicleId;
        $scope.vehShort  = dataVal.shortName;
        $scope.ododis    = dataVal.odoDistance;
        $scope.spLimit   = dataVal.overSpeedLimit;
        $scope.driName   = dataVal.driverName;
        $scope.refname   = dataVal.regNo;
        $scope.vehType   = dataVal.vehicleType;
            
        if(dataVal.safetyParking=='yes') {
          $scope.sparkType = 'Yes';
          $('#safePark span').text('Yes');
        } else if(dataVal.safetyParking=='no') {
          $scope.sparkType = 'No';
          $('#safePark span').text('No');
        }

        $('#vehiid #val').text(dataVal.shortName);
        $('#toddist #val').text(dataVal.distanceCovered);
        $('#vehstat #val').text(dataVal.position);
        $('#regNo span').text(dataVal.regNo);
        $('#vehitype span').text(dataVal.vehicleType);
        $('#mobNo span').text(dataVal.mobileNo);
        $('#graphsId #speed').text(dataVal.speed);
        $('#graphsId #fuel').text(dataVal.tankSize);
        
        if(dataVal.tankSize!=0 && dataVal.fuelLitre!=0){ 
          tankSize       = parseInt(dataVal.tankSize);
          fuelLtr        = parseInt(dataVal.fuelLitre);
        }else if(dataVal.tankSize!=0 && dataVal.fuelLitre==0){
          tankSize       = parseInt(dataVal.tankSize);
          fuelLtr        = parseInt(dataVal.fuelLitre);
        }else if(dataVal.tankSize==0 && dataVal.fuelLitre==0){
          tankSize       = parseInt(dataVal.tankSize);
          fuelLtr        = parseInt(dataVal.fuelLitre);
        }

        total            = parseInt(dataVal.speed);
        $('.vehdevtype').text(dataVal.odoDistance);
        $('#mobno #val').text(dataVal.overSpeedLimit);
        $('#positiontime').text(vamoservice.statusTime(dataVal).tempcaption);
        $('#regno #val').text(vamoservice.statusTime(dataVal).temptime);
        $('#driverName #val').text(dataVal.driverName);
        $('#lstseendate').html(new Date(dataVal.date).toString().split('GMT')[0])
        
        $scope._editValue._shortName    = dataVal.shortName;
        $scope._editValue._odoDista     = dataVal.odoDistance;
        $scope._editValue._overSpeed    = dataVal.overSpeedLimit;
        $scope._editValue._driverName   = dataVal.driverName;
        $scope._editValue._mobileNo     = dataVal.mobileNo;
        $scope._editValue._regNo        = dataVal.regNo;
        $scope._editValue.vehType       = dataVal.vehicleType;
        $scope._editValue.routeName     = dataVal.routeName;

        $("#safeParkShow").show();
        $('#viewable').show();

        fetchAddress(dataVal);

        $scope.vehiclFuel=graphChange(dataVal.fuel);   

        if($scope.vehiclFuel==true){
            $('#graphsId').removeClass('graphsCls');
        } else {
            $('#graphsId').addClass('graphsCls');
        }
        $('#graphsId').show();

    }


       //var markerSearch = new google.maps.Marker({});
    
        $http({
            method : "GET",
            url : GLOBAL.DOMAIN_NAME+'/getVehicleLocations'
        }).then(function mySuccess(response) {
            $scope.data = response.data;
            $scope.fcode.push(response.data[$scope.groupid]);

            $scope.totalVehicles = response.data[$scope.groupid]['totalVehicles'];
            $scope.vehicleOnline = response.data[$scope.groupid]['online'];
            $scope.attention = response.data[$scope.groupid]['attention'];
            $scope.parkedCount = response.data[$scope.groupid]['totalParkedVehicles'];
            $scope.movingCount = response.data[$scope.groupid]['totalMovingVehicles'];
            $scope.idleCount = response.data[$scope.groupid]['totalIdleVehicles'];
            $scope.overspeedCount = response.data[$scope.groupid]['topSpeed'];
            
            $scope.vehiname = response.data[$scope.groupid].vehicleLocations[0].vehicleId;
            $scope.gName    = response.data[$scope.groupid].group;
            sessionValue($scope.vehiname, $scope.gName);
            $scope.apiKeys=response.data[$scope.groupid].apiKey;
            $scope.support   = response.data[$scope.groupid].supportDetails;

            $scope.trafficLayer = new google.maps.TrafficLayer();
            markerSearch        = new google.maps.Marker({});
            geocoderVar         = new google.maps.Geocoder();

            $scope.initilize('maploc');

        }, function myError(response) {
            $scope.myWelcome = response.statusText;
        });


      // polygen draw function
  function polygenFunction(getVehicle){
    //console.log(' getVehicle ')
    var polygenOrgs   = [];
    var unique      =   new Set();
    polygenOrgs     = ($filter('filter')(getVehicle[$scope.groupid].vehicleLocations, {'live': 'yes'}));
    for (var i=0; polygenOrgs.length > i; i++) {
      unique.add(polygenOrgs[i].orgId)
    };
    if(unique.size>0){
      $scope._addPoi  =   false; 
      angular.forEach(unique, function(value, key) {
        //service call to site details
        siteInvoke(value);
      });
    }
    else {
      // $scope._addPoi   =   true; 
      siteInvoke();

    }
    
  }


//draw polygen in map function
  function polygenDrawFunction(list){
    
      // if(list.length=>0){
      var sp;
      polygenList   = [];
      var split     = list.latLng.split(",");

      for(var i = 0; split.length>i; i++){
          sp    = split[i].split(":");
          polygenList.push(new google.maps.LatLng(sp[0], sp[1]));
      }

      var labelAnchorpos = new google.maps.Point(19, 0);
      var polygenColor = new google.maps.Polygon({
            path: polygenList,
            strokeColor: "#000",//7e7e7e
            strokeWeight: 0.7,
            fillColor: colorChange(list.siteName),//'#' + Math.floor(Math.random()*16777215).toString(16),//'#fe716d',
          //fillOpacity: ,
            map: $scope.map
        });
      
        //12, 37
     /* $scope.marker = new MarkerWithLabel({
         position: centerMarker(polygenList), 
         map: $scope.map,
         icon: 'assets/image/area_img.png',
         color: '#fff',
         labelContent: list.siteName,
         labelAnchor: labelAnchorpos,
         labelClass: "maps", 
         labelInBackground: false
      });*/

          var image = {
            url: 'assets/image/area_img.png',
            labelOrigin: new google.maps.Point(19, 0),
          };

        //  console.log(list.siteName);

         $scope.markerss = new google.maps.Marker({
           position:centerMarker(polygenList), 
           map: $scope.map,
           icon: image,
           label: {
           text: list.siteName,
           color: 'black',
           fontSize: "12px",
           fontWeight: "bold",
           }

         });

  
      $scope.map.setCenter(centerMarker(polygenList)); 
      $scope.map.setZoom(14);  
    // }
  }

function centerMarker(listMarker){
    var bounds = new google.maps.LatLngBounds();
    for (i = 0; i < listMarker.length; i++) {
          bounds.extend(listMarker[i]);
      }
    return bounds.getCenter()
  }


function colorChange(value){
  var color ='';
  switch(value){
    case 'Virugambakkam' :
      color   = 'c17b97';
      break;
    case 'Kolathur' :
      color   = 'f76c7c';
      break;
    case 'Egmore(SC)' :
      color   = 'e746bc';
      break;
    case 'Thiyagaraya_Nagar' :
      color   = '277f07';
      break;
    case 'Saidapet' :
      color   = 'fad195';
      break;
    case 'Dr_Radhakrishnan_Nagar':
      color   = '28909c';
      break;
    case 'Perambur' :
      color   = 'f381a7';
      break;
    case 'Chepauk_Thiruvallikeni': 
      color   = 'a05071';
      break;
    case 'Thiru_Vi_Ka_Nagar_(SC)' :
      color   = '3d59be';
      break;
    case 'Harbour' :
      color   = 'b28d53';
      break;
    case 'Royapuram' :
      color   = '98beb6';
      break;
    case 'Mylapore' :
      color   = '84c8b6';
      break;
    case 'Velachery' :
      color   = '6f738d';
      break;
    case 'Thousand_Lights':
      color   = '456d4d';
      break;
    case 'Anna_Nagar' :
      color   = 'aca6b7';
      break;
    case 'Villivakkam': 
      color   = 'f22af0';
      break;
    default:
      color   = 'f22af0';
        break;
  }
  return '#'+color;

}

  
  function siteInvoke(val) {
    var url_site          = GLOBAL.DOMAIN_NAME+'/viewSite';
    vamoservice.getDataCall(url_site).then(function(data) {
      // console.log(data);
      if(data.siteParent && $scope._addPoi == false){

     //   if(map_change==0){
          angular.forEach(data.siteParent, function(value, key){
          //console.log(' value'+key);
            if(val == value.orgId){
              angular.forEach(value.site, function(vals, keys){
              //console.log('inside the for loop');
                polygenDrawFunction(vals);
              }); 

              if(value.location.length>0){
                angular.forEach(value.location, function(locs, ind){
                 locat_address(locs);
                });
              }
            }
          });
       }

      if(data && data.orgIds != undefined){
        $scope.orgIds   = data.orgIds;
      }
    });
  }  


  function calcLatLongForDrawShapes(longitude, lat, distance, bearing) {
   var EARTH_RADIUS_EQUATOR = 6378140.0;
     var RADIAN = 180 / Math.PI;

   var b = bearing / RADIAN;
   var lon = longitude / RADIAN;
   var lat = lat / RADIAN;
   var f = 1/298.257;
   var e = 0.08181922;
    
   var R = EARTH_RADIUS_EQUATOR * (1 - e * e) / Math.pow( (1 - e*e * Math.pow(Math.sin(lat),2)), 1.5);  
   var psi = distance/R;
   var phi = Math.PI/2 - lat;
   var arccos = Math.cos(psi) * Math.cos(phi) + Math.sin(psi) * Math.sin(phi) * Math.cos(b);
   var latA = (Math.PI/2 - Math.acos(arccos)) * RADIAN;

   var arcsin = Math.sin(b) * Math.sin(psi) / Math.sin(phi);
   var longA = (lon - Math.asin(arcsin)) * RADIAN;

   return latA+':'+longA;
  };

//create save site
  $scope.markPoi   =   function(textValue, latlanList)
  {
    
    $scope.toast    = '';
    if(checkXssProtection(textValue) == true)
    try
    {

      var URL_ROOT    = "AddSiteController/";    /* Your website root URL */
      var text        = textValue;
      var drop        = 'Home Site';
      var org         = $scope.orgIds[0];
      
      // post request
      if(text && drop && latlanList.length>=3 && org)
      {
        $.ajax({
          async: false,
          method: 'POST', 
          'url' : URL_ROOT+'store',
          data: {'_token': $('meta[name=csrf-token]').attr('content'), 'siteName': text, 'siteType': drop, 'org':org, 'latLng': latlanList},
          success: function (response) {

            if(response!='') {
               $('#notifyS span').text('Successfully updated !..');
               $('#notifyMsg').show();
               $("#notifyS").show(500);
               $("#notifyF").hide(); 
               timeOutVar = setTimeout(setsTimeOuts, 2000);
            } else {

               $('#notifyF span').text("Enter all the field / Mark the Site ");
               $('#notifyMsg').show();
               $("#notifyF").show(500);
               $("#notifyS").hide(); 
               timeOutVar = setTimeout(setsTimeOuts, 2000);
              stopLoading();

            }

            stopLoading();
          }
        }).fail(function() {
          console.log("fail");
          stopLoading();
        });
       
      } else {

               $('#notifyF span').text("Enter all the field / Mark the Site ");
               $('#notifyMsg').show();
               $("#notifyF").show(500);
               $("#notifyS").hide(); 
               timeOutVar = setTimeout(setsTimeOuts, 2000);
              stopLoading();
      }

    } catch (err)    {

               $('#notifyF span').text("Enter all the field / Mark the Site ");
               $('#notifyMsg').show();
               $("#notifyF").show(500);
               $("#notifyS").hide(); 
               timeOutVar = setTimeout(setsTimeOuts, 2000);
      stopLoading();
    }
    stopLoading();
    
  }

  //split methods
  $scope.split_fcode = function(fcode){
    var str = $scope.fcode[0].group;
    var strFine = str.substring(str.lastIndexOf(':'));
    while(strFine.charAt(0)===':')
    strFine = strFine.substr(1);
    return strFine;
  }

  var modalss = document.getElementById('poi');
  var spanss = document.getElementsByClassName("poi_close")[0];
  
  function popUp_Open_Close(){

    modalss.style.display = "block";
    modalss.style.zIndex= 9999;
    spanss.onclick = function() {
        modalss.style.display = "none";
    }
  }  


  $scope.addPoi   = function(lat, lng){

    $scope.poiLat = lat;
    $scope.poiLng = lng;
    popUp_Open_Close();

  }

  $scope.submitPoi  = function(poiName){

    var width       = 1000;
    var latlngList  = [];

    if($scope.map.getZoom()>5 && $scope.map.getZoom()<=8)
      width = 1000;
    else if($scope.map.getZoom()>8 && $scope.map.getZoom()<=12)
      width = 100;
    else if($scope.map.getZoom()>12 && $scope.map.getZoom()<=15)
      width = 10;
    else if($scope.map.getZoom()>15)
      width = 1;
      
    var radius = (Math.sqrt (2 * (width * width))) / 2;
    
    latlngList[0]   = calcLatLongForDrawShapes($scope.poiLng, $scope.poiLat, radius, 45)
    latlngList[1]   = calcLatLongForDrawShapes($scope.poiLng, $scope.poiLat, radius, -45)
    latlngList[2]   = calcLatLongForDrawShapes($scope.poiLng, $scope.poiLat, radius, -135)
    latlngList[3]   = calcLatLongForDrawShapes($scope.poiLng, $scope.poiLat, radius, 45)

    console.log(latlngList);
    $scope.markPoi(poiName, latlngList)
    modalss.style.display = "none";

  }  



    $scope.initilize = function(ID){
        
        $scope.locations04=[];
        //$scope.groupid = 
        for(i=0;i<$scope.data.length;i++){
           val = $scope.data[i];
           $scope.locations04.push({rowId:val.rowId,group:val.group});
           if(typeof $scope.locations04[$scope.groupid] != 'undefined')
           {
           $scope.locations04[$scope.groupid].vehicleLocations=[];
           
           }
           $scope.locations04.vehicleLocations = [];
        }

        var mapOptions={
          zoom: $scope.zoom,
          zoomControlOptions:{position: google.maps.ControlPosition.LEFT_TOP},
          center: new google.maps.LatLng($scope.data[$scope.groupid]['latitude'], $scope.data[$scope.groupid]['longitude']),
          mapTypeId: google.maps.MapTypeId.ROADMAP,
        };
       
        $scope.map = new google.maps.Map(document.getElementById(ID), mapOptions);

        $(document).on('pageshow', '#maploc', function(e){       
          google.maps.event.trigger(document.getElementById('maploc'), "resize");
        });

        $scope.setMarkers($scope.data[$scope.groupid].vehicleLocations);
        polygenFunction($scope.data);

        stopLoading();


        var input_value   =  document.getElementById('pac-input');
        var sbox        =  new google.maps.places.SearchBox(input_value);

  // search box function
    sbox.addListener('places_changed', function() {
      markerSearch.setMap(null);
      var places = sbox.getPlaces();
      markerSearch = new google.maps.Marker({
        position: new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()),
        animation: google.maps.Animation.BOUNCE,
        map: $scope.map,
    
      });
    //  console.log(' lat lan  '+places[0].geometry.location.lat(), places[0].geometry.location.lng())
      $scope.map.setCenter(new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()));
      $scope.map.setZoom(13);
    
      });

      google.maps.event.addListener($scope.map, 'click', function(event) {
        if($scope.clickflag==true){
          if($scope.clickflagVal ==0){
            $scope.firstLoc = event.latLng;
            $scope.clickflagVal =1;
          }else if($scope.clickflagVal==1){
            $scope.drawLine($scope.firstLoc, event.latLng);
            $scope.firstLoc = event.latLng;
          }
        }else if($scope.nearbyflag==true){
          // $('#status02').show(); 
          // $('#preloader02').show(); 
          var tempurl = GLOBAL.DOMAIN_NAME+'/getNearByVehicles?lat='+event.latLng.lat()+'&lng='+event.latLng.lng();
          
          $http.get(tempurl).success(function(data){
            $scope.nearbyLocs = data;
            // $('#status02').fadeOut(); 
            // $('#preloader02').delay(350).fadeOut('slow');
            if($scope.nearbyLocs.fromAddress==''){}else{
              $('.nearbyTable').delay(350).show();
            }
          });
        }
      });

  };

  $scope.split_fcode = function(fcode){
    var str = $scope.fcode[0].group;
    var strFine = str.substring(str.lastIndexOf(':'));
    while(strFine.charAt(0)===':')
    strFine = strFine.substr(1);
    return strFine;
  }


  $scope.getMailIdPhoneNo = function(vehi, days) {
    //console.log('inside the methods')
    var mailId = document.getElementById("mail").value;
    var phone  = document.getElementById("phone").value;
    if(vehi == 0 && days ==0)
    console.log('select correctly') 
    else
    {
      $scope.split_fcode($scope.fcode[0].group);
      var f_code = $scope.split_fcode($scope.fcode[0].group);
      var f_code_url =  GLOBAL.DOMAIN_NAME+'/getVehicleExp?vehicleId='+vehi+'&fcode='+f_code+'&days='+days+'&mailId='+mailId+'&phone='+phone;
      var ecrypt_code_url = '';
      $http.get(f_code_url).success(function(result){
        
        //console.log(result);
          
        var url='../public/track?vehicleId='+result.trim()+'&maps=track'+'&userID='+sp1[1];
        window.open(url,'_blank');
        
      });  
      }
  }
  

    $scope.groupSelection = function(group, rowid){

         startLoading();

         $scope.groupid = rowid;
       //$scope.locations04=[];
         $scope.Filter = 'ALL';
         $scope.zoom   = 6;

         $scope.marker=[];

          $http({
            method : "GET",
            url :GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group=' + group
          }).then(function mySuccess(response) {
            $scope.data = response.data;
            $scope.totalVehicles = response.data[$scope.groupid]['totalVehicles'];
            $scope.vehicleOnline = response.data[$scope.groupid]['online'];
            $scope.attention = response.data[$scope.groupid]['attention'];
            $scope.parkedCount = response.data[$scope.groupid]['totalParkedVehicles'];
            $scope.movingCount = response.data[$scope.groupid]['totalMovingVehicles'];
            $scope.idleCount = response.data[$scope.groupid]['totalIdleVehicles'];
            $scope.overspeedCount = response.data[$scope.groupid]['topSpeed'];
            $scope.vehiname = response.data[$scope.groupid].vehicleLocations[0].vehicleId;
            $scope.gName    = response.data[$scope.groupid].group;
            sessionValue($scope.vehiname, $scope.gName);
            $scope.apiKeys=response.data[$scope.groupid].apiKey;

            $scope.initilize('maploc');
        }, function myError(response) {
            $scope.myWelcome = response.statusText;
        });
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

function markerChange(value){

  var icon , img;

    angular.forEach($scope.data[$scope.groupid].vehicleLocations, function(valu, key){
    //img = ($scope.makerType == 'markerChange')? 'assets/imgs/'+'Car2'+'.png' : vamoservice.iconURL(valu)
      img = ($scope.makerType == 'markerChange')? vamoservice.markerImage(valu) : vamoservice.iconURL(valu);

      if($scope.makerType == 'markerChange'){
          icon = {scaledSize: new google.maps.Size(30, 30),url: img,labelOrigin:  new google.maps.Point(25,40)} //scaledSize: new google.maps.Size(25, 25)
       // icon = {scaledSize: new google.maps.Size(30, 30),url: img} //scaledSize: new google.maps.Size(25, 25) valu.vehicleType
      } else {
        icon = {url: img,labelOrigin:  new google.maps.Point(25,40)}
      }

       $scope.marker[key].setIcon(icon);
       $scope.marker[key].setMap($scope.map);

      //gmarkers[key].setIcon(icon);
      //gmarkers[key].setMap($scope.map);
    });
  }

//view map
    $scope.mapView  =   function(value) {
        switch(value){
            case 'listMap' :
                listMap();
                break;
            case 'home' :
                homeMap();
                break;
            case 'cluster' :
                clusterMarker();
                break;
            case 'single' :
                singleMarker();
                break;
            case 'fscreen' :
                fullScreen();
                break;
            case 'escreen':
                exitScreen();
                break;
            case 'tablefull' :
                fulltable();
                break;
            case 'graphs': 
                graphView();
                break;
            case 'markerChange':
                $scope.makerType =  "markerChange";
                changeMarker();
                markerChange('markerChange');
                break;
          /*case 'tollYes':
                tollMarkers();
                break;
            case 'tollNo':
                removeToll();
                break;*/  
      case 'enableLabel':
                enableLabel();
                break;
            case 'disableLabel':
                disableLabel();
                break;        
            case 'undefined':
                $scope.makerType =  undefined;
                changeMarker();
                markerChange(undefined);
            default:
                break;
        }
    }
  function disableLabel()
  {
    $("#enableLabel").show();
    $("#disableLabel").hide();
    $scope.labeldisplay=false;
    $scope.tempMarker = $scope.marker;
    $scope.clearMarkers();
    $scope.marker = $scope.tempMarker;
    $scope.tempMarker = [];
    for(var i=0; i<$scope.marker.length; i++)
    {
      $scope.marker[i].setMap($scope.map);
      $scope.marker[i].setLabel("");
    }
  }
  function enableLabel()
  {
    $("#disableLabel").show();
    $("#enableLabel").hide();
    $scope.labeldisplay=true;
    $scope.tempMarker = $scope.marker;
    $scope.clearMarkers();
    $scope.marker = $scope.tempMarker;
    $scope.tempMarker = [];
    for(var i=0; i<$scope.marker.length; i++)
    {
      $scope.marker[i].setMap($scope.map);
      
      $scope.marker[i].setLabel($scope.markerLabel[i]);
    }
  }
  // clusterMarker 
  function clusterMarker()
  {
    $("#cluster").hide();
    $("#single").show();
    $scope.groupMap=true;
    // markerCluster  = new MarkerClusterer($scope.map, null, null)
    // mcOptions = {gridSize: 50,maxZoom: 15,styles: [ { height: 53, url: "assets/imgs/m1.png", width: 53}]}
    $scope.markerCluster = new MarkerClusterer($scope.map,  $scope.marker,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'}); 
  }
  //single
  function singleMarker()
  {
    $("#single").hide();
    $("#cluster").show();
    $scope.groupMap=false;
    $scope.tempMarker = $scope.marker;
    $scope.clearMarkers();
    $scope.marker = $scope.tempMarker;
    $scope.tempMarker = [];
    for(var i=0; i<$scope.marker.length; i++)
    {
      $scope.marker[i].setMap($scope.map);
    }
  }

  // changeMarker
  function changeMarker()
  {
    if($scope.makerType == undefined)
    {
      $("#carMarker").show();
      $("#marker").hide();  
    } else if ($scope.makerType == 'markerChange'){
      $("#carMarker").hide();
      $("#marker").show();  
    }
    
    // $scope.groupMap=true;
    // markerCluster  = new MarkerClusterer($scope.map, null, null)
    // mcOptions = {gridSize: 50,maxZoom: 15,styles: [ { height: 53, url: "assets/imgs/m1.png", width: 53}]}
    // markerCluster  = new MarkerClusterer($scope.map, gmarkers, mcOptions)  
  }
    function fulltable()
    {
        setId();
        $("#minmax").hide();
        document.getElementById($scope.idinvoke).setAttribute("id", "tablelist");
        document.getElementById('mapTable-mapList').setAttribute("id", "talist");       
        $('#talist').show(500);
    }

    function graphView()
    {
        $('#graphsId').toggle(500);
    }
    function fullScreen()
    {
        setId();
        $("#efullscreen").show();
        $("#contentmin").show(1000);
        $("#sidebar-fullscreen").show(500);
        document.getElementById($scope.idinvoke).setAttribute("id", "sidebar-fullscreen");
        
        
    }

    function exitScreen()
    {
        setId();
        
        $("#fullscreen").show();

        // $("#listImg").show();
        // $("#homeImg").hide();
        $("#contentmin").show(1000);
        $("#sidebar-wrapper").show(500);
        document.getElementById($scope.idinvoke).setAttribute("id", "wrapper");
    }

$('#mapTable-mapList').hide()
    $("#homeImg").hide();
    $("#listImg").show();
    $("#single").hide();
    $("#cluster").show();
    $("#efullscreen").hide();
    $("#fullscreen").show();
    //$('#graphsId').hide();
    $("#carMarker").show();
    $("#marker").hide();
    $("#tollYes").show();
    $("#tollNo").hide();
  $("#disableLabel").show();
  $("#enableLabel").hide();
    function listMap ()
    {
    // if($scope.zohoReports==undefined){
        setId();
        // $("#homeImg").show();
        $scope.tabView=1;
        $("#listImg").hide();
        $("#homeImg").show();
        $("#fullscreen").show();
        $('#mapTable-mapList').show(1000);
        // 
        document.getElementById($scope.idinvoke).setAttribute("id", "mapList");
        if(document.getElementById('talist')!=null)
        document.getElementById('talist').setAttribute("id", "mapTable-mapList");
    //  }
    }
    //return home
    function homeMap ()
    {
        setId();
          
        $scope.tabView=0;

        // $("#listImg").show();
        $("#homeImg").hide();
        $("#listImg").show();
        $("#fullscreen").show();
        
        $("#contentmin").show(1000);
        $("#sidebar-wrapper").show(500);
        // document.getElementById($scope.idinvoke).setAttribute("id", "mapList")
        document.getElementById($scope.idinvoke).setAttribute("id", "wrapper");
    }


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
                $scope.locations04[$scope.groupid].vehicleLocations.push(location);
                location.gsmLevel=parseInt(location.gsmLevel);
                //location.shortName=parseInt(location.shortName);
                $scope.locations04.vehicleLocations.push(location)
        }
          //}
         //ar infowindow = null;
          if (typeof infowindow != 'undefined') {
                infowindow.close();
            }

        /*  var markertemp = new MarkerWithLabel({
            position: new google.maps.LatLng(location['latitude'], location['longitude']),
            map: $scope.map,
            icon: vamoservice.iconURL(location),
            labelContent :location['shortName'],
            labelAnchor: new google.maps.Point(19, 0),
            labelClass: "labels", 
            labelInBackground: false
          });*/

        /*  var markertemp = new google.maps.Marker({
            position: new google.maps.LatLng(location['latitude'], location['longitude']),
            map: $scope.map,
            icon: vamoservice.iconURL(location),
            labelContent :location['shortName'],
            labelAnchor: new google.maps.Point(19, 0),
            labelClass: "maps", 
            labelInBackground: false
          }); */
      
          var image = {
          url: vamoservice.iconURL(location),
          labelOrigin:  new google.maps.Point(30,50)
          };
        var labelTemp = {
           text: location['shortName'],
           color: "red",
           fontSize: "12px",
           fontWeight: "bold",
           };
         var markertemp = new google.maps.Marker({
           position: new google.maps.LatLng(location['latitude'], location['longitude']),
           map: $scope.map,
           icon: image,
           label: labelTemp

         });

          if($scope.Filter != 'SINGLE'){
            google.maps.event.addListener(markertemp, "click", function(e) {
                if (typeof infowindow != 'undefined') {
                infowindow.close();
            }
             $scope.$apply(function () {
                    $scope._editValue_con   =   true;
                  //  $scope.individualVehicle = location;

                  $scope.assignValue(location);

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
       $scope.markerLabel.push( labelTemp);
       if(!$scope.labeldisplay)
       {
         markertemp.setLabel('');
       }
           if(address != undefined){
            infowindow = new InfoBubble({
              minWidth: 240,  
              maxHeight: 140,
              content: $scope.infoContent(location)[0]
            });
            infowindow.open($scope.map, markertemp);
           }
          $scope.map.setZoom($scope.zoom);
          $scope.map.setCenter(markertemp.position);
           return markertemp;
        })
        if($scope.Filter != 'SINGLE'){
      clusterMarker();
    }
    };  

    $scope.genericFunction = function(vehicleno, rowId){
        //angular.forEach($scope.locations, function(value, key){
        //if($scope.zohoReports==undefined){    
      $scope._editValue_con   =   true;
      individualVehicle = $filter('filter')($scope.locations04[$scope.groupid].vehicleLocations, { vehicleId:  vehicleno});
      $scope.individualVehicle = individualVehicle[0];
        //console.log(individualVehicle);
    //$scope.clearMarkers();
      //console.log("Selected Value: " + $scope.vehicleStatus.id + "\nSelected Text: " + $scope.vehicleStatus.name);
      $scope.Filter = 'SINGLE';
      $scope.zoom = 15;
      $scope.setMarkers(individualVehicle, individualVehicle[0].address);
      $scope.assignValue(individualVehicle[0]);
    };

  $('.nearbyTable').hide();
  $scope.nearBy = function(){
    if($scope.nearbyflag == false){
      $('#myModal').modal();  
      $scope.nearbyflag=true;
    }else{
      $('.nearbyTable').hide();
      $scope.nearbyflag=false;
    }
  }

   $scope.setsTraffic = function(val){
    if($scope.checkVal==false){
      document.getElementById(val).style.backgroundColor = "#fdfdb6"
      $scope.trafficLayer.setMap($scope.map);
      $scope.checkVal = true;
    }else{
      document.getElementById(val).style.backgroundColor = "#ffffff"
      $scope.trafficLayer.setMap(null);
      $scope.checkVal = false;
    }
  }  

  $scope.distance = function(val){
      $scope.nearbyflag=false;
      $('.nearbyTable').hide();
    
      if($scope.clickflag==true){
        document.getElementById(val).style.backgroundColor = "#fdfdb6"
        $scope.clickflagVal = 0;
        $('#distanceVal').val(0);
        $scope.clickflag=false;
          for(var i=0; i<$scope.flightpathall.length; i++){
            $scope.flightpathall[i].setMap(null); 
          }
     } else {
       document.getElementById(val).style.backgroundColor = "yellow"
       $scope.clickflag=true;  
    }
  }

  $scope.drawLine = function(loc1, loc2){
    var flightPlanCoordinates = [loc1, loc2];
    $scope.flightPath = new google.maps.Polyline({
      path: flightPlanCoordinates,
      geodesic: true,
      strokeColor: "#ff0000",
      strokeOpacity: 1.0,
      strokeWeight:2
    });
    
    $scope.flightPath.setMap($scope.map);
    $scope.flightpathall.push($scope.flightPath);
    tempdistVal = parseFloat($('#distanceVal').val()) + parseFloat((google.maps.geometry.spherical.computeDistanceBetween(loc1,loc2)/1000).toFixed(2))
    $('#distanceVal').val(tempdistVal.toFixed(2));
  }


    $scope.getLocation = function(lat,lon, callback){
     //console.log('getLocation.....');
      var latlng = new google.maps.LatLng(lat, lon);
        geocoderVar.geocode({'latLng': latlng}, function(results, status) {
          //console.log(results);
            if (status == google.maps.GeocoderStatus.OK) {
              if (results[1]) {
                if(typeof callback === "function") callback(results[1].formatted_address)
              }
              if(results[0]) {
                var newVals = vamoservice.googleAddress(results[0]);   
                  saveAddressFunc(newVals,lat,lon);
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
      +'<div style="padding-top:5px;"><a href="history?vid='+data.vehicleId+'&vg='+$scope.gName+'">Reports</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+data.vehicleId+'&track=single&maps=single" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/track?maps=replay&vehicleId='+data.vehicleId+'&gid='+$scope.gName+'" target="_blank">History</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+data.vehicleId+'&track=multiTrack&maps=mulitple" target="_blank">MultiTrack</a>&nbsp;&nbsp; <a href="#" ng-click="addPoi('+data.latitude+','+data.longitude+')">Site</a>'
    //+'<div style="padding-top:5px;"><a href="history?vid='+vehicleID+'&vg='+$scope.gName+'">Reports</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+vehicleID+'&track=single&maps=single" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/track?maps=replay&vehicleId='+vehicleID+'&gid='+$scope.gName+'" target="_blank">History</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+vehicleID+'&track=multiTrack&maps=mulitple" target="_blank">MultiTrack</a>&nbsp;&nbsp; <a href="#" ng-click="addPoi('+lat+','+lng+')">Save Site</a>'
    //+'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
      +'</div>';


        var compiled = $compile(contentString)($scope);
     // var  drop1 = document.getElementById("ddlViewBy");
     // var drop_value1= drop1.options[drop1.selectedIndex].value;
    return compiled;
};  


function setId()
    {
        $("#minmax").show();
        // $("#efullscreen").hide();
        // $("#sidebar-wrapper").hide(500);
        $("#fullscreen").hide();

        $("#efullscreen").hide();
        $('#mapTable-mapList').hide(500);
        // $("#efullscreen").hide();
        
        $("#contentmin").hide(500);
        $("#sidebar-wrapper").hide(500);
        if(document.getElementById("wrapper")!=null)
            $scope.idinvoke = document.getElementById("wrapper").id;
        else if(document.getElementById("sidebar-fullscreen")!=null)
            $scope.idinvoke = document.getElementById("sidebar-fullscreen").id;
        else if(document.getElementById("mapList")!=null)
            $scope.idinvoke = document.getElementById("mapList").id;
        else if(document.getElementById("mapTable-mapList")!=null)
            $scope.idinvoke = document.getElementById("mapTable-mapList").id;
        else if(document.getElementById("tablelist")!=null)
            $scope.idinvoke = document.getElementById("tablelist").id;

    }
$scope.starSplit    =   function(val){

 var splitVal;  

    if(val!=undefined){
    splitVal=val.split('<br>');
    }else{
    splitVal='No Address';
    }

 return splitVal;  
}

$("#notifyF").hide();
$("#notifyS ").hide();

$('#viewable').hide();
$("#graphsId").hide();

$("#safeParkShow").hide();
$("#safEdits").show();
$("#safeUps").hide();

 $("#safEdit").click(function(e){

    //  console.log('safe edits...');

       $('#safEdits').hide();
       $('#safeUps').show();

  });

 $("#safeUp").click(function(e){
       $('#safEdits').show();
       $('#safeUps').hide();
  });



}])

.directive('tooltips', function ($document, $compile) {
  return {
    restrict: 'A',
    scope: true,
    link: function (scope, element, attrs) {

      var tip = $compile('<span ng-class="tipClass">'+
        '<table class="tabStyles">'+
        '<tr ng-show="loc.expired==Yes">'+'<td colspan="4">'+'This Vehicle has expired !'+'</td></tr>'+
        '<tr ng-hide="loc.expired==Yes">'+'<td colspan="2">'+'{{ loc.date | date:"yyyy-MM-dd HH:mm:ss" }}'+'</td>'+'<td colspan="2">'+'{{ loc.shortName }}'+'</td>'+'</tr>'+
        '<tr ng-hide="loc.expired==Yes">'+'<td>'+'Odo(kms)'+'</td>'+'<td>'+'{{ loc.odoDistance }}'+'</td>'+'<td>'+'Covered(kms)'+'</td>'+'<td>'+'{{ loc.distanceCovered}}'+'</td>'+'</tr>'+
        '<tr ng-hide="loc.expired==Yes">'+'<td>'+'Ignition'+'</td>'+'<td>'+'{{ loc.ignitionStatus }}'+'</td>'+'<td>'+'MaxSpeed(kms)'+'</td>'+'<td>'+'{{ loc.overSpeedLimit }}'+'</td>'+'</tr>'+
        '<tr ng-hide="loc.expired==Yes">'+'<td>'+'Voltage'+'</td>'+'<td>'+'{{ loc.deviceStatus }}'+'%'+'</td>'+'<td>'+'Speed(kms)'+'</td>'+'<td>'+'{{loc.speed}}'+'</td>'+'</tr>'+
        '<tr ng-hide="loc.expired==Yes">'+'<td>'+'Sat Count'+'</td>'+'<td>'+'{{ loc.gsmLevel }}'+'</td>'+'<td>'+'Direction'+'</td>'+'<td>'+'{{loc.direction}}'+'</td>'+'</tr>'+
        '<tr ng-hide="loc.expired==Yes">'+'<td colspan="4">'+'{{ loc.address }}'+'</td></tr>'+
        '</table>'+
        '</span>')(scope),
          tipClassName = 'tooltips',
          tipActiveClassName = 'tooltips-show';
      scope.tipClass = [tipClassName];
      scope.text = attrs.tooltips;
      
      if(attrs.tooltipsPosition) {
        scope.tipClass.push('tooltips-' + attrs.tooltipsPosition);
      }
      else {
       scope.tipClass.push('tooltips-down'); 
      }
      $document.find('#sidebar-wrapper').append(tip);
      
      element.bind('mouseover', function (e) {
        tip.addClass(tipActiveClassName);
        
        var pos = e.target.getBoundingClientRect(),
            offset = tip.offset(),
            tipHeight = tip.outerHeight(),
            tipWidth = tip.outerWidth(),
            elWidth = pos.width || pos.right - pos.left,
            elHeight = pos.height || pos.bottom - pos.top,
            tipOffset = 10;
        
        if(tip.hasClass('tooltips-right')) {
          offset.top = pos.top - (tipHeight / 2) + (elHeight / 2);
          offset.left = pos.right + tipOffset;
        }
        else if(tip.hasClass('tooltips-left')) {
          offset.top = pos.top - (tipHeight / 2) + (elHeight / 2);
          offset.left = pos.left - tipWidth - tipOffset;
        }
        else if(tip.hasClass('tooltips-down')) {
          offset.top = pos.top + elHeight + tipOffset;
          offset.left = pos.left - (tipWidth / 2) + (elWidth / 2);
        }
        else {
          offset.top = pos.top - tipHeight - tipOffset;
          offset.left = pos.left - (tipWidth / 2) + (elWidth / 2);
        }

        tip.offset(offset);
      });
      
      element.bind('mouseout', function () {
        tip.removeClass(tipActiveClassName);
      });

      tip.bind('mouseover', function () {
        tip.addClass(tipActiveClassName);
      });

      tip.bind('mouseout', function () {
        tip.removeClass(tipActiveClassName);
      });

      
    }
  }
});

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
  
  $('#draggable').hide(0);  
      $('#minmaxMarker').click(function(){
        $('#draggable').animate({
          height: 'toggle'
        },500);
  });



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


 /*   var gaugeOptions = {
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
                shape: 'arc',
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
*/

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
 //             tankSize =200;
 //           chartFuel.yAxis[0].update({
//          max: tankSize,
//      }); 

        }
    }, 1000);
});

$(document).ready(function(){
      
});

 
// angular.bootstrap(document, ['mapApp']);
 
 /*  app.constant("_global", {
      'DOMAIN_NAME' : '//'+globalIP+context+'/public', 
      'VALID_PHONE_NUMBER' : '* Phone Number must be 10 digit numbers only !!', 
    });  */

