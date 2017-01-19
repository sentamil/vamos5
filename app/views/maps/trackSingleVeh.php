<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Satheesh">
    <title>GPS</title>
    <link rel="shortcut icon" href="assets/imgs/tab.ico">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/font-awesome-4.2.0/css/font-awesome.css" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- <script src="assets/js/static.js"></script> -->
    <script src="assets/js/jquery-1.11.0.js"></script>
    <script type="text/javascript">
      function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
      }

      var postValue = {
      'id': getParameterByName('userID')

      };
      
    $.post('{{ route("ajax.apiKeyAcess") }}',postValue)
      .done(function(data) {
        
       
            sessionStorage.setItem('apiKey', JSON.stringify(data));
            
          }).fail(function() {
            console.log("fail");
      });
    </script>

    <style type="text/css">
        #map_canvas{
            width:100%;
            height: 100vh; 
        }
         #container-speed{  width: 170px; height: 100px;}
     #container-fuel{  width: 170px; height: 100px;}
         .rightSection{position:absolute; top:70px; right:5px; width:275px; padding:10px; background:#fff; -webkit-border-radius: 12px; -moz-border-radius: 12px; border-radius: 12px; }
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
                    </div>
                    <!-- <div id="minmax" style="position: absolute;top: 0px;right: 10px; z-index:999999">
                            <img src="assets/imgs/add.png" />
                        </div>
                    <div class="rightSection" id="contentmin">
                        <table cellpadding="0" cellspacing="0" style="font-size:12px; word-wrap: break-word;" class="trackDetails">
                            <tr>
                                <td style="width:50%">Vehicle Name</td>
                                <td id="vehiid" style="width:50%"><span></span></td>
                            </tr>
                            <tr>
                                <td style="width:50%">odoDistance</td>
                                <td id="vehdevtype" style="width:50%"><span></span> Km</td>
                            </tr>
                            <tr>
                                <td style="width:50%">Speed Limit</td>
                                <td id="mobno"><span></span> Km/h</td>
                            </tr>
                            <tr>
                                <td colspan="2"><div id="container-speed"></div></td>
                            </tr>
                            <tr>
                                <td style="width:50%">Today Distance</td>
                                <td id="toddist"><span><span></span>&nbsp;Km</span></td>
                            </tr>
                            <tr>
                                <td style="width:50%"><span id="positiontime">Position </span>Time</td>
                                <td id="regno"><span></span></td>
                            </tr>
                             <tr>
                                <td style="width:50%">Address</td>
                                <td id="lastseentrack"><span></span></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="latlong"><label>LatLong<input type="text"  style="width:230px" value="0.0" id="latinput" readonly/> <br/> POI : <input type="text" style="width:153px;" id="poival" placeholder="Enter Point of Interest" ng-enter="enterkeypress()" /> <button ng-click="enterkeypress()">Save</button></label></div>
                                </td>
                                
                            </tr>
                        </table>
                        
                    	<h3 style="font-size:14px; text-align:center; margin-top:0;">Vehicle Status</h3>
                        <div class="tracklegend">
                        	<table cellpadding="0" cellspacing="0" style="font-size:12px; word-wrap: break-word;">
                            	<tbody>
                                    <tr>
                                    	<td style="width:25%">Moving</td>
                                        <td align="center"><img src="assets/imgs/green.png"/></td>
                                        <td style="width:25%">Parked</td>
                                        <td align="center"><img src="assets/imgs/flag.png"/></td>
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
                        </div>
                    </div> -->
                    <!--  <div id="graphsId">
                            <div>
                                <div>Speed - <label id="speed"></label>&nbsp;Km/h</div>
                                <div id="container-speed"></div>
                            </div>
                            <div>
                                <div>Tank Size - <label id="fuel"></label>&nbsp;Ltr</div>
                                <div id="container-fuel"></div>
                            </div>
                        </div> -->
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
    <!-- <script src="assets/js/static.js"></script>
    <script src="assets/js/jquery-1.11.0.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
    <script src="assets/js/ui-bootstrap-0.6.0.min.js"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/highcharts-more.js"></script>
	<script src="http://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places" type="text/javascript"></script>
    <script src="assets/js/markerwithlabel.js"></script>
    <script src="assets/js/infobubble.js"  type="text/javascript"></script> 
    <script src="assets/js/infobox.js"  type="text/javascript"></script>
    <script src="assets/js/vamoApp.js"></script>
    <script src="assets/js/services.js"></script>
    <script src="assets/js/customtrack.js"></script> -->
    <script>

var apikey_url = JSON.parse(sessionStorage.getItem('apiKey'));
// var url = "https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places";

// if(apikey_url != null || apikey_url != undefined)
      var url = "https://maps.googleapis.com/maps/api/js?key="+apikey_url;

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
   scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js");
   // scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js");
   // scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js");
   scriptLibrary.push(url);
   scriptLibrary.push("assets/js/ui-bootstrap-0.6.0.min.js");
   scriptLibrary.push("http://code.highcharts.com/highcharts.js");
   scriptLibrary.push("http://code.highcharts.com/highcharts-more.js");
   scriptLibrary.push("http://code.highcharts.com/modules/solid-gauge.js");
   scriptLibrary.push("assets/js/markerwithlabel.js");
   scriptLibrary.push("assets/js/infobubble.js");
   scriptLibrary.push("assets/js/infobox.js");
   scriptLibrary.push("assets/js/vamoApp.js");
   scriptLibrary.push("assets/js/services.js");
   scriptLibrary.push("assets/js/customtrack.js");



 
   // Pass the array of scripts you want loaded in order and a callback function to invoke when its done
   loadJsFilesSequentially(scriptLibrary, 0, function(){
       // application is "ready to be executed"
       // startProgram();
   });
  //   $(document).ready(function(){
  //       $('#minmax').click(function(){
  //           $('#contentmin').animate({
  //               height: 'toggle'
  //           },2000);
  //       });
  //   });
		// $("#menu-toggle").click(function(e) {
		// 	e.preventDefault();
		// 	$("#wrapper").toggleClass("toggled");
		// });
    </script>
</body>
</html>
