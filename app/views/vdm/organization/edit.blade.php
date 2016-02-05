@extends('includes.vdmheader')
@section('mainContent')
<h2><font color="blue">Add a school / college / Organization</font></h2>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($organizationId, array('route' => array('vdmOrganization.update', $organizationId), 'method' => 'PUT')) }}
<div class="row">
    <div class="col-md-4">   
    
       
    <div class="form-group">
        {{ Form::label('organizationId', 'School/College/Organization Id :')  }}
        {{ Form::text('organizationId', $organizationId, array('class' => 'form-control','disabled' => 'disabled')) }}

    </div>
   <div class="form-group">
        {{ Form::label('description', 'Description') }}
        {{ Form::text('description', $description, array('class' => 'form-control')) }}

    </div>
	<div class="form-group">
        {{ Form::label('etc', 'Evening Trip Cron') }}
        {{ Form::text('etc', $etc, array('class' => 'form-control')) }}
		
    </div>
	<div class="form-group">
        {{ Form::label('parkingAlert', 'Parking Alert') }}
      
		 {{ Form::select('parkingAlert',  array( 'no' => 'No','yes' => 'Yes' ), $parkingAlert, array('class' => 'form-control')) }} 
		
    </div>
	<div class="form-group">
        {{ Form::label('idleAlert', 'Idle Alert') }}
		 {{ Form::select('idleAlert',  array( 'no' => 'No','yes' => 'Yes' ), $idleAlert, array('class' => 'form-control')) }} 
		
    </div>
	<div class="form-group">
        {{ Form::label('sosAlert', 'SOS Alert') }}
		 {{ Form::select('sosAlert',  array( 'no' => 'No','yes' => 'Yes' ), $sosAlert, array('class' => 'form-control')) }} 
		
    </div>
	
	<div class="form-group">
        {{ Form::label('overspeedalert', 'Over Speed Alert') }}
		 {{ Form::select('overspeedalert',  array( 'no' => 'No','yes' => 'Yes' ), $overspeedalert, array('class' => 'form-control')) }} 
		
    </div>
	<div class="form-group">
        {{ Form::label('sendGeoFenceSMS', 'Send GeoFence SMS') }}
         {{ Form::select('sendGeoFenceSMS',  array( 'no' => 'No','yes' => 'Yes' ), $sendGeoFenceSMS, array('class' => 'form-control')) }} 
		
    </div>
    <div class="form-group">
        {{ Form::label('address', 'Address') }}
        {{ Form::textarea('address', $address, array('class' => 'form-control')) }}
    </div>
    
    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::text('email', $email, array('class' => 'form-control')) }}
    </div>
    
    
    </div>
    <div class="col-md-4">
    
      <div class="form-group">
        {{ Form::label('mobile', 'Mobile') }}
        {{ Form::text('mobile', $mobile, array('class' => 'form-control')) }}
    </div>
	
	<div class="form-group">
        {{ Form::label('mtc', 'Morning Trip Cron') }}
        {{ Form::text('mtc', $mtc, array('class' => 'form-control')) }}
		
    </div>
	<div class="form-group">
        {{ Form::label('atc', 'AfterNoon Trip Cron') }}
        {{ Form::text('atc', $atc, array('class' => 'form-control')) }}
		
    </div>
	<div class="form-group">
        {{ Form::label('parkDuration', 'Park Duration in minutes') }}
        {{ Form::text('parkDuration', $parkDuration, array('class' => 'form-control')) }}
		
    </div>
	<div class="form-group">
        {{ Form::label('idleDuration', 'Idle Duration in minutes') }}
        {{ Form::text('idleDuration', $idleDuration, array('class' => 'form-control')) }}
		
    </div>
	<div class="form-group">
        {{ Form::label('smsSender', 'SMS Sender') }}
        {{ Form::text('smsSender', $smsSender, array('class' => 'form-control')) }}
		
    </div>
    <div>
	{{ Form::label('poi', 'Place Of interest') }}
	</br>
	
	<table><tr><td>
	@foreach($place as $key => $value)
	<table><tr>
	<td>{{ Form::hidden('oldlatandlan'.$k++, $key, array('class' => 'form-control')) }}</td><td>
	{{ Form::text('latandlan'.$j++, $key, array('class' => 'form-control')) }}</td><td>
	{{ Form::text( 'place'.$i++,$value ,array('class' => 'form-control','disabled' => 'disabled') ) }}</td>
	</tr></table>
	 @endforeach
	
	 
    <div>
    <ul id="itemsort"> 
	</br>
	{{ Form::label('startTime', 'Critical Hours') }}
	<table><tr><td>{{ Form::label('startTime', 'Start Time') }}</td><td>{{ Form::label('endTime', 'End Time') }}</td></tr>
	
	<tr><td>{{  Form::input('time', 'time1', $time1, ['class' => 'form-control', 'placeholder' => 'time'])}}</td>
	<td>{{  Form::input('time', 'time2', $time2, ['class' => 'form-control', 'placeholder' => 'time'])}}</td></tr></table>
	
	
    </ul></div>
    </ul></div>
 </div>
  </div>

    {{ Form::submit('Update School/College/Organization!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop
