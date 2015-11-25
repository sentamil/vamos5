@extends('includes.vdmheader')
@section('mainContent')

<h2><b><font color="blue">Edit User</font></b></h2>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}


{{ Form::model($userId, array('route' => array('vdmUsers.update', $userId), 'method' => 'PUT')) }}
<div class="row">
	 <div class="col-md-4">

	<div class="form-group">
		{{ Form::label('userId', 'UserId :') }}
		
		{{ Form::label('userId', $userId) }}
	</div>

<div class="form-group">
		{{ Form::label('mobileNo', 'MobileNo :') }}
		{{ Form::text('mobileNo', $mobileNo, array('class' => 'form-control')) }}
	</div>
<div class="form-group">
		{{ Form::label('email', 'Email :') }}
		{{ Form::text('email', $email, array('class' => 'form-control')) }}
	</div>
	</div>
	</br>
		 <div class="form-group">
		 
	 <div class="col-md-9">
	 <h4>{{ Form::label('Filter', 'Filter :') }}
	  {{ Form::input('text', 'searchtext', null, ['class' => 'searchkey'])}} </h4>
	 <h4><font color="green">{{ Form::label('vehicleList', 'Select the groups:') }}</font></h4>
	
	 </div>
	 <div class="col-md-3">
	 {{ Form::submit('Update the User!', array('class' => 'btn btn-primary')) }}
</div>
</div>
</div>
	  @if(isset($vehicleGroups))
		@foreach($vehicleGroups as $key => $value)
			 <div class="col-md-3 vehiclelist"> 
			{{ Form::checkbox('vehicleGroups[]', $key,  in_array($value,$selectedGroups), ['class' => 'field']) }}
			{{ Form::label($value) }}
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