@extends('includes.adminheader')
@section('mainContent')
<h1>Create Route with bus stops </h1>

                                    
                                    {{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmBusRoutes')) }}
<div class="row">
    
    <div class="col-md-4">   
         <div class="form-group">
        {{ Form::label('stops', 'Bus Stops') }}
        {{ Form::textarea('stops', '', array('class' => 'form-control')) }}
    </div>
    
     </div>
    
    <div class="col-md-4">   
    <div class="form-group">
        {{ Form::label('schoolId', 'School Id') }}
        {{ Form::select('schoolId', $schoolList,Input::old('schoolId'),array('class' => 'form-control')) }}
    </div>         
 
   <div class="form-group">
        {{ Form::label('routeId', 'Bus Route Number') }}
        {{ Form::text('routeId', Input::old('routeId'), array('class' => 'form-control')) }}

    </div>
    
    <div class="form-group">
        {{ Form::label('morningSeq', 'Morning Sequence') }}
        {{ Form::text('morningSeq', Input::old('morningSeq'), array('class' => 'form-control')) }}

    </div>
   
  
    
    </div>
   
  </div>

    {{ Form::submit('Create Bus routes with Stops!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@stop