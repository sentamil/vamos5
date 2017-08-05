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
<link rel="stylesheet" href="assets/css/popup.bootstrap.min.css">
<link href="assets/css/jVanilla.css" rel="stylesheet">
<link href="assets/css/simple-sidebar.css" rel="stylesheet">
<link href="assets/font-awesome-4.2.0/css/font-awesome.css" rel="stylesheet">
<link href="assets/ui-drop/select.min.css" rel="stylesheet">

<style type="text/css">

#container, #speedGraph{
  max-width: 480px !important;
  max-height: 200px !important;
  min-width: 480px; height: 200px; margin: 0 auto
}
.box > .loading-img, .loading-img,#status, #status02{
   width: 1px !important;height: 1px !important;z-index: 9999;position: absolute;left: 50%;top: 50%;background-image: url(../imgs/loading.gif);background-repeat: no-repeat;background-position: center;margin: -100px 0 0 -100px; border-radius: 3px;
}
</style>

</head>

<!-- <div id="preloader" > -->
     <div id="status" >

          <div class="showLoading">
          <h2>Please Wait.. it will take few seconds..</h2><svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
  <path fill="#000" d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z">
    <animateTransform attributeType="xml"
      attributeName="transform"
      type="rotate"
      from="0 25 25"
      to="360 25 25"
      dur="0.6s"
      repeatCount="indefinite"/>
    </path>
  </svg>
          </div>
    </div>

<!-- </div> -->
<!-- <div id="preloader02" >
       <div id="status02">&nbsp;</div>
     </div> -->

