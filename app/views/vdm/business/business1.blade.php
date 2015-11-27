@include('includes.header_index')
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
                    Dash Board
                </div>
                <div class="panel-body">
				{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'Business/batchSale')) }}
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6">
                <div class="col-sm-6"><div id="example2_filter" class="dataTables_filter"></div></div></div><div class="row">
                	<div class="col-sm-12">
                	
				
					<br/>
					<div class="form-group">
					 
						 <br/>
					 <br/><br/>
								
								<br/>
								<br/>
								<table>
								<tr><td ></td><td width=20></td><td>Batch Sale</td>
								
								<td></td>
								</tr></table>
								<br>
								
								
								



</p>
<table ><tr><td id="hide1">{{ Form::radio('type1', 'new') }}</td><td width=20></td><td>New</td><td width=20></td><td id="p1">
								<tr><td id="show1">{{ Form::radio('type1', 'existing') }}</td><td width=20></td><td>Existing</td>
								
								<td></td>
								</tr></table>


<table ><tr>

<td id="t">
{{ Form::select('userIdtemp', array($userList), array('class' => 'form-control')) }}

</td>
<td id="t1">
<p>
<div class="row">
                	
							<div class="row">
							<div class="col-md-2">
							{{ Form::label('userId', 'User ID') }}
							</div>
							<div class="col-md-6">
							{{ Form::text('userId', Input::old('userId'), array('class' => 'form-control')) }}
							</div>
							</div>
							</br>
							<div class="row">
							<div class="col-md-2">
							{{ Form::label('mobileNo', 'Mobile Number') }}
							</div>
							<div class="col-md-6">
							{{ Form::text('mobileNo', Input::old('mobileNo'), array('class' => 'form-control')) }}
							</div>
							</div>
							</br>
							<div class="row">
							<div class="col-md-2">
							{{ Form::label('email', 'Email') }}
							</div>
							<div class="col-md-6">
							{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
							</div>
							</div>
							</br>
							<div class="row">
							<div class="col-md-2">
							{{ Form::label('password', 'Password') }}
							</div>
							<div class="col-md-6">
							{{ Form::text('password', Input::old('password'), array('class' =>'form-control')) }}
							</div>
							</div>
	                               
							<hr>
							
						</div>

</p>


</td></tr></table>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
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

</script>











								
							<h4>{{ Form::submit('Submit', array('class' => 'btn btn-sm btn-info')) }}</h4>	
							
							
								{{ Form::text('groupId', Input::old('groupId'), array('class' => 'form-control')) }}
					 
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
							
							<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/create1/'. $devices[$key-1]) }}">Details</a>
								
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