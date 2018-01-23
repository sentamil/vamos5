//comment by satheesh ++...
var setintrvl;
app.filter('statusfilter', function(){
  return function(obj, param){
     var out = [];
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
}).directive('trafficLayer', function() {
  return {
    restrict:'E',
    replace:true,
    template:'<input type="button" id="traffic" ng-click="trafficEnable()" value="Traffic" />',
    controller:function($scope){
      $scope.trafficLayer = new google.maps.TrafficLayer();
      $scope.checkVal=false;
      $scope.trafficEnable = function(){
        if($scope.checkVal==false){
          $scope.trafficLayer.setMap($scope.map);
          $scope.checkVal = true;
        }else{
          $scope.trafficLayer.setMap(null);
          $scope.checkVal = false;
        }
      }
    }
  }
});
 
app.controller('mainCtrl',['$scope','$compile','$http','vamoservice','$filter','_global', function($scope, $compile, $http, vamoservice, $filter, GLOBAL){
  
  $scope.locations    =  [];
  $scope.locations03  =  [];
  $scope.nearbyLocs   =  [];
  $scope.mapTable     =  [];
  $scope.mapsHist     =  0;
  $scope.maps_name    =  0;
  $scope.setMapOsm    =  0;
  $scope.mapInit      =  0;

  $scope.tabletds     = undefined;
  $scope.val = 5; 
  $scope.gIndex = 0;
  $scope.alertstrack = 0;
  $scope.totalVehicles = 0;
  $scope.vehicleOnline = 0;
  $scope.distanceCovered = 0;
  $scope.attention       = 0;
  $scope.vehicleno       = '';
  $scope.cityCircle      = [];
  $scope.cityCirclecheck = false;
  $scope.markerClicked   = false;

  sessionStorage.setItem('mapNo',0);
  var geocoderVar;

  $scope.url        = 'http://'+globalIP+context+'/public/getVehicleLocations';
  $scope.getZoho    = GLOBAL.DOMAIN_NAME+'/getZohoInvoice';
  $scope.getRoutes  = GLOBAL.DOMAIN_NAME+'/getRouteList';

  $scope.historyfor = '';
  $scope.map        =  null;
  $scope.flightpathall = []; 
  $scope.clickflag     = false;

  $scope._addPoi       =  false;
  $scope.checkVal      =  false;
  $scope.clickflagVal  =  0;
  $scope.nearbyflag    =  false;
  $scope.groupMap      =  false;

  $scope.vehiclFuel       =  true;
  $scope._editValue_con   = false;
  $scope._editValue       = {};
  $scope._editValue._vehiTypeList = ['Truck', 'Car', 'Bus', 'Bike'];
  //$scope.sparkArr   = ['yes','no'];

  $scope.navReports = "../public/reports";
  $scope.navStats   = "../public/statistics";
  $scope.navSched   = "../public/settings";
  $scope.navFms     = "../public/fms";
  $scope.minmaxs    = 1;
  $scope.zohod      = 0;  

  var newGroupSelectionName="";
  var oldGroupSelectionName="";
  var initValss=0;

  var tempdistVal = 0;
  // var mcOptions={};
  var markerClusters;
  var map =null;
  
  var vehicleids   = [];
  var polygenList  = [];
  var polygonList2 = []; 

  $scope.tabView  = 1;
  $scope.orgIds   = [];
    
  //var map_osm=null; 
  var map_change=0;
  var map_changeOsm=0;
  var m   =  [];


  var mcOptions = {
    maxZoom: 11,
    styles: [
      {
      height: 53,
      url: "assets/imgs/m1.png",
      width: 53
      },
      {
      height: 56,
      url: "assets/imgs/m2.png",
      width: 56
      },
      {
      height: 66,
      url: "assets/imgs/m3.png",
      width: 66
      }
    ]
  };



/*  var maploc1 = L.map( 'maploc1', {
      center: [20.0, 5.0],
      minZoom: 4,
      zoom: 6
    });

  L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    subdomains: ['a','b','c']
  }).addTo( maploc1 );  
*/
  // var menuVid;

  $scope.trimColon = function(textVal) {
      if(textVal != null || textVal != undefined){
         return textVal.split(":")[0].trim();
      }
  }

  $scope.sort = {       
                sortingOrder : 'shortName',
                reverse : false
            };

  function sessionValue(vid, gname){
      sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
      $("#testLoad").load("../public/menu");
  }

  var markerSearch = new google.maps.Marker({});
// var markerSearch =[];

  $scope.ZohoCall = function(){
    //console.log('close button...');
      $scope.zohod        = 0;
      $scope.zohoCloseBut = 1;
  }


  function getMaxOfArray(numArray) {
   return Math.max.apply(null, numArray);
  }

  function trimDueDays(value){
     var splitValue=value.split(/[ ]+/);
  return  splitValue[2];
  }

  function zohoDayValue(datValue){

    $scope.zohoHighVal=datValue;

    if(datValue>0){

     if(datValue==1){

      $scope.zohoReports=1;
      $scope.zohod=1;
      $scope.zohoDays='Payment overdue for '+ datValue+' day. Please make payment at the earliest to avoid de-activation.';

      }
      else if(datValue>1){

      $scope.zohoReports=1;
      $scope.zohod=1;
      $scope.zohoDays='Payment overdue for '+ datValue+' days. Please make payment at the earliest to avoid de-activation.';

      }

    return datValue;
    }
      
    /* if(datValue>=7){
       $scope.zohoReports=1;
         $scope.zohod=1;
         $scope.zohoDays='Payment overdue for '+ datValue+'days. Please make payment at the earliest to avoid de-activation.';
       console.log('Overdue to 7 days...');
        //$scope.zohoDays='Your dues are pending... Ovedues to 21 days...  Reports not available...';
      //console.log('21 (or) more than 21 days...');
     }
     else{

         $scope.navReports="../public/reports";
       $scope.navStats="../public/statistics";
       $scope.navSched="../public/settings";
       $scope.navFms="../public/fms";

        if(datValue>=5){
          $scope.zohod=1;
          $scope.zohoDays='Payment overdue for '+datValue+'days. Please make payment at the earliest to avoid de-activation.';
        console.log('Overdue to 5 days...');
        }
        else if(datValue>=4){
          $scope.zohod=1;
          $scope.zohoDays='Payment overdue for '+datValue+'days. Please make payment at the earliest to avoid de-activation.';
        console.log('Overdue to 4 days...');
        }
        else{
          $scope.zohod=0;
        console.log('less than 4 days...');
        }
    } */
 }


  function zohoDataCall(data){

     $scope.zohoData =[];
     $scope.zohoDayss=[];
        var zohoDatas=[];

          zohoDatas.push({customerName:data.customerName});
          zohoDatas[0].hist=[];

          angular.forEach(data.hist,function(val, key){
                $scope.zohoDayss.push(trimDueDays(val.dueDays));
                zohoDatas[0].hist.push({customerName:val.customerName,balanceAmount:val.balanceAmount,dueDate:val.dueDate,dueDays:val.dueDays,inVoice:val.invoiceLink});
           })

          $scope.zohoData=zohoDatas;
        //console.log( $scope.zohoData);
        //console.log(getMaxOfArray($scope.zohoDayss));

        zohoDayValue(getMaxOfArray($scope.zohoDayss));

        angular.forEach(data.hist,function(val, key){

          var newZohoVal=trimDueDays(val.dueDays);
          //console.log(newZohoVal);
          //console.log($scope.zohoHighVal);
            if(newZohoVal==$scope.zohoHighVal){

              //console.log(val.invoiceLink);
                $scope.zohoLink=val.invoiceLink;
            }      
        })
    }

/*   function zohoUrl(){
     //var getZoho=GLOBAL.DOMAIN_NAME+"/getZohoInvoice?refName=rama";
       console.log(getZoho);
      
       $http.get(getZoho).success(function(data){
         zohoDataCall(data); 
       })
    }*/

   
/* getting Org ids */
   
  /*  $scope.$watch("getOrgId", function (val) {
    $http.get($scope.getOrgId ).success(function(response){

        $scope.organIds     = [];
    $scope.organIds   = response.orgIds;

    console.log($scope.organIds);
    })
  });  */

   $scope.routeDataNames=function(data){

    var vehiRouteList = [];
    $scope._editValue._vehiRoutesList = [];

        angular.forEach(data.routeParents,function(val, key){
          
          if(val.route != null){
             //console.log(val.orgId);
             //console.log(val.route);
               vehiRouteList.push(val.route);     
          }
        })

        var firstVal=0;   

        angular.forEach(vehiRouteList,function(val, key){
            angular.forEach(val,function(sval, skey){

               if(firstVal==0){
                  $scope._editValue._vehiRoutesList.push("nill");
                  firstVal++;
                }else{
                  $scope._editValue._vehiRoutesList.push(sval);
               }
            })
        })
    // console.log($scope._editValue._vehiRoutesList);
    }

    $scope.$watch("getRoutes", function (val) {

        $http.get($scope.getRoutes).success(function(data){
       // vamoservice.getDataCall($scope.getRoutes).then(function(data) {
          $scope.routeDataNames(data);
       });
   });   

   $scope.$watch("getZoho", function (val) {
     vamoservice.getDataCall($scope.getZoho).then(function(data) {

         zohoDataCall(data); 
        });
   });

    
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


   $scope.filterExpire = function(data){

     var ret_obj=[];
     angular.forEach(data,function(val, key){
      //console.log(val.expired);
         if(val.expired == "No"){ 
           ret_obj.push(val);
         }
     })   
    return ret_obj;
    }



  $scope.$watch("url", function (val) {
       
    vamoservice.getDataCall($scope.url).then(function(data) {

      window.localStorage.setItem("totalV",data[0].totalVehicles);

      $scope.selected=undefined;
      $scope.selected02=undefined;
      $scope.vehicle_list=[];
      $scope.fcode=[];
      $scope.locations02 = data;
    //zohoUrl();

      $scope.locations04=[]; 
      $scope.locations04=$scope.vehiSidebar(data);

    //console.log($scope.locations04);

      try{
        $scope.dpdown = $scope.locations02[0].isDbDown; 
      }catch (err){
        
      }
      
      listVehicleName(data);
      // menuGroup(data);
      
      if(data.length){

        $scope.mapTable  = $scope.filterExpire(data[$scope.gIndex].vehicleLocations);
     // console.log(document.getElementById('one').innerText)
        $scope.vehiname  = data[$scope.gIndex].vehicleLocations[0].vehicleId;
        $scope.gName     = data[$scope.gIndex].group;
        sessionValue($scope.vehiname, $scope.gName)
        $scope.apiKeys=$scope.locations02[$scope.gIndex].apiKey;
        $scope.locations = $scope.statusFilter($scope.locations02[$scope.gIndex].vehicleLocations, $scope.vehicleStatus);
        $scope.locations03=$scope.filterExpire($scope.locations);

     // console.log($scope.locations03);
     // console.log($scope.mapTable);

        $scope.zoomLevel = 6;
        $scope.support   = data[$scope.gIndex].supportDetails;
                 
       /* if(map_osm!=null){
          map_osm.remove();
        }*/

        if(map_change==0){


          // console.log('init_google.....');

            document.getElementById("map_osm").style.display="none"; 
            document.getElementById("maploc").style.display="block"; 

            $scope.initilize('maploc');
            markerChange($scope.makerType);
        
        } else if(map_change==1){

         // console.log('init_osm.....');

          /*  document.getElementById("maploc").style.display="none"; 
            document.getElementById("map_osm").style.display="block"; */

            if($scope.map_osm!=null){
               $scope.map_osm.remove();
            }

            $scope.initilize_osm('map_osm');
            markerChange_osm($scope.makerType);

        }
           // $scope.initilize('maploc');
           // if($scope.makerType == undefined)

      }
    }); 
  });
    

  $scope.$watch("vehicleStatus", function (val) {
    if($scope.locations02!=undefined){
      $scope.selected     = undefined;
      $scope.selected02   = undefined;
      $scope.locations    = $scope.statusFilter($scope.locations02[$scope.gIndex].vehicleLocations, val);
      $scope.locations03  = $scope.filterExpire($scope.locations);      

      if($scope.map_osm!=null){
        $scope.map_osm.remove();
      }

      //$scope.initilize_osm('map_osm');
        $scope.initilize('maploc');

          if(map_change==0){
              $scope.initilize('maploc');
          } else if(map_change==1){
              $scope.initilize_osm('map_osm');      
          }
      }
  });
    
  $scope.statusFilter = function(obj, param){
    var out = [];
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

function fetchingAddress(pos){

  if( pos.address == '_' || pos.address == ',' || pos.address == null || pos.address == undefined || pos.address == ' ' ){
      $scope.getLocation(pos.latitude, pos.longitude, function(count){ 
        $('#lastseen').text(count); 
      });
    }
    else{
      $('#lastseen').text(pos.address.split('<br>Address :')[1] ? pos.address.split('<br>Address :')[1] : pos.address);
        //saveAddress(pos.latitude, pos.longitude);
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
  

  $scope.valueCheck = function(vale)
  {
    if(vale == 'nill' || vale == '0.0')return '--';
    else if (vale !='nill' || vale != '0.0')return vale;
  }

  $scope.genericFunction = function(vehicleno, rowId){

    

    // angular.forEach($scope.locations, function(value, key){
    //if($scope.zohoReports==undefined){
    //$scope._editValue_con   = true;
      var individualVehicle = $filter('filter')($scope.locations, { vehicleId:  vehicleno});
      if (individualVehicle[0].position === 'N' || individualVehicle[0].position === 'Z')
      {
        $('#status').fadeOut(); 
        $('#preloader').delay(350).fadeOut('slow');
        $('body').delay(350).css({'overflow':'visible'});
        alert(individualVehicle[0].address);
      }
      else
      {
        //console.log(individualVehicle[0]);
          $scope.assignValue(individualVehicle[0]);
          fetchingAddress(individualVehicle[0]);
            
          if(individualVehicle[0].expired == "No"){

            if($scope.tabletds==1){
              $scope.tabletds=undefined;
            }

            $scope.selects  = 1;    
            $scope.selected = rowId;
         // console.log($scope.selected);

           if(map_change==0){

           // console.log('google...');

               $scope.removeTask(vehicleno);
             
            } else if(map_change==1){

            //  console.log('osm...');

               $scope.removeTask_osm(vehicleno);
            }

            sessionValue(vehicleno, $scope.gName);
            $('#viewable,#rightAddress2').show();

            $scope.vehiclFuel=graphChange(individualVehicle[0].fuel);
            if($scope.vehiclFuel==true){
                $('#graphsId').removeClass('graphsCls');
            } else{
                $('#graphsId').addClass('graphsCls');
            }
            $('#graphsId').show();
         
         // editableValue();

           } else {
           console.log('Vehicle Expired...');
         }
      }
    // }) 
    
  }

  //for edit details in the right side div
  //document.getElementById("inputEdit").disabled = true;
  //function editableValue()
  //{
  //  document.getElementById("inputEdit").disabled = false;
  //}

//for edit details in the right side div
//  document.getElementById("inputEdit").disabled = true;
  function editableValue() {
    document.getElementById("inputEdit").disabled = false;
  }

  //update function in the right side div
  $scope.updateDetails  =   function() {

     console.log('update function called!...');

   //console.log($scope._editValue.routeName);
    $('#vehiid #val').text($scope.vehShort);
    $('#toddist #val').text($scope.ododis);
  //$('#vehstat #val').text(dataVal.position);
    $('#regNo span').text($scope.refname);
    $('#routeName span').text($scope._editValue.routeName);
    $('#vehitype span').text($scope.vehType);
    $('#mobNo span').text($scope.mobileNo);
    $('#mobno #val').text($scope.spLimit);
    $('#driverName #val').text($scope.driName);
    $('#safePark span').text($scope.sparkType);


    var URL_ROOT = "vdmVehicles/"; 
    $.post( URL_ROOT+'updateLive/'+$scope.vehicleid, {
        '_token': $('meta[name=csrf-token]').attr('content'),
        'shortName':$scope.vehShort,
        'odoDistance': $scope.ododis,
        'overSpeedLimit':$scope.spLimit,
        'driverName': $scope.driName,
        'mobileNo' :$scope.mobileNo,
        'regNo': $scope.refname,
        'vehicleType':$scope.vehType,
        'routeName':$scope._editValue.routeName,
    })
      .done(function(data) {
    //  updateCall();
        console.log("update details success..");
      })
      .fail(function() {
  //  updateCall();
        console.log("update details fails..");
      });

  // document.getElementById("inputEdit").disabled = false;
    $("#editable").hide();
    $("#viewable").show();
  }

  function refData(){
    $.ajax({
      async: false,
      method: 'GET', 
      url: 'http://'+globalIP+context+'/public/getVehicleLocations',
      success: function (response){

        console.log(response);

     //  var dataVar=response.data[$scope.gIndex].vehicleLocations;
         // var spVals=dataVar[$scope.vehiRowId].safetyParking;

         if(spVals=='yes') {
            $scope.sparkType = 'Yes';
            $('#safePark span').text($scope.sparkType);
         } else if(spVals=='no') {
            $scope.sparkType = 'No';
            $('#safePark span').text($scope.sparkType);
         }
      }
    });

  }


  $scope.updateSafePark=function(){

    var timeOutVar,spVal;
      console.log('updateSafePark.....');

    if($scope.sparkType=='Yes') {
      spVal = 'yes';
      $('#safePark span').text($scope.sparkType);
    } else if($scope.sparkType=='No') {
      spVal = 'no';
      $('#safePark span').text($scope.sparkType);
    }

    //$('#safePark span').text($scope.sparkType);
      var saferParkUrl  = GLOBAL.DOMAIN_NAME+'/configureSafetyParkingAlarm?vehicleId='+ $scope.vehicleid+'&enableOrDisable='+spVal;

       //console.log(saferParkUrl);

    function setsTimeOuts() {
      //alert('timeOut');
      $("#notifyS").hide(1200);
      $("#notifyF").hide(1200); 
        if(timeOutVar!=null){
          //console.log('timeOutVar'+timeOutVar);
            clearTimeout(timeOutVar);
        }
    }

   (function(){

    $.ajax({
      async: false,
      method: 'GET', 
      url: saferParkUrl,
      success: function (response){
        //console.log(response);
        if(response=="success") {

        //$('#notifyS span').text(response+'!..');
          $("#notifyS").show(500);
          $("#notifyF").hide(); 
          timeOutVar = setTimeout(setsTimeOuts, 2000);

          //refData();

        } else if(response=="fail") {

          $('#notifyS span').text(response+'!..');
          $("#notifyS").show(500);
          $("#notifyF").hide();
          timeOutVar = setTimeout(setsTimeOuts, 2000);

        } else {

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


 /* function updateCall()
  {
    var url = 'http://'+globalIP+context+'/public//getVehicleLocations';
    $http.get(url).success(function(response){
      for (var i = 0; i < response[$scope.gIndex].vehicleLocations.length; i++) 
      {
        if($scope.vehicleid == response[$scope.gIndex].vehicleLocations[i].vehicleId)
        $scope.assignValue(response[$scope.gIndex].vehicleLocations[i])
      };
    })
  }

*/

  //update function in the right side div

/*  $scope.updateDetails  =   function()
  {
    if((checkXssProtection($scope._editValue._odoDista) == true) && (checkXssProtection($scope._editValue._shortName) == true) && (checkXssProtection($scope._editValue._overSpeed) == true) && (checkXssProtection($scope._editValue._driverName) == true) && (checkXssProtection($scope._editValue._mobileNo) == true) && (checkXssProtection($scope._editValue._regNo) == true))
    { 
    // $scope._editValue._shortName   = dataVal.shortName;
    // $scope._editValue._odoDista      = dataVal.odoDistance;
    // $scope._editValue._overSpeed   = dataVal.overSpeedLimit;
    // $scope._editValue._driverName  = dataVal.driverName;
    // $scope._editValue._mobileNo      = dataVal.mobileNo;
    // $scope._editValue._regNo     = dataVal.regNo;
    // $scope._editValue.vehType    = dataVal.vehicleType;

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
*/

  function updateCall()
  {
    var url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
    $http.get(url).success(function(response){

          if(response[$scope.gIndex].vehicleLocations != null){

            var respLen=response[$scope.gIndex].vehicleLocations.length;

      for (var i = 0; i < respLen; i++) 
      {
        if($scope.vehicleid == response[$scope.gIndex].vehicleLocations[i].vehicleId)
        $scope.assignValue(response[$scope.gIndex].vehicleLocations[i])
      };
       }
    })
  }

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
  
  // clusterMarker 
  $scope.clusterMarker = function()
  {

      $scope.groupMap=true;
      var mcClusterIconFolder = "assets/imgs";
      var mcOptions = {
          maxZoom: 11,
          styles: [
          {
            height: 53,
            url: mcClusterIconFolder + "/m1.png",
            width: 53
          },
          {
            height: 56,
            url: mcClusterIconFolder + "/m2.png",
            width: 56
          },
          {
            height: 66,
            url: mcClusterIconFolder + "/m3.png",
            width: 66
          },
          {
            height: 78,
            url: mcClusterIconFolder + "/m4.png",
            width: 78
          },
          {
            height: 90,
            url: mcClusterIconFolder + "/m5.png",
            width: 90
          }
        ]
  };
    
    // var mcOptions = {gridSize: 50,maxZoom: 15, imagePath: 'https://raw.githubusercontent.com/googlemaps/js-marker-clusterer/gh-pages/images/m1.png'}
    // var markerCluster  = new MarkerClusterer($scope.map, gmarkers, { 
    // imagePath: 'assets/imgs/m1.png' 
    // });  

     var markerCluster  = new MarkerClusterer($scope.map, gmarkers,{
         imagePath: 'https://rawgit.com/googlemaps/js-marker-clusterer/gh-pages/images/m1.png' 
       }); 

    /*  var  markerClusters = new L.MarkerClusterGroup(gmarkers);
        // markerClusters.addLayer(gmarkers);
         map_osm.addLayer( markerClusters );
     */    
  }


  $scope.getLocation = function(lat, lon, callback){
     //console.log(lat);
     //console.log(lon);
     
      var latlng = new google.maps.LatLng(lat, lon);
      geocoderVar.geocode({'latLng': latlng}, function(results, status) {
      //console.log(results);
      if(status == google.maps.GeocoderStatus.OK) {
        if(results[1]) {
           if(typeof callback === "function") callback(results[1].formatted_address)
        }
      
        if(results[0]) {
          var newVals = vamoservice.googleAddress(results[0]);   
            saveAddressFunc(newVals, lat, lon);
        } 
      }
    });
  };

  function utcdateConvert(milliseconds){
    //var milliseconds=1440700484003;
    var offset='+10';
    var d = new Date(milliseconds);
    utc = d.getTime() + (d.getTimezoneOffset() * 60000);
    nd = new Date(utc + (3600000*offset));
    var result=nd.toLocaleString();
    return result;
  }

  $scope.distance = function(val){
    $scope.nearbyflag=false;
    $('.nearbyTable').hide();
    if($scope.clickflag==true){
      document.getElementById(val).style.backgroundColor = "#FFFFFF"
      $scope.clickflagVal = 0;
      $('#distanceVal').val(0);
      $scope.clickflag=false;
      for(var i=0; i<$scope.flightpathall.length; i++){
        $scope.flightpathall[i].setMap(null); 
      }
    }else{
      document.getElementById(val).style.backgroundColor = "yellow"
      $scope.clickflag=true;  
    }
  }
  

  $scope.groupSelection = function(groupname, groupid){

      $('#graphsId').hide();
      $('#viewable,#rightAddress2').hide();

      if(initValss>0){
        oldGroupSelectionName=newGroupSelectionName;
      }
        
      newGroupSelectionName=groupname;
      initValss++;

      if(oldGroupSelectionName != newGroupSelectionName){

         $('#status').show();
         $('#preloader').show();
         $scope.selects      = 1;
         $scope.selected     = undefined;
         $scope.selected02   = undefined;
         $scope.dynamicvehicledetails1 = false;
         $scope.url = 'http://'+globalIP+context+'/public/getVehicleLocations?group=' + groupname;
    
         $scope.gIndex   = groupid;
         $scope.mapTable = $scope.filterExpire($scope.locations02[groupid].vehicleLocations);

         setTimeout(function() {
          if ($scope.locations02[groupid].totalVehicles > 50){
          {
           $scope.displayErrorMsg = false;
           clusterMarker();
           } 
          }
         },3500);

         if(map_change==0){
  
          ginfowindow[0].setMap(null);
          clearInterval(setintrvl);
          markerChange($scope.makerType);
       
        } else if(map_change==1){


        }

         
         $('#graphsId').hide();

      // $scope.locations02 = vamoservice.getDataCall($scope.url);
      // $('#status').fadeOut(); 
      // $('#preloader').delay(350).fadeOut('slow');
      // $('body').delay(350).css({'overflow':'visible'});

       }
  }

  var modal = document.getElementById('poi');
  var span = document.getElementsByClassName("poi_close")[0];
  
  function popUp_Open_Close(){

    modal.style.display = "block";
    modal.style.zIndex= 9999;
    span.onclick = function() {
        modal.style.display = "none";
    }
  }  
  

$scope.infoBoxed_osm = function(map, marker, vehicleID, lat, lng, data){
    
      var tempoTime = vamoservice.statusTime(data);
      if(data.ignitionStatus=='ON'){
        var classVal = 'green';
      }else{
        var classVal = 'red';
      }

       var contentStrings = '<div style="width:auto;font-family:Lato;min-height:auto;">'
      +'<div><b class="_info_caption" >Vehicle Name</b> - <span style="font-weight:bold;">'+data.shortName+'</span></div>'
    //+'<div><b >ODO Distance</b> - '+data.odoDistance+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
      +'<div><b class="_info_caption">Today Distance</b> - '+data.distanceCovered+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
      +'<div><b class="_info_caption">'+vamoservice.statusTime(data).tempcaption+'</span></b> - '+vamoservice.statusTime(data).temptime+'</div>'
      +'<div><b class="_info_caption">ACC Status</b> - <span style="color:'+classVal+'; font-weight:bold;">'+data.ignitionStatus+'</span> </div>'
      +'<div><b class="_info_caption">Loc Time</b> - <span>'+$filter('date')(data.date, "dd-MMM-yy HH:mm")+'</span> </div>'
      +'<div ><b class="_info_caption">Comm Time</b> - <span>'+$filter('date')(data.lastComunicationTime, "dd-MMM-yy HH:mm")+'</span> </div>'
      +'<div style="padding-top:5px;"><a href="history?vid='+vehicleID+'&vg='+$scope.gName+'">Reports</a>&nbsp;&nbsp;<a href="../public/track?vehicleId='+vehicleID+'&track=single&maps=single" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/track?maps=replay&vehicleId='+vehicleID+'&gid='+$scope.gName+'" target="_blank">History</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+vehicleID+'&track=multiTrack&maps=mulitple" target="_blank">MultiTrack</a>&nbsp;&nbsp; <a href="#" ng-click="addPoi('+lat+','+lng+')">Save Site</a>'
    //+'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
      +'</div>';
      
  /*  var contentString = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
      +'<div><b style="width:100px; display:inline-block;">Vehicle Name</b> - <span style="font-weight:bold;">'+data.shortName+'</span></div>'
      +'<div><b style="width:100px; display:inline-block;">ODO Distance</b> - '+data.odoDistance+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
      +'<div><b style="width:100px; display:inline-block;">Today Distance</b> - '+data.distanceCovered+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
      +'<div><b style="width:100px; display:inline-block;">ACC Status</b> - <span style="color:'+classVal+'; font-weight:bold;">'+data.ignitionStatus+'</span> </div>'
      +'<div><a href="../public/track?vehicleId='+vehicleID+'&track=single&maps=single" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/track?maps=replay&vehicleId='+vehicleID+'&gid='+$scope.gName+'" target="_blank">History</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+vehicleID+'&track=multiTrack&maps=mulitple" target="_blank">MultiTrack</a>&nbsp;&nbsp;'
      +'</div>'; */

    /*  
        var contentString = '<div style="padding:5px; padding-top:10px; width:auto; max-height:170px; height:auto;">'
      +'<div><b style="width:100px; display:inline-block;">Vehicle Name</b> - <span style="font-weight:bold;">'+data.shortName+'</span></div>'
      +'<div><b style="width:100px; display:inline-block;">ODO Distance</b> - '+data.odoDistance+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
      +'<div><b style="width:100px; display:inline-block;">Today Distance</b> - '+data.distanceCovered+' <span style="font-size:10px;font-weight:bold;">kms</span></div>'
      +'<div><b style="width:100px; display:inline-block;">ACC Status</b> - <span style="color:'+classVal+'; font-weight:bold;">'+data.ignitionStatus+'</span> </div>'

      +'<div><a href="../public/track?vehicleId='+vehicleID+'&track=single&maps=single" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/track?maps=replay&vehicleId='+vehicleID+'&gid='+$scope.gName+'" target="_blank">History</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+vehicleID+'&track=multiTrack&maps=mulitple" target="_blank">MultiTrack</a>&nbsp;&nbsp;'
      +'</div>';
      */

      // var  drop1 = document.getElementById("ddlViewBy");
      // var drop_value1= drop1.options[drop1.selectedIndex].value;

        var infowindow = new L.popup({maxWidth: 400,  
      maxHeight:170}).setContent(contentStrings);


        ginfowindow_osm.push(infowindow);

        var icon = L.icon({
            iconUrl: vamoservice.iconURL(data),
            iconSize: [40,40], 
            iconAnchor:[20,40],
            popupAnchor:[-1,-40]//scaledSize: new google.maps.Size(25, 25)
        });  


    /*  var infowindow = new InfoBubble({
          maxWidth: 400,  
          maxHeight:170,
          content: contentString
      }); */

     // console.log( myLatlng );
     // var labelAnchorpos = L.point(20,40);
     // var popAnchorpos   = L.point(-1,-40);

     // popupAnchor:[19,0]


 if(marker!=undefined){

  (function(marker) {

      marker.setIcon(icon);
      marker.bindPopup(infowindow);

   
      marker.addEventListener('click', function(e) {

         $scope.selects=1;

         marker.openPopup();
      // infowindow.open(maploc1,marker);

      });


  //    marker.addEventListener('click',function(e){
    /*  for(var j=0; j<ginfowindow.length;j++){
            ginfowindow[j].close();
          }*/
    //     marker.bindPopup(infowindow).togglePopup(500);
        //  infowindow.open(maploc1,marker);
     //      });
        /*google.maps.event.addListener(marker, "click", function(e) {
          for(var j=0; j<ginfowindow.length;j++){
            ginfowindow[j].close();
          }

          infowindow.open(map,marker);
          }); 
      })(marker);*/
    
  })(marker);
  }
}

/*marker. addEventListener('click', _markerOnClick);

var _markerOnClick = function(e) {
 .....(write your code here);
};*/

  $scope.infoBoxed = function(map, marker, vehicleID, lat, lng, data){
    
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
      +'<div style="padding-top:5px;"><a href="history?vid='+vehicleID+'&vg='+$scope.gName+'">Reports</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+vehicleID+'&track=single&maps=single" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/track?maps=replay&vehicleId='+vehicleID+'&gid='+$scope.gName+'" target="_blank">History</a> &nbsp;&nbsp; <a href="../public/track?vehicleId='+vehicleID+'&track=multiTrack&maps=mulitple" target="_blank">MultiTrack</a>&nbsp;&nbsp; <a href="#" ng-click="addPoi('+lat+','+lng+')">Save Site</a>'
    //+'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
      +'</div>';
      
      var compiled = $compile(contentString)($scope);
    //var drop1 = document.getElementById("ddlViewBy");
    //var drop_value1= drop1.options[drop1.selectedIndex].value;
      
      var infowindow = new InfoBubble({
        maxWidth: 400,  
        maxHeight:170,
        content: compiled[0]
      });

      ginfowindow.push(infowindow);
        if(marker!=undefined)
        (function(marker) {
        google.maps.event.addListener(marker, "click", function(e) {
          for(var j=0; j<ginfowindow.length;j++){
            ginfowindow[j].close();
          }
            $scope.selects=1;
        // if($scope.zohoReports==undefined){  
            infowindow.open(map,marker);
        // }
          }); 
      })(marker);

      google.maps.event.addListener(infowindow, "closeclick", function(){   
        
        $scope.selected=undefined;
        $scope.selected02=undefined;    
        $scope.selects=0;  
        $('#graphsId').toggle(300);   
     // $("#contentmin").toggle();    
      });
    
  }

  // for new window track
  $scope.days=0;
  $scope.days1=0;
  $scope.vehicle_list=[];
  $scope.fcode=[];
  $scope.final_data;
  
  // for list of vehicles
  // $http.get('http://'+globalIP+'/vamo/public//getVehicleLocations').success(function(data)
  
  function listVehicleName(data) {

    if(data[$scope.gIndex].vehicleLocations!=null){

      var vehiLength = data[$scope.gIndex].vehicleLocations.length;
       
        for (var i = 0; i < vehiLength; i++) {
           $scope.vehicle_list.push({'vehiID' : data[$scope.gIndex].vehicleLocations[i].vehicleId, 'vName' : data[$scope.gIndex].vehicleLocations[i].shortName})
        };
    }

    $scope.fcode.push(data[$scope.gIndex]);
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
            console.log("Sucess");
            $scope.toast = "Sucessfully Created ...";
            
            toastMsg();
            stopLoading();
          }
        }).fail(function() {
          console.log("fail");
          stopLoading();
        });
       
      } else {
        $scope.toast = "Enter all the field / Mark the Site ";
      }

    } catch (err)
    {
      console.log(err)
      $scope.toast = "Enter all the field / Mark the Site ";
      toastMsg();
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

  $scope.addPoi   = function(lat, lng){

    $scope.poiLat = lat;
    $scope.poiLng = lng;
    popUp_Open_Close();

  }

  $scope.submitPoi  = function(poiName){

    var width   = 1000;
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
    modal.style.display = "none";

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
      var f_code_url ='http://'+globalIP+context+'/public/getVehicleExp?vehicleId='+vehi+'&fcode='+f_code+'&days='+days+'&mailId='+mailId+'&phone='+phone;
      var ecrypt_code_url = '';
      $http.get(f_code_url).success(function(result){
        
        //console.log(result);
          
        var url='../public/track?vehicleId='+result.trim()+'&maps=track'+'&userID='+sp1[1];
        window.open(url,'_blank');
        
      });  
      }
  }
  
$scope.addMarker_osm= function(pos){
  //    var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
    //    var labelAnchorpos = new google.maps.Point(19, 0);  ///12, 37
       var myLatlng = L.latLng(pos.lat,pos.lng);
           //console.log( myLatlng );
    //   var labelAnchorpos = L.point(22,15);

      if(pos.data.position != 'N')
      {

        //  var myIcon    = L.icon({iconUrl: vamoservice.iconURL(pos.data),iconSize: [40,40],iconAnchor: labelAnchorpos});
        //  $scope.marker = new L.marker(myLatlng,{title:(pos.data.shortName)});
            $scope.marker = new L.marker(myLatlng).bindLabel(pos.data.shortName, { noHide: true });
            $scope.marker.addTo($scope.map_osm);

//
  /* var  myIcon = L.icon({
    iconUrl: 'my-icon.png',
    iconRetinaUrl: 'my-icon@2x.png',
    iconSize: [38, 95],
    iconAnchor: [22, 94],
    popupAnchor: [-3, -76],
    shadowUrl: 'my-icon-shadow.png',
    shadowRetinaUrl: 'my-icon-shadow@2x.png',
    shadowSize: [68, 95],
    shadowAnchor: [22, 94]
});L.marker([50.505, 30.57], {icon: myIcon}).addTo(map);*/

        /* $scope.marker = new MarkerWithLabel({
         position: myLatlng, 
         map: $scope.map,
         icon: vamoservice.iconURL(pos.data),
         labelContent: pos.data.shortName,
         labelAnchor: labelAnchorpos,
         labelClass: "labels", 
         labelInBackground: false
      });*/
  } else {
      var myIcon    = L.icon({iconUrl: vamoservice.iconURL(pos.data)});
      $scope.marker = new L.marker(myLatlng,{icon:myIcon});
      $scope.marker.addTo($scope.map_osm);
      /* $scope.marker = new MarkerWithLabel({
         position: myLatlng, 
         map: $scope.map,
         icon: vamoservice.iconURL(pos.data),
         labelInBackground: false
      });*/
      }
     
  if(pos.data.vehicleId==$scope.vehicleno){
      $scope.assignValue(pos.data);
      // if(pos.data.address == null || pos.data.address == undefined || pos.data.address == ' ')
      //    $scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){ 
      //      $('#lastseen').text(count); 
      //    });
      //  else
      //    $('#lastseen').text(pos.data.address);
      fetchingAddress(pos.data);
        //  $scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
      //  $('#lastseen').text(count);
      //  var t = vamoservice.geocodeToserver(pos.data.latitude,pos.data.longitude,count);
      // });    
    }
    
    gmarkers_osm.push($scope.marker);
    // $scope.marl.push($scope.marker);
  /*  google.maps.event.addListener(gmarkers[gmarkers.length-1], "click", function(e){  
      
      $scope.vehicleno = pos.data.vehicleId;
      $scope.assignValue(pos.data);
      $('#graphsId').show(500);
      editableValue();

      fetchingAddress(pos.data);

      sessionStorage.setItem('user', JSON.stringify(pos.data.vehicleId+','+$scope.gName));

      // $scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
      //  $('#lastseen').text(count); 
      //  var t = vamoservice.geocodeToserver(pos.data.latitude,pos.data.longitude,count);
                  
      // });
      if($scope.selected!=undefined){
        $scope.map.setCenter(gmarkers[$scope.selected].getPosition());  
      }
       });*/


gmarkers_osm[gmarkers_osm.length-1].addEventListener( "click", function(e){ 

      $scope.vehicleno = pos.data.vehicleId;
      $scope.assignValue(pos.data);
      $('#viewable,#rightAddress2').show();

      $scope.$apply(function(){
          $scope.vehiclFuel=graphChange(pos.data.fuel);
      });

      if($scope.vehiclFuel==true){
          $('#graphsId').removeClass('graphsCls');
      } else {
          $('#graphsId').addClass('graphsCls');
      }
      $('#graphsId').show();

      //editableValue();
      fetchingAddress(pos.data);
      sessionStorage.setItem('user', JSON.stringify(pos.data.vehicleId+','+$scope.gName));

   // $scope.vehiRowId=pos.data.rowId;

      // $scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
      //  $('#lastseen').text(count); 
      //  var t = vamoservice.geocodeToserver(pos.data.latitude,pos.data.longitude,count);
                  
      // });
      if($scope.selected!=undefined){
        $scope.map_osm.setView(gmarkers_osm[$scope.selected02].getLatLng());   
      }
       });
    
}
  
  $scope.addMarker= function(pos){
      
      var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
      var labelAnchorpos = new google.maps.Point(19, 0);  ///12, 37
      if(pos.data.position != 'N')
      {
         $scope.marker = new MarkerWithLabel({
         position: myLatlng, 
         map: $scope.map,
         icon: vamoservice.iconURL(pos.data),
         labelContent: pos.data.shortName,
         labelAnchor: labelAnchorpos,
         labelClass: "labels", 
         labelInBackground: false
      });
      }
      else 
      {
         $scope.marker = new MarkerWithLabel({
         position: myLatlng, 
         map: $scope.map,
         icon: vamoservice.iconURL(pos.data),
         labelInBackground: false
      });
      }
     
      
    if(pos.data.vehicleId==$scope.vehicleno){
      $scope.assignValue(pos.data);

      // if(pos.data.address == null || pos.data.address == undefined || pos.data.address == ' ')
      //    $scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){ 
      //      $('#lastseen').text(count); 
      //    });
      //  else
      //    $('#lastseen').text(pos.data.address);
      fetchingAddress(pos.data);

     //    $scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
      //  $('#lastseen').text(count);
      //  var t = vamoservice.geocodeToserver(pos.data.latitude,pos.data.longitude,count);
                  
      // });    
    }
    gmarkers.push($scope.marker);
  //$scope.marl.push($scope.marker);

    google.maps.event.addListener(gmarkers[gmarkers.length-1], "click", function(e) {  

   // if($scope.zohoReports==undefined){ 
      $scope.vehicleno = pos.data.vehicleId;
      $scope.assignValue(pos.data);

      $scope.$apply(function(){
          $scope.vehiclFuel=graphChange(pos.data.fuel);
      });

      $('#viewable,#rightAddress2').show();

      if($scope.vehiclFuel==true){
          $('#graphsId').removeClass('graphsCls');
      } else {
          $('#graphsId').addClass('graphsCls');
      }
      $('#graphsId').show();
      
      //editableValue();

      fetchingAddress(pos.data);
      sessionStorage.setItem('user', JSON.stringify(pos.data.vehicleId+','+$scope.gName));
     // $scope.vehiRowId=pos.data.rowId;

      // $scope.getLocation(pos.data.latitude, pos.data.longitude, function(count){
      // $('#lastseen').text(count); 
      // var t = vamoservice.geocodeToserver(pos.data.latitude,pos.data.longitude,count);
      // });

      if($scope.selected02!=undefined){
        $scope.map.setCenter(gmarkers[$scope.selected02].getPosition());  
      }

   // }

    });
  }
  
  $scope.assignValue=function(dataVal) {

   // console.log(dataVal);

    $scope.vehicleid = dataVal.vehicleId;
    $scope.vehShort  = dataVal.shortName;
    $scope.ododis    = dataVal.odoDistance;

    $scope.spLimit   = dataVal.overSpeedLimit;
    $scope.driName   = dataVal.driverName;
    $scope.refname   = dataVal.regNo;
    $scope.vehType   = dataVal.vehicleType;
    $scope._editValue.routeName = dataVal.routeName;

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
    $('#routeName span').text(dataVal.routeName);
    $('#vehitype span').text(dataVal.vehicleType);
    $('#mobNo span').text(dataVal.mobileNo);
    $('#graphsId #speed').text(dataVal.speed);
    $('#graphsId #fuel').text(dataVal.tankSize);

    if(dataVal.tankSize!=0 && dataVal.fuelLitre!=0){ 
      tankSize     = parseInt(dataVal.tankSize);
      fuelLtr      = parseInt(dataVal.fuelLitre);
    }else if(dataVal.tankSize!=0 && dataVal.fuelLitre==0){
      tankSize     = parseInt(dataVal.tankSize);
      fuelLtr      = parseInt(dataVal.fuelLitre);
    }else if(dataVal.tankSize==0 && dataVal.fuelLitre==0){
      tankSize     = parseInt(dataVal.tankSize);
      fuelLtr      = parseInt(dataVal.fuelLitre);
    }

    $("#safeParkShow").show();
    
    total        = parseInt(dataVal.speed);
    $('#vehdevtype #val').text(dataVal.odoDistance);
    $('#mobno #val').text(dataVal.overSpeedLimit);
    $('#positiontime').text(vamoservice.statusTime(dataVal).tempcaption);
    $('#regno #val').text(vamoservice.statusTime(dataVal).temptime);
    $('#driverName #val').text(dataVal.driverName);
    $('#lstseendate').html(new Date(dataVal.date).toString().split('GMT')[0])
 
  }
  
  $scope.enterkeypress = function(){
    var url = GLOBAL.DOMAIN_NAME+'/setPOIName?vehicleId='+$scope.vehicleno+'&poiName='+document.getElementById('poival').value;
    if(document.getElementById('poival').value=='' || $scope.vehicleno==''){}else{
      vamoservice.getDataCall(url).then(function(data) {
        document.getElementById('poival').value='';
      });
    }
  }
  
$scope.createGeofence=function(url) {
    if($scope.cityCirclecheck==false){
      $scope.cityCirclecheck=true;
    }
    if($scope.cityCirclecheck==true){
      for(var i=0; i<$scope.cityCircle.length; i++){
        $scope.cityCircle[i].setMap(null);
      }
      for(var i=0; i<geomarker.length; i++){
        geomarker[i].setMap(null);
        
      }
      for(var i=0; i<geoinfo.length; i++){
        geoinfo[i].setMap(null);
      }
      
    }
    vamoservice.getDataCall(url).then(function(data) {
      $scope.geoloc = data;
      if (typeof(data.geoFence) !== 'undefined' && data.geoFence.length) {  
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
            geomarker.push(beachMarker);
          }
          var myOptions = { 
             content: labelText, 
             boxStyle: {
              textAlign: "center", 
              fontSize: "9pt", 
              fontColor: "#ff0000", 
              width: "100px"
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
          // labelinfo.setPosition($scope.cityCircle[i].getCenter());
           geoinfo.push(labelinfo);
        }
      }
    });
  }

  $scope.initial02 = function() {
    //console.log(' marker click ')
  //  if($scope.zohoReports==undefined){
    $scope.assignHeaderVal($scope.locations02);
  //    }
    //var locs = $scope.locations;
    var locs           = $scope.locations03;
    var parkedCount    = 0;
    var movingCount    = 0;
    var idleCount      = 0;
    var overspeedCount = 0;


    if($scope.locations02[$scope.gIndex].vehicleLocations != null){

     var locLength=$scope.locations02[$scope.gIndex].vehicleLocations.length;
    
     for (var i = 0; i < locLength; i++) {
      if($scope.locations02[$scope.gIndex].vehicleLocations[i].position=="P"){
        parkedCount=parkedCount+1;
      }else  if($scope.locations02[$scope.gIndex].vehicleLocations[i].position=="M"){
        movingCount=movingCount+1;
      }else if($scope.locations02[$scope.gIndex].vehicleLocations[i].position=="S"){
        idleCount=idleCount+1;
      }
      if($scope.locations02[$scope.gIndex].vehicleLocations[i].isOverSpeed=='Y'){
        overspeedCount=overspeedCount+1;
      }
     }

    }
    
    $scope.parkedCount    = parkedCount;
    $scope.movingCount    = movingCount;
    $scope.idleCount      = idleCount;
    $scope.overspeedCount = overspeedCount;
    
    for (var i = 0; i < $scope.locations03.length; i++) {
       // var temp = $scope.locations[i];  
       // var lat = temp.latitude;
       // var lng =  temp.longitude;
       // var latlng = new google.maps.LatLng(lat,lng);
       // gmarkers[i].icon = vamoservice.iconURL(temp);
       // gmarkers[i].setPosition(latlng);
       // gmarkers[i].setMap($scope.map);
       // $scope.infoBoxed($scope.map,gmarkers[i], temp.vehicleId, lat, lng, temp); 
       if($scope.locations03[i].vehicleId==$scope.vehicleno){
         $scope.assignValue($scope.locations03[i]);
      //   if($scope.selects){    
           //$scope.selected=i;
             $scope.selected = $scope.locations03[i].rowId;

          if($scope.tabletds==undefined){
            $scope.selected02=i;
          }
      //  }
        // fetchingAddress($scope.locations03[i]);
         // $scope.getLocation(lat, lng, function(count){
          //  $('#lastseen').text(count);
          //  var t = vamoservice.geocodeToserver(lat,lng,count);
         // }); 
       }
       //$scope.infoBoxed($scope.map,gmarkers[i], temp.vehicleId, lat, lng, temp);
    }

    if($scope.maps_name==0){
        if($scope.selected02!=undefined){
         //if($scope.zohoReports==undefined) {
            $scope.map.setCenter(gmarkers[$scope.selected02].getPosition());
            if($scope.selects){
              ginfowindow[$scope.selected02].open($scope.map,gmarkers[$scope.selected02]);
            }
          //}
        }
    
        if($scope.groupMap==true){
          markerCluster.clearMarkers();
        //mcOptions = {gridSize: 50, maxZoom: 15};
          markerCluster   = new MarkerClusterer($scope.map, gmarkers, mcOptions)  
        }

        $scope.markerJump($scope.locations03);
        markerChange($scope.makerType);
  
    } else if($scope.maps_name==1){

        if($scope.selected02!=undefined){

          $scope.map_osm.setView(gmarkers_osm[$scope.selected02].getLatLng());

          if($scope.selects){
            ginfowindow_osm[$scope.selected02].setLatLng(gmarkers_osm[$scope.selected02].getLatLng()).openOn($scope.map_osm);
          }

        }
    }

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


//draw polygon in osm map 

function polygonDraw_osm(data){

    var sp_osm,myLatlngs;
    polygonList2 = []; 

    var splits   = data.latLng.split(",");

    for(var i = 0; splits.length>i; i++) {
       sp_osm           = splits[i].split(":");
       myLatlngs = new L.LatLng(sp_osm[0], sp_osm[1]);
       polygonList2.push(myLatlngs);
    }

    $scope.polygonOsm = L.polygon(polygonList2,{ className: 'polygon_osm' }).addTo($scope.map_osm);

    if(myLatlngs) {
        var markerPoly = new L.marker(myLatlngs);
        markerPoly.setIcon($scope.iconPoly).bindLabel(data.siteName, { noHide: true, className: 'polygonLabel', clickable: true, direction:'auto' });
        markerPoly.addTo($scope.map_osm);
    }
}

//draw polygen in map function
function polygenDrawFunction(list){
    
      // if(list.length=>0){
      var sp;
      polygenList   = [];
      var split     = list.latLng.split(",");
      for(var i = 0; split.length>i; i++)
      {
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
      
        ///12, 37
      $scope.marker = new MarkerWithLabel({
         position: centerMarker(polygenList), 
         map: $scope.map,
         icon: 'assets/image/area_img.png',
         color: '#fff',
         labelContent: list.siteName,
         labelAnchor: labelAnchorpos,
         labelClass: "maps", 
         labelInBackground: false
      });
      $scope.map.setCenter(centerMarker(polygenList)); 
      $scope.map.setZoom(14);  
    // }
}

//locations

function locat_address(locs) {
  var googleLatAndLong = new google.maps.LatLng(locs.lat, locs.lng);
  var labelAnchorpos = new google.maps.Point(70, 80);
  $scope.marker = new MarkerWithLabel({
        position: googleLatAndLong,
        icon: 'assets/imgs/area_loc.png', 
        map: $scope.map,
        color: '#fff',
        labelClass: "labels1",
        labelContent: locs.address,
        labelAnchor: labelAnchorpos, 
        labelInBackground: false
      });
      $scope.map.setCenter(googleLatAndLong); 
      $scope.map.setZoom(14);
}



 function siteInvoke(val){
    var url_site          = GLOBAL.DOMAIN_NAME+'/viewSite';
    vamoservice.getDataCall(url_site).then(function(data) {
      // console.log(data);
      if(data.siteParent && $scope._addPoi == false){

        if(map_change==0){
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
        } else if(map_change==1){

           $scope.iconPoly = L.icon({
             iconUrl: 'assets/imgs/trans.png',
             iconAnchor:[0,0],
             labelAnchor: [-15,10],
           }); 

           angular.forEach(data.siteParent, function(value, key){
           //console.log(' value'+key);
            if(val == value.orgId){
              angular.forEach(value.site, function(vals, keys){
              //console.log('inside the for loop');
                polygonDraw_osm(vals);
              }); 

            if($scope.polygonOsm){
              $scope.map_osm.fitBounds($scope.polygonOsm.getBounds());
            }  

             /* if(value.location.length>0){
                  angular.forEach(value.location, function(locs, ind){
                     locat_address(locs);
                  });
                } */
            }
          });
        }
      }

      if(data && data.orgIds != undefined){
        $scope.orgIds   = data.orgIds;
      }

    })
  }


  // polygen draw function
  function polygenFunction(getVehicle){
    //console.log(' getVehicle ')
    var polygenOrgs   = [];
    var unique      =   new Set();
    polygenOrgs     = ($filter('filter')(getVehicle[$scope.gIndex].vehicleLocations, {'live': 'yes'}));
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


  //jump marker
  $scope.markerJump = function(getDat){
    //console.log(' val '+gmarkers.length)
    angular.forEach(gmarkers, function(val, key){
      if(getDat[key].live=='yes')
        if(getDat[key].insideGeoFence=='N')
          gmarkers[key].setAnimation(google.maps.Animation.BOUNCE);
        else if(getDat[key].insideGeoFence=='Y')
          gmarkers[key].setAnimation(null);
      else
        gmarkers[key].setAnimation(null);
      gmarkers[key].setMap($scope.map);
    })
  }


      function setMapAllOsm(){ 

            if( $scope.initOsm>1){    
                clearMarkerOsm();  
             }

           // console.log('marker cluster..');
            $scope.markerClusters = new L.MarkerClusterGroup();
            
            for(var i=0;i<gmarkers_osm.length;i++){
               $scope.markerClusters.addLayer(gmarkers_osm[i]);
            }
            
            $scope.map_osm.addLayer($scope.markerClusters);
         
      }

      function setMapOnAll() {    
        for (var i = 0; i < gmarkers.length; i++) {   
          gmarkers[i].setMap($scope.map);   
        }   
      }

      function clearMarkerOsm() {
        for(var i=0;i<gmarkers_osm.length;i++){
             $scope.map_osm.removeLayer(gmarkers_osm[i]);
        }

       }

      function clearMarkerss() {    
         for (var i = 0; i < gmarkers.length; i++) {    
          gmarkers[i].setMap(null);   
          ginfowindow[i].setMap(null);    
        } 
      }  

  $scope.inits   = 0;   
  $scope.initOsm = 0; 
  $scope.init = function(){

 // var locs   = $scope.locations;
    var locs   = $scope.locations03;    
    var length = locs.length; 

    if($scope.zohoCloseBut){
      $scope.zohod=1;
    }

    if($scope.maps_name==0){

        $scope.inits = $scope.inits+1; 

        if( $scope.inits>1){    
         clearMarkerss();   
        }

      gmarkers      = [];    
      ginfowindow   = [];  

     for (var i = 0; i < length; i++) {    
        var lat = locs[i].latitude;   
        var lng = locs[i].longitude;   

        $scope.addMarker({ lat: lat, lng: lng , data: locs[i]});  
        $scope.infoBoxed($scope.map,gmarkers[i], locs[i].vehicleId, lat, lng, locs[i]);  
      } 

       setMapOnAll();  

    } else if($scope.maps_name==1) {

     // console.log('osmmmmm....');

      $scope.initOsm = $scope.initOsm+1; 


      if($scope.groupMap == true){

        if($scope.markerClusters){

        $scope.markerClusters.clearLayers();

      }

       //  console.log('clearLayers');

       if( $scope.initOsm>1){    
         clearMarkerOsm();  
       } 

      }else {

        if( $scope.initOsm>1){    
         clearMarkerOsm();  
       }

      }

      gmarkers_osm    = [];
      ginfowindow_osm = [];
        
      for (var i = 0; i < length; i++) {    
        var lat = locs[i].latitude;   
        var lng = locs[i].longitude;   

        $scope.addMarker_osm({ lat: lat, lng: lng , data: locs[i]});  
        $scope.infoBoxed_osm($scope.map_osm,gmarkers_osm[i], locs[i].vehicleId, lat, lng, locs[i]);    
      }

      if($scope.groupMap == true){

     setMapAllOsm(); 
    }
  }
}


$scope.initilize_osm = function(ID){

   // console.log('initilize_osm...');
      var location02 = $scope.locations02;

      if($('.nav-second-level li').eq($scope.selected).children('a').hasClass('active')){
        }else{
        $('.nav-second-level li').eq($scope.selected).children('a').addClass('active');
      }
      var locs = $scope.locations03;
      $scope.assignHeaderVal(location02);
      
      var lat = location02[$scope.gIndex].latitude;
      var lng = location02[$scope.gIndex].longitude;


      /*  mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';
          map_osm = new L.map('map_osm',{ center: [lat, lng], minZoom: 4,zoom: $scope.zoomLevel });

          new L.tileLayer(
            'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
             attribution: '&copy; '+mapLink+' Contributors',
          // maxZoom: 18,
            }).addTo(map_osm); */

          mapLink = '<a href="http://207.154.194.241/nominatim/lf.html">OpenStreeetMap</a>'
          $scope.map_osm = new L.map('map_osm',{ center: [lat, lng],/* minZoom: 4,*/zoom: $scope.zoomLevel });

          new L.tileLayer(
            'http://207.154.194.241/osm_tiles/{z}/{x}/{y}.png', {
             attribution: '&copy; '+mapLink+' Contributors',
          // maxZoom: 18,
          }).addTo($scope.map_osm);

  /*    google.maps.event.addListener($scope.map, 'click', function(event) {
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
          var tempurl = 'http://'+globalIP+context+'/public//getNearByVehicles?lat='+event.latLng.lat()+'&lng='+event.latLng.lng();
          
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
      google.maps.event.addListener($scope.map, 'bounds_changed', function() {
        var bounds = $scope.map.getBounds();
      });*/
      var parkedCount = 0;
      var movingCount = 0;
      var idleCount = 0;
      var overspeedCount =0;
      
      for (var i = 0; i < location02[$scope.gIndex].vehicleLocations.length; i++) {
        if(location02[$scope.gIndex].vehicleLocations[i].position=="P"){
          parkedCount=parkedCount+1;
        }else  if(location02[$scope.gIndex].vehicleLocations[i].position=="M"){
          movingCount=movingCount+1;
        }else if(location02[$scope.gIndex].vehicleLocations[i].position=="S"){
          idleCount=idleCount+1;
        }
        if($scope.locations02[$scope.gIndex].vehicleLocations[i].isOverSpeed=='Y'){
          overspeedCount=overspeedCount+1;
        }
      }
      
      $scope.parkedCount = parkedCount;
      $scope.movingCount = movingCount;
      $scope.idleCount  = idleCount;
      $scope.overspeedCount = overspeedCount;

      $scope.init();
      
    /*  var length = locs.length;
      gmarkers_osm=[];
      ginfowindow_osm=[];
      for (var i = 0; i < length; i++) {
        var lat = locs[i].latitude;
        var lng =  locs[i].longitude;
          // console.log(' lat :'+lat+'lan :'+lng+'data :'+locs[i]);
          // console.log('marker :'+gmarkers[i]+' vehicle ID : '+ locs[i].vehicleId+'  lat  :'+ lat+'lng  :'+ lng);
      
          $scope.addMarker_osm({ lat: lat, lng: lng , data: locs[i]});
          //  console.log('addMarker_osm called...');
          $scope.infoBoxed_osm($scope.map_osm,gmarkers_osm[i], locs[i].vehicleId, lat, lng, locs[i]);  
        
      }*/
  //  });

    $scope.loading  = false;

  /*  if($scope.selected>-1 && gmarkers[$scope.selected]!=undefined){
      $scope.map.setCenter(gmarkers[$scope.selected].getPosition());  
    }
    $(document).on('pageshow', '#maploc', function(e){       
          google.maps.event.trigger(document.getElementById(' maploc'), "resize");
      });
    */

  //marker jump

  // $scope.markerJump_osm(location02[$scope.gIndex].vehicleLocations);

  //for the polygen draw

   polygenFunction(location02);

  // $('#status').fadeOut(); 
  // $('#preloader').delay(350).fadeOut('slow');
  // $('body').delay(350).css({'overflow':'visible'});
  
  stopLoading();

}


$scope.initilize = function(ID){
    
  //  vamoservice.getDataCall($scope.url).then(function(location02) {
      var location02 = $scope.locations02;
      if($('.nav-second-level li').eq($scope.selected).children('a').hasClass('active')){
      }else{
        $('.nav-second-level li').eq($scope.selected).children('a').addClass('active');
      }
      var locs = $scope.locations03;
      $scope.assignHeaderVal(location02);
      
      var lat = location02[$scope.gIndex].latitude;
      var lng = location02[$scope.gIndex].longitude;
       var myOptions = { zoom: $scope.zoomLevel, zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, center: new google.maps.LatLng(lat, lng), mapTypeId: google.maps.MapTypeId.ROADMAP/*,styles: [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]*/};
      $scope.map = new google.maps.Map(document.getElementById(ID), myOptions);

      geocoderVar = new google.maps.Geocoder();  

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
      google.maps.event.addListener($scope.map, 'bounds_changed', function() {
        var bounds = $scope.map.getBounds();
      });
      var parkedCount = 0;
      var movingCount = 0;
      var idleCount = 0;
      var overspeedCount =0;

      if(location02[$scope.gIndex].vehicleLocations != null){

        var locsLength=location02[$scope.gIndex].vehicleLocations.length;
      
      for (var i = 0; i < locsLength; i++) {

        if(location02[$scope.gIndex].vehicleLocations[i].position=="P"){
          parkedCount=parkedCount+1;
        }else  if(location02[$scope.gIndex].vehicleLocations[i].position=="M"){
          movingCount=movingCount+1;
        }else if(location02[$scope.gIndex].vehicleLocations[i].position=="S"){
          idleCount=idleCount+1;
        }
        if($scope.locations02[$scope.gIndex].vehicleLocations[i].isOverSpeed=='Y'){
          overspeedCount=overspeedCount+1;
        }
      }

      }
      
      $scope.parkedCount    = parkedCount;
      $scope.movingCount    = movingCount;
      $scope.idleCount      = idleCount;
      $scope.overspeedCount = overspeedCount;

    //  if($scope.zohoReports==undefined){


   //   console.log('innitsss....');
      $scope.init();

    if($scope.mapInit==0){  
      $scope.mapInit=1;
      setTimeout(function() {
       var count = window.localStorage.getItem("totalV");
       var number = parseInt(count);
     //console.log(number);
    
        if(number > 50) {
          $scope.displayErrorMsg = false;
          clusterMarker();
        //console.log("asa");
        } 
      },3000);
    }

      //    }
      // var length = locs.length;
      // gmarkers=[];
      // ginfowindow=[];
      // for (var i = 0; i < length; i++) {
      //  var lat = locs[i].latitude;
      //  var lng =  locs[i].longitude;
        
      //    // console.log(' lat :'+lat+'lan :'+lng+'data :'+locs[i]);
      //    // console.log('marker :'+gmarkers[i]+' vehicle ID : '+ locs[i].vehicleId+'  lat  :'+ lat+'lng  :'+ lng);
      //    $scope.addMarker({ lat: lat, lng: lng , data: locs[i]});
      //    $scope.infoBoxed($scope.map,gmarkers[i], locs[i].vehicleId, lat, lng, locs[i]); 
        
      // }
  //  });
    $scope.loading  = false;
    if($scope.selected02>-1 && gmarkers[$scope.selected02]!=undefined){
      $scope.map.setCenter(gmarkers[$scope.selected02].getPosition());  
    }
    $(document).on('pageshow', '#maploc', function(e){       
          google.maps.event.trigger(document.getElementById(' maploc'), "resize");
      });
       
    //   if($scope.zohoReports==undefined){
    //marker jump
    $scope.markerJump(location02[$scope.gIndex].vehicleLocations);
    //    }
    //for the polygen draw
    polygenFunction(location02);

  //      $('#status').fadeOut(); 
    // $('#preloader').delay(350).fadeOut('slow');
    // $('body').delay(350).css({'overflow':'visible'});
  stopLoading();

    var input_value   =  document.getElementById('pac-input');
    var sbox          =  new google.maps.places.SearchBox(input_value);
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

  }
  
  //click and resolve address
  $scope.address_click = function(data, ind)
  {
    console.log(' inside the address function')
    var tdurl     = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+data.latitude+','+data.longitude+"&sensor=true"
    vamoservice.getDataCall(tdurl).then(function(response) {
      console.log(response)
      // data.address   = response.results[0].formatted_address;
      // var t      =   vamo_sysservice.geocodeToserver(data.latitude,data.longitude,response.results[0].formatted_address);
    });
  }

  $scope.removeTask_osm=function(vehicleno,rowVal){
    
    $scope.vehicleno = vehicleno;
    var temp = $scope.locations03;
    //$scope.endlatlong = new google.maps.LatLng();
    //$scope.startlatlong = new google.maps.LatLng();
    //$scope.map.setZoom(16);
    $scope.map_osm.setZoom(16);
    $scope.dynamicvehicledetails1=true;

    for(var i=0; i<temp.length;i++){
      if(temp[i].vehicleId==$scope.vehicleno){
        
        $scope.selected02=i;  
      //$scope.vehiRowId=$scope.selected02;
        
        //$scope.map.setCenter(gmarkers[i].getPosition());
        $scope.map_osm.setView(gmarkers_osm[i].getLatLng());

        //$scope.assignValue(temp[i]);
        //fetchingAddress(temp[i]);
        // if(temp[i].address == null || temp[i].address == undefined || temp[i].address == ' ')
        //  $scope.getLocation(temp[i].latitude, temp[i].longitude, function(count){ 
        //    $('#lastseen').text(count); 
        //  });
        // ginfowindow_osm
        //  $('#lastseen').text(temp[i].address);

        $scope.infowindowShow={}; 

     /* for(var j=0; j<ginfowindow.length;j++){
            ginfowindow[j].close();
        } */

        ginfowindow_osm[i].setLatLng(gmarkers_osm[i].getLatLng()).openOn($scope.map_osm);
      // var url = 'http://'+globalIP+context+'/public//getGeoFenceView?vehicleId='+$scope.vehicleno;
      // $scope.createGeofence(url);
        
      }
    }
  };


  $scope.removeTask=function(vehicleno,rowVal){
    $scope.vehicleno = vehicleno;
    var temp         = $scope.locations03;
    //$scope.endlatlong = new google.maps.LatLng();
    //$scope.startlatlong = new google.maps.LatLng();
    $scope.map.setZoom(16);
    $scope.dynamicvehicledetails1=true;

    for(var i=0; i<temp.length;i++){
      if(temp[i].vehicleId==$scope.vehicleno){
        
        $scope.selected02=i;    
     // $scope.vehiRowId=$scope.selected02;

        $scope.map.setCenter(gmarkers[i].getPosition());
        
        //$scope.assignValue(temp[i]);
        //fetchingAddress(temp[i]);
        // if(temp[i].address == null || temp[i].address == undefined || temp[i].address == ' ')
        //  $scope.getLocation(temp[i].latitude, temp[i].longitude, function(count){ 
        //    $('#lastseen').text(count); 
        //  });
        // else
        //  $('#lastseen').text(temp[i].address);

        $scope.infowindowShow={};       
        for(var j=0; j<ginfowindow.length;j++){
          ginfowindow[j].close();
        }
        ginfowindow[i].open($scope.map,gmarkers[i]);
        var url = GLOBAL.DOMAIN_NAME+'/getGeoFenceView?vehicleId='+$scope.vehicleno;
        $scope.createGeofence(url);
        
      }
    }
  };


  function markerChange_osm(value){
    var icon , img;
      
    angular.forEach($scope.locations, function(valu, key){
      
    //img = ($scope.makerType == 'markerChange')? 'assets/imgs/'+valu.vehicleType+'.png' : vamoservice.iconURL(valu)
      img = ($scope.makerType == 'markerChange')? vamoservice.markerImage(valu) : vamoservice.iconURL(valu);

      if($scope.makerType == 'markerChange')
      {
        icon = L.icon({
                iconUrl: img ,
                iconSize: [40,40], 
                iconAnchor:[20,40],
                popupAnchor:[-1,-40]//scaledSize: new google.maps.Size(25, 25)
      });
      }
      else
      {
        icon = L.icon({
                iconUrl: img , //scaledSize: new google.maps.Size(25, 25)
                iconSize: [40,40], 
                iconAnchor:[20,40],
                popupAnchor:[-1,-40]
      });

      }
      
        gmarkers_osm[key].setIcon(icon);
      //gmarkers[key].setMap($scope.map);

    });

  }

function markerChange(value){
    var icon , img;
      
    angular.forEach($scope.locations03, function(valu, key){
      
      // img = ($scope.makerType == 'markerChange')? 'assets/imgs/'+'Car2'+'.png' : vamoservice.iconURL(valu)
         img = ($scope.makerType == 'markerChange')? vamoservice.markerImage(valu) : vamoservice.iconURL(valu);

      if($scope.makerType == 'markerChange'){
        icon = {scaledSize: new google.maps.Size(30, 30),url: img} //scaledSize: new google.maps.Size(25, 25)
       // icon = {scaledSize: new google.maps.Size(30, 30),url: img} //scaledSize: new google.maps.Size(25, 25) valu.vehicleType
      
      } else {
        icon = {url: img}
      }
      gmarkers[key].setIcon(icon);
      gmarkers[key].setMap($scope.map);

    });
    
  }

  $('#viewable,#rightAddress2').hide();
  $('#mapTable-mapList').hide()
  $("#homeImg").hide();
  $("#listImg").show();
  $("#single").hide();
  $("#cluster").show();
  $("#efullscreen").hide();
  $("#fullscreen").show();
  $('#graphsId').hide();
  $("#carMarker").show();
  $("#marker").hide();
  $("#tollYes").show();
  $("#tollNo").hide();
  // $scope.idinvoke;
  //list view
  
  function listMap() {
  //if($scope.zohoReports==undefined){
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
  // }
  }

  //return home
  function homeMap() {
    setId();
    $scope.tabView=0;
  //$("#listImg").show();
    $("#homeImg").hide();
    $("#listImg").show();
    $("#fullscreen").show();
    $("#contentmin").show(1000);
    $("#sidebar-wrapper").show(500);
  //document.getElementById($scope.idinvoke).setAttribute("id", "mapList")
    document.getElementById($scope.idinvoke).setAttribute("id", "wrapper");
  }

  
  // clusterMarker 
  function clusterMarker_osm() {

    var img, icon;
    
    $("#cluster").hide();
    $("#single").show();
    
    $scope.groupMap  = true;
    $scope.markerClusters = new L.MarkerClusterGroup();

  //console.log(markerClusters);
  //gmarkers[i].setView(map_osm);

    for(var i=0;i<gmarkers_osm.length;i++){
      $scope.map_osm.removeLayer(gmarkers_osm[i]);
    }

    for(var i=0;i<gmarkers_osm.length;i++){
        $scope.markerClusters.addLayer(gmarkers_osm[i]);
    }

    $scope.map_osm.addLayer($scope.markerClusters);
  }


  function clusterMarker() {
       $("#cluster").hide();
       $("#single").show();
       $scope.groupMap = true;
    // markerCluster  = new MarkerClusterer($scope.map, null, null)
    // mcOptions = {gridSize: 50,maxZoom: 15,styles: [ { height: 53, url: "assets/imgs/m1.png", width: 53}]}
       markerCluster = new MarkerClusterer($scope.map, gmarkers, mcOptions)  
  }


  //single
  function singleMarker_osm() {

  // console.log('singles.......');

   $("#single").hide();
   $("#cluster").show();
   $scope.groupMap=false;

   var locLen = $scope.locations03;

    $scope.markerClusters.clearLayers();

     for(var i=0;i<gmarkers_osm.length;i++){
      $scope.map_osm.removeLayer(gmarkers_osm[i]);
    }

       var locOsm   = $scope.locations03;    
       var locLen   = locOsm.length;

      gmarkers_osm=[];
      ginfowindow_osm = [];
        
      for (var i = 0; i < locLen; i++) {    
        var lat = locOsm[i].latitude;   
        var lng = locOsm[i].longitude;   

        $scope.addMarker_osm({ lat: lat, lng: lng , data: locOsm[i]});  
        $scope.infoBoxed_osm($scope.map_osm,gmarkers_osm[i], locOsm[i].vehicleId, lat, lng, locOsm[i]);    
      }

  }

  //single
  function singleMarker(){
    $("#single").hide();
    $("#cluster").show();
    $scope.groupMap=false;
    markerCluster.clearMarkers();
    //console.log(gmarkers.length)
    for(var i=0; i<gmarkers.length; i++){
      gmarkers[i].setMap($scope.map);
    }
  }

  // changeMarker
  function changeMarker_osm(){
    if($scope.makerType == undefined)
    {
      $("#carMarker").show();
      $("#marker").hide();  
    } else if ($scope.makerType == 'markerChange'){
      $("#carMarker").hide();
      $("#marker").show();  
    }
  }

  // changeMarker
  function changeMarker(){
    if($scope.makerType == undefined)
    {
      $("#carMarker").show();
      $("#marker").hide();  
    } else if ($scope.makerType == 'markerChange'){
      $("#carMarker").hide();
      $("#marker").show();  
    }
  }

  function fullScreen(){
    setId();
    $("#efullscreen").show();
    $("#contentmin").show(1000);
    $("#sidebar-fullscreen").show(500);
    document.getElementById($scope.idinvoke).setAttribute("id", "sidebar-fullscreen");
  }

  function exitScreen(){
    setId();
    $("#fullscreen").show();
  //$("#listImg").show();
  //$("#homeImg").hide();
    $("#contentmin").show(1000);
    $("#sidebar-wrapper").show(500);
    document.getElementById($scope.idinvoke).setAttribute("id", "wrapper");
  }

  function setId(){
    $("#minmax").show();
    //$("#efullscreen").hide();
    //$("#sidebar-wrapper").hide(500);
    $("#fullscreen").hide();

    $("#efullscreen").hide();
    $('#mapTable-mapList').hide(500);
    //$("#efullscreen").hide();
    
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

  function fulltable() {
    setId();
    $("#minmax").hide();
    document.getElementById($scope.idinvoke).setAttribute("id", "tablelist");
    // $("#contentmin").show(1000);
    // $("#sidebar-wrapper").hide(500);
    // $("#sidebar-fullscreen").hide(500);
    // $('#mapTable-mapList').show(1000);
    // document.getElementById('menuView').setAttribute("id", "menuView");
    // $('#menuView').show(1000);

    document.getElementById('mapTable-mapList').setAttribute("id", "talist");
    
    $('#talist').show(500);

    // $("#contentmin").show(1000);
    // $("#sidebar-wrapper").show(500);
  }

  function graphView()
  {
    $('#graphsId').toggle(500);
  }

 /* function tollMarkers() {
      $("#tollYes").hide();
      $("#tollNo").show();
    } */

/*  function removeToll() {
      $("#tollNo").hide();
      $("#tollYes").show();
    } */

    //alert($scope.totalVehicles);

    function clusterMarkerChange(){
      if(map_change==0) {
        clusterMarker();
      } else if(map_change==1) {
        clusterMarker_osm();
      }
    }

    function singleMarkerChange() {
       if(map_change==0){
        singleMarker();
      } else if(map_change==1){
        singleMarker_osm();
      }
    }

    function mapMarkerChange() {
      if(map_change==0){
        $scope.makerType = "markerChange";
        changeMarker();
        markerChange('markerChange');
      }else if(map_change==1){
        $scope.makerType = "markerChange";
        changeMarker_osm();
        markerChange_osm('markerChange');
      }
    }

    function mapMarkerChange_2(){
        if(map_change==0){
          $scope.makerType =  undefined;
          changeMarker();
          markerChange(undefined);
        }else if(map_change==1){
          $scope.makerType =  undefined;
          changeMarker_osm();
          markerChange_osm(undefined);
        }
    }

   //view map on load
  $scope.mapViewOnload  = function(value){
    if (window.localStorage.getItem("totalV") >= 50){
    //var value ='cluster';
   // console.log(value);
    switch(value){
      case 'listMap' :
        listMap();
      break;
      case 'home' :
        homeMap();
      break;
      case 'cluster' :
         if(map_change==0){
        clusterMarker();
        } else if(map_change==1){
        clusterMarker_osm();
        }
      break;
      case 'single' :
        if(map_change==0){
          singleMarker();
        } else if(map_change==1){
          singleMarker_osm();
        }
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
        if(map_change==0){
           changeMarker();
           markerChange('markerChange');
        } else if(map_change==1){
          //console.log('marker change...');
          changeMarker_osm();
          markerChange_osm('markerChange');
        }
      break;
   /* case 'tollYes':
        tollMarkers();
        break;
        case 'tollNo':
        removeToll();
      break; */    
      case 'undefined':
        $scope.makerType =  undefined;
          if(map_change==0){
           changeMarker();
           markerChange(undefined);
        } else if(map_change==1){
           changeMarker_osm();
           markerChange_osm(undefined);
        }
      break; 
      default:
      break;
     }
    }
  }

  //view map
  $scope.mapView  = function(value)
  {
    switch(value){
      case 'listMap' :
        listMap();
      break;
      case 'home' :
        homeMap();
      break;
      case 'cluster' :
        if(map_change==0){
        clusterMarker();
        } else if(map_change==1){
        clusterMarker_osm();
        }
      break;
      case 'single' :
        if(map_change==0){
          singleMarker();
        } else if(map_change==1){
          singleMarker_osm();
        }
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
          if(map_change==0){
           changeMarker();
           markerChange('markerChange');
        } else if(map_change==1){

          // console.log('marker change...');
           changeMarker_osm();
           markerChange_osm('markerChange');
        }
      break;
    /*case 'tollYes':
        tollMarkers();
        break;
        case 'tollNo':
        removeToll();
      break; */    
      case 'undefined':
         $scope.makerType =  undefined;
          if(map_change==0){
           changeMarker();
           markerChange(undefined);
          } else if(map_change==1){
           changeMarker_osm();
           markerChange_osm(undefined);
        }
      break;
      default:
      break;
    }
  }


$scope.changeMap=function(map_no) {

//console.log('map changes...');

$scope.maps_name = map_no;



if($scope.maps_name==0){

   sessionStorage.setItem('mapNo',0);

  $('#graphsId').hide();
  $('#viewable,#rightAddress2').hide();

  $scope.inits=0; 

  if($scope.gselect){
             
      $scope.gIndex=0;
      $scope.newGroupSelectionName="";
      $scope.oldGroupSelectionName="";
      $scope.initValsss=0;
    }

    $scope.map =  null;

  document.getElementById("map_osm").style.display="none"; 
    document.getElementById("maploc").style.display="block"; 

   map_change =  0;
   $scope.initilize('maploc');
   map_changeOsm = 0;

  } else if($scope.maps_name==1) {


    sessionStorage.setItem('mapNo',1);

    $scope.setMapOsm=1;
 

      $('#graphsId').hide();
      $('#viewable,#rightAddress2').hide();

      if($scope.gselect){
             
        $scope.gIndex=0;
        $scope.newGroupSelectionName = "";
        $scope.oldGroupSelectionName = "";
        $scope.initValsss=0;
      }

      map_change = 1;

      if($scope.map_osm!=null){
        $scope.map_osm.remove();
      }

      document.getElementById("maploc").style.display="none"; 
        document.getElementById("map_osm").style.display="block"; 

      $scope.initilize_osm('map_osm');
  }

}

  //mouse over in list view table
  $scope.mouseJump  = function(user)
  {
    for(var i = 0; i <gmarkers.length; i++)
    {
        if(gmarkers[i].labelContent == user.shortName)
        gmarkers[i].setAnimation(google.maps.Animation.BOUNCE);
        gmarkers[i].setAnimation(null);
        
     }
  }
  
  // table td click 
  $scope.tabletd = function(val)
  {
    for (var i = 0; i <gmarkers.length; i++) 
    {
      if(gmarkers[i].labelContent == val.shortName)
      {        
              
          if($scope.selects==1){
             $('#graphsId').toggle(300); 
          }
        $scope.selects=0; 
        $scope.selected02=i;
     // $scope.vehiRowId=$scope.selected02;
        $scope.tabletds=1;

        var individualVehicle02 = $filter('filter')($scope.locations, { shortName:  gmarkers[i].labelContent});
      //console.log(individualVehicle02[0]);
        $scope.selected = individualVehicle02[0].rowId;

        $scope.map.setZoom(19);
        $scope.map.setCenter(gmarkers[i].getPosition());
        gmarkers[i].setAnimation(google.maps.Animation.BOUNCE);
        gmarkers[i].setAnimation(null);

      }
    }
  }

  $scope.infowindowshowFunc = function(){
    for(var j=0; j<ginfowindow.length;j++){
      ginfowindow[j].close();
    }
    var tempoTime = vamoservice.statusTime($scope.infowindowShow.dataTempVal);
    var contentString = '<div style="padding:10px; width:200px; height:auto;">'
    +'<div><b>Vehicle ID</b> - '+$scope.infowindowShow.dataTempVal.vehicleId+'('+$scope.infowindowShow.dataTempVal.shortName+')</div>'
    +'<div><b>Speed</b> - '+$scope.infowindowShow.dataTempVal.speed+'</div>'
    +'<div><b>odoDistance</b> - '+$scope.infowindowShow.dataTempVal.odoDistance+'</div>'
    +'<div><b>Distance Covered</b> - '+$scope.infowindowShow.dataTempVal.distanceCovered+'</div>'
    +'<div><b>'+tempoTime.tempcaption+' Time</b> - '+tempoTime.temptime+'</div><br>'
    +'<div><a href="../public/track?vehicleId='+$scope.infowindowShow.dataTempVal.vehicleId+'" target="_blank">Track</a> &nbsp;&nbsp; <a href="../public/replay?vehicleId='+$scope.infowindowShow.dataTempVal.vehicleId+'" target="_self">History</a></div>'
    +'</div>';
    $scope.infowindowShow.currinfo.setContent(contentString);
    $scope.infowindowShow.currinfo.open($scope.map,$scope.infowindowShow.currmarker);
  }
  
  $scope.assignHeaderVal = function(data){
    $scope.distanceCovered =data[$scope.gIndex].distance;
    $scope.alertstrack = data[$scope.gIndex].alerts;
    $scope.totalVehicles  =data[$scope.gIndex].totalVehicles;
    $scope.attention  =data[$scope.gIndex].attention;
    $scope.vehicleOnline =data[$scope.gIndex].online;
  }
//  $(window).load(function() {
//    $('#status').fadeOut(); 
//    $('#preloader').delay(350).fadeOut('slow');
//    $('body').delay(350).css({'overflow':'visible'});
// });

$scope.starSplit  = function(val){

 var splitVal;  

  if(val!=undefined){
  splitVal=val.split('<br>');
    }else{
    splitVal='No Address';
    }

 return splitVal;  
}

 $("#safeParkShow").hide();
 $("#safEdits").show();
 $("#safeUps").hide();

 $("#safEdit").click(function(e){

      console.log('safe edits...');

       $('#safEdits').hide();
       $('#safeUps').show();
  });

 $("#safeUp").click(function(e){
       $('#safEdits').show();
      $('#safeUps').hide();
    });

}])

.directive('ngEnter', function () {
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
}).directive('map', function($http, vamoservice) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs){

        sessionStorage.setItem('mapNo',0);

        scope.trafficLayer = new google.maps.TrafficLayer();
        scope.checkVal     = false;
      
        scope.trafficEnable = function(){
          
          if(scope.checkVal==false){
            scope.trafficLayer.setMap(scope.map);
            scope.checkVal = true;
          } else {
            scope.trafficLayer.setMap(null);
            scope.checkVal = false;
          }
        }  
        
        scope.$watch("url", function (val) {

          //var interValRef=window.localStorage.getItem('refreshTime');

          setintrvl = setInterval(function(){
            scope.inits = 1;
            vamoservice.getDataCall(scope.url).then(function(data) {
              if(data.length){
                 scope.selected     =  undefined;
             // if(scope.zohoReports == undefined){
                 scope.locations02  =  data;
                 scope.apiKeys      =  scope.locations02[scope.gIndex].apiKey;
                 scope.locations04  =  scope.vehiSidebar(data);
                 scope.locations    =  scope.statusFilter(scope.locations02[scope.gIndex].vehicleLocations, scope.vehicleStatus);
                 scope.locations03  =  scope.filterExpire(scope.locations);
                 scope.mapTable     =  scope.filterExpire(scope.locations); 
             // }
                 scope.zoomLevel    =  scope.zoomLevel;
                 scope.loading      =  true;
                 scope.init();
                 scope.initial02();
              }
            }); 
       //   },interValRef);
          },60000);
        }); 
      }
    };
});

app.directive('maposm', function($http, vamoservice){
    return {
      restrict: 'E',
      replace: true,
      template: '<div></div>',
      link: function(scope, element, attrs){
      }
  };
});

// $(window).load(function() {
//    $('#status').fadeOut(); 
//    $('#preloader').delay(350).fadeOut('slow');
//    $('body').delay(350).css({'overflow':'visible'});
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
// app.directive('tooltipLoader', function() {
//         return function(scope, element, attrs) {

//          element.tooltip({
//          trigger:"hover",
//          placement: "top",
//          html: true,
//          animated : 'fade',
//          container: 'body',
//      });
//     };
// });

 function googleTranslateElementInit() 
    {
         new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
    }

    $(document).ready(function(){
        $('#minmax').click(function(){
            $('#contentmin').animate({
                height: 'toggle'
            },300);
        });
    });

    

    $("#editable").hide();
    $("#viewable").show();

    $(document).ready(function(){

         $("#notifyS").hide();
          $("#notifyF").hide();
      
        $('#draggable').hide(0);  
        $('#minmaxMarker').click(function(){
           
              $('#draggable').animate({
                height: 'toggle'
            },500);
        });
    

    });
    
    $("#inputEdit").click(function(e){
        $("#editable").show();
        $("#viewable").hide();
    })
    
    
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    $(document).ready(function(){
        if(navigator.appVersion.indexOf("MSIE")!=-1){
            document.body.style.zoom="90%";
        }
    });