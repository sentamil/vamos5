@extends('includes.vdmheader')
@section('mainContent')
<h1>Places of Interest</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmPOI')) }}

		
	<div class="form-group">
		{{ Form::label('vehicleId', 'Vehicle Id') }}
		
		{{ Form::select('vehicleId', $vehicleList,Input::old('vehicleId'),array('class' => 'form-control')) }}
		
	</div>
	
	<div class="form-group">
		{{ Form::label('proximityLevel1', 'Proximity Level') }}
		{{ Form::select('proximityLevel1', array('0' => 'Select a Level', '1' => '1 km', '2' => '2 km', '5' => '5 km', '10' => '10 km', '25' => '25 km', '50' => '50 km'), Input::old('proximityLevel1'), array('class' => 'form-control')) }}
				
	</div>
	
	<div class="form-group">
		{{ Form::label('location1', 'Name of Location -1 ') }}
		{{ Form::text('location1', Input::old('location1'), array('class' => 'form-control')) }}
	</div>
	
		<div class="form-group">
		{{ Form::label('proximityLevel2', 'Proximity Level') }}
		{{ Form::select('proximityLevel2', array('0' => 'Select a Level', '1' => '1 km', '2' => '2 km', '5' => '5 km', '10' => '10 km', '25' => '25 km', '50' => '50 km'), Input::old('proximityLevel2'), array('class' => 'form-control')) }}
		
	</div>
	
	<div class="form-group">
		{{ Form::label('location2', 'Name of Location -2') }}
		{{ Form::text('location2', Input::old('location2'), array('class' => 'form-control')) }}
	</div>
	
		<div class="form-group">
		{{ Form::label('proximityLevel3', 'Proximity Level') }}
		{{ Form::select('proximityLevel3', array('0' => 'Select a Level', '1' => '1 km', '2' => '2 km', '5' => '5 km', '10' => '10 km', '25' => '25 km', '50' => '50 km'), Input::old('proximityLevel3'), array('class' => 'form-control')) }}
				
	</div>
	
	<div class="form-group">
		{{ Form::label('location3', 'Name of Location -3') }}
		{{ Form::text('location3', Input::old('location3'), array('class' => 'form-control')) }}
	</div>
	
		<div class="form-group">
		{{ Form::label('proximityLevel4', 'Proximity Level') }}
		{{ Form::select('proximityLevel4', array('0' => 'Select a Level', '1' => '1 km', '2' => '2 km', '5' => '5 km', '10' => '10 km', '25' => '25 km', '50' => '50 km'), Input::old('proximityLevel4'), array('class' => 'form-control')) }}
				
	</div>
	
	<div class="form-group">
		{{ Form::label('location4', 'Name of Location -4') }}
		{{ Form::text('location4', Input::old('location4'), array('class' => 'form-control')) }}
	</div>
	
		<div class="form-group">
		{{ Form::label('proximityLevel5', 'Proximity Level') }}
		{{ Form::select('proximityLevel5', array('0' => 'Select a Level', '1' => '1 km', '2' => '2 km', '5' => '5 km', '10' => '10 km', '25' => '25 km', '50' => '50 km'), Input::old('proximityLevel5'), array('class' => 'form-control')) }}
				
	</div>
	
	<div class="form-group">
		{{ Form::label('location5', 'Name of Location -5') }}
		{{ Form::text('location5', Input::old('location5'), array('class' => 'form-control')) }}
	</div>
	
	
	

	{{ Form::submit('Add the Places of Interest!', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}

@stop