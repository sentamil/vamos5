@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		 <div class="panel-heading">
                   		 <h4><b><font color="blue">ADD DEVICE</font></b></h4>
                	 </div>
                	<div class="panel-body">
					Available licence :  {{Session::get('availableLincence')}}
					</br>
					
                		<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                			<div class="row">
                				<div class="col-sm-12">
			                		{{ HTML::ul($errors->all()) }}
									{{ Form::open(array('url' => 'Business')) }}
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">

													<div class="col-md-6">
														{{ Form::label('numberofdevice', 'Number of device') }}

													</div>
													<div class="col-md-6">
														{{ Form::text('numberofdevice', Input::old('numberofdevice'), array('class' => 'form-control')) }}
													</div>
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
@include('includes.js_create')
</body>
</html>