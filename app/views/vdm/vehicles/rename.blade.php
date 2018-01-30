@extends('includes.vdmEditHeader')
@section('mainContent')


<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		<div style="background-color: #20B2AA" class="panel-heading" align="center">
                   		<h4><font size="5px" color="white" >Vehicle Rename</font></h4>
                	</div>
                	<hr>
                	{{ HTML::ul($errors->all()) }}
                	{{ Form::open(array('url' => 'vdmVehicles/renameUpdate')) }}
                	<div class="panel-body">
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3">{{ Form::label('vehicleId', 'Asset Id / Vehicle Id :')  }}</div>
                			<div class="col-md-4"> {{ Form::text('vehicleId', $vehicleId, array('class' => 'form-control', 'required'=>'required','onkeyup' => 'caps(this)')) }}</div>
                		</div>
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3">{{ Form::hidden('vehicleIdOld', $vehicleId, array('class' => 'form-control')) }} 
                             {{ Form::hidden('expiredPeriodOld', $expiredPeriod, array('class' => 'form-control')) }}{{ Form::hidden('deviceIdOld', $deviceId, array('class' => 'form-control')) }} </div>
                			
                		</div>
						<script>
                         function caps(element){
                          element.value = element.value.toUpperCase();
                         }                         
                         </script>
                		 <!-- <br>
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3">{{ Form::label('deviceId', 'Device Id / IMEI No :') }}</div>
                			<div class="col-md-4">{{ Form::text('deviceId', $deviceId, array('class' => 'form-control')) }}</div>
                		</div>  -->
                		<br>
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3"></div>
                			<div class="col-md-4" style="position: relative; left: 10%">{{ Form::submit('Submit', array('class' => 'btn btn-success')) }}</div>
							<div class="col-md-3"><a class="btn btn btn-danger" style="position: relative; right: 70%" href="{{ URL::previous() }}">Cancel</a></div>
                		</div>
                	</div>
                	{{ Form::close() }}
                	
                </div>
            </div>
        </div>
    </div>
</div>

<div style="top: 0; left: 0;right: 0; padding-top: 150px;" align="center">
	<hr>
	@stop</div>
