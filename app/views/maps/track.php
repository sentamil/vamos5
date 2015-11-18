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
         #container-speed{ position:absolute; top:-67px; left:-50px;width: 300px; height: 100px; float: left} 
    </style>
</head>
<body ng-app="mapApp">
	<div id="wrapper" ng-controller="mainCtrl" style="padding-left:0 !important;">
        
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                    	<div id="maploc">
                        	<div id="pac-input01">
                                   
                                <div>
                                	<input type="button" id="traffic" ng-click="check()" value="Traffic" />
                                </div> 
                                </div>
                        <map id="map_canvas"></map>
                        </div>
                        <div class="nearbyTable01">
                            	<h3>Vehicle Status</h3>
                                <div>
                                	<table cellpadding="0" cellspacing="0">
                                    	<tbody>
                                            <tr>
                                            	<td>Moving</td>
                                                <td><img src="assets/imgs/green.png"/></td>
                                            </tr>
                                             <tr>
                                            	<td>Parked</td>
                                                <td><img src="assets/imgs/flag.png"/></td>
                                            </tr>
                                             <tr>
                                            	<td>Overspeed</td>
                                                <td><img src="assets/imgs/red.png"/></td>
                                            </tr>
                                             <tr>
                                            	<td>Standing</td>
                                                <td><img src="assets/imgs/orange.png"/></td>
                                            </tr>
                                             <tr>
                                            	<td>Geo Fence</td>
                                                <td><img src="assets/imgs/blue.png"/></td>
                                            </tr>
                                            <tr>
                                            	<td>No Data</td>
                                                <td><img src="assets/imgs/gray.png"/></td>
                                            </tr>
                                    </table>
                                </div>
                            </div>
                    </div>
                    <div id="lastseentrack" style="top:0; height: auto; bottom:auto">&nbsp;</div>
                    <div class="latlong"><label><input type="text"  style="width:265px" value="0.0" id="latinput" readonly/> POI : <input type="text" style="width:300px;" id="poival" placeholder="Enter Point of Interest" ng-enter="enterkeypress()" /> <button ng-click="enterkeypress()">Save</button></label><!--<label>Longitude : <input type="text" value="0.0" id="lnginput" readonly/></label>--></div>
                         <div class="bottomContent">
                        	<!--<a href="javascript:void(0);" class="contentbClose"><img src="assets/imgs/close.png"/></a>-->
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
                        </div>
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
    <script src="assets/js/infobox.js"  type="text/javascript"></script>
    <script src="assets/js/vamoApp.js"></script>
    <script src="assets/js/services.js"></script>
    <script src="assets/js/customtrack.js"></script>
    <script>
		$("#menu-toggle").click(function(e) {
			e.preventDefault();
			$("#wrapper").toggleClass("toggled");
		});
    </script>
</body>
</html>