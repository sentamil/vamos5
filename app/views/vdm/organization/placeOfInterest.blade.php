@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
                <div class="panel-heading">
                  <h4><font><b> Place of Interest </b> </font></h4>
                </div>
                <div class="panel-body">
                	<div class="row">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmOrganization/addpoi')) }}
  	              				<div>
  	              				<div class="col-md-5">
  	              					{{ Form::label('orgId', 'Organisation') }}
											
									{{ Form::select('orgId', array($orgList), Input::old('orgId'), array('class' => 'form-control selectpicker show-menu-arrow', 'data-live-search '=> 'true')) }}   
  	              				</div>
								<div class="col-md-3">
  	              					{{ Form::label('radiusrange', 'Radius Range') }}
											{{ Form::Number('radiusrange', Input::old('radiusrange'), array('class' => 'form-control','placeholder'=>'Radius Range (Km)', 'min'=>'1')) }}
  	              				</div>
                				<div class="col-md-3" style="text-align: right"><br>
                					<h4>{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</h4>
                				</div>
								</div>
								<hr> 
								<div class="row">
								<div class="col-md-12">
								<h5><font color="#086fa1">{{ Form::label('userplace', 'Select the Place Of Interest :') }}</font></h5>
								</div>
								<div class="col-md-3">{{ Form::label('Filter', 'Filter :')}}
								{{ Form::input('text', 'searchtext', null, ['class' => 'searchkey'])}}</div><div class="col-md-3">
								{{Form::label('Select All :')}} {{Form::checkbox('$userplace', 'value', false, ['class' => 'check'])}}</div>
								
								</div>
								</div>
								<br>

								<div class="row">
				            		<div class="col-md-12" id="selectedItems" style="border-bottom: 1px solid #a6a6a6;"></div>
				            		<br>
				            		<div class="col-md-12" id="unSelectedItems">
					            		@if(isset($userplace))
											@foreach($userplace as $key => $value)
												<div class="col-md-3 vehiclelist">
													{{ Form::checkbox('poi[]', $userplace[$key], null, ['class' => 'field', 'id' => 'questionCheckBox']) }}
													{{ Form::label($userplace[$key]) }}
												</div>
											@endforeach
										@endif
					            	</div>
					            </div>




								
               	{{ Form::close() }}	
    		</div>
    		<script>
				list = [];
      			var value = <?php echo json_encode($userplace ); ?>;
			</script>
		</div>
	</div>
</div>
</div>
@include('includes.js_create')
</body>
</html>