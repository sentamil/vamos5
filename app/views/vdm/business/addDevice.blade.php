@include('includes.header_index')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		 <div class="panel-heading">
                   		 Vehicles Create 
                	 </div>
                	<div class="panel-body">
                		<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                			<div class="row">
                				<div class="col-sm-12">
			                		{{ HTML::ul($errors->all()) }}
									{{ Form::open(array('url' => 'Business/adddevice')) }}
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
												
												
												<table id="example1" class="table table-bordered dataTable">
               		 <thead>
						<tr>
							<th style="text-align: center;">No</th>
							<th style="text-align: center;">Device ID</th>
							<th style="text-align: center;">Device Type</th>
						</tr>
					</thead>
					<tbody>
												
												
																	@for($i=1;$i<=$numberofdevice;$i++)
																	<tr style="text-align: center;">
							<td>{{ $i }}</td>
							<td>{{ Form::text('deviceid'.$i, Input::old('deviceid'), array('class' => 'form-control')) }}</td>
							<td>{{ Form::text('deviceidtype'.$i, Input::old('deviceidtype'), array('class' => 'form-control')) }}</td>
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
	</div>
</div>

</div>
</div>
@include('includes.js_index')
</body>
</html>