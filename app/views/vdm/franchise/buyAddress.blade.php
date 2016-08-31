@extends('includes.vdmheader')
@section('mainContent')

<h2><font color="blue">Address Control</font></h2>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmFranchises/updateAddCount')) }}
<br>
<div class="row">
    <div class="col-md-6">
    <div class="form-group">
        
    <div class="form-group">
        {{ Form::label('addressCount', 'Daily Address buying count') }}
        {{ Form::text('addressCount',$addressCount, array('class' => 'form-control')) }}
    </div>

    </div>

</br>
</br>
    {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
   </div>
@stop