@include('includes.header_index')
@include('includes.js_index')

<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
               		 <div class="panel-heading">
                  		<h4><b><font> Remove Devices</font></b></h4>
                	</div>
               		 <div class="panel-body">
						{{ Form::open(array('url' => 'Remove/removedevices')) }}
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
							
							<div class="col-md-1"></div>
							<table class="col-md-12">
								<tr>
									<td class="col-md-4"><span style=" border-radius: 0px; width : 0px; height:0px; padding: 0px;border: 0px; position: absolute;" id="hide">{{ Form::radio('type', 'Device') }} </span>&nbsp;&nbsp;&nbsp; Device Id</td>
									<td class="col-md-4"><span style=" border-radius: 0px; width : 0px; height:0px; padding: 0px;border: 0px; position: absolute" id="show">{{ Form::radio('type', 'Vehicle') }} </span>&nbsp;&nbsp;&nbsp; Vehicle Id</td>
									<td class="col-md-4"><div>{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</div></td>
								</tr>
							</table>
						</div>
											
						<br>
						<div class="row" id="p1">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<table class="table table-bordered dataTable" style="z-index: -10px; position: relative" >
									<thead>
										<tr>
											<th style="text-align: center;">No</th>
											<th style="text-align: center;">Device ID</th>
																				
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
											$.post('{{ route("ajax.checkDevice1") }}', data, function(data, textStatus, xhr) {
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

				   							});													

											</script>	
										
										<tr style="text-align: center;">
											<td>{{ $i }}</td>
											<td >{{ Form::text('deviceid'.$i, Input::old('deviceid'), array('id' => 'deviceid'.$i, 'class' => 'form-control')) }}</td>
																					
										</tr>
										
										@endfor
									</tbody>
								</table>

							</div>
						</div>	
                       
						<div class="row" id="p">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<table class="table table-bordered dataTable" style="z-index: -10px; position: relative" >
									<thead>
										<tr>
											<th style="text-align: center;">No</th>
											<th style="text-align: center;">Vehicle ID</th>
																				
										</tr>
									</thead>
									<tbody>
										@for($i=1;$i<=$numberofdevice;$i++)
										<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>


											<script>


											$(document).ready(function() {
											$("#td{{$i}}").hide();
										    $('#vehicleId{{$i}}').on('change', function() {
											$('#error').text('');
											var data = {
											'id': $(this).val()

											};
											console.log('ahan'+data);
											$.post('{{ route("ajax.checkvehicle1") }}', data, function(data, textStatus, xhr) {
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

											
											});				

											
											</script>	
										<tr style="text-align: center;">
											<td>{{ $i }}</td>
											<td>{{ Form::text('vehicleId'.$i, Input::old('vehicleId'), array('id' => 'vehicleId'.$i, 'class' => 'form-control')) }}</td>
											
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
	//$("#t").hide();
	//$("#t1").hide();
	$('#hide').attr('disabled', true);
});
$("#show").click(function(){
	$("#p").show();
	$("#p1").hide();
	$('#show').attr('disabled', true);
});

    		$("#p").hide();
    		$("#p1").hide();
    		//$("#t").hide();
    		//$("#t1").hide();

</script>