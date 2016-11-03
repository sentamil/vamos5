app.controller('mainCtrl',['$scope','vamoservice','$timeout',function($scope, vamoservice,$timeout){
	
$("#testLoad").load("../public/menu");

//global variable
$scope.vehicles         = [];
$scope.checkingValue    = {};
$scope.vehiId           = [];

$scope.hours            = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24];
$scope.reports          = ['Movement (M)','OverSpeed (O)','Site (S)', 'POI (PI)']; //,'Fuel (F)','Temperature (T)'
var url                 = 'http://'+globalIP+context+'/public//getVehicleLocations';
var menuValue           = JSON.parse(sessionStorage.getItem('userIdName'));
var sp                  = menuValue.split(",");


// $scope.checkingValue.move[] = [];
// $scope.checkingValue.over[] = [];

$scope.checkingValue.move = [];
$scope.checkingValue.over = [];
$scope.checkingValue.site = [];
$scope.checkingValue.poi = [];
$scope.checkingValue.temp = [];


// onload function for value already present
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
                  $scope.checkingValue.poi[key]   = false;
                  // $scope.checkingValue.temp[key]   = false;
                  angular.forEach(response, function(innerValue, innerKey){
                    if(value.vehicleId == innerValue.vehicleId){
                      $scope.vehiId[key] = true;
                      console.log(innerValue.reports)
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
(function (){
  startLoading();

  vamoservice.getDataCall(url).then(function(data){
    
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
}());

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
  if($scope.vehiId.length && $scope.mailId && $scope.from || $scope.from==0 && $scope.to){
    var reportsList = [];
    angular.forEach($scope.vehiId,function(val,id){
      
      var reports = '';
      
      if($scope.vehiId[id] == true && $scope.from != undefined && $scope.to != undefined && $scope.vehicles[id].vehicleId != null && $scope.vehicles[id].vehicleId != ''){
        try{ reports += $scope.checkingValue.move[id] == true ?  'movement,' : ''; }
        catch (e){}
        try{ reports += $scope.checkingValue.over[id] == true ?  'overspeed,' : ''; } 
        catch (e){}
        try{ reports += $scope.checkingValue.site[id] == true ?  'site,' : ''; } 
        catch (e){}
        try{ reports += $scope.checkingValue.poi[id] == true ?  'poi,' : ''; } 
        catch (e){}
        try{ reports += $scope.checkingValue.temp[id] == true ?  'temperature,' : ''; } 
        catch (e){}
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
    $scope.error = "* Please fill all field"
    var countUp = function() {
      $scope.error = '';
        
    }
    
    $timeout(countUp, 5000);
  }
}






$scope.changeValue =  function(reportName){
  $scope.checkingValue.move = [];
  $scope.checkingValue.over = [];
  $scope.checkingValue.site = [];
  $scope.checkingValue.poi = [];
  
  if(reportName.length)
    angular.forEach(reportName, function(name, id){
      angular.forEach($scope.vehicles, function(key, value){
        switch (name){
          case 'Movement (M)':
            $scope.checkingValue.move[value]  = true;
            break;
          case 'OverSpeed (O)':
            $scope.checkingValue.over[value]  = true;
            break;
          case 'Site (S)':
            $scope.checkingValue.site[value]  = true;
            break;
          case 'POI (PI)':
            $scope.checkingValue.poi[value]  = true;
            break;
          case 'Temperature (T)':
            $scope.checkingValue.temp[value]  = true;
            break;

        }
      })
    })
}

$scope.groupChange  = function(){

  $scope.checkingValue.move = [];
  $scope.checkingValue.over = [];
  $scope.checkingValue.site = [];
  $scope.checkingValue.poi = [];
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
  });
}


// $scope.report   = function(a, b, c, d, e){
// console.log(' in controller '+a+"  "+b+' '+c+' '+d+' '+e)
  
//   var URL_ROOT    = "ScheduledController/";
//     $.post( URL_ROOT+'reportScheduling', {
//         '_token': $('meta[name=csrf-token]').attr('content'),
//         'vehicle': a,
//         'report': e,
//         'fromt': c,
//         'tot': d,
//         'mail':b,
//         'userName':'SBLT',
//         // 'org':org,
//         // 'latLng': latlanList,
//       })
//       .done(function(data) {
//         console.log("Sucess");
//       })
//       .fail(function() {
//         console.log("fail");
//       });
// }


}]);
  