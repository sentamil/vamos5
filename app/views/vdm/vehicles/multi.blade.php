@extends('includes.vdmheader')
@section('mainContent')

<h1>Add Multiple Vehicles</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmVehicles/storeMulti')) }}
<div class="row">
    <div class="col-md-4">
    <div class="form-group">
        {{ Form::label('vehicleDetails', 'Vehicle Details') }}
        {{ Form::textarea('vehicleDetails', Input::old('vehicleDetails'), array('class' => 'form-control')) }}
    </div>
    </div>
    <div class="col-md-4">
    
    <div class="form-group">
        {{ Form::label('schoolId', 'School/College/Company Id') }}
       {{ Form::select('schoolId', array( $schoolList),' ' , array('class' => 'form-control')) }}             

    </div>
    
    </div>     
 </div>    
    {{ Form::submit('Add Multiple Vehicles!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@stop