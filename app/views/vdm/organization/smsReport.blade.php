@extends('includes.vdmEditHeader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
                <div class="panel-heading">
                   SMS Report 
                </div>
                <div class="panel-body">
                	<div class="row">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmOrganization/addpoi')) }}
  	              				<div class="row">
  	              				
								
                				
								</div>
								<hr> 
			              <table id="example1" class="table table-bordered dataTable"> 	
			              		<thead>
			                		<tr>
			                			<th>{{ Form::label('report', ' SMS Report:') }}</th>
			                		</tr>
			                	</thead>
			                	<tbody>	
								@if(isset($details))
									
								@foreach($details as $key => $value)
			                		<tr class="col-md-2">
			                			<td>
				                			
											{{ Form::label($key) }}
											</br>
											@foreach($value as $key1 => $value1)
											{{ Form::label($key1) }} :
											{{ Form::label($value1) }}
											</br>
											@endforeach
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