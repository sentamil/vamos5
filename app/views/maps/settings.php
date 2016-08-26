<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
   <title>GPS</title>


<link rel="shortcut icon" href="assets/imgs/tab.ico">

<!-- <link rel="stylesheet" href="assets/css/popup.bootstrap.min.css"> -->
<!-- <link href="../app/views/reports/AdminLTE/AdminLTE.css" rel="stylesheet"> -->
<!-- <link href="assets/css/bootstrap.css" rel="stylesheet"> -->
<!-- <link href="../app/views/reports/datepicker/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"> -->
<link href="assets/css/simple-sidebar.css" rel="stylesheet">
<!-- <link href="assets/font-awesome-4.2.0/css/font-awesome.css" rel="stylesheet"> -->
<!-- <link href="assets/css/bootstrap-select.min.css" rel="stylesheet"> -->

<!-- <link rel="stylesheet" href="assets/css/bootstrap.css"> -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.css"> -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="assets/css/bootstrap-select.css">
 
</head>
<div id="status">&nbsp;</div>
<body ng-app = "mapApp">
    <div ng-controller = "mainCtrl">
        <div id="wrapper">
            <div id="sidebar-wrapper">
                <ul class="sidebar-nav">
                    <li class="sidebar-brand"><a href="javascript:void(0);"><img id="imagesrc" src=""/></i></a></li>
                    <li class="track"><a href="../public/track"><div></div><label>Track</label></a></li>
                    <li class="history"><a href="../public/track?maps=replay"><div></div><label>History</label></a></li>
                    <li class="alert01"><a href="../public/reports"><div></div><label>Reports</label></a></li>
                    <li class="stastics"><a href="../public/statistics"><div></div><label>Statistics</label></a></li>
                    <li class="admin"><a href="../public/fuel"><div></div><label>Fuel</label></a></li>
                    <li><a href="../public/logout"><img src="assets/imgs/logout.png"/></a></li>         
                </ul>
                <ul class="sidebar-subnav" style="max-height: 100vh; overflow-y: auto;" ng-init="vehicleStatus='ALL'">
                    <li style="margin-bottom: 15px;"><div class="right-inner-addon" align="center"><i class="fa fa-search"></i><input type="search" class="form-control" placeholder="Search" ng-model="searchbox" name="search" /></div>
                    </li>
                    <li ng-repeat="location in locations02"><a href="javascript:void(0);" ng-click="groupSelection(location.group, location.rowId)" ng-cloak >{{trimColon(location.group)}}</a>
                        <ul class="nav nav-second-level" style="max-height: 400px; overflow-y: auto;">
                            <li ng-repeat="loc in location.vehicleLocations | filter:searchbox"><a href="" ng-class="{active: $index == selected, red:loc.status=='OFF'}" ng-click="genericFunction(loc.vehicleId, $index, loc.shortName)"><img ng-src="assets/imgs/{{loc.vehicleType}}.png" fall-back-src="assets/imgs/Car.png" width="16" height="16"/><span>{{loc.shortName}}</span></a></li>

                        </ul>
                    </li>
                </ul>
            </div>
            <div id="testLoad"></div>
            <div style="position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; top: 40px; background-color: #fcfcfc; margin: 10px; border-radius: 3px; border: 0.7px solid #dcdcdc; ">
                <h4>Mail Scheduling</h4>
                <div class="col-md-3"></div>
                <div class="col-md-3" align="center">
                  <div class="form-group">
                    <input type="text" class="form-control" ng-model="mailId" placeholder="E-Mail" data-toggle="tooltip" data-placement="left"  title="please don't use space">
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
                      <th class="id" style="text-align:center;" width="10%">
                        <input type="checkbox" ng-model="selectId" ng-click="vehiSelect(selectId)"/>
                      </th>
                      <th class="id" style="text-align:center;" width="20%">Vehicle Name</th>
                      <th class="id" sort="sort" style="text-align:center;" width="15%"> 
                        <select class="selectpicker" data-width="100px"  ng-options="option+' hrs' for option in hours" ng-model="from">
                        <option style="display:none" value="">From</option>
                            <!-- <option>From Hours</option> -->
                            <!-- <option ng-repeat="x in hours">{{x}}&nbsp;hour</option> -->
                          </select>
                      </th>
                      <th class="id" sort="sort" style="text-align: center;" width="15%">
                        <select class="selectpicker" data-width="100px"  ng-options="option+' hrs' for option in hours" ng-model="to">
                        <option style="display:none" value="">To</option>
                            <!-- <option>From Hours</option> -->
                            <!-- <option ng-repeat="x in hours">{{x}}&nbsp;hour</option> -->
                          </select>
                      </th>
                      <th class="id" style="text-align: center;" width="40%"> 
                      <select name="selectdata" ng-model="reportSelected" ng-change="changeValue(reportSelected)" class="selectpicker" title="Select Report" ng-options="report for report in reports" multiple  data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true"></select></th>
                     
                    </tr>
                  </thead>
                <tbody>
                  <tr ng-repeat="vehicleName in vehicles">
                    <td><input type="checkbox" ng-model="vehiId[$index]"/></td>
                    <td>{{vehicleName.shortName}}{{}}</td>
                    <td>{{from}}&nbsp;hrs</td>
                    <td>{{to}}&nbsp;hrs</td>
                    <td id="checking">
                    <input type="checkbox" ng-model="checkingValue.move[$index]">M</input>&nbsp;
                    <input type="checkbox" ng-model="checkingValue.over[$index]">O</input>&nbsp;
                    <input type="checkbox" ng-model="checkingValue.site[$index]">S</input>&nbsp;
                    <input type="checkbox" ng-model="checkingValue.fuel[$index]">F</input>&nbsp;
                    <input type="checkbox" ng-model="checkingValue.temp[$index]">T</input>
                    <!-- <select data-width="100px"  ng-options="option+' hour' for option in hours" ng-model="to">
                        <option style="display:none" value="">To</option>
                          </select> -->
                      <!-- <select class="selectpicker"  multiple ng-options="option for option in reports" ng-model="report"></select> -->

                     <!-- <select  multiple ng-options="option for option in reports" ng-model="report" style="height: 24pt"></select> -->
                     <!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->
                    
                    </td>
                  </tr>
                </tbody>
                
              </table>
               
                <div class="row">
                    <div class="col-md-1" align="center"></div>
                    <div class="col-md-2" align="center">
                        <div class="form-group">
                          
                                   <!--    <select multiple ng-options="option for option in vehicles" ng-model="ca">
                            
                          </select><br>
                           <span style="font-size: 10px">(Use ctrl or ctrl+a for multiple vehicles)</span>     -->       
                          <!-- <button ng-click="valueas(ca)">as</button> -->
                         
                        </div>
                    </div>

                     <!-- <div class="col-md-1" align="center"></div> -->
                   <!--  <div class="col-md-1" align="center">
                      <div class="form-group">
                        <div>
                          <select class="selectpicker" data-width="100px">
                            <option>From Hours</option>
                            <option ng-repeat="x in hours">{{x}}&nbsp;hours</option>
                          </select>
                        </div>
                      </div>
                    </div> -->
                 
                 
                </div>
                <div class="row">
                <div class="col-md-4" align="center"></div>
                    
                </div>
                
               <!-- <button ng-click="submitFunction()">Submit</button> -->
               <!-- Modal -->
 <!--  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
  
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div> -->
            </div>
         
        </div>
    </div>
        <!-- <input type="text" name="vehicleid" ng-model="vehi">
        <input type="text" name="mailid" ng-model="mail">
        <input type="text" name="fromtime" ng-model="from">
        <input type="text" name="totime" ng-model="to">
        <input type="text" name="reports" ng-model="rep">
        <input type="submit" ng-click="report(vehi, mail, from, to, rep)">
         <select class="selectpicker" multiple data-actions-box="true" ng-model="vall">
  <option>Mustard</option>
  <option>Ketchup</option>
  <option>Relish</option>
  <option>arun</option>
  <option>aah</option>
  <option>aahan</option>
  <option>aa</option>
