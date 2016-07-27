@extends('includes.vdmheader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4><font color="blue"><b>Edit Tag </b></font><h4> 
						</div>
						<div class="panel-body">
							<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<div class="row">
									<div class="col-sm-12">
										{{ HTML::ul($errors->all()) }}
										{{ Form::open(array('url' => 'rfid/update')) }}
										<div class="row">
											<div class="col-md-12">
												<div class="col-md-6">
													<div class="row">


														<table id="example1" class="table table-bordered dataTable">
															<thead>
																<tr>
																	<th style="text-align: center;">Tag Id</th>
																	<th style="text-align: center;">Tag Name</th>
																	<th style="text-align: center;">Mobile number</th>
																	<th style="text-align: center;">Org Name</th>
																</tr>
															</thead>
															<tbody>


																
																<tr style="text-align: center;">
																	<td>{{ Form::text('tagid', $tagid, array('class' => 'form-control')) }}
																		{{ Form::hidden('tagidtemp', $tagid, array('class' => 'form-control')) }}
																	</td>
																	<td>{{ Form::text('tagname', $tagname, array('class' => 'form-control')) }}</td>
																	<td>{{ Form::text('mobile', $mobile, array('class' => 'form-control')) }}</td>
																	<td>{{ Form::select('org', $orgList, $orgname,array('id' => 'orgid')) }}</td>
																	<td>{{ Form::hidden('orgT', $orgname,array('id' => 'orgid')) }}</td>
																	
																</tr>

															</tbody>
														</table>
													</div>
												</div>
												<div class="col-md-12">
													<div class="row">
														<div class="col-md-12">	
													
														<div class="col-md-12">
														<div class="row">
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
			@stop
			
		</body>
		</html>