@extends('includes.vdmEditHeader')
@section('mainContent')

<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		<div class="panel-heading" align="center">
                   		<h4><font>Edit Vehicle</font></h4>
                	</div>
                	<div class="panel-body">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmVehicles/updateCalibration')) }}
                		<hr>
                		<div class="row">
                			<div class="col-md-3"></div>
                			<div class="col-md-3">{{ Form::label('vehicleId', 'AssetID')  }}</div>
                			<div class="col-md-3">{{ Form::hidden('vehicleId', $vehicleId, array('class' => 'form-control')) }}{{ Form::text('vehicleId1', $vehicleId, array('class' => 'form-control','disabled' => 'disabled')) }}</div>
                		</div>
                		<br>
                		<div class="row">
                			<div class="col-md-3"></div>
                			<div class="col-md-3">{{ Form::label('deviceId', 'Device Id') }}</div>
                			<div class="col-md-3">{{ Form::text('deviceId', $deviceId, array('class' => 'form-control','disabled' => 'disabled')) }}</div>
                		</div>
                		<hr>
                		<div class="row">
                			<div class="col-md-3"></div>
                			<div class="col-md-3" style="color: #086fa1; text-align: center">{{ Form::label('volt', "Volt", array('disabled' => 'disabled')) }}</div>
                			<div class="col-md-3" style="color: #086fa1; text-align: center">{{ Form::label('litre', "Litre", array('disabled' => 'disabled')) }}</div>
                		</div>
                		@foreach($place as $key => $value)
                		<br/>
                		<div class="row">
                			<div class="col-md-3"></div>
                			<div class="col-md-3">{{ Form::text('volt'.$j++, isset(explode(":", $value)[0])?explode(":", $value)[0]:0,  array('class' => 'form-control')) }}</div>
                			<div class="col-md-3">{{ Form::text('litre', "Litre", array('class' => 'form-control','disabled' => 'disabled')) }}</div>
                		</div>
                		 @endforeach
                		<br>
                		<div class="row">
							<div class='col-md-6'></div>
							<div class='col-md-4' align="center">{{ Form::submit('Calibrate!', array('class' => 'btn btn-primary')) }}</div>
                			<div class='col-md-2'></div>
                		</div>
                		<hr>
                		{{ Form::close() }}
                		{{ Form::open(array('url' => 'vdmVehicles/calibrate/analog/')) }}
                		<div class="row">
                			<div class="col-md-3"></div>
                			<div class="col-md-2">{{ Form::label('tanksize', 'Tank Size') }}</div>
                			<div class="col-md-3">{{ Form::text('tanksize', $tanksize, array('class' => 'form-control')) }}</div>
                			<div class="col-md-2"> {{ Form::hidden('vehicleId', $vehicleId, array('class' => 'form-control')) }}{{ Form::submit('Calibrate Analog!', array('class' => 'btn btn-sm btn-success')) }}</div>
                		</div>
                		<hr>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div align="center">@stop</div>