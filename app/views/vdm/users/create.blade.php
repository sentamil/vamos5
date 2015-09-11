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
				<div class="row">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmUsers')) }}
							<div class="row">
							<div class="col-md-2">
							{{ Form::label('userId', 'User ID') }}
							</div>
							<div class="col-md-6">
							{{ Form::text('userId', Input::old('userId'), array('class' => 'form-control')) }}
							</div>
							</div>
							</br>
							<div class="row">
							<div class="col-md-2">
							{{ Form::label('mobileNo', 'Mobile Number') }}
							</div>
							<div class="col-md-6">
							{{ Form::text('mobileNo', Input::old('mobileNo'), array('class' => 'form-control')) }}
							</div>
							</div>
							</br>
							<div class="row">
							<div class="col-md-2">
							{{ Form::label('email', 'Email') }}
							</div>
							<div class="col-md-6">
							{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
							</div>
							</div>
							</br>
							<div class="row">
							<div class="col-md-2">
							{{ Form::label('password', 'Password') }}
							</div>
							<div class="col-md-6">
							{{ Form::text('password', Input::old('password'), array('class' =>'form-control')) }}
							</div>
							</div>
	                                                <div  style="text-align: right">
							<h6>{{ Form::submit('submit',array('class'=>'btn btn-primary')) }}</h6>
							</div>
							<hr>
							<table id="example1" class="table table-bordered dataTable">
							<thead>	
								<tr>
								<th>
							 	{{ Form::label('vehicleGroups', 'Select the Groups:') }}</th>
							 </tr>
							   </thead>
								<tbody>
							              @if(isset($vehicleGroups))
								            @foreach($vehicleGroups as $key => $value)
										<tr class="col-md-2">
									<td>
									{{ Form::checkbox('vehicleGroups[]', $key, null, ['class' => 'field']) }}
									{{ Form::label($value) }}
								@endforeach
								</td>
								</tr>
								@endif
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
