@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
               		 <div class="panel-heading">
                  		 Dealers Create  
                	</div>
               		 <div class="panel-body">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmDealers')) }}
						
                	<div class="row">	
                		<div class="col-sm-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('dealerId', 'Dealer ID') }}
								</div>
								<div class="col-md-9">
									{{ Form::text('dealerId', Input::old('dealerId'), array('class' => 'form-control')) }}
								</div>
							</div>
							<br />
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('mobileNo', 'Mobile Number') }}									
								</div>
								<div class="col-md-9">
									{{ Form::text('mobileNo', Input::old('mobileNo'), array('class' => 'form-control')) }}
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('email', 'Email') }}	
								</div>
								<div class="col-md-9">
									{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
								</div>
							</div>
							<br />
							 <div class="row">
								<div class="col-md-3">
									{{ Form::label('password', 'password') }}	
								</div>
								<div class="col-md-9">
									{{ Form::text('password', Input::old('password'), array('class' => 'form-control')) }}
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