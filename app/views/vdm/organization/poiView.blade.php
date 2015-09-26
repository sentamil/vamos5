@extends('includes.vdmEditHeader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
                <div class="panel-heading">
                   Place of Interest  
                </div>
                <div class="panel-body">
                	<div class="row">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmOrganization/addpoi')) }}
  	              				<div class="row">
  	              				<div class="col-md-5">
  	              					{{ Form::label('orgId', 'organisation') }}
											
									{{ Form::label('orgId', $orgId, array('class' => 'form-control')) }}   
  	              				</div>
								<div class="col-md-3">
  	              					{{ Form::label('radius', 'Radius Range') }}
											{{ Form::label('radius', $radius, array('class' => 'form-control')) }}
  	              				</div>
                				<div class="col-md-3" style="text-align: right"><br>
                					<h4>{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</h4>
                				</div>
								</div>
								<hr> 
			              <table id="example1" class="table table-bordered dataTable"> 	
			              		<thead>
			                		<tr>
			                			<th>{{ Form::label('poi', ' Place of Interest:') }}</th>
			                		</tr>
			                	</thead>
			                	<tbody>	
								@if(isset($userplace))
									
								@foreach($userplace as $key => $value)
			                		<tr class="col-md-2">
			                			<td>
				                			
											{{ Form::label($value) }}
											
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