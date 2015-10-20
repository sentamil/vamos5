<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="Satheesh">
<title>VAMOS</title>
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/simple-sidebar.css" rel="stylesheet">
<link href="assets/font-awesome-4.2.0/css/font-awesome.css" rel="stylesheet">
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style type="text/css">
     #map_canvas{ width:100%; height:100%;}  
     #container-speed{  width: 240px; height: 120px;} 
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
<body ng-app="mapApp">
    <div ng-controller="mainCtrl">
       <div id="sidebar-wrapper" style="display: none">
             <ul class="sidebar-nav">
                <li class="sidebar-brand"><a href="javascript:void(0);"><img src="assets/imgs/logo.png"/></i></a></li>
                <li class="track"><a href="javascript:void(0);" class="active"><div></div><label>Track</label></a></li>
                <li class="history"><a href="../public/replay"><div></div><label>History</label></a></li>
                <li class="alert01"><a href="../public/reports"><div></div><label>Reports</label></a></li>
                <li class="stastics"><a href="../public/statistics"><div></div><label>Statistics</label></a></li>
                
                <!-- <li class="settings"><a href="../public/settings"><div></div><label>Settings</label></a></li> -->
               
            <li class="admin"><a href="../public/performance"><div></div><label>Performance</label></a></li>
                <li><a href="../public/logout"><img src="assets/imgs/logout.png"/><div></div><label>Logout</label></a></li>         
            </ul>
            <ul class="sidebar-subnav" style="max-height: 100vh; overflow-y: auto;" ng-init="vehicleStatus='ALL'">
                <li style="margin-bottom: 15px;"><div class="right-inner-addon" align="center"><i class="fa fa-search"></i><input type="search" class="form-control" placeholder="Search" ng-model="searchbox" name="search" /></div>
                <select ng-model="vehicleStatus" style="width:127px; margin-top: 5px; font-size: 12px;" ng-cloak>
                    <option value="ALL">All ({{totalVehicles}})</option>
                    <option value="ON">ON ({{vehicleOnline}})</option>
                    <option value="OFF">OFF ({{attention}})</option>
                    <option value="P">Parked ({{parkedCount}})</option>
                    <option value="M">Moving ({{movingCount}})</option>
                    <option value="S">Idle ({{idleCount}})</option>
                    <option value="O">Overspeed ({{overspeedCount}})</option>
                </select>
                  <!--  <span style="font-size: 12px; font-weight: bold;" ng-cloak><input type="radio" value="ALL" ng-model="vehicleStatus" name="vehicleStatus" class="radioButt" /> All ({{totalVehicles}}) &nbsp;</span>
                    <span style="font-size: 12px; font-weight: bold;" ng-cloak><input type="radio" value="ON" ng-model="vehicleStatus" name="vehicleStatus" class="radioButt"/> On <span style="color: green">({{vehicleOnline}}) &nbsp;</span></span>
                    <span style="font-size: 12px; font-weight: bold;" ng-cloak><input type="radio" value="OFF" ng-model="vehicleStatus" name="vehicleStatus" class="radioButt"/> Off <span style="color: red">({{attention}}) </span></span><br/>
                    <span style="font-size: 12px; font-weight: bold;" ng-cloak><input type="radio" value="P" ng-model="vehicleStatus" name="vehicleStatus" class="radioButt"/> Parked <span>({{parkedCount}}) </span></span> &nbsp; &nbsp;
                    <span style="font-size: 12px; font-weight: bold;" ng-cloak> <input type="radio" value="M" ng-model="vehicleStatus" name="vehicleStatus" class="radioButt"/> Moving <span>({{movingCount}}) </span></span><br/>
                    <span style="font-size: 12px; font-weight: bold;" ng-cloak><input type="radio" value="S" ng-model="vehicleStatus" name="vehicleStatus" class="radioButt"/> Idle <span>({{idleCount}}) </span></span> -->
               
                </li>
                <li ng-repeat="location in locations02"><a href="javascript:void(0);" ng-click="groupSelection(location.group, location.rowId)" ng-cloak >{{trimColon(location.group)}}</a>
                    <ul class="nav nav-second-level" style="max-height: 400px; overflow-y: auto;">
                        <li ng-repeat="loc in location.vehicleLocations | statusfilter:vehicleStatus | filter:searchbox " ng-class="{active:selected==$index}"><a href="javascript:void(0);" ng-class="{red:loc.status=='OFF'}" ng-click="genericFunction(loc.vehicleId, $index)" ng-cloak><img ng-src="assets/imgs/{{loc.vehicleType}}.png" fall-back-src="assets/imgs/Car.png" width="16" height="16"/> {{loc.vehicleId}} ({{loc.shortName}}) </a></li>
                    </ul>
                </li>
            </ul>  -->
        </div>
     <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="topContent" style="visibility: hidden">
                            <div class="row">
                                <div class="col-md-2" align="center" id="totalvehicles">
                                    <h6 ng-cloak>Total Vehicles - <span>( {{totalVehicles}} )</span></h6>
                                </div>
                                <div class="col-md-6 col-lg-2">
                                    <h6 ng-cloak>Vehicles Online - <span>( {{vehicleOnline}} )</span></h6>
                                </div>
                                <div class="col-md-2" id="totalalerts">
                                    <h6 ng-cloak>Alarms - <span>( {{alertstrack}} )</span></h6>
                                </div>
                                <div class="col-md-2" id="totalAttention">
                                    <h6 ng-cloak>Attention - <span>( {{attention}} )</span></h6>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <h6 ng-cloak>Overall Distance <span>( {{distanceCovered}} Km)</span></h6>
                                </div>
                            </div>
                        </div>


                        <div class="rightsection" style="display: none; position: absolute;top: 30px;right: 10px; z-index:999999; width: 250px;padding: 10px;background: #fff;-webkit-border-radius: 12px;-moz-border-radius: 12px;border-radius: 12px;">
                               <input type="button" id="traffic" ng-click="check()" value="Traffic" />
                               <input type="button" value="Measure" id="pac-input02" ng-click="distance();"/>
                               <input type="text" id="distanceVal" value="0" disabled/> <span style="font-size:12px;">Kms</span>
                               
                               <input type="button" value="Near by Vehicles" id="nearbyVeh" ng-click="nearBy();"/>
                               <div style="width:100%; background: #F4F4F4; font-weight: bold; font-size: 12px; padding : 3px" align="center">Live Tracking</div>
                               <div>
                               <select ng-model="days1">
                                    <option value = "0" label = "Vehicle Id"></option>
                                    <option ng-repeat="vehi in vehicle_list">{{vehi}}</option>
                                </select>
                                <select id="Days" ng-model="days">
                                    <option value = "0" label = "Days"></option>
                                    <option value = "1" label = "1"></option>
                                    <option value = "2" label = "2"></option>
                                    <option value = "3" label = "3"></option>
                                    <option value = "4" label = "4"></option>
                                    <option value = "5" label = "5"></option>
                                    <option value = "6" label = "6"></option>
                                    <option value = "7" label = "7"></option>
                                    <option value = "8" label = "8"></option>
                                    <option value = "9" label = "9"></option>
                                    <option value = "10" label = "10"></option>
                                </select>
                                <a ng-href= "#" ng-click="clicked(days1, days)">click</a>
                               </div>
                               <div class="nearbyTable">
                                <h3>NearBy Vehicles <a href="#" style="position:absolute; top:10px; right:10px;" ng-click="nearBy();">X</a></h3>
                                <h5 ng-cloak>Your Place - {{nearbyLocs.fromAddress}}</h5>   
                                <div>
                                    <table cellpadding="0" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th style="width:30%;">Vehicle ID</th>
                                                <th style="width:30%;">Distance</th>
                                                <th style="width:40%;">Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="nearbyLoc in nearbyLocs.vehicleDetails">
                                                <td ng-cloak>{{nearbyLoc.vehicleId}}({{nearbyLoc.shortName}})</td>
                                                <td align="right" ng-cloak>{{nearbyLoc.distance}}</td>
                                                <td ng-cloak>{{nearbyLoc.address}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                               <div class="dynamicvehicledetails" ng-init="dynamicvehicledetails1=false" ng-if="dynamicvehicledetails1==true">
                                    <h3 id="vehiid"><span></span> Details</h3>
                                    <div>
                                        <table cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td>odoDistance</td>
                                                    <td id="vehdevtype"><span></span> kms</td>
                                                    
                                                </tr>
                                                 <tr>
                                                    <td>Speed Limit</td>
                                                    <td id="mobno"><span></span> km/h</td>
                                                    
                                                </tr>
                                                 <tr>
                                                    <td>Today Distance</td>
                                                    <td id="toddist"><span></span> kms</td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td id="positiontime"><span></span> Time</td>
                                                    <td id="regno"><span></span></td>
                                                    
                                                </tr>
                                        </table>
                                    </div>
                                    <div id="rightAddress">
                                        <h3>Address</h3>
                                        <div align="center" id="lastseen"></div>
                                    </div>
                                    <h3>Last Seen Date & Time</h3>
                                    <div align="center" id="lstseendate">
                                       
                                    </div>
                                    <div id="container-speed"></div>
                                    <h3>Support details</h3>
                                    <div align="center">{{support}}</div>
                                </div>
                               
                                <div class="legendlist">
                                    <h3><b>Vehicle Status</b></h3>
                                    <div>
                                        <table cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td>Moving</td>
                                                    <td><img src="assets/imgs/green.png"/></td>
                                                    <td>Standing</td>
                                                    <td><img src="assets/imgs/orange.png"/></td>
                                                </tr>
                                                 <tr>
                                                    <td>Parked</td>
                                                    <td><img src="assets/imgs/flag.png"/></td>
                                                    <td>Geo Fence</td>
                                                    <td><img src="assets/imgs/blue.png"/></td>
                                                </tr>
                                                 <tr>
                                                    <td>Overspeed</td>
                                                    <td><img src="assets/imgs/red.png"/></td>
                                                    <td>No Data</td>
                                                    <td><img src="assets/imgs/gray.png"/></td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                        </div>
                        <a href="javascript:void(0);" class="contentexpand"><img src="assets/imgs/add.png"/></a>
                        <div id="maploc">
                            <map id="map_canvas" style="height:100vh; width:100%;"></map>
                            
                        </div>
                         
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Near By Vehicles</h4>
          </div>
          <div class="modal-body">
            <p>click location you want to find nearby Vehicles</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
          </div>
        </div>
      </div>
    </div>
    <script src="assets/js/static.js"></script>
    <script src="assets/js/jquery-1.11.0.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script> 
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/highcharts-more.js"></script>
    <script src="http://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places" type="text/javascript"></script>
    <script src="assets/js/markerwithlabel.js"></script>
    <script src="assets/js/infobubble.js"  type="text/javascript"></script>
    <script src="assets/js/infobox.js"  type="text/javascript"></script>
    <script src="assets/js/vamoApp.js"></script>
    <script src="assets/js/services.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    $(document).ready(function(){
        if(navigator.appVersion.indexOf("MSIE")!=-1){
            document.body.style.zoom="90%";
        }
    });
    </script>
</body>
</html>
