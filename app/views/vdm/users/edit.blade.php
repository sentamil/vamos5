@extends('includes.vdmheader')
@section('mainContent')

<h2><font color="blue">Edit User</font></h2>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}


{{ Form::model($userId, array('route' => array('vdmUsers.update', $userId), 'method' => 'PUT')) }}
<div class="row">
	 <div class="col-md-4">

	<div class="form-group">
	<h4>	{{ Form::label('userId', 'UserId :') }}
		
		{{ Form::label('userId', $userId) }}</h4>
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
	</div>
	</br>
	<div class="row">
		 <div class="form-group">
		 
	 <div class="col-md-9">
	  <h4><font color="green">{{ Form::label('vehicleList', 'Select the groups:') }}</font></h4>
	  
	 <h4>{{ Form::label('Filter', 'Filter :') }}
	  {{ Form::input('text', 'searchtext', null, ['class' => 'searchkey'])}} </h4>
	
	<div>{{Form::label('Select All :')}} {{Form::checkbox('as', 'value', false, ['class' => 'check'])}}</div>
	 </div>
	
	 <div class="col-md-3">
	 {{ Form::submit('Update the User!', array('class' => 'btn btn-primary')) }}
</div>
</div>
</div>
	  @if(isset($vehicleGroups))
		@foreach($vehicleGroups as $key => $value)
			 <div class="col-md-3 vehiclelist"> 
			{{ Form::checkbox('vehicleGroups[]', $key,  in_array($value,$selectedGroups), ['class' => 'field','id' => 'questionCheckBox']) }}
			{{ Form::label($value) }}
			</div>
		@endforeach
		@endif
		</br/>
		</br/>	
		
{{ Form::close() }}
<script type="text/javascript">
list = [];
var value = <?php echo json_encode($vehicleGroups); ?>;

</script>
@include('includes.js_footer')
@stop