@extends('includes.vdmheader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
		    <div class="col-lg-12">
		        <div class="hpanel">
		            <div class="panel-heading" align="center">
		               <h4><b>Edit Dealer</b></h4>
		            </div>
		            <div class="panel-body">
		            	{{ HTML::ul($errors->all()) }}{{ Form::model($dealerid, array('route' => array('vdmDealers.update', $dealerid), 'method' => 'PUT')) }}
		            	<hr>
		            	<div class="row">
		            		<div class="col-md-3"></div>
		            		<div class="col-md-2">{{ Form::label('dealerid', 'Dealer Id * :') }}</div>
		            		<div class="col-md-4">{{ Form::label('dealerid', $dealerid, array('class' => 'form-control','required'=>'required')) }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-3"></div>
		            		<div class="col-md-2">{{ Form::label('mobileNo', 'MobileNo * :') }}</div>
		            		<div class="col-md-4">{{ Form::text('mobileNo', $mobileNo, array('class' => 'form-control', 'placeholder'=>'Mobile Number','required'=>'required')) }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-3"></div>
		            		<div class="col-md-2">{{ Form::label('email', 'Email * :') }}</div>
		            		<div class="col-md-4">{{ Form::email('email', $email, array('class' => 'form-control', 'placeholder'=>'Email','required'=>'required')) }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-3"></div>
		            		<div class="col-md-2">{{ Form::label('website', 'Website :') }}</div>
		            		<div class="col-md-4">{{ Form::text('website', $website, array('class'=>'form-control', 'placeholder'=>'Website'))  }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-3"></div>
		            		<div class="col-md-2">{{ Form::label('smsSender', 'Sms Sender  :') }}</div>
		            		<div class="col-md-4">{{ Form::text('smsSender', $smsSender, array('class' => 'form-control', 'placeholder'=>'Sms Sender')) }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-3"></div>
		            		<div class="col-md-2">{{ Form::label('smsProvider', 'SMS Provider :') }}</div>
		            		<div class="col-md-4">{{ Form::select('smsProvider',  array( $smsP), $smsProvider, array('class' => 'form-control')) }} </div>
		            	</div><br>
		            	<div class="row">
		            		<div class="col-md-3"></div>
		            		<div class="col-md-2">{{ Form::label('providerUserName', 'Provider UserName :') }}</div>
		            		<div class="col-md-4">{{ Form::text('providerUserName', $providerUserName, array('class' => 'form-control', 'placeholder'=>'Provider Name')) }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-3"></div>
		            		<div class="col-md-2">{{ Form::label('providerPassword', 'Provider Password :') }}</div>
		            		<div class="col-md-4">{{ Form::text('providerPassword', $providerPassword, array('class' => 'form-control', 'placeholder'=>'Provider providerPassword')) }}</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-lg-12" align="center">{{ Form::submit('Update the User!', array('class' => 'btn btn-primary')) }}</div>
		            	</div>
		            	{{ Form::close() }}
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</div>
<div style="top: 0; left: 0;right: 0; padding-top: 30px;" align="center"><hr>@stop</div>
