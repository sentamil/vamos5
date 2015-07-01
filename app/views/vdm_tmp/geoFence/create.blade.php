@extends('includes.vdmheader')
@section('mainContent')
<h1>Add Place of Interest/GeoFence</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmGeoFence')) }}
<div class="row">
    <div class="col-md-4">   
    <div class="form-group">
        {{ Form::label('vehicleId', 'Vehicle Id') }}
        {{ Form::select('vehicleId', $vehicleList,Input::old('vehicleId'),array('class' => 'form-control')) }}
    </div>   
     
       
    <div class="form-group">
        {{ Form::label('geoFenceId', 'GeoFence Id :')  }}
        {{ Form::text('geoFenceId', Input::old('geoFenceId'), array('class' => 'form-control')) }}

    </div>
   <div class="form-group">
        {{ Form::label('poiName', 'Place of Interest') }}
        {{ Form::text('poiName', Input::old('[poiName'), array('class' => 'form-control')) }}

    </div>
    <div class="form-group">
        {{ Form::label('mobileNos', 'mobile Numbers') }}
        {{ Form::textarea('mobileNos', Input::old('mobileNos'), array('class' => 'form-control')) }}
    </div>
    
    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
    </div>
    
    
    </div>
    <div class="col-md-4">
    
    <div class="form-group">
        {{ Form::label('direction', 'Direction') }}
       {{ Form::select('direction', array('pickup' => 'pickup', 'drop' => 'drop', 'NA'=>'Not Applicable'), Input::old('direction'), array('class' => 'form-control')) }}             


    </div>
    <div class="form-group">
        {{ Form::label('geoLocation', 'Geo Location') }}
        {{ Form::text('geoLocation', Input::old('geoLocation'), array('class' => 'form-control')) }}

    </div>
    <div class="form-group">
        {{ Form::label('geoAddress', 'Geo Address') }}
        {{ Form::text('geoAddress', Input::old('geoAddress'), array('class' => 'form-control')) }}

    </div>
    <div class="form-group">
        {{ Form::label('proximityLevel', 'Proximity Level') }}
        {{ Form::select('proximityLevel', array( '10' => '10 m','20' => '20 m','50' => '50 m', '100' => '100 m','200' => '200 m','300' => '300 m',  '1000' => '1 km', '5000' => '5 km', '10000' => '10 km', '25000' => '25 km','50000' => '50 km','100000' => '100 km'), Input::old('proximityLevel'), array('class' => 'form-control')) }}             
    </div>
    <div class="form-group">
        {{ Form::label('geoFenceType', 'Geo Fence Type') }}
        <br/>
        {{ Form::select('geoFenceType', array('school' => 'School', 'trip' => 'Trip', 'exit' => 'On Exit'), Input::old('geoFenceType'),array('class' => 'form-control')) }}
        
    </div>
    <div class="form-group">
        {{ Form::label('message', 'Message') }}
        {{ Form::text('message', Input::old('message'), array('class' => 'form-control')) }}
            
    </div>
 </div>
  </div>

    {{ Form::submit('Create the GeoFence/POI!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop
