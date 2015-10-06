@extends('includes.vdmheader')
@section('mainContent')

<h1>Edit Group</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmOrganization/addpoi')) }}
	
	<div class="form-group">
		{{ Form::label('orgId', 'OrgId Id :')  }}
		
		
		{{ Form::text('orgId',  $orgId, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('radius', 'Radius:')  }}
		
		{{ Form::text('radiusrange',  $radiusrange,Input::old('radiusrange'), array('class' => 'form-control')) }}
		
	</div>
	

		 <div class="form-group">
	 {{ Form::label('userplace', 'Select the Places:') }}
	 </div>
	 @if(isset($userplace))		  
		@foreach($userplace as $key => $value)
			 
			{{ Form::checkbox('poi[]', $key,  in_array($value,$selectedVehicles), ['class' => 'field']) }}
			{{ Form::label($key) }}
			
			<br/>
		@endforeach
	@endif
		</br/>
		</br/>

	{{ Form::submit('Update the Group!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@stop