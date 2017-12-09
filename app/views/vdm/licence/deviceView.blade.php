@extends('includes.vdmEditHeader')
@section('mainContent')
<head>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css">

</head>
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading" align="center">
						<h4><font><b> Licence View  </b> </font></h4>
						<span  style="position: absolute; float:left; left:33px;z-index: 1;margin-top:22px;">
                            <a class="btn btn-sm btn-primary" href="{{ URL::previous() }}" style="margin-right: 38px;margin-top: -46px;">Go Back</a>
                        </span>
					</div>
					<div class="panel-body">
						

						{{ HTML::ul($errors->all()) }}
						
						{{ Form::model($valueT, array('route' => array('Licence.update', $valueT), 'method' => 'PUT')) }}
						<div class="form-group">
							
							
							
							<span id="error" style="color:red;font-weight:bold"></span>
							<div class="row">
								<div class="col-md-12">
									<hr>
                  
									<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
										<thead>
											<tr>
											@if($i=='1')
												<th style="text-align: center;">Select</th>
												@endif 
												<th style="text-align: center;">ID</th>
												<th style="text-align: center;">Vehicle ID</th>
												<th style="text-align: center;">Device ID</th>
												<th style="text-align: center;">Org Id</th>
												<th style="text-align: center;">Status</th>
												<th style="text-align: center;">On Boarded Date</th>
												<th style="text-align: center;">Renewed Date</th>
												
											</tr>
										</thead>
										<tbody>
											@if(isset($details))
											@foreach($details as $key => $val)
											<tr style="text-align: center;">
											@if($i=='1')<td>{{ Form::checkbox('vehicleList[]', $val->vehicle_id.';'.$val->payment_mode_id , null, ['class' => 'field']) }}</td>@endif 
												<td>{{ ++$key }}</td>
												<td class="vehiclelist">{{ $val->vehicle_id }}</td>
												<td>{{ $val->device_id  }}</td>
												<td>{{ $val->orgId  }}</td>
												<td>{{ $val->status }}</td>
												<td>{{ $val->sold_date }}</td>
												<td>{{ $val->renewal_date }}</td>
												<!-- <td>
													
													
													
												</td> -->

											</tr>
											@endforeach
											@endif

										</tbody>




									</table>
								</div>
							</div> 
						</div>
		
						@if($i=='1')
						{{ Form::hidden('tempVal', $valueT) }}
            {{ Form::submit('renewal', array('class' => 'btn btn-sm btn-info')) }}

            @endif 

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js
"></script>

<script type="text/javascript">
  

$(document).ready(function() {
    $('#example').DataTable();
} );




</script>
<div style="top: 0; left: 0;right: 0; padding-top: 30px;" align="center"><hr>@stop</div>
</body>
</html>