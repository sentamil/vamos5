var app = angular.module('testApp', []);
app.controller('testCtrl',function($scope, $http){ 
	//alert(globalIP);
	$scope.url = 'http://'+globalIP+'/vamo/public/getVehicleLocations';
	$scope.path = [];
	$scope.inter = 0;
	//alert($scope.url)
	$scope.demo	= "test";
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

app.config(['$interpolateProvider', function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
}]);
