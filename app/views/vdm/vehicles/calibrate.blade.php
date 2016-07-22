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
                            <!-- {{Form::text('countAdd',Input::old('countAdd'), array('id'=>'countValue'))}} -->
                		</div>
                		<hr>
                		<div class="row">
                            <!-- <div class="col-md-12">as</div> -->
                			<div class="col-md-3"></div>
                			<div class="col-md-3" style="color: #086fa1; text-align: center">{{ Form::label('volt', "Volt", array('disabled' => 'disabled')) }}</div>
                			<div class="col-md-3" style="color: #086fa1; text-align: center">{{ Form::label('litre', "Litre", array('disabled' => 'disabled')) }}</div>
                           
                           

                        </div>
                        @foreach($place as $key => $value)
                		<br/>
                		<div class="row">
                			<div class="col-md-3"></div>
                			<div class="col-md-3">{{ Form::text('volt'.$j++, isset(explode(":", $value)[0])?explode(":", $value)[0]:0,  array('class' => 'form-control')) }}</div>
                			<div class="col-md-3">{{ Form::text( 'litre'.$i++,isset(explode(":", $value)[1])?explode(":", $value)[1]:0,array('class' => 'form-control') ) }}</div>
                		</div>
                		@endforeach
                		<br>
                		<div class="row">
							<div class='col-md-6'></div>
							<div class='col-md-4' align="center">{{ Form::submit('Calibrate!', array('class' => 'btn btn-primary')) }}</div>
                			<div class='col-md-2'></div>
                		</div>
                		

                            
                            

                		{{ Form::close() }}
                        <div class="row">
                           {{ Form::open(array('url' => 'vdmVehicles/calibrate/count')) }} 
                            <div class='col-md-3'></div>
                            <div class="col-md-2">{{ Form::Number('count_Calib', 0,array('class' => 'form-control','placeholder'=>'Count', 'min'=>'0', 'id'=>'counting')) }}</div>
                            {{Form::hidden('vehicleId', $vehicleId, array('class' => 'form-control')) }}
                            {{Form::hidden('listvalue', count($place), array('class' => 'form-control'))}}
                            <div class="col-md-2">{{ Form::submit('Add', array('class' => 'btn btn-sm btn-primary','id'=>'addcount')) }}</div>
                            {{ Form::close() }} 
                        </div>
                        
                        <hr>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
    // var count = 0;
    // $('#counting').change(function(){
    //     count =  this.value;
    //     $('#countValue').val(this.value);
    //     console.log(' value '+$('#countValue').val(this.value));
    // });
    // $('#addcount').click(function(){
    //     // count =  this.value;
    //     $('#countValue').val(3);
    //     console.log(' value '+$('#countValue').val(this.value));
    // });
</script>

<div align="center">@stop</div>