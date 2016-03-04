@extends('includes.vdmheader')
@section('mainContent')

<h2><font color="blue">Select the Franchise name</font></h2>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmFranchises/findFransList')) }}
<br>
<div class="row">
    <div class="col-md-6">
    <div class="form-group">
        
    <div class="form-group">
        {{ Form::label('dealerId', 'Franchise Id') }}
        {{ Form::select('frans', array($fransId), array('class' => 'form-control')) }}
    </div>

    </div>

</br>
</br>
    {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
   </div>
@stop