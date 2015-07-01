@extends('includes.vdmheader')
@section('mainContent')
<h1>Add a school/college</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmSchool')) }}
<div class="row">
    <div class="col-md-4">   
    
       
    <div class="form-group">
        {{ Form::label('schoolId', 'School/College Id :')  }}
        {{ Form::text('schoolId', Input::old('schoolId'), array('class' => 'form-control')) }}

    </div>
   <div class="form-group">
        {{ Form::label('description', 'Description') }}
        {{ Form::text('description', Input::old('description'), array('class' => 'form-control')) }}

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
        {{ Form::label('routes', 'Routes') }}
        {{ Form::textarea('routes', Input::old('routes'), array('class' => 'form-control')) }}
    </div>
    
 </div>
  </div>

    {{ Form::submit('Add the School/College!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop
