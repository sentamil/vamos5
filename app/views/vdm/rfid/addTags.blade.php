@extends('includes.vdmheader')
@section('mainContent')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4><font color="blue"><b>Tags Create </b></font><h4> 
						</div>
						<div class="panel-body">
							<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<div class="row">
									<div class="col-sm-12">
										{{ HTML::ul($errors->all()) }}
										{{ Form::open(array('url' => 'rfid/addTags')) }}
										<div class="row">
											<div class="col-md-12">
												<div class="col-md-6">
													<div class="row">
<div>Org Name</div>
<div>{{ Form::select('org' , $orgList, Input::old('orgname'),array('id' => 'orgid')) }}</div>
<div>{{ Form::label('ahan', $ahan) }}</div>
<div><input id='ahan' type='text'></input></div>
<div>Belongs To</div>
<div>{{ Form::select('belongsTo', $vehList, Input::old('belongsTo'),array('id' => 'belongsTo')) }}</div>

<div>Swiped by</div>

<div>{{Form::select('swipedBy', $vehRfidYesList, array('swipedBy'), array('multiple','name'=>'sports'.'[]','id' => 'swi')

)}}</div>
														<table id="example1" class="table table-bordered dataTable">
															<thead>
																<tr>
																	<th style="text-align: center;">No</th>
																	<th style="text-align: center;">Tag Id</th>
																	<th style="text-align: center;">Tag Name</th>
																	
																	<!-- <th style="text-align: center;">Belongs To</th>
																	<th style="text-align: center;">Swiped by</th> -->
																</tr>
															</thead>
															<tbody>

							




																@for($i=1;$i<=$tags;$i++)
																<tr style="text-align: center;">
																	<td>{{ $i }}</td>
																	<td>{{ Form::text('tagid'.$i, Input::old('tagid')) }}</td>
																	<td>{{ Form::text('tagname'.$i, Input::old('tagname')) }}</td>
																	<!-- <td>{{ Form::select('belongsTo' .$i, $vehList, Input::old('belongsTo'),array('id' => 'useri')) }}</td>

																	<td>{{Form::select('swipedBy'.$i, $vehRfidYesList, array('swipedBy'.$i), array('multiple','name'=>'sports'.$i.'[]'))}}</td> -->
																</tr>
																@endfor

															</tbody>
														</table>



													</div>
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-6">	
														</div>
														<div class="col-md-6">
															{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}{{ Form::close() }}
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
			</div>
			
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>


<script type="text/javascript" >
	

 $(document).ready(function() {
        $('#orgid').on('change', function() {
            var data = {
                'id': $(this).val()
                
            };
            console.log('ahan'+data);
            $.post('{{ route("ajax.user_select") }}', data, function(data, textStatus, xhr) {
                /*optional stuff to do after success */
                console.log('ahan ram'+data.rfidlist);
              //  $('#belongsTo').val(data.vehicle);
              $('#belongsTo').empty();
			$.each(data.vehicle, function(key, value) {   
			     $('#belongsTo')
			         .append($("<option></option>")
			         .attr("value",key)
			         .text(value)); 
			});
			$('#swi').empty();
			$.each(data.rfidlist, function(key, value) {   
			     $('#swi')
			         .append($("<option></option>")
			         .attr("value",key)
			         .text(value)); 
			});
              //  $('#email').html(data.email);
            });
        });
    });

</script>


		</body>
		</html>