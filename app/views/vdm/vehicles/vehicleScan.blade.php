@include('includes.header_index')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
.bt{
	margin-top: 5px !important;
}
.popover {
	position: absolute;
	border: 1px solid gray;
	min-width:380px;
	max-width:450px;
	min-height: 240px;
    max-height:260px;
    background-color:#fdfdfd; 
}
.tabledata {
    min-width: 350px;
    max-width: 420px;
    padding: 1px;
}
.tcentre{
	text-align: center;
}
.table-striped{
background-color:#e7e6e6;
}

table {
width: 100%; 
}
tr:nth-of-type(odd) {
 /* background: white;*/
}
@media  only screen and (max-width: 760px), 
 (min-device-width: 320px) and (max-device-width: 780px) {
  table, thead, tbody, th, td, tr {
    display: block;
  }
   thead tr {
    position: absolute;
        top: -9999px;
        left: -9999px;
  }
  tr {
    border: 1px solid #eee; 
  }
  td {

    border-bottom: 1px solid #eee;
    position: relative;
    white-space: normal;
   /* width: 50%;*/
    font-size: 17px;
  }
 td:before { 
    position:relative;
    color: #8AC007; 
    padding: 2px;
    right: 10px;
	border: 2px solid #aaa; 
  }
  th {
	  text-align: left;
  }
/*.vCol'=='1')
{
	td:nth-of-type(6):before {content: "Action ";}
}*/
  td:nth-of-type(1):before {content: "ID ";}
  td:nth-of-type(2):before {content: "AssetID ";}
  td:nth-of-type(3):before {content: "Vehicles Name ";}
  td:nth-of-type(4):before {content: "Org Name ";}
  td:nth-of-type(5):before {content: "Device ID ";}
  td:nth-of-type(6):before {content: "Mobile No ";}
  td:nth-of-type(7):before {content: "Status ";}  
  td:nth-of-type(8):before {content: "Device Model ";}
  
  @if(Session::get('vCol')=='1')
	  
  td:nth-of-type(9):before {content: "Action "}
  @endif
  <!--td:nth-of-type(6):before {content: "Status ";}  
  td:nth-of-type(7):before {content: "Device Model ";}
  td:nth-of-type(8):before {content: "Mobile No ";}-->
 } 
 </style>
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
				     @if(Session::has('message'))
                	 <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                	 @endif
                    <h4><b>Vehicles Search</b></h4>                
                </div>
                <div class="panel-body">
                {{ Form::open(array('url' => 'VdmVehicleScan','method' => 'post')) }}
                <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6">
                        <div class="row">
                           {{ Form::text('text_word' , Input::old('text_word'),  array('class' => 'form-control', 'placeholder'=>'Search Vehicles'))}}
                           <div style="font-size: 9px; text-align: center;"><b>Note : </b> Use * for getting all Vehicles </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        {{ Form::submit('Search', array('class' => 'btn btn-primary')) }}{{ Form::close() }}
                      </div>
                      <hr>
                  </div>
                </div>
                <!-- {{ Form::open(array('url' => 'vdmVehiclesSearch/scan','method' => 'post')) }}

     {{ Form::text('text_word') }}
  <input type="submit" name="$value" value="search">
   {{ Form::close() }} -->
                	<table id="example1" class="table table-bordered dataTable">
               		 <thead>
						<tr>
							<th style="text-align: center;">ID</th>
							<!--  @if(Session::get('cur')=='admin')
							<th style="text-align: center;">Select</th>
							 @endif  -->
							<th style="text-align: center;">AssetID </th>
							<th style="text-align: center;">Vehicle Name</th>
							<th style="text-align: center;">Org Name</th>
							<th style="text-align: center;">Device ID</th>
							<th style="text-align: center;">Gps Sim No</th>
							<th style="text-align: center;">status</th>
							<th style="text-align: center;">Device Model</th>
							<th style="text-align: center;">Action</th> 
						</tr>
					</thead>
					<tbody>
					@foreach($vehicleList as $key => $value)
						<tr>
							<td>{{ ++$tmp }}</td>
							 <!-- @if(Session::get('cur')=='admin')
							<td>{{ Form::checkbox('vehicleList[]', $value, null, ['class' => 'field']) }}</td>
							@endif  -->
							<td>{{ $value }}</td>
							<td>{{ array_get($shortNameList, $value)}}</td>
							<td>{{ array_get($orgIdList, $value)}}</td>
							<td>{{ array_get($deviceList, $value)}}</td>
					        <td>{{ array_get($mobileNoList, $value)}}</td>
							 
							

								<td>
									@if(array_get($statusList, $value) == 'P')
										<div style="color: #8e8e7b">Parking</div>
									@endif
									@if(array_get($statusList, $value) == 'M')
										<div style="color: #00b374">Moving</div>
									@endif
									@if(array_get($statusList, $value) == 'S')
										<div style="color: #ff6500">Idle</div>
									@endif
									@if(array_get($statusList, $value) == 'U')
										<div style="color: #fe068d">No Data</div>
									@endif
									@if(array_get($statusList, $value) == 'N')
										<div style="color: #0a85ff">New Device</div>
									@endif
								</td>
								

							<td>{{ array_get($deviceModelList, $value)}}</td>
						<!--  <td>{{ array_get($expiredList, $value)}}</td> -->
					       <!-- <td>{{ array_get($mobileNoList, $value)}}</td> 	-->
											
							@if(Session::get('vCol')=='1')
							<td>
								
								<a  class="btn btn-sm btn-info" href="{{ URL::to('vdmVehicles/stops/' . $value,'normal') }}" >Show Stops</a>
								
								<a class="btn btn-sm btn-danger" href="{{ URL::to('vdmVehicles/removeStop/' . $value,'normal') }}">Remove Stops</a>
								
								<!--<a  class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/stops/' . $value,'alternate') }}" >Show ALTS</a>
								
								<a class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/removeStop/' . $value,'alternate') }}">Remove ALTS</a>-->
								
								
