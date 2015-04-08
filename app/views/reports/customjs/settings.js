var app = angular.module('mapApp', []);
app.controller('mainCtrl',function($scope, $http){ 
	$scope.url = 'http://'+globalIP+'/vamo/public/getVehicleLocations';
	$scope.path = [];
	$scope.inter = 0;
	$http.get($scope.url).success(function(data){
		$scope.locations = data;
		$scope.trackVehID =$scope.locations[0].vehicleLocations[0].vehicleId;
		$scope.iframeurl='http://'+globalIP+'/vamo/public/vdmVehicles';
	}).error(function(){ /*alert('error'); */});
    $scope.genericFunction = function(vehicleno, index){
		$scope.selected = index;
		$scope.iframeurl='http://'+globalIP+'/vamo/public/vdmVehicles/'+vehicleno+'/edit'
	}
          
});
