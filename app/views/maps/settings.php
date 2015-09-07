<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Satheesh">
    <title>VAMOSGPS</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/font-awesome-4.2.0/css/font-awesome.css" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        #map_canvas{
            width:100%;
            height: 100vh; 
        }
    </style>
</head>
<body ng-app="mapApp">
	<div id="wrapper" ng-controller="mainCtrl">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand"><a href="javascript:void(0);"><img src="assets/imgs/logo.png"/></i></a></li>
                <li class="track"><a href="../public/live"><div></div><label>Track</label></a></li>
                <li class="history"><a href="../public/replay"><div></div><label>History</label></a></li>
                <li class="alert01"><a href="../public/reports"><div></div><label>Reports</label></a></li>
                <li class="stastics"><a href="../public/statistics"><div></div><label>Statistics</label></a></li>
                <li class="settings"><a href="../public/settings"  class="active"><div></div><label>Settings</label></a></li>
                <li class="admin"><a href="../public/performance"><div></div><label>Performance</label></a></li>
                <li><a href="../public/logout"><img src="assets/imgs/logout.png"/></a></li>
            </ul>
            <ul class="sidebar-subnav" style="max-height: 100vh; overflow-y: auto;">
                <li style="padding-left:25px;">
                        <div class="right-inner-addon" align="center">
                    <i class="fa fa-search"></i>
                    	<input type="search" class="form-control" placeholder="Search" ng-model="searchbox" name="search" />
                    </div>
                </li>
                <li ng-repeat="location in locations" class="active"><a href="javascript:void(0);" ng-click="groupSelection(location.group, location.rowId)">{{location.group}}</a>
                    <ul class="nav nav-second-level" style="max-height: 400px; overflow-y: auto;">
                    <li ng-repeat="loc in location.vehicleLocations | filter:searchbox"><a href="" ng-class="{active: $index == selected, red:loc.status=='OFF'}" ng-click="genericFunction(loc.vehicleId, $index)"><img ng-src="assets/imgs/{{loc.vehicleType}}.png" fall-back-src="assets/imgs/Car.png" width="16" height="16"/>{{loc.vehicleId}} ({{loc.shortName}})</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                      <iframe src="{{iframeurl}}" style="width:100vw; height:100vh; border:none;" ></iframe>  
                    </div>
                </div>
            </div>
        </div>
    </div>
      <script src="assets/js/static.js"></script>
    <script src="assets/js/jquery-1.11.0.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
    <script src="assets/js/customsettings.js"></script>
    <script>
		$("#menu-toggle").click(function(e) {
			e.preventDefault();
			$("#wrapper").toggleClass("toggled");
		});
    </script>
</body>
</html>