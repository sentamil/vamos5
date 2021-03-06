@include('includes.header_create')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
							<h4><font><b>Vehicles List</b></font></h4>	
					</div>
					<div class="panel-body">
						<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<div class="row">
								<div class="col-sm-6">
									<div class="col-sm-6">
										<div id="example2_filter" class="dataTables_filter"></div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<table id="example1" class="table table-bordered dataTable">
											<thead>
												<tr>
													<th style="text-align: center;">ID</th>
													<th style="text-align: center;">Routes</th>
													<th style="text-align: center;">Actions</th>
												</tr>
											</thead>
											<tbody>
												@foreach($routeList as $key => $value)
												<tr>
													<td>{{ $key }}</td>
													<td>{{ $value }}</td>

													<!-- we will also add show, edit, and delete buttons -->
													<td> {{ Form::open(array('url' => 'vdmGeoFence/' . $key, 'class' => 'pull-right')) }}
													{{ Form::hidden('_method', 'DELETE') }}
													{{ Form::submit('Delete this route', array('class' => 'btn btn-warning')) }}
													{{ Form::close() }} 
													
													<a class="btn btn-small btn-success" href="{{ URL::to('vdmBusStops/' . $orgId . ':' . $value) }}">Show stops</a>
													<!--
													<a class="btn btn-small btn-info" href="{{ URL::to('vdmBusRoutes/' . $key . '/edit') }}">Edit stops </a></td>
													-->
												</tr>
												@endforeach
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@include('includes.js_create')
			</body>
			</html>
