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

						<div class="row">
							<div class="col-sm-12">
								{{ HTML::ul($errors->all()) }}

								{{ Form::open(array('url' => 'vdmBusRoutes')) }}

				                <div class="row">
									<div class="col-md-3">{{ Form::label('OrgId', 'Org Id') }}</div>
									<div class="col-md-6">{{ Form::select('orgId', $orgList,Input::old('orgId'),array('class' => 'form-control','required'=>'required')) }}</div>
								</div>
								<br />
								<div class="row">
									<div class="col-md-3">{{ Form::label('routeId', 'Bus Route Number') }}</div>
									<div class="col-md-6">{{ Form::text('routeId', Input::old('routeId'), array('class' => 'form-control', 'placeholder'=>'Bus Route Number','required'=>'required')) }}</div>
								</div>
								</br>
								<div class="row">
									<div class="col-md-3">{{ Form::label('morningSeq', 'Morning Sequence') }}</div>
									<div class="col-md-6">{{ Form::text('morningSeq', Input::old('morningSeq'), array('class' => 'form-control', 'placeholder'=>'Morning Sequence')) }}</div>
								</div>
								</br>
								<div class="row">
									<div class="col-md-3">{{ Form::label('eveningSeq', 'Evening Sequence') }}</div>
									<div class="col-md-6">{{ Form::text('eveningSeq', Input::old('eveningSeq'), array('class' => 'form-control', 'placeholder'=>'Evening Sequence')) }}</div>
								</div>
								</br>
								</br>
								<div class="row">
									<div class="col-md-3">{{ Form::label('stops', 'Bus Stops') }}</div>
									<div class="col-md-6">{{ Form::textarea('stops', '', array('class' => 'form-control', 'placeholder'=>'Bus Stops','required'=>'required')) }}</div>
								</div>
								</br>
								<div class="row">
									<div class="col-md-3"></div>
									<div class="col-md-6">{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</div>
								</div>
								{{ Form::close() }}
							</div>
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

