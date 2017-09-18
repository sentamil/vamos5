app.controller('mainCtrl',['$scope','vamoservice','$timeout','_global',function($scope, vamoservice,$timeout, GLOBAL){
  
$("#testLoad").load("../public/menu");

 $scope.schReportShow=false; 
 $scope.reportBanShow=false;
 
 $scope.reportUrl  =  GLOBAL.DOMAIN_NAME+'/getReportsList';

  $scope.$watch("reportUrl", function (val) {
      
    vamoservice.getDataCall($scope.reportUrl).then(function(data){
     var tabShow = data;
        //console.log(tabShow); 
    if(tabShow != "" && tabShow != null)  {     
       // console.log('not Empty getReportList API ...');
         angular.forEach(tabShow,function(val, key){

            var newReportName = Object.getOwnPropertyNames(val).sort();
             
            if(newReportName == 'Scheduled_IndivdiualReportsList') {
              if(val.Scheduled_IndivdiualReportsList[0].SCHEDULED_REPORTS==false) {
                /*   $(window).load(function(){
                        $('#allReport').modal('show');
                    });*/
                    $scope.schReportShow = false;
                    $scope.reportBanShow=true;
                 
                  stopLoading();
                } else {

                      $scope.reportBanShow = false;
                      $scope.schReportShow = true;

                        $scope.url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
                        initFunc();

                  /* var reportSubName = Object.getOwnPropertyNames(val.Scheduled_IndivdiualReportsList[0]).sort();
                          console.log(reportSubName);

                      angular.forEach(reportSubName,function(value, keys) {
                         switch(value) {
                           case 'SCHEDULED_REPORTS':
                            //if(val.Scheduled_IndivdiualReportsList[0].SCHEDULED_REPORTS==false){ $scope.schReportShow=true; }
                              $scope.schReportShow=val.Scheduled_IndivdiualReportsList[0].SCHEDULED_REPORTS;
                              $scope.url_site = GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
                            break;
                          }
                      });*/
                }
            }
      });

} else{
    console.log('Empty getReportList API ...');

     $scope.schReportShow=true; 
     $scope.reportBanShow=false;

    $scope.url  = GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
    initFunc();
  }

 });

});

//global variable
$scope.vehicles         = [];
$scope.checkingValue    = {};
$scope.vehiId           = [];

$scope.hoursFrom        = ['0:00','1:00','2:00','3:00','4:00','5:00','6:00','7:00','8:00','9:00','10:00', '11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00']
$scope.hoursTo          = ['0:59','1:59','2:59','3:59','4:59','5:59','6:59','7:59','8:59','9:59','10:59','11:59','12:59','13:59','14:59','15:59','16:59','17:59','18:59','19:59','20:59','21:59','22:59','23:59'];
//$scope.reports        = ['Movement (M)','OverSpeed (O)','Site (S)', 'POI (PI)','Fuel (F)','Temperature (T)'];

$scope.getReportNames   = function(){
 
     $.ajax({
      async: false,
      method: 'GET', 
      url: "ScheduledController/getRepName",
    //data: value,
      success: function (response) {

        console.log(response);
        $scope.repNames = [];
        $scope.repNames = response;
      //location.reload();
      }
    });

return $scope.repNames;  
}

$scope.reports          = $scope.getReportNames();

//var url                 = GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
var menuValue           = JSON.parse(sessionStorage.getItem('userIdName'));
var sp                  = menuValue.split(",");

//$scope.checkingValue.move[] = [];
//$scope.checkingValue.over[] = [];

  $scope.checkingValue.move = [];
  $scope.checkingValue.over = [];
  $scope.checkingValue.site = [];
  $scope.checkingValue.poi  = [];
  $scope.checkingValue.fuel = [];
//$scope.checkingValue.temp = [];

//onload function for value already present
function fetchController(group){
$.ajax({
          async: false,
          method: 'GET', 
          url: "ScheduledController/getValue",
          data: {'userName':sp[1], 'groupName':group},
          success: function (response) {
              if(response.length){
                $scope.mailId   = response[0].email;
                $scope.from     = response[0].fromTime;
                $scope.to       = response[0].toTime;
                $scope.groupSelected    = response[0].groupName;
                angular.forEach($scope.vehicles, function(value, key){
                  $scope.vehiId[key] = false;
                  $scope.checkingValue.move[key]   = false;
                  $scope.checkingValue.over[key]   = false;
                  $scope.checkingValue.site[key]   = false;
                  $scope.checkingValue.poi[key]    = false;
                  $scope.checkingValue.fuel[key]   = false;
                  // $scope.checkingValue.temp[key]   = false;
                  angular.forEach(response, function(innerValue, innerKey){
                    if(value.vehicleId == innerValue.vehicleId){
                      $scope.vehiId[key] = true;
                      // console.log(innerValue.reports)
                      var reportsValue  =  innerValue.reports.split(',');
                      try{

                        angular.forEach(reportsValue, function(rep, ind){

                          switch (rep){
                            case 'movement':
                              $scope.checkingValue.move[key]   = true;
                              break;
                            case 'overspeed':
                              $scope.checkingValue.over[key]   = true;
                              break;
                            case 'site':
                              $scope.checkingValue.site[key]   = true;
                              break;
                            case 'poi':
                              $scope.checkingValue.poi[key]    = true;
                              break;
                            case 'fuel':
                              $scope.checkingValue.fuel[key]   = true;
                              break;
                          }

                        });

                      }catch(err){

                        console.log(err)

                      }
                      
                    }
                });

              });
            }
            
          }
        });
}

//add vehicle in single list
function addVehi(vehi){
  $scope.vehicles = [];
  for (var i = 0; i < vehi.length; i++) 
  {$scope.vehicles.push(vehi[i])}
  
}


//init function
/*(function (){*/

function initFunc()
{

  startLoading();

  vamoservice.getDataCall($scope.url).then(function(data){
    
    addVehi(data[0].vehicleLocations);
    $scope.locations02 = data;
    // $scope.groupSelected.group  = data[0];
    if(data.length){
      fetchController(data[0].group);
      // try {
      //   for (var i = 0; i <= data.length-1; i++) {

      //     angular.forEach(data, function(value, key){
      //       if(key!=0)
      //       $http.get(url+'?group='+value.group).success(function(res){
      //         addVehi(res[key].vehicleLocations);
      //         if(key == data.length-1)
      //         //     console.log('err');
      //          fetchController();
      //       });
      //     })
      //     if(i!=0)
            
      //     if()
      //   }
      //    throw "is empty";
      // } catch (err) {
      //   // console.log(err);
      // }

    } else {
      // fetchController();
    }
    
  });

  stopLoading();
//}());
}

$scope.trimColon = function(textVal){
  return textVal.split(":")[0].trim();
}

$scope.vehiSelect   = function(vehid){
  $scope.vehiId  = [];
  if(vehid == true)
  angular.forEach($scope.vehicles, function(data, id){
    
    $scope.vehiId[id] = true;
  })
}

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

function checkUndefined(value){
  try{
    if(value)
      return true
  }
  catch (e){ 
    return false
  }
}


$scope.storeValue   = function(){

  startLoading();
  if((checkXssProtection($scope.mailId) == true))
    if($scope.vehiId.length && $scope.mailId  && $scope.mailId != '' && $scope.from  && $scope.to){
      var reportsList = [];
      angular.forEach($scope.vehiId,function(val,id){
        
        var reports = '';
        
        if($scope.vehiId[id] == true && $scope.from != undefined && $scope.to != undefined && $scope.vehicles[id].vehicleId != null && $scope.vehicles[id].vehicleId != ''){
          try{ reports += $scope.checkingValue.move[id] == true ?  'movement,' : ''; }catch (e){}
          try{ reports += $scope.checkingValue.over[id] == true ?  'overspeed,' : ''; }catch (e){}
          try{ reports += $scope.checkingValue.site[id] == true ?  'site,' : ''; }catch (e){}
          try{ reports += $scope.checkingValue.poi[id] == true ?  'poi,' : ''; }catch (e){}
          try{ reports += $scope.checkingValue.fuel[id] == true ?  'fuel,' : ''; }catch (e){}
          // try{ reports += $scope.checkingValue.temp[id] == true ?  'temperature,' : ''; } 
          // catch (e){}
          reportsList.push([sp[1],reports, $scope.vehicles[id].vehicleId, $scope.from, $scope.to, $scope.mailId, $scope.groupSelected]);
          
        }
       
      })

    $.ajax({
            async: false,
            method: 'POST', 
            url: "ScheduledController/reportScheduling",
            data: {'reportList': reportsList,'userName':sp[1],'groupName': $scope.groupSelected},//{'userName':sp[1],'vehicle': $scope.vehicles[id].vehicleId,'report': reportsList,'fromt': $scope.from,'tot': $scope.to,'mail':$scope.mailId,'groupName': $scope.groupSelected.group}, // a JSON object to send back
            success: function (response) {
              stopLoading();
              location.reload();
            }
          });
  }
    else{
      stopLoading();
      $scope.error = "* Please fill all field & Enter valid email id"
      var countUp = function() {
        $scope.error = '';
          
      }
      
      $timeout(countUp, 5000);
    }
  else{
    stopLoading();
  }
}


function forNull(){
  $scope.selectId           = false;
  $scope.checkingValue.move = [];
  $scope.checkingValue.over = [];
  $scope.checkingValue.site = [];
  $scope.checkingValue.poi  = [];
  $scope.checkingValue.fuel = [];
}



$scope.changeValue =  function(reportName){
  
  forNull();
  if(reportName.length)
    angular.forEach(reportName, function(name, id){
      angular.forEach($scope.vehicles, function(key, value){
        switch (name){
          case 'Movement(M)':
            $scope.checkingValue.move[value]  = true;
            break;
          case 'Overspeed(O)':
            $scope.checkingValue.over[value]  = true;
            break;
          case 'Site(S)':
            $scope.checkingValue.site[value]  = true;
            break;
          case 'POI(P)':
            $scope.checkingValue.poi[value]  = true;
            break;
          case 'Fuel(F)':
            $scope.checkingValue.fuel[value]  = true;
            break;  
       /* case 'Temperature(T)':
            $scope.checkingValue.temp[value]  = true;
            break;*/
        }
      })
    })
}

$scope.groupChange  = function(){
  startLoading();
  forNull();
  // $scope.checkingValue.temp = [];
  $scope.vehiId             = [];
  $scope.from               = '';
  $scope.to                 = '';
  vamoservice.getDataCall(url+'?group='+$scope.groupSelected).then(function(response){
    angular.forEach(response, function(value, key){
      ($scope.groupSelected == value.group) ? addVehi(response[key].vehicleLocations) : console.log(' No groupName ');
    })
    // addVehi(res[key].vehicleLocations);
    fetchController($scope.groupSelected);
    // fetchController($scope.groupSelected.group);
    stopLoading();
  });
}

/*
  FOR DELETING THE SCHEDULE REPORT 
*/
$scope.deleteFn   = function(_data, index){
  startLoading();
  $scope.error    = '';
  var value       = {};
  value.userName  = '';
  value.groupName = '';
  value.vehiId    = '';
  value.userName  = sp[1];
  value.groupName = $scope.groupSelected;
  value.vehiId    = (!index == undefined || index >= 0) ? _data.vehicleId : null;
  
  if(value.userName != undefined && value.groupName != undefined)
    $.ajax({
      async: false,
      method: 'GET', 
      url: "ScheduledController/reportDelete",
      data: value,
      success: function (response) {
        $scope.error = (response == 'correct') ? 'Successfully Updated' : 'Not Updated Successfully';
        stopLoading();
        location.reload();
      }
    });
  else
    $scope.error = '*Enter all fields';
}


}]);
