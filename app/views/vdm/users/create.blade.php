@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
               		 <div class="panel-heading">
                  		<h4><b><font color="blue"> User Create</font></b></h4>
                	</div>
               		 <div class="panel-body">
				
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmUsers')) }}
							
							<div class="row">
							<div class="col-md-4">
							<div class="form-group">
							{{ Form::label('userId', 'User ID') }}
							{{ Form::text('userId', Input::old('userId'), array('class' =>'form-control')) }}
							</div>
							</br>
							<div class="form-group">
							{{ Form::label('mobileNo', 'MOBILE NUMBER') }}
							{{ Form::text('mobileNo', Input::old('mobileNo'), array('class' => 'form-control')) }}
							</div>
							</div>
							<div class="col-md-4">
							<div class="form-group">
							{{ Form::label('email', 'Email') }}
							
							{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
							</div>
							</br>
							 <div class="form-group">
							{{ Form::label('password', 'Password') }}
							
							{{ Form::text('password', Input::old('password'), array('class' =>'form-control')) }}
							</div>
							</div>
	                        <div class="col-md-3" style="text-align: right"><br>
							<h6>{{ Form::submit('submit', array('class' => 'btn btn-primary')) }}</h6>
							</div>
							</div>
							<hr>
							 
							<div class="row">
							<div class="col-md-9">
							 	
							 <h4> {{ Form::label('Filter', 'Filter :') }}
							  {{ Form::input('text', 'searchtext', null, ['class' => 'searchkey'])}}</h4>
							  <h4><font color="green">{{ Form::label('vehicleGroups', 'Select the Groups:') }}</font></h4>
							 </div>
							 </div>
							 </br>
							              @if(isset($vehicleGroups))
								            @foreach($vehicleGroups as $key => $value)
										<div class="col-md-3 vehiclelist">
									
									{{ Form::checkbox('vehicleGroups[]', $key, null, ['class' => 'field']) }}
									{{ Form::label($value) }}
									</div>
								@endforeach
								
								@endif
								
								{{ Form::close() }}
						</div>
			</div>
		</div>
	</div>
</div>
@include('includes.js_create')
</body>
</html>
