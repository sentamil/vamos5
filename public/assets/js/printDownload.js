app.controller('printHtml', function($scope, $http){
	console.log('inside the controller')

	function getParameterByName(name) {
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	$scope.vehicleId			=	getParameterByName('vid');
	$scope.vehicleName 			= 	getParameterByName('vName');
	console.log($scope.vehicleId)

	var url 	=	"http://"+globalIP+context+"/public/getGeoFenceView?vehicleId="+$scope.vehicleId;

	$http.get(url).success(function(data){
		$scope.geoStops 	= data.geoFence;
	})
})