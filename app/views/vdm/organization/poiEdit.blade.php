@extends('includes.vdmheader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
		    <div class="col-lg-12">
		        <div class="hpanel">
		            <div class="panel-heading">
		               <h4><b>Edit POI</b></h4>
		               <hr>
		            </div>
		            <div class="panel-body">
		            	{{ HTML::ul($errors->all()) }}{{ Form::open(array('url' => 'vdmOrganization/addpoi')) }}
		            	
		            	<div class="row">
		            		<!-- <div class="col-md-3"></div> -->
		            		<div class="col-md-2">{{ Form::label('orgId', 'OrgId Id :')  }}</div>
		            		<div class="col-md-4">{{ Form::text('orgId',  $orgId, array('class' => 'form-control')) }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<!-- <div class="col-md-3"></div> -->
		            		<div class="col-md-2">{{ Form::label('radius', 'Radius:')  }}</div>
		            		<div class="col-md-4">{{ Form::Number('radiusrange',  $radiusrange ,Input::old('radiusrange'), array('class' => 'form-control','placeholder'=>'Radius Range (Km)', 'min'=>'1','required'=>'required')) }}
		            			
		            		</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<hr>
		            		<div class="col-md-2" style="color: #086fa1">{{ Form::label('userplace', 'Select the Places:') }}</div>

		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-1">{{ Form::label('Filter', 'Filter :') }}</div>
		            		<div class="col-md-2">{{ Form::input('text', 'searchtext', null, ['class' => 'searchkey','placeholder'=>'Filter'])}}</div>
		            		<div class="col-md-1">{{Form::label('Select All :')}} </div>
		            		<div class="col-md-1">{{Form::checkbox('$userplace', 'value', false, ['class' => 'check'])}}</div>
		            		<div class="col-md-3">{{ Form::submit('Update the POI!', array('class' => 'btn btn-primary')) }}</div>
		            	</div>
		            	<br>
		            	<hr>
		            	<div class="row">
		            		@if(isset($userplace))		  
								@foreach($userplace as $key => $value)
									 <div class="col-md-3 vehiclelist">
									{{ Form::checkbox('poi[]', $userplace[$key],  in_array($value,$selectedVehicles), ['class' => 'field','id' => 'questionCheckBox']) }}
									{{ Form::label($userplace[$key]) }}			
									
									</div>
								@endforeach
							@endif
		            	</div>
		            	{{ Form::close() }}
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</div>
<script type="text/javascript">
list = [];
var value = <?php echo json_encode($userplace); ?>;

</script>
@include('includes.js_footer')

<div style="top: 0; left: 0;right: 0; padding-top: 30px;" align="center"><hr>@stop</div>
