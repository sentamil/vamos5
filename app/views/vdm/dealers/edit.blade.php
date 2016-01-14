@extends('includes.vdmheader')
@section('mainContent')

<h1><font color="blue">Edit Dealer</font></h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}


{{ Form::model($dealerid, array('route' => array('vdmDealers.update', $dealerid), 'method' => 'PUT')) }}


							<div class="row">
								<div class="col-md-4">
	<div class="form-group">
		<h4><font color="green">{{ Form::label('dealerid', 'Dealer Id :') }}</h4>
		
		{{ Form::label('dealerid', $dealerid) }}</font>
	</div>

<div class="form-group">
		{{ Form::label('mobileNo', 'MobileNo :') }}
		{{ Form::text('mobileNo', $mobileNo, array('class' => 'form-control')) }}
	</div>
<div class="form-group">
		{{ Form::label('email', 'EMail :') }}
		{{ Form::text('email', $email, array('class' => 'form-control')) }}
	</div>
<div class="form-group">
		{{ Form::label('website', 'EMail :') }}
		{{ Form::text('website', $website, array('class' => 'form-control')) }}
	</div>
		 
		</br/>
		</br>
	
	{{ Form::submit('Update the User!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop