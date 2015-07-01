@extends('includes.vdmheader')
@section('mainContent')

<h1>Showing Device</h1>

	<div class="jumbotron text-center">
		
		<p>
			<br/><h2>{{ 'Vehicle Id : ' . $vehicleId }}</h2>
			<br/><strong>Proximity Level: </strong><br/><br/> {{ $proximityArr }}<br>
			<br/><strong>Geo Address: </strong><br/><br/> {{ $geoAddres }}<br>
			<br/><strong>Geo Coordinates: </strong><br/><br/> {{ $geoCoordinates }}<br>
			
		
		</p>
	</div>
@stop