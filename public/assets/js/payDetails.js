app.controller('mainCtrl',['$scope','$http','vamoservice','$filter', '_global', function($scope, $http, vamoservice, $filter, GLOBAL){
  

  //global declaration
  $scope.getZoho=GLOBAL.DOMAIN_NAME+"/getZohoInvoice?";

  //$scope.sort = sortByDate('startTime');

    var tab = getParameterByName('tn');
                
  function getParameterByName(name) {
      name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
      var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
          results = regex.exec(location.search);
      return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  }


  //$scope.locations01 = vamoservice.getDataCall($scope.url);
  $scope.trimColon = function(textVal){
    return textVal.split(":")[0].trim();
  }


  function sessionValue(vid, gname){
    sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
    $("#testLoad").load("../public/menu");
  }
  
  function getTodayDate(date) {
      var date = new Date(date);
      return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
    };

    function convert_to_24h(time_str) {
    //console.log(time_str);
    var str   = time_str.split(' ');
    var stradd  = str[0].concat(":00");
    var strAMPM = stradd.concat(' '+str[1]);
    var time = strAMPM.match(/(\d+):(\d+):(\d+) (\w)/);
      var hours = Number(time[1]);
      var minutes = Number(time[2]);
      var seconds = Number(time[2]);
      var meridian = time[4].toLowerCase();
  
      if (meridian == 'p' && hours < 12) {
        hours = hours + 12;
      }
      else if (meridian == 'a' && hours == 12) {
        hours = hours - 12;
      }     
      var marktimestr =''+hours+':'+minutes+':'+seconds;      
      return marktimestr;
    };

    // millesec to day, hours, min, sec
    $scope.msToTime = function(ms) 
    {
        days = Math.floor(ms / (24 * 60 * 60 * 1000));
      daysms = ms % (24 * 60 * 60 * 1000);
    hours = Math.floor((ms) / (60 * 60 * 1000));
    hoursms = ms % (60 * 60 * 1000);
    minutes = Math.floor((hoursms) / (60 * 1000));
    minutesms = ms % (60 * 1000);
    seconds = Math.floor((minutesms) / 1000);
    // if(days==0)
    //  return hours +" h "+minutes+" m "+seconds+" s ";
    // else
      return hours +":"+minutes+":"+seconds;
  }


  function formatAMPM(date) {
        var date = new Date(date);
      var hours = date.getHours();
      var minutes = date.getMinutes();
      var ampm = hours >= 12 ? 'PM' : 'AM';
      hours = hours % 12;
      hours = hours ? hours : 12; // the hour '0' should be '12'
      minutes = minutes < 10 ? '0'+minutes : minutes;
      var strTime = hours + ':' + minutes + ' ' + ampm;
      return strTime;
  }

/*
function zohoDataCall(data){

     $scope.zohoData=[];
       var zohoDatas=[];

          zohoDatas.push({customerName:data.customerName});
          zohoDatas[0].hist=[];

          angular.forEach(data.hist,function(val, key){
          
            zohoDatas[0].hist.push({customerName:val.customerName,balanceAmount:val.balanceAmount,dueDate:val.dueDate,dueDays:val.dueDays});
          })

          $scope.zohoData=zohoDatas;
          console.log( $scope.zohoData);
}


    function webCall(){

      var zohoUrl = GLOBAL.DOMAIN_NAME+"/getZohoInvoice?refName=Rajeev";
      console.log(zohoUrl);
     
        $http.get(zohoUrl).success(function(data){

          zohoDataCall(data);

        stopLoading();
    }); 
  }
*/

 function getMaxOfArray(numArray) {
  return Math.max.apply(null, numArray);
 }

  function trimDueDays(value){
     var splitValue=value.split(/[ ]+/);
   return  splitValue[2];
   }

  function zohoDayValue(value){
      
     if(value>=7){

       $scope.navReports="../public/reports";
       $scope.navStats="../public/statistics";
       $scope.navSched="../public/settings";
       $scope.navFms="../public/fms";

     }else{

       console.log('less than 7 days');

     }

 }

   function zohoDataCall(data){

     $scope.zohoData =[];
     $scope.zohoDayss=[];
        var zohoDatas=[];

          zohoDatas.push({customerName:data.customerName,balanceAmount:data.balanceAmount,error:data.error});
          zohoDatas[0].hist=[];

          angular.forEach(data.hist,function(val, key){
                $scope.zohoDayss.push(trimDueDays(val.dueDays));
                zohoDatas[0].hist.push({customerName:val.customerName,balanceAmount:val.balanceAmount,dueDate:val.dueDate,dueDays:val.dueDays});
           })

          $scope.zohoData=zohoDatas;
  //        console.log( $scope.zohoData);
        //  console.log(getMaxOfArray($scope.zohoDayss));
          zohoDayValue(getMaxOfArray($scope.zohoDayss))
    }

/* function zohoUrl(){
    //var getZoho=GLOBAL.DOMAIN_NAME+"/getZohoInvoice?refName=rama";
      console.log(getZoho);
        $http.get(getZoho).success(function(data){
        zohoDataCall(data); 
      })
    }*/

   $scope.$watch("getZoho", function (val) {

     vamoservice.getDataCall($scope.getZoho).then(function(data){

        // console.log(data);

         zohoDataCall(data);
  
        stopLoading();
      });
   });


  //get the value from the ui
  function getUiValue(){
    $scope.uiDate.fromdate    = $('#dateFrom').val();
      $scope.uiDate.fromtime    = $('#timeFrom').val();
      $scope.uiDate.todate    = $('#dateTo').val();
      $scope.uiDate.totime    = $('#timeTo').val();
  
  }



  $scope.exportData = function (data) {
    // console.log(data);
    var blob = new Blob([document.getElementById(data).innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };

    $scope.exportDataCSV = function (data) {
    // console.log(data);
    CSV.begin('#'+data).download(data+'.csv').go();
    };

  $('#minus').click(function(){
    $('#menu').toggle(1000);
  })

}]);
