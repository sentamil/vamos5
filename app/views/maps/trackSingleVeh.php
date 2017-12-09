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
    <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:500|Roboto|Source+Sans+Pro|Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css"/>
    <link href="assets/css/leaflet.label.css" rel="stylesheet">
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
      $.ajax({
            async: false,
            method: 'post', 
            url: 'getApiKeys',
            data: postValue,
            success: function (response) {
              sessionStorage.setItem('apiKey', JSON.stringify(response));
            }
        })
    </script>

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

    #pac-input01{
      position: fixed;
      top: 12px;
      float: right;
      right: 78px; 
      z-index:1; 
      border-radius: 2px;
    }
   
    #pac-input01 input{ 
     background:none; 
     border:none;
     font-size:11px; 
     background-color:#fff; 
     border:1px solid #aaa; 
     margin-bottom:10px;
    }
            

      #container-speed{  width: 170px; height: 100px;}
      #container-fuel{  width: 170px; height: 100px;}
      .rightSection{position:absolute; top:70px; right:5px; width:275px; padding:10px; background:#fff; -webkit-border-radius: 12px; -moz-border-radius: 12px; border-radius: 12px; }

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
  <div id="wrapper" ng-controller="mainCtrl" style="padding-left:0 !important;">
        
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

                        <div id="pac-input01">
                            <input type="button" id="traffic" ng-click="checkme('traffic')" value="Traffic" />
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

        </div>
    </div>

    <script>

    var apikey_url = JSON.parse(sessionStorage.getItem('apiKey'));
    var url = "https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places";

    if(apikey_url != null ||  apikey_url != undefined){
      url = "https://maps.googleapis.com/maps/api/js?key="+apikey_url+"&libraries=places";
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
      } else {
       librariesLoadedCallback();
      }
    }
 
   // An array of scripts you want to load in order
   var scriptLibrary = [];
   
   scriptLibrary.push("assets/js/static.js");
   scriptLibrary.push("assets/js/jquery-1.11.0.js");
   scriptLibrary.push("assets/js/bootstrap.min.js");
// scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js");
   scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular.min.js");
// scriptLibrary.push("https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js");
   scriptLibrary.push(url);
 //scriptLibrary.push("https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places");
   scriptLibrary.push("http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js");
   scriptLibrary.push("assets/js/leaflet.label.js");
   scriptLibrary.push("assets/js/ui-bootstrap-0.6.0.min.js");
/* scriptLibrary.push("https://code.highcharts.com/highcharts.js");
   scriptLibrary.push("https://code.highcharts.com/highcharts-more.js");
   scriptLibrary.push("https://code.highcharts.com/modules/solid-gauge.js");*/
   scriptLibrary.push("assets/js/highcharts_new.js");
   scriptLibrary.push("assets/js/highcharts-more_new.js");
   scriptLibrary.push("assets/js/solid-gauge_new.js");
   scriptLibrary.push("assets/js/markerwithlabel.js");
   scriptLibrary.push("assets/js/infobubble.js");
   scriptLibrary.push("assets/js/infobox.js");
   scriptLibrary.push("assets/js/vamoApp.js");
   scriptLibrary.push("assets/js/services.js");
   scriptLibrary.push("assets/js/customSingle.js");


   // Pass the array of scripts you want loaded in order and a callback function to invoke when its done
   loadJsFilesSequentially(scriptLibrary, 0, function(){
       // application is "ready to be executed"
       // startProgram();
   });

    </script>
</body>
</html>