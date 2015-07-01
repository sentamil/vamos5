@extends('includes.vdmheader')
@section('mainContent')

<h1>Add Multiple Vehicles</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmVehicles/storeMulti')) }}
<div class="row">
    <div class="col-md-6">
    <div class="form-group">
        {{ Form::label('vehicleDetails', 'Vehicle Details') }}
        {{ Form::textarea('vehicleDetails', Input::old('vehicleDetails'), array('class' => 'form-control')) }}
    </br>
    </br>
    <div class="form-group">
        {{ Form::label('orgId', 'Organization Id') }}
        {{ Form::select('orgId', array($orgList), array('class' => 'form-control')) }}
    </div>

    </div>

</br>
</br>
    {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
   </div>
@stop