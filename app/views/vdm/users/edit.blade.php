@extends('includes.vdmheader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
		    <div class="col-lg-12">
		        <div class="hpanel">
		            <div class="panel-heading">
		               <h4><b>Edit Group</b></h4>
		            </div>
		            <div class="panel-body">
		            	{{ HTML::ul($errors->all()) }}{{ Form::model($userId, array('route' => array('vdmUsers.update', $userId), 'method' => 'PUT')) }}
		            	<div class="row">
		            		
		            		<div class="col-md-1">{{ Form::label('userId', 'UserId :')}}</div>
		            		<div class="col-md-4">{{ Form::label('userId', $userId, array('class'=>'form-control'))  }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		
		            		<div class="col-md-1">{{ Form::label('mobileNo', 'MobileNo :') }}</div>
		            		<div class="col-md-4">{{ Form::text('mobileNo', $mobileNo, array('class' => 'form-control','placeholder'=>'Mobile Number')) }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		
		            		<div class="col-md-1">{{ Form::label('email', 'Email :') }}</div>
		            		<div class="col-md-4">{{ Form::Email('email', $email, array('class' => 'form-control','placeholder'=>'Email')) }}</div>
		            	</div>
		            	<hr>
		            	<div class="row">
		            		<div class="col-md-12"><h4><font color="#086fa1">{{ Form::label('vehicleList', 'Select the groups:') }} </font></div>
		            	</div>
		            	<div class="row">
		            		<div class="col-md-1">{{ Form::label('Filter', 'Filter :') }}</div>
		            		<div class="col-md-2"> {{ Form::input('text', 'searchtext', null, ['class' => 'searchkey'])}}</div>
		            		<div class="col-md-1">{{Form::label('Select All :')}}</div>
		            		<div class="col-md-1">{{Form::checkbox('group', 'value', false, ['class' => 'check'])}}</div>
		            		<div class="col-md-2">{{ Form::submit('Update the User!', array('class' => 'btn btn-primary')) }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		@if(isset($vehicleGroups))
								@foreach($vehicleGroups as $key => $value)
									 <div class="col-md-3 vehiclelist"> 
									{{ Form::checkbox('vehicleGroups[]', $key,  in_array($value,$selectedGroups), ['class' => 'field','id' => 'questionCheckBox']) }}
									{{ Form::label($value) }}
									</div>
								@endforeach
							@endif
		            	</div>
		            	<hr>
		            	{{ Form::close() }}
		            </div> 
				</div>
			</div>
		</div>
	</div>
</div>		
<script type="text/javascript">
list = [];
var value = <?php echo json_encode($vehicleGroups ); ?>;

</script>
@include('includes.js_footer')
<div align="center">@stop</div>
