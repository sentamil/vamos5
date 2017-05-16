@extends('includes.vdmEditHeader')
@section('mainContent')


<!-- if there are creation errors, they will show here -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		<div class="panel-heading" align="center">
                   		<h4><font>Edit Vehicle</font></h4>
                	</div>
                	{{ HTML::ul($errors->all()) }}
					{{ Form::model($vehicleId, array('route' => array('vdmVehicles.update', $vehicleId), 'method' => 'PUT')) }}
                	<div class="panel-body">
                		<div class="row">
                			<hr>
                			<div class="col-md-1"></div>
							<div class="col-md-5">
								<div class="form-group">
									{{ Form::label('vehicleId', 'AssetID :')  }}
									{{ Form::text('vehicleId', $vehicleId, array('class' => 'form-control','disabled' => 'disabled')) }}
								</div>
								<div class="form-group">
									{{ Form::label('deviceId', 'Device Id / IMEI No') }}
									<br/>
									{{ Form::text('deviceId', $refData['deviceId'], array('class' => 'form-control','disabled' => 'disabled')) }}

								</div>
								<div class="form-group">
									{{ Form::label('shortName', 'Vehicle Name / Vehicle Id') }}
									{{ Form::text('shortName', $refData['shortName'], array('class' => 'form-control')) }}
								</div>

								<div class="form-group">
									{{ Form::label('deviceModel', 'Device Model') }}
									{{ Form::select('deviceModel',$protocol, $refData['deviceModel'], array('class' => 'form-control')) }}
								</div>

								<div class="form-group">
									{{ Form::label('regNo', 'Vehicle Registration Number') }}
									{{ Form::text('regNo', $refData['regNo'], array('class' => 'form-control')) }}
								</div>

								<div class="form-group">
									{{ Form::label('vehicleType', 'Vehicle Type') }}
									{{ Form::select('vehicleType', array( 'Car' => 'Car', 'Truck' => 'Truck','Bus'=>'Bus', 'Bike' => 'Bike'), $refData['vehicleType'], array('class' => 'form-control')) }}           
								</div>

								<div class="form-group">
									{{ Form::label('overSpeedLimit', 'OverSpeed Limit') }}
									{{ Form::select('overSpeedLimit', array( '10' => '10','20' => '20','30' => '30','40' => '40','50' => '50','60' => '60','70' => '70','80' => '80','90' => '90','100' => '100','110' => '110','120' => '120','130' => '130','140' => '140','150' => '150' ),$refData['overSpeedLimit'], array('class' => 'form-control')) }}
								</div>


								<!-- <div class="form-group">
									{{ Form::label('morningTripStartTime', 'Morning Trip Start Time') }}
									{{ Form::text('morningTripStartTime', $refData['morningTripStartTime'], array('class' => 'form-control')) }}
								</div>

								<div class="form-group">
									{{ Form::label('eveningTripStartTime', 'Evening Trip Start Time') }}
									{{ Form::text('eveningTripStartTime',$refData['eveningTripStartTime'], array('class' => 'form-control'))}}            
								</div> -->
								<div class="form-group">
									{{ Form::label('route', 'Route Name') }}
									{{ Form::select('routeName',$routeName, $refData['routeName'], array('class' => 'form-control')) }}
								</div>
								<div class="form-group">
									{{ Form::label('date', 'Date') }}
									{{ Form::text('date', $refData['date'], array('class' => 'form-control','disabled' => 'disabled')) }}
								</div>
								<!-- <div class="form-group">
									{{ Form::label('expiredPeriod', 'Expired Period') }}
									{{ Form::text('expiredPeriod', $refData['expiredPeriod'], array('class' => 'form-control','disabled' => 'disabled')) }}
								</div> -->


								<div class="form-group">
									{{ Form::label('analog1', 'Analog input 1') }}
									{{ Form::select('analog1', array('no' => 'No','fuel' => 'Fuel','load' => 'Load' ), $refData['analog1'],array('class' => 'form-control')) }}
								</div>

								<div class="form-group">
									{{ Form::label('digital1', 'Digital input 1') }}
									{{ Form::select('digital1', array('no' => 'No','ac' => 'Ac','hire' => 'Hire','door' => 'Door' ), $refData['digital1'],array('class' => 'form-control')) }}
								</div>

								<div class="form-group">
									{{ Form::label('serial1', 'Serial input 1') }}
									{{ Form::select('serial1', array('no' => 'No','temperature' => 'Temperature','rfid' => 'Rfid','camera' => 'Camera' ), $refData['serial1'],array('class' => 'form-control')) }}
								</div>
								<div class="form-group">
									{{ Form::label('digitalout', 'Digital output') }}
									{{ Form::select('digitalout', array('no' => 'No','yes' => 'Yes'), $refData['digitalout'],array('class' => 'form-control')) }}
								</div>
								<!-- <div class="form-group">
									{{ Form::label('fuel', 'Fuel') }}
									{{ Form::select('fuel', array('no' => 'No','yes' => 'Yes' ), $refData['fuel'],array('class' => 'form-control')) }} 
								</div> -->
								
								<div class="form-group">
									{{ Form::label('isRF', 'IsRFID') }}<br>

									{{ Form::select('isRfid', array('yes' => 'Yes','no' => 'No'), isset($refData['isRfid'])?$refData['isRfid']:'no', array('class' => 'form-control')) }} 
								</div>
				
								<div class="form-group">
									{{ Form::label('rfidType', 'Rfid Type') }}<br>

									{{ Form::select('rfidType', array('type1' => 'Type1','type2' => 'Type2','type3' => 'Type3 (234 reverse)'), isset($refData['rfidType'])?$refData['rfidType']:'no', array('class' => 'form-control')) }} 
								</div>
								<div class="form-group">
						{{ Form::label('descriptionStatus', 'Description') }}
						{{ Form::text('descriptionStatus', $refData['descriptionStatus'], array('class' => 'form-control')) }}
						
					</div>

					<!-- <br/> -->
					<div class="form-group">
						{{ Form::label('ipAddress', 'IP Address') }}
						{{ Form::text('ipAddress', $refData['ipAddress'], array('class' => 'form-control')) }}
						
					</div>
					<div class="form-group">
						{{ Form::label('portNo', 'Port Number') }}
						{{ Form::text('portNo', $refData['portNo'], array('class' => 'form-control')) }}
						
					</div>
										</div>

							<div class="col-md-5">

								<div class="form-group">
									{{ Form::label('orgId', 'Org/College Name') }}


									{{ Form::select('orgId', array($orgList), $refData['orgId'], array('class' => 'form-control selectpicker' , 'data-live-search '=> 'true')) }}
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


								<!-- <div class="form-group">
									{{ Form::label('parkingAlert', 'Parking Alert') }}
									{{ Form::select('parkingAlert', array('no' => 'No','yes' => 'Yes'), $refData['parkingAlert'], array('class' => 'form-control')) }}           

								</div>


								<div class="form-group">
									{{ Form::label('sendGeoFenceSMS', 'Send GeoFence SMS') }}
									{{ Form::select('sendGeoFenceSMS', array('no' => 'No','yes' => 'Yes'), $refData['sendGeoFenceSMS'], array('class' => 'form-control')) }}           

								</div> -->
								<div class="form-group">
									{{ Form::label('altShort', 'Alternate Vehicle Name') }}
									{{ Form::text('altShortName',$refData['altShortName'], array('class' => 'form-control')) }}          

								</div>
								<div class="form-group">
									{{ Form::label('paymentType', 'Payment Type') }}
									{{ Form::text('paymentType', $refData['paymentType'], array('class' => 'form-control','disabled' => 'disabled')) }}
								</div>
								<div class="form-group">
									{{ Form::label('analog2', 'Analog input 2') }}
									{{ Form::select('analog2', array('no' => 'No','fuel' => 'Fuel','load' => 'Load' ), $refData['analog2'],array('class' => 'form-control')) }}
								</div>
								<div class="form-group">
									{{ Form::label('digital2', 'Digital input 2') }}
									{{ Form::select('digital2', array('no' => 'No','ac' => 'Ac','hire' => 'Hire','door' => 'Door' ), $refData['digital2'],array('class' => 'form-control')) }}
								</div>
								<div class="form-group">
									{{ Form::label('serial2', 'Serial input 2') }}
									{{ Form::select('serial2', array('no' => 'No','temperature' => 'Temperature','rfid' => 'Rfid','camera' => 'Camera' ), $refData['serial2'],array('class' => 'form-control')) }}
								</div>


								<div class="form-group">
									{{ Form::label('fuelTyp', 'Fuel Type') }}<br>

									{{ Form::select('fuelType', array('digital' => 'Digital','analog' => 'Analog'), isset($refData['fuelType'])?$refData['fuelType']:'Digital', array('class' => 'form-control')) }} 
								</div>
								
							<div class="form-group">
								{{ Form::label('License1', 'Licence') }}
								{{ Form::select('Licence1', array($Licence), $refData['Licence'],array('class' => 'form-control')) }} 
							</div>
							<div class="form-group">
								{{ Form::label('Payment_Mode1', 'Payment Mode') }}
								{{ Form::select('Payment_Mode1', array($Payment_Mode), $refData['Payment_Mode'],array('class' => 'form-control')) }} 
							</div>
							<div class="form-group">
								{{ Form::label('mintem', 'Minimum Temperature') }}
								{{ Form::number('mintemp', $refData['mintemp'],array('class' => 'form-control', 'placeholder'=>'Quantity', 'min'=>'-100')) }} 
							</div>
							<div class="form-group">
								{{ Form::label('maxtem', 'Maximum Temperature') }}
								{{ Form::number('maxtemp', $refData['maxtemp'],array('class' => 'form-control', 'placeholder'=>'Quantity', 'min'=>'-100')) }} 
							</div>
<div class="form-group">
{{ Form::label('vehicleExpiry', 'Vehicle Expire') }}
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
 <script>   
    $(function() {
        // $( "#calendar" ).datepicker();   
         $( "#calendar" ).datepicker({ dateFormat: 'yy-mm-dd' });
    }); 
 </script>
{{ Form::text('vehicleExpiry', $refData['vehicleExpiry'], array( 'id' => 'calendar', 'class' => 'form-control')) }}
<!--<input type="text" name="vehicleExpiry" id="calendar" />-->
</div>
							<br/>
					
								<div class="col-md-5" style="top: 30px; position: relative; left: 40%">
									
										 {{ Form::submit('Update the Vehicle!', array('class' => 'btn btn-primary')) }}
								</div>
								
							</div>

						</div>
						<hr>
                	</div>
                	{{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>


<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
      $(function () {
      $('.selectpicker').selectpicker();
      });

    </script>
<div align="center">@stop</div>