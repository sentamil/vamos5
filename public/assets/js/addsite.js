var getIP = globalIP;
app.directive('map', function($http, vamoservice) {
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        link: function(scope, element, attrs) {
       //     vamoservice.getDataCall(scope.url).then(function(data) {
		   		// var locs = data;

		  //  		var myOptions = {
				// 	zoom: 13,
				// 	center: new google.maps.LatLng(12.993803, 80.193075),
				// 	mapTypeId: google.maps.MapTypeId.ROADMAP
				// 	//styles: [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]
    //         	};
    //         	scope.map = new google.maps.Map(document.getElementById(attrs.id), myOptions);
				// google.maps.event.addListener(scope.map, 'click', function(event) {
				// 				scope.clickedLatlng = event.latLng.lat() +','+ event.latLng.lng();
				// 				// $('#latinput').val(scope.clickedLatlng);
				// 			});
			// });
		  // $(document).on('pageshow', '#maploc', function(e, data){       
    //             	google.maps.event.trigger(document.getElementById('	maploc'), "resize");
    //        		 });
		  	
		  
        }
    };
});

app.controller('mainCtrl',function($scope, $http){

	var line;
	var lineList 		    = [];
	var lineSymbol 	    = {path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW};
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
  var oldName         = '';
  $scope.url          = 'http://'+getIP+'/vamo/public/viewSite';
  var myOptions		    = {
          								zoom: 7,
          								center: new google.maps.LatLng(12.993803, 80.193075),
          								mapTypeId: google.maps.MapTypeId.ROADMAP
          						   };

  $scope.map 		     = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
  var list  =[];
  $scope.$watch('url', function(){
    serviceCall()
  });
  
  //create button click
  $scope.drawline 	= 	function()
  {
  	var URL_ROOT    = "AddSiteController/";    /* Your website root URL */
  	var text 		    = $scope.textValue;
  	var drop 		    = $scope.dropValue;
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

    var url_site          = 'http://'+getIP+'/vamo/public/viewSite';
    $http.get(url_site).success(function(response){
      
     
      mergeList       = [];
      dropDown=[];
      $scope.dropDownList=response;
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
  google.maps.event.addListener($scope.map, 'click', function(event) {
	  var latClick	 = event.latLng.lat();
	  var lanclick	 = event.latLng.lng();
	  pointToPoint(latClick, lanclick);
  });


  // draw two lat lan as line in map function
 	function pointToPoint(lat, lan)
 	{	
 		latlanList.push(lat+":"+lan);
 		var firstlat 	= lat;
 		var firstlan 	= lan;
 		var latlan 		= lat+","+lan;
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
 		seclat 		= firstlat;
 		seclan 		= firstlan;
 	}


  //clear button on map
 	$scope.clearline 	= 	function()
 	{
 		if (lineList){
     	for (var i=0; i<lineList.length; i++){
    		lineList[i].setMap(null);
       }
 		}
    latlanList    = [];
    seclat        = '';
    seclan        = '';
    // $scope.textValue    = '';
    // $scope.dropValue    = '';
    // $scope.orgID        = '';

 	}

  
  //inside the table td click function
  $scope.siteNameClick  =   function(user)
  {
      oldName             = user.siteName;
      $scope.textValue    = user.siteName;
      $scope.dropValue    = user.siteType;
      $scope.orgID        = user.orgId;
      var split           = user.latLng.split(",");
      drawlineJoin(split);
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
    console.log(' old value '+oldName)
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


});