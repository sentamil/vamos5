@extends('includes.vdmEditHeader')
@section('mainContent')
<h1>Edit Vehicle</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}
{{ Form::open(array('url' => 'vdmVehicles/updateCalibration')) }}

<div class="row">
		<div class="col-md-4">
	<div class="form-group">
		{{ Form::label('vehicleId', 'Vehicle Id :')  }}
		 {{ Form::hidden('vehicleId', $vehicleId, array('class' => 'form-control')) }}
		 {{ Form::text('vehicleId1', $vehicleId, array('class' => 'form-control','disabled' => 'disabled')) }}
	</div>
	<div class="form-group">
		{{ Form::label('deviceId', 'Device Id') }}
		<br/>
		{{ Form::text('deviceId', $deviceId, array('class' => 'form-control','disabled' => 'disabled')) }}

	</div>
	
	
     </div>

	<div class="col-md-4">
   <table><tr><td>
   
   
   <td>{{ Form::text('volt', "Volt", array('class' => 'form-control','disabled' => 'disabled')) }}</td>
   <td>{{ Form::text('litre', "Litre", array('class' => 'form-control','disabled' => 'disabled')) }}</td>
	@foreach($place as $key => $value)
	<table><tr>
	<td></td><td>
	{{ Form::text('volt'.$j++,$value,  array('class' => 'form-control')) }}</td><td>
	{{ Form::text( 'litre'.$i++,$key ,array('class' => 'form-control') ) }}</td>
	</tr></table>
	
	
	 
	
	 @endforeach
	
	</td><td >
	
	</td></tr></table>
	
	</div>
	</div>

	{{ Form::submit('Calibrate!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop
