@include('includes.header_index')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		 <div class="panel-heading">
                   		<h4><font color="blue"><b>Tags Create </b></font><h4> 
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
							 <td>{{ Form::select('deviceidtype' .$i, array( 'GT06N' => 'GT06N (9964)', 'FM1202' => 'FM1202 (9975)','FM1120' => 'FM1120 (9975)', 'TR02' => 'TR02 (9965)', 'GT03A' => 'GT03A (9969)', 'VTRACK2' => 'VTRACK2 (9964)','ET01'=>'ET01 (9971)','ET02'=>'ET02 (9962)', 'ET03'=>'ET03 (9974)'), Input::old('deviceidtype'), array('class' => 'form-control')) }}</td>
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