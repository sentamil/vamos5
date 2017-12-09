<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Arun">
    <title>GPS</title>
    <link rel="shortcut icon" href="assets/imgs/tab.ico">
    <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:500|Roboto|Source+Sans+Pro|Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css"/>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/font-awesome-4.2.0/css/font-awesome.css" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">

        body{
           font-family: 'Lato', sans-serif;
         /*font-weight: bold;font-family: 'Lato', sans-serif;
           font-family: 'Roboto', sans-serif;
           font-family: 'Open Sans', sans-serif;
           font-family: 'Raleway', sans-serif;
           font-family: 'Faustina', serif;
           font-family: 'PT Sans', sans-serif;
           font-family: 'Ubuntu', sans-serif;
           font-family: 'Droid Sans', sans-serif;
           font-family: 'Source Sans Pro', sans-serif; */
          }

          #map_canvas {
            width: 100%;
            height:100vh; 
          }

          #map_canvas2 {
            width: 100%;
            height:100vh; 
          }

        .rightSection{position:absolute; top:70px; right:5px; width:275px; padding:10px; background:#fff; -webkit-border-radius: 12px; -moz-border-radius: 12px; border-radius: 12px; }
         div.pane  td{
          border:  0.5px solid #d9d9d9;
          padding:  2px;
          word-wrap: break-word;
         }

          #dropdowns{
            box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2); 
            position: absolute;
            z-index: 1;
            top: 10px;
            left: 130px; 
            cursor: pointer;
           }

    </style>
</head>
<body ng-app="mapApp">
    <div id="wrapper" ng-controller="mainCtrl" style="padding-left:0 !important;" class="ng-cloak">
        
        <div id="page-content-wrapper">

          <div  id="dropdowns">
            <select ng-model="mapsHist" ng-change="mapChanges(mapsHist)" style="font-size:12px; height: 26px; width: 64px; background: rgba(255, 255, 255, 0.9);border: none !important;">
                  <option value="0">Google</option>
                  <option value="1">Osm</a></option>
              </select>
            </div>

                        <div>
                            <map id="map_canvas"></map>
                        </div>

                        <div>
                            <maposm  id="map_canvas2"></maposm> 
                        </div>

            <div class="container-fluid">
                <div class="row">
                   
                    <div id="minmax1" style="position: absolute;top: 6px;right: 6px; z-index:999">
                            <img src="assets/imgs/add.png" />
                        </div>
                    <div id="contentreply">
                        
                        <div class="form-group form-inline" style="margin-bottom: 5px">
                            <div class="input-group datecomp">
                              <input type="button" id="traffic" ng-click="checkme('traffic')" value="Traffic" class="sizeInput"/>
                            </div>
                            <div class="input-group datecomp">
                              <input type="text" value="0.0" id="latinput" class="sizeInput" style="width:200px" readonly/>
                            </div>
                            <div class="input-group datecomp">
                                <input type="text" class="sizeInput" id="poival" placeholder="Point of Interest" ng-enter="enterkeypress()" /> 
                            </div>
                            <div class="input-group datecomp">
                                <button ng-click="enterkeypress()" class="sizeInput">Save</button>
                            </div>
                            
                        </div>
                        <div class="pane">
                            <table class="tables">
                            <thead>
                            </thead>
                                <tr>
                                  <td><b>VehicleName</b></td>
                                  <td>{{histVal[0].shortName}}</td>         
                                  <td><b>SpeedLimit</b></td>
                                  <td>{{histVal[0].overSpeedLimit}}</td>
                                </tr>
                                <tr>
                                    <td><b>Reg No</b></td>
                                    <td>{{histVal[0].regNo}}</td>
                                    <td><b>Analog Volt</b></td>
                                    <td>{{histVal[0].deviceVolt}}%</td>
                                </tr>
                            </table>
                        </div>

                        <div class="pane">
                         
                            <table class="tables">
                                <tr>
                                    <td width="15%">Date&amp;Time</td>
                                    <td width="10%">Odo km</td>
                                    <td width="20%" colspan="2">Position</td>
                              <!--  <td width="10%">Max km</td>  -->
                                    <td width="7%">Max</td>
                                    <td width="7%">Sat</td>
                                    <td width="35%">Address</td>
                                    <!-- <td width="10%">DeviceVolt</td> -->
                                    <td width="10%">G-Map</td>
                                </tr>
                                <tr ng-repeat="liveVal in histVal">
                                    <td>{{liveVal.date | date:'yy-MM-dd HH:mm:ss'}}</td>
                                    <td>{{liveVal.odoDistance }}</td>
                                    
                                    <td ng-switch on="liveVal.position">
                                        <span ng-switch-when="S">Standing</span>
                                        <span ng-switch-when="M">Moving</span>
                                        <span ng-switch-when="P">Parked</span>
                                        <span ng-switch-when="U">NoData</span>
                                    </td>
                                    <td ng-switch on="liveVal.position">
                                        <span ng-switch-when="S">{{timems(liveVal.idleTime)}}</span>
                                        <span ng-switch-when="M">{{timems(liveVal.movingTime)}}</span>
                                        <span ng-switch-when="P">{{timems(liveVal.parkedTime)}}</span>
                                        <span ng-switch-when="U">{{timems(liveVal.noDataTime)}}</span>
                                    </td>
                                    <td>{{liveVal.speed}}</td>
                                    <td>{{liveVal.gsmLevel}}</td>
                                    <td>{{liveVal.address }}</td>
                                    <!-- <td width="10%">{{liveVal.deviceVolt }}</td> -->
                                    <td><a href="https://www.google.com/maps?q=loc:{{liveVal.latitude}},{{liveVal.longitude}}" target="_blank">Link</a></td>
                                </tr>
                                <tr ng-if="histVal.length == 0">
                                    <td colspan="7" class="err"><h6>No Data Found!</h6></td>
                                </tr>
                            </table>
                        </div>
                        
                    <!--    <div class="pane">
                            <table class="tables">
                                <tr>
                                    <td>Moving</td>
                                    <td ><img src="assets/imgs/green.png"/></td>
                                    <td >Parked</td>
                                    <td ><img src="assets/imgs/flag.png"/></td>
                                </tr>
                                <tr>
                                    <td>Overspeed</td>
                                    <td align="center"><img src="assets/imgs/red.png"/></td>
                                    <td>Standing</td>
                                    <td align="center"><img src="assets/imgs/orange.png"/></td>
                                </tr>
                                 <tr>
                                    <td>Geo Fence</td>
                                    <td align="center"><img src="assets/imgs/blue.png"/></td>
                                    <td>No Data</td>
                                    <td align="center"><img src="assets/imgs/gray.png"/></td>
                                </tr>
                            </table>
                         
                        </div> -->
                    </div>
                    <div id="graphsId">
                        <div>
                            <div>Speed - <label id="speed"></label>&nbsp;Km/h</div>
                            <div id="container-speed"></div>
                        </div>
                        <div ng-show="vehiclFuel">
                            <div>Tank Size - <label id="fuel"></label>&nbsp;Ltr</div>
                            <div id="container-fuel"></div>
                        </div>
                    </div>
                    <!--div id="lastseentrack" style="top:0; height: auto; bottom:auto">&nbsp;</div-->
                   <!-- <div class="latlong"><label><input type="text"  style="width:265px" value="0.0" id="latinput" readonly/> POI : <input type="text" style="width:300px;" id="poival" placeholder="Enter Point of Interest" ng-enter="enterkeypress()" /> <button ng-click="enterkeypress()">Save</button></label><!--<label>Longitude : <input type="text" value="0.0" id="lnginput" readonly/></label></div>-->
                         <!-- div class="bottomContent">
                            <div class="row">
                                <div class="col-md-4 col-lg-2" align="center" id="vehiid">
                                    <h6>Vehicle ID - <span></span></h6>
                                    
                                </div>
                                <div class="col-md-2" align="center" id="vehdevtype">
                                    <h6>odoDistance - <span></span></h6>
                                </div>
                                 <div class="col-md-2" align="center" id="mobno">
                                    <h6>Speed Limit - <span></span></h6>
                                    
                                </div>
                                
                                <div class="col-md-4 col-lg-2" align="center">
                                    <div id="container-speed"></div>
                                </div>
                                <div class="col-md-4 col-lg-2" align="right" id="toddist">
                                    <h6 align="right" style="text-align:right;">&nbsp;Today Distance - <span><span></span>&nbsp;Km</span></h6>
                                   
                                </div>
                               
                                <div class="col-md-2" align="center" id="regno">
                                    <h6><p id="positiontime" style="display: inline; padding: 0; margin: 0;">Position</p> Time - <span></span></h6>
                                    
                                </div>
                            </div>
                        </div -->
                </div>
            </div>
        </div>
    </div>
   <!--  <script src="assets/js/static.js"></script>
    <script src="assets/js/jquery-1.11.0.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
    <script src="assets/js/ui-bootstrap-0.6.0.min.js"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/highcharts-more.js"></script>
    <script src="http://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places" type="text/javascript"></script>
    <script src="assets/js/markerwithlabel.js"></script>
    <script src="assets/js/infobox.js"  type="text/javascript"></script>
    <script src="assets/js/vamoApp.js"></script>
    <script src="assets/js/services.js"></script>
    <script src="assets/js/customtrack.js"></script> -->
    <script>
    


