@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-9">
        		<div class="hpanel">
               		 <div class="panel-heading">
                  		<h4><b><font color="blue">Dealers Create</font></b></h6> 
                	</div>
               		 <div class="panel-body">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmDealers')) }}
						
                	<div class="row">	
                		<div class="col-sm-9">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('dealerId', 'Dealer ID') }}
								</div>
								<div class="col-md-6">
									{{ Form::text('dealerId', Input::old('dealerId'), array('class' => 'form-control')) }}
								</div>
							</div>
							<br />
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('mobileNo', 'Mobile Number') }}									
								</div>
								<div class="col-md-6">
									{{ Form::text('mobileNo', Input::old('mobileNo'), array('class' => 'form-control')) }}
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('email', 'Email') }}	
								</div>
								<div class="col-md-6">
									{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
								</div>
							</div>
							<br />
							 <div class="row">
								<div class="col-md-3">
									{{ Form::label('password', 'password') }}	
								</div>
								<div class="col-md-6">
									{{ Form::text('password', Input::old('password'), array('class' => 'form-control')) }}
								</div>
							</div>
							  <br />
							 <div class="row">
								<div class="col-md-3">
									{{ Form::label('website', 'website') }}	
								</div>
								<div class="col-md-6">
									{{ Form::text('website', Input::old('website'), array('class' => 'form-control')) }}
								</div>
							</div>


 <br />
								<div class="row">
								<div class="col-md-3">
							{{ Form::label('smsSender', 'SMS Sender') }}
							</div>
							<div class="col-md-6">
							{{ Form::text('smsSender', Input::old('smsSender'), array('class' => 'form-control')) }}
								</div>
						</div> <br />
						<div class="row">
								<div class="col-md-3">
		{{ Form::label('smsProvider', 'SMS Provider') }}
			</div><div class="col-md-6">
		 		{{ Form::select('smsProvider',  array( $smsP), Input::old('smsProvider'), array('class' => 'form-control')) }} 
			</div>
			</div> <br />
							<div class="row">
								<div class="col-md-3">
		{{ Form::label('providerUserName', 'SMS Provider User Name') }}
		</div><div class="col-md-6">
		{{ Form::text('providerUserName', Input::old('providerUserName'), array('class' => 'form-control')) }}
		</div>
	</div> <br />
	<div class="row">
								<div class="col-md-3">
		{{ Form::label('providerPassword', 'SMS Provider Password') }}</div>
		<div class="col-md-6">
		{{ Form::text('providerPassword', Input::old('providerPassword'), array('class' => 'form-control')) }}
		</div>
	</div>



								</br/>
								</br/>
							<div style="text-align: right">
								{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}							
								{{ Form::close() }}	
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