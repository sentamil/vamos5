<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
       			 <h6><b><font color="red"> {{ HTML::ul($errors->all()) }}</font></b></h6>
               		 <div class="panel-heading">
                   		 <h4><b><font>ADD DEVICE</font></b></h4>
                	 </div>
                	<div class="panel-body">
					<h4><font color="#196481">Available licences :  {{$availableLincence}}
					</font></h4>
					</br>
					<br>
                		<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                			<div class="row">
                				<div class="col-sm-12">
			                		
									{{ Form::open(array('url' => 'Business')) }}
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">

													<div class="col-md-6">
														{{ Form::label('numberofdevice', 'Number Of Devices to be added :') }}

													</div>
													
													<div class="col-md-6">
														{{ Form::number('numberofdevice', Input::old('numberofdevice'), array('class' => 'form-control', 'required' => 'required', 'placeholder'=>'Quantity', 'min'=>'1'))}}
														{{ Form::hidden('availableLincence', $availableLincence, array('class' => 'form-control')) }}
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