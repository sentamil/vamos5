@extends('includes.vdmEditHeader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
	                <div class="panel-heading">
	                   <h4 align="center"><b><font>Edit Alerts</font></b></h4>
	                </div>
	                <div class="panel-body">
	                	<hr> 
	                	<div class="row">
	                		{{ HTML::ul($errors->all()) }}
							{{ Form::open(array('url' => 'vdmOrganization/updateNotification')) }}
	  	              			<div class="row">
	  	              				<div class="col-md-3"></div>
	  	              				<div class="col-md-3">
	  	              					{{ Form::label('orgId1', 'Organisation') }}
										{{ Form::label('orgId1', $orgId, array('class' => 'form-control')) }}  
										{{ Form::hidden('orgId', $orgId, array('class' => 'form-control')) }} 
	  	              				</div>
									
	                				
								</div>
									
				             	<div class="row">
				             		<div class="col-md-2"></div>
									
								</div>
								














<table id="example1" class="table table-bordered dataTable">
               		 <thead>
						<tr>
							<th style="text-align: center;">S.NO</th>
							<!--  @if(Session::get('cur')=='admin')
							<th style="text-align: center;">Select</th>
							 @endif  -->
							<th style="text-align: center;">Alerts </th>
							<th style="text-align: center;">EMail</th>
							<th style="text-align: center;">SMS</th>
							
						</tr>
					</thead>
					<tbody>
					@foreach($alertList as $key => $value)
						<tr style="text-align: center;">
							<td>{{ ++$tmp }}</td>
							<td>{{ $value }}</td>
				<td>{{ Form::checkbox('emailList[]', $value,  in_array($value,$emailArray), ['class' => 'field']) }}</td>
				<td>{{ Form::checkbox('smsList[]', $value,  in_array($value,$smsArray), ['class' => 'field']) }}</td>
							
			 
						</tr>
						@endforeach
						
						
<div class="col-md-3">{{ Form::submit('Update Alerts', array('class' => 'btn btn-primary')) }}</div>
							<script>




  function ConfirmDelete()
  {
  var x = confirm("Confirm to remove?");
  if (x)
    return true;
  else
    return false;
  }

</script>
					</tbody>
                </table>

















	               			{{ Form::close() }}	
	    				</div>
	    			</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="top: 0; left: 0;right: 0; padding-top: 30px;" align="center"><hr>@stop</div>
