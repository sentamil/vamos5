//var app = angular.module('mapApp', ['ui.select']);
var gmarkers         = [];
var ginfowindow      = [];
var gsmarker         = [];
var gsinfoWindow     = [];
var geoinfowindow    = [];
var contentString    = [];
var contentString01  = [];
var geomarker        = [], geoinfo = [];
var id;

app.controller('mainCtrl',['$scope', '$http', 'vamoservice', '$q', '$filter','_global',function($scope, $http, vamoservice, $q, $filter, GLOBAL){


  $scope.locations        = [];
  $scope.path             = [];
  $scope.osmPath          = [];
  $scope.polylinearr      = [];
  $scope.polylineOsmColor = [];
  $scope.polyline1        = [];
  $scope.tempadd01        = '';
  $scope.cityCircle       = [];
  $scope.geoMarkerDetails = {};

  $scope.url              = GLOBAL.DOMAIN_NAME+'/getVehicleLocations';
  $scope.url_site         = GLOBAL.DOMAIN_NAME+'/viewSite';
  $scope.vehicle_list     = [];
  $scope.markerValue      = 0;
  $scope.popupmarker;
  var vehicIcon           = [];
  var vehicsIcon          = [];
  var gmarkersOsm         = [];
  var infoWindowOsm       = [];
  var VehiType, myIcons, osmInterVal, polygonval, siteLength;
  var siteDataCall    = 0;
  var polygonInitOsm  = 0;
  var polygonInitGoog = 0;
  
  $scope.lineCount_osm = 0;
  $scope.initGoogVal   = 0;
  $scope.plotVal       = 0; 

  /* $('#pauseButton').hide();
     $('#playButton').hide();      
     $('#stopButton').hide();   
     $('#replayButton').hide(); */

  $('#playButton').prop('disabled', true);
    $('#replayButton').prop('disabled', true);
      $('#stopButton').prop('disabled', true);
        $('#pauseButton').prop('disabled', true);




  $scope.addTinyMarker_osm = function(data){

    //console.log(data);

    var tinyIcon, pinImage, latlng,addresss;

    angular.forEach(data.vehicleLocations,function(val,key){

      latlng = new L.LatLng(val.latitude,val.longitude);
      $scope.tinyMarkers  = new L.marker(latlng);

      //console.log(val.address);

      if(val.position =='P') {
        
      //pinImage = 'assets/imgs/'+pos.data.position+'.png';
        pinImage = 'assets/imgs/flag.png';
        posval = 'Parked Time';

       

        var contentString = '<h3 class="infoh3">'+$scope.shortVehiId+'</h3><div class="nearbyTable02">'
         +'<div><table cellpadding="0" cellspacing="0"><tbody>'
         +'<tr><td>Location</td><td>'+val.address+'</td></tr>'
         +'<tr><td>Last seen</td><td>'+dateFormat(val.date)+'</td></tr>'
         +'<tr><td>'+posval+'</td><td>'+$scope.timeCalculate(val.parkedTime)+'</td></tr>'
         +'<tr><td>Trip Distance</td><td>'+val.tripDistance+'</td></tr>'
         +'</table></div>';

        var infowindow = new L.popup({maxWidth:600/*,maxHeight: 170*/}).setContent(contentString);
            
        infoWindowOsm.push(infowindow);

        tinyIcon = L.icon({
          iconUrl: pinImage,
          iconAnchor:[11,20],
          popupAnchor: [0, -20],
        });

        $scope.tinyMarkers.bindPopup(infowindow);
        $scope.tinyMarkers.setIcon(tinyIcon);
   

      } 
      //else 

      if(val.position =='S') {

      //pinImage = 'assets/imgs/A_'+pos.data.direction+'.png';
        pinImage = 'assets/imgs/orange.png';
        posval = 'Idle Time';



        var contentString = '<h3 class="infoh3">'+$scope.shortVehiId+'</h3><div class="nearbyTable02">'
         +'<div><table cellpadding="0" cellspacing="0"><tbody>'
         +'<tr><td>Location</td><td>'+val.address+'</td></tr>'
         +'<tr><td>Last seen</td><td>'+dateFormat(val.date)+'</td></tr>'
         +'<tr><td>'+posval+'</td><td>'+$scope.timeCalculate(val.idleTime)+'</td></tr>'
         +'<tr><td>Trip Distance</td><td>'+val.tripDistance+'</td></tr>'
         +'</table></div>';

        var infowindow = new L.popup({maxWidth:600/*,maxHeight: 170*/}).setContent(contentString);
            
        infoWindowOsm.push(infowindow);

        tinyIcon = L.icon({
          iconUrl: pinImage,
          iconAnchor:[11,20],
          popupAnchor: [0, -20],
        });

        $scope.tinyMarkers.bindPopup(infowindow);
        $scope.tinyMarkers.setIcon(tinyIcon);
     
      } 

      //else 

     if(val.position !='P' && val.position !='S') {
        pinImage = 'assets/imgs/trans.png';

           tinyIcon = L.icon({
             iconUrl: pinImage,
           });

        $scope.tinyMarkers.setIcon(tinyIcon);
      }

      gmarkersOsm.push($scope.tinyMarkers);
      $scope.tinyMarkers.addTo($scope.map_osm);

     // }

});

} 


  $scope.addMarkerstart_osm = function(data) {

      var startFlagVal = new L.LatLng(data.latitude, data.longitude);
      var myIconStart = L.icon({
          iconUrl: 'assets/imgs/startflag.png',
          iconSize: [68,70], 
          iconAnchor:[43,68],
          popupAnchor: [0, -65],
      });

      var contentString_osm = '<h3 class="infoh3">Vehicle Details ('+$scope.shortVehiId+') </h3><div class="nearbyTable02"><div>'
        +'<table cellpadding="0" cellspacing="0">'
        +'<tbody><!--<tr><td>Location</td><td>'+$scope.tempadd+'</td></tr>-->'
        +'<tr><td>Last seen</td><td>'+dateFormat(data.date)+'</td></tr>'
        +'<tr><td>Parked Time</td><td>'+$scope.timeCalculate(data.parkedTime)+'</td></tr>'
        +'<tr><td>Trip Distance</td><td>'+data.tripDistance+'</td></tr></table></div>';

        $scope.markerStart     = new L.marker();
        $scope.infowindowStart = new L.popup(/*{maxWidth:400,maxHeight: 170}*/);

        $scope.infowindowStart.setContent(contentString_osm);
        $scope.markerStart.bindPopup($scope.infowindowStart);
        $scope.markerStart.setIcon(myIconStart);
        $scope.markerStart.setLatLng(startFlagVal).addTo($scope.map_osm);

    }  

  $scope.endMarkerstart_osm = function(data){


        var endFlagval  = new L.LatLng(data.latitude, data.longitude); 

        var myIconEnd = L.icon({
          iconUrl: 'assets/imgs/endflag.png',
          iconSize: [68,70], 
          iconAnchor:[22,70],
          popupAnchor: [3, -65],
        });

       var contentString_osm2 = '<h3 class="infoh3">Vehicle Details ('+$scope.shortVehiId+') </h3><div class="nearbyTable02"><div>'
        +'<table cellpadding="0" cellspacing="0">'
        +'<tbody><!--<tr><td>Location</td><td>'+$scope.tempadd+'</td></tr>-->'
        +'<tr><td>Last seen</td><td>'+dateFormat(data.date)+'</td></tr>'
        +'<tr><td>Parked Time</td><td>'+$scope.timeCalculate(data.parkedTime)+'</td></tr>'
        +'<tr><td>Trip Distance</td><td>'+data.tripDistance+'</td></tr></table></div>';

        $scope.markerEnd     = new L.marker();
        $scope.infoWindowEnd = new L.popup(/*{maxWidth:400,maxHeight: 170}*/);

        $scope.infoWindowEnd.setContent(contentString_osm2);
        $scope.markerEnd.bindPopup($scope.infoWindowEnd);
        $scope.markerEnd.setIcon(myIconEnd);
        $scope.markerEnd.setLatLng(endFlagval).addTo($scope.map_osm);
  } 


   function utcdateConvert(milliseconds){
        //var milliseconds=1440700484003;
        var offset='+10';
        var d = new Date(milliseconds);
        utc = d.getTime() + (d.getTimezoneOffset() * 60000);
        nd  = new Date(utc + (3600000*offset));
        var result=nd.toLocaleString();
      return result;
      }

      function currentTimes() {
        var date = new Date();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';
          hours = hours % 12;
          hours = hours ? hours : 12; // the hour '0' should be '12'
          minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTimes = hours + ':' + minutes + ' ' + ampm;
                  
      return strTimes;
      }

      function getTodayDatesss() {
        var date = new Date();
        return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
      };


      function formatAMPM(date) {
        var date = new Date(date);
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
      return strTime;
      }


  $scope.initGoogle_Map = function(data){

            //console.log('google init...');

               if(data.vehicleLocations.length != 0) {
                    //console.log(parseInt(locs.tripDistance));
                      if(parseInt(data.tripDistance) > 200) {
                          $scope.zoomCtrlVal     =  true;
                          data.zoomLevel        =  12;
                          $scope.ZoomDisableBtn  =  true;
                          $scope.scrollsWheel    =  false;
                       // console.log(locs.tripDistance+"Active no Zoom");
                      } else{
                          $scope.zoomCtrlVal    = false;
                          $scope.ZoomDisableBtn = false;
                          $scope.scrollsWheel   = true;
                       }

                   /* var myOptions = {
                        zoom: Number(locs.zoomLevel),zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
                        center: new google.maps.LatLng(data.vehicleLocations[0].latitude, data.vehicleLocations[0].longitude),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                      //styles: [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]
                    }; */

                    var myOptions = {
                        zoom: Number(data.zoomLevel),
                        zoomControl: $scope.scrollsWheel,
                        zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
                        center: new google.maps.LatLng(data.vehicleLocations[0].latitude, data.vehicleLocations[0].longitude),
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        disableDoubleClickZoom: $scope.zoomCtrlVal,
                        scrollwheel: $scope.scrollsWheel,
                        disableDefaultUI: $scope.ZoomDisableBtn,
                     // styles: [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]
                    };

                    $scope.map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);

              //  }else{
              //    alert("Data Not Found!..");
              //    console.log('vehicleLocations not found!...'+data);
              //  }

              google.maps.event.addListener($scope.map, 'click', function(event) {
                $scope.clickedLatlng = event.latLng.lat() +','+ event.latLng.lng();
                $('#latinput').val($scope.clickedLatlng);
              });
                
           

                  $scope.polylineCtrl();
               // console.log($scope.path.length);

                if($scope.getValue != undefined){

                  $scope.getValueCheck($scope.getValue);
                }
              
                $scope.pointDistances = [];
                var sphericalLib     = google.maps.geometry.spherical;
                var pointZero        = $scope.path[0];
                var wholeDist        = sphericalLib.computeDistanceBetween(pointZero, $scope.path[$scope.path.length - 1]);
              
                $('#replaybutton').removeAttr('disabled');

            //}else{
            //  $('.error').show();
            //  $('#lastseen').html('<strong>From Date & time :</strong> -');
            //  $('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');
          // }
        //  }

      }

          
        var url = GLOBAL.DOMAIN_NAME+'/getGeoFenceView?vehicleId='+$scope.trackVehID;
    
        $scope.createGeofence(url);
        stopLoading();
         
           //}

          $scope.SiteCheckbox = {
             value1 : true,
             value2 : 'YES'
          }
  }


  $scope.initOsm_Map=function(data){
 
    if(polygonInitOsm==1){
      polygonInitOsm=0;
    }


    if($scope.map_osm!=undefined){

      // console.log("osm map undefined....");
      // $scope.map_osm.panTo(null);
      // window.clearInterval(timeInterval);  

        if($scope.markerss){
          $scope.map_osm.removeLayer($scope.markerss);
        }

       if($scope.polylines_osm){
         $scope.map_osm.removeLayer($scope.polylines_osm);
       }

      /* delete $scope.markerss;
         delete $scope.polylines_osm;
         delete myIcons; */
         
         $scope.map_osm.removeLayer($scope.tileOsm);
         $scope.map_osm.remove();
      
      /* delete $scope.map_osm;
         delete $scope.tileOsm;
         delete osmInterVal; */

         var osmInterVal;
         var myIcons;
         gmarkersOsm     = [];
         infoWindowOsm   = [];
        
         //stopLoading();
      } 
       
       // if($scope.map_osm ==undefined){
       // console.log('map_osm');

          var mapLink    = '<a href="http://207.154.194.241/nominatim/lf.html">OpenStreeetMap</a>';
          $scope.map_osm = new L.map('map_canvas2',{ center: [13.0827,80.2707],/* minZoom: 4,*/zoom: 8 });

          $scope.tileOsm = new L.tileLayer(
               'http://207.154.194.241/osm_tiles/{z}/{x}/{y}.png', {
               attribution: '&copy; '+mapLink+' Contributors',
            // maxZoom: 18,
            }).addTo($scope.map_osm);

           // console.log(data);

      if($scope.hisloc.vehicleLocations!=null){     

            var vehType=$scope.hisloc.vehicleLocations[0].vehicleType;
            vehicsIcon = vehiclesChange(vehType);

            $scope.osmPath=[];

            for(var i=0;i<$scope.hisloc.vehicleLocations.length;i++){

            //  console.log(data.vehicleLocations[i].latitude+" "+data.vehicleLocations[i].longitude);
                  $scope.osmPath.push(new L.LatLng($scope.hisloc.vehicleLocations[i].latitude, $scope.hisloc.vehicleLocations[i].longitude));
              
                  if($scope.hisloc.vehicleLocations[i].isOverSpeed == 'Y'){
                    var pscolorvals = '#ff0000';
                  } else {
                    var pscolorvals = '#6dd538';
                  }

              $scope.polylineOsmColor.push(pscolorvals);
            } 

           // console.log($scope.osmPath.length);
           // console.log($scope.osmPath);
           // console.log(vamoservice.iconURL($scope.hisloc.vehicleLocations[0]));
              $scope.markerss     = new L.marker();
              $scope.infowindowss = new L.popup({maxWidth:400,maxHeight: 170});

             // $scope.startFlagval = new L.LatLng($scope.hisloc.vehicleLocations[0].latitude, $scope.hisloc.vehicleLocations[0].longitude);
             
              var latLength   = $scope.hisloc.vehicleLocations.length-1;
              $scope.addMarkerstart_osm($scope.hisloc.vehicleLocations[0]);
              $scope.endMarkerstart_osm($scope.hisloc.vehicleLocations[latLength]);
              $scope.polylines_osm = L.polyline($scope.osmPath, {stroke:true, color:'#04B308', weight:5, fillColor:'#04B308'}).addTo($scope.map_osm);
              
             // $scope.hisloc2=data;

              if( $scope.lineCount_osm != 0){
                $scope.lineCount_osm = 0;
              }
                stopLoading();

              $scope.osmInterVal  = setInterval(function(){ osmPolyLine() },600);


              }else{

                stopLoading();
              }

              
        //}
    }


  function osmPolyLine(){

    //console.log('poly liness....');

    /*  $scope.polyline1[lineCount] = new google.maps.Polyline({
            map: $scope.map,
            path: [$scope.path[lineCount], $scope.path[lineCount+1]],
            strokeColor: $scope.polylinearr[lineCount],
            strokeOpacity: 0,
            //strokeOpacity: 1,
            strokeWeight: 3,
            icons: [{
                    icon: doubleLine,
                    offset: '50%',
                    repeat: '15px'
                }],
            clickable: true
        }); */

       // console.log(lineCount_osm);
       // console.log($scope.osmPath.length);
       // console.log($scope.hisloc2.vehicleLocations.length);

        if($scope.osmPath.length == $scope.lineCount_osm+1) {
         // console.log('Interval.......');

               clearInterval($scope.osmInterVal);

               $('#playButton').prop('disabled',  true);
               $('#replayButton').prop('disabled',false);
               $('#stopButton').prop('disabled', true);
               $('#pauseButton').prop('disabled', true);
               $scope.speedVal=1; 
      
          // $scope.endMarkerstart_osm($scope.endFlagval);
        }
        
        if($scope.osmPath.length != $scope.lineCount_osm+1) {

          /*  $scope.markerhead.setIcon({
                path:vehicIcon[0],
                scale:vehicIcon[1],
                strokeWeight: 1,
                fillColor: $scope.polylinearr[lineCount],
                fillOpacity: 1,
                anchor:vehicIcon[2],
                rotation: rotationd,
              });

           // var myIcon = L.icon({iconUrl: vamoservice.iconURL(pos.data)}); */
           // var myIcons = L.icon({iconUrl: vamoservice.iconURL($scope.hisloc.vehicleLocations[0])});
           // $scope.markerss  = new L.marker({icon:myIcons});

         //  if(lineCount_osm==0){   

         //   $scope.markerss.addTo($scope.map_osm);
        //  }

             // $scope.markerss.setLatLng($scope.osmPath[lineCount_osm]);
              
      var contenttsString = '<div style="padding:2px; padding-top:3px; width:175px;">'
      +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">LocTime</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+dateFormat($scope.hisloc.vehicleLocations[$scope.lineCount_osm].date)+'</span> </div>'
      +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">Speed</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+ $scope.hisloc.vehicleLocations[$scope.lineCount_osm].speed+'</span><span style="font-size:10px;padding-left:10px;">kmph</span></div>'
      +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">DistCov</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+$scope.hisloc.vehicleLocations[$scope.lineCount_osm].distanceCovered+'</span><span style="font-size:10px;padding-left:10px;">kms</span></div>'
      +'<div style="text-align:center;padding-top:3px;"><span style="font-size:10px;width:100px;">'+$scope.trimComma($scope.hisloc.vehicleLocations[$scope.lineCount_osm].address)+'</span></div>'
   // +'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
      +'</div>';

        myIcons = L.icon({
            iconUrl: vamoservice.iconURL($scope.hisloc.vehicleLocations[$scope.lineCount_osm]),
            iconSize: [40,40], 
            iconAnchor: [20,40],
            popupAnchor: [-1, -40],
        });

        $scope.infowindowss.setContent(contenttsString);
        $scope.markerss.bindPopup($scope.infowindowss);
        $scope.markerss.setIcon(myIcons);
        $scope.markerss.setLatLng($scope.osmPath[$scope.lineCount_osm]);

        if($scope.lineCount_osm==0){   
          $scope.markerss.addTo($scope.map_osm);
          $scope.markerss.openPopup();

          $scope.addTinyMarker_osm($scope.hisloc);
          $scope.tinyMarkers.addEventListener('click', function(e) {
                $scope.tinyMarkers.openPopup();             
          });
        }

           // var nVar      = [$scope.osmPath[lineCount_osm],$scope.osmPath[lineCount_osm+1]];
           // var polylines = L.polyline(nVar, {color: $scope.polylineOsmColor[lineCount_osm]}).addTo($scope.map_osm);
           // $scope.addTinyMarker_osm($scope.osmPath[lineCount_osm],$scope.hisloc.vehicleLocations[lineCount_osm]);
              $scope.map_osm.panTo($scope.osmPath[$scope.lineCount_osm]);

           // console.log($scope.osmPath[lineCount_osm]);

          $scope.lineCount_osm++;
        }

  }


  function drawPolygon(){

   if(polygonval==0){

        if(polygonInitGoog==0){

                  polygenList =[];
                  var latLanlist, seclat, seclan, sp; 

                  function centerMarker(listMarker){
                    var bounds = new google.maps.LatLngBounds();
                      for (i = 0; i < listMarker.length; i++) {
                            bounds.extend(listMarker[i]);
                      }
                    return bounds.getCenter()
                  }
                                 
                    if($scope.siteData.siteParent!=null){
                      siteLength = $scope.siteData.siteParent.length;
                      polygenList.push(new google.maps.LatLng(11, 11));
                    }

                  for (var listSite = 0; listSite < siteLength; listSite++) {
                    
                    var len = $scope.siteData.siteParent[listSite].site.length;
                    for (var k = 0; k < len; k++) {
                    // if(response.siteParent[i].site.length)
                    // {
                      var orgName = $scope.siteData.siteParent[listSite].site[k].siteName;
                      var splitLatLan = $scope.siteData.siteParent[listSite].site[k].latLng.split(",");
                      
                      polygenList = [];
                      for(var j = 0; splitLatLan.length>j; j++)
                          {
                            sp  = splitLatLan[j].split(":");
                            polygenList.push(new google.maps.LatLng(sp[0], sp[1]));
                            // console.log(sp[0]+' ---- '+ sp[1])
                            // latlanList.push(sp[0]+":"+sp[1]);
                            // seclat        = sp[0];
                            // seclan        = sp[1];
                          }
                      
                          polygenColors = new google.maps.Polygon({
                            path: polygenList,
                            strokeColor: "#282828",
                            strokeWeight: 1,
                            fillColor: '#808080',
                            fillOpacity: 0.50,
                            //map: scope.map
                          });

                          polygenColors.setMap($scope.map);
                          labelAnchorpos = new google.maps.Point(19, 0);  //12, 37

                          markers = new MarkerWithLabel({

                            position: centerMarker(polygenList), 
                         // map: scope.map,
                            icon: 'assets/imgs/area_img.png',
                            labelContent: orgName,
                            labelAnchor: labelAnchorpos,
                            labelClass: "labels", 
                            labelInBackground: false
                          });

                          markers.setMap($scope.map);
                          polygonInitGoog=1;
                        // scope.map.setCenter(centerMarker(polygenList)); 
                        // scope.map.setZoom(14); 
                        // }
                      }
                  }
                }

    } else if(polygonval==1) {

      console.log('osm polygon');

        if(polygonInitOsm==0){

                  var latLngPoly, sp_osm;
              
                  var iconPoly = L.icon({
                    iconUrl: 'assets/imgs/trans.png',
                    iconAnchor:[0,0],
                    labelAnchor: [-15,10],
                  }); 

                  if($scope.siteData.siteParent!=null) {
                    siteLength = $scope.siteData.siteParent.length;
                  }
               
                  for(var l=0;l<siteLength;l++){
                    
                    var lengths = $scope.siteData.siteParent[l].site.length;

                    for(var k=0;k<lengths;k++){
                    
                    //if(response.siteParent[i].site.length){

                        var orgNam     = $scope.siteData.siteParent[l].site[k].siteName;
                        var spLatLangs = $scope.siteData.siteParent[l].site[k].latLng.split(",");
                        var polygenList_Osm =[];

                        for(var j=0;spLatLangs.length>j;j++) {
                          sp_osm = spLatLangs[j].split(":");
                          latLngPoly = new L.LatLng(sp_osm[0],sp_osm[1]);
                          polygenList_Osm.push(latLngPoly);
                        }

                        $scope.polygons = L.polygon(polygenList_Osm,{ className: 'polygonOsm' }).addTo($scope.map_osm);
                        $scope.markerPoly = new L.marker(latLngPoly);
                        $scope.markerPoly.setIcon(iconPoly).bindLabel(orgNam, { noHide: true, className: 'polygonLabel', clickable: true, direction:'auto' });
                        $scope.markerPoly.addTo($scope.map_osm);
                        polygonInitOsm=1;
                    }
                  }

                // $scope.map_osm.fitBounds($scope.polygons.getBounds());
              }
    }

}
  

  $scope.getValueCheck = function(getStatus){

    $scope.getValue = getStatus;

    if($scope.getValue == 'YES') {
          $scope.hideMe = false;
          $scope.hideMarker= false;

        (function(){

          if(siteDataCall==0){
            $http.get($scope.url_site).success(function(response){
              $scope.siteData=response;
              siteDataCall=1;
              drawPolygon();
            });
            
          } else if(siteDataCall==1){
              if( $scope.siteData != undefined && $scope.siteData!=null ){
                 drawPolygon();
              }
          }
          
      }());

    } else if ($scope.getValue == 'NO') {
      $scope.hideMe = true;
    }
  }

var osmInitsVal=0;
// $scope.$watch("hisurl", function (val) {

$scope.initMap  = function(vals,initVal){

  //console.log('map init...');
  //console.log(initVal);
    startLoading();

    if(vals == 0){
      polygonval = 0;
    } else if(vals == 1){
      polygonval = 1;
    } 

    if(initVal==true) {

      $scope.path   = [];
      $scope.hisloc = [];

      var locs;
      var geoUrl  = GLOBAL.DOMAIN_NAME+'/viewSite';
      var polygenColors,labelAnchorpos,markers;

      $http.get($scope.hisurl).success(function(data){

          locs            = data;
          $scope.hisloc   = data;

        //  console.log($scope.hisloc );

          $scope._tableValue(locs);


         if($scope.hisloc.vehicleLocations==null ){
          stopLoading();

         }

          if(data.fromDateTime=='' || data.fromDateTime==undefined || data.fromDateTime=='NaN-aN-aN'){ 
            if(data.error==null){}else{
              $('.alert-danger').show();
              // $('#myModal').modal();
            }
            $('#lastseen').html('<strong>From Date & time :</strong> -');
            $('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');

              $scope.fromdate   =  getTodayDatesss();
              $scope.todate     =  $scope.fromdate;
              $scope.fromtime   =  '12:00 AM';
              $scope.totime     =  currentTimes();

          } else {

            $('.alert-danger').hide();

            if(data.error==null){
  
              $scope.fromNowTS = data.fromDateTimeUTC;
              $scope.toNowTS   = data.toDateTimeUTC; 
       
              $scope.fromtime  = formatAMPM($scope.fromNowTS);
              $scope.totime    = formatAMPM($scope.toNowTS);
              $scope.fromdate  = $scope.getTodayDate($scope.fromNowTS);
              $scope.todate    = $scope.getTodayDate($scope.toNowTS);
              
              $('#vehiid h3').text(locs.shortName);
              $('#lastseen').html('<strong>From Date & time :</strong> '+ new Date(data.fromDateTimeUTC).toString().split('GMT')[0]);
              $('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> '+ new Date(data.toDateTimeUTC).toString().split('GMT')[0]);

            }
        } 

    if(vals==0){

   
        $('#playButton').prop('disabled', true);
        $('#replayButton').prop('disabled', false);
        $('#stopButton').prop('disabled', false);
        $('#pauseButton').prop('disabled', false);

       // console.log("gooooogle...........");

        clearInterval($scope.osmInterVal);

        document.getElementById("map_canvas").style.display="block"; 
          document.getElementById("map_canvas2").style.display="none"; 

        if($scope.initGoogVal==0){
           $scope.initGoogle_Map($scope.hisloc);
           $scope.initGoogVal=1;
        }else if($scope.initGoogVal==1){
           $scope.polylineCtrl();
        }

        $scope.googleMap=true;
        $scope.osmMap=false;

    } else if(vals==1){
        

        $('#playButton').prop('disabled', true);
        $('#replayButton').prop('disabled', false);
        $('#stopButton').prop('disabled', false);
        $('#pauseButton').prop('disabled', false);

   /*  $('#pauseButton').show();
       $('#playButton').hide();      
    // $('#stopButton').show();   
       $('#replayButton').show();*/

     // console.log('osm mapsssssss...');

      clearInterval($scope.osmInterVal);

        document.getElementById("map_canvas").style.display="none"; 
          document.getElementById("map_canvas2").style.display="block"; 

        //  if(osmInitsVal==0){
            $scope.initOsm_Map();
          //}

          $scope.googleMap=false;
          $scope.osmMap=true;
         // osmInitsVal++;
      }

   }).error(function(){ 
        stopLoading();
      }); 

 } else {

      if(vals==0){

       // console.log('google...');

        $('#playButton').prop('disabled', true);
        $('#replayButton').prop('disabled', false);
        $('#stopButton').prop('disabled', false);
        $('#pauseButton').prop('disabled', false);

        //console.log("gooooogle elseee...........");

        clearInterval($scope.osmInterVal);
         window.clearInterval(timeInterval);  

        document.getElementById("map_canvas").style.display="block"; 
          document.getElementById("map_canvas2").style.display="none"; 

         // console.log($scope.hisloc);

        // $scope.initGoogVal=1; 
       //  $scope.plotVal=0;

      if( $scope.hisloc.vehicleLocations !=null){

         pcount = 0; 
         //$scope.initGoogle_Map($scope.hisloc);

      // $scope.initGoogle_Map($scope.hisloc);
        $scope.polylineCtrl();

      }else{
        stopLoading();
      }

         $scope.googleMap=true;
         $scope.osmMap=false;

     } else if(vals==1){


        $('#playButton').prop('disabled', true);
        $('#replayButton').prop('disabled', false);
        $('#stopButton').prop('disabled', false);
        $('#pauseButton').prop('disabled', false);

        // $scope.initGoogVal=1; 
        // $scope.plotVal=0;
        // console.log('osm mapsssssss elseeee...');

        clearInterval($scope.osmInterVal);

        document.getElementById("map_canvas").style.display="none"; 
        document.getElementById("map_canvas2").style.display="block"; 

        $scope.initOsm_Map();
        $scope.googleMap = false;
        $scope.osmMap    = true;

      }

 }



}

// });


  /* $scope.changeMap = function(val) {

    if(val==0){

      $scope.googleMap=false;
        $scope.osmMap=true;

      document.getElementById("map_canvas").style.display="block"; 
        document.getElementById("map_canvas2").style.display="none"; 

      $scope.initGoogle_Map($scope.hisloc);

    } else if(val==1){

       $scope.googleMap=false;
         $scope.osmMap=true;

        document.getElementById("map_canvas").style.display="none"; 
          document.getElementById("map_canvas2").style.display="block"; 

        $scope.initOsm_Map();
    }
  } */

  $scope.getTodayDate  =  function(date) {
    var date = new Date(date);
    return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
  }

  $scope.parseInts=function(data){
     return parseInt(data);
  }

  $scope.alladdress    = [];
  $scope.moveaddress   = [];
//$scope.overaddress   = [];
  $scope.parkaddress   = [];
  $scope.idleaddress   = [];
//$scope.fueladdress   = [];
  $scope.igniaddress   = [];
//$scope.acc_address   = [];
  $scope.stop_address  = [];

  // loading start function
  // $scope.startLoading    = function () {
  //  $('#status').show(); 
  //  $('#preloader').show();
  // };

  // //loading stop function
  // $scope.stopLoading   = function () {
  //  $('#status').fadeOut(); 
  //  $('#preloader').delay(350).fadeOut('slow');
  //  $('body').delay(350).css({'overflow':'visible'});
  // };

  function sessionValue(vid, gname){
    sessionStorage.setItem('user', JSON.stringify(vid+','+gname));
    $("#testLoad").load("../public/menu");
  }

  $scope.loading  = true;
  
  function _pairFilter(_data, _yes, _no, _status)
    {
      var _checkStatus =_no ,_pairList  = [];
      angular.forEach(_data, function(value, key){
        
        if(_pairList.length <= 0){
              if(value[_status] == _yes)
                _pairList.push(value)
            } else if(_pairList.length >0 )
            {
              if(value[_status] == _checkStatus){
                  _pairList.push(value)
                  if(_pairList[_pairList.length-1][_status] == _yes)
                      _checkStatus = _no;
                  else
                      _checkStatus = _yes
              }
            }

      });

      if(_pairList.length>1)
        if(_pairList.length%2==0)
          return _pairList;
        else{
           _pairList.pop();
          return _pairList;
      }

    }

/*  function filter(obj){
      var _returnObj = [];
      if(obj)
        angular.forEach(obj,function(val, key){
          if(val.fuelLitre >0)
            _returnObj.push(val)
        })
      return _returnObj;
    } */


    function filter(obj,name){
      var _returnObj = [];
    /*  if(name=='fuel'){
        angular.forEach(obj,function(val, key){

          if(val.fuelLitre >0)
          {
            _returnObj.push(val)
          }
        })
      } */
      
      if(name=='stoppage'){

        angular.forEach(obj,function(val, key){

          if(val.stoppageTime >0)
          {
            _returnObj.push(val)
          }
        })
      }
    /*  else if(name=='ovrspd'){

        angular.forEach(obj,function(val, key){

          if(val.overSpeedTime >0)
          {
            _returnObj.push(val)
          }
        })
      }*/
    return _returnObj;
  }

/*    $scope.fuelChart  =   function(data){
    var ltrs    = [];
    var fuelDate  = [];
    try{
      if(data.length)
        for (var i = 0; i < data.length; i++) {
          if(data[i].fuelLitre !='0' || data[i].fuelLitre !='0.0')
          {
            ltrs.push(data[i].fuelLitre);
            var dar = $filter('date')(data[i].date, "dd/MM/yyyy HH:mm:ss");
            fuelDate.push(dar)
          }
        };
    }
    catch (err){
      console.log(err.message)
    }
    
  $(function () {
   
        $('#container').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Fuel Report'
            },
          
             xAxis: {
            categories: fuelDate
            },
            
            yAxis: {
                title: {
                    text: 'Fuel'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },

            series: [{
                type: 'area',
                name: 'Fuel Level',
                data: ltrs
            }]
        });

});
  }


  $scope.speedKms   =   function(data){
    var ltrs    = [];
    var fuelDate  = [];
    try{
      if(data.length)
        for (var i = 0; i < data.length; i++) {
          if(data[i].speed !='0')
          {
            ltrs.push(data[i].speed);
            var dar = $filter('date')(data[i].date, "dd/MM/yyyy HH:mm:ss");
            fuelDate.push(dar)
          }
        };
    }
    catch (err){
      console.log(err.message)
    }
    
  $(function () {
   
        $('#speedGraph').highcharts({
            chart: {
                zoomType: 'x'
            },
        title: {
            text: ''
        },
       subtitle: {
            text: 'Speed km/h'
        },
        xAxis: {
            categories: fuelDate
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
        // plotOptions: {
        //     line: {
        //         dataLabels: {
        //             enabled: false
        //         },
        //         enableMouseTracking: true
        //     }
        // },
        // legend: {
        //     enabled: false
        // },

        series: [{
          type: 'area',
            name: 'km/h',
            data: ltrs
        }]
        });

  });
}
*/
  $scope._tableValue = function(_value){
//if(_value && _value.vehicleLocations != null){
    $scope.moveaddress   = [];
    $scope.alladdress    = [];
  //$scope.overaddress   = [];
    $scope.parkaddress   = [];
    $scope.idleaddress   = [];
  //$scope.fueladdress   = [];
    $scope.igniaddress   = [];
  //$scope.acc_address   = [];
    $scope.stop_address  = [];
    $scope.parkeddata    = [];
    $scope.overspeeddata = [];
    $scope.allData       = [];
    $scope.movementdata  = [];
    $scope.idlereport    = [];
    $scope.ignitValue    = [];
  //$scope.acReport      = [];
    $scope.stopReport    = [];
  //$scope.fuelValue     = [];

    if(_value && _value.vehicleLocations != null) {

      var ignitionValue     = ($filter('filter')(_value.vehicleLocations, {'ignitionStatus': "!undefined"}))
      $scope.parkeddata     = ($filter('filter')(_value.vehicleLocations, {'position':"P"}));
      $scope.overspeeddata  = ($filter('filter')(_value.vehicleLocations, {'isOverSpeed':"Y"}));
  //  $scope.overspeeddata  = filter(_value.vehicleLocations,'ovrspd');
      $scope.allData        = ($filter('filter')(_value.vehicleLocations, {}));
      $scope.movementdata   = ($filter('filter')(_value.vehicleLocations, {'position':"M"}));
      $scope.idlereport     = ($filter('filter')(_value.vehicleLocations, {'position':"S"}));
      $scope.ignitValue     = _pairFilter(ignitionValue, 'ON', 'OFF', 'ignitionStatus');
    //$scope.acReport       = _pairFilter(_value.vehicleLocations, 'yes', 'no', 'vehicleBusy');
    //$scope.fuelValue      =  filter(_value.vehicleLocations);
    //$scope.fuelValue      =  filter(_value.vehicleLocations,'fuel');
      $scope.stopReport     =  filter(_value.vehicleLocations,'stoppage');
      
      // console.log($scope.ignitValue);
    }
  // $scope.speedKms($scope.movementdata);
  // $scope.fuelChart($scope.fuelValue);
}

  function google_api_call(tempurlMo, index1, _stat) {
  // console.log(' temperature ')
  // $scope.av           = "adasdas"
  // $scope.moveaddress  = [];
  // $scope.overaddress  = [];
  // $scope.parkaddress  = [];
  // $scope.idleaddress  = [];
  // $scope.fueladdress  = [];
  // $scope.igniaddress  = [];
  // $scope.acc_address  = [];
    $http.get(tempurlMo).success(function(data){
      //  $.ajax({
           // async: false,
           // method: 'GET', 
           // url: tempurlMo,
           // success: function (data) {
              // console.log(data)
              // aUthName = response;
              // sessionStorage.setItem('apiKey', JSON.stringify(aUthName[0]));
              // sessionStorage.setItem('userIdName', JSON.stringify('username'+","+aUthName[1]));
       switch (_stat){
        case 'all':
          console.log(_stat);
          $scope.alladdress[index1] = data.results[0].formatted_address;
        break;
        case 'movement':
          console.log(_stat);
          $scope.moveaddress[index1] = data.results[0].formatted_address;
        break;
        case 'parked':
          console.log(_stat);
          $scope.parkaddress[index1] = data.results[0].formatted_address;
        break;
        case 'idle':
          console.log(_stat);
          $scope.idleaddress[index1] = data.results[0].formatted_address;
        break;
        case 'ignition':
          console.log(_stat);
          $scope.igniaddress[index1] = data.results[0].formatted_address;
        break;
          case 'stoppage':
          console.log(_stat);
          $scope.stop_address[index1] = data.results[0].formatted_address;
        break;
      }
        //     }
        // })
      
      // $scope.moveaddress[index1] = data.results[0].formatted_address;
      // var t = vamo_sysservice.geocodeToserver(latMo,lonMo,data.results[0].formatted_address);
    })
  };
  
var queue1 = [];
  var delaying = (function () {
      
      function processQueue1() {
        if (queue1.length > 0) {
          setTimeout(function () {
            queue1.shift().cb();
            processQueue1();
          }, queue1[0].delay);
        }
      }

      return function delayed(delay, cb) {
        queue1.push({ delay: delay, cb: cb });

        if (queue1.length === 1) {
          processQueue1();
        }
      };

  }());

  $scope.recursive   = function(location_over, _stat, _address){
      // console.log(va)
      var indexs = 0;
      angular.forEach(location_over, function(value, primaryKey){
        indexs = primaryKey;
        if(location_over[indexs].address == undefined && _address[indexs] == undefined)
        {
          //console.log(' address over speed'+indexs)
          var latOv    =  location_over[indexs].latitude;
          var lonOv    =  location_over[indexs].longitude;
          var tempurlOv  =  "https://maps.googleapis.com/maps/api/geocode/json?latlng="+latOv+','+lonOv+"&sensor=true";
          //console.log(' in overspeed '+indexs)
          delaying(3000, function (indexs) {
                return function () {
                  google_api_call(tempurlOv, indexs, _stat);
                };
              }(indexs));
        }
      })
  }

  $scope.addressResolve   = function(tabVal){
    
    queue1 = [];
    switch (tabVal){
      case 'all':
        $scope.recursive($scope.allData, tabVal, $scope.alladdress);
      break;

      case 'movement':
        $scope.recursive($scope.movementdata, tabVal, $scope.moveaddress);
      break;

      case 'parked': 
        $scope.recursive($scope.parkeddata, tabVal, $scope.parkaddress);
      break;

      case 'idle':
        $scope.recursive($scope.idlereport, tabVal, $scope.idleaddress);
      break;

      case 'ignition':
        $scope.recursive($scope.ignitValue, tabVal, $scope.igniaddress);
      break;

      case 'stoppage':
        $scope.recursive($scope.stopReport, tabVal, $scope.stop_address);
      break;
    }
  }

  // $http.get($scope.url).success(function(data){
    
  //  $scope.locations = data;
  //  $scope.groupname = data[0].group;
  //  $scope.vehicleId = data[0].vehicleLocations[0].vehicleId;
  //  sessionValue($scope.vehicleId, $scope.groupname)
  //  if(getParameterByName('vehicleId')=='' && getParameterByName('gid')==''){
  //    $scope.trackVehID =$scope.locations[0].vehicleLocations[3].vehicleId;
  //    $scope.shortVehiId =$scope.locations[0].vehicleLocations[3].shortName;
  //    $scope.selected=0;
  //  }else{
  //    $scope.trackVehID =getParameterByName('vehicleId');
  //    for(var i=0; i<$scope.locations[0].vehicleLocations.length;i++){
  //      if($scope.locations[0].vehicleLocations[i].vehicleId==$scope.trackVehID){
  //        $scope.selected=i;
  //      }
  //    }
  //  }
  // }).error(function(){ /*alert('error'); */});

  $scope.getOrd   = function()
  {
    $scope.error = "";
    $scope.routeName = "";
    $scope.selectedOrdId = "";

    getRouteNames();
  }

  function showRoutes(setRoute){

    if(setRoute != '' && setRoute == 'routes'){
      $("#myModal1").modal();
      $scope.getOrd();
    }
  }

  function addZero(i) {
      if (i < 10) {
        i = "0" + i;
      }
    return i;
    }

   function timeNow24Hrs(){

     var d = new Date();
     var h = addZero(d.getHours());
     var m = addZero(d.getMinutes());
     var s = addZero(d.getSeconds());

    //return h + ":" + m + ":" + s;
   return h+":00:00";
   }

   function getTodayDatess() {
     var date = new Date();
     return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + (date.getDate())).slice(-2);
   };

    
(function init(){
   // startLoading();

   var mapsVal=sessionStorage.getItem('mapNo');
    //console.log(mapsVal);

    if(mapsVal==0){
       $scope.maps_no  = 0;
       $scope.mapsHist = 0;
         $scope.googleMap  = true;
           $scope.osmMap   = false;

       document.getElementById("map_canvas2").style.display="none"; 
         document.getElementById("map_canvas").style.display="block"; 
    }else if(mapsVal==1){
       $scope.maps_no  = 1;
       $scope.mapsHist = 1;

           $scope.osmMap    = true;
             $scope.googleMap  = false;
                 
       document.getElementById("map_canvas").style.display="none"; 
         document.getElementById("map_canvas2").style.display="block"; 
    }

    var url;
    $scope.groupname = getParameterByName('gid');
    url =(getParameterByName('vehicleId')=='' && getParameterByName('gid')=='')?$scope.url:$scope.url+'?group='+$scope.groupname;
    $http.get(url).success(function(response){

      $scope.locations  = response;

      if (getParameterByName('gid') == '' && getParameterByName('vehicleId') == '') {

        $scope.groupname  = response[0].group;
        $scope.trackVehID   = $scope.locations[0].vehicleLocations[0].vehicleId;
        $scope.shortVehiId  = $scope.locations[0].vehicleLocations[0].shortName;
        $scope.selected   = 0;
          //$('#vehiid h3').text($scope.shortVehiId);
        VehiType = $scope.locations[0].vehicleLocations[0].vehicleType;
      
      } else { 

        $scope.trackVehID   = getParameterByName('vehicleId');
        angular.forEach(response, function(value, key){
          if($scope.groupname   == value.group)
          { 
            angular.forEach(value.vehicleLocations, function(val, k){

                            $scope.vehicle_list.push({'vehiID' : val.vehicleId, 'vName' : val.shortName});

              if(val.vehicleId == $scope.trackVehID){
                $scope.selected   = k;
                $scope.shortVehiId  = val.shortName;
                //$('#vehiid h3').text(val.shortName);
                VehiType = val.vehicleType;
              }
            })
          //$scope.locations = response;
          } 
        })

      }
    sessionValue($scope.trackVehID, $scope.groupname)
    //$scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID;
            var todayTime = new Date();
        var currentTimeRaw = new Date();
        
        todayTime.setHours(todayTime.getHours()-1);
          todayTime.setMinutes(todayTime.getMinutes()-59);
          var timeShow = $filter('date')(todayTime, 'HH:mm:ss');

        $scope.fromTimes = timeShow; 
      //$scope.toTimes   = timeNow24Hrs();
        $scope.fromDates = getTodayDatess();
      //$scope.toDates   = getTodayDatess();
        
      if((checkXssProtection($scope.fromDates) == true) && (checkXssProtection($scope.fromTimes) == true)){
       //$scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+$scope.fromDates+'&fromTime='+$scope.fromTimes+'&toDate='+$scope.toDates+'&toTime='+$scope.toTimes+'&fromDateUTC='+utcFormat($scope.fromDates,$scope.fromTimes)+'&toDateUTC='+utcFormat($scope.toDates,$scope.toTimes);
         $scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+$scope.fromDates+'&fromTime='+$scope.fromTimes+'&fromDateUTC='+utcFormat($scope.fromDates,$scope.fromTimes);
    
          if($scope.googleMap==true && $scope.osmMap==false){
               $scope.initMap(0,true);
          } else if($scope.osmMap==true && $scope.googleMap==false){
               $scope.initMap(1,true);
          }
      }

    $('.nav-second-level li').eq(0).children('a').addClass('active');
    // stopLoading();
    })
    // } else{

    // }
    
  }());

  $scope.trimColon = function(textVal){
    return textVal.split(":")[0].trim();
  }

  $scope.createGeofence=function(url){
    //console.log('--->'+url)
    if($scope.cityCirclecheck==false){
      $scope.cityCirclecheck=true;
    }
    if($scope.cityCirclecheck==true){
      for(var i=0; i<$scope.cityCircle.length; i++){
        $scope.cityCircle[i].setMap(null);
      }
      for(var i=0; i<geomarker.length; i++){
        geomarker[i].setMap(null);
        geoinfo[i].setMap(null);
      }
    }
    var defdata = $q.defer();
    $http.get(url).success(function(data){
      //console.log(' url '+data)
      $scope.geoloc = defdata.resolve(data);
      $scope.geoStop = data;
      geomarker=[];
      if (typeof(data.geoFence) !== 'undefined' ) { 
        
        if(data.geoFence!=null){
        for(var i=0; i<data.geoFence.length; i++){
          if(data.geoFence[i]!=null){
          var populationOptions = {
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.02,
                map: $scope.map,
                center: new google.maps.LatLng(data.geoFence[i].latitude,data.geoFence[i].longitude),
                radius: parseInt(data.geoFence[i].proximityLevel)
          };
          
          $scope.cityCircle[i] = new google.maps.Circle(populationOptions);
          var centerPosition = new google.maps.LatLng(data.geoFence[i].latitude, data.geoFence[i].longitude);
          var labelText = data.geoFence[i].poiName;
          $scope.infowin(data.geoFence[i]);
          
          var myOptions = {
            content: labelText,
            boxStyle: {textAlign: "center", fontSize: "8pt", fontColor: "#0031c4", width: "100px"
            },
            disableAutoPan: true,
            pixelOffset: new google.maps.Size(-50, 0),
            position: centerPosition,
            closeBoxURL: "",
            isHidden: false,
            pane: "mapPane",
            enableEventPropagation: true
          };

            var labelinfo = new InfoBox(myOptions);
            labelinfo.open($scope.map);
            labelinfo.setPosition($scope.cityCircle[i].getCenter());
             }
        }
      }
    }
  });
}

  $scope.goeValueChange = function()
  {
    //$scope.geoStops;
    //console.log(' ---- >'+$scope.geoStops)
    $scope.map.setZoom(13)
    if($scope.geoStops!=0)
    {
      for(var i = 0; i < $scope.geoStop.geoFence.length; i++)
      {
        if($scope.geoStops==$scope.geoStop.geoFence[i].stopName)
        {
          //$scope.map.setZoom(18);
          $scope.map.setCenter(geomarker[i].getPosition());
          animateMapZoomTo($scope.map, 20);
        }
      }
    }
  }

 // getting Org ids
  $scope.$watch("url_site", function (val) {

      $http.get($scope.url_site).success(function(response){
        $scope.orgIds   = response.orgIds;
        showRoutes(getParameterByName('rt'))
      });
  });
  
  function getRouteNames(){

    if($scope.orgIds){
      $.ajax({
        
        async: false,
            method: 'GET', 
            url: "storeOrgValues/val",
            data: {"orgId":$scope.orgIds},
            success: function (response) {
              $scope.routedValue = response;
            }
        });
    }
  }

    
  
  $scope.getMap = function(routesMap){

    $scope.windowRouteName=routesMap;
       
    if(!routesMap =='' && $scope.orgIds.length>0 && !$scope.orgIds==''){
        $.ajax({
          async:false,
          method:'GET',
          url:"storeOrgValues/mapHistory",
          data:{"maproute":routesMap,"organId":$scope.orgIds},
          success:function(response){
            $scope.mapValues =response;
            getMapArray($scope.mapValues);                       }
        });
    }        
  }

var latLanpath=[];
var markerList=[];
var pathCoords=[];


function clearMap(path){
    
    for (var i=0; i<latLanpath.length; i++){
      latLanpath[i].setMap(null);
    }

    for (var i = 0; i < markerList.length; i++) {
      markerList[i].setMap(null);
    }
}

function getMapArray(values){

    var latSplit ;
    var latLangs=values;
    clearMap(pathCoords);
    pathCoords=[];

    for(i=0;i<latLangs.length;i++){

        latSplit = latLangs[i].split(",");
        pathCoords.push({"lat": latSplit[0],"lng": latSplit[1]});
    }

  dvMap.setCenter(new google.maps.LatLng(pathCoords[0].lat,pathCoords[0].lng)); 
  autoRefresh(dvMap);
}

function myMap(){

  var mapCanvas=document.getElementById("dvMap");
  var mapOptions={
    center: new google.maps.LatLng(0,0),
      zoom: 8,
      mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  dvMap=new google.maps.Map(mapCanvas,mapOptions);
}
myMap();

$('#myModal2').on('shown.bs.modal', function () {
  google.maps.event.trigger(dvMap, "resize");
});

function moveMarker(marker, latlng) {
  
  marker.setPosition(latlng);
}

function autoRefresh(map) {
  var i, route, marker;
  
  route = new google.maps.Polyline({
    path: [],
    geodesic : true,
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 2,
    editable: false,
    map:map
  });
                
  latLanpath.push(route);
    marker = new google.maps.Marker({map:map,icon:""});
    markerList.push(marker);
  
    for(i=0; i<pathCoords.length; i++) {
    
      setTimeout(function (coords){
        var latlng = new google.maps.LatLng(coords.lat, coords.lng);
        route.getPath().push(latlng);
                moveMarker( marker, latlng);
                map.panTo(latlng);
            
      }, 0* i, pathCoords[i]);
    }
}
  
  /*
    show table in view
  */

  $( "#historyDetails" ).hide();

  $scope.hideShowTable  = function(){
    var btValue = ($("#btnValue").text()=='ShowDetails')?'HideDetails':'ShowDetails';
    $('#btnValue').text(btValue);
    $( "#historyDetails" ).fadeToggle("slow");
  }
 
  /*
    Store the routes in redis
  */
  $scope.routesSubmit =  function(){
    $scope.error = "";
    console.log(' get org ids ')
    var fromdate  = document.getElementById('dateFrom').value;
    var todate    = document.getElementById('dateTo').value;
    var fromtime  = document.getElementById('timeFrom').value;
    var totime    = document.getElementById('timeTo').value;
    if((checkXssProtection(fromdate) == true) && (checkXssProtection(todate) == true) && (checkXssProtection(fromtime) == true) && (checkXssProtection(totime) == true) && (checkXssProtection($scope.routeName) == true))
      try{
        $scope.error = (!fromdate || !todate || !fromtime || !totime)? "Please enter the Route Name":  "";
        if($scope.error == "")
          {
            var utcFrom   = utcFormat(fromdate, $scope.timeconversion(fromtime));
            var utcTo     = utcFormat(todate, $scope.timeconversion(totime));
            var _routeUrl   = GLOBAL.DOMAIN_NAME+'/addRoutesForOrg?vehicleId='+$scope.trackVehID+'&fromDateUTC='+utcFrom+'&toDateUTC='+utcTo+'&routeName='+removeSpace_Join($scope.routeName);
            if(!$scope.trackVehID == "" && !$scope.routeName == "")
              $http.get(_routeUrl).success(function(response){
                if(response.trim()== "false"){
                  $scope.error = "* Route Name already exists"
                } else if (response.trim()== "true"){
                  $scope.error = "* Successfully saved";
                  getRouteNames();
                } else
                  $scope.error = "* Try again"
              })
            else
              throw $scope.error;

          } else 
            throw $scope.error;
          getRouteNames();
      }catch(err){
        console.log(' error --> '+err)
        $scope.error  = "* Please enter the Route Name";
      }
  }

  $scope.popUpMarkerNull =function()
  {
    if($scope.popupmarker !== undefined){
        $scope.popupmarker.setMap(null);
        $scope.infowindow.close();
      $scope.infowindow.setMap(null);
    }
  }

  
  $scope.markerPoup   =   function(val) {

   if($scope.googleMap==true){

    $scope.popUpMarkerNull();
    $scope.popupmarker = new google.maps.Marker({
          icon: 'assets/imgs/popup.png',
    });

    var latLngs = new google.maps.LatLng(val.latitude, val.longitude);

    $scope.popupmarker.setMap($scope.map);
    $scope.popupmarker.setPosition(latLngs);

    var contentString = '<div style="padding:2px; padding-top:3px; width:190px;">'
    +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;color:#666463;">Date&amp;Time</b> <span style="padding-left:9px;">-</span> <span style="font-size:10px;padding-left:3px;color:#666463;font-weight:bold;border-bottom:0.5px;">'+dateFormat(val.date)+'</span></div>'
    +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;color:#666463;">Speed</b> <span style="padding-left:9px;">-</span> <span style="font-size:10px;padding-left:3px;color:#666463;font-weight:bold;border-bottom:0.5px;">'+ val.speed +'</span><span style="font-size:10px;padding-left:10px;border-bottom:0.5px;">kmph</span></div>'
    +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;color:#666463;">OdoDist</b> <span style="padding-left:9px;">-</span> <span style="font-size:10px;padding-left:3px;color:#666463;font-weight:bold;border-bottom:0.5px;">'+val.odoDistance+'</span><span style="font-size:10px;padding-left:10px;border-bottom:0.5px;">kms</span></div>'
    +'<div style="text-align:center;padding-top:3px;"><span style="font-size:10px;width:100px;">'+$scope.trimComma(val.address)+'</span></div>'
 // +'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
    +'</div>';

 /* $scope.infowindow = new InfoBubble({
      maxWidth: 400,  
      maxHeight:170,
      content: contentString,
    });*/

    $scope.infowindow = new google.maps.InfoWindow({ maxWidth: 200, maxHeight:150 });

    $scope.infowindow.setContent(contentString); 
    $scope.infowindow.setPosition(latLngs);
    $scope.infowindow.open($scope.map, $scope.popupmarker);

    google.maps.event.addListener($scope.infowindow,'closeclick',function(){
       $scope.popUpMarkerNull();
    }); 

   } else if($scope.osmMap==true){

    if($scope.popupmarker_osm!=undefined){
       $scope.map_osm.removeLayer($scope.popupmarker_osm);
    }


    var latlng_osm = new L.LatLng(val.latitude, val.longitude);

      var popupIcon = L.icon({
          iconUrl: 'assets/imgs/popup.png',
          iconSize: [40,40], 
          iconAnchor: [20,40],
          popupAnchor: [-1, -40],
      });

       var contentString_osm = '<div style="padding:2px; padding-top:3px; width:190px;">'
    +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;color:#666463;">Date&amp;Time</b> <span style="padding-left:9px;">-</span> <span style="font-size:10px;padding-left:3px;color:#666463;font-weight:bold;border-bottom:0.5px;">'+dateFormat(val.date)+'</span></div>'
    +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;color:#666463;">Speed</b> <span style="padding-left:9px;">-</span> <span style="font-size:10px;padding-left:3px;color:#666463;font-weight:bold;border-bottom:0.5px;">'+ val.speed +'</span><span style="font-size:10px;padding-left:10px;border-bottom:0.5px;">kmph</span></div>'
    +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;color:#666463;">OdoDist</b> <span style="padding-left:9px;">-</span> <span style="font-size:10px;padding-left:3px;color:#666463;font-weight:bold;border-bottom:0.5px;">'+val.odoDistance+'</span><span style="font-size:10px;padding-left:10px;border-bottom:0.5px;">kms</span></div>'
    +'<div style="text-align:center;padding-top:3px;"><span style="font-size:10px;width:100px;">'+$scope.trimComma(val.address)+'</span></div>'
 // +'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
    +'</div>';


        var infoPopup_osm = new L.popup({maxWidth: 400,  
          maxHeight:170}).setContent(contentString_osm);

        $scope.popupmarker_osm  = new L.marker();

        $scope.popupmarker_osm.bindPopup(infoPopup_osm);
        $scope.popupmarker_osm.setIcon(popupIcon);
        $scope.popupmarker_osm.setLatLng(latlng_osm).addTo($scope.map_osm);
        $scope.popupmarker_osm.openPopup();
          
        $scope.popupmarker_osm.addEventListener('click', function(e) {
           $scope.popupmarker_osm.openPopup();
        });


   /*       myIcons = L.icon({
            iconUrl: vamoservice.iconURL($scope.hisloc.vehicleLocations[lineCount_osm]),
            iconSize: [40,40], 
            iconAnchor: [20,40],
            popupAnchor: [-1, -40],
        });

        $scope.infowindowss.setContent(contenttsString);
        $scope.markerss.bindPopup($scope.infowindowss);
        $scope.markerss.setIcon(myIcons);
        $scope.markerss.setLatLng($scope.osmPath[lineCount_osm]);

        if(lineCount_osm==0){   
          $scope.markerss.addTo($scope.map_osm);
          $scope.markerss.openPopup();

            */
   }

  }

  $scope.callValue = function(val){
    //console.log(val);
    $scope.trackVehID = val.vehiID;
    $scope.shortVehiId = val.vName;
    $scope.hideButton = false;
    $scope.btn5Hrs = false;
    $scope.btn12Hrs = false;
    $scope.btn1Day = false;
    $scope.btn2Day = false;
  }

  $scope.deleteRouteName  = function(deleteValue){
   // $scope.routeName = deleteValue;
    console.log(deleteValue);
    try{

      if(!deleteValue =='' && $scope.orgIds.length>0 && !$scope.orgIds=='')
        $.ajax({
          
          async: false,
              method: 'GET', 
              url: "storeOrgValues/deleteRoutes",
              data: {"delete":deleteValue, "orgIds":$scope.orgIds},
              success: function (response) {

                // $scope.routedValue = response;
   
              }
        })
        getRouteNames();
        $scope.error =  "* Deleted Successfully"

    } catch (err){

      $scope.error =  "* Not Deleted Successfully"
    }
    // console.log($(this).parent().parent().find('td').text())
    // console.log($('.editAction').closest('tr').children('td:eq(0)').text());

  }

  

$('.dynData').on("click", "#editAction", function(event){
    var target    = $(this).closest('tr').children('td:eq(0)')
    $scope.error  = ""
    // $(target).html($('<input />',{'value' : target.text()}).val(target.text()));

    // $(this).siblings().each(
    //     function(){
            // if the td elements contain any input tag
            if(!target.text() == '');
            if ($(target).find('input').length){
                // sets the text content of the tag equal to the value of the input
                $(target).text($(target).find('input').val());
            }
            else {
                // removes the text, appends an input and sets the value to the text-value
                var t = $(target).text();
                $(target).html($('<input />',{'value' : target.text()}).val(target.text()));
            }
        // });
    
});

  $('.dynData').on("change", $(this).closest('tr').children('td:eq(0) input'), function(event){
    console.log(' new value '+event.target.value)
    $scope.error =  ""
    var _newValue = event.target.value;
    var _oldValue = event.target.defaultValue;
    try{

      if(!_newValue =='' && $scope.orgIds.length>0 && !$scope.orgIds=='')
        $.ajax({
          
          async: false,
              method: 'GET', 
              url: "storeOrgValues/editRoutes",
              data: {"newValue":_newValue, "oldValue":_oldValue, "orgIds":$scope.orgIds},
              success: function (response) {

                // $scope.routedValue = response;
                $scope.error =  "* Edited Successfully"
   
              }
        })

    } catch (err){

      $scope.error =  "* Not Edited Successfully"
    }
    

  })

 // $(document).ready(function() {
 //                $('.dynData table tbody tr td input').change(function() {
 //                    var rowEdit = $(this).parents('tr');
 //                    alert(rowEdit);
 //                    console.log($(rowEdit));
 //                    $(rowEdit).children('.sub').html('Success');
 //                })
 //            })

function animateMapZoomTo(map, targetZoom) {
    var currentZoom = arguments[2] || map.getZoom();
    if (currentZoom != targetZoom) {
        google.maps.event.addListenerOnce(map, 'zoom_changed', function (event) {
            animateMapZoomTo(map, targetZoom, currentZoom + (targetZoom > currentZoom ? 1 : -1));
        });
        setTimeout(function(){ map.setZoom(currentZoom) }, 80);
    }
}

function smoothZoom (map, max, cnt) {
    if (cnt >= max) {
            return;
        }
    else {
        z = google.maps.event.addListener(map, 'zoom_changed', function(event){
            google.maps.event.removeListener(z);
            smoothZoom(map, max, cnt + 1);
        });
        setTimeout(function(){map.setZoom(cnt)}, 80);
    }
}  

  $scope.infowin = function(data){
    
    var centerPosition = new google.maps.LatLng(data.latitude, data.longitude);
          var labelText = data.poiName;
          var stop = data.stopName;
          var image = 'assets/imgs/busgeo.png';
          
            var beachMarker = new google.maps.Marker({
              position: centerPosition,
              title: stop,
              map: $scope.map,
              icon: image
            });
            geomarker.push(beachMarker);
          // var myOptions = { content: labelText, boxStyle: {textAlign: "center", fontSize: "9pt", fontColor: "#ff0000", width: "100px"},
            // disableAutoPan: true,
            // pixelOffset: new google.maps.Size(-50, 0),
            // position: centerPosition,
            // closeBoxURL: "",
            // isHidden: false,
            // pane: "mapPane",
            // enableEventPropagation: true
          // };
          // var labelinfo = new InfoBox(myOptions);
          // labelinfo.open($scope.map);simple
          // labelinfo.setPosition($scope.cityCircle[i].getCenter());
          // geoinfo.push(labelinfo);
                    
          var contentString = '<h3 class="infoh3">Location</h3>'
          +'<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody>'
          +'<tr><td>Latlong</td><td>'+data.geoLocation+' </td></tr>'+'<tr><td>StopName</td><td>'+data.stopName+' </td></tr>'
          +'</table></div>';
          var infoWindow = new google.maps.InfoWindow({content:contentString});
          geoinfowindow.push(infoWindow);
          
          (function(marker) {
              google.maps.event.addListener(beachMarker, "click", function(e) {
                for(var j=0; j<geoinfowindow.length;j++){
                geoinfowindow[j].close();
              }
                infoWindow.open($scope.map, marker);
              
              }); 
            })(beachMarker);
  }
  
  
  $scope.genericFunction = function(a,b,shortName){
    startLoading();
    $scope.path = [];
    gmarkers=[];
    ginfowindow=[];
    contentString = [];
    $scope.trackVehID = a;
    $scope.shortVehiId = shortName;
    $scope.selected = b;
    $scope.plotting();
    sessionValue($scope.trackVehID, $scope.groupname);
    // stopLoading();
  }
  
  $scope.groupSelection = function(groupname, groupid){
  
    startLoading();
     $scope.selected=0;
     $scope.url = GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group=' + groupname;
     $scope.gIndex = groupid;
     gmarkers=[];
     ginfowindow=[];
     $scope.loading = true;
     
     for(var i=0; i<gmarkers.length; i++){
        gmarkers[i].setMap(null);
      }
      
      if($scope.polyline){
        
        for(var i=0; i<$scope.polyline1.length; i++){
        $scope.polyline1[i].setMap(null);
        }
      
        $scope.polyline.setMap(null);
            }

      if($scope.markerstart){
        
        $scope.markerstart.setMap(null);
        $scope.markerend.setMap(null);
        $scope.path = [];
        $scope.polylinearr = [];
        gmarkers=[];
        ginfowindow=[];
        contentString = [];
        gsmarker=[];
        gsinfoWindow=[];
        window.clearInterval(id);
        $('#replaybutton').attr('disabled','disabled');
        $('#lastseen').html('<strong>From Date & time :</strong> -');
        $('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');
      }

        $http.get($scope.url).success(function(data){
         
         $scope.locations = data;
         
         if(data.length)
          $scope.vehiname = data[$scope.gIndex].vehicleLocations[0].vehicleId;
          $scope.groupname  = data[$scope.gIndex].group;
          $scope.trackVehID   = $scope.locations[$scope.gIndex].vehicleLocations[$scope.selected].vehicleId;
          sessionValue($scope.trackVehID, $scope.groupname);
          $scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID;
          $('.nav-second-level li').eq(0).children('a').addClass('active');
          $scope.loading  = false;
            
                //stopLoading();
        }).error(function(){ stopLoading();});
  }

  $scope.pointMarker=function(data){
  
    var line0Symbol = {
          path: google.maps.SymbolPath.CIRCLE,
          scale:3,
          strokeColor: '#0645AD',
          strokeWeight: 5
      };
      
      var marker = new google.maps.Marker({
            map: $scope.map,
            position: new google.maps.LatLng(data.latitude, data.longitude),
            icon:line0Symbol
      });
  
      gsmarker.push(marker);
  
      var contentString01 = '<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody></tbody></table></div>';
      var infoWindow = new google.maps.InfoWindow({content: contentString01});
      gsinfoWindow.push(infoWindow);  
      
      (function(marker) {
          google.maps.event.addListener(marker, "click", function(e) {
          $scope.infowindowShow={};
        $scope.infowindowShow['dataTempVal'] =  data;
        $scope.infowindowShow['currinfo'] = infoWindow;
        $scope.infowindowShow['currmarker'] = marker;
        for(var j=0; j<gsinfoWindow.length;j++){
          gsinfoWindow[j].close();
        }
        $scope.infowindowshowFunc();
         });  
        })(marker);
  }

  $scope.infowindowshowFunc = function(){
    for(var j=0; j<gsinfoWindow.length;j++){
      gsinfoWindow[j].close();
    }
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(+$scope.infowindowShow.dataTempVal.latitude, +$scope.infowindowShow.dataTempVal.longitude);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
              var contentString = '<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody>'
      +'<tr><td>Speed </td><td>'+$scope.infowindowShow.dataTempVal.speed+'</td></tr>'
      +'<tr><td>date & time</td><td>'+dateFormat($scope.infowindowShow.dataTempVal.date)+'</td></tr>'
      +'<tr><td>trip distance</td><td>'+$scope.infowindowShow.dataTempVal.tripDistance+'</td></tr>'
      +'<tr><td style="widthpx">location</td><td style="width:100px">'+results[1].formatted_address+'</td></tr>'
      +'</tbody></table></div>';
            $scope.infowindowShow.currinfo.setContent(contentString);
            $scope.infowindowShow.currinfo.open($scope.map,$scope.infowindowShow.currmarker);
            }
            
          }
    });
  }

  $scope.pointinfowindow=function(map, marker, data){
    
      if(gsmarker.length>0){
     }
  }

  $scope.locationname="";
  $scope.getLocation = function(lat,lon, callback){

    var tempurl01 =  "https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lon;
    var t = $.ajax({
      dataType:"json",
      url: tempurl01,
      success:function(data){
        
        if(data.results[0]!=undefined){
        if(data.results[0].formatted_address==undefined){
          if(typeof callback === "function") callback('');
        }else{
          if(typeof callback === "function") callback(data.results[0].formatted_address);
        }
        }else{
          if(typeof callback === "function") callback('');
        }
      },
      error:function(xhr, status, error){
        //if(typeof callback === "function") callback('');
        console.log('error:' + status + error);
      }
    });
    
    };  
    
  $scope.timeCalculate = function(duration){
    var milliseconds = parseInt((duration%1000)/100), seconds = parseInt((duration/1000)%60);
    var minutes = parseInt((duration/(1000*60))%60), hours = parseInt((duration/(1000*60*60))%24);
    
    hours = (hours < 10) ? "0" + hours : hours;
    minutes = (minutes < 10) ? "0" + minutes : minutes;
    seconds = (seconds < 10) ? "0" + seconds : seconds;
    temptime = hours + " H : " + minutes +' M : ' +seconds +' S';

    var s=duration;
    function addZ(n) {
        return (n<10? '0':'') + n;
    }

    var ms = s % 1000;
    s = (s - ms) / 1000;
    var secs = s % 60;
    s = (s - secs) / 60;
    var mins = s % 60;
    var hrs = (s - mins) / 60;

    var tempTim = addZ(hrs) + ' H : ' + addZ(mins) + ' M : ' + addZ(secs) + ' S '; //+ ms;
    return tempTim;
  }

  // function parseDate(str) {
  //     var mdy = str.split('/');
  //     return new Date(mdy[2], mdy[0]-1, mdy[1]);
  // }
  
  $scope.msToTime   = function(ms) {
        days = Math.floor(ms / (24*60*60*1000));
      daysms=ms % (24*60*60*1000);
      hours = Math.floor((ms)/(60*60*1000));
      hoursms=ms % (60*60*1000);
      minutes = Math.floor((hoursms)/(60*1000));
      minutesms=ms % (60*1000);
      sec = Math.floor((minutesms)/(1000));
      return hours+":"+minutes+":"+sec;
    }

  function utcdateConvert(milliseconds){
    //var milliseconds=1440700484003;
    var offset='+10';
    var d = new Date(milliseconds);
    utc = d.getTime() + (d.getTimezoneOffset() * 60000);
    nd = new Date(utc + (3600000*offset));
    var result=nd.toLocaleString();
    return result;
  }

  $scope.timeconversion= function(time){
    var time = time;
    var hours = Number(time.match(/^(\d+)/)[1]);
    var minutes = Number(time.match(/:(\d+)/)[1]);
    var AMPM = time.match(/\s(.*)$/)[1];
    if(AMPM == "PM" && hours<12) hours = hours+12;
    if(AMPM == "AM" && hours==12) hours = hours-12;
    var sHours = hours.toString();
    var sMinutes = minutes.toString();
    if(hours<10) sHours = "0" + sHours;
    if(minutes<10) sMinutes = "0" + sMinutes;
    return sHours+":"+sMinutes+":00";
  }
  $scope.timeconversion2= function(time){
    var time = time;
    var hours = Number(time.match(/^(\d+)/)[1]);
    var minutes = Number(time.match(/:(\d+)/)[1]);
    var AMPM = time.match(/\s(.*)$/)[1];
    if(AMPM == "PM" && hours<12) hours = hours+12;
    if(AMPM == "AM" && hours==12) hours = hours-12;
    var sHours = hours.toString();
    var sMinutes = minutes.toString();
    if(hours<10) sHours = "0" + sHours;
    if(minutes<10) sMinutes = "0" + sMinutes;
    return sHours+":"+sMinutes+":59";
  }

  $scope.showPlot = function(totime,todate,fromtime,fromdate)
  {
    $scope.hideButton = false;
  }

  $scope.startLoading = function(){
    startLoading();
  }

  $scope.plotting = function(val,parameter){
    //console.log("Plotting button pressed");

    $scope.plotVal=1; 

   window.clearInterval(timeInterval);
   clearInterval($scope.osmInterVal);

    startLoading();

    var allDataOk = 0;
    $scope.hideButton = true;

    (function(){
      $scope.hideButton = false;
    },5000);

    if (val != 1){
      var fromdate = document.getElementById('dateFrom').value;
      var todate = document.getElementById('dateTo').value;
    }

    if((checkXssProtection(fromdate) == true) && (checkXssProtection(todate) == true) && (checkXssProtection(document.getElementById('timeTo').value) == true) && (checkXssProtection(document.getElementById('timeFrom').value) == true)){
      startLoading();
      $scope.hideOverlay = true;

      //$scope.popUpMarkerNull();
      $scope.hisurlold = $scope.hisurl;

      switch (val){
      case 1: {
        $scope.showLoader = true;
        $scope.btn5Hrs = true;
        $scope.btn12Hrs= false;
        $scope.btn1Day = false;
        $scope.btn2Day = false;

        var todayTime = new Date();
        var currentTimeRaw = new Date();
        var currentTime = $filter('date')(currentTimeRaw,'HH:mm:ss');
        var fromDateRaw = $filter('date')(currentTimeRaw,'yyyy-MM-dd');
        todayTime.setHours(todayTime.getHours()-4);
        todayTime.setMinutes(todayTime.getMinutes()-59);
        $scope.HHmmss = $filter('date')(todayTime, 'HH:mm:ss');
          
        var fromtime = $scope.HHmmss;
        var totime = currentTime ;
        var fromdate = fromDateRaw;
        var todate = fromDateRaw;
        
        break;

      }case 2: {
        $scope.showLoader = true;
        $scope.btn5Hrs = false;
        $scope.btn12Hrs= true;
        $scope.btn1Day = false;
        $scope.btn2Day = false;

        var todayTime  = new Date();
        var newYesDate = new Date();

        var currentTimeRaw = new Date();
        var currentTime = $filter('date')(currentTimeRaw,'HH:mm:ss');
        var fromDateRaw = $filter('date')(currentTimeRaw,'yyyy-MM-dd');
        todayTime.setHours(todayTime.getHours()-11);
        todayTime.setMinutes(todayTime.getMinutes()-59);
        var previousDate = newYesDate.setDate(newYesDate.getDate()-1);
        $scope.HHmmss = $filter('date')(todayTime, 'HH:mm:ss');
        // alert($scope.HHmmss);

        var fromtime = $scope.HHmmss;
        var totime = currentTime ;
        var fromdate = $filter('date')(previousDate,'yyyy-MM-dd');
        var todate = fromDateRaw;

        break;
        
      }case 3:  {
        $scope.showLoader = true;
        $scope.btn5Hrs = false;
        $scope.btn12Hrs= false;
        $scope.btn1Day = true;
        $scope.btn2Day = false;
        var todayTime  = new Date();
        var newYesDate = new Date();

        var currentTimeRaw = new Date();
        var currentTime = $filter('date')(currentTimeRaw,'HH:mm:ss');
        var fromDateRaw = $filter('date')(currentTimeRaw,'yyyy-MM-dd');
        todayTime.setHours(todayTime.getHours()-23);
        todayTime.setMinutes(todayTime.getMinutes()-59);
        var previousDate = newYesDate.setDate(newYesDate.getDate());
        $scope.HHmmss = $filter('date')(todayTime, 'HH:mm:ss');
        // alert($scope.HHmmss);

        var fromtime = '00:00:00';//$scope.HHmmss;
        var totime = currentTime ;
        var fromdate = $filter('date')(previousDate,'yyyy-MM-dd');
        var todate = fromDateRaw;

        break;
      
      }case 4:  {
        $scope.showLoader = true;
        $scope.btn5Hrs = false;
        $scope.btn12Hrs= false;
        $scope.btn1Day = false;
        $scope.btn2Day = true;

        var todayTime  = new Date();
        var newYesDate = new Date();

        var currentTimeRaw = new Date();
        var currentTime = $filter('date')(currentTimeRaw,'HH:mm:ss');
        var fromDateRaw = $filter('date')(currentTimeRaw,'yyyy-MM-dd');
        todayTime.setHours(todayTime.getHours()-1);
        todayTime.setMinutes(todayTime.getMinutes()-59);
        var previousDate = newYesDate.setDate(newYesDate.getDate()-1);
        var fromYesDate = newYesDate.setDate(newYesDate.getDate()-1);
        $scope.HHmmss = $filter('date')(todayTime, 'HH:mm:ss');
        var fromtime ="00:00:00";
        var totime = "23:59:59";
        var fromdate = $filter('date')(previousDate,'yyyy-MM-dd');
        var todate = $filter('date')(previousDate,'yyyy-MM-dd');

        break;
    }
      default:  {
        if(document.getElementById('timeFrom').value==''){
           var fromtime = "00:00:00";
          }else{
           var fromtime = $scope.timeconversion(document.getElementById('timeFrom').value);
          }
          if(document.getElementById('timeTo').value==''){
           var totime = "00:00:00";
          }else{
           var totime = $scope.timeconversion2(document.getElementById('timeTo').value);
          }
        }
      }

  
      if(document.getElementById('dateFrom').value==''){

        if(document.getElementById('dateTo').value==''){
          
             $scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID;

              if($scope.googleMap==true && $scope.osmMap==false){
               $scope.initMap(0,true);
              } else if($scope.osmMap==true && $scope.googleMap==false){
               $scope.initMap(1,true);
              } 
        }

      } else {
        
        if(document.getElementById('dateTo').value==''){
          $scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+fromdate+'&fromTime='+fromtime;
            if($scope.googleMap==true && $scope.osmMap==false){
               $scope.initMap(0,true);
            } else if($scope.osmMap==true && $scope.googleMap==false){
               $scope.initMap(1,true);
            } 

        } else {
         
          var days =daydiff(new Date(fromdate), new Date(todate));

          if (fromdate == todate)
          {
            if (fromtime ==  totime)
            {
              stopLoading();
            } else {
                
                $scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+fromdate+'&fromTime='+fromtime+'&toDate='+todate+'&toTime='+totime+'&fromDateUTC='+utcFormat(fromdate,fromtime)+'&toDateUTC='+utcFormat(todate,totime);
                if($scope.googleMap==true && $scope.osmMap==false){
                 $scope.initMap(0,true);
                } else if($scope.osmMap==true && $scope.googleMap==false){
                 $scope.initMap(1,true);
                }

              }
            } else if (fromdate > todate){
          
            stopLoading();
          }
          else
          {
              if(days<=3){

                $scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+fromdate+'&fromTime='+fromtime+'&toDate='+todate+'&toTime='+totime+'&fromDateUTC='+utcFormat(fromdate,fromtime)+'&toDateUTC='+utcFormat(todate,totime);
                if($scope.googleMap==true && $scope.osmMap==false){
                  $scope.initMap(0,true);
                } else if($scope.osmMap==true && $scope.googleMap==false){
                  $scope.initMap(1,true);
                }

              } else if(days < 7) {

                 $scope.hisurl = GLOBAL.DOMAIN_NAME+'/getVehicleHistory?vehicleId='+$scope.trackVehID+'&fromDate='+fromdate+'&fromTime='+fromtime+'&toDate='+todate+'&toTime='+totime+'&interval=1'+'&fromDateUTC='+utcFormat(fromdate,fromtime)+'&toDateUTC='+utcFormat(todate,totime);
                  if($scope.googleMap==true && $scope.osmMap==false){
                   $scope.initMap(0,true);
                  } else if($scope.osmMap==true && $scope.googleMap==false){
                   $scope.initMap(1,true);
                  }

              } else {
                  alert(' Please select date range within 7 days. ');
                  stopLoading();
              }
              
          }
        }
    }

      if($scope.hisurlold!=$scope.hisurl){  
        for(var i=0; i<gmarkers.length; i++){
          gmarkers[i].setMap(null);
        }
        for(var i=0; i<$scope.polyline1.length; i++){
          $scope.polyline1[i].setMap(null);
        }

        $scope.path = [];
        $scope.polylinearr = [];
        gmarkers=[];
        ginfowindow=[];
        contentString = [];
        gsmarker=[];
        gsinfoWindow=[];
           
            if($scope.mhValue==1){
             //window.clearInterval(intervalPoly);  
              if($scope.markerValue != 1){
               //$scope.infosWindows.close();
                 $scope.infosWindows=null;
                 $scope.markerheads.setMap(null);
              }
        }  

        if($scope.markerValue==1){
               // window.clearInterval(timeInterval);
            $scope.polyline.setMap(null);
            $scope.markerstart.setMap(null);
            $scope.markerend.setMap(null);
            $scope.infosWindow.close();
            $scope.infosWindow=null;
            $scope.markerheads.setMap(null);
            $scope.markerValue=0;
        }

      //  $('#replaybutton').attr('disabled','disabled');
        $('#lastseen').html('<strong>From Date & time :</strong> -');
        $('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');

      }else{
        if($scope.hisloc.error!=null || $scope.hisloc.error == undefined){
          // $('#myModal').modal();
          // alert('Please selected date before 7 days / No data found')
          //stopLoading();
        }
      }
    }
    
  //stopLoading();
  }
  
  $scope.addMarker= function(pos){
  
    var myLatlng = new google.maps.LatLng(pos.lat, pos.lng);
    var labelAnchorpos = new google.maps.Point(12, 37);
  
  /*  if(pos.data.insideGeoFence =='Y'){
      //pinImage = 'assets/imgs/F_'+pos.data.direction+'.png';
        pinImage = 'assets/imgs/trans.png';
    }
    else{  */
      if(pos.data.position =='P') {
      //pinImage = 'assets/imgs/'+pos.data.position+'.png';
        pinImage = 'assets/imgs/flag.png';
      } 
    //else 
        if(pos.data.position =='S') {
        
        //pinImage = 'assets/imgs/A_'+pos.data.direction+'.png';
        pinImage = 'assets/imgs/orange.png';
      }
  //}
    if(pos.data.position !='P' &&  pos.data.position !='S'){
      pinImage = 'assets/imgs/trans.png';
    }

    $scope.marker = new MarkerWithLabel({
         position: pos.path, 
         map: $scope.map,
         icon: pinImage,
         labelContent: ''/*pos.data.position*/,
      // labelAnchor: labelAnchorpos,
      // labelClass: "labels", 
         labelInBackground: true
    });

   gmarkers.push($scope.marker);  
  } 

  $scope.geocoder = function(lat, lng, callback){
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[1]) {
          if(results[1].formatted_address==undefined){
            if(typeof callback === "function") callback("No Result");
          }else{
          if(typeof callback === "function") callback(results[1].formatted_address);
        }
        } else {
         console.log('No results found');
        }
      } else {
         console.log('Geocoder failed due to: ' + status);
      }
    });
  }

  $scope.addMarkerstart= function(pos){
    
    var myLatlng = new google.maps.LatLng(pos.lat, pos.lng);
    pinImage = 'assets/imgs/startflag.png';

    $scope.markerstart = new MarkerWithLabel({
       position: pos.path, 
       map: $scope.map,
       icon: pinImage
    });
    
    $scope.geocoder(pos.lat, pos.lng, function(count){
      $scope.tempadd = count;
    });

    var contentString = '<h3 class="infoh3">Vehicle Details ('+$scope.shortVehiId+') </h3><div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody><!--<tr><td>Location</td><td>'+$scope.tempadd+'</td></tr>--><tr><td>Last seen</td><td>'+dateFormat(pos.data.date)+'</td></tr><tr><td>Parked Time</td><td>'+$scope.timeCalculate(pos.data.parkedTime)+'</td></tr><tr><td>Trip Distance</td><td>'+pos.data.tripDistance+'</td></tr></table></div>';
    var infoWindow = new google.maps.InfoWindow({content: contentString});
    
    google.maps.event.addListener($scope.markerstart, "click", function(e){
      infoWindow.open($scope.map, $scope.markerstart);  
    });
    
    (function(marker, data, contentString) {
      google.maps.event.addListener(marker, "click", function(e) {
      infoWindow.open($scope.map, marker);
      }); 
    })($scope.markerstart, pos.data);
  }

  $scope.addMarkerend= function(pos){
    var myLatlng = new google.maps.LatLng(pos.lat, pos.lng);
    pinImage = 'assets/imgs/endflag.png';
    $scope.markerend = new MarkerWithLabel({
       position: pos.path, 
       map: $scope.map,
       icon: pinImage
    });
    $scope.geocoder(pos.lat, pos.lng, function(count){
      if(count!=undefined){
        $scope.tempadd = count;
      }else{
        $scope.tempadd = 'No Result';
      }
      
      var contentString = '<h3 class="infoh3">Vehicle Details ('+$scope.shortVehiId+') </h3>'
      +'<div class="nearbyTable02"><div><table cellpadding="0" cellspacing="0"><tbody>'
      +'<tr><td>Location</td><td>'+$scope.tempadd+'</td></tr><tr><td>Last seen</td><td>'+dateFormat(pos.data.date)+'</td>'
      +'</tr><tr><td>Parked Time</td><td>'+$scope.timeCalculate(pos.data.parkedTime)+'</td></tr><tr><td>Trip Distance</td>'
      +'<td>'+pos.data.tripDistance+'</td></tr></table></div>';
      
      var infoWindow = new google.maps.InfoWindow({content: contentString});
      google.maps.event.addListener($scope.markerend, "click", function(e){
        infoWindow.open($scope.map, $scope.markerend);  
      });
      
      (function(marker, data, contentString) {
        google.maps.event.addListener(marker, "click", function(e) {
        infoWindow.open($scope.map, marker);
        }); 
      })($scope.markerend, pos.data);
    });
  }
  $scope.tempadd="";

  function dateFormat(date) { return $filter('date')(date, "yyyy-MM-dd HH:mm:ss");}

  $scope.printadd=function(a, b, c, d, marker, map, data){
    var posval='';
    
    if(data.position=='S'){
      posval = 'Idle Time';
    
    }else if(data.position=='P'){
      posval = 'Parked Time';
    }
    
    var contentString = '<h3 class="infoh3">Vehicle Details('+$scope.shortVehiId+') </h3><div class="nearbyTable02">'
    +'<div><table cellpadding="0" cellspacing="0"><tbody>'
    +'<tr><td>Location</td><td>'+d+'</td></tr>'
    +'<tr><td>Last seen</td><td>'+a+'</td></tr>'
    +'<tr><td>'+posval+'</td><td>'+$scope.timeCalculate(b)+'</td></tr>'
    +'<tr><td>Trip Distance</td><td>'+c+'</td></tr>'
    +'</table></div>';
    var infoWindow = new google.maps.InfoWindow({content: contentString});
    ginfowindow.push(infoWindow);
    google.maps.event.addListener(marker, "click", function(e){
      for(j=0;j<ginfowindow.length;j++){
        ginfowindow[j].close();
      }
      infoWindow.open(map, marker); 
    });
    // (function(marker, data, contentString) {
    //   google.maps.event.addListener(marker, "click", function(e) {
    //    for(j=0;j<ginfowindow.length;j++){
          
    //    ginfowindow[j].close();
    //  }
    //  infoWindow.open(map, marker);
    //   });  
    // })(marker, data);
  }

  $scope.imgsrc = function(img){
     return img;
  }

   $scope.infoBox = function(map, marker, data){
     
    var t = $scope.getLocation(data.latitude, data.longitude, function(count){
      
       if(data.position=='S'){
        var b = data.idleTime;
       }else if(data.position=='P'){
        var b = data.parkedTime;
       }
      $scope.printadd(dateFormat(data.date), b, data.tripDistance, count, marker, map, data); 
    });
  };

   
    $scope.trimComma = function(textVal){

      var strValues;
    //console.log(textVal);

      if(textVal!=undefined){
      var splitValue = textVal.split(/[,]+/);
        var strLen=splitValue.length;

        switch(strLen){
              case 0:
               strValues='No Data';
               break;
              case 1:
               strValues=splitValue[0];
               break;
              case 2:
               strValues=splitValue[0]+','+splitValue[1];
               break;
              default:
               strValues=splitValue[0]+','+splitValue[1];
               break;
            }
      }else{
        strValues='No Address';
      }

    return  strValues;
  }

  $scope.hideMarkerVal = true;
  $scope.hideMarker = {
        value1 : true,
        value2 : 'YES'
    }
          
  $scope.addRemoveMarkers=function(val){

     if (val == 'YES')
    {
         $scope.hideMarkerVal = false;
    }
    else
    {
       $scope.hideMarkerVal = true;
    }
  }

  var intervalPoly;
  var timeInterval;

  $scope.polylineCheck  = true;
  $scope.markerPark     = [];

  function myStopFunction() {
      clearInterval(intervalPoly);
  }

  function markerClear(){

    for(var i=0; i<gmarkers.length; i++){
        gmarkers[i].setMap(null);
    }
    if($scope.polylineLoad)
      if($scope.polylineLoad.length >0)
        for (var i = $scope.polylineLoad[0].length - 1; i >= 0; i--) {
         $scope.polylineLoad[0][i].setMap(null);
        }
  }

  
  var pcount       = 0;
  var lenCount2    = 0;
  $scope.timeDelay = 180;
  $scope.speedVal  = 0;
  $scope.pasVal    = 0;


  function timerSet(){

      if($scope.hisloc.vehicleLocations.length!= pcount+1){

          var latlngs = new google.maps.LatLng($scope.hisloc.vehicleLocations[pcount+1].latitude,$scope.hisloc.vehicleLocations[pcount+1].longitude);
       // if(($scope.hisloc.vehicleLocations[pcount].latitude+''+$scope.hisloc.vehicleLocations[pcount].longitude)!=($scope.hisloc.vehicleLocations[pcount+1].latitude+''+$scope.hisloc.vehicleLocations[pcount+1].latitude)){
             $scope.rotationsd = getBearing($scope.hisloc.vehicleLocations[pcount].latitude,$scope.hisloc.vehicleLocations[pcount].longitude,$scope.hisloc.vehicleLocations[pcount+1].latitude,$scope.hisloc.vehicleLocations[pcount+1].longitude);                
                   
                                 
                            $scope.markerheads.setIcon({
                              path:vehicIcon[0],
                              scale:vehicIcon[1],
                              strokeWeight: 1,
                              fillColor:$scope.polylinearr[pcount+1],
                              fillOpacity: 1,
                              anchor:vehicIcon[2],
                              rotation:$scope.rotationsd,
                            });

                            if(pcount==0){
                               $scope.markerheads.setMap($scope.map);
                            }

                            $scope.markerheads.setPosition(latlngs);


      var contenttString = '<div style="padding:2px; padding-top:3px; width:175px;">'
      +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">LocTime</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+dateFormat($scope.hisloc.vehicleLocations[pcount+1].date)+'</span> </div>'
      +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">Speed</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+ $scope.hisloc.vehicleLocations[pcount+1].speed+'</span><span style="font-size:10px;padding-left:10px;">kmph</span></div>'
      +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">DistCov</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+$scope.hisloc.vehicleLocations[pcount+1].distanceCovered+'</span><span style="font-size:10px;padding-left:10px;">kms</span></div>'
      +'<div style="text-align:center;padding-top:3px;"><span style="font-size:10px;width:100px;">'+$scope.trimComma($scope.hisloc.vehicleLocations[pcount+1].address)+'</span></div>'
      // +'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
      +'</div>';

                 //console.log(contenttString);

                    $scope.infosWindow.setContent(contenttString);
                    $scope.infosWindow.setPosition(latlngs);

                    if(pcount==0){
                      $scope.infosWindow.open($scope.map,$scope.markerheads);
                      $scope.infosWindow.close();
                    }   

                   // console.log(latlngs);
       
                    $scope.map.panTo(latlngs);
    // }
      pcount++;
    
  } else {

       if(lenCount2 == 0 ){

          if($scope.hisloc.vehicleLocations.length==1){

                var latlngs = new google.maps.LatLng($scope.hisloc.vehicleLocations[0].latitude,$scope.hisloc.vehicleLocations[0].longitude);
          //  if(($scope.hisloc.vehicleLocations[pcount].latitude+''+$scope.hisloc.vehicleLocations[pcount].longitude)!=($scope.hisloc.vehicleLocations[pcount+1].latitude+''+$scope.hisloc.vehicleLocations[pcount+1].latitude)){
            //  $scope.rotationsd = getBearing($scope.hisloc.vehicleLocations[pcount].latitude,$scope.hisloc.vehicleLocations[pcount].longitude,$scope.hisloc.vehicleLocations[pcount+1].latitude,$scope.hisloc.vehicleLocations[pcount+1].longitude);                
                   
                              $scope.markerheads.setIcon({
                                    path:vehicIcon[0],
                                    scale:vehicIcon[1],
                                    strokeWeight: 1,
                                    fillColor:$scope.polylinearr[0],
                                    fillOpacity: 1,
                                    anchor:vehicIcon[2],
                                  //rotation:$scope.rotationsd,
                              });

                              $scope.markerheads.setMap($scope.map);
                              $scope.markerheads.setPosition(latlngs);
                            

      var contenttString = '<div style="padding:2px; padding-top:3px; width:175px;">'
      +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">LocTime</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+dateFormat($scope.hisloc.vehicleLocations[0].date)+'</span> </div>'
      +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">Speed</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+ $scope.hisloc.vehicleLocations[0].speed+'</span><span style="font-size:10px;padding-left:10px;">kmph</span></div>'
      +'<div style="font-size=11px;border-bottom: 0.5px solid #f3eeeb;"><b class="_info_caption2" style="font-size:11px;padding-right:3px;color:#666463;">DistCov</b> - <span style="font-size:10px;padding-left:2px;color:green;font-weight:bold;">'+$scope.hisloc.vehicleLocations[0].distanceCovered+'</span><span style="font-size:10px;padding-left:10px;">kms</span></div>'
      +'<div style="text-align:center;padding-top:3px;"><span style="font-size:10px;width:100px;">'+$scope.trimComma($scope.hisloc.vehicleLocations[0].address)+'</span></div>'
      // +'<div style="overflow-wrap: break-word; border-top: 1px solid #eee">'+data.address+'</div>'
      +'</div>';

                     $scope.infosWindow.setContent(contenttString);
                     $scope.infosWindow.setPosition(latlngs);
                     $scope.infosWindow.open($scope.map,$scope.markerheads);
                     $scope.infosWindow.close();
          }

        lenCount2++;
        }

        if(pcount==$scope.hisloc.vehicleLocations.length-1){

          window.clearInterval(timeInterval);

          $('#playButton').prop('disabled',  true);
          $('#replayButton').prop('disabled',false);
          $('#stopButton').prop('disabled', true);
          $('#pauseButton').prop('disabled', true);
          $scope.speedVal=1; 
        }

     } 
}

