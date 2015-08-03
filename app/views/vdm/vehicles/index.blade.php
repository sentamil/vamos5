@include('includes.header_index')
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
                    Vehicles List  
                </div>
                <div class="panel-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6">
                <div class="col-sm-6"><div id="example2_filter" class="dataTables_filter"></div></div></div><div class="row">
                	<div class="col-sm-12">
                	<table id="example1" class="table table-bordered dataTable">
               		 <thead>
						<tr>
							<th style="text-align: center;">ID</th>
							<th style="text-align: center;">Vehicle ID</th>
							<th style="text-align: center;">Short Name</th>
							<th style="text-align: center;">Device ID</th>
							<th style="text-align: center;">Mobile No</th>
							<th style="text-align: center;">Actions</th>
						</tr>
					</thead>
					<tbody>
					@foreach($vehicleList as $key => $value)
						<tr style="text-align: center;">
							<td>{{ ++$key }}</td>
							<td>{{ $value }}</td>
							<td>{{ array_get($shortNameList, $value)}}</td>
							<td>{{ array_get($deviceList, $value)}}</td>
					        <td>{{ array_get($mobileNoList, $value)}}</td>    
							<td>
								{{ Form::open(array('url' => 'vdmVehicles/' . $value, 'class' => 'btn-sm pull-right')) }}
									{{ Form::hidden('_method', 'DELETE') }}
									{{ Form::submit('Remove', array('class' => 'btn btn-sm btn-danger')) }}
								{{ Form::close() }}
								<a class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/stops/' . $value) }}">Show Stops</a>
								<a class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/removeStop/' . $value) }}">Remove Stops</a>
								<a class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/' . $value) }}">Show Vehicle</a>
								
								<a class="btn btn-sm btn-success" href="{{ URL::to('vdmGeoFence/' . $value) }}">Show GeoFencing</a>
					
								<a class="btn btn-sm btn-info" href="{{ URL::to('vdmVehicles/' . $value . '/edit') }}">Edit</a>
				
							</td>
						</tr>
						@endforeach
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