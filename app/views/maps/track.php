<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>GPS</title>
    <link rel="shortcut icon" href="assets/imgs/tab.ico">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/font-awesome-4.2.0/css/font-awesome.css" rel="stylesheet">
    <style type="text/css">
       /* #map_canvas{
            width:100%;
            height: 100vh;
            padding: 0px;
            margin: 0px;
            background-color: green 
        }*/

        #maploc1{
            width:100%;
            height: 45vh;

        } 
       #maploc2{
            width:100%;
            height: 45vh; 
        } 
        #maploc3{
            width:100%;
            height: 45vh; 
        } 
       #maploc4{
            width:100%;
            height: 45vh; 
        } 

        .shadow{box-shadow: 3px 3px 2px #888888;}

        .watermark1 {
            position: absolute;
            opacity: 0.25;
            top: 100px;
            left: 100px;
            font-size: 1em;
            text-align: center;
            z-index: 1000;
        }

         .watermark2 {
            position: absolute;
            opacity: 0.25;
            top: 100px;
            right: 100px;
            font-size: 1em;
            text-align: center;
            z-index: 1000;
        }

        .watermark3 {
            position: absolute;
            opacity: 0.25;
            bottom: 100px;
            left: 100px;
            font-size: 1em;
            text-align: center;
            z-index: 1000;
        }

         .watermark4 {
            position: absolute;
            opacity: 0.25;
            bottom: 100px;
            right: 100px;
            font-size: 1em;
            text-align: center;
            z-index: 1000;
        }

      /*  body{padding: 0px; margin: 0px}*/
    </style>
</head>
<div id="preloader" >
    <div id="status">&nbsp;</div>
</div>
<div id="preloader02" >
    <div id="status02">&nbsp;</div>
</div>

<body ng-app="mapApp">
	<div ng-controller="mainCtrl" style="padding-left:0 !important;">
       <!--  <div > -->
            <div style="background-color: #d8d8d8; padding: 5px; box-shadow: 3px 3px 2px #888888; border-top: 3px solid #3071a9">
                <div class="container">
                    <div class="col-md-3">
                        <select ng-options="groups for groups in sliceGroup" ng-model="vehicleSelected" ng-change="update()" class="form-control">
                            <option style="display:none" value="">Select Group</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select ng-options="vehi.vehicleId for vehi in vehicles" ng-model="vehicle" class="form-control">
                            <option style="display:none" value="">Select Vehicle</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select ng-options="screen for screen in selectScreen"  class="form-control" ng-model="screen">
                            <option style="display:none" value="">Select Screen</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" ng-click="multiTracking(vehicle, screen)">Submit</button>
                    </div>
                </div>
            </div>
            <div>
                <p class="watermark1">Screen 1</p> 
                <div class="col-md-6 quarter" style="padding: 3px; margin: 0px;">
                  
                    <div id="maploc1" class="shadow"></div>
                </div>
                <p class="watermark2">Screen 2</p> 
                <div class="col-md-6 quarter"  style="padding: 3px; margin: 0px;">
                    
                    <div id="maploc2" class="shadow"></div>
                </div>
                <div class="col-md-12"><hr style="margin: 0.3em auto;"></div>
                <p class="watermark3">Screen 3</p> 
                <div class="col-md-6 quarter" style="padding: 3px; margin: 0px;">
                   
                    <div id="maploc3" class="shadow"></div>
                </div>
                <p class="watermark4">Screen 4</p> 
                <div class="col-md-6 quarter" style="padding: 3px; margin: 0px;">
                    
                    <div id="maploc4" class="shadow"></div>
                </div>
            </div>
        <!-- </div>
 -->
    </div>
    <script src="assets/js/static.js"></script>
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
    <script src="assets/js/multiTrack.js"></script>



    <script>
    $(document).ready(function(){
        $('#minmax').click(function(){
            $('#contentmin').animate({
                height: 'toggle'
            },2000);
        });
    });
		$("#menu-toggle").click(function(e) {
			e.preventDefault();
			$("#wrapper").toggleClass("toggled");
		});
    </script>
</body>
</html>
