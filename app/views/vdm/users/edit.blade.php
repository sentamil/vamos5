<html>
<body>
@extends('includes.vdmheader')
@section('mainContent')
<div id="wrapper">
  <div class="content animate-panel">
     <div class="row">
       <div class="col-lg-12">
	 <div class="hpanel">
		<div class="panel-heading">
                     <h1>Edit User</h1>
		<div class="panel-body">
		<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row">
		<div claa="row">
		<div class="col-sm-12">

                       <!-- if there are creation errors, they will show here -->
			{{ HTML::ul($errors->all()) }}


			{{ Form::model($userId, array('route' => array('vdmUsers.update', $userId), 'method' => 'PUT')) }}

			<div class="form-group">
			{{ Form::label('userId', 'UserId :') }}

		       {{ Form::label('userId', $userId) }}
   			</div><br>
			</br>
			<div class="form-group">
			   {{ Form::label('MobileNo', 'MobileNo') }}
			   {{ Form::text('MobileNo', $mobileNo, array('class' => 'form-control')) }}
			</div><br><br>
			<div class="form-group">
			{{ Form::label('Email', 'Email') }}
			{{ Form::text('Email', $email, array('class' => 'form-control')) }}
			</div>
			<div style="text-align: right;"><br>
			{{ Form::submit('Update the User!', array('class' => 'btn btn-primary')) }}
			</div>
			<hr>
			<table id="example1" class="table table-bordered dataTable">
			<thead>
			<tr>
	                      <th>{{ Form::label('vehicleList', 'Select the groups:') }}</th>
			</tr>
		</thead>
		<tbody>
	        @if(isset($vehicleGroups))
		@foreach($vehicleGroups as $key => $value)
		<tr class="col-md-2">
		<td>	{{ Form::checkbox('vehicleGroups[]', $key,  in_array($value,$selectedGroups), ['class' => 'field']) }}
			{{ Form::label($value) }}

		</td>
		</tr>
		@endforeach
		</tbody>
		</table>
		@endif
{{ Form::close() }}
</div>
</div>
</div>
@stop
</body>
</html>
