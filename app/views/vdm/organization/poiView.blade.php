@extends('includes.vdmEditHeader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
	                <div class="panel-heading">
	                   <h4 align="center"><b><font>View Place Of Interest</font></b></h4>
	                </div>
	                <div class="panel-body">
	                	<hr> 
	                	<div class="row">
	                		{{ HTML::ul($errors->all()) }}
							{{ Form::open(array('url' => 'vdmOrganization/addpoi')) }}
	  	              			<div class="row">
	  	              				<div class="col-md-3"></div>
	  	              				<div class="col-md-3">
	  	              					{{ Form::label('orgId', 'Organisation') }}
										{{ Form::label('orgId', $orgId, array('class' => 'form-control')) }}   
	  	              				</div>
									<div class="col-md-3">
	  	              					{{ Form::label('radius', 'Radius Range') }}
												{{ Form::label('radius', $radius, array('class' => 'form-control')) }}
	  	              				</div>
	                				
								</div>
									
				             	<div class="row">
				             		<div class="col-md-2"></div>
									<div class="col-md-3"><h4><font color="#086fa1">Place Of Interest :</h4></div>
								</div>
								<div class="row">
									@if(isset($userplace))
										@foreach($userplace as $key => $value)
					                		<div class="col-md-3">{{ Form::label($userplace[$key]) }}</div>
										@endforeach
				                	@endif
				                </div>
	               			{{ Form::close() }}	
	    				</div>
	    			</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="top: 0; left: 0;right: 0; padding-top: 30px;" align="center"><hr>@stop</div>