</select> -->
  

   <script src="assets/js/static.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.8/angular.min.js"></script> 
  <script src="assets/js/ui-bootstrap-0.6.0.min.js"></script>

  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/bootstrap-select.js"></script>

  <script src="assets/js/vamoApp.js"></script>
  <script src="assets/js/services.js"></script>
  <script src="../app/views/reports/customjs/settings.js"></script>


 <!-- <script src="assets/js/static.js"></script>
    <script src="assets/js/jquery-1.11.0.js"></script>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.8/angular.min.js"></script> 
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="../app/views/reports/customjs/ui-bootstrap-tpls-0.12.0.min.js"></script>

     <script src="assets/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.0/js/bootstrap-select.min.js"></script> 
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script src="../app/views/reports/customjs/html5csv.js"></script>
    <script src="../app/views/reports/customjs/moment.js"></script>
    <script src="../app/views/reports/customjs/FileSaver.js"></script>
    <script src="../app/views/reports/datepicker/bootstrap-datetimepicker.js"></script>
    <script src="../app/views/reports/datatable/jquery.dataTables.js"></script>
    <script src="assets/js/vamoApp.js"></script>
  <script src="../app/views/reports/customjs/reports.js"></script> -->
    <!-- <script src="../app/views/reports/customjs/settings.js"></script> -->



    <script type="text/javascript">
        
$(document).ready(function() {  
  $(".selectpicker").selectpicker();
});
    </script>

</body>
</html>