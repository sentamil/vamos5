@extends('includes.vdmEditHeader')
@section('mainContent')


<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		<div style="background-color: #c2d6d6" class="panel-heading" align="center">
                   		<h4 style="background-color: #c2d6d6" ><font size="5px" color="#293d3d" >Move Vehicle</font></h4>
                	</div>
                	<hr>
                	{{ HTML::ul($errors->all()) }}
                	{{ Form::open(array('url' => 'vdmVehicles/moveVehicle')) }}
                	<div class="panel-body">
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3">{{ Form::label('vehicleId', 'Asset Id / Vehicle Id :')  }}</div>
                			<div class="col-md-3"> {{ Form::text('vehicleId', $vehicleId, array('class' => 'form-control', 'required'=>'required','disabled' => 'disabled')) }}</div>
                		</div>
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3">{{ Form::hidden('vehicleIdOld', $vehicleId, array('class' => 'form-control')) }} 
                             {{ Form::hidden('expiredPeriodOld', $expiredPeriod, array('class' => 'form-control')) }}{{ Form::hidden('deviceIdOld', $deviceId, array('class' => 'form-control')) }} </div>
                			
                		</div>
                        <br>
                        <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">{{ Form::label('dealerId', 'Dealer Name :') }}</div>
                            <!--<div class="col-md-3">{{ Form::select('dealerId', array($dealerId),  Input::old('dealerId'),array('class'=>'form-control selectpicker show-menu-arrow', 'data-live-search '=> 'true')) }}</div>-->
							<div class="col-md-3">{{ Form::select( 'dealerId', $dealerId, array($OWN),  array( 'class'=>'form-control  selectpicker show-menu-arrow',  'data-live-search'=> 'true','selected' => $OWN)) }} </div>
                        </div>
                         <br>
                		 <!-- <br>
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3">{{ Form::label('deviceId', 'Device Id / IMEI No :') }}</div>
                			<div class="col-md-4">{{ Form::text('deviceId', $deviceId, array('class' => 'form-control')) }}</div>
                		</div>  -->
                		<br>
                		<div class="row">
                			<div class="col-md-2"></div>
                			<div class="col-md-3"></div><a class="btn btn-sm btn-primary" style="position: relative; right: 30%" href="{{ URL::previous() }}">Cancel</a>
                			<div class="col-md-4" style="position: relative; left: 10%">	{{ Form::submit('confirm', array('class' => 'btn btn-primary')) }}</div>
                		</div>
                	</div>
                	{{ Form::close() }}
                	
                </div>
            </div>
        </div>
    </div>
</div>

<div style="top: 0;  left: 0;right: 0; padding-top: 150px;" align="center">
	<hr>
	@stop</div>
