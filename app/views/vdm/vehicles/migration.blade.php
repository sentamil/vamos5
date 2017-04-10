@extends('includes.vdmEditHeader')
@section('mainContent')


<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		<div class="panel-heading" align="center">
                   		<h4><font>Vehicle Migration</font></h4>
                	</div>
                	<hr>
                	{{ HTML::ul($errors->all()) }}
                	{{ Form::open(array('url' => 'vdmVehicles/migrationUpdate')) }}
                	<div class="panel-body">
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3">{{ Form::label('vehicleId', 'Asset Id / Vehicle Id :')  }}</div>
                			<div class="col-md-4"> {{ Form::text('vehicleId', $vehicleId, array('class' => 'form-control', 'required'=>'required')) }}</div>
                		</div>
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3">{{ Form::hidden('vehicleIdOld', $vehicleId, array('class' => 'form-control')) }} 
                                {{ Form::hidden('expiredPeriodOld', $expiredPeriod, array('class' => 'form-control')) }}{{ Form::hidden('deviceIdOld', $deviceId, array('class' => 'form-control')) }}</div>
                			<div class="col-md-4"></div>
                		</div>
                		<br>
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3">{{ Form::label('deviceId', 'Device Id / IMEI No :') }}</div>
                			<div class="col-md-4">{{ Form::text('deviceId', $deviceId, array('class' => 'form-control','required'=>'required')) }}</div>
                		</div>
                		<br>
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3"></div>
                			<div class="col-md-4">	{{ Form::submit('Migrate the Vehicle!', array('class' => 'btn btn-primary')) }}</div>
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
