@extends('includes.vdmheader')
@section('mainContent')

<h1 align="center">The Suggested Stops for this route is {{ $vehicleId }}</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmVehicles/stops')) }}
<div class="row" >
   
   </br>
   </br>
    <table align="center"  cellpadding="200">
	<tr><td><table>
	<tr>
	<td>
	@foreach($sugStop as $key => $rowId)
	<div style="width:1000" align="left">
	 
	 {{ ++$key-1 }}. {{ $rowId }}.
	 
	 
	</div>
	
	@endforeach
	
	</td>
	</tr>
	
	
	</table>
</td></tr></table>
<a class="btn btn-sm btn-primary" href="{{ URL::previous() }}">Go Back</a>
   <!-- {{ Form::submit('back', array('class' => 'btn btn-primary')) }}-->
    {{ Form::close() }}
	
   </div>
@stop