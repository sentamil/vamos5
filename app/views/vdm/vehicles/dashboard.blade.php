@include('includes.header_index')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<!-- <div class="panel-heading"> -->
					<h4><b> Dash Board </b></h4>
					<div class="panel-body">


							
						<div id="example2_wrapper">
							<!-- <div class="col-sm-6">
								<div id="example2_filter" class="dataTables_filter"></div>
							</div> -->
							<div class="row">
								<div style="color: #086fa1">
									<div class="col-sm-6">{{ Form::label('tnovo', 'Total Vehicles OnBoard :') }}</div>
									<div class="col-sm-6">{{ Form::label('count', $count.' ',array('class' => 'form-control')) }}</div>
									<!-- <div class="col-sm-6">{{ Form::label('vechileEx', 'Number of Vehicles Expires Next Month :') }}</div>
									<div class="col-sm-6">{{ Form::label('0', count($vechile), array('class' => 'form-control')) }}</div> -->
									<!-- <div class="col-sm-6">{{ Form::label('num', 'Onboarded Details') }}</div> -->
									<div class="col-sm-6">{{Form::label('', 'Previous Month :')}}</div>
									<div class="col-sm-6">{{ Form::label('0', $prevMonthCount, array('class' => 'form-control')) }}</div>
									<div class="col-sm-6">{{Form::label('', 'Present Month :')}}</div>
									<div class="col-sm-6">{{ Form::label('0', $prsentMonthCount, array('class' => 'form-control')) }}</div>
									<div class="col-sm-6">{{Form::label('', 'Next Month :')}}</div>
									<div class="col-sm-6">{{ Form::label('0', $nextMonthCount, array('class' => 'form-control')) }}</div>
								</div>
								<div class="col-sm-12">
									<hr>
									<div class="col-sm-12">
										<div  style="color: #086fa1"> {{ Form::label('vechileEx', 'Vehicles Details :') }}</div>
										<table id="example1" class="table table-bordered dataTable">
											<thead>
												<font>

													<tr>
														<th style="text-align: center;">VEHICLE ID</th>
													</tr>
												</font>

											</thead>
											<tbody>
												@if(isset($vechile))
												@foreach($vechile as $key => $value)
												<tr style="text-align: center;">
													<td>{{ Form::label('li', $key) }}</td>
												</tr>
												@endforeach
											</tbody>
											@endif
										</table>
									</div>
									<div class="col-sm-12">
										<hr>
										<div class="col-sm-12" style="color: #086fa1">
											<div class="col-sm-6">{{ Form::label('vehicles', 'Vehicles OnBoard with each Dealers : ') }}</div>
											<div class="col-sm-6">{{ Form::label('Vehicles Expired :', $vechileEx) }}</div>
										</div>
										<div class="col-sm-6">
											<table id="example1" class="table table-bordered dataTable">
												<thead>
													<tr>
														<th style="text-align: center;">DEALER ID</th>
														<th style="text-align: center;">NUMBER OF VEHICLES</th>
													</tr>
												</thead>
												<tbody>
													@if(isset($dealerId))
													@foreach($dealerId as $key => $value)
													<tr style="text-align: center; font-size: 11px;">
														<td> {{ Form::label('li', $key) }}</td>
														<td> {{ Form::label('l',$value .' '.' ') }}</td>
													</tr>
													@endforeach
												</tbody>
												@endif
											</table>
										</div>
										<div class="col-sm-6">
											<!-- {{ Form::label('vechileEx1', $vechileEx1) }} -->
											<!-- </div> -->
											<table id="example1" class="table table-bordered dataTable">
												<thead>
													<tr>
														<th style="text-align: center;">DEALER ID</th>
														<th style="text-align: center;">NUMBER OF VEHICLES</th>
													</tr>
												</thead>
												<tbody>
													@if(isset($temp))
													@foreach($temp as $key => $value)
													<tr style="text-align: center; font-size: 11px">
														<td> {{ Form::label('li', $key) }}</td>
														<td> {{ Form::label('l', $value .'  '.' ') }}</td>
													</tr>
													@endforeach
												</tbody>
												@endif
											</table>
											{{ Form::close() }}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('includes.js_index')
</body>
</html>

