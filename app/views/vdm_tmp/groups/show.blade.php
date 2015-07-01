@extends('includes.vdmheader')
@section('mainContent')
<h1>Showing Group Details</h1>

	<div class="jumbotron text-center">
		<h2>{{ $groupId }}</h2>
		<p>
			<strong><br/>Vehicle List: </strong><br/><br/> {{ $vehicleList }}<br>
		</p>
	</div>
@stop