app.controller('mainCtrl',['$scope','vamoservice',function($scope, vamoservice){
	
$("#testLoad").load("../public/menu");

//global variable
$scope.vehicles         = [];
$scope.checkingValue    = {};
$scope.vehiId           = [];
$scope.hours            = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24];
$scope.reports          = ['Movement (M)','OverSpeed (O)','Site (S)','Fuel (F)','Temperature (T)'];
var url                 = 'http://'+globalIP+context+'/public//getVehicleLocations';


//add vehicle in single list
function addVehi(vehi){
  for (var i = 0; i < vehi.length; i++) 
  {$scope.vehicles.push(vehi[i])}
}


//init function
(function (){
  startLoading();
  vamoservice.getDataCall(url).then(function(data){
    
    addVehi(data[0].vehicleLocations);
    $scope.locations02 = data;
    for (var i = 0; i <= data.length-1; i++) {
      if(i!=0)
      vamoservice.getDataCall(url+'?group='+data[i].group).then(function(res){
        addVehi(res[i-1].vehicleLocations);
      });
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
  if($scope.vehiId.length && $scope.mailId)
    angular.forEach($scope.vehiId,function(val,id){
      
      var reports = '';
      
      if($scope.from != undefined && $scope.to != undefined && $scope.vehicles[id].vehicleId != null && $scope.vehicles[id].vehicleId != ''){
        try{ reports += $scope.checkingValue.move[id] == true ?  'movement,' : ''; }
        catch (e){}
        try{ reports += $scope.checkingValue.over[id] == true ?  'overspeed,' : ''; } 
        catch (e){}
        try{ reports += $scope.checkingValue.site[id] == true ?  'site,' : ''; } 
        catch (e){}
        try{ reports += $scope.checkingValue.fuel[id] == true ?  'fuel,' : ''; } 
        catch (e){}
        try{ reports += $scope.checkingValue.temp[id] == true ?  'temperature,' : ''; } 
        catch (e){}
          
        var menuValue = JSON.parse(sessionStorage.getItem('userIdName'));
        var sp  = menuValue.split(",");


        $.ajax({
          async: false,
          method: 'POST', 
          url: "ScheduledController/reportScheduling",
          data: {'userName':sp[1],'vehicle': $scope.vehicles[id].vehicleId,'report': reports,'fromt': $scope.from,'tot': $scope.to,'mail':$scope.mailId}, // a JSON object to send back
          success: function (response) {
            stopLoading();
            location.reload();
          }
        });

      }
      stopLoading();
    })
  
}






$scope.changeValue =  function(reportName){

  $scope.checkingValue.move = [];
  $scope.checkingValue.over = [];
  $scope.checkingValue.site = [];
  $scope.checkingValue.fuel = [];
  $scope.checkingValue.temp = [];
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
          case 'Fuel (F)':
            $scope.checkingValue.fuel[value]  = true;
            break;
          case 'Temperature (T)':
            $scope.checkingValue.temp[value]  = true;
            break;

        }
      })
    })
}

$scope.report   = function(a, b, c, d, e){
console.log(' in controller '+a+"  "+b+' '+c+' '+d+' '+e)
  
  var URL_ROOT    = "ScheduledController/";
    $.post( URL_ROOT+'reportScheduling', {
        '_token': $('meta[name=csrf-token]').attr('content'),
        'vehicle': a,
        'report': e,
        'fromt': c,
        'tot': d,
        'mail':b,
        'userName':'SBLT',
        // 'org':org,
        // 'latLng': latlanList,
      })
      .done(function(data) {
        console.log("Sucess");
      })
      .fail(function() {
        console.log("fail");
      });
}


}]);
  