<body ng-controller="mainCtrl" class="ng-cloak">
  <div id="page-content-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div id="minmax1">
          <img src="assets/imgs/add.png" />
        </div>
        <div id="contentreply">
          <div class="bg_bright">
            <table cellpadding="0" cellspacing="0" class="dynData">
              <tbody>
                <tr>
                  <td style="font-weight:bold;padding-left:10px;">Vehicle Name</td>
                  
               <!-- <td id="vehiid" style="font-weight:bold !important;"><h3></h3></td> -->
                    <td style="float:left;padding-left:10px;"> 
                       <!-- <select ng-model="trackVehID"  ng-change="showPlot(totime,todate,fromtime,fromdate)" style="background-color:#f9f9f9;padding:0 0 0 3px;min-width:110px;max-width:110px;">
                              <option ng-repeat="vehi in vehicle_list" value="{{vehi.vehiID}}">{{vehi.vName}}</option>
                            </select>  -->

                            <ui-select ng-model="vName.selected" ng-change="callValue(vName.selected)" theme="bootstrap" ng-disabled="disabled" style="width:200px;">
                                <ui-select-match placeholder="{{shortVehiId}}">{{$select.selected.vName}}</ui-select-match>
                                <ui-select-choices  repeat="vehi in vehicle_list | filter: $select.search" style="overflow-x:auto;font-size:12px;">
                                  <span ng-bind="vehi.vName | highlight: $select.vName"></span>
                                </ui-select-choices>
                            </ui-select>
                    </td>

                  <td style="float:right;padding-top:12px;padding-right:40px;"><b>Total Dist</b><span style="float:right;padding-left:8px;" ng-if="hisloc.tripDistance > 1 && hisloc.tripDistance != null">{{hisloc.tripDistance}} <span style="padding-left:5px;"><b>kms</b></span></span><span style="float:right;padding-left:10px;" ng-if="hisloc.tripDistance < 1 && hisloc.tripDistance != null">{{hisloc.tripDistance}} <span style="padding-left:5px;"><b>km</b></span></span><span style="float:right;padding-left:10px;" ng-if="hisloc.tripDistance == null">0<span style="padding-left:5px;"><b>km</b></span></span></td>
                </tr>

              </tbody>
            </table>
          </div>
          <div class="form-group form-inline" style="margin-bottom: 5px">
            <div class="input-group datecomp">
              <input type="text" class="sizeInput" style="" ng-model="fromdate" ng-change="showPlot(totime,todate,fromtime,fromdate)" id="dateFrom" placeholder="From date">
                 <!-- <div class="input-group-addon"><i class="fa fa-calendar"></i></div> -->
            </div>
            <div class="input-group datecomp">
              <input type="text" class="sizeInput" ng-model="fromtime" ng-change="showPlot(totime,todate,fromtime,fromdate)" id="timeFrom" placeholder="From time">
            </div>
            <div class="input-group datecomp">
              <input type="text" class="sizeInput"  ng-model="todate" ng-change="showPlot(totime,todate,fromtime,fromdate)" id="dateTo" placeholder="To date">
              <!-- <div class="input-group-addon"><i class="fa fa-calendar"></i></div> -->
            </div>
            <div class="input-group datecomp">
              <input type="text" class="sizeInput" ng-model="totime"  ng-change="showPlot(totime,todate,fromtime,fromdate)" id="timeTo" placeholder="To time" >
              <!-- <div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div> -->
            </div>
            <div class="input-group ">
              <button ng-click="plotting()" ng-disabled="hideButton" class="sizeInput btn btn-success" ng-class="{'diabled-class': !PayoutEnabled}" style="font-weight:bold;">Plot</button>
            </div>
          </div>

          <div class="form-group form-inline" style="margin-bottom: 5px" >

            <div class="input-group " style="padding: 2% !important;"> 
              <button class="sizeInput btn btn-success" ng-click="plotting(1,'5hr')"  ng-disabled="btn5Hrs"  showPlot ng-class="{'diabled-class': !PayoutEnabled}">5 Hours </button>
            </div>

            <div class="input-group " style="padding: 2% !important;">
              <button class="sizeInput btn btn-info"  ng-click="plotting(2,'12hr')" ng-disabled="btn12Hrs" ng-disabled="btn12Hrs" ng-class="{'diabled-class': !PayoutEnabled}">12 Hours </button>
            </div>

            <div class="input-group" style="padding: 2% !important;">
              <button class="sizeInput btn btn-warning"  ng-click="plotting(3,'24hr')" ng-disabled="btn1Day" ng-class="{'diabled-class': !PayoutEnabled}">1 Day </button>
            </div>

            <div class="input-group" style="padding: 2% !important;">
              <button class="sizeInput btn btn-danger"  ng-click="plotting(4,'yes')" ng-disabled="btn2Day" ng-class="{'diabled-class': !PayoutEnabled}">2 Days </button>
            </div>

          </div>
                          
    <!--    <div class="form-group form-inline" style="margin-bottom: 5px">
             <div class="input-group ">
              <label>Stops&nbsp;</label>
              <select id="traffic" title="Suggested Stops" style="width: 80px;height: 25px;" ng-model="geoStops" ng-change="goeValueChange()" ng-options="geo.stopName as geo.stopName for geo in geoStop.geoFence"></select>
              <a ng-hide="true" href="../public/printStops?vName={{shortVehiId}}&vid={{trackVehID}}" target="_blank">Print</a>
            </div>

            <div class="input-group">
              <button data-target="#myModal1" data-toggle="modal" ng-click="getOrd()" class="sizeInput">Routes</button> -->
              <!-- <button ng-click="hideShowTable()" id="btnValue">ShowDetails</button> -->
       <!-- </div>

          </div> -->

           <div>
                    
            <ul class='tabs'>
              <li ng-click="addressResolve('all')" ><a href='#all'>All</a></li>
              <li ng-click="addressResolve('movement')" ><a href='#movement'>Movement</a></li>
         <!-- <li ng-click="addressResolve('movement')"><a href='#speed'>Speed</a></li>
              <li ng-click="addressResolve('overspeed')"><a href='#overspeed'>OverSpeed</a></li> -->
              <li ng-click="addressResolve('parked')" ><a href='#parked'>Parked</a></li>
              <li ng-click="addressResolve('idle')" ><a href='#idle'>Idle</a></li>
         <!-- <li ng-click="addressResolve('fuel')"><a href='#fuel'>Fuel</a></li> -->
              <li ng-click="addressResolve('ignition')" ><a href='#ignition'>Ignition</a></li>
         <!-- <li ng-click="addressResolve('acc')"><a href='#acc'>A/C</a></li> -->
              <li ng-click="addressResolve('stoppage')" ><a href='#stoppage'>Stoppage</a></li>
            </ul>

            <!-- movement -->
            <div id='all' class="pane">
      
              <table class="tables">
                
                <tr style="border-top: 1px solid #d9d9d9">
                  <td width="15%">Date&amp;Time</td>
                  <td width="10%">Max (kmph)</td>
                  <td width="7%">Sat</td>
                  <td width="37%">Address</td>
                  <td width="8%">Direction</td>
                  <td width="10%">G-Map</td>
                  <!-- <td width="7%">Dist</td> -->
                  <td width="10%">C-Dist (kms)</td>
                  <td width="12%">Odo (kms)</td>
                </tr>
                <tr ng-repeat="all in allData" ng-click="markerPoup(all)">
                  <td>{{all.date | date:'yy-MM-dd HH:mm:ss'}}</td>
                  <td>{{all.speed}}</td>
                  <td>{{all.gsmLevel}}</td>
                  <td>
                    <p ng-if="all.address!=null">{{all.address}}</p>
                    <p ng-if="all.address==null && alladdress[$index]!=null">{{alladdress[$index]}}</p>
                    <p ng-if="all.address==null && alladdress[$index]==null"><img src="assets/imgs/loader.gif" align="middle"></p>
                  </td>
                  <td>{{all.direction}}</td>
                  <td><a href="https://www.google.com/maps?q=loc:{{all.latitude}},{{all.longitude}}" target="_blank">Link</a></td>
                  <!-- <td>{{move.tmpDistance}}</td> -->
                  <td>{{all.distanceCovered}}</td>
                  <td>{{all.odoDistance}}</td>
                </tr>
                <tr ng-if="allData.length == 0 || allData == undefined">
                  <td colspan="8" class="err"><h6>No Data Found! Choose some other date</h6></td>
                </tr>
              </table>
            </div>

               <!-- movement -->
            <div id='movement' class="pane">
         
              <table class="tables">
                <tr style="border-top: 1px solid #d9d9d9">
                  <td width="15%">Date&amp;Time</td>
                  <td width="10%">Max (kmph)</td>
                  <td width="7%">Sat</td>
                  <td width="37%">Address</td>
                  <td width="8%">Direction</td>
                  <td width="10%">G-Map</td>
                  <!-- <td width="7%">Dist</td> -->
                  <td width="10%">C-Dist (kms)</td>
                  <td width="18%">Odo (kms)</td>
                </tr>
                <tr ng-repeat="move in movementdata" ng-click="markerPoup(move)">
                  <td>{{move.date | date:'yy-MM-dd HH:mm:ss'}}</td>
                  <td>{{move.speed}}</td>
                  <td>{{move.gsmLevel}}</td>
                  <td>
                    <p ng-if="move.address!=null">{{move.address}}</p>
                    <p ng-if="move.address==null && moveaddress[$index]!=null">{{moveaddress[$index]}}</p>
                    <p ng-if="move.address==null && moveaddress[$index]==null"><img src="assets/imgs/loader.gif" align="middle"></p>
                  </td>
                  <td>{{move.direction}}</td>
                  <td><a href="https://www.google.com/maps?q=loc:{{move.latitude}},{{move.longitude}}" target="_blank">Link</a></td>
                  <!-- <td>{{move.tmpDistance}}</td> -->
                  <td>{{move.distanceCovered}}</td>
                  <td>{{move.odoDistance}}</td>
                </tr>
                <tr ng-if="movementdata.length == 0 || movementdata == undefined">
                  <td colspan="6" class="err"><h6>No Data Found! Choose some other date</h6></td>
                </tr>
              </table>
            </div>

            <!-- speed report -->

      <!-- <div id='speed' class="pane">
             <div id="speedGraph"></div>
              <table class="tables">
                <tr>
                  <td colspan="2">Vehicle Name</td>
                  <td colspan="2">{{hisloc.shortName}}</td>
                  <td colspan="2">Speed Limit(kmph)</td>
                  <td>{{hisloc.overSpeedLimit}}</td>         
                </tr>
                <!-- <tr class="gap">
                  <td colspan="2">Regn No</td>
                  <td> {{hisloc.regNo}}</td>
                  <td colspan="2">Speed Limit</td>
                  <td colspan="2"> {{hisloc.overSpeedLimit}}</td>
                </tr> -->
                
         <!--     </table>
              
              <table class="tables">
                
                <tr style="border-top: 1px solid #d9d9d9">
                  <td width="20%">Date&amp;Time</td>
                  <td width="20%">Max (kmph)</td>
                  <td width="7%">Sat</td>
                  <td width="50%">Address</td>
                  <td width="10%">G-Map</td>
                </tr>
                <tr ng-repeat="move in movementdata" ng-click="markerPoup(move)">
                  <td>{{move.date | date:'yy-MM-dd HH:mm:ss'}}</td>
                  <td>{{move.speed}}</td>
                  <td>{{move.gsmLevel}}</td>
                  <td>
                    <p ng-if="move.address!=null">{{move.address}}</p>
                    <p ng-if="move.address==null && moveaddress[$index]!=null">{{moveaddress[$index]}}</p>
                    <p ng-if="move.address==null && moveaddress[$index]==null"><img src="assets/imgs/loader.gif" align="middle"></p>
                  </td>
                  <td><a href="https://www.google.com/maps?q=loc:{{move.latitude}},{{move.longitude}}" target="_blank">Link</a></td>
                 <!--  <td>{{move.tmpDistance}}</td>
                  <td>{{move.distanceCovered}}</td>
                  <td>{{move.odoDistance}}</td> -->
          <!--      </tr>
                <tr ng-if="movementdata.length == 0 || movementdata == undefined">
                  <td colspan="4" class="err"><h6>No Data Found! Choose some other date</h6></td>
                </tr>
              </table>
            </div> -->

            <!-- overspeed -->
      <!--      <div id='overspeed' class="pane">
              <table class="tables">
                <tr>
                  <td colspan="2">Vehicle Group</td>
                  <td>{{trimColon(groupname)}}</td>
                  <td colspan="2">Vehicle Name</td>
                  <td colspan="2">{{hisloc.shortName}}</td>         
                </tr>
                <tr class="gap">
                  <td colspan="2">Regn No</td>
                  <td> {{hisloc.regNo}}</td>
                  <td colspan="2">Speed Limit (kmph)</td>
                  <td colspan="2"> {{hisloc.overSpeedLimit}}</td>
                </tr>
                  
              </table>
              <table class="tables">

                <tr>
                  <td width="15%">Date&amp;Time</td>
                  <td width="15%">Speed (kmph)</td>
                  <td width="7%">Sat</td>
                  <td width="25%">Address</td>
                  <td width="10%">G-Map</td>
                  <td width="15%">Duration (h:m:s)</td>
                  <td width="10%">Trip (kms)</td>
                  <td width="10%">Odo (kms)</td>
                </tr>
                
                <tr ng-repeat="over in overspeeddata" ng-click="markerPoup(over)">
                  <td>{{over.date | date:'yy-MM-dd HH:mm:ss'}}</td>
                  <td>{{over.speed}}</td>
                  <td>{{over.gsmLevel}}</td>
                  <td>
                    <p ng-if="over.address!=null">{{over.address}}</p>
                    <p ng-if="over.address==null && overaddress[$index]!=null">{{overaddress[$index]}}</p>
                    <p ng-if="over.address==null && overaddress[$index]==null"><img src="assets/imgs/loader.gif" align="middle"></p>
                  </td>
                  <td><a href="https://www.google.com/maps?q=loc:{{over.latitude}},{{over.longitude}}" target="_blank">Link</a></td>
                  <td>{{msToTime(over.movingTime)}}</td>
                  <td>{{over.odoDistance}}</td>
                  <td>{{over.tripDistance}}</td>
                </tr>
                <tr ng-if="overspeeddata.length == 0 || overspeeddata == undefined">
                  <td colspan="7" class="err"><h6>No Data Found! Choose some other date</h6></td>
                </tr>
              </table>
            </div> -->

            <!-- parked -->
            <div id='parked' class="pane">
              <table class="tables">
                <tr>
                  <td>Vehicle Group</td>
                  <td>{{trimColon(groupname)}}</td>
                  <td>Vehicle Name</td>
                  <td>{{hisloc.shortName}}</td>         
                </tr>
                  
              </table>
              <table class="tables">

                <tr>
                  <td width="15%">Date&amp;Time</td>
                  <td width="15%">Duration (h:m:s)</td>
                  <td width="7%">Sat</td>
                  <td width="60%">Address</td>
                  <td width="8%">Direction</td>
                  <td width="10%">G-Map</td>
                </tr>
                
                <tr ng-repeat="park in parkeddata" ng-click="markerPoup(park)">
                  <td>{{park.date | date:'yy-MM-dd HH:mm:ss'}}</td>
                  <td>{{msToTime(park.parkedTime)}}</td>
                  <td>{{park.gsmLevel}}</td>
                  <td>
                    <p ng-if="park.address!=null">{{park.address}}</p>
                    <p ng-if="park.address==null && parkaddress[$index]!=null">{{parkaddress[$index]}}</p>
                    <p ng-if="park.address==null && parkaddress[$index]==null"><img src="assets/imgs/loader.gif" align="middle"></p>
                  </td>
                  <td>{{park.direction}}</td>
                  <td><a href="https://www.google.com/maps?q=loc:{{park.latitude}},{{park.longitude}}" target="_blank">Link</a></td>
                </tr>
                <tr ng-if="parkeddata.length == 0 || parkeddata == undefined">
                  <td colspan="4" class="err"><h6>No Data Found! Choose some other date</h6></td>
                </tr>
              </table>
            </div>

            <!-- idle -->
            <div id='idle' class="pane">
                <table class="tables">
                  <tr>
                    <td>Vehicle Group</td>
                    <td>{{trimColon(groupname)}}</td>
                    <td>Vehicle Name</td>
                    <td>{{hisloc.shortName}}</td>         
                  </tr>
                    
                </table>
                <table class="tables">

                  <tr>
                    <td width="15%">Date&amp;Time</td>
                    <td width="15%">Duration (h:m:s)</td>
                    <td width="7%">Sat</td>
                    <td width="60%">Address</td>
                    <td width="8%">Direction</td>
                    <td width="10%">G-Map</td>
                  </tr>
                  
                  <tr ng-repeat="idle in idlereport" ng-click="markerPoup(idle)">
                    <td>{{idle.date | date:'yy-MM-dd HH:mm:ss'}}</td>
                    <td>{{msToTime(idle.idleTime)}}</td>
                    <td>{{idle.gsmLevel}}</td>
                    <td>
                      <p ng-if="idle.address!=null">{{idle.address}}</p>
                      <p ng-if="idle.address==null && idleaddress[$index]!=null">{{idleaddress[$index]}}</p>
                      <p ng-if="idle.address==null && idleaddress[$index]==null"><img src="assets/imgs/loader.gif" align="middle"></p>
                    </td>
                    <td>{{idle.direction}}</td>
                    <td><a href="https://www.google.com/maps?q=loc:{{idle.latitude}},{{idle.longitude}}" target="_blank">Link</a></td>
                  </tr>
                  <tr ng-if="idlereport.length == 0 || idlereport == undefined">
                  <td colspan="4" class="err"><h6>No Data Found! Choose some other date</h6></td>
                </tr>
                </table>
            </div>

            <!-- fuel report -->
        <!--    <div id='fuel' class="pane">
              <div id="container"></div>
              <table class="tables">
                <tr>
                  <td width="15%">Date &amp; Time</td>
                  <td width="15%">Fuel (ltr)</td>
                  <td width="7%">Sat</td>
                  <td width="60%">Nearest Location</td>
                  <td width="10%">G-Map</td>
                </tr>   
                <tr ng-repeat="fuelR in fuelValue" ng-click="markerPoup(fuelR)">
                  <td>{{fuelR.date | date:'yy-MM-dd HH:mm:ss'}}</td>
                  <td>{{fuelR.fuelLitre}}</td>
                  <td>{{fuelR.gsmLevel}}</td>
                  <td>
                      <p ng-if="fuelR.address!=null">{{fuelR.address}}</p>
                      <p ng-if="fuelR.address==null && fueladdress[$index]!=null">{{fueladdress[$index]}}</p>
                      <p ng-if="fuelR.address==null && fueladdress[$index]==null"><img src="assets/imgs/loader.gif" align="middle"></p>
                    </td>
                  <td><a href="https://www.google.com/maps?q=loc:{{fuelR.latitude}},{{fuelR.longitude}}" target="_blank">Link</a></td>
                </tr>
                <tr ng-if="fuelValue.length == 0 || fuelValue == undefined">
                  <td colspan="4" class="err"><h6>No Data Found! Choose some other date</h6></td>
                </tr>
              </table>
            </div> -->

            <!-- ignition -->
            <div id='ignition' class="pane">
              <table class="tables">
                <tr>
                  <td>Vehicle Group</td>
                  <td>{{trimColon(groupname)}}</td>
                  <td>Vehicle Name</td>
                  <td>{{hisloc.shortName}}</td>         
                </tr>
                  
              </table>
              <table class="tables">
                
                  <tr>
                    <td width="15%">Date &amp; Time</td>
                    <td width="10%">Status</td>
                    <td width="15%">Duration (h:m:s)</td>
                    <td width="7%">Sat</td>
                    <td width="50%">Nearest Location</td>
                    <td width="8%">Direction</td>
                    <td width="10%">G-Map</td>
                </tr>
                <tr ng-repeat="ignition in ignitValue" ng-click="markerPoup(ignition)">
                  <td>{{ignition.date | date:'yyyy-MM-dd HH:mm:ss'}}</td>
                  <td>{{ignition.ignitionStatus}}</td>
                  <td rowspan="2" ng-if="ignition.ignitionStatus == 'ON'">{{msToTime(ignitValue[$index+1].date-ignition.date)}}</td>
                  <td>{{ignition.gsmLevel}}</td>
                  <td>
                    <p ng-if="ignition.address!=null">{{ignition.address}}</p>
                    <p ng-if="ignition.address==null && igniaddress[$index]!=null">{{igniaddress[$index]}}</p>
                    <p ng-if="ignition.address==null && igniaddress[$index]==null"><img src="assets/imgs/loader.gif" align="middle"></p>
                  </td>
                  <td>{{ignition.direction}}</td>
                  <td><a href="https://www.google.com/maps?q=loc:{{ignition.latitude}},{{ignition.longitude}}" target="_blank">Link</a></td>
                </tr>
                <tr ng-if="ignitValue.length == 0 || ignitValue == undefined">
                  <td colspan="5" class="err"><h6>No Data Found! Choose some other date</h6></td>
                </tr>
              </table>
             
            </div>

            <!-- ac report -->
     <!--       <div id='acc' class="pane">
              <table class="tables">
                <tr>
                  <td>Vehicle Group</td>
                  <td>{{trimColon(groupname)}}</td>
                  <td>Vehicle Name</td>
                  <td>{{hisloc.shortName}}</td>         
                </tr>
                  
              </table>
              <table class="tables">
                <tr>
                  <td width="15%">Date &amp; Time</td>
                  <td width="10%">Status</td>
                  <td width="15%">Duration (h:m:s)</td>
                  <td width="7%">Sat</td>
                  <td width="50%">Nearest Location</td>
                  <td width="10%">G-Map</td>
                </tr>
                <tr ng-repeat="acc in acReport" ng-click="markerPoup(acc)">
                  <td>{{acc.date | date:'yyyy-MM-dd HH:mm:ss'}}</td>
                  <td>
                    <span ng-if="acc.vehicleBusy == 'yes'">ON</span>
                    <span ng-if="acc.vehicleBusy == 'no'">OFF</span>
                  </td>
                  <td rowspan="2" ng-if="acc.vehicleBusy == 'yes'">{{msToTime(acReport[$index+1].date-acc.date)}}</td>
                  <td>{{acc.gsmLevel}}></td>
                  <td>
                    <p ng-if="acc.address!=null">{{acc.address}}</p>
                    <p ng-if="acc.address==null && acc_address[$index]!=null">{{acc_address[$index]}}</p>
                    <p ng-if="acc.address==null && acc_address[$index]==null"><img src="assets/imgs/loader.gif" align="middle"></p>
                  </td>
                  <td><a href="https://www.google.com/maps?q=loc:{{add.latitude}},{{add.longitude}}" target="_blank">Link</a></td>
                </tr>
                <tr ng-if="acReport.length == 0 || acReport == undefined">
                  <td colspan="5" class="err"><h6>No Data Found! Choose some other date</h6></td>
                </tr>
              </table>
            </div> -->
            
            <!-- stoppage report -->
            <div id='stoppage' class="pane">
              <table class="tables">
                <tr>
                  <td>Vehicle Group</td>
                  <td>{{trimColon(groupname)}}</td>
                  <td>Vehicle Name</td>
                  <td>{{hisloc.shortName}}</td>         
                </tr>
                  
              </table>
              <table class="tables">
                 <tr>
                  <td width="15%">Start Time</td>
                  <td width="15%">End Time</td>
                  <td width="15%">Duration (h:m:s)</td>
                  <td width="7%">Sat</td>
                  <td width="45%">Address</td>
                  <td width="8%">Direction</td>
                  <td width="10%">G-Map</td>
                </tr>

                <tr ng-repeat="stop in stopReport" ng-click="markerPoup(stop)">

                  <td>{{stop.date | date:'yy-MM-dd HH:mm:ss'}}</td>
                  <td>{{stop.endStoppageTime | date:'yy-MM-dd HH:mm:ss'}}</td>
                  <td>{{msToTime(stop.stoppageTime)}}</td>
                  <td>{{stop.gsmLevel}}</td>
                  <td>
                    <p ng-if="stop.address!=null">{{stop.address}}</p>
                    <p ng-if="stop.address==null && stop_address[$index]!=null">{{stop_address[$index]}}</p>
                    <p ng-if="stop.address==null && stop_address[$index]==null"><img src="assets/imgs/loader.gif" align="middle"></p>
                  </td>
                  <td>{{stop.direction}}</td>
                  <td><a href="https://www.google.com/maps?q=loc:{{stop.latitude}},{{stop.longitude}}" target="_blank">Link</a></td>
                </tr>

                <tr ng-if="stopReport.length == 0 || stopReport == undefined">
                  <td colspan="5" class="err"><h6>No Data Found! Choose some other date</h6></td>
                </tr>
              </table>
            </div>  

          </div>


        <div class="form-group form-inline" style="margin-bottom: 5px;padding-left: 10px;">
            <div class="input-group">
              <button ng-click="playhis()" id="playButton" style="display:none"><i class="glyphicon glyphicon-play"></i></button>
              <button ng-click="pausehis()" id="pauseButton"><i class="glyphicon glyphicon-pause"></i></button>
              <button ng-click="replays()" id="replayButton"><i class="glyphicon glyphicon-repeat"></i></button>
              <button ng-click="stophis()" id="stopButton"><i class="glyphicon glyphicon-stop"></i></button>
            </div>

            <div class="input-group speedbutt hideClass" id="animatecontrols" ng-init="timeDelay=180" style="padding-left:30px;">
              <!-- <label>Speed :</label> -->
              <input name="anispeed" checked="checked" ng-click="speedchange()" ng-model="timeDelay" value="180" type="radio" />
              <span>Slow</span>
              <input name="anispeed" type="radio" ng-click="speedchange()" ng-model="timeDelay" value="100" />
              <span>Normal</span>
              <input name="anispeed" type="radio" ng-click="speedchange()" ng-model="timeDelay" value="20" />
              <span>Fast</span>
            </div>
           
           <div class="input-group" style="float:right;padding-right:10px;">
              <button data-target="#myModal1" data-toggle="modal" ng-click="getOrd()" class="sizeInput">Routes</button> 
              <!-- <button ng-click="hideShowTable()" id="btnValue">ShowDetails</button> -->
         </div>

        </div>

        <div class="modal fade" id="myModal1" role="dialog" data-backdrop="false" style=" top: 70px;">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header" style="height:45px;padding-top:7px;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4> Save Route </h4> 
                </div>
                <div class="modal-body" >
                  <label for="error" style="color: #ec0808">{{error}}</label>
                  <div style="float:left;">      
                    <input type="text" class="form-control" ng-model="routeName" placeholder="Enter Route Name" style="width:470px; height: 30px">
                  </div>   
                  <div style="float: right; padding-right:20px">
                    <button type="button" ng-click="routesSubmit()" class="btn btn-default"  style="height:30px;text-align: center;padding-top:5px; " >Save</button>
                  </div>
                  
                </div>
                <br>
                <div class="panel-body" style="height: 275px; overflow: scroll; border: 1px solid #f4f4f4">
                    <table class="dynData">
                      <thead>
                        <tr style="padding: 5px; background-color: #f4f4f4">
                          <th>&nbsp;Routes Name</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tr ng-repeat="route in routedValue">
                        <td ng-click="getMap(route)"><a  data-target="#myModal2" data-toggle="modal" style="cursor: pointer;">{{route}}</a></td>
                        <td id="editAction"><a class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span> </a></td>
                        <td ng-click="deleteRouteName(route)"><a class="btn btn-xs btn-default"><span class="glyphicon glyphicon-trash"></span></a></td>
                      </tr>
                    </table>
                   </div>
                  </div>
                </div>
              </div>
            </div>  

            <style type="text/css" ng-if ="hideMe">
                  
                  .labels{
                       visibility: hidden !important;
                  }
            </style>

            <style type="text/css" ng-if="hideMarkerVal">
                   
                img[src="assets/imgs/flag.png"],img[src="assets/imgs/orange.png"]{

                  visibility: hidden !important; 
                }
                
                .h2,h2{
                    font-size: 18px !important;
                   }
            </style>
                            <!-- <div class="row"> -->
                            <!-- class="col-lg-7" style=" width: 70%; float: left; margin-left: 10px; margin-right: 10px" -->
                              <!-- <div> -->
                                <map on-load-callback="callMapFun()" id="map_canvas"></map>
                              <!-- </div> -->


                              <!-- <div class="col-lg-4" style="background-color: #196481; position: relative; float: right; height: 100vh; width: 28% !important; ">
                                
                              </div> -->
                            <!-- </div> -->
                            <!--input id="pac-input" class="controls" type="text" placeholder="Location Search"/-->
                            <div class="error" style="position:absolute; height:100%; background:#fff; top:0; left:0;  width:100%;" align="center">
                              <span style="padding:10px; background:#fff; margin-top:200px; display:inline-block; font-size: 12px !important;" >No Data Found. Please select another date range</span>
                            </div>
                            
                            <div style="position: fixed;top: 10px;left: 150px; z-index:99; background-color: #fff; padding:2px; border-radius: 2px; cursor: pointer;" class="viewList">

                            <img src="assets/imgs/add.png" />
                            
                            <label><input type="text" value="0.0" id="latinput" style="width:120px; height: 20px;"  readonly /></label>
                            
                        </div>
                        
                        <div class="radioBut">
                           <label> <input type="checkbox" name="track" ng-model="polylineCheck" ng-click="polylineCtrl()">
                             <b>Load All</b>
                           </label>
                      <!-- <label>
                        <input type="radio" name="track" >
                        stepbystep  
                      </label> -->
                      </div>

                  <div class="radioButts">

                     <label><input type="checkbox" name="track" ng-change="getValueCheck(SiteCheckbox.value1)" ng-model="SiteCheckbox.value1"  ng-true-value="'YES'" ng-false-value="'NO'" >
                       <b> Site</b>
                     </label>
                  </div>

                  <div class="radioButts2">
                       <label> 
                          <input type="checkbox" name="track" ng-change="addRemoveMarkers(hideMarker.value1)"  ng-model="hideMarker.value1"  ng-true-value="'YES'" ng-false-value="'NO'" >
                           <b> Markers</b>
                        </label>
                  </div>

                        <div style="position: fixed;top: 50px;left: 180px; z-index:99; background-color: #fff; padding: 5px; border-radius: 2px; cursor: pointer;  width: 250px" class="legendlist">
                          <div>
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
                        </div>
                </div>
            </div>
        <!-- </div> -->
        <!-- /#page-content-wrapper -->
    <!-- </div> -->
     
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

   <div class="modal fade" id="myModal2" role="dialog"  style="top:30px;">
    <div class="modal-dialog modal-md" style="width:700px;height:500px;"  >
      <div class="modal-content">
        <div class="modal-header" style="height:45px; padding-top: 7px;">
         <!-- <div> --><button  type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true" style="color:white;">&times;</span></button>
            <h4>{{windowRouteName}}</h4>
            <!-- </div> -->
        </div>
    <div class="modal-body" style="padding:5px 5px 5px 5px;">
          
         <div id="dvMap" style="width:100%;height:450px;"></div>

         </div>
        
      </div>
    </div>
  </div>    
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

  if(apikey_url != null || apikey_url != undefined){
    url = "https://maps.googleapis.com/maps/api/js?key="+apikey_url+"&libraries=places,geometry";
  }        

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
 
 //An array of scripts you want to load in order
   var scriptLibrary = [];
   scriptLibrary.push("assets/js/static.js");
   scriptLibrary.push("assets/js/jquery-1.11.0.js");
   scriptLibrary.push("assets/js/bootstrap.min.js");
 //scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js");
   scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js");
 //scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js");
   scriptLibrary.push(url);
 //scriptLibrary.push("https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places,geometry");
   scriptLibrary.push("assets/js/ui-bootstrap-0.6.0.min.js");
 //scriptLibrary.push("assets/js/bootstrap.min_3.3.7.js");
 //scriptLibrary.push("http://code.highcharts.com/highcharts.js");
 //scriptLibrary.push("http://code.highcharts.com/highcharts-more.js");
 //scriptLibrary.push("http://code.highcharts.com/modules/solid-gauge.js");
   scriptLibrary.push("assets/js/markerwithlabel.js");
   scriptLibrary.push("assets/js/highcharts.js");
   scriptLibrary.push("assets/js/moment.js");
   scriptLibrary.push("assets/js/bootstrap-datetimepicker.js");
   scriptLibrary.push("assets/js/infobubble.js");
   scriptLibrary.push("assets/js/infobox.js");
   scriptLibrary.push("assets/ui-drop/select.min.js");
   scriptLibrary.push("assets/js/vamoApp.js");
   scriptLibrary.push("assets/js/services.js");
   scriptLibrary.push("assets/js/customplay.js");

 //Pass the array of scripts you want loaded in order and a callback function to invoke when its done
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