var apikey_url = JSON.parse(sessionStorage.getItem('apiKey'));
var url = "https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places";

if(apikey_url != null || apikey_url != undefined)
        url = "https://maps.googleapis.com/maps/api/js?key="+apikey_url+"&libraries=places";

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
   
   scriptLibrary.push("assets/js/static.js");
   scriptLibrary.push("assets/js/jquery-1.11.0.js");
   scriptLibrary.push("assets/js/bootstrap.min.js");
 /*scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js");*/
   scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular.min.js");
 //scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js");
   scriptLibrary.push(url);
 //scriptLibrary.push("https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places");
   scriptLibrary.push("http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"); 
   scriptLibrary.push("assets/js/ui-bootstrap-0.6.0.min.js");
/* scriptLibrary.push("https://code.highcharts.com/highcharts.js");
   scriptLibrary.push("https://code.highcharts.com/highcharts-more.js");
   scriptLibrary.push("https://code.highcharts.com/modules/solid-gauge.js"); */
   scriptLibrary.push("assets/js/highcharts_new.js");
   scriptLibrary.push("assets/js/highcharts-more_new.js");
   scriptLibrary.push("assets/js/solid-gauge_new.js");
   scriptLibrary.push("assets/js/markerwithlabel.js");
 //scriptLibrary.push("assets/js/infobubble.js");
 //scriptLibrary.push("assets/js/moment.js");
 //scriptLibrary.push("assets/js/bootstrap-datetimepicker.js");
   scriptLibrary.push("assets/js/infobox.js");
   scriptLibrary.push("assets/js/vamoApp.js");
   scriptLibrary.push("assets/js/services.js");
   scriptLibrary.push("assets/js/customtrack.js");

   // Pass the array of scripts you want loaded in order and a callback function to invoke when its done
   loadJsFilesSequentially(scriptLibrary, 0, function(){
       // application is "ready to be executed"
       // startProgram();
   });

    </script>
</body>
</html>