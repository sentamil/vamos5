<!DOCTYPE html>
<html lang="en" ng-app="mapApp">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="Satheesh">
<title>GPS</title>
<link rel="shortcut icon" href="assets/imgs/tab.ico">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="assets/css/simple-sidebar.css" rel="stylesheet">
<link href="assets/font-awesome-4.2.0/css/font-awesome.css" rel="stylesheet">
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style type="text/css">
  #map_canvas{
    height:94vh; width:100%; margin-top: 35px;
  }
  [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
      display: none !important;
   }
  
</style>
</head>
<div id="preloader" >
    <div id="status">&nbsp;</div>
</div>
<div id="preloader02" >
    <div id="status02">&nbsp;</div>
</div>
<body ng-controller="mainCtrl">
        <div id="wrapper" >
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand"><a href="javascript:void(0);"><img id="imagesrc" src=""/></i></a></li>
                <li class="track"><a href="../public/track"><div></div><label>Track</label></a></li>
                <li class="history"><a href="../public/track?maps=replay" class="active"><div></div><label>History</label></a></li>
                <li class="alert01"><a href="../public/reports"><div></div><label>Reports</label></a></li>
                <li class="stastics"><a href="../public/statistics"><div></div><label>Statistics</label></a></li>
                <li class="admin"><a href="../public/settings"><div></div><label>Scheduled</label></a></li>
                <li><a href="../public/logout"><img src="assets/imgs/logout.png"/></a></li>
            </ul>
            <ul class="sidebar-subnav" style="max-height: 100vh; overflow-y: auto;">
                <li style="padding-left:25px;">
                        <div class="right-inner-addon" align="center">
                    <i class="fa fa-search"></i>
                    <input type="search" class="form-control" placeholder="Search" ng-model="searchbox" name="search" />
                    </div>
                </li>
                <li ng-repeat="location in locations" class="active"><a href="javascript:void(0);" ng-click="groupSelection(location.group, location.rowId)" ng-cloak>{{trimColon(location.group)}}</a>
                    <ul class="nav nav-second-level" style="max-height: 400px; overflow-y: auto;">
                    <li ng-repeat="loc in location.vehicleLocations | filter:searchbox" ng-class="{active:selected==$index}"><a href="javascript:void(0);" ng-class="{red:loc.status == 'OFF'}" ng-click="genericFunction(loc.vehicleId, $index, loc.shortName)" ng-cloak><img ng-src="assets/imgs/{{loc.vehicleType}}.png" fall-back-src="assets/imgs/Car.png" width="16" height="16"/> <span>{{loc.shortName}}</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="testLoad"></div>
                        </div>
                        <div id="minmax">
                            <img src="assets/imgs/add.png" />
                        </div>
                        <div id="contentmin" class="rightsection" style="position: absolute;margin-top: -20px;right: 10px; z-index:9; width: 300px;padding: 10px;background: #fff;-webkit-border-radius: 12px;-moz-border-radius: 12px;border-radius: 12px;">
                                 <table cellpadding="0" cellspacing="0" class="dynData">
                                          <tbody>
                                                <tr>
                                                  <td style="text-align:center; font-weight:bold;">Vehicle Name</td>
                                                    <td id="vehiid" style="text-align:center; font-weight:bold !important;"><h3></h3></td>
                                                    
                                                </tr>
                                            </tbody>
                                            </table>
                                                
                                <div>
                                  <div class="form-group" style="width:140px; margin-right:10px; float:left">
                                      <div class="input-group datecomp">
                                            <input type="text" class="form-control placholdercolor" ng-model="fromdate" id="dateFrom" placeholder="From date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                      </div>
                                    </div>
                                
                                  <div class="form-group" style="width:125px; float:left">
                                      <div class="input-group datecomp">
                                            <input type="text" class="form-control placholdercolor" ng-model="fromtime" id="timeFrom" placeholder="From time">
                                            <div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div>
                                      </div>
                                    </div>
                               </div>
                                    <div>
                                      <div class="form-group" style="width:140px; margin-right:10px;  float:left">
                                          <div class="input-group datecomp">
                                                <input type="text" class="form-control placholdercolor" ng-model="todate" id="dateTo" placeholder="To date">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                          </div>
                                        </div>
                                      <div class="form-group" style="width:125px; float:left ">
                                          <div class="input-group datecomp">
                                                <input type="text" class="form-control placholdercolor" ng-model="totime" id="timeTo" placeholder="To time" >
                                                <div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class=" speedbutt" id="animatecontrols" style="padding-top: 5px;" ng-init="speedval=100">
                                <label>Speed :</label>
                                <input name="anispeed" checked="checked" ng-click="speedchange()" ng-model="speedval" value="200" type="radio" /><span>Slow</span>
                                <input name="anispeed" type="radio" ng-click="speedchange()" ng-model="speedval" value="100" /><span>Normal</span>
                                <input name="anispeed" type="radio" ng-click="speedchange()" ng-model="speedval" value="20" /><span>Fast</span>
                                    <button ng-click="plotting()" style="width:75px;">Plot</button>
                              </div>
                              <div class=""  style="padding-top: 5px; float:left;">
                                    <button ng-click="playhis()" id="playButton" style="display:none"><i class="glyphicon glyphicon-play"></i></button>
                                    <button ng-click="pausehis()" id="pauseButton"><i class="glyphicon glyphicon-pause"></i></button>
                                    <button ng-click="animated()" id="replaybutton"><i class="glyphicon glyphicon-repeat"></i></button>
                                    <button ng-click="stophis()" id="stopButton"><i class="glyphicon glyphicon-stop"></i></button>
                                     
                                </div>
                                <div style="padding: 5px; float:left;">
                                        <label>Stops :</label>
                                        <select id="traffic" title="Suggested Stops" style="width: 120px;height: 25px;" ng-model="geoStops" ng-change="goeValueChange()" ng-options="geo.stopName as geo.stopName for geo in geoStop.geoFence">
                                           <!-- <option ng-repeat="geo in geoStop.geoFence" value="{{geo.stopName}}">{{geo.stopName}}</option> -->
                                        </select>
                                        <a ng-hide="true" href="../public/printStops?vName={{shortVehiId}}&vid={{trackVehID}}" target="_blank">Print</a>
                                        
                                </div>
                                   
                                <div>

                                    <h3 style="font-size:14px; clear:both; text-align:center;"><b>Vehicle Details</b></h3>
                                    
                                    <table cellpadding="0" cellspacing="0" class="dynData">
                                      <tbody>
                                             <tr>
                                              <td>odoDistance</td>
                                                <td id="vehdevtype"><h3><span></span> kms</h3></td>
                                                
                                            </tr>
                                            <tr>
                                                <td >Total Idle Time</td>
                                                <td id="vehstat"><h3>-</h3></td>
                                            </tr>
                                            <tr>
                                                <td>Total Running Time</td>
                                                <td id="toddist"> <h3>-</h3></td>
                                            </tr>
                                             <tr>
                                              <td>Total Parked Time</td>
                                                <td id="mobno"><h3></h3></td>
                                            </tr>
                                            <tr>
                                                <td><span></span> Trip Distance</td>
                                                <td id="regno"><h3><span></span> kms</h3> </td>
                                            </tr>
                                    </table>
                                </div>
                            <div class="latlong" style="bottom: 130px;width:275px; "><label><input type="text" value="0.0" id="latinput" style="width:265px"  readonly /></label></div>
                            <div id="lastseen"></div><div id="lstseendate"></div>
                        
                                <div class="legendlist">

                                  <h3><b>Vehicle Status</b></h3>
                                    <div>
                                      <table cellpadding="0" cellspacing="0">
                                          <tbody>
                                                <tr>
                                                  <td>Milestone</td>
                                                    <td><img src="assets/imgs/milestone.png"/></td>
                                                    <td>Idle</td>
                                                    <td><img src="assets/imgs/orange.png"/></td>
                                                </tr>
                                                 <tr>
                                                  <td>Parked</td>
                                                    <td><img src="assets/imgs/flag.png"/></td>
                                                    <td>Start</td>
                                                    <td><img src="assets/imgs/startflagico.png"/></td>
                                                </tr>
                                                 <tr>
                                                  <td>End</td>
                                                    <td><img src="assets/imgs/endflagico.png"/></td>
                                                    <td>No Data</td>
                                                    <td><img src="assets/imgs/gray.png"/></td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <map id="map_canvas"></map>
                            <!--input id="pac-input" class="controls" type="text" placeholder="Location Search"/-->
                            <div class="error" style="position:absolute; height:100%; background:#fff; top:0; left:0;  width:100%;" align="center">
                              <p style="padding:10px; background:#fff; margin-top:200px; display:inline-block;">No Data Found. Please select another date range</p>
                            </div>
                              <div class="bottomContent" style="display:none;">
                            <div class="row">
                            <div class="col-md-4 col-lg-2" align="center" id="vehiid">
                                    <h6>Vehicle ID</h6>
                                      <h3>-</h3>
                                  </div>
                                  <div class="col-md-2" align="center" id="vehdevtype">
                                    <h6>odoDistance</h6>
                                      <h3>-</h3>
                                  </div>
                                  <div class="col-md-2" align="center" id="vehstat">
                                    <h6>Total Idle Time</h6>
                                      <h3>-</h3>
                                  </div>
                                  
                                  <div class="col-md-4 col-lg-2" align="center" id="toddist">
                                    <h6>Total Running Time</h6>
                                      <h3>-</h3>
                                  </div>
                                  <div class="col-md-2" align="center" id="mobno">
                                    <h6>Total Parked Time</h6>
                                      <h3>-</h3>
                                  </div>
                                  <div class="col-md-2" align="center" id="regno">
                                    <h6>Trip Distance</h6>
                                      <h3><span>-</span>&nbsp;km</h3>
                                  </div>
                              </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
     
    <div class="modal fade" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Error</h4>
            </div>
          <div class="modal-body">
              <p>{{hisloc.error}}</p>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
            </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
   <!--  <script src="assets/js/static.js"></script>
    <script src="assets/js/jquery-1.11.0.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
    <script src="assets/js/ui-bootstrap-0.6.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=geometry,places" type="text/javascript"></script>
    <script src="assets/js/markerwithlabel.js"></script>
    <script src="assets/js/infobubble.js" type="text/javascript"></script>
    <script src="assets/js/moment.js" type="text/javascript"></script>
    <script src="assets/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
    <script src="assets/js/infobox.js"  type="text/javascript"></script>
    <script src="assets/js/vamoApp.js"></script>
    <script src="assets/js/unique.js"></script>
    <script src="assets/js/customplay.js"></script> -->
    <script>

 
