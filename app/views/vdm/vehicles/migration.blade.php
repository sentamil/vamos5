@extends('includes.vdmEditHeader')
@section('mainContent')
<h1>Vehicle Migration</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmVehicles/migrationUpdate')) }}
<div class="row">
		<div class="col-md-4">
	<div class="form-group">
		{{ Form::label('vehicleId', 'Vehicle Id :')  }}
		 {{ Form::text('vehicleId', $vehicleId, array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('deviceId', 'Device Id') }}
		<br/>
		{{ Form::text('deviceId', $deviceId, array('class' => 'form-control')) }}

	</div>
	
    
   
	
     </div>

	
	</div>

	{{ Form::submit('Migrate the Vehicle!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop
