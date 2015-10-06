<html>
<body>
@extends('includes.vdmheader')
@section('mainContent')
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
  <div class="col-ls-12">
	<div class="hpanel">
		<div class="panel-heading">
	            <h2>Edit Group</h2>
		</div>
		<div class="panel-body">
		<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row">
		<div class="col-sm-12">
		{{ HTML::ul($errors -> all()) }}
		{{ Form::model($groupId, array('route' => array('vdmGroups.update', $groupId), 'method' => 'PUT')) }}
		{{ Form::label('groupId', 'Group Id:') }}
		{{ Form::label('groupId', $groupId) }}
		
		<div style="text-align: right">
		{{ Form::submit('Update the Group!', array('class' =>'btn btn-primary')) }}
		</div>
		<hr>
		<table id="example1" class="table table-bordered dataTable">
		<thead>
		 <tr>
		     <th> {{ Form::label('vehicleList', 'Select the Vehicles:') }}</th>
		 </tr>
		</thead>
		<tbody>
		@foreach($vehicleList as $key => $value)
		<tr class="col-sm-2">
		     <td>	{{ Form::checkbox('vehicleList[]', $key, in_array($value,$selectedVehicles), ['class' => 'field']) }}
			{{ Form::label($value) }}
			{{ Form::label(' (' . array_get($shortNameList, $value) .' )') }}
		</td>
		</tr>
		@endforeach
		</tbody>
		</table>
		{{ Form::close() }}
@stop
