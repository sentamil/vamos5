@extends('includes.vdmheader')
@section('mainContent')
<h1>Geo fence</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmGF')) }}

		
	<div class="form-group">
		{{ Form::label('vehicleId', 'Vehicle Id') }}
		{{ Form::select('vehicleId', $vehicleList,Input::old('vehicleId'),array('class' => 'form-control')) }}
	</div>
		
	<div class="form-group">
		{{ Form::label('periodicalAlert', 'Periodical Alert ') }}
		{{ Form::text('periodicalAlert', Input::old('periodicalAlert'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('geoFenceId1', 'Geo Fence Id - 1') }}
		{{ Form::text('geoFenceId1', Input::old('geoFenceId1'), array('class' => 'form-control')) }}
	</div>
	
	
	<div class="form-group">
		{{ Form::label('gfLocation1', 'Geo Fence Location') }}
		{{ Form::text('gfLocation1', Input::old('gfLocation1'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('proximityLevel1', 'Proximity Level') }}
		{{ Form::select('proximityLevel1', array('0' => 'Select a Level', '10' => '10 m', '100' => '100 m', '1000' => '1 km', '5000' => '5 km', '10000' => '10 km', '25000' => '25 km'), Input::old('proximityLevel1'), array('class' => 'form-control')) }}
				
	</div>
	
	
	<div class="form-group">
		{{ Form::label('gfType1', 'Geo Fence Type') }}
		<br/>
		{{ Form::select('gfType1', array('0' => 'Select a Type', 'trip' => 'Trip', 'exit' => 'On Exit'), Input::old('gfType1'),array('class' => 'form-control')) }}
		
	</div>
	<br/>
	
	<div class="form-group">
		{{ Form::label('geoFenceId2', 'Geo Fence Id - 2') }}
		{{ Form::text('geoFenceId2', Input::old('geoFenceId2'), array('class' => 'form-control')) }}
	</div>
	
	
	<div class="form-group">
		{{ Form::label('gfLocation2', 'Geo Fence Location') }}
		{{ Form::text('gfLocation2', Input::old('gfLocation2'), array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('proximityLevel2', 'Proximity Level') }}
		{{ Form::select('proximityLevel1', array('0' => 'Select a Level', '10' => '10 m', '100' => '100 m', '1000' => '1 km', '5000' => '5 km', '10000' => '10 km', '25000' => '25 km'), Input::old('proximityLevel1'), array('class' => 'form-control')) }}
				
	</div>

	
	<div class="form-group">
		{{ Form::label('gfType2', 'Geo Fence Type') }}
		{{ Form::select('gfType2', array('0' => 'Select a Type', 'trip' => 'Trip', 'exit' => 'On Exit'), Input::old('gfType2'), array('class' => 'form-control')) }}
		
	</div>
	<br/>
	
	<div class="form-group">
		{{ Form::label('geoFenceId3', 'Geo Fence Id - 3') }}
		{{ Form::text('geoFenceId3', Input::old('geoFenceId3'), array('class' => 'form-control')) }}
	</div>
	
	
	<div class="form-group">
		{{ Form::label('gfLocation3', 'Geo Fence Location') }}
		{{ Form::text('gfLocation3', Input::old('gfLocation3'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('proximityLevel3', 'Proximity Level') }}
		{{ Form::select('proximityLevel1', array('0' => 'Select a Level', '10' => '10 m', '100' => '100 m', '1000' => '1 km', '5000' => '5 km', '10000' => '10 km', '25000' => '25 km'), Input::old('proximityLevel1'), array('class' => 'form-control')) }}
						
	</div>
	

	
	<div class="form-group">
		{{ Form::label('gfType3', 'Geo Fence Type') }}
		{{ Form::select('gfType3', array('0' => 'Select a Type', 'trip' => 'Trip', 'exit' => 'On Exit'),  Input::old('gfType3'),array('class' => 'form-control')) }}
		
	</div>
	<br/>
	
	<div class="form-group">
		{{ Form::label('geoFenceId4', 'Geo Fence Id - 4') }}
		{{ Form::text('geoFenceId4', Input::old('geoFenceId4'), array('class' => 'form-control')) }}
	</div>
	
	
	<div class="form-group">
		{{ Form::label('gfLocation4', 'Geo Fence Location') }}
		{{ Form::text('gfLocation4', Input::old('gfLocation4'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('proximityLevel4', 'Proximity Level') }}
		{{ Form::select('proximityLevel1', array('0' => 'Select a Level', '10' => '10 m', '100' => '100 m', '1000' => '1 km', '5000' => '5 km', '10000' => '10 km', '25000' => '25 km'), Input::old('proximityLevel1'), array('class' => 'form-control')) }}
						
	</div>
	
	<div class="form-group">
		{{ Form::label('gfType4', 'Geo Fence Type') }}
		{{ Form::select('gfType4', array('0' => 'Select a Type', 'trip' => 'Trip', 'exit' => 'On Exit'),  Input::old('gfType4'),array('class' => 'form-control')) }}
		
	</div>
	
	<br/>
	
	<div class="form-group">
		{{ Form::label('geoFenceId5', 'Geo Fence Id - 5') }}
		{{ Form::text('geoFenceId5', Input::old('geoFenceId5'), array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('gfLocation5', 'Geo Fence Location') }}
		{{ Form::text('gfLocation5', Input::old('gfLocation5'), array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('proximityLevel5', 'Proximity Level') }}
		{{ Form::select('proximityLevel1', array('0' => 'Select a Level', '10' => '10 m', '100' => '100 m', '1000' => '1 km', '5000' => '5 km', '10000' => '10 km', '25000' => '25 km'), Input::old('proximityLevel1'), array('class' => 'form-control')) }}
				
	</div>
	
	<div class="form-group">
		{{ Form::label('gfType5', 'Geo Fence Type') }}
		{{ Form::select('gfType5', array('0' => 'Select a Type', 'trip' => 'Trip', 'exit' => 'On Exit'),  Input::old('gfType5'), array('class' => 'form-control')) }}
		
	</div>
	
	

	{{ Form::submit('Add Geo Fencing Locations!', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}

@stop