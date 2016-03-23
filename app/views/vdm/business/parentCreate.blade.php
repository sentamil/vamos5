<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
       			 
               		
                	
</div>

</div>
</div>





<!-- ahan index
 -->




<div class="hpanel">

<div class="panel-heading">
<h4><font color="blue"><b>Add Device </b></font><h4> 
</div>
<div class="panel-body">
<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
<div class="row">
<div class="col-sm-12">

{{ Form::open(array('url' => 'Business/adddevice')) }}
<h6><b><font color="red"> {{ HTML::ul($errors->all()) }}</font></b></h6>
<div class="row">
<div class="col-md-12">
<div class="col-md-6">
<div class="row">

{{ Form::hidden('numberofdevice1', $numberofdevice, array('class' => 'form-control')) }}
{{ Form::hidden('availableLincence', $availableLincence, array('class' => 'form-control')) }}

<h5><font color="green">{{ Form::label('Business','BUSINESS :') }}</font></h5>
	        <font color="blue">
	       
	        	<table><tr><td id="hide">{{ Form::radio('type', 'Move') }}</td><td width=20></td><td>Batch Move</td><td width=20></td><td id="p1">{{ Form::select('dealerId', array($dealerId), array('class' => 'form-control')) }}</td></tr>
	        		<tr><td id="show">{{ Form::radio('type', 'Sale') }}</td><td width=20></td><td>Batch Sale</td>

	        			<td></td>
	        		</tr></table>
	        		<br>
	        	</p>
	        	<table id="p"><tr><td id="hide1">{{ Form::radio('type1', 'new') }}</td><td width=20></td><td>New User</td><td width=20></td><td id="p1">
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
{{ Form::select('orgId',  array($orgList), Input::old('orgId'), array('class' => 'form-control')) }} 


	        			<td id="t1">
	        				<p>
	        					<div class="row">

	        						<div class="row">
	        							<div class="col-md-3">
	        								{{ Form::label('userId', 'User ID') }}
	        							</div>
	        							<div class="col-md-9">
	        								{{ Form::text('userId', Input::old('userId'), array('id'=>'userIdtempNew')) }}
	        							</div>
	        						</div>
	        					</br>
	        					<div class="row">
	        						<div class="col-md-3">
	        							{{ Form::label('mobileNo', 'Mobile Number') }}
	        						</div>
	        						<div class="col-md-9">
	        							{{ Form::text('mobileNo', Input::old('mobileNo')) }}
	        						</div>
	        					</div>
	        				</br>
	        				<div class="row">
	        					<div class="col-md-3">
	        						{{ Form::label('email', 'Email') }}
	        					</div>
	        					<div class="col-md-9">
	        						{{ Form::text('email', Input::old('email')) }}
	        					</div>
	        				</div>
	        			</br>
	        			<div class="row">
	        				<div class="col-md-3">
	        					{{ Form::label('password', 'Password') }}
	        				</div>
	        				<div class="col-md-9">
	        					{{ Form::text('password', Input::old('password')) }}
	        				</div>
	        			</div>



	        		</div>

	        	</p>
	        </td></tr></table>       




<span id="error" style="color:red;font-weight:bold"></span> 




<table class="table table-bordered dataTable">
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
<tr style="text-align: center;">
<td>{{ $i }}

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


});
//alert("The text has been changeded.");
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





$('#vehicleId{{$i}}').on('change', function() {
console.log('ahan1');
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
	alert(data.error);
}

});
//alert("The text has been changeded.");
});

$("#refData{{$i}}").click(function(){
$("#td{{$i}}").toggle(500);
});



		

}


);

</script>	





</td>
<td >{{ Form::text('deviceid'.$i, Input::old('deviceid'), array('id' => 'deviceid'.$i,'required')) }}</td>
<td>{{ Form::select('deviceidtype' .$i, array( 'GT06N' => 'GT06N (9964)','GT300' => 'GT300 (9961)','TK103' => 'TK103 (9968)', 'TK99' => 'TK99 (9978)', 'FM1202' => 'FM1202 (9975)','FM1120' => 'FM1120 (9975)', 'TR02' => 'TR02 (9965)', 'GT03A' => 'GT03A (9969)', 'VTRACK2' => 'VTRACK2 (9964)','ET01'=>'ET01 (9971)','ET02'=>'ET02 (9962)', 'ET03'=>'ET03 (9974)'), Input::old('deviceidtype'), array('class' => 'form-control')) }}</td>
<td>{{ Form::text('vehicleId'.$i, Input::old('vehicleId'), array('id' => 'vehicleId'.$i)) }}</td>
<td>
<a id="refData{{$i}}" class="btn btn-sm btn-success" >Details</a>
</td>
</tr>


