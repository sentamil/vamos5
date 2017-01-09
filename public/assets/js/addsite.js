var getIP = globalIP;

app.controller('mainCtrl',function($scope, $http){

  var line;
  var lineList        = [];
  var lineSymbol      = {path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW};
  var text;
  var drop;
  $scope.textValue    = '';
  $scope.dropValue    = '';
  $scope.orgID        = '';
  $scope.orgIdlist    = [];
  $scope.search;
  var seclat          = '';
  var seclan          = '';
  var latlanList      = [];
  var mergeList       = [];
  var dropDown        = [];
  var polygenList     = [];
  var polygenColor;
  var editableColor   = {"editable": true, "strokeColor": 'red', "fillColor": "#f4f4f4", "fillOpacity": .5, "strokeWeight": 3};
  marker = new google.maps.Marker({});
  var oldName         = '';
  $scope.url          = 'http://'+getIP+context+'/public/viewSite';
  var myOptions       = {
                          zoom: 7,
                          center: new google.maps.LatLng(12.993803, 80.193075),
                          
                          mapTypeId: google.maps.MapTypeId.ROADMAP,
                          zoomControlOptions: {
                            position: google.maps.ControlPosition.LEFT_BOTTOM
                          },

                          streetViewControlOptions: {
                            position: google.maps.ControlPosition.LEFT_BOTTOM
                          },

                        };

  $scope.map         = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
  var polygenValue;
  var shapes = [];
  var drawingManager = new google.maps.drawing.DrawingManager({
          
          drawingControl: true,
          drawingControlOptions: {
          
            position: google.maps.ControlPosition.LEFT_TOP,
            drawingModes: ['polygon'],
            
          },
          
          polygonOptions: editableColor,
          // circleOptions:editableColor,
          // rectangleOptions:editableColor,

          // markerOptions: {icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'},
          // circleOptions: {
          //   fillColor: '#ffff00',
          //   fillOpacity: 1,
          //   strokeWeight: 5,
          //   clickable: true,
          //   editable: true,
          //   zIndex: 1
          // }
        });


  // $scope.map.data.setControls(['Polygon']);
  //   $scope.map.data.setStyle({
  //       editable: true,
  //       draggable: true
  //   });
  drawingManager.setMap($scope.map);


  var list  =[];
  $scope.$watch('url', function(){
    serviceCall()
  });
  

//   google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
//     var coordinates = (polygon.getPath().getArray());
//     console.log(coordinates.length);
//     console.log(coordinates);
// });




function listLatLng(_latlng){
  latlanList = [];
  if(_latlng.length > 2){
      angular.forEach(_latlng, function(value, key){
        latlanList.push(value.lat()+":"+value.lng());
      })
      latlanList.push(_latlng[0].lat()+":"+_latlng[0].lng());
    }
    // console.log(latlanList)
}


google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
  
  shapes.push(polygon);
  listLatLng(polygon.getPath().getArray());

  google.maps.event.addListener(polygon.getPath(), 'set_at', function(po) {
    listLatLng(polygon.getPath().getArray());
    shapes.push(polygon);
  });

  google.maps.event.addListener(polygon.getPath(), 'insert_at', function(po) {
    listLatLng(polygon.getPath().getArray());
    shapes.push(polygon);
  });

});


  var input_value   =  document.getElementById('pac-input');
  var sbox     =  new google.maps.places.SearchBox(input_value);
  
  // search box function
  sbox.addListener('places_changed', function() {
    marker.setMap(null);
    var places = sbox.getPlaces();
    marker = new google.maps.Marker({
    position: new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()),
    animation: google.maps.Animation.BOUNCE,
   map: $scope.map,
    
  });
  console.log(' lat lan  '+places[0].geometry.location.lat(), places[0].geometry.location.lng())
    $scope.map.setCenter(new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()));
    $scope.map.setZoom(13);
  });


   //create button click
  $scope.drawline   =   function()
  {

    // if(polygenValue)
    shapes[0].setEditable(false);
    var URL_ROOT    = "AddSiteController/";    /* Your website root URL */
    var text        = $scope.textValue;
    var drop        = $scope.dropValue;
    var org         = $scope.orgID;
    // post request
    if(text && drop && latlanList.length>=3 && org)
    {
      $.post( URL_ROOT+'store', {
        '_token': $('meta[name=csrf-token]').attr('content'),
        'siteName': text,
        'siteType': drop,
        'org':org,
        'latLng': latlanList,
      })
      .done(function(data) {
        console.log("Sucess");
      })
      .fail(function() {
        console.log("fail");
      });
     
    }else{
      
      console.log(' enter all field ')
    }
    serviceCall();
  }

  //create new json
  function serviceCall()
  {
    //$scope.orgIdlist    = [];

    var url_site          = 'http://'+getIP+context+'/public/viewSite';
    $http.get(url_site).success(function(response){
      mergeList       = [];
      dropDown=[];
      $scope.dropDownList=response;
      if(response != "")
      for(var i = 0; response.siteParent.length>i; i++)
      {
        if(response.siteParent[i]!=undefined)
        {
          for(var j = 0; response.siteParent[i].site.length>j; j++)
            {
              mergeList.push({'siteName': response.siteParent[i].site[j].siteName, 'siteType' : response.siteParent[i].site[j].siteType, 'latLng' : response.siteParent[i].site[j].latLng, 'orgId' : response.siteParent[i].site[j].orgId})
            }
        }
      }
      $scope.orgIdlist    = mergeList;
      
    });
  }

  //direct map click 
  // google.maps.event.addListener($scope.map, 'click', function(event) {
  //   var latClick   = event.latLng.lat();
  //   var lanclick   = event.latLng.lng();
  //   pointToPoint(latClick, lanclick);
  // });


  // draw two lat lan as line in map function
  function pointToPoint(lat, lan)
  { 
    latlanList.push(lat+":"+lan);
    var firstlat  = lat;
    var firstlan  = lan;
    var latlan    = lat+","+lan;
    if(seclan && seclan)
    {
      line = new google.maps.Polyline({
            path: [
                new google.maps.LatLng(seclat, seclan), 
                new google.maps.LatLng(firstlat, firstlan)
            ],
            strokeColor: "#008000",
            strokeOpacity: 1.0,
            strokeWeight: 3,
            icons: [{
              icon: lineSymbol,
              offset: '100%'
            }],
            map: $scope.map
        });
     lineList.push(line);
    }
    seclat    = firstlat;
    seclan    = firstlan;
  }


  function clearShaps(){
    for (var i = 0; i < shapes.length; i++) {
      shapes[i].setMap(null);
    }
  }

  //clear button on map
  $scope.clearline  =   function()
  {

       
    // if (drawingManager.getDrawingMode() != null) {
          
    //       shapes = [];
    //   }
    // drawingManager.setMap();
    // console.log(shapes);
    // marker.setMap(null);
    clearShaps();
    if($scope.marker)
      $scope.marker.setMap(null);
    if(polygenColor)
      polygenColor.setMap(null);
    if (lineList){
      for (var i=0; i<lineList.length; i++){
        lineList[i].setMap(null);
       }
    }
    latlanList    = [];
    polygenList   = [];
    seclat        = '';
    seclan        = '';
    // $scope.textValue    = '';
    // $scope.dropValue    = '';
    // $scope.orgID        = '';

  }
  function centerMarker(listMarker){
    var bounds = new google.maps.LatLngBounds();
    for (i = 0; i < listMarker.length; i++) {
          bounds.extend(listMarker[i]);
      }
    return bounds.getCenter()
  }
 

  function polygenFunction(list){
    
    if(list.length>0){
      var sp;
      polygenList   = [];
      $scope.clearline();
      for(var i = 0; list.length>i; i++)
      {
          sp    = list[i].split(":");
          polygenList.push(new google.maps.LatLng(sp[0], sp[1]));
          latlanList.push(sp[0]+":"+sp[1]);
          seclat        = sp[0];
          seclan        = sp[1];
      }
      polygenColor = new google.maps.Polygon({
            path: polygenList,
            strokeColor: "#282828",
            strokeWeight: 1,
            fillColor: '#808080',
            fillOpacity: 0.50,
            map: $scope.map
        });
      
      var labelAnchorpos = new google.maps.Point(19, 0);  ///12, 37
      $scope.marker = new MarkerWithLabel({
         position: centerMarker(polygenList), 
         map: $scope.map,
         icon: 'assets/imgs/area_img.png',
         labelContent: $scope.textValue,
         labelAnchor: labelAnchorpos,
         labelClass: "labels", 
         labelInBackground: false
      });
      $scope.map.setCenter(centerMarker(polygenList)); 
      $scope.map.setZoom(14);  
    }
  }

  
  //inside the table td click function
  $scope.siteNameClick  =   function(user)
  {
      // if (drawingManager.getDrawingMode() != null) {
          // for (var i = 0; i < shapes.length; i++) {
          //     shapes[i].setMap(null);
          // }
          // shapes = [];
      // }
      clearShaps()
      drawingManager.set('drawingMode'); 
      oldName             = user.siteName;
      $scope.textValue    = user.siteName;
      $scope.dropValue    = user.siteType;
      $scope.orgID        = user.orgId;
      var split           = user.latLng.split(",");
      polygenFunction(split);
  }

  //join the add site function
  function drawlineJoin(list)
  {
    if(list.length>0)
    {
     $scope.clearline();
     var sp;
      for(var i = 0; list.length>i; i++)
      {
          sp    = list[i].split(":");
          pointToPoint(sp[0], sp[1]);
      }
      sp = list[0].split(":");
      pointToPoint(sp[0], sp[1]);
      $scope.map.setCenter(new google.maps.LatLng(sp[0], sp[1])); 
      $scope.map.setZoom(11);
      
    }
  }

  // function for the update button
  $scope.updateDrawline   =   function()
  {
    //console.log(' updateDrawline '+latlanList.length)
    var URL_ROOT    = "AddSiteController/";    /* Your website root URL */
    var text        = $scope.textValue;
    var drop        = $scope.dropValue;
    var org         = $scope.orgID;
    //console.log(' old value '+oldName)
    // post request for update
    if(text && drop && latlanList.length>=3 && org && oldName)
    {
      $.post( URL_ROOT+'update', {
        '_token': $('meta[name=csrf-token]').attr('content'),
        'siteName': text,
        'siteNameOld': oldName,
        'siteType': drop,
        'org':org,
        'latLng': latlanList,
      })
      .done(function(data) {
        console.log("Sucess");
      })
      .fail(function() {
        console.log("fail");
      });
     
    }else{
     
      console.log(' enter all field ')
    }
    serviceCall();
  }

  //delete function 
  $scope.deleteClick  =   function(response)
  {
    var URL_ROOT    = "AddSiteController/";  
    if(response)
    {
      $.post( URL_ROOT+'delete', {
        '_token': $('meta[name=csrf-token]').attr('content'),
        'org':response.orgId,
        'siteName': response.siteName,
      })
      .done(function(data) {
        console.log("Sucess");
      })
      .fail(function() {
        console.log("fail");
      });
     
    }else{
     
      console.log(' enter all field ')
    }
    serviceCall();
  }

$(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    //menu loading
    $("#testLoad").load("../public/menu");
});