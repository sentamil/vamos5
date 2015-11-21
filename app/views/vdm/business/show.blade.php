@extends('includes.vdmheader')
@section('mainContent')

<h1>Showing Vehicle</h1>

	<div class="jumbotron text-center">
		
		<p>
			<br/><h2>{{ 'Vehicle ID : ' . $vehicleId }}</h2>
			<br/><strong>Other Details: </strong><br/><br/> {{ $deviceRefData }}<br>
		</p>
	</div>
@stop