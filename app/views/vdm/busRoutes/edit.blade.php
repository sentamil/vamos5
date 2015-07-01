@extends('includes.vdmheader')
@section('mainContent')
<h1>Edit Place of Interest/GeoFence</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($geoFenceId, array('route' => array('vdmGeoFence.update', $geoFenceId), 'method' => 'PUT')) }}
<div class="row">
      <div class="col-md-4">      
    <div class="form-group">
        {{ Form::label('geoFenceId', 'GeoFence Id :')  }}
                <br/>
        {{ Form::label('geoFenceId' , $geoFenceId) }}
    </div>
   <div class="form-group">
        {{ Form::label('poiName', 'Place of Interest') }}
        {{ Form::text('poiName', $poiName, array('class' => 'form-control')) }}

    </div>
    <div class="form-group">
        {{ Form::label('mobileNos', 'mobile Numbers') }}
        {{ Form::textarea('mobileNos', $mobileNos, array('class' => 'form-control')) }}
    </div>
   <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::text('email', $email, array('class' => 'form-control')) }}

    </div>
    </div>
      <div class="col-md-4">   
     <div class="form-group">
        {{ Form::label('direction', 'Direction') }}
       {{ Form::select('direction', array( 'pickup' => 'pickup', 'drop' => 'drop', 'NA'=>'Not Applicable'), $direction, array('class' => 'form-control')) }}            
       
    </div>
    
    <div class="form-group">
        {{ Form::label('geoLocation', 'geoLocation') }}
        {{ Form::text('geoLocation', $geoLocation, array('class' => 'form-control')) }}

    </div>
    <div class="form-group">
        {{ Form::label('geoAddress', 'geoAddress') }}
        {{ Form::text('geoAddress', $geoAddress, array('class' => 'form-control')) }}

    </div>
     <div class="form-group">
        {{ Form::label('proximityLevel', 'Proximity Level') }}
        {{ Form::select('proximityLevel',array( '10' => '10 m','10' => '20 m','50' => '50 m', '100' => '100 m','200' => '200 m','300' => '300 m',  '1000' => '1 km', '5000' => '5 km', '10000' => '10 km', '25000' => '25 km','50000' => '50 km','100000' => '100 km'), $proximityLevel, array('class' => 'form-control')) }}
             
    </div>
    <div class="form-group">
        {{ Form::label('geoFenceType', 'Geo Fence Type') }}
        {{ Form::select('geoFenceType', array( 'org' => 'org','trip' => 'Trip', 'exit' => 'On Exit'), $geoFenceType,array('class' => 'form-control')) }}
            
    </div>
     <div class="form-group">
        {{ Form::label('message', 'Message') }}
        {{ Form::text('message', $message,array('class' => 'form-control')) }}
            
    </div>
 </div>
 </div>

    {{ Form::submit('Update the GeoFence/POI!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop
