@extends('includes.vdmheader')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading" align="center">
						<h4><font><b>Road SpeedLimit</b></font></h4>
					</div>
					<hr>
					<div class="panel-body">
					{{ Form::open(array('url' => 'vdmBusRoutes/editSpeed')) }}

					{{ HTML::ul($errors->all()) }}
						<!-- <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"> -->
						<div class="form-group" >
							<!-- <div class="col-lg-2"></div> -->
							<div class="col-lg-12">
								{{ Form::text('roadName',$roadName,array('class' => 'form-control','placeholder'=>'RoadName', 'required' => 'required'))  }}
							<br>
							</div>
							
							<div class="col-lg-12">
								{{ Form::Number('speed',$speed,array('class' => 'form-control','placeholder'=>'SpeedLimit', 'required' => 'required', 'min'=>0))  }}
								<br>
							</div>

							<div class="col-lg-12">
								{{ Form::text('fromlatlng', $fromlatlng,array('class' => 'form-control','placeholder'=>'Start latlong', 'required' => 'required'))  }}
								<div style="font-size: 11px; ">seperate lat lng by comma (lat,lng)</div>
								<br>
							</div>

							<div class="col-lg-12">
								{{ Form::text('tolatlng' ,$tolatlng,array('class' => 'form-control','placeholder'=>'End latlong', 'required' => 'required'))  }}
								<div style="font-size: 11px; ">seperate lat lng by comma (lat,lng)</div>
								<br>
							</div>
							<div class="col-lg-12">
								{{ Form::submit('Submit', array('class' => 'btn btn-primary ')) }}
							</div>
							<br>	
						</div>
						
						
					{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


</body>
</html>

