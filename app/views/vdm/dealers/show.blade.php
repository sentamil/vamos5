@extends('includes.vdmheader')
@section('mainContent')


<h2><font color="blue">Showing DealerId</font></h2>

	<div class="jumbotron text-center">
		<h2><font color="green">{{ $userId }}</font></h2>
			<p>
				<strong><br/>Mobile No: </strong><br/><br/> {{ $mobileNo }}<br>
		</p>
		<p>
				<strong><br/>Email Id: </strong><br/><br/> {{ $email }}<br>
		</p>
		<p>
				<strong><br/>Website: </strong><br/><br/> {{ $website }}<br>
		</p>
	</div>
@stop