<!-- 								<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/' . $value) }}">Show Vehicle</a>
 -->								
								<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/migration/' . $value) }}">Migration</a>
					
								<a class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/edit/' . $value ) }}">Edit</a>
								
								<a class="btn btn-sm btn-warning" href="{{ URL::to('vdmVehicles/calibrateOil/' . $value,'0') }}">Calibrate</a>
							    
								<a class="btn btn-sm btn-info" href="{{ URL::to('vdmVehicles/rename/' . $value) }}">Rename</a>
								
								<button  type="button" class="btn btn-sm btn-primary bt" data-toggle="popover" onclick="getVal('{{$value}}')" data-trigger="focus" >Details</button>
			
				
							</td>@endif 
						</tr>
						@endforeach
 <script>
 var lat;
 var lng;
 function getVal(val){   

 	var vehId=val;
	
	console.log('test vamos');
	var datas = {
		'id':vehId
	}
    $.post('{{ route("ajax.details") }}', datas, function(data, textStatus, xhr) {
    lat=data.latitude;
    lng=data.longitude;
	
	$('#maxSpeed').text(data.maxSpeed);
	$('#regNo').text(data.regNo);
	$('#time').text(data.time);
	$('#lastComm').text(data.lastComm);
	$('#lastSeen').text(data.lastSeen);
	$('#deviceVolt').text(data.deviceVolt);
	$('#position').text(data.position);
	$('#sat').text(data.sat);
	$('#kms').text(data.kms);
	$('#ac').text(data.ac);
	$('#speed').text(data.speed);
	$('#direction').text(data.direction);
	$('#nearLoc').text(data.nearLoc);
	$('#latitude').text(data.latitude);
	$('#longitude').text(data.longitude);
	$('#odoDistance').text(data.odoDistance);
	$('#statusList').text(data.statusList);
	$('#devicestatus').text(data.devicestatus);
    $('#vehicleType').text(data.vehicleType);
	$('#error').text(data.error);

	if(data.error!=' ')	{
	 //alert(data.error);
	}
   });								
 }
 function callFunct() {
	var url="https://www.google.com/maps?q=loc:"+lat+","+lng;
	window.open(url,'_blank');
 }
 $(document).ready(function(){
	
   $('[data-toggle="popover"]').popover({
        placement : 'right',
	    html : true,
        content :'<div id="test1">'
           +'<table class="tabledata table-striped " style="padding: 8px;">'
           +'<tbody>'
           +'<tr style="border-bottom: 1px solid #ddd;">'+'<td  id="regNo" colspan="3"  style="padding-top:5px; padding-left:100px; font-weight:bold; "></td><td><a href="#" onclick="callFunct()">G-Map</a></td></tr>'
           +'<tr style="border-bottom: 1px solid #ddd;">'+'<td style="padding-top:5px; padding-right:5px; padding-left:8px; font-weight:bold; ">Odo(kms)</td>'+'<td id="odoDistance" style="padding-top:5px;border-right:0.5px solid #c8cec6; padding-left:8px;"></td>'+'<td style="padding-top:5px; padding-left:8px; font-weight:bold; padding-right:5px;">Kms</td>'+'<td id="kms" style="padding-top:5px; padding-left:3px;padding-left:8px;"></td></tr>'
           +'<tr style="border-bottom: 1px solid #ddd;"><td style="padding-top:5px; padding-left:8px; font-weight:bold; padding-right:5px;">Ignition</td>'+'<td id="ac" style="padding-top:5px; border-right:0.5px solid #c8cec6; padding-left:8px;"></td>'+'<td style="padding-top:5px; padding-left:8px; font-weight:bold; padding-right:5px;">MaxSpeed</td>'+'<td id="maxSpeed" style="padding-top:5px; padding-left:8px;"></td></tr>'
           +'<tr style="border-bottom: 1px solid #ddd;"><td style="padding-top:5px; padding-left:8px; font-weight:bold; padding-right:5px;">Volt</td>'+'<td id="deviceVolt" style="padding-top:5px; border-right:0.5px solid #c8cec6; padding-left:8px;"></td>'+'<td style="padding-top:5px;padding-left:8px; font-weight:bold; padding-right:5px;">Speed</td>'+'<td id="speed" style="padding-top:5px; padding-left:8px;"></td></tr>'
           +'<tr style="border-bottom: 1px solid #ddd;"><td style="padding-top:5px; padding-left:8px; font-weight:bold; padding-right:5px;">SatCount</td>'+'<td id="sat" style="padding-top:5px; border-right:0.5px solid #c8cec6; padding-left:8px;"></td>'+'<td style="padding-top:5px;padding-left:8px; font-weight:bold; padding-right:5px;">VehicleType</td>'+'<td id="vehicleType" style="padding-top:5px; padding-left:8px;"></td></tr>'
           +'<tr style="border-bottom: 1px solid #ddd;"><td style="padding-top:5px; padding-left:8px; font-weight:bold; padding-right:5px;">Position</td>'+'<td id="statusList" style="padding-top:5px; border-right:0.5px solid #c8cec6; padding-left:8px;"></td>'+'<td id="devicestatus" style="padding-top:5px; padding-left:8px; font-weight:bold; padding-right:5px;"></td>'+'<td id="time" style="padding-top:5px; padding-left:8px;"></td>'
           +'<tr style="border-bottom: 1px solid #ddd;"><td style="padding-top:5px; padding-left:8px; font-weight:bold;">Loc Time</td>'+'<td class="tcentre" style="break-word:wrap; padding-top:5px; border-right:0.5px solid #c8cec6; padding-left:8px;" id="lastSeen"></td>'
           +'<td style="padding-top:5px; padding-left:8px; font-weight:bold;">Comm Time</td>'
           +'<td class="tcentre" padding-top:5px; padding-left:8px;" id="lastComm"></td></tr>'
           +'<tr><td colspan="4" class="tcentre" style="break-word:wrap; padding-top:5px;padding-bottom:5px;" id="nearLoc"></td></tr>'
           +'</tbody></table>'
           +'</div>'
		  });
    });	
 function ConfirmDelete()
  {
  var x = confirm("Confirm to remove?");
  if (x)
    return true;
  else
    return false;
  }

</script>
						
					</tbody>
                </table>
         
</div>
</div>
</div>
@include('includes.js_index')
</body>
</html>