@extends('includes.vdmheader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
		    <div class="col-lg-12">
		        <div class="hpanel">
		            <div class="panel-heading">
		               <h4><b>Edit Notification</b></h4>
		            </div>
		            <div class="panel-body">
		            {{ Form::open(array('url' => 'vdmUsers/updateNotification')) }}
		            	<div class="row">
		            		
		            		<div class="col-md-1">{{ Form::label('userId', 'UserId :')}}</div>
		            		<div class="col-md-4">{{ Form::label('userId', $userId, array('class'=>'form-control'))  }}
		            			{{ Form::hidden('userId', $userId) }}
		            		</div>
		            		
		            		
		            	</div>
		            



		            	<br>
		            	
		            	<hr>



		            	<div class="row">
		            		<div class="col-md-12"><h4><font color="#086fa1">{{ Form::label('vehicleList', 'Select the Notification:') }} </font></div>
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
		            		<div class="col-md-12" id="selectedItems" style="border-bottom: 1px solid #a6a6a6;"></div>
		            		<br>
		            		<div class="col-md-12" id="unSelectedItems">
		            		@if(isset($notificationGroups))
								@foreach($notificationGroups as $key => $value)
									 <div class="col-md-3 vehiclelist"> 
									{{ Form::checkbox('notificationGroups[]', $key,  in_array($value,$notificationArray), ['class' => 'field','id' => 'questionCheckBox']) }}
									{{ Form::label($value) }}
									</div>
								@endforeach
							@endif
						</div>
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
var value = <?php echo json_encode($notificationGroups ); ?>;

</script>
@include('includes.js_footer')
<div align="center">@stop</div>
