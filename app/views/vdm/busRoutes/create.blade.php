@include('includes.header_create') <!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4><font color="blue"><b>Vehicles Create</b></font></h4>
					</div>
					<div class="panel-body">
						<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">

							<div class="row">
								<div class="col-sm-12">
									{{ HTML::ul($errors->all()) }}

									{{ Form::open(array('url' => 'vdmBusRoutes')) }}

					                  			<div class="col-md-4">
										<div class="form-group">
											{{ Form::label('OrgId', 'Org Id') }}
										
											{{ Form::select('OrgId', $orgList,Input::old('orgId'),array('class' => 'form-control')) }}
										</div></br>
										<br>
										<div class="form-group">
											{{ Form::label('routeId', 'Bus Route Number') }}
										
											{{ Form::text('routeId', Input::old('routeId'), array('class' => 'form-control')) }}
										</div></br>
										<br>
										<div class="form-group">
											{{ Form::label('morningSeq', 'Morning Sequence') }}
					                                            {{ Form::text('morningSeq', Input::old('morningSeq'), array('class' => 'form-control')) }}

										</div></br>
										<br>
										<div class="form-group">
                                           						 {{ Form::label('eveningSeq', 'Evening Sequence') }}
                                                                                        {{ Form::text('eveningSeq', Input::old('eveningSeq'), array('class' => 'form-control')) }}
                                       						 </div></br>
										 <br></div>
										<div class="col-md-4">
										<div class="form-group">
										
											{{ Form::label('stops', 'Bus Stops') }}
											{{ Form::textarea('stops', '', array('class' => 'form-control')) }}
										</div></br>
										</br>
										{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}

										{{ Form::close() }}
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
@include('includes.js_create')
</body>
</html>

