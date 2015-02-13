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
        #map_canvas{
            width:100%;
            height: 100vh; 
        }
    </style>
</head>
<body ng-app="mapApp">
	<div id="wrapper" ng-controller="mainCtrl" style="padding-left:0 !important;">
        
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <map id="map_canvas"></map>
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
                          			<div class="container01" align="center"></div>
                                </div>
                                <div class="col-md-4 col-lg-2" align="right" id="toddist">
                                	<h6 align="right" style="text-align:right;">&nbsp;Today Distance - <span><span></span>&nbsp;Km</span></h6>
                                   
                                </div>
                               <!--div class="col-md-1" align="center" id="vehstat">
                                	<h6>Position - <span></span></h6>
                                    
                                </div-->
                                <div class="col-md-2" align="center" id="regno">
                                	<h6>Position Time - <span></span></h6>
                                    
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
    
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places" type="text/javascript"></script>
    <script src="assets/js/markerwithlabel.js"></script>
    <script src="assets/js/infobox.js"  type="text/javascript"></script>
    <script src="assets/js/customtrack.js"></script>
    <script>
		$("#menu-toggle").click(function(e) {
			e.preventDefault();
			$("#wrapper").toggleClass("toggled");
		});
    </script>
</body>
</html>