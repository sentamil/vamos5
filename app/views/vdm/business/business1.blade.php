@include('includes.header_index')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4><font><b> Dash Board </b> </font></h4>
					</div>
					<div class="panel-body">
						{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'Business/batchSale')) }}
						<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6">
							<div class="col-sm-6"><div id="example2_filter" class="dataTables_filter"></div></div></div><div class="row">
							<div class="col-sm-12">



								<div class="form-group">
									<h5><font color="green"<b>    
										<table>
											<tr><td ></td><td width=20></td><td>Batch Sale</td>
												
												<td></td>
											</tr></table>
										</b></font></h5>
										
										<br>
										
										



									</p>
									<table ><tr><td id="hide1">{{ Form::radio('type1', 'new') }}</td><td width=20></td><td>New User</td><td width=20></td><td id="p1">
										<tr><td id="show1">{{ Form::radio('type1', 'existing') }}</td><td width=20></td><td>Existing User</td>
											
											<td></td>
										</tr></table>


										<table ><tr>

											<td id="t">
												{{ Form::select('userIdtemp', array($userList),'select', array('id'=>'userIdtemp1')) }}



<br/>
	        				<br/>
	        				
	        							{{ Form::label('Group', 'Group name') }}
	        						
	        						{{ Form::select('groupname', array(null),Input::old('groupname'), array('id'=>'groupname')) }}

	        						{{ Form::label('orgId', 'org/College Name') }}
					{{ Form::select('orgId',  array_merge(['' => 'Please Select'], $orgList), Input::old('orgId'), array('class' => 'form-control')) }} 
											</td>
											<td id="t1">
												<p>

													<div class="row">

														<div class="col-md-3">
															{{ Form::label('userId', 'User ID') }}
														</div>
														<div class="col-md-6">
															{{ Form::text('userId', Input::old('userId'),array('id'=>'userIdtempNew')) }}
														</div>
													</div>
												</br>
												<div class="row">
													<div class="col-md-3">
														{{ Form::label('mobileNo', 'Mobile Number') }}
													</div>
													<div class="col-md-6">
														{{ Form::text('mobileNo', Input::old('mobileNo')) }}
													</div>
												</div>
											</br>
											<div class="row">
												<div class="col-md-3">
													{{ Form::label('email', 'Email') }}
												</div>
												<div class="col-md-6">
													{{ Form::text('email', Input::old('email')) }}
												</div>
											</div>
										</br>
										<div class="row">
											<div class="col-md-3">
												{{ Form::label('password', 'Password') }}
											</div>
											<div class="col-md-6">
												{{ Form::text('password', Input::old('password')) }}
											</div>
										</div>

										
										
									</div>

								</p>


							</td></tr></table>
							
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>


<script>
								$(document).ready(function(){

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
								});

$('#userIdtemp1').on('change', function() {
console.log('test vamos');
var data = {
'id': $(this).val()

};
console.log('ahan'+data);
$.post('{{ route("ajax.getGroup") }}', data, function(data, textStatus, xhr) {

 			 $('#groupname').empty();
 			 //onsole.log('ahan1'+data.groups);
 			 $('#error').text(data.error);
 			 if(data.error!=' ')
				{
					alert(data.error);
				}
			$.each(data.groups, function(key, value) {   
			     $('#groupname')
			         .append($("<option></option>")
			         .attr("value",key)
			         .text(value)); 
			});


});
});


$('#userIdtempNew').on('change', function() {
//alert("hai");
var data = {
'id': $(this).val()

};
console.log('ahan'+data);
$.post('{{ route("ajax.checkUser") }}', data, function(data, textStatus, xhr) {

 			 $('#error').text(data.error);
 			 if(data.error!=' ')
				{
					alert(data.error);
				}
 			 //onsole.log('ahan1'+data.groups);
			


});
//alert("The text has been changeded.");
});




							</script>










<span id="error" style="color:red;font-weight:bold"></span> 
							
							<h4>{{ Form::submit('Submit', array('class' => 'btn btn-sm btn-info')) }}</h4>  
							
							
							<!--        {{ Form::text('groupId', Input::old('groupId'), array('class' => 'form-control')) }} -->
							
							<table id="example1" class="table table-bordered dataTable">
								<thead>
									<tr>
										<th style="text-align: center;">ID</th>
										<th style="text-align: center;">Choose</th>
										<th style="text-align: center;">Device ID</th>
										<th style="text-align: center;">Device Type</th>
										<th style="text-align: center;">Operate</th>
										
									</tr>
								</thead>
								<tbody>
									@if(isset($devices))
									@foreach($devices as $key => $value)
									<tr style="text-align: center;">
										<td>{{ ++$key }}</td>
										<td>{{ Form::checkbox('vehicleList[]', $devices[$key-1].','.$devicestypes[$key-1], null, ['class' => 'field']) }}</td>
										
										<td>{{ array_get($devices, $key-1)}}</td>
										<td>{{ array_get($devicestypes, $key-1)}}</td>
										<td>
											
											<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/create/'. $devices[$key-1]) }}">Details</a>
											
											<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/migration/' . $value) }}">Track</a>
											<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/migration/' . $value) }}">History</a>
											
										</td>

									</tr>
									@endforeach
									@endif

								</tbody>
							</table>
							

						</div>


					</div>



				</div>
			</div>
		</div>


	</div>
</div>
</div>
@include('includes.js_index')
</body>
</html>
