app.controller('mainCtrl',['$scope', '$location', 'vamoservice','_global', '$http', function($scope, $location, vamoservice, GLOBAL, $http){
	
var url             = $location.absUrl();
$scope._tabValue    = url.includes("groupEdit");
var _gUrl           = GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
var _addUrl         = GLOBAL.DOMAIN_NAME+'/addMobileNumberSubscription';
var _showUrl        = GLOBAL.DOMAIN_NAME+'/getSpecificRouteDetails';
var _deleteUrl      = GLOBAL.DOMAIN_NAME+'/stopSmsSubscription';
var _searchUrl      = GLOBAL.DOMAIN_NAME+'/getStudentDetailsOfSpecifyNum';
var _siteUrl        = GLOBAL.DOMAIN_NAME+'/viewSite';
$scope.switchingVar = false;
// $scope.switchEdit   = false;
$scope.caption      = "MobileNo Search";
$scope.sort         = {sortingOrder : 'vehicles', reverse : true };
$scope.notifyUpdate = [];
$scope.rowsValue    = [];
// $scope.selectEdit   = (getParameterByName('userlevel') == 'reset') ? true : (getParameterByName('userlevel') == 'notify') : false;

$scope.checkValue = function(value){
  if(value == 'true'){
    return true;
  }
  else  if(value == 'false')
    return false;
}


  $scope.selectStopEdit = false;
  // $scope.selectEdit     = false;
if(getParameterByName('userlevel') == 'reset')
  $scope.selectEdit = true;
else if(getParameterByName('userlevel') == 'notify'){
  startLoading();
  $scope.selectEdit = false;
  $scope.notify   = [{'check': true, 'value' : 'lowbat', 'caption':'LOW BATTERY'}];
  
  $.ajax({
      
      async: false,
        method: 'GET', 
        url: 'notificationFrontend',
        // data: {"orgId":},
        success: function (response) {
          if (response != 'fail')
            $scope.notificationValue = response;
            angular.forEach($scope.notificationValue, function(ke, val){
              $scope.notifyUpdate.push($scope.checkValue(ke));
            })
        }
    })

  startLoading();
} else if(getParameterByName('userlevel') == 'busStop'){

  $scope.selectStopEdit = true;
}


/*
  remove group from this user 
*/

  $scope.removeGroup = function(gId){

    startLoading();
    $scope.check_box = false;
    $scope.error ="";
    $scope.grpName = gId;
    var list = [];
    angular.forEach($scope.groupList, function(album){

      if (album.groupName !== '') list.push({'groupName': album.groupName}); 

    });
    if(gId !== '')
      $.ajax({
        async: false,
        method: 'POST', 
        url: "VdmGroup/removingGroup",
        data: {'grpName': gId},//{'userName':sp[1],'vehicle': $scope.vehicles[id].vehicleId,'report': reportsList,'fromt': $scope.from,'tot': $scope.to,'mail':$scope.mailId,'groupName': $scope.groupSelected.group}, // a JSON object to send back
        success: function (response) {
          if(response =='sucess'){

          
          location.reload();
          }
        }
      });

    $scope.groupList =[];
    $scope.groupList =list;

    console.log($scope.groupList)
    stopLoading();
  }
         
  /*
    Edit group for user
  */

  $scope.editGroup  = function(gId){

    startLoading();
    $scope.check_box = false;
    $scope.error ="";
    $scope.grpName = gId;
    console.log(' edit group '+gId);
    if(gId !== undefined)
      $.ajax({
        async: false,
        method: 'POST', 
        url: "VdmGroup/showGroup",
        data: {'grpName': gId},
        success: function (response) {
         
          // location.reload();
          // console.log(response)
          if(typeof response !== "string"){
            console.log(typeof response)
            $scope.vehiList =[];
            $scope.vehiList   = response;
          }
        }
      });
    stopLoading();
  }


  /*
    Add group
  */

  $scope.addGroup   = function(){
    $scope.error = "* Add only one group at a time";
    var count = 1;    
    $scope.groupList.push({'groupName':''})
    var listGroup = $scope.groupList;
    $scope.groupList = [];
    angular.forEach(listGroup, function(val, ke){
      if(val.groupName == '' && count == 1){
        $scope.groupList.push({'groupName':''})
        count ++;
        return;
      } else if(val.groupName !== '') $scope.groupList.push({'groupName':val.groupName})
      
    })
    $scope.editGroup('');
  }

  /*
    Add group in db
  */
  $scope.changevalue = function(gN, ind)
  {
    $scope.error = '';
    $scope.grpName = gN;
    // console.log($addNewGroup)
    
    $.ajax({
        async: false,
        method: 'POST', 
        url: "VdmGroup/groupId",
        data: {'id': gN},
        success: function (response) {
         if(response=='fail')
          $scope.error = 'No groups available ,Please select another user';
            
          // location.reload();
          console.log(response)
          
        }
      });
  }

  /*
    updateGroup for user
  */

  $scope.updateGroup  = function(g_Name){

    console.log($scope.groupList)
    $scope.check_box = false;
    var newValu = '';
    if(g_Name === '')
    angular.forEach($scope.groupList, function(va, ke){
      if(va.newGp !== ''){
        g_Name = va.newGp;
        newValu = 'newValue'
      }
    })
    startLoading();
    $scope.error ="";
    $scope.grpName = g_Name;
    var list  = [];
    angular.forEach($scope.vehiList, function(selected_Vehi){

      (selected_Vehi.check == true)? list.push(selected_Vehi.vehicles) : '';
    
    });

    console.log("list")
    if(list.length >0){
      $.ajax({
        async: false,
        method: 'POST', 
        url: "VdmGroup/saveGroup",
        data: {'grplist': list, 'grpName': g_Name, 'newValu': newValu},
        success: function (response) {
          if(response == 'sucess') location.reload()
          else $scope.error = "* Group may not save Sucessfully"
        }
      });
    } else {
        console.log('no vehicle')
        $scope.error ="* Must need one vehicle in a group, otherwise delete the group"
    }
    stopLoading();
    
  }

/*
  for get orgId
*/
function getOrgValue(val){
  var orgId   = '';
  angular.forEach($scope.routeNameList, function(org, k){
    angular.forEach(org, function(stop, key){
      if(val == stop){
        orgId = k
        return;
      }
    })
  });
  return orgId;
}

/*
  get bus stops 
*/
function _getBustopValue(id){
  var _returnValue;
  angular.forEach($scope.stopList, function(value, key){
    if(value.poiId == id){
      _returnValue = value;
      return;
    }
  });
  if(_returnValue == undefined)
    _returnValue = $scope.stopList[$scope.stopList.length-1];
  return _returnValue;
}


/*
  check null values
*/

function _assignValue (obj, status){
// return
  switch (status){

    case 'name':
      return (obj.sNameNew == undefined) ? obj.sName : obj.sNameNew;
      break;
    case 'num':
      return (obj.mNumNew == undefined) ? obj.mNum : obj.mNumNew;
      break;
    case 'std':
      return (obj.stdNew == undefined) ? obj.std : obj.stdNew;
      break;
    case 'stId':
      return (obj.poiId == undefined) ? obj.stopId : obj.poiId.poiId;
    case 'id':
      return (obj.id == undefined) ? 0 : obj.id;
      break;

  }
}


$scope.submitValue  = function(){

  console.log('submitValue ...');

  startLoading();
  $scope.error = '';
  $scope.rowsCount = 0;
  var statusValue = true;
  
  /*
    validation
  */
  var _url = '';
  
  for (var i = 0; i < $scope.rowsValue.length; i++) {

    // if(validCharCheck(_assignValue($scope.rowsValue[i], 'name')) == false){
    //   statusValue = false, $scope.error = '* Enter Student Name, without special characters in '+( i+1 )+' th row .';
    //   stopLoading();
    //   return;
    // }
    if(checkXssProtection(_assignValue($scope.rowsValue[i], 'name')) && checkXssProtection(_assignValue($scope.rowsValue[i], 'num')) && checkXssProtection(_assignValue($scope.rowsValue[i], 'std')))
    {
      if(mobNumCheckTenDigit(_assignValue($scope.rowsValue[i], 'num')) == false){
        statusValue = false, $scope.error = '* Enter Mobile No, as 10 or 12 digit in '+( i+1 )+' th row.';
        stopLoading();
        return;
      }
    } 
    else {
      statusValue = false, $scope.error = '* Not Valid Data in '+( i+1 )+' th row.';
      stopLoading();
      return;
    }
    // if(removeColonStar(_assignValue($scope.rowsValue[i], 'std')) == false){
    //   statusValue = false, $scope.error = '* Enter Standard, dnt use special characters '+( i+1 )+' th row.';
    //   stopLoading();
    //   return;
    // }


    _url +=$scope.selectRouteName+':'+_assignValue($scope.rowsValue[i], 'name')+':'+_assignValue($scope.rowsValue[i], 'num')+':'+_assignValue($scope.rowsValue[i], 'std')+':'+_assignValue($scope.rowsValue[i], 'stId')+':'+_assignValue($scope.rowsValue[i], 'id')+'*';
   
  }
  if(statusValue){

   console.log('inserted...');

    $.ajax({
      async: false,
      method: 'POST', 
      url: _addUrl,
      data: {
        'orgId' : getOrgValue($scope.selectRouteName),
        'studentDetails' : _url,
      },
      success: function (response) {
        console.log(response)
        // if(response.trim() == 'Success'){
          $scope.toast = response;
          toastMsg()
        // }
      }
    });
    $scope._addDetails();
  }
  stopLoading();
}



function _editGlobal(ind){

  $scope.rowsValue[ind].sNameNew  = ($scope.rowsValue[ind].sName) ? $scope.rowsValue[ind].sName : '';
  $scope.rowsValue[ind].sName     = ' ';
  $scope.rowsValue[ind].mNumNew   = ($scope.rowsValue[ind].mNum) ? $scope.rowsValue[ind].mNum : '';
  $scope.rowsValue[ind].mNum      = ' ';
  $scope.rowsValue[ind].stdNew    = ($scope.rowsValue[ind].std) ? $scope.rowsValue[ind].std : '';
  $scope.rowsValue[ind].std       = ' ';
  $scope.rowsValue[ind].poiId     = (_getBustopValue($scope.rowsValue[ind].stopId)) ? _getBustopValue($scope.rowsValue[ind].stopId) : '';
  $scope.rowsValue[ind].poiIds    = '';

}


/*
  Switching function
*/
$scope.switching  = function(){

  if($scope.switchingVar == false)
    $scope.switchingVar = true,$scope.caption      = "Back";
  else
    $scope.switchingVar = false, $scope.caption      = "MobileNo Search";

}



/*
    searching mobile number function
  
*/
$scope.searchingMobile  = function(mobile){
  $scope.error      = '';
  var _addMobileNo  = '', statusValue = true, _serviceUrl = '', checkNull = '';
  mobile  = splitComma(mobile);
  for (var i = 0; i < mobile.length; i++) {
    checkNull = removeSpace(mobile[i]);
    if(mobNumCheckTenDigit(checkNull) == false){
      statusValue   = false ,$scope.error = '* Enter 10 or 12 digit mobile number for each.';
      stopLoading();
      return;
    }
    _addMobileNo  += checkNull+',';
  }
  _serviceUrl    = _searchUrl+'?mobileNo='+_addMobileNo;
  if(statusValue)
    vamoservice.getDataCall(_serviceUrl).then(function(value){
      
      // console.log(value)
      $scope.searchValue  = value;

    });
    

}


/*
  edit bus stops

*/
$scope._editStop  = function(ind, status){
  startLoading();
  
  // console.log($scope.rowsValue);
  // console.log($scope.stopList);
  // $scope.switchEdit   = true;

  if(status == 'one'){
    _editGlobal(ind);
  }
  // else if(status == 'all')
  //   for (var i = 0; i < $scope.rowsValue.length; i++) {
  //     _editGlobal(i);
  //   }
  stopLoading();
}


/*
  undo edit
*/

$scope._undoEdit  = function(ind, status){

  
  $scope.rowsValue[ind].sName     = ($scope.rowsValue[ind].sNameNew) ? $scope.rowsValue[ind].sNameNew : '';
  $scope.rowsValue[ind].mNum      = ($scope.rowsValue[ind].mNumNew) ? $scope.rowsValue[ind].mNumNew : '';
  $scope.rowsValue[ind].std       = ($scope.rowsValue[ind].stdNew) ? $scope.rowsValue[ind].stdNew : '';
  // $scope.rowsValue[ind].poiIds    = _getBustopValue($scope.rowsValue[ind].stopId).stop;
  $scope.rowsValue[ind].poiIds    = _getBustopValue($scope.rowsValue[ind].poiId.poiId).stop;
  // $scope.rowsValue[ind].poiIds    = ($scope.rowsValue[ind].stopId == $scope.rowsValue[ind].poiId.poiId) ? _getBustopValue($scope.rowsValue[ind].stopId).stop : ;

}

/*
  delete busstop values
*/

$scope._deleteMobNum    = function(index){

  console.log(index);

  $scope.error = '';
  console.log(' _deleteMobNum ');
  $.ajax({
    async: false,
    method: 'POST', 
    url: _deleteUrl,
    data: {
      'mobNum' : $scope.rowsValue[index].mNum,
      'orgId' : getOrgValue($scope.selectRouteName),
      
    },
    success: function (response) {
      // if(response)
      console.log(response)
     
      
    }
  });
  $scope.rowsValue.splice(index, 1);
  // $scope._addDetails();
}


function arrayObjectIndexOf(myArray, searchTerm, property) {
    for(var i = 0, len = myArray.length; i < len; i++) {
        if (myArray[i][property] === searchTerm) return i;
    }
    return -1;
}
//arrayObjectIndexOf(arr, "stevie", "hello"); // 1

$scope._deleteFilterMobNum    = function(index,mobb){

  var indexNew=arrayObjectIndexOf($scope.rowsValue, mobb, "mNum");

  $scope.searchValue.studentDetails.splice(index, 1);

  $scope.error = '';
  console.log(' _deleteFilterMobNum ');
  $.ajax({
    async: false,
    method: 'POST', 
    url: _deleteUrl,
    data: {
      //'mobNum' : $scope.rowsValue[index].mNum,
      'mobNum':mobb,
      'orgId' : getOrgValue($scope.selectRouteName),
      
    },
    success: function (response) {
      // if(response)
      console.log(response)
     
      
    }
  });
  $scope.rowsValue.splice(indexNew, 1);
  //$scope._addDetails();
}

/*
  * show the stop details   
*/

function showStop() {
  
  var _showStopUrl  = _showUrl+'?routeNo='+$scope.selectRouteName;
  vamoservice.getDataCall(_showStopUrl).then(function(value){

    $scope.rowsValue =[];
    var busStopValue;
    angular.forEach(value.studentDetails, function(val, key){
      busStopValue = _getBustopValue(val.busStop);
      $scope.rowsValue.push({'sName' : trimed(val.name), 'mNum': trimed(val.mobileNumber), 'std' : trimed(val.class), 'poiIds' : busStopValue.stop, 'stopId' : val.busStop, 'id' : val.rowId});

     console.log($scope.rowsValue);
    });
  })



}


/*
  check all vehicle 
*/
$scope.checkAll   = function(){
  $scope.error ="";
  angular.forEach($scope.vehiList, function(val, key){
    val.check=$scope.check_box
  })

}

/*
    add rows
*/

$scope.addRows      = function(counts){

  console.log(' inside the row function '+counts);
  // $scope.rowsValue  = [];
  $scope.rowsCount  = counts;
  for (var i=0; i<counts; i++) {
      $scope.rowsValue.push({'sName' : ' ', 'mNum': ' ', 'std' : ' ', 'poiIds' : ''});  
    }

}

  
$scope._addDetails  = function(){
  startLoading();
  $scope.error      = '';
  $scope.stopList   = [];
  var _geoUrl       = GLOBAL.DOMAIN_NAME+'/getBusStops?routeNo='+$scope.selectRouteName+'&orgId='+getOrgValue($scope.selectRouteName);
  
  vamoservice.getDataCall(_geoUrl).then(function(data) {
    try{
    if((data && data != '') && (data.studentDetails.length > 0)){
      angular.forEach(data.studentDetails, function(value, key){

        if((value && value != '') && (parseInt(value['BusStopId']) >= 0))
          $scope.stopList.push({'stop' :value['BusStopName'], 'poiId' :value['BusStopId']})

      });

    }

    showStop();
    stopLoading()
  }catch (err){stopLoading()}

  });

}


function getRouteName (){

  $scope.vehiStopList   = [];
  var stopList          = [];
  vamoservice.getDataCall(_siteUrl).then(function(value, key){

    if((value && value != '') && (value.orgIds && value.orgIds.length > 0))
      $.ajax({
        async: false,
        method: 'GET', 
        url: 'VdmOrg/getStopName',
        data: {
          'orgId' : value.orgIds,
        },
        success: function (response) {
          $scope.routeNameList  = response;
          angular.forEach(response, function(value, key){
            angular.forEach(value, function(val, key){
                stopList.push(val);
              })
            

          });
          $scope.vehiStopList   = stopList;
          if($scope.vehiStopList.length >0){
            $scope.selectRouteName = $scope.vehiStopList[0];
            $scope._addDetails();
            // showStop();

          }
        }
      });
  })

}



function _addStops() {
  getRouteName();
}


/*
  initial for groupEdit
*/
  function initial(){

    startLoading();
    $scope.error ="";
    vamoservice.getDataCall(_gUrl).then(function(data) {
      $scope.groupList    = [];
      
      angular.forEach(data, function(value, key){
        $scope.groupList.push({'groupName': value.group});
      })
      $scope.selectStopEdit == true ? _addStops() : console.log(' Fuck off !! ');
      stopLoading();
    });
  }  

($scope._tabValue == true || $scope.selectStopEdit == true)?initial():stopLoading();


  $scope.trimColon = function(textVal){
    if(textVal)
    return textVal.split(":")[0].trim();
  }


  // checking auth in the addsite and rfid 

  $scope.passwordConfirm  = function(){

    startLoading();
    if(checkXssProtection($scope.pwd) == true){
      var password  = $scope.pwd;
      var URL_ROOT    = "AddSiteController/";    /* Your website root URL */
      $.post(URL_ROOT+'checkPwd',{
          '_token': $('meta[name=csrf-token]').attr('content'),
          'pwd': password,
      }).done(function(data){
          console.log('Sucess---------->' + data+location.pathname);
          stopLoading();
          if(data == 'incorrect')
            $scope.statusUi   = "Invalid Password"; 
          else if (data == 'correct'){
            var path = context+'/public/track?maps=sites'
            location.href = path;
            sessionStorage.setItem('auth', 'sitesVal');
          }
            
                 
      }).fail(function(){
        stopLoading();
          console.log('fail');
          $scope.statusUi   = "Response Failure";  
      })
    }
    stopLoading();
  }

$scope.error        = '';
    /*
      update password
    */

    $scope.updatePwd  = function(){
      startLoading();

      $scope.error        = '';
      if((checkXssProtection($scope.oldValue) == true) && (checkXssProtection($scope.firstVal) == true) && (checkXssProtection($scope.reEnterVal) == true))
      {
        if($scope.oldValue == '' || $scope.oldValue == undefined && $scope.firstVal == '' || $scope.firstVal == undefined && $scope.reEnterVal == '' || $scope.reEnterVal == undefined){

          $scope.error        = "* Fill all Fields ."; 
          
        } else {

          if($scope.oldValue != $scope.firstVal && $scope.oldValue != $scope.reEnterVal && $scope.firstVal == $scope.reEnterVal && $scope.firstVal.length >= 5){

            $.ajax({
              async: false,
              method: 'POST', 
              url: "updatePwd",
              data: {'pwd': $scope.firstVal, 'old': $scope.oldValue},
              success: function (response) {
                if(response =='sucess'){
                  console.log(response);
                  $scope.error = "Updated Sucessfully ."
                   setTimeout(function () {
                      document.location.href = 'login';
                      
                    }, 1000); 
                  
                } else if(response == 'oldPwd'){
                  $scope.error = "Old password is wrong ."                
                }
              },
              fail:function() {
                
                $scope.error        = '* Connection Fails .'
              }
            });  

          } else {

            $scope.error        = "* Dnt match old password and new password / password length more then five characters .";
              
          }

        }
      }
      stopLoading();
     
    }



  $scope.updateNotify   = function(){
    var count = 0;
    var selectNotiy = [];
    angular.forEach($scope.notificationValue, function(val, key){

        if($scope.notifyUpdate[count] == true)
          selectNotiy.push(key);
          count ++;
    
    })
    var menuValue = JSON.parse(sessionStorage.getItem('userIdName'));
    sp1 = menuValue.split(",");
    $.ajax({
      
      async: false,
        method: 'POST', 
        url: 'notificationFrontendUpdate',
        data: {"userId":sp1[1], "notificationGroups" : selectNotiy},
        success: function (response) {
          if (response == 'success')
            location.reload();
          else if (response == 'fail')
            console.log(' check console Some problem... ');
        }
    })
  }

}]);
