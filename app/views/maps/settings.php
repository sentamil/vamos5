<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GPS</title>
<link rel="shortcut icon" href="assets/imgs/tab.ico">
<link href="https://fonts.googleapis.com/css?family=Lato|Raleway:500|Roboto|Source+Sans+Pro|Ubuntu" rel="stylesheet">
<link rel="stylesheet" href="assets/css/popup.bootstrap.min.css">
<link href="../app/views/reports/AdminLTE/AdminLTE.css" rel="stylesheet">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/jVanilla.css" rel="stylesheet">
<link href="assets/css/simple-sidebar.css" rel="stylesheet">
<!--<link rel="stylesheet" href="assets/css/bootstrap.min.css">-->
<link rel="stylesheet" href="assets/css/bootstrap-select.css">

<style type="text/css">
  
  body{
    font-family: 'Lato', sans-serif;
  /*font-weight: bold;*/ 

  /*font-family: 'Lato', sans-serif;
    font-family: 'Roboto', sans-serif;
    font-family: 'Open Sans', sans-serif;
    font-family: 'Raleway', sans-serif;
    font-family: 'Faustina', serif;
    font-family: 'PT Sans', sans-serif;
    font-family: 'Ubuntu', sans-serif;
    font-family: 'Droid Sans', sans-serif;
    font-family: 'Source Sans Pro', sans-serif;
   */
} 

</style>

</head>

<div id="status">&nbsp;</div>
<body ng-app = "mapApp">
    <div ng-controller = "mainCtrl" class="ng-cloak">
        <div id="wrapper-site">
            <div id="sidebar-wrapper-site" style="min-width:92px;">
                <ul class="sidebar-nav" ">
                    <li class="sidebar-brand"><a href="javascript:void(0);"><img id="imagesrc" src=""/></i></a></li>
                    <li class="track"><a href="../public/track"><div></div><label>Track</label></a></li>
                    <!-- <li class="history"><a href="../public/track?maps=replay"><div></div><label>History</label></a></li> -->
                    <li class="alert01"><a href="../public/reports"><div></div><label>Reports</label></a></li>
                    <li class="stastics"><a href="../public/statistics"><div></div><label>Statistics</label></a></li>
                    <li class="admin"><a href="../public/settings" class="active"><div></div><label>Scheduled</label></a></li>
                    <li class="fms"><a href="../public/fms"><div></div><label>FMS</label></a></li>
                    <li><a href="../public/logout"><img src="assets/imgs/logout.png"/></a></li>         
                </ul>
                <!-- <ul class="sidebar-subnav" style="max-height: 100vh; overflow-y: auto;" ng-init="vehicleStatus='ALL'">
                    <li style="margin-bottom: 15px;"><div class="right-inner-addon" align="center"><i class="fa fa-search"></i><input type="search" class="form-control" placeholder="Search" ng-model="searchbox" name="search" /></div>
                    </li>
                    <li ng-repeat="location in locations02"><a href="javascript:void(0);" ng-click="groupSelection(location.group, location.rowId)" ng-cloak >{{trimColon(location.group)}}</a>
                        <ul class="nav nav-second-level" style="max-height: 400px; overflow-y: auto;">
                            <li ng-repeat="loc in location.vehicleLocations | filter:searchbox"><a href="" ng-class="{active: $index == selected, red:loc.status=='OFF'}" ng-click="genericFunction(loc.vehicleId, $index, loc.shortName)"><img ng-src="assets/imgs/{{loc.vehicleType}}.png" fall-back-src="assets/imgs/Car.png" width="16" height="16"/><span>{{loc.shortName}}</span></a></li>

                        </ul>
                    </li>
                </ul> -->
            </div>
            <div id="testLoad"></div>

