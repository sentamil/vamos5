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
        {{ Form::label('routes', 'Routes') }}
        {{ Form::textarea('routes', $routes, array('class' => 'form-control')) }}
    </div>
    
 </div>
  </div>

    {{ Form::submit('Update School/College/Organization!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop
