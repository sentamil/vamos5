@include('includes.header_create') <!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4><font><b>Road SpeedLimit</b></font></h4>
					</div>
					<div class="panel-body">
					{{ Form::open(array('url' => 'vdmBusRoutes/_speedRange')) }}
					{{ HTML::ul($errors->all()) }}
						<!-- <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"> -->
						<div class="form-group">
							
							<div class="col-lg-3">
								{{ Form::text('roadName', Input::old('roadName'),array('class' => 'form-control','placeholder'=>'RoadName', 'required' => 'required'))  }}
							</div>
							
							<div class="col-lg-2">
								{{ Form::Number('speed', Input::old('speed'),array('class' => 'form-control','placeholder'=>'SpeedLimit', 'required' => 'required', 'min'=>0))  }}
							</div>

							<div class="col-lg-2">
								{{ Form::text('fromlatlng', Input::old('fromlatlng'),array('class' => 'form-control','placeholder'=>'From latlng', 'required' => 'required'))  }}
								<div style="font-size: 9px; text-align: center;">seperate lat lng by comma (lat,lng)</div>
							</div>

							<div class="col-lg-2">
								{{ Form::text('tolatlng', Input::old('tolatlng'),array('class' => 'form-control','placeholder'=>'To latlng', 'required' => 'required'))  }}
								<div style="font-size: 9px; text-align: center;">seperate lat lng by comma (lat,lng)</div>
							</div>
							<div class="col-lg-3">
								{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
							</div>	
						</div>
						
						<div class="clearfix"></div>

						<!-- <div class="row"><br><hr></div> -->
						<div class="col-lg-12">
							<table class="table table-striped table-condensed table-hover">
								@if(isset($roadSpeedValue))
	                            	@foreach($roadSpeedValue as $key => $value)
									<tr>
										<th colspan="3">{{$key}}</th>
										<!-- <th><a href=""><img src="../assets/imgs/wedit.png" style="width: 14px; height: 14px" class="edit"></th> -->
										<th><a href="{{ URL::to('vdmBusRoutes/Range/'. $key) }}"><img src="../assets/imgs/wdel.png" style="width: 14px; height: 14px" class="delete"></th>
									</tr>
									@foreach($value as $keys => $values)
									<tr>
										<!-- <td>{{array_get($values, $key+1)}}</td> -->
										<td>{{explode (':' ,$values )[0]}}</td>
										<td>{{explode (':' ,$values )[1]}}</td>
										<td>{{explode (':' ,$values )[2]}}</td>
										<!-- <td><a href=""><img src="../assets/imgs/wedit.png" style="width: 14px; height: 14px" class="edit"></td> -->
										<td><a href="{{ URL::to('vdmBusRoutes/Range/'. $key.':'.$values) }}"><img src="../assets/imgs/wdel.png" style="width: 14px; height: 14px" class="delete"></td>
									</tr>
									@endforeach
									@endforeach
	                        	@endif
							</table>
						</div>

					{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include('includes.js_create')
</body>
</html>

