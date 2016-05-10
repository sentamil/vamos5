@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
                <div class="panel-heading">
                   <h4><b>Create Group  </b></h4> 
                </div>
                <div class="panel-body">
                	<div class="row">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmGroups')) }}
						<div class="col-md-12">
							<span id="validation" style="color: red; font-weight: bold"></span>
						</div>
  	              				<div class="row">
  	              					<div class="col-md-12">
		  	              				<div class="col-md-2">
		  	              					<h5>{{ Form::label('groupId', 'Group ID') }}</h5>
		  	              				</div>
										<div class="col-md-4">{{ Form::text('groupId', Input::old('groupId'), array('class' => 'form-control', 'placeholder'=>'Group Id','required'=>'required','id'=>'groupName')) }}</div>
		                				<div class="col-md-3" style="text-align: right">
		                					{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
		                				</div>
									</div>
								</div>
								<hr> 
								<div class="row">
									 <div class="col-md-12">
									 <h5><font>{{ Form::label('vehicleList', 'Select the vehicles:') }}</font></h5>
									

									 </div>
									 <div class="col-md-5">
									<h5> {{ Form::label('Filter', 'Filter :') }}
									 {{ Form::input('text', 'searchtext', null, ['class' => 'searchkey','placeholder'=>'Filter'])}}  </h5>
									
									</div>
									 <div class="col-md-5"><h5>{{Form::label('Select All :')}} {{Form::checkbox('$userVehicles', 'value', false, ['class' => 'check'])}} </h5></div>
								</div>
								<br/>
								<div class="row">
				            		<div class="col-md-12" id="selectedItems" aligin="center"></div>
				            		<br>
				            		<div class="col-md-12" id="unSelectedItems">
				            		@if(isset($userVehicles))		  
											@foreach($userVehicles as $key => $value)
												<div class="col-md-3 vehiclelist"> 
												{{ Form::checkbox('vehicleList[]', $key, null, ['class' => 'field', 'id' => 'questionCheckBox']) }}
												{{ Form::label($value) }}
												{{ Form::label('( ' . array_get($shortNameList, $value) . ' )') }}
												</div>
														
											@endforeach
										@endif
								</div>
		            		</div>


								<!-- @if(isset($userVehicles))		  
									@foreach($userVehicles as $key => $value)
										<div class="col-md-3 vehiclelist"> 
										{{ Form::checkbox('vehicleList[]', $key, null, ['class' => 'field', 'id' => 'questionCheckBox']) }}
										{{ Form::label($value) }}
										{{ Form::label('( ' . array_get($shortNameList, $value) . ' )') }}
										</div>
												
									@endforeach
								@endif -->
								
								<script>
									list = [];
                  					var value = <?php echo json_encode($userVehicles ); ?>;
								</script>
			              		
			              		
               	{{ Form::close() }}	
    		</div>
		</div>
	</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<script type="text/javascript">
	$('#groupName').on('change', function() {
		$('#validation').text('');
		var postValue = {
			'id': $(this).val()

			};
		// alert($('#groupName').val());
		$.post('{{ route("ajax.groupIdCheck") }}',postValue)
			.done(function(data) {
				if(data=='fail')
				$('#validation').text('No groups available ,Please select another user');
        		console.log("Sucess-------->"+data);
        		
      		}).fail(function() {
        		console.log("fail");
      });

		
	})

</script>
@include('includes.js_create')
</body>
</html>
