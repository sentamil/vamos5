@extends('includes.vdmheader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
		    <div class="col-lg-12">
		        <div class="hpanel">
		            <div class="panel-heading" align="center">
		               <h4><b>Showing UserId</b></h4>
		            </div>
		            <div class="panel-body">
		            	<hr>
		            	<div class="row">
		            		<div class="col-md-3"></div>
		            		<div class="col-md-2">{{ Form::label('userName', 'User Name')  }}</div>
		            		<div class="col-md-4">{{ Form::label('userName', $userId, array('class'=>'form-control'))  }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-3"></div>
		            		<div class="col-md-2">{{ Form::label('mobileNo', 'Mobile No')  }}</div>
		            		<div class="col-md-4">{{ Form::label('mobileNo', $mobileNo, array('class'=>'form-control'))  }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-3"></div>
		            		<div class="col-md-2">{{ Form::label('vehicleGroups', 'Vehicle Groups')  }}</div>
		            		<div class="col-md-4">{{$vehicleGroups}}</div>
		            	</div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</div>
<div style="top: 0; left: 0;right: 0; padding-top: 150px;" align="center">
	<hr>
	@stop</div>
