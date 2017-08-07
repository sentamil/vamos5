@include('includes.header_create')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4> <b>Tags List</b></h4>
					</div>
					<div class="panel-body">
						{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'Business/batchSale')) }}
						<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<div class="row">
								<!-- <div class="col-sm-6">
									<div class="col-sm-6">
										<div id="example2_filter" class="dataTables_filter"></div>
									</div>
							</div> 
						<div class="row">-->
							<div class="col-sm-12">

								<!-- <div class="form-group"> -->

									                                 

	                            <table id="example1" class="table table-bordered dataTable">

	                            	<thead>

	                            		<tr style="text-align: center;font-size: 12px">

	                            			<th>Tag ID</th>
	                            			<th>Tag Name</th>
	                            			<th>Mobile Number</th>

	                            			<th>Org Name</th>
	                            			<th>Action</th>

	                            		</tr>
	                            	</thead>
	                            	<tbody>
	                            		@if(isset($values))
	                            		@foreach($values as $key => $value)
	                            		<tr style="text-align: center;font-size: 12px">
	                            			<td>{{ $key }}</td>
	                            			<td>{{ array_get($tagnameList, $key)}}</td>
											<td>{{ array_get($mobileList, $key)}}</td>
											<td>{{ $orgIdUi}}</td>
	                            			<td>
<a class="btn btn-success" href="{{ URL::to('rfid/'.$key.';'.$orgIdUi.'/edit') }}">Edit</a>
<a class="btn btn-danger" href="{{ URL::to('rfid/'.$key.';'.$orgIdUi.'/destroy') }}">Delete</a>
                                                    </td>
	                            		</tr>
	                            		@endforeach
	                            		@endif

	                            	</tbody>
	                            </table>


	                       <!--  </div> -->


	                  <!--   </div> -->



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
	@include('includes.js_create')
	</body>
	</html>