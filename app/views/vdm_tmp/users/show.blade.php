@extends('includes.vdmheader')
@section('mainContent')


<h1>Showing UserId</h1>

	<div class="jumbotron text-center">
		<h2>{{ $userId }}</h2>
			<p>
				<strong><br/>Mobile No: </strong><br/><br/> {{ $mobileNo }}<br>
		</p>
		<p>
				<strong><br/>Vehicle Groups: </strong><br/><br/> {{ $vehicleGroups }}<br>
		</p>
	</div>
@stop