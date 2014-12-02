@extends('includes.adminheader')
@section('mainContent')
<h1>Showing Franchise</h1>

	<div class="jumbotron text-center">
		
		<p>
			<br/><h2>{{ 'Franchise Name : ' . $fname }}</h2>
			<br/><h2>{{ 'Franchise Id : ' . $fcode }}</h2>
			<br/><strong>Other Details: </strong><br/><br/> {{ $franchiseDetails }}<br>
		
		</p>
	</div>
@stop