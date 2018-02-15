@extends('includes.vdmEditHeader')
@section('mainContent')


<!-- if there are creation errors, they will show here -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		<div style="background-color: #7A7F99" class="panel-heading" align="center">
                   		<h4 style="background-color:  #b3b3cc" ><font size="6px" color="white" >Edit Vehicle</font></h4>
                	</div>
                	 <font color="red">{{ HTML::ul($errors->all()) }}</font>
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
									{{ Form::select('deviceModel',$protocol, $refData['deviceModel'], array('class' => 'selectpicker show-menu-arrow form-control','data-live-search '=> 'true')) }}
								</div>

								<div class="form-group">
									{{ Form::label('regNo', 'Vehicle Registration Number') }}
									{{ Form::text('regNo', $refData['regNo'], array('class' => 'form-control')) }}
								</div>

								<div class="form-group">
									{{ Form::label('vehicleType', 'Vehicle Type') }}
									{{ Form::select('vehicleType', array( 'Bike' => 'Bike','Car' => 'Car','Bus'=>'Bus','Truck' => 'Truck','Heavy Vehicle'=>'Heavy Vehicle' ), $refData['vehicleType'], array('class' => 'form-control')) }}           
								</div>

								<div class="form-group">
									{{ Form::label('overSpeedLimit', 'OverSpeed Limit') }}
									{{ Form::select('overSpeedLimit', array( '10' => '10','20' => '20','30' => '30','40' => '40','50' => '50','60' => '60','70' => '70','80' => '80','90' => '90','100' => '100','110' => '110','120' => '120','130' => '130','140' => '140','150' => '150' ),$refData['overSpeedLimit'], array('class' => 'form-control')) }}
								</div>


								 <div class="form-group">
									{{ Form::label('morningTripStartTime', 'DCODE') }}
									{{ Form::text('morningTripStartTime', $refData['morningTripStartTime'], array('class' => 'form-control')) }}
								</div>

								<div class="form-group">
									{{ Form::label('eveningTripStartTime', 'TIMEZONE') }}
									{{ Form::select('eveningTripStartTime', array( 'TIMEZONE' => 'TIMEZONE', 'INDIA' => 'INDIA', 'CHINA' => 'CHINA','GMT'=>'GMT'), $refData['eveningTripStartTime'], array('class' => 'form-control'))}}            
								</div> 
								<div class="form-group">
									{{ Form::label('route', 'Route Name') }}
									{{ Form::select('routeName',$routeName, $refData['routeName'], array('class' => 'form-control')) }}
								</div>
								<!-- <div class="form-group">
									{{ Form::label('date', 'Date') }}
									{{ Form::text('date', $refData['date'], array('class' => 'form-control','disabled' => 'disabled')) }}
								</div> -->
								<div class="form-group">
									{{ Form::label('onboardDate', 'Onboard Date') }}
									{{ Form::text('onboardDate', $refData['onboardDate'], array('class' => 'form-control','disabled' => 'disabled')) }}
								</div>
								<!-- <div class="form-group">
									{{ Form::label('expiredPeriod', 'Expired Period') }}
									{{ Form::text('expiredPeriod', $refData['expiredPeriod'], array('class' => 'form-control','disabled' => 'disabled')) }}
								</div> -->


								<div class="form-group">
									{{ Form::label('analog1', 'Analog input 1') }}
									{{ Form::select('analog1', array('no' => 'No','fuel' => 'Fuel','load' => 'Load' ), $refData['analog1'],array('id'=>'analog','class' => 'form-control')) }}
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
									{{ Form::label('isRF', 'Is RFID') }}<br>

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
									{{ Form::text('gpsSimNo', $refData['gpsSimNo'], array('class' => 'form-control', 'maxlength' => 15,'minlength'=>10)) }}
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
{{ Form::label('vehicleExpiry', 'Vehicle Expiration Date') }}
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
 <script>   
    $(function() {
        // $( "#calendar" ).datepicker();   
         $( "#calendar" ).datepicker({ dateFormat: 'yy-mm-dd', minDate: 0 });
    }); 
 </script>
{{ Form::text('vehicleExpiry', $refData['vehicleExpiry'], array( 'id' => 'calendar', 'class' => 'form-control', 'readonly' => 'true')) }}
<!--<input type="text" name="vehicleExpiry" id="calendar" />-->
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

									{{ Form::text('fuelType', 'Digital', array('class' => 'form-control', 'readonly' => 'true')) }}
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
                                {{ Form::label('safetyParking', 'Safety Parking') }}<br>
                                {{ Form::select('safetyParking', array('yes' => 'Yes','no' => 'No'), isset($refData['safetyParking'])?$refData['safetyParking']:'no', array('class' => 'form-control')) }}
                            </div>
							<div class="form-group">
                                {{ Form::label('tankSize', 'Tank Size') }}
								{{ Form::number('tankSize', isset($refData['tankSize'])?$refData['tankSize']:' ',array('id'=>'tanksize','class' => 'form-control', 'placeholder'=>'Quantity', 'min'=>'1','max'=>'5000')) }}
							</div>

							<br/>
					
								<div class="col-md-5" style="top: 30px; position: relative; left: 40%">
									
								{{ Form::submit('Update Vehicle', array('id'=>'sub','class' => 'btn btn-primary')) }}
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
	
	$('#sub').on('click', function() {

	var datas={
		'val':$('#tanksize').val(),
		'val1':$('#analog').val()
		} 
       if (datas.val != '') {
       document.getElementById("analog").value ="fuel";
	}    
	if(datas.val1=='fuel' && datas.val == '')
	{
	//var neww2=datas.val;
	//var neww=datas.val1;
	 alert('Please Enter Tank Size');
	 document.getElementById("tanksize").focus();
	return false;
	}

});

$('#analog').on('change', function() {
 var input={
 	'val':$('#analog').val()
 }
  console.log(input.val);
 if(input.val=='no' || input.val=='load')
 {
 	console.log(input.val);
  
   document.getElementById("tanksize").readOnly = true;	
   document.getElementById("tanksize").value= '';
 }
 else {
 	document.getElementById("tanksize").readOnly = false;	
	 document.getElementById("tanksize").value = ''; 
}

});

    </script>
<div align="center">@stop</div>

