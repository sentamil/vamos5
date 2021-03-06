@include('includes.header_index')
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
            <div class="panel-heading">
			 @if(Session::has('message'))
             <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
             @endif
                <h4> <b>Device List</b></h4>
            </div>
            <div class="panel-body">
			    <!-- <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                	<div class="row">
                		<div id="example2_filter" class="dataTables_filter"></div>
                	<div class="col-sm-12"> -->
				<div class="form-group">
					<table id="example1" class="table table-bordered dataTable">
						<thead>
					 		<tr>
								<th style="text-align: center;">ID</th>
								<th style="text-align: center;">Device ID</th>
								<th style="text-align: center;">Vehicle Id</th>
								<th style="text-align: center;">Dealer Name</th>
								<th style="text-align: center;">Onboard Date</th>
								<th style="text-align: center;">Vehicle Expiry</th>
							    <th style="text-align: center;">Move Vehicle</th>
							</tr>
						</thead>
						<tbody>
						@if(isset($deviceMap))
						 @foreach($deviceMap as $key => $value)
							<tr style="text-align: center;">
								<td>{{ $key }}</td>
								<td>{{ explode (',' ,$value )[1]  }}</td>
								<td>{{ explode (',' ,$value )[0]  }}</td>
								<td>{{ explode (',' ,$value )[2]  }}</td>
								<td>{{ explode (',' ,$value )[3]  }}</td>
								<td>{{ explode (',' ,$value )[4]  }}</td>
								<td>
								 <a class="btn btn-sm btn-danger" href="{{ URL::to('vdmVehicles/move_vehicle/' . $value) }}">Move Vehicle</a>
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
@include('includes.js_index')
</body>
</html>