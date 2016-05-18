@extends('includes.vdmheader')
@section('mainContent')
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
		    <div class="col-lg-12">
		        <div class="hpanel">
		            <div class="panel-heading" align="center">
		               <h4><b>Showing DealerId</b></h4>
		            </div>
		            <div class="panel-body">
		            	<hr>
		            	<div class="row">
		            		<div class="col-md-2">{{ Form::label('adminName', 'Admin Name')  }}</div>
		            		<div class="col-md-4"><label class="form-control">{{$userId}}</label></div>
		            		<div class="col-md-2">{{ Form::label('mobileNo', 'Mobile No')  }}</div>
		            		<div class="col-md-4"><label class="form-control">{{$mobileNo}}</label></div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-2">{{ Form::label('email', 'Email')  }}</div>
		            		<div class="col-md-4"><label class="form-control">{{$email}}</label></div>
		            		<div class="col-md-2">{{ Form::label('website', 'Website')  }}</div>
		            		<div class="col-md-4"><label class="form-control">{{$website}}</label></div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-2">{{ Form::label('smsSender', 'Sms Sender')  }}</div>
		            		<div class="col-md-4"><label class="form-control">{{$smsSender}}</label></div>
		            		<div class="col-md-2">{{ Form::label('smsProvider', 'Sms Provider')  }}</div>
		            		<div class="col-md-4"><label class="form-control">{{$smsProvider}}</label></div>
		           		</div>
		            	<br>
		            	<div class="row">
		            		<div class="col-md-2">{{ Form::label('providerUserName', 'Provider Name')  }}</div>
		            		<div class="col-md-4"><label class="form-control">{{$providerUserName}}</label></div>
		            		<div class="col-md-2">{{ Form::label('providerPassword', 'Provider Password')  }}</div>
		            		<div class="col-md-4"><label class="form-control">{{$providerPassword}}</label></div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		
		            		<div class="col-md-4">
		            			{{Form::label('Image 272*144(png)')}}
		            			<img src='{{$imgMob}}' class="img-thumbnail"/>
		            		</div>
		            		<div class="col-md-4">
		            			{{Form::label('Image 144*144(png)')}}
		            			<img src='{{$imgLogo}}' class="img-thumbnail"/>
		            		</div>
		            		<div class="col-md-4">
		            			{{Form::label('Image 52*52(png)')}}
		            			<img src='{{$imgSmall}}' class="img-thumbnail"/>
		            		</div>
		            	</div>
		            	<br>
		            	<div class="row">
		            		
		            		
		            	</div><br>
		            	<div class="row">
		            		
		            		
		            	</div>
		            	<br>
		            	<div class="row">
		            		
		            		
		            	</div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</div>
<div style="top: 0; left: 0;right: 0; padding-top: 50px;" align="center">
	<hr>
	@stop</div>