$scope.polylineCheck = {
  value1: 'YES',
  value2 : 'NO'
}

$scope.hideAllDetail = function(val) {

  if(val == 'NO') {

        $scope.hideAllDetailVal = true;
        $scope.hideMe           = true;
        $scope.hideMarkerVal    = true;
        /*  $('.hideClass').hide();
            $('#pauseButton').hide();
            $('#playButton').hide();      
            $('#stopButton').hide();   
            $('#replayButton').hide();*/

            $('#playButton').prop('disabled', true);
            $('#replayButton').prop('disabled', true);
            $('#stopButton').prop('disabled', true);
            $('#pauseButton').prop('disabled', true);

  } else if(val == 'YES') {

        $scope.hideAllDetailVal = false;
        $scope.hideMe = false;
        $scope.hideMarkerVal = false;

         /* $('.hideClass').show();
            $('#pauseButton').show();
         // $('#playButton').show();      
            $('#stopButton').show();   
            $('#replayButton').show(); */

            $('#playButton').prop('disabled', false);
            $('#replayButton').prop('disabled', false);
            $('#stopButton').prop('disabled', false);
            $('#pauseButton').prop('disabled', false);
  } 
}

 


$scope.polylineCtrl   = function(){
  
  startLoading();

  //console.log('polylineCtrl...');


  if($scope.hisloc.vehicleLocations!=null){

  if($scope.initGoogVal==0){

   // console.log('initGoogVal==0...');


    if($scope.map==undefined){

           // console.log('google init...');

               if($scope.hisloc.vehicleLocations.length != 0) {
                    //console.log(parseInt(locs.tripDistance));
                      if(parseInt($scope.hisloc.tripDistance) > 200) {
                          $scope.zoomCtrlVal     =  true;
                          $scope.hisloc.zoomLevel        =  12;
                          $scope.ZoomDisableBtn  =  true;
                          $scope.scrollsWheel    =  false;
                       // console.log(locs.tripDistance+"Active no Zoom");
                      } else{
                          $scope.zoomCtrlVal    = false;
                          $scope.ZoomDisableBtn = false;
                          $scope.scrollsWheel   = true;
                       }

                   /* var myOptions = {
                        zoom: Number(locs.zoomLevel),zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
                        center: new google.maps.LatLng(data.vehicleLocations[0].latitude, data.vehicleLocations[0].longitude),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                      //styles: [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]
                    }; */

                    var myOptions = {
                        zoom: Number($scope.hisloc.zoomLevel),
                        zoomControl: $scope.scrollsWheel,
                        zoomControlOptions: { position: google.maps.ControlPosition.LEFT_TOP}, 
                        center: new google.maps.LatLng($scope.hisloc.vehicleLocations[0].latitude, $scope.hisloc.vehicleLocations[0].longitude),
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        disableDoubleClickZoom: $scope.zoomCtrlVal,
                        scrollwheel: $scope.scrollsWheel,
                        disableDefaultUI: $scope.ZoomDisableBtn,
                     // styles: [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]
                    };

                    $scope.map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);

              //  }else{
              //    alert("Data Not Found!..");
              //    console.log('vehicleLocations not found!...'+data);
              //  }


              google.maps.event.addListener($scope.map, 'click', function(event) {
                $scope.clickedLatlng = event.latLng.lat() +','+ event.latLng.lng();
                $('#latinput').val($scope.clickedLatlng);
              });


                $scope.path=[];
                $scope.polylinearr=[];

                for(var i=0;i<$scope.hisloc.vehicleLocations.length;i++){

                  $scope.path.push(new google.maps.LatLng($scope.hisloc.vehicleLocations[i].latitude, $scope.hisloc.vehicleLocations[i].longitude));
                  
                    if($scope.hisloc.vehicleLocations[i].isOverSpeed=='Y'){
                          var pscolorval = '#ff0000';
                    } else {
                          var pscolorval = '#6dd538';
                    }

                  $scope.polylinearr.push(pscolorval);
              }
                
           

               
               // console.log($scope.path.length);

                if($scope.getValue != undefined){

                  $scope.getValueCheck($scope.getValue);
                }
              
                $scope.pointDistances = [];
                var sphericalLib     = google.maps.geometry.spherical;
                var pointZero        = $scope.path[0];
                var wholeDist        = sphericalLib.computeDistanceBetween(pointZero, $scope.path[$scope.path.length - 1]);
              
                $('#replaybutton').removeAttr('disabled');

            //}else{
            //  $('.error').show();
            //  $('#lastseen').html('<strong>From Date & time :</strong> -');
            //  $('#lstseendate').html('<strong>To  &nbsp; &nbsp; Date & time :</strong> -');
          // }
        //  }

      }

          
        var url = GLOBAL.DOMAIN_NAME+'/getGeoFenceView?vehicleId='+$scope.trackVehID;
    
        $scope.createGeofence(url);
        stopLoading();
         
           //}

          $scope.SiteCheckbox = {
             value1 : true,
             value2 : 'YES'
          }

   } else {

   $scope.path=[];
   $scope.polylinearr=[];

     for(var i=0;i<$scope.hisloc.vehicleLocations.length;i++){

                  $scope.path.push(new google.maps.LatLng($scope.hisloc.vehicleLocations[i].latitude, $scope.hisloc.vehicleLocations[i].longitude));
                  
                    if($scope.hisloc.vehicleLocations[i].isOverSpeed=='Y'){
                          var pscolorval = '#ff0000';
                    } else {
                          var pscolorval = '#6dd538';
                    }

                  $scope.polylinearr.push(pscolorval);
              }


   }

  

   // console.log('vehicle locations...');

    myStopFunction();

    markerClear();
    gmarkers=[];
    ginfowindow=[];

    var j =0;

    $('.radioBut').hide(0).delay(5000).show(100);

  
    vehicIcon=vehiclesChange(VehiType);
     



      //    console.log('google mapsss....');
          
                if($scope.markerheads){
                 // $scope.infosWindows.close();
                   $scope.markerstart.setMap(null);
                   $scope.markerend.setMap(null);
                  $scope.infosWindows=null;
                  $scope.markerheads.setMap(null);
                  $scope.markerheads=null;
                }
      
              var tempFlag=false;
                 
              for(var k=0;k<$scope.hisloc.vehicleLocations.length;k++){
                  if($scope.hisloc.vehicleLocations[k].position =='M' && tempFlag==false){
                    var firstval = k;
                    tempFlag=true;
                  }
              }

              if(firstval==undefined){
                  firstval=0;
              }

            var lastval = $scope.hisloc.vehicleLocations.length-1;
            $scope.addMarkerstart({ lat: $scope.hisloc.vehicleLocations[firstval].latitude, lng: $scope.hisloc.vehicleLocations[firstval].longitude , data: $scope.hisloc.vehicleLocations[firstval], path:$scope.path[firstval]});
            $scope.addMarkerend({ lat: $scope.hisloc.vehicleLocations[lastval].latitude, lng: $scope.hisloc.vehicleLocations[lastval].longitude, data: $scope.hisloc.vehicleLocations[lastval], path:$scope.path[lastval] });
              

                $scope.polyline = new google.maps.Polyline({
                map: $scope.map,
                path: $scope.path,
                strokeColor: '#068b03',
                strokeOpacity: 0.7,
                strokeWeight: 3,
                /*icons: [{
                        icon: lineSymbol,
                       offset: '100%'
                    }],*/
                clickable: true
                });

              $scope.markerheads = new google.maps.Marker();

              $scope.infosWindow = new google.maps.InfoWindow({maxWidth:180});
             // console.log('infowindow..');

              $scope.markerheads.addListener('click', function() {
                 $scope.infosWindow.open($scope.map,$scope.markerheads);
              });

              $scope.markerValue=1;

              pcount       = 0;
              lenCount2    = 0;
              timeInterval = setInterval(function(){ timerSet() }, $scope.timeDelay);
      
      
              var latLngBounds = new google.maps.LatLngBounds();
                for(var i = 0; i < $scope.path.length; i++) {
                latLngBounds.extend($scope.path[i]);
                  if($scope.hisloc.vehicleLocations[i]){
                   if($scope.hisloc.vehicleLocations[i].position!=undefined){
                    if($scope.hisloc.vehicleLocations[i].position=='P' || $scope.hisloc.vehicleLocations[i].position=='S' || $scope.hisloc.vehicleLocations[i].insideGeoFence=='Y' ){
                      
                    $scope.addMarker({ lat: $scope.hisloc.vehicleLocations[i].latitude, lng: $scope.hisloc.vehicleLocations[i].longitude , data: $scope.hisloc.vehicleLocations[i], path:$scope.path[i]});
                    $scope.infoBox($scope.map, gmarkers[j], $scope.hisloc.vehicleLocations[i]);
                    //$scope.markerPark.push($scope.marker);
                      j++;
                    }
                    
                  }
                }
              }

            stopLoading();  


   }else if($scope.plotVal==1){

   // console.log('plotVal==1');


    $scope.path=[];
       for(var i=0;i<$scope.hisloc.vehicleLocations.length;i++){

                  $scope.path.push(new google.maps.LatLng($scope.hisloc.vehicleLocations[i].latitude, $scope.hisloc.vehicleLocations[i].longitude));
                  
                    if($scope.hisloc.vehicleLocations[i].isOverSpeed=='Y'){
                          var pscolorval = '#ff0000';
                    } else {
                          var pscolorval = '#6dd538';
                    }

                  $scope.polylinearr.push(pscolorval);
              }
     $scope.plotVal=0; 


     
   // console.log('vehicle locations...');

    myStopFunction();

    markerClear();
    gmarkers=[];
    ginfowindow=[];

    var j =0;

    $('.radioBut').hide(0).delay(5000).show(100);

  
    vehicIcon=vehiclesChange(VehiType);
     



      //    console.log('google mapsss....');
          
                if($scope.markerheads){
                 // $scope.infosWindows.close();
                   $scope.markerstart.setMap(null);
                   $scope.markerend.setMap(null);
                  $scope.infosWindows=null;
                  $scope.markerheads.setMap(null);
                  $scope.markerheads=null;
                }
      
              var tempFlag=false;
                 
              for(var k=0;k<$scope.hisloc.vehicleLocations.length;k++){
                  if($scope.hisloc.vehicleLocations[k].position =='M' && tempFlag==false){
                    var firstval = k;
                    tempFlag=true;
                  }
              }

              if(firstval==undefined){
                  firstval=0;
              }

            var lastval = $scope.hisloc.vehicleLocations.length-1;
            $scope.addMarkerstart({ lat: $scope.hisloc.vehicleLocations[firstval].latitude, lng: $scope.hisloc.vehicleLocations[firstval].longitude , data: $scope.hisloc.vehicleLocations[firstval], path:$scope.path[firstval]});
            $scope.addMarkerend({ lat: $scope.hisloc.vehicleLocations[lastval].latitude, lng: $scope.hisloc.vehicleLocations[lastval].longitude, data: $scope.hisloc.vehicleLocations[lastval], path:$scope.path[lastval] });
              

                $scope.polyline = new google.maps.Polyline({
                map: $scope.map,
                path: $scope.path,
                strokeColor: '#068b03',
                strokeOpacity: 0.7,
                strokeWeight: 3,
                /*icons: [{
                        icon: lineSymbol,
                       offset: '100%'
                    }],*/
                clickable: true
                });

              $scope.markerheads = new google.maps.Marker();

              $scope.infosWindow = new google.maps.InfoWindow({maxWidth:180});
             // console.log('infowindow..');

              $scope.markerheads.addListener('click', function() {
                 $scope.infosWindow.open($scope.map,$scope.markerheads);
              });

              $scope.markerValue=1;

              pcount       = 0;
              lenCount2    = 0;
              timeInterval = setInterval(function(){ timerSet() }, $scope.timeDelay);
      
      
              var latLngBounds = new google.maps.LatLngBounds();
                for(var i = 0; i < $scope.path.length; i++) {
                latLngBounds.extend($scope.path[i]);
                  if($scope.hisloc.vehicleLocations[i]){
                   if($scope.hisloc.vehicleLocations[i].position!=undefined){
                    if($scope.hisloc.vehicleLocations[i].position=='P' || $scope.hisloc.vehicleLocations[i].position=='S' || $scope.hisloc.vehicleLocations[i].insideGeoFence=='Y' ){
                      
                    $scope.addMarker({ lat: $scope.hisloc.vehicleLocations[i].latitude, lng: $scope.hisloc.vehicleLocations[i].longitude , data: $scope.hisloc.vehicleLocations[i], path:$scope.path[i]});
                    $scope.infoBox($scope.map, gmarkers[j], $scope.hisloc.vehicleLocations[i]);
                    //$scope.markerPark.push($scope.marker);
                      j++;
                    }
                    
                  }
                }
              }

            stopLoading();  

          


     }else{

   //   console.log('pol lin else...');

          stopLoading();  
         timeInterval = setInterval(function(){ timerSet() }, $scope.timeDelay);
     }
  
       

    
    }
  }






  $scope.replays= function(){

    /*  $('#playButton').hide();
        $('#replayButton').show();
        $('#stopButton').show();  
        $('#pauseButton').show(); */

      $('#playButton').prop('disabled', true);
      $('#replayButton').prop('disabled', false);
      $('#stopButton').prop('disabled', false);
      $('#pauseButton').prop('disabled', false);

      if($scope.googleMap==true && $scope.osmMap==false){

         window.clearInterval(timeInterval);               
         pcount = 0; 
         timeInterval = setInterval(function(){ timerSet() }, $scope.timeDelay);
     
      } else if($scope.osmMap==true && $scope.googleMap==false){

         clearInterval($scope.osmInterVal);
         $scope.lineCount_osm = 0;
         $scope.osmInterVal  = setInterval(function(){ osmPolyLine() },$scope.timeDelay);
      }

  }
  
  $scope.pausehis= function(){

     $scope.pasVal=1; 
    
    /* $('#stopButton').hide(); 
       $('#pauseButton').hide();
       $('#replayButton').hide();
       $('#playButton').show(); */

      $('#playButton').prop('disabled', false);
      $('#replayButton').prop('disabled', false);
      $('#stopButton').prop('disabled', true);
      $('#pauseButton').prop('disabled', true);

  if($scope.googleMap==true && $scope.osmMap==false){
       
    window.clearInterval(timeInterval);

  } else if($scope.osmMap==true && $scope.googleMap==false){

    clearInterval($scope.osmInterVal);
  }

}

  $scope.speedchange=function(){

    if($scope.speedVal!==1){
      $('#playButton').prop('disabled', true);
      $('#replayButton').prop('disabled', false);
      $('#stopButton').prop('disabled', false);
      $('#pauseButton').prop('disabled', false);

      /* $('#playButton').hide();
         $('#replayButton').show();
         $('#stopButton').show();  
         $('#pauseButton').show(); */
    }

    if($scope.pasVal==1){
      /* $('#playButton').hide();
         $('#replayButton').show();
         $('#stopButton').show();  
         $('#pauseButton').show(); */

      $('#playButton').prop('disabled', true);
      $('#replayButton').prop('disabled', false);
      $('#stopButton').prop('disabled', false);
      $('#pauseButton').prop('disabled', false);

      $scope.pasVal=0;
    }

      if($scope.googleMap==true && $scope.osmMap==false){

        window.clearInterval(timeInterval);
            timeInterval=setInterval(function(){ timerSet() }, $scope.timeDelay );

      } else if($scope.osmMap==true && $scope.googleMap==false){

          clearInterval($scope.osmInterVal);
              $scope.osmInterVal  = setInterval(function(){ osmPolyLine() },$scope.timeDelay);
      }

  }
  
  $scope.playhis=function(){

    /*  $('#playButton').hide();
        $('#replayButton').show();
        $('#stopButton').show();  
        $('#pauseButton').show();  */

      $('#playButton').prop('disabled', true);
      $('#replayButton').prop('disabled', false);
      $('#stopButton').prop('disabled', false);
      $('#pauseButton').prop('disabled', false);

      if($scope.googleMap==true && $scope.osmMap==false){

        window.clearInterval(timeInterval);     
          timeInterval=setInterval(function(){ timerSet() }, $scope.timeDelay );

      } else if($scope.osmMap==true && $scope.googleMap==false){

          clearInterval($scope.osmInterVal);
          $scope.osmInterVal  = setInterval(function(){ osmPolyLine() },$scope.timeDelay);
      }
  }

  $scope.stophis=function(){

    /*  $('#stopButton').hide(); 
        $('#pauseButton').hide();
        $('#replayButton').show();
        $('#playButton').show();  */

      $('#playButton').prop('disabled', true);
      $('#replayButton').prop('disabled', false);
      $('#stopButton').prop('disabled', true);
      $('#pauseButton').prop('disabled', true);

      if($scope.googleMap==true && $scope.osmMap==false){

        window.clearInterval(timeInterval);
      } else if($scope.osmMap==true && $scope.googleMap==false){

        clearInterval($scope.osmInterVal);
      }
  }

  $(document).ready(function(){
      $('#minmax1').click(function(){
        $('#contentreply').animate({
                height: 'toggle'
            },500);
        });
      });

  $("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
  });
    
  $(function () {
    $('#dateFrom, #dateTo').datetimepicker({
        format:'YYYY-MM-DD',
        useCurrent:true,
        pickTime: false
    });
    $('#timeFrom').datetimepicker({
        pickDate: false,
        useCurrent:true,
    });
    $('#timeTo').datetimepicker({
        useCurrent:true,
        pickDate: false
    });
  });
        
  $(document).ready(function(){
        $('#editAction').click(function(){
            // $('#contentmin').animate({
            //     height: 'toggle'
            // },500);
            console.log(' edit action ')
        });
    });
  $('.legendlist').hide()
    $(document).ready(function() {
      $('.viewList').click(function(){
        $('.legendlist').animate({
          height: 'toggle'
        })
      })
    });

  $('ul.tabs').each(function(){
  // For each set of tabs, we want to keep track of
  // which tab is active and its associated content
  var $active, $content, $links = $(this).find('a');

  // If the location.hash matches one of the links, use that as the active tab.
  // If no match is found, use the first link as the initial active tab.
  $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
  $active.addClass('current');

  $content = $($active[0].hash);

  // Hide the remaining content
  $links.not($active).each(function () {
    $(this.hash).hide();
  });

  // Bind the click event handler
  $(this).on('click', 'a', function(e){
    // Make the old tab inactive.
    $active.removeClass('current');
    $content.hide();

    // Update the variables with the new link and content
    $active = $(this);
    $content = $(this.hash);

    // Make the tab active.
    $active.addClass('current');
    $content.show();

    // Prevent the anchor's default click action
    e.preventDefault();
  });
});

}])
.directive('map', ['$http', '_global', function($http, GLOBAL) {
    return {
      restrict: 'E',
      replace: true,
      template: '<div></div>',
      link: function(scope, element, attrs) {


      }
    };
}]);

app.directive('maposm', function($http, vamoservice) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs){

         // console.log('osm map directive...');

      /*  var mapLink = '<a href="http://207.154.194.241/nominatim/lf.html">OpenStreeetMap</a>'
          var map_osm = new L.map('map_canvas2',{ center: [13.0827,80.2707],/* minZoom: 4,zoom: 8 });

          new L.tileLayer(
            'http://207.154.194.241/osm_tiles/{z}/{x}/{y}.png', {
             attribution: '&copy; '+mapLink+' Contributors',
          // maxZoom: 18,
          }).addTo(map_osm); */
      }
  };

}); 

