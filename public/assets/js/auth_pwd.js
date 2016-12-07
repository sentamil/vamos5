app.controller('mainCtrl',function($scope, $location, vamoservice){
	
var url             = $location.absUrl();
$scope._tabValue    = url.includes("groupEdit");
var _gUrl           = 'http://'+globalIP+context+'/public/getVehicleLocations';

$scope.sort         = {sortingOrder : 'vehicles', reverse : true };

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

// $scope.check_box = false;
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
      stopLoading();
    });
  }  

($scope._tabValue == true)?initial():'';


  $scope.trimColon = function(textVal){
    if(textVal)
    return textVal.split(":")[0].trim();
  }


  // checking auth in the addsite and rfid 

  $scope.passwordConfirm  = function(){

    startLoading();
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

});
