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
		<p>
				<strong><br/>SmsSender: </strong><br/><br/> {{ $smsSender }}<br>
		</p>
		<p>
				<strong><br/>SmsProvider: </strong><br/><br/> {{ $smsProvider }}<br>
		</p>
		<p>
				<strong><br/>ProviderUserName: </strong><br/><br/> {{ $providerUserName }}<br>
		</p>
		<p>
				<strong><br/>ProviderPassword: </strong><br/><br/> {{ $providerPassword }}<br>
		</p>

	</div>
@stop