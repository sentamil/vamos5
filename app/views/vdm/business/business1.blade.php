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
						<div class="form-group">
							<h5><font><b>Batch Sale</b></font></h5>
							<!-- <div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-1"><div id="hide1" style="border-radius: -25px; height:0px; margin: 0; width : 0px; padding: 0px; border: 0px">{{ Form::radio('type1', 'new') }}</div></div>
								<div class="col-md-1">New User</div>
								 <div class="col-md-2"></div> 
							</div> -->
							<br>
							<div>
								<table class="col-md-12">
									<tr>
										<td class="col-md-4"><span style=" border-radius: 0px; width : 0px; height:0px; padding: 0px;border: 0px; position: absolute;" id="hide1">{{ Form::radio('type1', 'new') }} </span>&nbsp;&nbsp;&nbsp; New User</td>
										<td class="col-md-4"><span style=" border-radius: 0px; width : 0px; height:0px; padding: 0px;border: 0px; position: absolute" id="show1">{{ Form::radio('type1', 'existing') }} </span>&nbsp;&nbsp;&nbsp; Existing User</td>
										<td class="col-md-4"><div>{{ Form::submit('Submit', array('class' => 'btn btn-sm btn-info')) }}</div></td>
									</tr>
								</table>
								<!-- <div  style=" background-color: green"></div>: New User
								<br>
								<div id="show1" style="height:10px; width : 10px; background-color: green">{{ Form::radio('type1', 'existing') }} </div>: 
								<br>
								 -->
							</div>
							<!-- <div class="row"> -->
								<!-- <div class="col-md-2"></div>
								<div class="col-md-1"><div id="show1" style="border-radius: -25px; height:0px; margin: 0; width : 0px; padding: 0px; border: 0px">{{ Form::radio('type1', 'existing') }}</div></div>
								<div class="col-md-1">Existing User</div> -->
								<!-- <div class="col-md-2"></div> -->
								
								<!-- <div </div> -->
								<!-- <div class="col-md-1" id="p1"></div> -->
								
								<!-- <div class="col-md-2"></div> -->
								
							<!-- </div> -->
							<br>
							<div id="t">
								<br>
								<hr>
								<div class="col-md-2"></div>
								<div class="col-md-2">{{ Form::label('ExistingUser', 'Existing User') }}{{ Form::select('userIdtemp', array($userList),'select', array('id'=>'valSelected','class' => 'selectpicker show-menu-arrow form-control','data-live-search '=> 'true')) }}</div>
								<div class="col-md-2">{{ Form::label('Group', 'Group name') }}{{ Form::select('groupname', array(null),Input::old('groupname'), array('id'=>'groupname','class' => 'selectpicker show-menu-arrow form-control','data-live-search '=> 'true')) }}</div>
								<div class="col-md-2">{{ Form::label('orgId', 'org/College Name') }}{{ Form::select('orgId',  array_merge(['' => 'Please Select'], $orgList), Input::old('orgId'), array('class' => 'selectpicker show-menu-arrow form-control','data-live-search '=> 'true')) }}</div>
							</div>
							<div id="t1">
								<br>
								<hr>
								<div class="col-md-2"></div>
								<div class="col-md-2">{{ Form::text('userId', Input::old('userId'),array('id'=>'userIdtempNew','placeholder'=>'User Name','class' => 'form-control')) }}</div>
								<div class="col-md-2">{{ Form::number('mobileNo', Input::old('mobileNo'), array('placeholder'=>'Mobile No','class' => 'form-control')) }}</div>
								<div class="col-md-2">{{ Form::email('email', Input::old('email'), array('placeholder'=>'Email','class' => 'form-control')) }}</div>
								<div class="col-md-2">{{ Form::text('password', Input::old('password'), array('placeholder'=>'Password','class' => 'form-control','onkeyup' => 'caps(this)')) }}</div>
							</div>
							<span id="error" style="color:red;font-weight:bold"></span>
							<div class="row">
								<div class="col-md-12">
									<hr>
									<table id="example1" class="table table-bordered dataTable">
										<thead>
											<tr>
												<th style="text-align: center;">ID</th>
												<th style="text-align: center;">Choose</th>
												<th style="text-align: center;">Device ID</th>
												<th style="text-align: center;">Device Type</th>
												<th style="text-align: center;">Action</th>
												
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
													
													<!-- <a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/migration/' . $value) }}">Track</a>
													<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/migration/' . $value) }}">History</a> -->
													
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
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script>
function caps(element){
    element.value = element.value.toUpperCase();
  }
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


$('#valSelected').on('change', function() {
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
			  $('#groupname').selectpicker('refresh');
 			});
});


$('#userIdtempNew').on('change', function() {

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
	});
});
});
</script>
@include('includes.js_index')
</body>
</html>
