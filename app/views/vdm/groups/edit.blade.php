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
	 <div class="row">
	 <div class="col-md-9">
	 {{ Form::label('vehicleList', 'Select the vehicles:') }}
	 {{ Form::input('text', 'searchtext', null, ['class' => 'searchkey'])}}
	</div>
	<div class="col-md-3">
	 {{ Form::submit('Update the Group!', array('class' => 'btn btn-primary')) }}
	</div>
</div>
	 </div>
	 @if(isset($vehicleList))		  
		@foreach($vehicleList as $key => $value)
			<div class="col-md-3 vehiclelist"> 
			{{ Form::checkbox('vehicleList[]', $key,  in_array($value,$selectedVehicles), ['class' => 'field']) }}
			{{ Form::label($value) }}
			{{ Form::label('( ' . array_get($shortNameList, $value) . ' )') }}
			</div>
			
		@endforeach
	@endif
		</br/>
		</br/>
{{ Form::close() }}

<script type="text/javascript">
$( ".searchkey" ).keyup(function() {
  var valThis = $(this).val().toLowerCase();
   $('.vehiclelist>input').each(function(){
       var text = $(this).val().toLowerCase();
       if(text.indexOf(valThis) >= 0) {
       	$(this).parent('div').fadeIn();
       }
       else{
       	$(this).parent('div').fadeOut();
       }

  });
})</script>

@stop