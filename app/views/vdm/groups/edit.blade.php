@extends('includes.vdmheader')
@section('mainContent')

<h1>Edit Group</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($groupId, array('route' => array('vdmGroups.update', $groupId), 'method' => 'PUT')) }}
	
	<div class="form-group">
		{{ Form::label('groupId', 'Group Id :')  }}
		
		{{ Form::label('groupId' , $groupId) }}
	</div>
	
<!-- 
	
	<div class="form-group">
		{{ Form::label('vehicleList', 'Vehicle List (press shift to select multiple vehicles)') }}
		{{ Form::select('vehicleList[]', $vehicleList, Input::old('vehicleList'),  array('multiple' => true,'class' => 'form-control')) }}

	</div>
	 -->
		 <div class="form-group">
	 {{ Form::label('vehicleList', 'Select the vehicles:') }}
	 </div>
	 
		@foreach($vehicleList as $key => $value)
			 
			{{ Form::checkbox('vehicleList[]', $key,  in_array($value,$selectedVehicles), ['class' => 'field']) }}
			{{ Form::label($value) }}
			 {{ Form::label('( ' . array_get($shortNameList, $value) . ' )') }}
			<br/>
		@endforeach
		</br/>
		</br/>

	{{ Form::submit('Update the Group!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop