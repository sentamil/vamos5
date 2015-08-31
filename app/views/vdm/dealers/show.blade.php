@extends('includes.vdmheader')
@section('mainContent')


<h1>Showing DealerId</h1>

	<div class="jumbotron text-center">
		<h2>{{ $userId }}</h2>
			<p>
				<strong><br/>Mobile No: </strong><br/><br/> {{ $mobileNo }}<br>
		</p>
		<p>
				<strong><br/>Email Id: </strong><br/><br/> {{ $email }}<br>
		</p>
	</div>
@stop