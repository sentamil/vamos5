@extends('includes.vdmEditHeader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
                <div class="panel-heading">
                   <h4><font color="blue"><b>SMS Report</b></font></h4> 
                </div>
                <div class="panel-body">
                	<div class="row">
					<div class="col-md-9">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmOrganization/addpoi')) }}
  	              				<div class="row">
  	              				<div class="col-md-9">
					
								<hr> 
			              <table id="example1" class="table table-bordered dataTable"> 	
			              		<thead>
			                		
			                			<td>{{ Form::label('report', ' SMS Report:') }}</td>
			                		
			                	</thead>
								
											                	<tbody>	
																
								@if(isset($details))
									
								@foreach($details as $key => $value)
								
			                		<tr class="col-md-5">
				                			
											<th>{{ Form::label($key) }}</th>
											
											@foreach($value as $key1 => $value1)
											<th>{{ Form::label($key1) }} :
											{{ Form::label($value1) }} </th>
											
											@endforeach
											@endforeach
			                				
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