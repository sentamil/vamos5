@include('includes.header_create') <!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4><font><b>Create Stops</b></font></h4>
					</div>
					<div class="panel-body">
						<!-- <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"> -->

						<div class="form-group">
							
								{{ HTML::ul($errors->all()) }}

								{{ Form::open(array('url' => 'vdmBusRoutes')) }}

				                <div class="form-group">
									{{ Form::label('OrgId', 'Org Id') }}
									{{ Form::select('orgId', $orgList,Input::old('orgId'),array('class' => 'form-control selectpicker show-menu-arrow', 'data-live-search '=> 'true','required'=>'required')) }}
								</div>
								
								<div class="form-group">
									{{ Form::label('routeId', 'Bus Route Number') }}
									{{ Form::text('routeId', Input::old('routeId'), array('class' => 'form-control', 'placeholder'=>'Bus Route Number','required'=>'required')) }}
								</div>
								
								<div class="form-group">
									{{ Form::label('morningSeq', 'Morning Sequence') }}
									{{ Form::text('morningSeq', Input::old('morningSeq'), array('class' => 'form-control', 'placeholder'=>'Morning Sequence')) }}
								</div>
								
								<div class="form-group">
									{{ Form::label('eveningSeq', 'Evening Sequence') }}
									{{ Form::text('eveningSeq', Input::old('eveningSeq'), array('class' => 'form-control', 'placeholder'=>'Evening Sequence')) }}
								</div>
								
								<div class="form-group">
									{{ Form::label('stops', 'Bus Stops') }}
									{{ Form::textarea('stops', '', array('class' => 'form-control', 'placeholder'=>'Bus Stops','required'=>'required')) }}
								</div>
								
								<div class="form-group">
									
									{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
								</div>
								{{ Form::close() }}
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('includes.js_create')
</body>
</html>

