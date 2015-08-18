@extends('includes.vdmheader')
@section('mainContent')

<h1 align="center">Generate Stops</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmVehicles/generate')) }}

 
<div class="row" >
   
   </br>
   </br>
    <table align="center"  cellspacing="100">
	
	<tr>
	<td >
	
	
									<div class="form-group">
										{{ Form::label('vechicle', 'Vechicle Id') }}
										 
									 {{ Form::label('vehicleId', $vehicleId) }}

									  {{ Form::hidden('vehicleId', $vehicleId) }}
									</div></td>
	
	<td >
	
	
								   

									
                                    
                                   
									<div class="form-group">
										{{ Form::label('Date', 'Date') }}
										{{ Form::text('date',   Input::old('date'),array('placeholder' => 'dd-mm-yyyy'), array('class' => 'form-control')) }}
									</div></td></tr><tr><td>
									<div class="form-group">
										{{ Form::label('MST', 'Morning Start Time') }}
										{{ Form::text('mst',   Input::old('mst'),array('placeholder' => 'hh:mm'), array('class' => 'form-control')) }}
										</br>
									</div></td><td>
									<div class="form-group">
										{{ Form::label('MET', 'Morning End Time') }}
										{{ Form::text('met',   Input::old('met'),array('placeholder' => 'hh:mm'), array('class' => 'form-control')) }}
									</div></td></tr><tr><td>
									<div class="form-group">
										{{ Form::label('EST', 'Evening Start Time') }}
										{{ Form::text('est',   Input::old('est'),array('placeholder' => 'hh:mm'), array('class' => 'form-control')) }}
									</div></td><td>
									<div class="form-group">
										{{ Form::label('EET', 'Evening End Time') }}
										{{ Form::text('eet',   Input::old('eet'),array('placeholder' => 'hh:mm'), array('class' => 'form-control')) }}
									</div>
									</td>	
									
									</tr><tr>
									<td>
									
									<div class="form-group">
										{{ Form::label('spd', 'Speed') }}
										{{ Form::select('type', array( '0' => 'Strict', '5' => 'Medium','10' => 'Relax'), Input::old('type'), array('class' => 'form-control')) }}  
									</div>
									
									
									<div class="form-group">
										{{ Form::label('demo', 'Type') }}
										{{ Form::text('demo', $demo ) }}
									</div>
									</td>	
                                    <br />
									
									<br/>
									</td>
	</tr><tr><td colspan="2">
									<div class="form-group">
									    {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}{{ Form::close() }}
										
									 </div>   

								</td></tr>

								
	
	
	
	</table>
	
	
	
	
	
	
	



   
	
   </div>
@stop