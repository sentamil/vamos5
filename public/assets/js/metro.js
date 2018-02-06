
var MetronicApp = angular.module("MetronicApp", []); 


MetronicApp.controller('AppController', ['$scope','$http','$filter', function($scope,$http,$filter) {


	$scope.logoName="GPSVTS";

		$http.get('http://188.166.244.126:9000/getVehicleLocations?userId=MSS').success(function(data){
			$scope.vehiDatas = data[0];
			$scope.vehiData  = data;
			$scope.repData   = data[0].vehicleLocations;
  			console.log($scope.vehiData);
   		});


        var myLatLng = {lat: -25.363, lng: 131.044};

        var map = new google.maps.Map(document.getElementById('gmap_geocoding'), {
          zoom: 4,
          center: myLatLng
        });

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });


     var markerSearch = new google.maps.Marker({});
     
    var input_value   =  document.getElementById('gmap_geocoding_address');
    var sbox          =  new google.maps.places.SearchBox(input_value);
  // search box function
    sbox.addListener('places_changed', function() {
      markerSearch.setMap(null);
      var places = sbox.getPlaces();
      markerSearch = new google.maps.Marker({
        position: new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()),
        animation: google.maps.Animation.BOUNCE,
        map: map,
    
      });
    //  console.log(' lat lan  '+places[0].geometry.location.lat(), places[0].geometry.location.lng())
      map.setCenter(new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()));
      map.setZoom(13);
    });

     $scope.groupSelection = function(){

      console.log('group...');

          $http({
            method : "GET",
            url :'http://188.166.244.126:9000/getVehicleLocations?userId=MSS&group=MSS-CARGO:SMP',
          }).then(function mySuccess(response) {
                $scope.vehiDatas = response.data[1];
                $scope.vehiData  = response.data;
                $scope.repData   = response.data[1].vehicleLocations;
                console.log($scope.vehiData);

        }, function myError(response) {
          
        });
      }; 

    var indVehicle;

    $scope.genericFunction = function(vehId){

      console.log(vehId);

      console.log('generic..');
   
      indVehicle = $filter('filter')($scope.vehiData[0].vehicleLocations, { vehicleId:vehId});
      $scope.individualVehicle = indVehicle[0];

      var maps = new google.maps.Map(document.getElementById('gmap_geocoding'), {
          zoom: 13,
          center: new google.maps.LatLng(indVehicle[0].latitude, indVehicle[0].longitude),
        });

         var markerSearchs = new google.maps.Marker({});
    
       markerSearchs = new google.maps.Marker({
        position: new google.maps.LatLng(indVehicle[0].latitude, indVehicle[0].longitude),
        animation: google.maps.Animation.BOUNCE,
        map: maps,
    
      });

    };


    function distVal(newVal){

      console.log(newVal);

      var retsArr=[];

      for(var i=0; i<newVal.length; i++){
        //console.log(newVal[i].shortName);
        retsArr.push( { "distance" :newVal[i].distanceToday,"date" : newVal[i].shortName});
      }

    var newArrs = [];
        newArrs = retsArr;
     
    Highcharts.chart('containerss', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'World\'s largest cities per 2014'
    },
    subtitle: {
        text: 'Source: <a href="http://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
    },
    xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Population (millions)'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>'
    },
    series: [{
        name: 'Population',
        data: [
            ['Shanghai', 23.7],
            ['Lagos', 16.1],
            ['Istanbul', 14.2],
            ['Karachi', 14.0],
            ['Mumbai', 12.5],
            ['Moscow', 12.1],
            ['São Paulo', 11.8],
            ['Beijing', 11.7],
            ['Guangzhou', 11.1],
            ['Delhi', 11.1],
            ['Shenzhen', 10.5],
            ['Seoul', 10.4],
            ['Jakarta', 10.0],
            ['Kinshasa', 9.3],
            ['Tianjin', 9.3],
            ['Tokyo', 9.0],
            ['Cairo', 8.9],
            ['Dhaka', 8.9],
            ['Mexico City', 8.9],
            ['Lima', 8.9]
        ],
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});


}


function distChart(){   
 
 var newArr = []; 

        $http({
            method : "GET",
            url :'http://188.166.244.126:9000/getExecutiveReport?groupId=JCT:SMP&fromDate=2018-01-29&toDate=2018-02-04&userId=JCT',
          }).then(function mySuccess(response) {

            console.log(response.data);

            newArr = response.data.execReportData;  

            //console.log(newArr);

            distVal(newArr);


        }, function myError(response) {
          
        });

 /* var newArr =[{
      "date" : "2012-01-01",
      "distance" : 227,
      "townName" : "New York",
      "townName2" : "New York",
      "townSize" : 25,
      "latitude" : 40.71,
      "duration" : 408
  }];
*/


}

distChart();


Highcharts.chart('containerx', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Efficiency Optimization by Branch'
    },
    xAxis: {
        categories: [
            'Seattle HQ',
            'San Francisco',
            'Tokyo'
        ]
    },
    yAxis: [{
        min: 0,
        title: {
            text: 'Employees'
        }
    }, {
        title: {
            text: 'Profit (millions)'
        },
        opposite: true
    }],
    legend: {
        shadow: false
    },
    tooltip: {
        shared: true
    },
    plotOptions: {
        column: {
            grouping: false,
            shadow: false,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Employees',
        color: 'rgba(165,170,217,1)',
        data: [150, 73, 20],
        pointPadding: 0.3,
        pointPlacement: -0.2
    }, {
        name: 'Employees Optimized',
        color: 'rgba(126,86,134,.9)',
        data: [140, 90, 40],
        pointPadding: 0.4,
        pointPlacement: -0.2
    }, {
        name: 'Profit',
        color: 'rgba(248,161,63,1)',
        data: [183.6, 178.8, 198.5],
        tooltip: {
            valuePrefix: '$',
            valueSuffix: ' M'
        },
        pointPadding: 0.3,
        pointPlacement: 0.2,
        yAxis: 1
    }, {
        name: 'Profit Optimized',
        color: 'rgba(186,60,61,.9)',
        data: [203.6, 198.8, 208.5],
        tooltip: {
            valuePrefix: '$',
            valueSuffix: ' M'
        },
        pointPadding: 0.4,
        pointPlacement: 0.2,
        yAxis: 1
    }]
});


     
   
}]);


