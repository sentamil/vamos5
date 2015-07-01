@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
               		 <div class="panel-heading">
                  		 User Create  
                	</div>
               		 <div class="panel-body">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmUsers')) }}
						
                	<div class="row">	
                		<div class="col-sm-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('userId', 'User ID') }}
								</div>
								<div class="col-md-9">
									{{ Form::text('userId', Input::old('userId'), array('class' => 'form-control')) }}
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
							 <div class="form-group">
							 	{{ Form::label('vehicleGroups', 'Select the Groups:') }}
							 </div>
							   <br>
								@foreach($vehicleGroups as $key => $value)
									{{ Form::checkbox('vehicleGroups[]', $key, null, ['class' => 'field']) }}
									{{ Form::label($value) }}
									<br/>
								@endforeach
								</br/>
								</br/>
								
								
								{{ Form::label('orgsList', 'Select Organizations:') }}
								
								<br/>
								<br/>
                                @foreach($orgsList as $key => $value)
                                    {{ Form::checkbox('orgsList[]', $key, null, ['class' => 'field']) }}
                                    {{ Form::label($value) }}
                                    <br/>
                                @endforeach
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