<tr >
<td id="td{{$i}}" colspan="8"><div >
<table >
<tr>
<td>{{ Form::label('shortName', 'Vehicle Name') }}
<br/>
			
				{{ Form::text('shortName'.$i, Input::old('shortName'), array('class' => 'form-control')) }} </td>
<td>

				{{ Form::label('regNo'.$i, 'Vehicle Reg Number') }}
			<br/>
				{{ Form::text('regNo'.$i, Input::old('regNo'), array('class' => 'form-control')) }}
</td>
<td>
{{ Form::label('vehicleType'.$i, 'Vehicle Type') }}
			<br/>
				{{ Form::select('vehicleType'.$i, array( 'Car' => 'Car', 'Truck' => 'Truck','Bus' => 'Bus'), Input::old('vehicleType'), array('class' => 'form-control')) }}     
</td>

<td>
{{ Form::label('oprName'.$i, 'Telecom Operator Name') }}
			<br/>
				{{ Form::select('oprName'.$i, array( 'airtel' => 'airtel', 'reliance' => 'reliance','idea' => 'idea'), Input::old('oprName'), array('class' => 'form-control')) }}
			
</td>








<td>{{ Form::label('fuel'.$i, 'Fuel') }}
			<br/>
				{{ Form::select('fuel'.$i,  array( 'no' => 'No','yes' => 'Yes' ), Input::old('fuel'), array('class' => 'form-control')) }}</td>

</tr>

<tr>

<td>
{{ Form::label('morningTripStartTime'.$i, 'Morning Trip Start Time') }}
			<br/>
				{{ Form::text('morningTripStartTime'.$i, Input::old('morningTripStartTime'), array('class' => 'form-control')) }}  
</td>
<td>
{{ Form::label('eveningTripStartTime'.$i, 'Evening Trip Start Time') }}
			<br/>
				{{ Form::text('eveningTripStartTime'.$i, Input::old('eveningTripStartTime'), array('class' => 'form-control')) }}
</td>
<td>
{{ Form::label('overSpeedLimit'.$i, 'OverSpeed Limit') }}
			<br/>
				{{ Form::select('overSpeedLimit'.$i,  array( '10' => '10','20' => '20','30' => '30','40' => '40','50' => '50','60' => '60','70' => '70','80' => '80','90' => '90','100' => '100','110' => '110','120' => '120','130' => '130','140' => '140','150' => '150' ), Input::old('overSpeedLimit'), array('class' => 'form-control')) }} 
</td>
<td>
{{ Form::label('mobileNo'.$i, 'Mobile Number for Alerts') }}
			<br/>
				{{ Form::text('mobileNo'.$i, Input::old('mobileNo'), array('class' => 'form-control')) }}
</td>
<td>
{{ Form::label('odoDistance'.$i, 'Odometer Reading') }}
			<br/>
				{{ Form::text('odoDistance'.$i, Input::old('odoDistance'), array('class' => 'form-control')) }}
</td>
</tr>


<tr><td>
{{ Form::label('driverName'.$i, 'Driver Name') }}
			<br/>
				{{ Form::text('driverName'.$i, Input::old('driverName'), array('class' => 'form-control')) }}
</td><td>

				{{ Form::label('gpsSimNo'.$i, 'GPS Sim Number') }}
			<br/>
				{{ Form::text('gpsSimNo'.$i, Input::old('gpsSimNo'), array('class' => 'form-control')) }}
</td>
<td>
{{ Form::label('email'.$i, 'Email for Alerts') }}
			<br/>
				{{ Form::text('email'.$i, Input::old('email'), array('class' => 'form-control')) }}
</td>
<td>{{ Form::label('altShort'.$i, 'Alternate Short Name') }}
			<br/>
				{{ Form::text('altShortName'.$i, Input::old('altShortName'), array('class' => 'form-control')) }}</td>


</tr>
<tr>

				<td>{{ Form::label('isRfi'.$i, 'Is Rfid Reader') }}
			<br/>
				{{ Form::select('isRfid'.$i,  array( 'no' => 'No','yes' => 'Yes' ), Input::old('isRfid'), array('class' => 'form-control')) }}</td>


				</tr>
				
</table>							
</div></td>
</tr>


@endfor


</tbody>
</table>




</div>
</div>
<div class="col-md-6">
<div class="row">
<div class="col-md-6">	
</div>
<div class="col-md-6">
{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}{{ Form::close() }}
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