@extends('includes.vdmEditHeader')
@section('mainContent')
<h1><font color="blue">Edit Vehicle</font></h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($vehicleId, array('route' => array('vdmVehicles.update', $vehicleId), 'method' => 'PUT')) }}
<div class="row">
		<div class="col-md-4">
	<div class="form-group">
		{{ Form::label('vehicleId', 'AssetID :')  }}
		 {{ Form::text('vehicleId', $vehicleId, array('class' => 'form-control','disabled' => 'disabled')) }}
	</div>
	<div class="form-group">
		{{ Form::label('deviceId', 'Device Id') }}
		<br/>
		{{ Form::text('deviceId', $refData['deviceId'], array('class' => 'form-control','disabled' => 'disabled')) }}

	</div>
	<div class="form-group">
		{{ Form::label('shortName', 'Vehicle Name') }}
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
    <div class="form-group">
		{{ Form::label('date', 'Date') }}
		{{ Form::text('date', $refData['date'], array('class' => 'form-control','disabled' => 'disabled')) }}
	</div>
	<div class="form-group">
		{{ Form::label('expiredPeriod', 'Expired Period') }}
		{{ Form::text('expiredPeriod', $refData['expiredPeriod'], array('class' => 'form-control','disabled' => 'disabled')) }}
	</div>
	<div>
	{{ Form::label('fuel', 'Fuel') }}
						 {{ Form::select('fuel', array('no' => 'No','yes' => 'Yes' ), $refData['fuel'],array('class' => 'form-control')) }}  
	</div>
	<br/>
     </div>

	<div class="col-md-4">
    
     <div class="form-group">
        {{ Form::label('orgId', 'Org/College Name') }}
        
		
		{{ Form::select('orgId', array($orgList), $refData['orgId'], array('class' => 'form-control')) }} 
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
        {{ Form::label('parkingAlert', 'Parking Alert') }}
      {{ Form::select('parkingAlert', array('no' => 'No','yes' => 'Yes'), $refData['parkingAlert'], array('class' => 'form-control')) }}            

    </div>
	
	
    <div class="form-group">
        {{ Form::label('sendGeoFenceSMS', 'Send GeoFence SMS') }}
      {{ Form::select('sendGeoFenceSMS', array('no' => 'No','yes' => 'Yes'), $refData['sendGeoFenceSMS'], array('class' => 'form-control')) }}            

    </div>
	<div class="form-group">
        {{ Form::label('altShort', 'Alternate Vehicle Name') }}
      {{ Form::text('altShortName',$refData['altShortName'], array('class' => 'form-control')) }}           

    </div>
	<div class="form-group">
		{{ Form::label('paymentType', 'Payment Type') }}
		{{ Form::text('paymentType', $refData['paymentType'], array('class' => 'form-control','disabled' => 'disabled')) }}
	</div>
	<div class="form-group">
        {{ Form::label('fuelTyp', 'Fuel Type') }}<br>
     
{{ Form::select('fuelType', array('digital' => 'Digital','analog' => 'Analog'), isset($refData['fuelType'])?$refData['fuelType']:'Digital', array('class' => 'form-control')) }}  
    </div>
	
	</div>
	</div>

	{{ Form::submit('Update the Vehicle!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop
