@extends('includes.vdmheader')
@section('mainContent')

<h1>Edit Dealer</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}


{{ Form::model($dealerid, array('route' => array('vdmDealers.update', $dealerid), 'method' => 'PUT')) }}


	<div class="form-group">
		{{ Form::label('dealerid', 'Dealer Id :') }}
		<br/>
		{{ Form::label('dealerid', $dealerid) }}
	</div>

<div class="form-group">
		{{ Form::label('mobileNo', 'mobileNo') }}
		{{ Form::text('mobileNo', $mobileNo, array('class' => 'form-control')) }}
	</div>
<div class="form-group">
		{{ Form::label('email', 'email') }}
		{{ Form::text('email', $email, array('class' => 'form-control')) }}
	</div>

		 
		</br/>
		</br/>
		 
        
        </br/>
        </br/>
	
	{{ Form::submit('Update the User!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop