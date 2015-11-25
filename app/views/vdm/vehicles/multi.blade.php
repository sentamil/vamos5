<!DOCTYPE html>
@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
       			 <div class="hpanel">
               		 <div class="panel-heading">
                   		<h4><b><font color="blue">Add Multiple Vehicles</font></b></h4>
                	 </div>
                	<div class="panel-body">
                		<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                			<div class="row">
                				<div class="col-sm-12">
<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmVehicles/storeMulti')) }}
<div class="row">
    <div class="col-md-6">
    <div class="form-group">
        {{ Form::label('vehicleDetails', 'Vehicle Details') }}
        {{ Form::textarea('vehicleDetails', Input::old('vehicleDetails'), array('class' => 'form-control')) }}
    </br>
    </br>
    <div class="form-group">
        {{ Form::label('orgId', 'Organization Id') }}
        {{ Form::select('orgId', array($orgList), array('class' => 'form-control')) }}
    </div>

    </div>

</br>
</br>
    {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
   </div>

 @include('includes.js_create')
 </body>
</html>