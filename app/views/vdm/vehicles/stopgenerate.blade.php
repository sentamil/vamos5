@extends('includes.vdmheader')
@section('mainContent')

<div style="background-color: #b3d9ff" class="panel-heading" align="center">
<h4 align="center"><font size="5px" >Generate Stops</font></h4>
</div>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmVehicles/generate')) }}

 
<div class="row" >   
	<div class="col-lg-12">
        <div style="background-color: #cce6ff" class="hpanel-body">
        <hr>  
        	<br />
        	<div class="row">
        		<div class="col-md-2"></div>
        		<div class="col-md-3" >{{ Form::label('vehicle', 'Vehicle Id') }}</div>
				<div class="col-md-4">{{ Form::label('vehicleId', $vehicleId,array('class' => 'form-control', 'disabled' => 'disabled')) }}{{ Form::hidden('vehicleId', $vehicleId) }}</div>
			</div>
        	<br />
        	<div class="row">
        		<div class="col-md-2"></div>
        		<div class="col-md-3">{{ Form::label('Date', 'Date') }}</div>
				<div class="col-md-4">{{ Form::text('date', Input::old('date'),array('placeholder' => 'dd-mm-yyyy','class' => 'form-control','required'=>'required')) }}</div>
			</div>
			<br />
			<div class="row">
				<div class="col-md-2"></div>
        		<div class="col-md-3">{{ Form::label('MST', 'Morning Start Time') }}</div>
				<div class="col-md-4">{{ Form::text('mst',   Input::old('mst'),array('placeholder' => 'hh:mm', 'class' => 'form-control','required'=>'required')) }}</div>

        	</div>
        	<br />
        	<div class="row">
        		<div class="col-md-2"></div>
        		<div class="col-md-3">{{ Form::label('MET', 'Morning End Time') }}</div>
        		<div class="col-md-4">{{ Form::text('met',   Input::old('met'),array('placeholder' => 'hh:mm','class' => 'form-control','required'=>'required')) }}</div>
        	</div>
        	<br />
        	<div class="row">
        		<div class="col-md-2"></div>
        		<div class="col-md-3">{{ Form::label('EST', 'Evening Start Time') }}</div>
        		<div class="col-md-4">{{ Form::text('est',   Input::old('est'),array('placeholder' => 'hh:mm','class' => 'form-control','required'=>'required')) }}</div>
        	</div>
        	<br />
        	<div class="row">
        		<div class="col-md-2"></div>
        		<div class="col-md-3">{{ Form::label('EET', 'Evening End Time') }}</div>
        		<div class="col-md-4">{{ Form::text('eet',   Input::old('eet'),array('placeholder' => 'hh:mm','class' => 'form-control','required'=>'required')) }}</div>
        	</div>
        	<br />
        	<div class="row">
        		<div class="col-md-2"></div>
        		<div class="col-md-3">{{ Form::label('spd', 'Speed') }}</div>
        		<div class="col-md-4">{{ Form::select('type', array( '0' => 'Strict', '5' => 'Medium','10' => 'Relax'), Input::old('type'), array('class' => 'form-control')) }}  </div>
        	</div>
        	<br />
        	<div class="row">
        		<div class="col-md-2"></div>
        		<div class="col-md-3">{{ Form::label('demo', 'Type') }}</div>
        		<div class="col-md-4">{{ Form::text('demo', $demo, array('class'=>'form-control', 'disabled' => 'disabled') ) }}</div>
        	</div>
        	<br/>
            <div class="row">
                <div class="col-md-5"></div>
                <div class="col-md-5">{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</div>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
<div align="center">
	<hr>
	@stop
</div>

