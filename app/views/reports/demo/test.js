var app = angular.module('testApp', []);
app.controller('testCtrl',function($scope, $http, $location){ 
	//alert(globalIP);
	$scope.myVar = 1;

   $scope.$watch('myVar', function() {
       //alert('hey, myVar has changed!');
       $scope.globalIP	=	globalIP;
   });
	
	$scope.url = 'http://'+globalIP+':8087/vamosgps/public//getVehicleLocations';
	$scope.path = [];
	$scope.inter = 0;
	//alert($scope.url)
	$scope.demo	= "test";
	$http.get($scope.url).success(function(data){
		$scope.locations = data;
		console.log(data);
		$scope.trackVehID =$scope.locations[0].vehicleLocations[0].vehicleId;
		$scope.iframeurl='http://'+globalIP+':8087/vamosgps/public//vdmVehicles';
	}).error(function(){ /*alert('error'); */});
	
	$scope.genericFunction = function(vehicleno, index){
		$scope.selected = index;
		//$scope.iframeurl='http://'+globalIP+':8087/vamosgps/public//vdmVehicles/'+vehicleno+'/edit'
		window.location.href = 'http://'+globalIP+':8087/vamosgps/public//vdmVehicles/'+vehicleno+'/edit';
	}
});

app.config(['$interpolateProvider', function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
}]);
