@extends('includes.vdmheader')
@section('mainContent')
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>		
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4>Showing Vehicle</h4>
					</div>
					<div class="panel-body" ng-app="myapp">
						
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-3">{{ Form::label('Vehicle ID', 'Vehicle ID') }}</div>
							<div class="col-md-4">{{ Form::label('vehicleId', $vehicleId,array('class' => 'form-control')) }}</div>
							
						</div>
						<div class="row" ng-controller="myCtrl">
							<div class="col-md-2"></div>
							<div class="col-md-3"><label>Vehicle ID</label></div>
							<div class="col-md-4"><label class="form-control"></label></div>
							asd
							<postsPagination style="width: 100px; height: 100px; background-color: green"></postsPagination>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<!-- <div class="jumbotron text-center"> -->
		
		<!-- <p> -->
			<br/><h4>{{ 'Vehicle ID : ' . $vehicleId }}</h4>
			<!-- <br/><strong>Other Details: </strong><br/><br/> {{ $deviceRefData }}<br> -->
			
			<script> 
				var app 	=	angular.module('myapp', []);
				app.directive('postsPagination', function($scope){  
				   return{
				    restrict: 'E',
        			replace: true,
				    template: '<div></div>'
							
				   };
				});
				app.controller('myCtrl', function($scope) {
					
				});
			</script>
		<!-- </p> -->
	<!-- </div> -->
@stop