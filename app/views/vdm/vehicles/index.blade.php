@include('includes.header_index')
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
                    <h4><b>Vehicles List</b></h4>  
                </div>
                <div class="panel-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6">
                <div class="col-sm-6"><div id="example2_filter" class="dataTables_filter"></div></div></div><div class="row">
                	<div class="col-sm-12">
                	<table id="example1" class="table table-bordered dataTable">
               		 <thead>
						<tr>
							<th style="text-align: center;">ID</th>
							<th style="text-align: center;">AssetID </th>
							<th style="text-align: center;">Vehicle Name</th>
							<th style="text-align: center;">Org Name</th>
							<th style="text-align: center;">Device ID</th>
							
								@if(Session::get('vCol')=='2')
									<th style="text-align: center;">Status</th>
									<th style="text-align: center;">Device Model</th>
								<!-- <th style="text-align: center;">Expire Date</th> -->
								<th style="text-align: center;">Mobile No</th>
									@endif 
							@if(Session::get('vCol')=='1')
							<th style="text-align: center;">Actions</th>
							@endif 
						</tr>
					</thead>
					<tbody>
					@foreach($vehicleList as $key => $value)
						<tr style="text-align: center;">
							<td>{{ ++$tmp }}</td>
							<td>{{ $value }}</td>
							<td>{{ array_get($shortNameList, $value)}}</td>
							<td>{{ array_get($orgIdList, $value)}}</td>
							<td>{{ array_get($deviceList, $value)}}</td>
					        
							@if(Session::get('vCol')=='2')

								<td>
									@if(array_get($statusList, $value) == 'P')
										<div style="color: #8e8e7b">Parking</div>
									@endif
									@if(array_get($statusList, $value) == 'M')
										<div style="color: #00b374">Moving</div>
									@endif
									@if(array_get($statusList, $value) == 'S')
										<div style="color: #ff6500">Standing</div>
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
					        <td>{{ array_get($mobileNoList, $value)}}</td> 	
							@endif 							
							@if(Session::get('vCol')=='1')
							<td>
								
								<a  class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/stops/' . $value,'normal') }}" >Show Stops</a>
								
								<a class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/removeStop/' . $value,'normal') }}">Remove Stops</a>
								
								<a  class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/stops/' . $value,'alternate') }}" >Show ALTS</a>
								
								<a class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/removeStop/' . $value,'alternate') }}">Remove ALTS</a>
								
								
<!-- 								<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/' . $value) }}">Show Vehicle</a>
 -->								
								<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/migration/' . $value) }}">Migration</a>
					
								<a class="btn btn-sm btn-info" href="{{ URL::to('vdmVehicles/' . $value . '/edit') }}">Edit</a>
								
								<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/calibrateOil/' . $value) }}">Calibrate</a>
							
			
				
							</td>@endif 
						</tr>
						@endforeach
						
						
							<script>

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
                </table></div></div>
            </div>
    </div>
</div>
</div>
</div>
@include('includes.js_index')
</body>
</html>