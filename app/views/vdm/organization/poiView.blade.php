@extends('includes.vdmEditHeader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
                <div class="panel-heading">
                   <h3><b><font color="blue">View Place Of Interest</font></b></h3>
                </div>
                <div class="panel-body">
                	<div class="row">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmOrganization/addpoi')) }}
  	              				<div class="row">
  	              				<div class="col-md-3">
  	              					{{ Form::label('orgId', 'Organisation') }}
											
									{{ Form::label('orgId', $orgId, array('class' => 'form-control')) }}   
  	              				</div>
								<div class="col-md-3">
  	              					{{ Form::label('radius', 'Radius Range') }}
											{{ Form::label('radius', $radius, array('class' => 'form-control')) }}
  	              				</div>
                				
								</div>
								<hr> 
			              <table id="example1" class="table table-bordered dataTable"> 	
			              	<!--	<thead>
			                		<tr>
			                			<th>{{ Form::label('poi', ' Place of Interest:') }}</th>
			                		</tr>
			                	</thead>
			                	<tbody>	-->
								<h4><font color="green">Place Of Interest :</h4>
								@if(isset($userplace))
									
								@foreach($userplace as $key => $value)
			                		<tr class="col-md-3">
			                			<td>
				                			
											{{ Form::label($userplace[$key]) }}
											
											@endforeach
			                			</td>	
			                		</tr>
								
								@endif
			                		
               					</tbody>
                		</table>
               	{{ Form::close() }}	
    		</div>
		</div>
	</div>
</div>
</div>

</body>
</html>
@stop