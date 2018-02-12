<!-- Main Wrapper -->
<!-- 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script> -->

<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
               		 <div class="panel-heading">
                  		<h4><b><font> Add Device</font></b></h4>
                	</div>
               		 <div class="panel-body">
						{{ Form::open(array('url' => 'Business/adddevice')) }}
						{{ HTML::ul($errors->all()) }}
						<span id="error" style="color:red;font-weight:bold"></span> 
						<div class="col-md-12">
							{{ Form::hidden('numberofdevice1', $numberofdevice, array('class' => 'form-control')) }}
							{{ Form::hidden('availableLincence', $availableLincence, array('class' => 'form-control')) }}
						</div>
						<div class="row">
							<div class="col-md-12"><h5><font>{{ Form::label('Business','BUSINESS :') }}</font></h5></div>
						</div>
						<br>
						<div class="row">	
							<!-- <div class="col-md-2"></div>
							<div class="col-md-1"><div id="hide" style="border-radius: -25px; height:0px; margin: 0; width : 0px; padding: 0px; border: 0px">{{ Form::radio('type', 'Move') }}</div></div>
							<div class="col-md-1">Batch Move</div> -->
							
							<table class="col-md-12">
								<tr>
									<td class="col-md-4"><span style=" border-radius: 0px; width : 0px; height:0px; padding: 0px;border: 0px; position: absolute;" id="hide">{{ Form::radio('type', 'Move') }} </span>&nbsp;&nbsp;&nbsp; Batch Move</td>
									<td class="col-md-4"><span style=" border-radius: 0px; width : 0px; height:0px; padding: 0px;border: 0px; position: absolute" id="show">{{ Form::radio('type', 'Sale') }} </span>&nbsp;&nbsp;&nbsp; Batch Sale</td>
									<td class="col-md-4"><div>{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</div></td>
								</tr>
							</table>
						</div>
						<br>
						<div class="col-md-12">
							<!-- <div class="col-md-2"></div> -->
							<!-- <div class="col-md-1"><div id="show" style="border-radius: -25px; height:0px; margin: 0; width : 0px; padding: 0px; border: 0px">{{ Form::radio('type', 'Sale') }}</div></div>
							<div class="col-md-2">Batch Sale</div> -->
							<div class="col-md-2"id="p1">{{ Form::select('dealerId', array($dealerId), Input::old('	'), array('class' => 'selectpicker show-menu-arrow','data-live-search '=> 'true', 'data-toggle' => 'dropdown')) }}</div>
						<!-- 	<div class="col-md-2"></div>
							<div class="col-md-2">{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</div> -->
						</div>
						<hr>
						
						<div class="row">
							<div class="col-md-2"></div>
							<table class="col-md-8" id="p">
								<tr>
									<td class="col-md-4"><span id="hide1"  style=" border-radius: 0px; width : 0px; height:0px; padding: 0px;border: 0px; position: absolute;" id="hide">{{ Form::radio('type1', 'new') }} </span>&nbsp;&nbsp;&nbsp; New User</td>
									<td class="col-md-4"><span id="show1" style=" border-radius: 0px; width : 0px; height:0px; padding: 0px;border: 0px; position: absolute" id="show">{{ Form::radio('type1', 'existing') }} </span>&nbsp;&nbsp;&nbsp; Existing User</td>
									<!-- <td class="col-md-4"><div>{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</div></td> -->
								</tr>
							</table>
							<!-- <div id="p" class="col-md-8">
								<br>
								<div class="col-md-2"><span id="hide1" style="border-radius: 0px; width : 0px; height:0px; padding: 0px;border: 0px; position: absolute;">{{ Form::radio('type1', 'new') }}</span></div>
								<div class="col-md-2">New User</div>
								<div class="col-md-1" id="p1"></div>
								<div class="col-md-2" ><div id="show1" style="border-radius: 0px; height:0px; margin: 0; width : 0px; padding: 0px; border: 0px; background-color: green">{{ Form::radio('type1', 'existing') }}</div></div>
								<div class="col-md-2">Existing User</div>
							</div> -->

						</div>
						
						<div class="col-md-12" id="t">
							<br>
							<hr>
							<div class="col-md-2"></div>
							<div class="col-md-2">{{ Form::label('ExistingUser', 'Existing User') }}{{ Form::select('userIdtemp', array($userList),'select', array('id'=>'userIdtemp1','class' => 'selectpicker show-menu-arrow form-control', 'data-live-search '=> 'true')) }}</div>
							<div class="col-md-2">{{ Form::label('Group', 'Group name') }}{{ Form::select('groupname', array(null),Input::old('groupname'), array('id'=>'groupname','class' => 'selectpicker show-menu-arrow form-control', 'data-live-search '=> 'true')) }}</div>
							<div class="col-md-2">{{ Form::label('orgId', 'org/College Name') }}{{ Form::select('orgId',  array($orgList), Input::old('orgId'), array('class' => 'selectpicker show-menu-arrow form-control', 'data-live-search '=> 'true')) }} </div>
							
						</div>
						
						<script>
                         function caps(element){
                          element.value = element.value.toUpperCase();
                         }                         
                         </script>
						<div class="row" Id="t1">
							<br>
							<hr>
							<div class="col-md-2"></div>
							<div class="col-md-2">{{ Form::text('userId', Input::old('userId'), array('id'=>'userIdtempNew','class' => 'form-control','onkeyup' => 'caps(this)','placeholder'=>'User Name')) }}</div>
							<div class="col-md-2">{{ Form::text('mobileNoUser', Input::old('mobileNoUser'),array('class' => 'form-control','placeholder'=>'Mobile Number')) }}</div>
							<div class="col-md-2">{{ Form::Email('emailUser', Input::old('emailUser'),array('class' => 'form-control','placeholder'=>'Email')) }}</div>
							<div class="col-md-2">{{ Form::text('password', Input::old('password'),array('class' => 'form-control','placeholder'=>'Password'))}}</div>

						</div>
						<br>
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<table class="table table-bordered dataTable" style="z-index: -10px; position: relative">
									<thead>
										<tr>
											<th style="text-align: center;">No</th>
											<th style="text-align: center;">Device ID</th>
											<th style="text-align: center;">Device Type</th>
											<th style="text-align: center;">Vehicle Id</th>
											<th style="text-align: center;">Action</th>
										</tr>
									</thead>
									<tbody>
										@for($i=1;$i<=$numberofdevice;$i++)
										<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>


											<script>


											$(document).ready(function() {
											$("#td{{$i}}").hide();
											$('#deviceid{{$i}}').on('change', function() {
											console.log('ahan1');
											var data = {
											'id': $(this).val()

											};
											console.log('ahan'+data);
											$.post('{{ route("ajax.checkDevice") }}', data, function(data, textStatus, xhr) {
											/*optional stuff to do after success */
											// console.log('ahan ram'+data.rfidlist);
											$('#error').text(data.error);
											if(data.error!=' ')
											{
												alert(data.error);
											}
											});
											//alert("The text has been changeded.");
											});



											$('#userIdtemp1').on('change', function() {
											console.log('test vamos');
											var data = {
											'id': $(this).val()

											};
											console.log('ahan'+data);
											$.post('{{ route("ajax.getGroup") }}', data, function(data, textStatus, xhr) {

											 			 $('#groupname').empty();
											 			 $('#error').text(data.error);
											 			 if(data.error!=' ')
														{
															alert(data.error);
														}
														 			 //onsole.log('ahan1'+data.groups);
														$.each(data.groups, function(key, value) {   
														     $('#groupname')
														         .append($("<option></option>")
														         .attr("value",key)
														         .text(value)); 
														});
													$('#groupname').selectpicker('refresh');
											});
											//alert("The text has been changeded.");
											});


											$('#userIdtempNew').on('change', function() {
											//alert("hai");
											$('#error').text('');
											var data = {
											'id': $(this).val()

											};
											console.log('ahan'+data);
											$.post('{{ route("ajax.checkUser") }}', data, function(data, textStatus, xhr) {

											 			 $('#error').text(data.error);
											 			 if(data.error!=' ')
															{
																// alert(data.error);
															}
											 			 //onsole.log('ahan1'+data.groups);
														


											});
											//alert("The text has been changeded.");
											});





											$('#vehicleId{{$i}}').on('change', function() {
											$('#error').text('');
											var data = {
											'id': $(this).val()

											};
											console.log('ahan'+data);
											$.post('{{ route("ajax.checkvehicle") }}', data, function(data, textStatus, xhr) {
											/*optional stuff to do after success */
											// console.log('ahan ram'+data.rfidlist);
											$('#error').text(data.error);
											if(data.error!=' ')
											{
												// alert(data.error);
											}

											});
											//alert("The text has been changeded.");
											});

											$("#refData{{$i}}").click(function(){
											$("#td{{$i}}").toggle(500);
											});



													

											}


											);
											function caps(element){
												element.value = element.value.toUpperCase();
											}

											</script>	
										<tr style="text-align: center;">
											<td>{{ $i }}</td>
											<td >{{ Form::text('deviceid'.$i, Input::old('deviceid'), array('id' => 'deviceid'.$i,'required', 'class' => 'form-control')) }}</td>
											<td>{{ Form::select('deviceidtype' .$i, $protocol, Input::old('deviceidtype'), array('class' => 'form-control')) }}</td>
											<td>{{ Form::text('vehicleId'.$i, Input::old('vehicleId'), array('id' => 'vehicleId'.$i, 'class' => 'form-control','onkeyup' => 'caps(this)')) }}</td>
											<td><a id="refData{{$i}}" class="btn btn-sm btn-success" >Details</a></td>
										</tr>
										<tr>
											<td id="td{{$i}}" colspan="6">
												<table>
													<tr style="height: 50px">
														<td class="col-md-2">{{ Form::text('shortName'.$i, Input::old('shortName'), array('class' => 'form-control','placeholder'=>'Vehicle Name')) }} </td>
														<td class="col-md-2">{{ Form::text('regNo'.$i, Input::old('regNo'), array('class' => 'form-control','placeholder'=>'Reg No')) }}</td>
														<td class="col-md-2">{{ Form::select('vehicleType'.$i, array( 'Car'=>'Vehicle Type','Car' => 'Car', 'Truck' => 'Truck','Bus' => 'Bus', 'Bike' => 'Bike'), Input::old('vehicleType'), array('class' => 'form-control')) }}</td>
														<td class="col-md-2">{{ Form::select('oprName'.$i, array('airtel'=>'Operator', 'airtel' => 'airtel', 'reliance' => 'reliance','idea' => 'idea'), Input::old('oprName'), array('class' => 'form-control')) }}</td>
														<td class="col-md-2">{{ Form::select('fuel'.$i,  array( 'no' => 'Fuel No','yes' => 'Fuel Yes' ), Input::old('fuel'), array('class' => 'form-control')) }}</td>
														<td class="col-md-2">{{ Form::text('altShortName'.$i, Input::old('altShortName'), array('class' => 'form-control','placeholder'=>'Alternate Short Name')) }}</td>

													</tr>
													<tr style="height: 50px">
														<td class="col-md-2">{{ Form::text('eveningTripStartTime'.$i, Input::old('eveningTripStartTime'), array('class' => 'form-control','placeholder'=>'Evening Trip Start Time')) }}</td>
														<td class="col-md-2">{{ Form::select('overSpeedLimit'.$i,  array( '60'=>'Speed Limit','10' => '10','20' => '20','30' => '30','40' => '40','50' => '50','60' => '60','70' => '70','80' => '80','90' => '90','100' => '100','110' => '110','120' => '120','130' => '130','140' => '140','150' => '150' ), Input::old('overSpeedLimit'), array('class' => 'form-control')) }} </td>
														<td class="col-md-2">{{ Form::text('mobileNo'.$i, Input::old('mobileNo'), array('class' => 'form-control','placeholder'=>'Alert Mobile No')) }}</td>
														<td class="col-md-2">{{ Form::text('odoDistance'.$i, Input::old('odoDistance'), array('class' => 'form-control','placeholder'=>'Odometer')) }}</td>
														<td class="col-md-2">{{ Form::text('driverName'.$i, Input::old('driverName'), array('class' => 'form-control','placeholder'=>'Driver Name')) }}</td>
														<td class="col-md-2">{{ Form::text('gpsSimNo'.$i, Input::old('gpsSimNo'), array('class' => 'form-control','placeholder'=>'Sim No','maxlength' => 15,'minlength'=>10)) }}</td>
													</tr>
													<tr style="height: 50px">	
														<td class="col-md-2">{{ Form::text('email'.$i, Input::old('email'), array('class' => 'form-control','placeholder'=>'Alert Email')) }}</td>
														</td>
														<td class="col-md-2">{{ Form::select('isRfid'.$i,  array( 'no' => 'Rfid No','yes' => 'Rfid Yes' ), Input::old('isRfid'), array('class' => 'form-control')) }}</td>
														<td class="col-md-2">{{ Form::select('Licence'.$i,  array($Licence), 'Basic', array('class' => 'form-control')) }}</td>
														<td class="col-md-2">{{ Form::select('Payment_Mode'.$i,  array($Payment_Mode), 'Yearly', array('class' => 'form-control')) }}</td>
														<td class="col-md-2">{{ Form::text('descr'.$i, Input::old('descr'), array('class' => 'form-control','placeholder'=>'Description')) }}</td>
														<td class="col-md-2">
                                                          <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
                                                          <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
                                                        <script>   
                                                            $(function() {
                                                             $( "#calendar" ).datepicker({ dateFormat: 'yy-mm-dd' });
                                                               }); 
                                                        </script>
                                                           <input type="text" name="vehicleExpiry" placeholder="vehicleExpiry" id="calendar" />
                                                     </td>
													</tr>
												</table>							
											</td>
										</tr>
										@endfor
									</tbody>
								</table>
							</div>
						</div>
						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
 // $(function() {
 //        $("#userIdtemp1").customselect();
 //      });

$("#hide").click(function(){
	$("#p").hide();
	$("#p1").show();
	$("#t").hide();
	$("#t1").hide();
	$('#hide').attr('disabled', true);
});
$("#show").click(function(){
	$("#p").show();
	$("#p1").hide();
	$('#show').attr('disabled', true);
});
$("#hide1").click(function(){
	$("#t").hide();
	$("#t1").show();
	$('#hide1').attr('disabled', true);
});
$("#show1").click(function(){
	$("#t").show();
	$("#t1").hide();
	$('#show1').attr('disabled', true);
});

    		$("#p").hide();
    		$("#p1").hide();
    		$("#t").hide();
    		$("#t1").hide();

</script>
