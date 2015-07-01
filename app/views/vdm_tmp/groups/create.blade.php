@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
                <div class="panel-heading">
                   Group Create  
                </div>
                <div class="panel-body">
                	<div class="row">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmGroups')) }}
  	              				<div class="row">
  	              				<div class="col-md-9">
  	              					{{ Form::label('groupId', 'Group ID') }}
											{{ Form::text('groupId', Input::old('groupId'), array('class' => 'form-control')) }}
  	              				</div>
                				<div class="col-md-3" style="text-align: right"><br>
                					<h4>{{ Form::submit('Create the Group!', array('class' => 'btn btn-primary')) }}</h4>
                				</div>
								</div>
								<hr> 
			              <table id="example1" class="table table-bordered dataTable"> 	
			              		<thead>
			                		<tr>
			                			<th>{{ Form::label('vehicleList', 'Select the vehicles:') }}</th>
			                		</tr>
			                	</thead>
			                	<tbody>	
			                		@foreach($userVehicles as $key => $value)
			                		<tr class="col-md-2">
			                			<td>
				                			{{ Form::checkbox('vehicleList[]', $key, null, ['class' => 'field']) }}
											{{ Form::label($value) }}
											{{ Form::label('( ' . array_get($shortNameList, $value) . ' )') }}
											@endforeach
			                			</td>	
			                		</tr>
               					</tbody>
                		</table>
               	{{ Form::close() }}	
    		</div>
		</div>
	</div>
</div>
</div>
@include('includes.js_create')
</body>
</html>