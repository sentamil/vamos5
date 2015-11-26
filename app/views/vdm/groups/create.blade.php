@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
                <div class="panel-heading">
                   <h4><b><font color="blue">Create Group  </font></b></h4> 
                </div>
                <div class="panel-body">
                	<div class="row">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmGroups')) }}
  	              				<div class="row">
  	              				<div class="col-md-6">
  	              					<h5>{{ Form::label('groupId', 'Group ID') }}</h5>
											{{ Form::text('groupId', Input::old('groupId'), array('class' => 'form-control')) }}
  	              				</div>
                				<div class="col-md-5" style="text-align: right"><br>
                					<h4>{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</h4>
                				</div>
								</div>
								<hr> 

								<div class="row">
									 <div class="col-md-6">
									 <h4><font color="green">{{ Form::label('vehicleList', 'Select the vehicles:') }}</font></h4>
									 </div>
									 <div class="col-md-9">
									<h4> {{ Form::label('Filter', 'Filter :') }}
									 {{ Form::input('text', 'searchtext', null, ['class' => 'searchkey'])}}</h4>
									</div>
								</div>
								<br/>
								@if(isset($userVehicles))		  
									@foreach($userVehicles as $key => $value)
										<div class="col-md-3 vehiclelist"> 
										{{ Form::checkbox('vehicleList[]', $key, null, ['class' => 'field']) }}
										{{ Form::label($value) }}
										{{ Form::label('( ' . array_get($shortNameList, $value) . ' )') }}
										</div>
												
									@endforeach
								@endif


			              		<!--table id="example1" class="table table-bordered dataTable">

			              		<thead>
			                		<tr>
			                			<th>{{ Form::label('vehicleList', 'Select the vehicles:') }}</th>
			                			<th>{{ Form::input('text', 'searchtext', null, ['class' => 'searchkey'])}}</th>
			                		</tr>

			                	</thead>
			                	<tbody>	
								@if(isset($userVehicles))
									
								@foreach($userVehicles as $key => $value)
			                		<tr class="col-md-4 vehiclelist">
			                			<td>
				                			{{ Form::checkbox('vehicleList[]', $key, null, ['class' => 'field']) }}
											{{ Form::label($value) }}
											{{ Form::label('( ' . array_get($shortNameList, $value) . ' )') }}
			                			</td>	
			                		</tr>
								@endforeach
								@endif
			                	
               					</tbody>
                		</table -->
               	{{ Form::close() }}	
    		</div>
		</div>
	</div>
</div>
</div>
@include('includes.js_create')
</body>
</html>
