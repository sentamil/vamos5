@extends('includes.vdmheader')
@section('mainContent')
<h2><font color="blue"><b>Showing Group Details</b></font></h1>

	<div class="jumbotron text-center">
		<h2>{{ $groupId }}</h2>
		<p>
			<strong><br/>Vehicle List: </strong><br/><br/> {{ $vehicleList }}<br>
		</p>
	</div>
@stop