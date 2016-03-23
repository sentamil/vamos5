@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		 <div class="panel-heading">
                   		<h4><font color="blue"><b>Add a school / college / Organization</b></font></h4>
                	 </div>
                	<div class="panel-body">
                		<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                			<div class="row">
                				<div class="col-sm-12">

<!-- if there are creation errors, they will show here -->
	{{ HTML::ul($errors->all()) }}

	{{ Form::open(array('url' => 'vdmOrganization')) }}
	<div class="row">	
     <div class="col-md-6">
	<div class="form-group">
    <div class="col-md-6">   
    
       
    
        {{ Form::label('organizationId', 'School/College/Organization Id :')  }}
		</div>
								<div class="col-md-6">
        {{ Form::text('organizationId', Input::old('organizationId'), array('class' => 'form-control')) }}

    </div><br><br><br>
	
    <div class="col-md-6"> 
   
        {{ Form::label('description', 'Description :') }}
		</div>
								<div class="col-md-6">
        {{ Form::text('description', Input::old('[description'), array('class' => 'form-control')) }}

    </div><br><br><br>
	
    <div class="col-md-6"> 
        {{ Form::label('email', 'Email :') }}
		</div>
								<div class="col-md-6">
        {{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
    </div>
    <br><br><br>
     
    <div class="col-md-6"> 
        {{ Form::label('mobile', 'Mobile :') }}
		</div>
								<div class="col-md-6">
        {{ Form::text('mobile', Input::old('mobile'), array('class' => 'form-control')) }}
		
    </div>
	<br><br><br>
	
     <div class="col-md-6"> 
        {{ Form::label('mtc', 'Morning Trip Cron :') }}
		</div>
								  <div class="col-md-6"> 
        {{ Form::text('mtc', Input::old('mtc'), array('class' => 'form-control')) }}
		
    </div>
	<br><br><br>
	
      <div class="col-md-6"> 
        {{ Form::label('atc', 'After Trip Cron :') }}
		</div>
								  <div class="col-md-6"> 
        {{ Form::text('atc', Input::old('atc'), array('class' => 'form-control')) }}
		
    </div>
	<br><br><br>
    
    <div class="col-md-6"> 
        {{ Form::label('etc', 'Evening Trip Cron :') }}
		</div>
								<div class="col-md-6">
        {{ Form::text('etc', Input::old('etc'), array('class' => 'form-control')) }}
		
    </div><br><br><br>
	



								
								
						
								<div class="col-md-6">
		{{ Form::label('smsProvider', 'SMS Provider') }}
			</div><div class="col-md-6">
		 		{{ Form::select('smsProvider',  array( $smsP), Input::old('smsProvider'), array('class' => 'form-control')) }} 
			</div> <br><br><br>
			
							
								<div class="col-md-6">
		{{ Form::label('providerUserName', 'SMS Provider User Name') }}
		</div><div class="col-md-6">
		{{ Form::text('providerUserName', Input::old('providerUserName'), array('class' => 'form-control')) }}
		</div> <br><br><br>
	
	
								<div class="col-md-6">
		{{ Form::label('providerPassword', 'SMS Provider Password') }}</div>
		<div class="col-md-6">
		{{ Form::text('providerPassword', Input::old('providerPassword'), array('class' => 'form-control')) }}
		</div>
	

 <br><br><br>






    <div class="col-md-6"> 
        {{ Form::label('parkingAlert', 'Parking Alert :') }}
		</div>
								<div class="col-md-6">
         {{ Form::select('parkingAlert',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('parkingAlert'), array('class' => 'form-control')) }} 
		
    </div><br><br><br>
	
    <div class="col-md-6"> 
        {{ Form::label('idleAlert', 'Idle Alert :') }}
		</div>
								<div class="col-md-6">
         {{ Form::select('idleAlert',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('idleAlert'), array('class' => 'form-control')) }} 
		
    </div>
	<br><br><br>
	<div class="col-md-6"> 
        {{ Form::label('sosAlert', 'SOS Alert :') }}
		</div>
								<div class="col-md-6">
         {{ Form::select('sosAlert',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('sosAlert'), array('class' => 'form-control')) }} 
		
    </div>
	<br><br><br>
    <div class="col-md-6"> 
        {{ Form::label('overspeedalert', 'Over Speed Alert :') }}
		</div>
								<div class="col-md-6">
         {{ Form::select('overspeedalert',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('overspeedalert'), array('class' => 'form-control')) }} 
		
    </div>
	<br><br><br>
	
    <div class="col-md-6"> 
        {{ Form::label('sendGeoFenceSMS', 'Send GeoFence SMS :') }}
		</div>
								<div class="col-md-6">
         {{ Form::select('sendGeoFenceSMS',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('sendGeoFenceSMS'), array('class' => 'form-control')) }} 
		
    </div>
	<br><br><br>
   
    
	
    
    <div class="col-md-6"> 
        {{ Form::label('parkDuration', 'Park Duration in minutes :') }}
		</div>
								<div class="col-md-6">
        {{ Form::text('parkDuration', Input::old('parkDuration'), array('class' => 'form-control')) }}
		
    </div>
	<br><br><br>
	
    <div class="col-md-6"> 
        {{ Form::label('idleDuration', 'Idle Duration in minutes :') }}
		</div>
								<div class="col-md-6">
        {{ Form::text('idleDuration', Input::old('idleDuration'), array('class' => 'form-control')) }}
		
    </div>
	
	<br><br><br>
	<div class="col-md-6"> 
        {{ Form::label('smsSender', 'Sms Sender') }}
		</div>
								<div class="col-md-6">
        {{ Form::text('smsSender', Input::old('smsSender'), array('class' => 'form-control')) }}
		
    </div>
	
	<br><br><br>
	<div class="col-md-6"> 
        {{ Form::label('live', 'Show Live Site') }}
		</div>
								<div class="col-md-6">
        {{ Form::select('live',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('live'), array('class' => 'form-control')) }} 
		
    </div>
	<br><br><br>
	<div class="col-md-6"> 
        {{ Form::label('address', 'Address :') }}
		</div>
								<div class="col-md-6">
		<textarea rows="4" cols="40">
		</textarea>
    </div>
    
	<br><br><br>
	<div>
    <ul id="itemsort"> 
	<table><tr><td>{{ Form::label('startTime', 'Start Time :') }}</td>
	<td>{{ Form::label('endTime :', 'End Time') }}</td></tr>
	
	<tr><td>{{  Form::input('time', 'time1', null, ['class' => 'form-control', 'placeholder' => 'time'])}}</td>
	<td>{{  Form::input('time', 'time2', null, ['class' => 'form-control', 'placeholder' => 'time'])}}</td></tr></table>
	
	
    </ul></div>
 </div>
  
  
 <br><br><br>
 <right> {{ Form::submit('Add the School/College/Organization!', array('class' => 'btn btn-primary')) }}</right>

{{ Form::close() }}
@include('includes.js_create')
 </body>
</html>
