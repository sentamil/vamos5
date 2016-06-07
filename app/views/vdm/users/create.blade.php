@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
               		 <div class="panel-heading">
                  		<h4><b><font> User Create</font></b></h4>
                	</div>
               		 <div class="panel-body">
				
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmUsers')) }}
						<div class="col-md-12">
							<span id="validation" style="color: red; font-weight: bold"></span>
						</div>
							<div class="row">
							<div class="col-md-4">
							<div class="form-group">
							{{ Form::label('userId', 'User Id *') }}
							{{ Form::text('userId', Input::old('userId'), array('class' =>'form-control','placeholder'=>'UserName', 'required' => 'required', 'id'=>'userID')) }}
							</div>
							</br>
							<div class="form-group">
							{{ Form::label('mobileNo', 'Mobile Number *') }}
							{{ Form::text('mobileNo', Input::old('mobileNo'), array('class' => 'form-control','placeholder'=>'Mobile Number', 'required' => 'required')) }}
							</div>
							</div>
							<div class="col-md-4">
							<div class="form-group">
							{{ Form::label('email', 'Email *') }}
							
							{{ Form::Email('email', Input::old('email'), array('class' => 'form-control','placeholder'=>'Email', 'required' => 'required')) }}
							</div>
							</br>
							 <div class="form-group">
							{{ Form::label('password', 'Password *') }}
							
							{{ Form::text('password', Input::old('password'), array('class' =>'form-control','placeholder'=>'Password', 'required' => 'required')) }}
							</div>

							</br>
							 <div class="form-group">
							 {{Form::checkbox('virtualaccount', 'value',true, ['class' => 'check1'])}}
							{{ Form::label('virtualaccount', 'Virtual Account') }}
							</div>
							



							</div>
	                        <div class="col-md-3" style="text-align: right"><br>
							<h6>{{ Form::submit('submit', array('class' => 'btn btn-primary')) }}</h6>
							</div>
							</div>
							<hr>
							 
							<div class="row">
								 <h5><font color="#086fa1">{{ Form::label('vehicleGroups', 'Select the Groups:') }}</font></h5>	
							<div class="col-md-4">
							 
							 <h5> {{ Form::label('Filter', 'Filter :') }}
							  {{ Form::input('text', 'searchtext', null, ['class' => 'searchkey','placeholder'=>'Filter'])}}</h5>
							
							 </div> <div class="col-md-4">{{Form::label('Select All :')}} {{Form::checkbox('$vehicleGroups', 'value', false, ['class' => 'check'])}}</div>
							 </div>
							 </br>



							 	<div class="row">
				            		<div class="col-md-12" id="selectedItems" style="border-bottom: 1px solid #a6a6a6;"></div>
				            		<br>
				            		<div class="col-md-12" id="unSelectedItems">
					            		@if(isset($vehicleGroups))
									        @foreach($vehicleGroups as $key => $value)
												<div class="col-md-3 vehiclelist">
													{{ Form::checkbox('vehicleGroups[]', $key, null, ['class' => 'field', 'id' => 'questionCheckBox']) }}
													{{ Form::label($value) }}
												</div>
											@endforeach
										@endif
					            	</div>
					            </div>



							            
								
								{{ Form::close() }}
						</div>
						
			</div>
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<script type="text/javascript">
	list = [];
    var value = <?php echo json_encode($vehicleGroups ); ?>;
	
	$('#userID').on('change', function() {
		$('#validation').text('');
		var postValue = {
			'id': $(this).val()

			};
		// alert($('#groupName').val());
		$.post('{{ route("ajax.userIdCheck") }}',postValue)
			.done(function(data) {
				
				$('#validation').text(data);
        		
        		
      		}).fail(function() {
        		console.log("fail");
      });

		
	})

</script>
@include('includes.js_create')
</body>
</html>