var apikey_url = JSON.parse(sessionStorage.getItem('apiKey'));
var url = "https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places,geometry";

if(apikey_url != null || apikey_url != undefined)
        url = "https://maps.googleapis.com/maps/api/js?key="+apikey_url+"&libraries=places,geometry";
          url   = "https://maps.googleapis.com/maps/api/js?key="+apikey_url+"&libraries=places,geometry"

   function loadJsFilesSequentially(scriptsCollection, startIndex, librariesLoadedCallback) {
     if (scriptsCollection[startIndex]) {
       var fileref = document.createElement('script');
       fileref.setAttribute("type","text/javascript");
       fileref.setAttribute("src", scriptsCollection[startIndex]);
       fileref.onload = function(){
         startIndex = startIndex + 1;
         loadJsFilesSequentially(scriptsCollection, startIndex, librariesLoadedCallback)
       };
 
       document.getElementsByTagName("head")[0].appendChild(fileref)
     }
     else {
       librariesLoadedCallback();
     }
   }
 
   // An array of scripts you want to load in order
   var scriptLibrary = [];
   scriptLibrary.push("assets/js/jquery-1.11.0.js");
   scriptLibrary.push("assets/js/static.js");
   scriptLibrary.push("assets/js/bootstrap.min.js");
   scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js");
   scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js");
   scriptLibrary.push(url);
   scriptLibrary.push("assets/js/ui-bootstrap-0.6.0.min.js");
   // scriptLibrary.push("http://code.highcharts.com/highcharts.js");
   // scriptLibrary.push("http://code.highcharts.com/highcharts-more.js");
   // scriptLibrary.push("http://code.highcharts.com/modules/solid-gauge.js");
   scriptLibrary.push("assets/js/markerwithlabel.js");
   
   scriptLibrary.push("assets/js/moment.js");
   scriptLibrary.push("assets/js/bootstrap-datetimepicker.js");
   scriptLibrary.push("assets/js/infobubble.js");
   scriptLibrary.push("assets/js/infobox.js");
   scriptLibrary.push("assets/js/vamoApp.js");
   // scriptLibrary.push("assets/js/services.js");
   scriptLibrary.push("assets/js/customplay.js");



 
   // Pass the array of scripts you want loaded in order and a callback function to invoke when its done
   loadJsFilesSequentially(scriptLibrary, 0, function(){
       // application is "ready to be executed"
       // startProgram();
   });
   
    // $(document).ready(function(){
    //     $('#minmax').click(function(){
    //         $('#contentmin').animate({
    //             height: 'toggle'
    //         },500);
    //     });
    // });
    // $("#menu-toggle").click(function(e) {
    //   e.preventDefault();
    //   $("#wrapper").toggleClass("toggled");
    // });
    
    //     $(function () {
    //     $('#dateFrom, #dateTo').datetimepicker({
    //       format:'YYYY-MM-DD',
    //       useCurrent:true,
    //       pickTime: false
    //     });
    //     $('#timeFrom').datetimepicker({
    //       pickDate: false,
    //                 useCurrent:true,
    //     });
    //     $('#timeTo').datetimepicker({
    //       useCurrent:true,
    //       pickDate: false
    //     });
    //     });
        </script>
</body>

</html>
