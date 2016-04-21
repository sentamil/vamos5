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
		            	{{ HTML::ul($errors->all()) }}{{ Form::model($groupId, array('route' => array('vdmGroups.update', $groupId), 'method' => 'PUT')) }}
		            	<div class="row">
		            		
		            		<div class="col-md-1">{{ Form::label('groupId', 'Group Id :')  }}</div>
		            		<div class="col-md-4">{{ Form::label('groupId', $groupId, array('class'=>'form-control'))  }}</div>
		            	</div>
		            	<hr>
		            	<div class="row">
		            		<div class="col-md-12"><h4><font color="#086fa1">{{ Form::label('vehicleList', 'Select the vehicles:') }} </font></div>
		            	</div>
		            	<div class="row">
		            		<div class="col-md-1">{{ Form::label('Filter', 'Filter :') }}</div>
		            		<div class="col-md-2">{{ Form::input('text', 'searchtext', null, ['class' => 'searchkey'])}}</div>
		            		<div class="col-md-1">{{Form::label('Select All :')}}</div>
		            		<div class="col-md-1">{{Form::checkbox('$userVehicles', 'value', false, ['class' => 'check'])}}</div>
		            		<div class="col-md-2"> {{ Form::submit('Update the Group!', array('class' => 'btn btn-primary')) }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		@if(isset($vehicleList))		  
								@foreach($vehicleList as $key => $value)
									<div class="col-md-3 vehiclelist"> 
									{{ Form::checkbox('vehicleList[]', $key,  in_array($value,$selectedVehicles), ['class' => 'field' ,'id' => 'questionCheckBox']) }}
									{{ Form::label($value) }}
									{{ Form::label('( ' . array_get($shortNameList, $value) . ' )') }}
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
var value = <?php echo json_encode($vehicleList ); ?>;

</script>
@include('includes.js_footer')
<div align="center">@stop
</div>