<!--<div ng-show="reportBanShow" class="modal fade" id="allReport" role="dialog" style="top: 100px">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-body">
                  <p class="err" style="text-align: center;"> You are not a premium user !... </p>
              </div>
          </div>
      </div>
      </div> -->

        <div ng-show="reportBanShow" class="col-md-10" >
            <div class="box box-primary" style="height:90px; padding-top:30px; margin-top:15%; margin-left:8%;">
                <p ><h5 class="err" style="text-align: center;border:1px solid bold;"> No Reports Found! </h5></p>
            </div>
        </div>

      <!--  <tabset class="nav-tabs-custom">
         <tab heading="Daily"  active="actsTab">-->

      <div class="col-md-12" style="margin-top: 70px;margin-left: 8px;">
          
          <tabset class="nav-tabs-custom">
              <tab  ng-show="reportDaily" heading="Daily"  active="reportDailyAct">
                 <!--<div class="box box-primary">

                    <!--  <div class="box-header" data-toggle="tooltip" title="" data-original-title="Header tooltip" >
              
                         <h5 class="box-title">Scheduled Report</h5>

                         </div>-->

          <div class="box box-primary" ng-show="reportDaily" style="min-height:650px;position: relative; padding-right: 15px; padding-left: 15px;  background-color: #fdfdfd;  border-radius: 3px; border: 0.7px solid #e0dfdf; ">
                <h4>Daily Mail Scheduling</h4>

                <h6 style="margin-left: 15px; color: red">{{error}}</h6>
                <div class="col-md-1" ></div>
                
                <div class="col-md-3">
                  <!-- <select class="form-control" ng-options="trimColon(grpName.group) for grpName in locations02" ng-model="groupName" ng-change="groupChange()">
                     <option style="display:none" value="">Select Group</option>
                  </select> -->
                  <select ng-options="groups.group as trimColon(groups.group) for groups in locations02" ng-model="groupSelected" ng-change="groupChange(groupSelected)" class="form-control" id="colorChange">
                            <option style="display:none" value="">Select Group</option>
                        </select>
                </div>

                <div class="col-md-3" align="center">
                  <div class="form-group">
                    <input type="text" class="form-control" id="mailIdDaily" ng-model="mailId" placeholder="E-Mail" data-toggle="tooltip" data-placement="left"  title="please don't use space"><span style="font-size: 10px">(Use Comma ',' for more MailId)</span>
                  </div>
                </div>

                <div class="col-md-2" align="center">
                  <div class="form-group">
                    <button ng-click="storeValue()">Submit</button>
                  </div>
                </div>

               <table class="table table-bordered table-striped table-condensed table-hover" id="reportable">
                  <thead>
                    <tr style="text-align:center;font-weight: bold;">
                      <th class="id" style="text-align:center;background-color: #deebf3;" width="10%">
                        <input type="checkbox" ng-model="selectId" ng-click="vehiSelect(selectId)"/>
                      </th>
                      <th class="id" style="text-align:center;background-color: #deebf3;color:#545353;" width="20%">Vehicle Name</th>
                      <th class="id" sort="sort" style="text-align:center;background-color: #deebf3;" width="15%"> 
                        <select class="selectpicker" data-width="100px"  ng-options="option+' hrs' for option in hoursFrom" id="fromTime"  ng-model="from">
                        <option style="display:none" value="">From</option>
                            <!-- <option>From Hours</option> -->
                            <!-- <option ng-repeat="x in hours">{{x}}&nbsp;hour</option> -->
                          </select>
                      </th>
                      <th class="id" sort="sort" style="text-align: center;background-color: #deebf3;" width="15%">
                        <select class="selectpicker" data-width="100px"  ng-options="option+' hrs' for option in hoursTo" id="toTime" ng-model="to">
                        <option style="display:none" value="">To</option>
                            <!-- <option>From Hours</option> -->
                            <!-- <option ng-repeat="x in hours">{{x}}&nbsp;hour</option> -->
                          </select>
                      </th>
                      <th class="id" style="text-align: center;background-color: #deebf3;" width="40%"> 
                      <select name="selectdata" ng-model="reportSelected" ng-change="changeValue(reportSelected)" class="selectpicker" title="Select Report" ng-options="report for report in reports" multiple  data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true"></select></th>
                     <th style="text-align:center;font-weight: bold; cursor: pointer;background-color: #deebf3;" title="Delete All Vehicles">
                        <span class="glyphicon glyphicon-trash" ng-click="deleteFn()"></span>
                     </th>
                    </tr>
                  </thead>
                <tbody>
                  <tr ng-repeat="vehi in vehicles">
                    <td><input type="checkbox" ng-model="vehiId[$index]"/></td>
                    <td>{{vehi.shortName}}</td>
                    <td>{{from}}&nbsp;hrs</td>
                    <td>{{to}}&nbsp;hrs</td>
                    
                    <td id="checking">
                    <input type="checkbox" ng-model="checkingValue.move[$index]">M</input>&nbsp;
                    <input type="checkbox" ng-model="checkingValue.over[$index]">O</input>&nbsp;
                    <input type="checkbox" ng-model="checkingValue.site[$index]">S</input>&nbsp;
                    <input type="checkbox" ng-model="checkingValue.poi[$index]">PI</input>&nbsp;
                    <input type="checkbox" ng-model="checkingValue.fuel[$index]">F</input>&nbsp;
                    <!--<input type="checkbox" ng-model="checkingValue.temp[$index]">T</input> -->
                    
                    </td>
                    <td style="cursor: pointer;" title="Delete Individual Vehicle">
                        <span class="glyphicon glyphicon-trash" ng-click="deleteFn(vehi, $index)">
                    </td>
                  </tr>
                </tbody>
                
              </table>

          </div>
     <!--    </div> -->

      </tab>

       <tab ng-show="reportMonthly" heading="Monthly"  active="reportMonthlyAct" ng-click="fetchMonController()">

              <div class="box box-primary" ng-show="reportMonthly" style="min-height:600px;position: relative; padding-right: 15px; padding-left: 15px;  background-color: #fdfdfd;  border-radius: 3px; border: 0.7px solid #e0dfdf; ">

                  <h4>Monthly Mail Scheduling</h4>

                <h6 style="margin-left: 15px; color: red">{{error}}</h6>
                <div class="col-md-1" ></div>
                
                <div class="col-md-2">
                  <!-- <select class="form-control" ng-options="trimColon(grpName.group) for grpName in locations02" ng-model="groupName" ng-change="groupChange()">
                     <option style="display:none" value="">Select Group</option>
                  </select> -->
                 <!--   <select ng-options="groups.group as trimColon(groups.group) for groups in locations02" ng-model="groupSelected" ng-change="groupChange()" class="form-control" id="colorChange">
                            <option style="display:none" value="">Select Group</option>
                        </select>  -->
                </div>

                <div class="col-md-3" align="center">
                  <div class="form-group">
                    <input type="text" class="form-control" id="groupMailId" ng-model="grpMailId" placeholder="E-Mail" data-toggle="tooltip" data-placement="left"  title="please don't use space"><span style="font-size: 10px">( Type comma ' , ' between more than one mail id )</span>
                  </div>
                </div>

                <div class="col-md-2" align="center">
                  <div class="form-group">
                    <button ng-click="storeMonValues()">Submit</button>
                  </div>
                </div>

               <table class="table table-bordered table-striped table-condensed table-hover" id="reportable">
                  <thead>
                    <tr >
                      <th class="id" style="text-align:center;background-color: #deebf3;font-weight: bold;margin-top:0px;" width="10%">
                        <input type="checkbox" ng-model="selectGrpId" ng-click="grpSelect(selectGrpId)"/>
                      </th>

                      <th class="id" style="text-align:center;background-color: #deebf3;color:#545353;font-weight: bold;" width="40%">Group Name</th>
           <!--       <th class="id" sort="sort" style="text-align:center;" width="15%"> 
                        <select class="selectpicker" data-width="100px"  ng-options="option+' hrs' for option in hoursFrom" ng-model="from">
                        <option style="display:none" value="">From</option>
                            <!-- <option>From Hours</option> -->
                            <!-- <option ng-repeat="x in hours">{{x}}&nbsp;hour</option> -->
           <!--         </select>
                      </th>
                      <th class="id" sort="sort" style="text-align: center;" width="15%">
                        <select class="selectpicker" data-width="100px"  ng-options="option+' hrs' for option in hoursTo" ng-model="to">
                        <option style="display:none" value="">To</option>
                            <!-- <option>From Hours</option> -->
                            <!-- <option ng-repeat="x in hours">{{x}}&nbsp;hour</option> -->
           <!--         </select>
                      </th>  -->

                      <th class="id" style="text-align: center;background-color: #deebf3;font-weight: bold;" width="30%"> 

                      <select name="selectdata" ng-model="monReportSel" ng-change="changeMonValue(monReportSel)" class="selectpicker" title="Select Report" ng-options="report for report in monReport" multiple  data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true"></select>

                      </th>


                     <th width="10%" style="text-align:center;font-weight: bold; cursor: pointer;background-color: #deebf3;" title="Delete All Vehicles">
                        <span class="glyphicon glyphicon-trash" ng-click="deleteMonFunc()"></span>
                     </th>

                    </tr>
                  </thead>


                 <tbody>
                 <tr> <td style="border-color:none;"></td></tr>
                
                  <tr ng-repeat="groups in groupList">
                    <td><input type="checkbox" ng-model="grpId[$index]"/></td>
                    <td>{{groups}}</td>
                    
                    <!--<td>{{from}}&nbsp;hrs</td>
                        <td>{{to}}&nbsp;hrs</td> -->
                    
                    <td id="checking">
                      <input type="checkbox" ng-model="checkingValue.cstop[$index]">&nbsp;CS</input>&nbsp;
                    </td>

                    <td style="cursor: pointer;" title="Delete Individual Vehicle">
                        <span class="glyphicon glyphicon-trash" ng-click="deleteMonFunc($index)">
                    </td>

                  </tr>
                </tbody>
                
              </table>




              </div>
       </tab>

    </tabset> 

     </div>
    </div>

</div>
         
  <script src="assets/js/static.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.8/angular.min.js"></script> 

    <script src="../app/views/reports/customjs/ui-bootstrap-tpls-0.12.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

  <!--<script src="assets/js/ui-bootstrap-0.6.0.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>-->

  <script src="assets/js/bootstrap-select.js"></script>

  <script src="assets/js/vamoApp.js"></script>
  <script src="assets/js/services.js"></script>
  <script src="../app/views/reports/customjs/settings.js"></script>

  <script type="text/javascript">
        
    $(document).ready(function() {  
      $(".selectpicker").selectpicker();
    });

  </script>

</body>
</html>
