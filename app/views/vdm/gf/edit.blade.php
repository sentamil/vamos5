@extends('includes.vdmheader')
@section('mainContent')

<h1>Edit GeoFencing</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($vehicleId, array('route' => array('vdmGF.update', $vehicleId), 'method' => 'PUT')) }}

	<div class="form-group">
		{{ Form::label('vehicleId', 'Vehicle Id')  }}
				<br/>
		{{ Form::label('vehicleId' , $vehicleId) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('periodicalAlert', 'Periodical Alert') }}
		{{ Form::text('periodicalAlert', $gfDataArr['periodicalAlert'], array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('geoFenceId1', 'Geo Fence Id - 1') }}
		{{ Form::text('geoFenceId1',  $gfDataArr['geoFenceId1'], array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('gfLocation1', 'Geo Fence Location') }}
		{{ Form::text('gfLocation1',  $gfDataArr['gfLocation1'],array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('proximityLevel1', 'Proximity Level') }}
		{{ Form::select('proximityLevel1',  array('0' => 'Select a Level', '10m' => '10 m', '100m' => '100 m', '1km' => '1 km', '5km' => '5 km', '10km' => '10 km', '25km' => '25 km'),$gfDataArr['proximityLevel1'],array('class' => 'form-control')) }}
		
	</div>
	
	
	<div class="form-group">
		{{ Form::label('gfType1', 'Geo Fence Type') }}
		<br/>
		{{ Form::select('gfType1', array('0' => 'Select a Type', 'trip' => 'Trip', 'exit' => 'On Exit'), $gfDataArr['gfType1'],array('class' => 'form-control')) }}
		
	</div>
	<br/>
	
	<div class="form-group">
		{{ Form::label('geoFenceId2', 'Geo Fence Id - 2') }}
		{{ Form::text('geoFenceId2',  $gfDataArr['geoFenceId2'], array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('gfLocation2', 'Geo Fence Location') }}
		{{ Form::text('gfLocation2', $gfDataArr['gfLocation2'], array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('proximityLevel2', 'Proximity Level') }}
		{{ Form::select('proximityLevel2',  array('0' => 'Select a Level', '10m' => '10 m', '100m' => '100 m', '1km' => '1 km', '5km' => '5 km', '10km' => '10 km', '25km' => '25 km'), $gfDataArr['proximityLevel2'], array('class' => 'form-control')) }}
		
	</div>

	
	<div class="form-group">
		{{ Form::label('gfType2', 'Geo Fence Type') }}
		{{ Form::select('gfType2', array('0' => 'Select a Type', 'trip' => 'Trip', 'exit' => 'On Exit'), $gfDataArr['gfType2'], array('class' => 'form-control')) }}
		
	</div>
	<br/>
	
	<div class="form-group">
		{{ Form::label('geoFenceId3', 'Geo Fence Id - 3') }}
		{{ Form::text('geoFenceId3',  $gfDataArr['geoFenceId3'], array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('gfLocation3', 'Geo Fence Location') }}
		{{ Form::text('gfLocation3', $gfDataArr['gfLocation3'], array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('proximityLevel3', 'Proximity Level') }}
		{{ Form::select('proximityLevel3',  array('0' => 'Select a Level', '10m' => '10 m', '100m' => '100 m', '1km' => '1 km', '5km' => '5 km', '10km' => '10 km', '25km' => '25 km'), $gfDataArr['proximityLevel3'], array('class' => 'form-control')) }}
				
	</div>
	

	
	<div class="form-group">
		{{ Form::label('gfType3', 'Geo Fence Type') }}
		{{ Form::select('gfType3', array('0' => 'Select a Type', 'trip' => 'Trip', 'exit' => 'On Exit'),  $gfDataArr['gfType3'],array('class' => 'form-control')) }}
		
	</div>
	<br/>
	
	
	<div class="form-group">
		{{ Form::label('geoFenceId4', 'Geo Fence Id - 4') }}
		{{ Form::text('geoFenceId4',  $gfDataArr['geoFenceId4'], array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('gfLocation4', 'Geo Fence Location') }}
		{{ Form::text('gfLocation4', $gfDataArr['gfLocation4'], array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('proximityLevel4', 'Proximity Level') }}
		{{ Form::select('proximityLevel4',  array('0' => 'Select a Level', '10m' => '10 m', '100m' => '100 m', '1km' => '1 km', '5km' => '5 km', '10km' => '10 km', '25km' => '25 km'), $gfDataArr['proximityLevel4'], array('class' => 'form-control')) }}
				
	</div>
	
	<div class="form-group">
		{{ Form::label('gfType4', 'Geo Fence Type') }}
		{{ Form::select('gfType4', array('0' => 'Select a Type', 'trip' => 'Trip', 'exit' => 'On Exit'),  $gfDataArr['gfType4'],array('class' => 'form-control')) }}
		
	</div>
	
	<br/>
	
	<div class="form-group">
		{{ Form::label('geoFenceId5', 'Geo Fence Id - 5') }}
		{{ Form::text('geoFenceId5',  $gfDataArr['geoFenceId5'], array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('gfLocation5', 'Geo Fence Location') }}
		{{ Form::text('gfLocation5', $gfDataArr['gfLocation5'], array('class' => 'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('proximityLevel5', 'Proximity Level') }}
		{{ Form::select('proximityLevel5',  array('0' => 'Select a Level', '10m' => '10 m', '100m' => '100 m', '1km' => '1 km', '5km' => '5 km', '10km' => '10 km', '25km' => '25 km'), $gfDataArr['proximityLevel5'], array('class' => 'form-control')) }}
				
	</div>
	
	<div class="form-group">
		{{ Form::label('gfType5', 'Geo Fence Type') }}
		{{ Form::select('gfType5', array('0' => 'Select a Type', 'trip' => 'Trip', 'exit' => 'On Exit'),  $gfDataArr['gfType5'], array('class' => 'form-control')) }}
		
	</div>	
	
	
	{{ Form::submit('Update the GeoFence', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop