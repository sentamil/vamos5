@extends('includes.vdmheader')
@section('mainContent')

<h2><font color="blue">Edit POI</font></h2>
<div class="row">
	<div class="col-md-12">
<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmOrganization/addpoi')) }}

	<div class="row">
	<div class="col-md-3">
	
		{{ Form::label('orgId', 'OrgId Id :')  }}
		
		
		{{ Form::text('orgId',  $orgId, array('class' => 'form-control')) }}
	</div>
	</div>
	</br>
	
	<div class="row">
	<div class="col-md-3">
		{{ Form::label('radius', 'Radius:')  }}
		
		{{ Form::text('radiusrange',  $radiusrange,Input::old('radiusrange'), array('class' => 'form-control')) }}
		
	</div>
	</div>
 <hr>
		 <div class="form-group">
		 <div class="row">
	 <div class="col-md-8">
	  <h4><font color="green">{{ Form::label('userplace', 'Select the Places:') }}</font></h4>
		 <h4>{{ Form::label('Filter', 'Filter :') }}
		 {{ Form::input('text', 'searchtext', null, ['class' => 'searchkey'])}} </h4>
	
	 </div>
	 <div class="col-md-3" style="text-align: right"></br></br>
	 {{ Form::submit('Update the POI!', array('class' => 'btn btn-primary')) }}
	</div>
	 </div>
	
	 @if(isset($userplace))		  
		@foreach($userplace as $key => $value)
			 <div class="col-md-3 userplace">
			{{ Form::checkbox('poi[]', $key,  in_array($value,$selectedVehicles), ['class' => 'field']) }}
			{{ Form::label($key) }}			
			
			</div>
		@endforeach
	@endif
		</br/>
		</br/>

	
{{ Form::close() }}
<script type="text/javascript">
$( ".searchkey" ).keyup(function() {
  var valThis = $(this).val().toLowerCase();
   $('.userplace>input').each(function(){
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