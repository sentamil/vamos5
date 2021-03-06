app.controller('mainCtrl', ['$scope','$http' ,'$filter','vamoservice', '_global', function($scope, $http, $filter, vamoservice, GLOBAL){

  $scope.reportBanShow    =  false;
  $scope.donut_new        =  0;
  $scope.donut            =  1;
  $scope.showMonTable     =  false;
  $scope.showMonFuelTable =  false;

  var getUrl          =  document.location.href;
  var tabId;
//var tabId           =  'executive';
//$scope.donut_new    =  false;
  var index           =  getParameterByName("ind");
//$scope.fromMonthFuel='06/2017';
  $scope.reportUrl  =  GLOBAL.DOMAIN_NAME+'/getReportsList';

  $scope.$watch("reportUrl", function (val) {
      
    $http.get($scope.reportUrl).success(function(data){

     var tabShow = data;
       // console.log(tabShow);
    
      if(tabShow != "" && tabShow != null)  {     

        //console.log('not Empty getReportList API ...');

        angular.forEach(tabShow,function(val, key){

            var newReportName = Object.getOwnPropertyNames(val).sort();
             
            if(newReportName == 'Statistics_IndivdiualReportsList'){


              if(val.Statistics_IndivdiualReportsList[0].EXECUTIVE_FUEL==false&&val.Statistics_IndivdiualReportsList[0].MONTHLY_DIST_AND_FUEL==false&&val.Statistics_IndivdiualReportsList[0].DAILY==false&&val.Statistics_IndivdiualReportsList[0].MONTHLY_DIST==false&&val.Statistics_IndivdiualReportsList[0].POI==false&&val.Statistics_IndivdiualReportsList[0].CONSOLIDATED==false) {

                  $scope.sort  = sortByDate('date');
                  $scope.reportBanShow =true;
                  $(window).load(function(){
                      $('#allReport').modal('show');
                  });
                  stopLoading();

              } else {

                  var reportSubName = Object.getOwnPropertyNames(val.Statistics_IndivdiualReportsList[0]).sort();
                  //console.log(reportSubName);

                  angular.forEach(reportSubName,function(value, keys){

                    switch(value){
                       case 'EXECUTIVE_FUEL':
                        //if(val.Statistics_IndivdiualReportsList[0].EXECUTIVE_FUEL==false){ $scope.exFuelTabShow=false; }
                        $scope.exFuelTabShow=val.Statistics_IndivdiualReportsList[0].EXECUTIVE_FUEL;
                       break;
                       case 'MONTHLY_DIST_AND_FUEL':
                        //if(val.Statistics_IndivdiualReportsList[0].MONTHLY_DIST_AND_FUEL==false){ $scope.distMonFuelTabShow=false; }
                        $scope.distMonFuelTabShow=val.Statistics_IndivdiualReportsList[0].MONTHLY_DIST_AND_FUEL;
                       break;
                       case 'DAILY':
                        //if(val.Statistics_IndivdiualReportsList[0].DAILY==false){ $scope.dailyTabShow=false; }
                        $scope.dailyTabShow=val.Statistics_IndivdiualReportsList[0].DAILY;
                       break;
                       case 'MONTHLY_DIST':
                        //if(val.Statistics_IndivdiualReportsList[0].MONTHLY_DIST==false){ $scope.distMonTabShow=false; }
                        $scope.distMonTabShow=val.Statistics_IndivdiualReportsList[0].MONTHLY_DIST;
                       break;
                       case 'POI':
                        //if(val.Statistics_IndivdiualReportsList[0].POI==false){ $scope.poiTabShow=false; }
                        $scope.poiTabShow=val.Statistics_IndivdiualReportsList[0].POI;
                       break;
                       case 'CONSOLIDATED':
                        //if(val.Statistics_IndivdiualReportsList[0].CONSOLIDATED==false){ $scope.consolTabShow=false; }
                        $scope.consolTabShow=val.Statistics_IndivdiualReportsList[0].CONSOLIDATED;
                       break;
                    }
                });

  switch(index){

    case '1':
      tabId                = 'executive';
      $scope.downloadid    = 'executive';
      $scope.donut_new     = false;
      $scope.donut         = false;
      $scope.showDate      = true;
      $scope.showMonth     = false;
      $scope.showMonthFuel = false;
      $scope.sort          = sortByDate('date');
    break;
    case '2':
      tabId                = 'poi';
      $scope.downloadid    = 'poi';
      $scope.actTab        = true;
      $scope.donut_new     = false;
      $scope.donut         = true;
      $scope.showDate      = true;
      $scope.showMonth     = false;
      $scope.showMonthFuel = false;
      $scope.sort          = sortByDate('time');
    break;
    case '3':
      tabId                 = 'executive';
      $scope.donut_new      = false;
      $scope.donut          = false;
      $scope.downloadid     = 'consolidated';
      $scope.actCons        = true;
      $scope.showDate       = true;
      $scope.showMonth      = false;
      $scope.showMonthFuel  = false;
      $scope.sort           = sortByDate('date');
    break;
    case '4':
      $scope.donut_new      = true;
      $scope.donut          = true;
      tabId                 = 'fuel';
      $scope.downloadid     = 'fuel';
      $scope.showDate       = true;
      $scope.showMonth      = false;
      $scope.showMonthFuel  = false;
      $scope.actFuel        = true;
      $scope.sort           = sortByDate('date');
    break;
    case '5':
      $scope.donut_new        = false;
      $scope.donut            = true;
      tabId                   = 'month';
      $scope.downloadid       = 'month';
      $scope.showDate         = false;
      $scope.showMonth        = true;
      $scope.showMonthFuel    = false;
      $scope.actMonth         = true;
      $scope.showMonTable     = false;
      $scope.sort             = sortByDate('date');
    break;
    case '6':
      $scope.donut_new        = false;
      $scope.donut            = true;
      tabId                   = 'monthFuel';
      $scope.downloadid       = 'monthFuel';
      $scope.showDate         = false;
      $scope.showMonth        = false;
      $scope.showMonthFuel    = true;
      $scope.actMonthFuel     = true;
      $scope.showMonFuelTable = false;
      $scope.sort             = sortByDate('date');
    break;
    default:
      if($scope.dailyTabShow==true) {
        tabId                = 'executive';
        $scope.downloadid    = 'executive';
        $scope.donut_new     = false;
        $scope.donut         = false;
        $scope.showDate      = true;
        $scope.showMonth     = false;
        $scope.showMonthFuel = false;
        $scope.sort          = sortByDate('date');
      } else if($scope.poiTabShow==true) {
        tabId                = 'poi';
        $scope.downloadid    = 'poi';
        $scope.actTab        = true;
        $scope.donut_new     = false;
        $scope.donut         = true;
        $scope.showDate      = true;
        $scope.showMonth     = false;
        $scope.showMonthFuel = false;
        $scope.sort          = sortByDate('time');
      } else if($scope.consolTabShow==true) {
        tabId                 = 'executive';
        $scope.donut_new      = false;
        $scope.donut          = false;
        $scope.downloadid     = 'consolidated';
        $scope.actCons        = true;
        $scope.showDate       = true;
        $scope.showMonth      = false;
        $scope.showMonthFuel  = false;
        $scope.sort           = sortByDate('date');
      } else if($scope.exFuelTabShow==true) {
        $scope.donut_new      = true;
        $scope.donut          = true;
        tabId                 = 'fuel';
        $scope.downloadid     = 'fuel';
        $scope.showDate       = true;
        $scope.showMonth      = false;
        $scope.showMonthFuel  = false;
        $scope.actFuel        = true;
        $scope.sort           = sortByDate('date');
      } else if($scope.distMonTabShow==true) {
        $scope.donut_new        = false;
        $scope.donut            = true;
        tabId                   = 'month';
        $scope.downloadid       = 'month';
        $scope.showDate         = false;
        $scope.showMonth        = true;
        $scope.showMonthFuel    = false;
        $scope.actMonth         = true;
        $scope.showMonTable     = false;
        $scope.sort             = sortByDate('date');
      } else if($scope.distMonFuelTabShow==true){
        $scope.donut_new        =false;
        $scope.donut            =true;
        tabId                   = 'monthFuel';
        $scope.downloadid       = 'monthFuel';
        $scope.showDate         = false;
        $scope.showMonth        = false;
        $scope.showMonthFuel    = true;
        $scope.actMonthFuel     = true;
        $scope.showMonFuelTable = false;
        $scope.sort             = sortByDate('date');
      } else {
        tabId                = 'executive';
        $scope.downloadid    = 'executive';
        $scope.donut_new     = false;
        $scope.donut         = false;
        $scope.showDate      = true;
        $scope.showMonth     = false;
        $scope.showMonthFuel = false;
        $scope.sort          = sortByDate('date');
      }
    break;
  }

  }
            
}

});

} else {

    $scope.exFuelTabShow=true;
    $scope.distMonFuelTabShow=true; 
    $scope.dailyTabShow=true;
    $scope.distMonTabShow=true;
    $scope.poiTabShow=true;
    $scope.consolTabShow=true;

    console.log('Empty getReportList API ...');

  switch(index){
    case '1':
      tabId                = 'executive';
      $scope.downloadid    = 'executive';
      $scope.donut_new     = false;
      $scope.donut         = false;
      $scope.showDate      = true;
      $scope.showMonth     = false;
      $scope.showMonthFuel = false;
      $scope.sort          = sortByDate('date');
    break;
    case '2':
      tabId                = 'poi';
      $scope.downloadid    = 'poi';
      $scope.actTab        = true;
      $scope.donut_new     = false;
      $scope.donut         = true;
      $scope.showDate      = true;
      $scope.showMonth     = false;
      $scope.showMonthFuel = false;
      $scope.sort          = sortByDate('time');
    break;
    case '3':
      tabId                 = 'executive';
      $scope.donut_new      = false;
      $scope.donut          = false;
      $scope.downloadid     = 'consolidated';
      $scope.actCons        = true;
      $scope.showDate       = true;
      $scope.showMonth      = false;
      $scope.showMonthFuel  = false;
      $scope.sort           = sortByDate('date');
    break;
    case '4':
      $scope.donut_new      = true;
      $scope.donut          = true;
      tabId                 = 'fuel';
      $scope.downloadid     = 'fuel';
      $scope.showDate       = true;
      $scope.showMonth      = false;
      $scope.showMonthFuel  = false;
      $scope.actFuel        = true;
      $scope.sort           = sortByDate('date');
    break;
    case '5':
      $scope.donut_new        = false;
      $scope.donut            = true;
    //console.log('index 4...');
      tabId                   = 'month';
      $scope.downloadid       = 'month';
      $scope.showDate         = false;
      $scope.showMonth        = true;
      $scope.showMonthFuel    = false;
      $scope.actMonth         = true;
      $scope.showMonTable     = false;
      $scope.sort             = sortByDate('date');
    break;
    case '6':
      $scope.donut_new        =false;
      $scope.donut            =true;
      //console.log('index 5...');
      tabId                   = 'monthFuel';
      $scope.downloadid       = 'monthFuel';
      $scope.showDate         = false;
      $scope.showMonth        = false;
      $scope.showMonthFuel    = true;
      $scope.actMonthFuel     = true;
      $scope.showMonFuelTable = false;
      $scope.sort             = sortByDate('date');
    break;
    default:
      tabId                = 'executive';
      $scope.downloadid    = 'executive';
      $scope.donut_new     = false;
      $scope.donut         = false;
      $scope.showDate      = true;
      $scope.showMonth     = false;
      $scope.showMonthFuel = false;
      $scope.sort          = sortByDate('date');
    break;
    }
  }

})
.error(function(data, status) {
    
    console.error('Repos error', status, data);
    console.log('Empty getReportList API ...');

    $scope.exFuelTabShow=true;
    $scope.distMonFuelTabShow=true; 
    $scope.dailyTabShow=true;
    $scope.distMonTabShow=true;
    $scope.poiTabShow=true;
    $scope.consolTabShow=true;

  switch(index){
    case '1':
      tabId                = 'executive';
      $scope.downloadid    = 'executive';
      $scope.donut_new     = false;
      $scope.donut         = false;
      $scope.showDate      = true;
      $scope.showMonth     = false;
      $scope.showMonthFuel = false;
      $scope.sort          = sortByDate('date');
    break;
    case '2':
      tabId                = 'poi';
      $scope.downloadid    = 'poi';
      $scope.actTab        = true;
      $scope.donut_new     = false;
      $scope.donut         = true;
      $scope.showDate      = true;
      $scope.showMonth     = false;
      $scope.showMonthFuel = false;
      $scope.sort          = sortByDate('time');
    break;
    case '3':
      tabId                 = 'executive';
      $scope.donut_new      = false;
      $scope.donut          = false;
      $scope.downloadid     = 'consolidated';
      $scope.actCons        = true;
      $scope.showDate       = true;
      $scope.showMonth      = false;
      $scope.showMonthFuel  = false;
      $scope.sort           = sortByDate('date');
    break;
    case '4':
      $scope.donut_new      = true;
      $scope.donut          = true;
      tabId                 = 'fuel';
      $scope.downloadid     = 'fuel';
      $scope.showDate       = true;
      $scope.showMonth      = false;
      $scope.showMonthFuel  = false;
      $scope.actFuel        = true;
      $scope.sort           = sortByDate('date');
    break;
    case '5':
      $scope.donut_new        = false;
      $scope.donut            = true;
      tabId                   = 'month';
      $scope.downloadid       = 'month';
      $scope.showDate         = false;
      $scope.showMonth        = true;
      $scope.showMonthFuel    = false;
      $scope.actMonth         = true;
      $scope.showMonTable     = false;
      $scope.sort             = sortByDate('date');
    break;
    case '6':
      $scope.donut_new        =false;
      $scope.donut            =true;
      tabId                   = 'monthFuel';
      $scope.downloadid       = 'monthFuel';
      $scope.showDate         = false;
      $scope.showMonth        = false;
      $scope.showMonthFuel    = true;
      $scope.actMonthFuel     = true;
      $scope.showMonFuelTable = false;
      $scope.sort             = sortByDate('date');
    break;
    default:
      tabId                = 'executive';
      $scope.downloadid    = 'executive';
      $scope.donut_new     = false;
      $scope.donut         = false;
      $scope.showDate      = true;
      $scope.showMonth     = false;
      $scope.showMonthFuel = false;
      $scope.sort          = sortByDate('date');
    break;
    }

 });

});

/*  //tab view
  if(index == 1) {
      tabId                = 'poi';
      $scope.downloadid    = 'poi';
      $scope.actTab        = true;
      $scope.donut_new     = false;
      $scope.donut         = true;
      $scope.showDate      = true;
      $scope.showMonth     = false;
      $scope.showMonthFuel = false;
      $scope.sort          = sortByDate('time');

  } else if(index == 2){
      tabId                 = 'executive';
      $scope.donut_new      = false;
      $scope.donut          = false;
      $scope.downloadid     = 'consolidated';
      $scope.actCons        = true;
      $scope.showDate       = true;
      $scope.showMonth      = false;
      $scope.showMonthFuel  = false;
      $scope.sort           = sortByDate('date');
  } 
    else if(index == 3){
      $scope.donut_new    = true;
      $scope.donut        = true;
      tabId               = 'fuel';
      $scope.downloadid     = 'fuel';
      $scope.showDate       = true;
      $scope.showMonth      = false;
      $scope.showMonthFuel  = false;
      $scope.actFuel        = true;
      $scope.sort           = sortByDate('date');

  } else if(index == 4) {
      $scope.donut_new=false;
      $scope.donut    =true;
    //console.log('index 4...');
      tabId         = 'month';
      $scope.downloadid   = 'month';
      $scope.showDate     = false;
      $scope.showMonth    = true;
      $scope.showMonthFuel = false;
      $scope.actMonth   = true;
      $scope.showMonTable = false;
      $scope.sort     = sortByDate('date');
  }
   else if(index == 5){
      $scope.donut_new=false;
      $scope.donut    =true;
      //console.log('index 5...');
      tabId         = 'monthFuel';
      $scope.downloadid   = 'monthFuel';
      $scope.showDate     = false;
      $scope.showMonth    = false;
      $scope.showMonthFuel = true;
      $scope.actMonthFuel    = true;
      $scope.showMonFuelTable = false;
      $scope.sort     = sortByDate('date');
  }
  else {
      tabId               = 'executive';
      $scope.downloadid   = 'executive';
      $scope.donut_new    = false;
      $scope.donut        =false;
      $scope.showDate     = true;
      $scope.showMonth    = false;
      $scope.showMonthFuel = false;
      $scope.sort     = sortByDate('date');
  }

*/

  $scope.trimHyphens = function(textVal){
       var splitValue = textVal.split(/[-]+/);
  return splitValue[2];
  }


function daysInThisMonth() {
  var now = new Date();
 // console.log(now);
  var mm = now.getMonth();
    if(mm==0){
        mm=12;
     }
  var nowNew = new Date(now.getFullYear(), mm, 0).getDate();

 return nowNew;
}

$scope.submitMonFuel=function(){

  console.log($scope.fromMonthFuel);

  var newmonVal=$scope.fromMonthFuel.split('/');
  $scope.monFuelVal=parseInt(newmonVal[0]);
  $scope.yearFuelVal=newmonVal[1];

   $scope.lenMonss=getDaysInMonthFuel($scope.monFuelVal,$scope.yearFuelVal);
   $scope.colValss=$scope.lenMonss+2;

   $scope.monValFront  = $scope.monthsValss[newmonVal[0]-1];
   $scope.curYearFront = " - "+newmonVal[1];

     //console.log(parseInt(newmonVal[0]));
       //console.log($scope.yearFuelVal);
  startLoading();
   $scope.showMonFuelTable = false;
  serviceCall();
}

function getNewMonthFuel() {
  $scope.monthsValss=["January", "February","March","April","May","June","July","August","September","October","November","December"]; 

    var dates = new Date();
    var monsValss = dates.getMonth();
    var yearValss = dates.getFullYear(); 

            if(monsValss == 0){
                monsValss=12;
                yearValss=dates.getFullYear()-1; 

                $scope.monFuelVal   = monsValss;
                $scope.yearFuelVal  = yearValss;
                $scope.monValFront  = $scope.monthsValss[monsValss-1];
                $scope.curYearFront = " - "+yearValss;
            }else{

                $scope.monFuelVal   = monsValss;
                $scope.yearFuelVal  = yearValss;
                $scope.monValFront  = $scope.monthsValss[monsValss-1];
                $scope.curYearFront = " - "+yearValss;

                if(monsValss<10){
                   monsValss = "0"+monsValss; 
                }
            }
   
return monsValss+'/'+yearValss;
}
//console.log(getNewMonthFuel());
$scope.fromMonthFuel = getNewMonthFuel();
$scope.lenMon=daysInThisMonth();
$scope.colVals=$scope.lenMon+2;

$scope.lenMonss=$scope.lenMon;
$scope.colValss=$scope.lenMonss+2;

function getDaysInMonthFuel(month,year) {
  return new Date(year, month, 0).getDate();
}

function getDaysInMonth(month) {
    var now = new Date(); 
    return new Date(now.getFullYear(), month, 0).getDate();
}

function currentYear(){
  var yy = new Date();
 return yy.getFullYear();
}


function currentMonth(){
   
   var retVal;
   $scope.monthsVal=["January", "February","March","April","May","June","July","August","September","October","November","December"];
   var dd = new Date();
 //var mm = dd.getMonth()+1;
   var mm = dd.getMonth();
   var mmNew=mm;

    if(mm==0)
    {
      mm=12;
    } 

    if(mm<10) {
      mm='0'+mm;
    }
    $scope.monthNo=mm;

   //console.log($scope.monthNo);

    if(mmNew==0){
        retVal = $scope.monthsVal[11];
    }else{
        retVal = $scope.monthsVal[mmNew-1];
    }

return retVal;
}

   $scope.monArray=currentMonth();
// console.log($scope.monArray);
   $scope.curYear=" - "+currentYear()+"";
   $scope.monsVal=$scope.monArray;


$scope.monthssVal=function(val){

//  console.log(val);
  switch(val)
  {
      case 'January':
        $scope.monthNo="0"+1;
      break;
      case 'February':
        $scope.monthNo="0"+2;
      break;
      case 'March':
        $scope.monthNo="0"+3;
      break;
      case 'April':
        $scope.monthNo="0"+4;
      break;
      case 'May':
        $scope.monthNo="0"+5;
      break;
      case 'June':
        $scope.monthNo="0"+6;
      break;
      case 'July':
        $scope.monthNo="0"+7;
      break;
      case 'August':
        $scope.monthNo="0"+8;
      break;
      case 'September':
        $scope.monthNo="0"+9;
      break;
      case 'October':
        $scope.monthNo=10;
      break;
      case 'November':
        $scope.monthNo=11;
      break;
      case 'December':
        $scope.monthNo=12;
      break;
      default:
        console.log('not a month');
      break;
  }

   $scope.lenMon=getDaysInMonth($scope.monthNo);
   $scope.colVals=$scope.lenMon+2;

   startLoading();
   $scope.showMonTable =  false;
  serviceCall();
// console.log($scope.monthNo);
}

//Global Variable declaration 
$scope.url      =   GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
var clickStatus   =   'groupButton';
//$scope.donut    =   true;
//$scope.donut_new    =   false;
$scope.bar      = true;
$('#singleDiv').hide();
var avoidOnload   = false;

var vehicleSelected = '';


$scope.parseInts=function(data){
 return parseInt(data);
}

 $scope.getUpdateData = function(shortName,date){
    // alert("Hello");

      var usernameMater = window.localStorage.getItem("userMasterName");
      var sendUser = usernameMater.split(',');
      // alert(sendUser[1]);
    $http.get('http://128.199.159.130:9000/addExecutiveDetailsTableData?userId='+sendUser[1]+'&date='+date+'&vehicleId='+shortName+'');
    //window.location.reload();
    console.log(shortName+date);
     var groupUrl    = GLOBAL.DOMAIN_NAME+'/getExecutiveReport?groupId='+$scope.viewGroup.group+'&fromDate='+$scope.fromdate+'&toDate='+$scope.todate;
      vamoservice.getDataCall(groupUrl).then(function(responseGroup){
        var tagsCheck   = (responseGroup.error) ? true :  false;
        // console.log($scope.to_trusted($scope.fromdate));
        $scope.execGroupReportData    = [];
        if(tagsCheck == false)
        if(vehicleSelected){
          //$scope.donut  =   false;
          //$scope.donut_new=   false;
          $scope.donut  = 1;
          $scope.bar    = false;
          $('#singleDiv').show(500);
          $scope.execGroupReportData  = ($filter('filter')(responseGroup.execReportData, {'vehicleId':vehicleSelected}));
          
          barLoad(vehicleSelected);
        } else {
          //$scope.donut  =   false;
          //$scope.donut_new=   false;
          $scope.bar    = true;
          $('#singleDiv').hide();
          $scope.execGroupReportData  = responseGroup.execReportData;
          donutLoad(responseGroup);
        }
        else
          barLoad(vehicleSelected),donutLoad(responseGroup);
        stopLoading();
      })
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

$scope.getTodayDate  =  function(date) {
  var date = new Date(date);
  return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
};

function sessionValue(vid, gname){
  sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
  $("#testLoad").load("../public/menu");
}


//vehicle list
$scope.$watch("url", function(val){
  vamoservice.getDataCall(val).then(function(data) {

    $scope.vehicleList  = data;
//  console.log($scope.vehicleList);
    $scope.fromNowTS    = new Date();
    $scope.toNowTS      = new Date().getTime() - 86400000;
    $scope.fromdate     = $scope.getTodayDate($scope.fromNowTS.setDate($scope.fromNowTS.getDate()-7));
    $scope.todate       = $scope.getTodayDate($scope.toNowTS);
    $scope.fromtime     = formatAMPM($scope.fromNowTS);
      $scope.totime     = formatAMPM($scope.toNowTS);
      $scope.trackVehID = data[0].vehicleLocations[0].vehicleId;
      $scope.groupname  = data[0].group;
      sessionValue($scope.trackVehID, $scope.groupname);

    angular.forEach(data, function(value, key){

         if($scope.groupname == value.group){

        $scope.gIndex = value.rowId;
        $scope.vehicleNames=[];

         angular.forEach(value.vehicleLocations, function(val, skey){
            $scope.vehicleNames.push(val.vehicleId);
          // console.log(val.vehicleId);
        })
     }  
      if(value.totalVehicles) {
        $scope.viewGroup =  value;
        serviceCall();
        avoidOnload   = true;
      }
        
    })
    
  })
})

//group click
$scope.groupSelection   = function(groupName, groupId) {
  startLoading();

   $scope.showMonTable=false;
   vehicleSelected    = '';
   $scope.donut       = 0;

   $scope.viewGroup.group   =   groupName;
   var groupUrl       =   GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+groupName;

   vamoservice.getDataCall(groupUrl).then(function(groupResponse){

      $scope.trackVehID       =   groupResponse[groupId].vehicleLocations[0].vehicleId;
      $scope.groupname    = groupName;
      sessionValue($scope.trackVehID, $scope.groupname)
      $scope.vehicleList  =   groupResponse;

        angular.forEach(groupResponse, function(value, key){

         if($scope.groupname == value.group){
            $scope.vehicleNames=[]; 
            $scope.gIndex = value.rowId;
          
           angular.forEach(value.vehicleLocations, function(val, skey){

               $scope.vehicleNames.push(val.vehicleId);
               // console.log(val.vehicleId);
            })
          }
       })

     serviceCall();
   //stopLoading();
  })

}

//vehicleId click
$scope.genericFunction  =   function(vehicleID, index){
  startLoading();
  vehicleSelected   =   vehicleID;
  sessionValue(vehicleSelected,$scope.groupname);
  
  serviceCall();
}

//loading start function
// var startLoading   = function () {
//  $('#status').show(); 
//  $('#preloader').show();
// };

//loading stop function
// var stopLoading    = function () {
//  $('#status').fadeOut(); 
//  $('#preloader').delay(350).fadeOut('slow');
//  $('body').delay(350).css({'overflow':'visible'});
// };


//trim colon function
$scope.trimColon = function(textVal) {
    return textVal.split(":")[0].trim();
  }


//vehicle level graphs

function barLoad(vehicleId) {
      //console.log(vehicleId);
      $scope.barArray1  = [];
      $scope.barArray2  = [];
      $scope.barArray1.push(["X", "Date vs Distance"]);
      $scope.barArray2.push(["X", "Date vs Overspeed"]);
      $scope.data     = ($filter('filter')($scope.execGroupReportData, {'vehicleId':vehicleId}));
      angular.forEach($scope.data, function(value, key) {
        $scope.barArray1.push([value.date, value.distanceToday]);
        $scope.barArray2.push([value.date, value.topSpeed]);
    }); 
    //console.log($scope.barArray1);
      c3.generate({
        bindto: '#chart3',
        data: {
          x: 'X',
            columns: $scope.barArray1,
            type: 'bar'
        },
         axis: {
            x: {
              type : 'category',
                label:{                 
                  text : 'Date',
                  position: 'outer-right' 
                }
            },
            y: {
                label:{
                  text : 'Distance Today',
                  position: 'outer-middle'  
                }
            }
        }
    });
    
    c3.generate({
        bindto: '#chart4',
        data: {
          x: 'X',
            columns: $scope.barArray2,
            type: 'bar'
        },
         axis: {
            x: {
              type : 'category',
                label:{                 
                  text : 'Date',
                  position: 'outer-right' 
                }
            },
            y: {
                label:{
                  text : 'Topspeed',
                  position: 'outer-middle'  
                }
            }
        }
    });       
    };
  

//group level graph 
function donutLoad(data) {

  $scope.barArray = [];
  var vehiName='';
  if(data != '')
  angular.forEach(JSON.parse(data.distanceCoveredAnalytics), function(value, key) {
    angular.forEach(data.execReportData, function(val, key1){

      if(val.vehicleId == key)
      {
        vehiName = val.shortName;
        //console.log(' inside the for loop ---->'+key+'--->'+val.vehicleId)
        return;
      }
    })  
    $scope.barArray.push([vehiName, value]);
  }); 
  $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Total Distance'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '8px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Distance Travelled'
            }
        },
        legend: {
            enabled: false
        },
        // tooltip: {
        //     pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>'
        // },
        series: [{
            name: 'Distance',
            data: $scope.barArray,
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#003366',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '9px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
};


function chartFuel(data){

  $scope.chartVehic  =   [];
  $scope.chartDist   =   [];
  $scope.chartFuels  =   [];
  
  var distVal=0;
  var fuelVal=0;
  var preVehiVal="";
  var curVehiVal="";
  var elseVal=0;


  if(data)
  {
    angular.forEach(data, function(val, key){

      curVehiVal=val.vehicleName;

      if(key==0){

             distVal=distVal+val.distanceToday;
             fuelVal=fuelVal+val.fuelConsume;

             preVehiVal=curVehiVal;
      }

        if(key>0){

             if(preVehiVal==curVehiVal){

                 distVal=distVal+val.distanceToday;
                 fuelVal=fuelVal+val.fuelConsume;
             
                 preVehiVal=curVehiVal;
             }else{
               
              $scope.chartDist.push(parseInt(distVal));
              $scope.chartFuels.push(parseInt(fuelVal));
              $scope.chartVehic.push(preVehiVal);

            //  console.log(''+preVehiVal+'  '+distVal+'  '+fuelVal);
                distVal=0;
                fuelVal=0;
         

                distVal=distVal+val.distanceToday;
                fuelVal=fuelVal+val.fuelConsume;

              preVehiVal=curVehiVal;
              elseVal=1;
            }
         }
         if(key==data.length-1){

              $scope.chartDist.push(parseInt(distVal));
              $scope.chartFuels.push(parseInt(fuelVal));
              $scope.chartVehic.push(preVehiVal);
         }

      });

       if(elseVal==0){

          $scope.chartDist.push(parseInt(distVal));
          $scope.chartFuels.push(parseInt(fuelVal));
          $scope.chartVehic.push(preVehiVal);
       }

  /*  console.log($scope.chartVehic);
        console.log($scope.chartDist);  
          console.log($scope.chartFuels); */
    }


//Highcharts.chart('container', {
$('#container_new').highcharts({  
    chart: {
        type: 'column'
    },
    title: {
        text: 'Efficiency Optimization'
    },
    xAxis: {
        categories: $scope.chartVehic,
    },
    yAxis: [{
        min: 0,
        title: {
            text: 'Distance (Kms)'
        }
    }, {
        title: {
            text: 'Fuel (Ltrs)'
        },
        opposite: true
    }],
    legend: {
        shadow: false
    },
    tooltip: {
        shared: true
    },
    plotOptions: {
        column: {
            grouping: false,
            shadow: false,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Distance Covered',
        color: 'rgba(165,170,217,1)',
        data: $scope.chartDist,
        pointPadding: 0.3,
        pointPlacement: -0.2
    }, /*{
        name: 'Employees Optimized',
        color: 'rgba(126,86,134,.9)',
        data: [140, 90, 40],
        pointPadding: 0.4,
        pointPlacement: -0.2
    },*/ {
        name: 'Fuel Consumed',
        color: 'rgba(248,161,63,1)',
        data: $scope.chartFuels,
        tooltip: {
           // valuePrefix: '',
            valueSuffix: ' Ltrs'
        },
        pointPadding: 0.3,
        pointPlacement: 0.2,
        yAxis: 1
    } /*, {
        name: 'Profit Optimized',
        color: 'rgba(186,60,61,.9)',
        data: [203.6, 198.8, 208.5],
        tooltip: {
            valuePrefix: '$',
            valueSuffix: ' M'
        },
        pointPadding: 0.4,
        pointPlacement: 0.2,
        yAxis: 1
    }*/ ]
});

}




$scope.msToTime = function(ms) 
{
    days = Math.floor(ms / (24 * 60 * 60 * 1000));
    daysms = ms % (24 * 60 * 60 * 1000);
    hours = Math.floor((daysms) / (60 * 60 * 1000));
    hoursms = ms % (60 * 60 * 1000);
    minutes = Math.floor((hoursms) / (60 * 1000));
    minutesms = ms % (60 * 1000);
    seconds = Math.floor((minutesms) / 1000);
    
   if(days>1){
     return days+"days "+hours+":"+minutes+":"+seconds;
   } 
   else if(days==1){
     return days+"day "+hours+":"+minutes+":"+seconds;
   }
   else if(days==0){
      return hours +":"+minutes+":"+seconds;
   }
  }



function distanceMonth(data){

  var ret_obj=[];

    angular.forEach(data.vehicleDistanceDatas, function(val, key){

        ret_obj.push({vehiId:val.vehicleId,vehiName:val.shortName,totDist:val.totalDistance});
        ret_obj[key].distsTodays=[];

         angular.forEach(val.distances, function(sval, skey){
           ret_obj[key].distsTodays.push({distanceToday:sval});
           // console.log(sval);
           }) 
    })    
 return ret_obj;
}

function distanceFuelMonth(data){

  var ret_obj=[];

    angular.forEach(data.monthlyDistAndFuelDetails, function(val, key){

        ret_obj.push({vehiId:val.vehicleId,vehiName:val.shortName,totDist:val.totalDistance,totFuel:val.totalFuelConsume,odoStart:val.startOdoMeterReading,odoEnd:val.endOdoMeterReading});
        ret_obj[key].distsTodays=[];
        ret_obj[key].fuelTodays=[];

         angular.forEach(val.getVehicleDistanceData, function(sval, skey){
           ret_obj[key].distsTodays.push({distanceToday:sval});
           // console.log(sval);
           }); 

          angular.forEach(val.getVehicleFuelData, function(tval, tkey){
             ret_obj[key].fuelTodays.push({fuelsToday:tval});
           // console.log(tval);
           }); 
    });   

 return ret_obj;
}


 /*function distanceMonth(data){

  var ret_obj  = [] ;
  var preVal   = "" ;
    var curVal   = "" ;
    var firstVal =  0 ;
    var distVehi =  0 ;
    $scope.totDistY =  [] ;
      
    angular.forEach(data.executiveReportsForDistances, function(val, key){

        curVal=val.vehicleId.toString();

         if(key==0){

           ret_obj.push({VehiId:val.vehicleId});
             ret_obj[firstVal].dateDist=[];
             ret_obj[firstVal].dateDist.push({date:val.date,distanceToday:val.distanceToday});
             distVehi=distVehi+val.distanceToday;
             preVal=val.vehicleId.toString();
           
          }
      
      if(key>0){
             if(preVal==curVal){
      
                 ret_obj[firstVal].dateDist.push({date:val.date,distanceToday:val.distanceToday});
                 distVehi=distVehi+val.distanceToday;
                 preVal=val.vehicleId.toString();

             }else{

                 firstVal++;
                 $scope.totDistY.push({totDist:distVehi});
                 ret_obj.push({VehiId:val.vehicleId});
                 ret_obj[firstVal].dateDist=[];
                 preVal=val.vehicleId.toString();

              }
           }

    }) 

  $scope.totDistY.push({totDist:distVehi});
    
return ret_obj;
}

*/
 

function serviceCall(shortName,date){

  console.log("service....");


 if( tabId == 'executive' || tabId == 'poi' || tabId == 'fuel'){
  if((checkXssProtection($scope.fromdate) == true) && (checkXssProtection($scope.todate) == true)){
    if(tabId == 'executive' || $scope.actCons==true ){
      var groupUrl    = GLOBAL.DOMAIN_NAME+'/getExecutiveReport?groupId='+$scope.viewGroup.group+'&fromDate='+$scope.fromdate+'&toDate='+$scope.todate;
      vamoservice.getDataCall(groupUrl).then(function(responseGroup){
        var tagsCheck   = (responseGroup.error) ? true :  false;
        // console.log($scope.to_trusted($scope.fromdate));
        $scope.execGroupReportData    = [];
        if(tagsCheck == false)
        if(vehicleSelected){
          //$scope.donut  =   false;
          //$scope.donut_new=   false;
          $scope.donut  = 1;
          $scope.bar    = false;
          $('#singleDiv').show(500);
          $scope.execGroupReportData  = ($filter('filter')(responseGroup.execReportData, {'vehicleId':vehicleSelected}));

          barLoad(vehicleSelected);
        } else {
          //$scope.donut  =   false;
          //$scope.donut_new=   false;
          $scope.bar    = true;
          $('#singleDiv').hide();
          $scope.execGroupReportData  = responseGroup.execReportData;
          donutLoad(responseGroup);
        }
        else
          barLoad(vehicleSelected),donutLoad(responseGroup);
        stopLoading();
      })
    } else if(tabId == 'poi' || $scope.actTab == true){
      $scope.donut    =   true;
      $scope.donut_new    =   false;
      $scope.bar      =   true;
      $('#singleDiv').hide();
      var poiUrl      = GLOBAL.DOMAIN_NAME+'/getPoiHistory?groupId='+$scope.viewGroup.group+'&fromDate='+$scope.fromdate+'&toDate='+$scope.todate;
      vamoservice.getDataCall(poiUrl).then(function(responsePoi){
        $scope.geofencedata     =   [];
        if(responsePoi.history !=null)
        if(responsePoi.history.length>0)
          $scope.geofencedata   =     responsePoi.history;
        stopLoading();
      })
    } else if(tabId == 'fuel' || $scope.actFuel == true){
      $scope.donut    =   true;
      $scope.donut_new    =   true;
      $scope.bar      =   true;
      $('#singleDiv').hide();
      var fuelUrl =GLOBAL.DOMAIN_NAME+'/getExecutiveFuelReport?groupId='+$scope.viewGroup.group+'&fromDate='+$scope.fromdate+'&toDate='+$scope.todate;
      // console.log(fuelUrl);
      $scope.execFuelData   = [];
            $http.get(fuelUrl).success(function(data){

          $scope.execFuelData  = data;
            chartFuel($scope.execFuelData);

        stopLoading();
      });
    }

   }else {

        $scope.barArray1      = [];
        $scope.barArray2      = [];
        $scope.execGroupReportData  = [];
        $scope.geofencedata     = [];
        barLoad(vehicleSelected);
        donutLoad('');
        chartFuel('');
     stopLoading();
  }

} else if(tabId == 'month' || $scope.actMonth == true) {

      $scope.donut    =   true;
      $scope.donut_new    =   false;
      $scope.bar      =   true;
      $('#singleDiv').hide();

      var monthUrl =GLOBAL.DOMAIN_NAME+'/getExecutiveReportVehicleDistance?groupId='+$scope.viewGroup.group+'&month='+$scope.monFuelVal+'&year='+$scope.yearFuelVal;
      console.log(monthUrl);
             
          $scope.monthData  = [];
          $http.get(monthUrl).success(function(data){
            $scope.monthData = data;
          console.log($scope.monthData);

          $scope.monthDates=[];
            for(var i=0;i<$scope.lenMon;i++){
                  $scope.monthDates.push(i+1);
                }

          $scope.distMonData=[];
          $scope.distMonData=distanceMonth($scope.monthData);

          $scope.totDistVehic=[];
          if(data.comulativeDistance!=null){

               for(var i=0;i<data.comulativeDistance.length;i++){
                   $scope.totDistVehic.push(data.comulativeDistance[i]); 
                }
          }
             //console.log($scope.distMonData);
             //console.log(daysInThisMonth());
          $scope.showMonTable=true;
        stopLoading();
      });
  
  } else if(tabId == 'monthFuel' || $scope.actMonthFuel == true) {

      $scope.donut      =   true;
      $scope.donut_new  =   false;
      $scope.bar        =   true;
      $('#singleDiv').hide();

      var monthFuelUrl = GLOBAL.DOMAIN_NAME+'/getMonthlyExecutiveDistanceAndFuel?groupId='+$scope.viewGroup.group+'&month='+$scope.monFuelVal+'&year='+$scope.yearFuelVal;
         //console.log(monthFuelUrl);
          $scope.monthFuelData  = [];
          $http.get(monthFuelUrl).success(function(data){
           
              $scope.monthFuelData = data;
           // console.log($scope.monthFuelData);

           $scope.monthFuelDates=[];
             for(var i=0;i<$scope.lenMonss;i++){
                  $scope.monthFuelDates.push(i+1);
                }

           $scope.distMonFuelData=[];
           $scope.distMonFuelData=distanceFuelMonth($scope.monthFuelData);

           $scope.totMonDistVehic=[];
           if(data.cumulativeVehicleDistanceData!=null){

               for(var i=0;i<data.cumulativeVehicleDistanceData.length;i++){
                   $scope.totMonDistVehic.push(data.cumulativeVehicleDistanceData[i]); 
                }
           }

           $scope.totMonFuelVehic=[];
           if(data.cumulativeVehicleFuelData!=null){

               for(var i=0;i<data.cumulativeVehicleFuelData.length;i++){
                   $scope.totMonFuelVehic.push(data.cumulativeVehicleFuelData[i]); 
                }
           }
             //console.log($scope.distMonFuelData);
             //console.log(daysInThisMonth());
          $scope.showMonFuelTable=true;
        stopLoading();
      });
  } 
}

$scope.plotHist   = function()
{
  startLoading();
  serviceCall();
  // stopLoading();
}

//tab click method
$scope.alertMe    =   function(tabClick)
{ 
  if(avoidOnload == true)
  switch (tabClick){
    case 'executive':
      startLoading();
      tabId               = 'executive';
      $scope.sort         = sortByDate('date');
      $scope.downloadid   = 'executive';
      $scope.tabActive    = true;
      $scope.actTab       = false;
      $scope.actFuel      = false;
      $scope.actMonth     = false;
      $scope.actMonthFuel = false;
      $scope.actCons      = false;
      $scope.showDate     = true;
      $scope.showMonth    = false;
      $scope.showMonthFuel = false;
      serviceCall();
      $scope.donut        = false;
      $scope.donut_new    = false;
      break;
    case 'poi':
      startLoading();
      $scope.sort         = sortByDate('time');
      tabId               = 'poi';
      $scope.downloadid   = 'poi';
      $scope.tabActive    = false;
      $scope.actTab       = true;
      $scope.actFuel      = false;
      $scope.actMonth     = false;
      $scope.actMonthFuel = false;
      $scope.actCons      = false;
      $scope.showDate     = true;
      $scope.showMonth    = false;
      $scope.showMonthFuel = false;
      serviceCall();
      break;
    case 'consolidated' :
      startLoading();
      $scope.sort         = sortByDate('date');
      tabId               = 'executive';
      $scope.downloadid   = 'consolidated';
      $scope.tabActive    = false;
      $scope.actTab       = false;
      $scope.actFuel      = false;
      $scope.actMonth     = false;
      $scope.actMonthFuel = false;
      $scope.actCons      = true;
      $scope.showDate     = true;
      $scope.showMonth    = false;
      $scope.showMonthFuel = false;
      serviceCall();
      $scope.donut        = false;
      $scope.donut_new    = false;
      break;
    case 'fuel' :
      startLoading();
      $scope.sort         = sortByDate('date');
      tabId               = 'fuel';
      $scope.downloadid   = 'fuel';
      $scope.tabActive    = false;
      $scope.actTab       = false;
      $scope.actFuel      = true;
      $scope.actMonth     = false;
      $scope.actMonthFuel = false;
      $scope.actCons      = false;
      $scope.showDate     = true;
      $scope.showMonth    = false;
      $scope.showMonthFuel = false;
      serviceCall();
      break;
    case 'distMonth' :
      startLoading();
      $scope.sort         = sortByDate('date');
      $scope.tabActive    = false;
      $scope.actTab       = false;
      $scope.actFuel      = false;
      $scope.actMonth     = true;
      $scope.actMonthFuel = false;
      $scope.actCons      = false;
      $scope.showMonTable = false;
      tabId               = 'month';
      $scope.downloadid   = 'month';
      $scope.showDate     = false;
      $scope.showMonthFuel = false;
      $scope.showMonth    = true;
      serviceCall();
      break;
    case 'distMonthFuel' :
      startLoading();
      tabId  = 'monthFuel';
      $scope.sort         = sortByDate('date');
      $scope.tabActive    = false;
      $scope.actTab       = false;
      $scope.actFuel      = false;
      $scope.actMonth     = false;
      $scope.actCons      = false;
      $scope.actMonthFuel = true;
      $scope.showMonFuelTable = false;
      $scope.downloadid   = 'monthFuel';
      $scope.showDate     = false;
      $scope.showMonth    = false;
      $scope.showMonthFuel = true;
      serviceCall();
      break;    
    default :
      break;
  }
} 

$scope.exportData = function (data) {
      //console.log(data);
    var blob = new Blob([document.getElementById(data).innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, data+".xls");
    };
    
    $scope.exportDataCSV = function (data) {
    //console.log(data);
    CSV.begin('#'+data).download(data+'.csv').go();
    };


}]);
