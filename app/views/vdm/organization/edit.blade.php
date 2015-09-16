@extends('includes.vdmheader')
@section('mainContent')
<h1>Add a school/college/Organization</h1>

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
	
	</td><td >
	@foreach($place1 as $key => $value)
	 {{ Form::text('l'.$m++, $key, array('class' => 'form-control','disabled' => 'disabled')) }}
	  @endforeach
	</td></tr></table>
	
	 
	 
	 
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
