@extends('includes.vdmEditHeader')
@section('mainContent')
<h1>Edit Vehicle</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($vehicleId, array('route' => array('vdmVehicles.update', $vehicleId), 'method' => 'PUT')) }}
<div class="row">
		<div class="col-md-4">
	<div class="form-group">
		{{ Form::label('vehicleId', 'Vehicle Id :')  }}
		 {{ Form::text('vehicleId', $vehicleId, array('class' => 'form-control','disabled' => 'disabled')) }}
	</div>
	<div class="form-group">
		{{ Form::label('deviceId', 'Device Id') }}
		<br/>
		{{ Form::text('deviceId', $refData['deviceId'], array('class' => 'form-control','disabled' => 'disabled')) }}

	</div>
	<div class="form-group">
		{{ Form::label('shortName', 'Short Name') }}
		{{ Form::text('shortName', $refData['shortName'], array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('deviceModel', 'Device Model') }}
		{{ Form::text('deviceModel', $refData['deviceModel'], array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('regNo', 'Vehicle Registration Number') }}
		{{ Form::text('regNo', $refData['regNo'], array('class' => 'form-control')) }}
	</div>
	
	 <div class="form-group">
        {{ Form::label('vehicleType', 'Vehicle Type') }}
      {{ Form::select('vehicleType', array( 'Car' => 'Car', 'Truck' => 'Truck','Bus'=>'Bus'), $refData['vehicleType'], array('class' => 'form-control')) }}            
    </div>
    
    <div class="form-group">
        {{ Form::label('overSpeedLimit', 'OverSpeed Limit') }}
        {{ Form::text('overSpeedLimit', $refData['overSpeedLimit'], array('class' => 'form-control')) }}
    </div>
    
    
	<div class="form-group">
		{{ Form::label('morningTripStartTime', 'Morning Trip Start Time') }}
		{{ Form::text('morningTripStartTime', $refData['morningTripStartTime'], array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
        {{ Form::label('eveningTripStartTime', 'Evening Trip Start Time') }}
        {{ Form::text('eveningTripStartTime',$refData['eveningTripStartTime'], array('class' => 'form-control'))}}             
	</div>
    
     </div>

	<div class="col-md-4">
    
     <div class="form-group">
        {{ Form::label('schoolName', 'School/College Name') }}
        {{ Form::text('schoolName', isset($vehicleRefData->routeNo)?$vehicleRefData->routeNo:' ', array('class' => 'form-control','disabled' => 'disabled')) }}
    </div>
    
     <div class="form-group">
        {{ Form::label('routeList', 'Routes') }}
        {{ Form::select('routeList',array($routeList), $refData['routeNo'], array('class' => 'form-control')) }}
    </div>
    
	    
	<div class="form-group">
		{{ Form::label('oprName', 'Telecom Operator Name') }}    
        {{ Form::select('oprName', array( 'airtel' => 'airtel', 'reliance' => 'reliance','idea' => 'idea'), $refData['oprName'],array('class' => 'form-control')) }}             
	</div>
	
	<div class="form-group">
		{{ Form::label('mobileNo', 'Mobile Number for Alerts') }}
		{{ Form::text('mobileNo', $refData['mobileNo'], array('class' => 'form-control')) }}
	</div>
	
    <div class="form-group">
		{{ Form::label('odoDistance', 'Odometer Reading') }}	
		{{ Form::text('odoDistance', $refData['odoDistance'], array('class' => 'form-control')) }}
 
	</div>
	
		
    <div class="form-group">
		{{ Form::label('driverName', 'Driver Name') }}
		{{ Form::text('driverName', $refData['driverName'], array('class' => 'form-control')) }}
		
	</div>
	
	<div class="form-group">
		{{ Form::label('gpsSimNo', 'GPS Sim Number') }}
		{{ Form::text('gpsSimNo', $refData['gpsSimNo'], array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('email', 'Email for Notification') }}
		{{ Form::text('email', $refData['email'], array('class' => 'form-control')) }}
	</div>
	
	
    <div class="form-group">
        {{ Form::label('sendGeoFenceSMS', 'Send GeoFence SMS') }}
      {{ Form::select('sendGeoFenceSMS', array('no' => 'No','yes' => 'Yes'), $refData['sendGeoFenceSMS'], array('class' => 'form-control')) }}            

    </div>
	
	</div>
	</div>

	{{ Form::submit('Update the Vehicle!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop
