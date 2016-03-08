@include('includes.header_index')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4> <b><font color="blue">Ahan ram </font></b></h4>
					</div>
					<div class="panel-body">
						{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'Business/batchSale')) }}
						<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6">
							<div class="col-sm-6"><div id="example2_filter" class="dataTables_filter"></div></div></div><div class="row">
							<div class="col-sm-12">

								<div class="form-group">

									<h4><font color="green">
										
										<br/>
										<br/>
                                                                               

	                            <table id="example1" class="table table-bordered dataTable">

	                            	<thead>

	                            		<tr style="text-align: center;font-size: 12px">

	                            			<th>Tag ID</th>
	                            			<th>Tag Name</th>
	                            			<th>Org Name</th>
	                            			<th>Belongs To</th>
	                            			<th>Swiped By</th>
	                            			<th>Action</th>

	                            		</tr>
	                            	</thead>
	                            	<tbody>
	                            		@if(isset($values))
	                            		@foreach($values as $key => $value)
	                            		<tr style="text-align: center;font-size: 12px">
	                            			<td>{{ $key }}</td>
	                            			<td>{{ array_get($tagnameList, $key)}}</td>
											<td>{{ array_get($orgList, $key)}}</td>
											<td>{{ array_get($belongsToList, $key)}}</td>
											<td>{{ array_get($swipevalueList, $key)}}</td>
	                            			<td>
                                                    <a class="btn btn-warning" href="{{ URL::to('rfid/destroy/'.$key) }}">Delete</a>
<a class="btn btn-warning" href="{{ URL::to('rfid/'.$key . '/edit') }}">Edit</a>
                                                    </td>
	                            		</tr>
	                            		@endforeach
	                            		@endif

	                            	</tbody>
	                            </table>


	                        </div>


	                    </div>



	                </div>
	            </div>
	        </div>
	        <br>
	       
	        <font color="blue">
	        	
	        		<br>
	        	</p>     
	    </div>
	</div>
	</div>
	@include('includes.js_index')
	</body>
	</html>