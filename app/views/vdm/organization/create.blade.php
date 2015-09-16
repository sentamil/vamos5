@extends('includes.vdmheader')
@section('mainContent')
<h1>Add a school/college/Organization</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmOrganization')) }}
<div class="row">
    <div class="col-md-4">   
    
       
    <div class="form-group">
        {{ Form::label('organizationId', 'School/College/Organization Id :')  }}
        {{ Form::text('organizationId', Input::old('organizationId'), array('class' => 'form-control')) }}

    </div>
   <div class="form-group">
        {{ Form::label('description', 'Description') }}
        {{ Form::text('description', Input::old('[description'), array('class' => 'form-control')) }}

    </div>
	<div class="form-group">
        {{ Form::label('etc', 'Evening Trip Cron') }}
        {{ Form::text('etc', Input::old('etc'), array('class' => 'form-control')) }}
		
    </div>
    <div class="form-group">
        {{ Form::label('address', 'Address') }}
        {{ Form::textarea('address', Input::old('address'), array('class' => 'form-control')) }}
    </div>
    
    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
    </div>
    
    
    </div>
    <div class="col-md-4">
    
      <div class="form-group">
        {{ Form::label('mobile', 'Mobile') }}
        {{ Form::text('mobile', Input::old('mobile'), array('class' => 'form-control')) }}
		
    </div>
	
	<div class="form-group">
        {{ Form::label('mtc', 'Morning Trip Cron') }}
        {{ Form::text('mtc', Input::old('mtc'), array('class' => 'form-control')) }}
		
    </div>
	<div class="form-group">
        {{ Form::label('atc', 'After Trip Cron') }}
        {{ Form::text('atc', Input::old('atc'), array('class' => 'form-control')) }}
		
    </div>
	
	
	
	
	
	<div>
    <ul id="itemsort"> {{ Form::label('poi', 'Place of interest') }}
       @for ($i = 0; $i < 10; $i++)
{{ Form::text('poi'.$i, Input::old('poi'), array('class' => 'form-control')) }}
			@endfor
    </ul></div>
	
	
	<div>
    <ul id="itemsort"> 
	<table><tr><td>{{ Form::label('startTime', 'Start Time') }}</td><td>{{ Form::label('endTime', 'End Time') }}</td></tr>
	
	<tr><td>{{  Form::input('time', 'time1', null, ['class' => 'form-control', 'placeholder' => 'time'])}}</td>
	<td>{{  Form::input('time', 'time2', null, ['class' => 'form-control', 'placeholder' => 'time'])}}</td></tr></table>
	
	
    </ul></div>
 </div>
  </div>
  
 

    {{ Form::submit('Add the School/College/Organization!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop
