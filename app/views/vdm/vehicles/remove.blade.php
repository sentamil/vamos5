@extends('includes.vdmheader')
@section('mainContent')

<h1 align="center">The Suggested Stops for this route {{ $vehicleId }} is successfully removed</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmVehicles/stops')) }}
<div class="row" >
   
   </br>
   </br>
    <table align="center"  cellpadding="200">
	<tr><td><table>
	<tr>
	
	</tr>
	
	
	</table>
</td></tr></table>
    {{ Form::submit('back', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
	
   </div>
@stop