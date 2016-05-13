<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
       			 <h6><b><font color="red"> {{ HTML::ul($errors->all()) }}</font></b></h6>
               		 <div class="panel-heading">
                   		 <h4><b><font>Checkes licences</font></b></h4>
                	 </div>
                	<div class="panel-body">
					<h4><font color="#196481">
					</font></h4>
					</br>
					<br>
                		<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                			<div class="row">
                				<div class="col-sm-12">
			                		
									{{ Form::open(array('url' => 'Licence')) }}
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-8">
												<div class="row">

													<div class="col-md-6">
														{{ Form::label('month', 'Month:') }}
														{{ Form::select('month', array($month),$monthT , array('class' => 'form-control')) }}

													</div>
													<div class="col-md-6">
														{{ Form::label('Year', 'year:') }}
														{{ Form::select('year', array($year),$yearT , array('class' => 'form-control')) }}

													</div>
													<br/>
													<div class="col-md-6">
														{{ Form::label('Licence', 'Licence:') }}
														{{ Form::select('Licence', array($Licence), $typeT, array('class' => 'form-control')) }}

													</div>
													<!-- <div class="col-md-6">
														{{ Form::label('Payment_Mode', 'Payment_Mode:') }}
														{{ Form::select('Payment_Mode', array($Payment_Mode), 'Monthly', array('class' => 'form-control')) }}

													</div> -->
													<div class="col-md-6">
														{{ Form::label('own', 'Ownership:') }}
														{{ Form::select('own', array($own), $ownT, array('class' => 'form-control')) }}

													</div>
													
													<div class="col-md-6">
		
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