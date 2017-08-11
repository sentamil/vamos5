@extends('includes.vdmEditHeader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
	                <div class="panel-heading">
	                   <h4 align="center"><b><font> Site Notification</font></b></h4>
	                </div>
	                <div class="panel-body">
	                	<hr> 
	                	<div class="row">
	                		{{ HTML::ul($errors->all()) }}
							{{ Form::open(array('url' => 'vdmOrganization/siteUpdate')) }}
	  	              			<div class="row">
	  	              				<div class="col-md-3"></div>
	  	              				<div class="col-md-3">
	  	              					{{ Form::label('orgId1', 'Organisation Name') }}
										{{ Form::label('orgId1', $orgId, array('class' => 'form-control')) }}  
										{{ Form::hidden('orgId', $orgId, array('class' => 'form-control')) }} 
	  	              				</div>
									<a class="btn btn-sm btn-primary" style="position: relative; right: 50%" href="{{ URL::previous() }}">Go Back</a>
	                				
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
							<th style="text-align: center;">Site Name</th>
							<th style="text-align: center;">Time</th>
							<th style="text-align: center;">Enable</th>
							
						</tr>
					</thead>
					<tbody>
					@for ($i=0;$i<$counts;$i++)
						<tr style="text-align: center;">
							<td>{{ ++$tmp }}</td>
							<td>{{ $alertList[$i] }}</td>
				<td>{{ Form::select('time[]', $time, $times[$i], array('class' => 'form-control')) }}</td>
				<td>{{ Form::select('enable[]', $enable, $enables[$i], array('class' => 'form-control')) }}</td>
						</tr>
					@endfor
					<!--@foreach($alertList as $key => $value)
						<tr style="text-align: center;">
							<td>{{ ++$tmp }}</td>
							<td>{{ $value }}</td>
				<td>{{ Form::select('time[]', $time,  in_array($value,$time), array('class' => 'form-control')) }}</td>
				<td>{{ Form::select('enable[]', $enable,  in_array($value,$time), array('class' => 'form-control')) }}</td>
						</tr>
						@endforeach
						-->
						
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
<div class="col-md-4" style="position: relative; left: 80%" >{{ Form::submit('Update Site', array('class' => 'btn btn-primary')) }}</div>
















	               			{{ Form::close() }}	
	    				</div>
	    			</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="top: 0; left: 0;right: 0; padding-top: 30px;" align="center"><hr>@stop</div>
