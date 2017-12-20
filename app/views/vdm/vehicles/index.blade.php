@include('includes.header_index')
{{ HTML::ul($errors->all()) }}
 {{ Form::open(array('url' => 'vdmVehicles/moveDealer')) }}
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
                    <h4>
					@if(Session::get('vCol')=='1')
                    <b>Vehicles List</b>
                    @endif  
                    @if(Session::get('vCol')=='2')
                    <b>View Vehicles</b>
                    @endif
                    </h4> 


                   <!--  @if(Session::get('cur')=='admin')
                    <div >{{ Form::label('dealerId', 'Dealers Id') }}</div>
                            <div >{{ Form::select('dealerId', array($dealerId), Input::old(''),array('class'=>'form-control selectpicker show-menu-arrow', 'data-live-search '=> 'true')) }}</div>
                            <div >{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</div>
       				 @endif  -->
                    
                    
                </div>

                <div class="panel-body">

                   <!--  @if(Session::get('cur')=='admin')
                    <div >{{ Form::label('dealerId', 'Dealers Id') }}</div>
                            <div >{{ Form::select('dealerId', array($dealerId), Input::old(''),array('class'=>'form-control selectpicker show-menu-arrow', 'data-live-search '=> 'true')) }}</div>
                            <div >{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</div>
       				 @endif  -->
               
       				 
                	<hr>
                	<table id="example1" class="table table-bordered dataTable">
               		 <thead>
						<tr>
							<th style="text-align: center;">ID</th>
							<!--  @if(Session::get('cur')=='admin')
							<th style="text-align: center;">Select</th>
							 @endif  -->
							<th style="text-align: center;">AssetID </th>
							<th style="text-align: center;">Vehicle Name</th>
							<th style="text-align: center;">Org Name</th>
							<th style="text-align: center;">Device ID</th>
							
								@if(Session::get('vCol')=='2')
									<th style="text-align: center;">Status</th>
									<th style="text-align: center;">Device Model</th>
								<!-- <th style="text-align: center;">Expire Date</th> -->
								<th style="text-align: center;">Mobile No</th>
									@endif 
							@if(Session::get('vCol')=='1')
							<th style="text-align: center;">Actions</th>
							@endif 
						</tr>
					</thead>
					<tbody>
					@foreach($vehicleList as $key => $value)
						<tr style="text-align: center;">
							<td>{{ ++$tmp }}</td>
							 <!-- @if(Session::get('cur')=='admin')
							<td>{{ Form::checkbox('vehicleList[]', $value, null, ['class' => 'field']) }}</td>
							@endif  -->
							<td>{{ $value }}</td>
							<td>{{ array_get($shortNameList, $value)}}</td>
							<td>{{ array_get($orgIdList, $value)}}</td>
							<td>{{ array_get($deviceList, $value)}}</td>
					        
							@if(Session::get('vCol')=='2')

								<td>
									@if(array_get($statusList, $value) == 'P')
										<div style="color: #8e8e7b">Parking</div>
									@endif
									@if(array_get($statusList, $value) == 'M')
										<div style="color: #00b374">Moving</div>
									@endif
									@if(array_get($statusList, $value) == 'S')
										<div style="color: #ff6500">Idle</div>
									@endif
									@if(array_get($statusList, $value) == 'U')
										<div style="color: #fe068d">No Data</div>
									@endif
									@if(array_get($statusList, $value) == 'N')
										<div style="color: #0a85ff">New Device</div>
									@endif
								</td>
								

							<td>{{ array_get($deviceModelList, $value)}}</td>
						<!--  <td>{{ array_get($expiredList, $value)}}</td> -->
					        <td>{{ array_get($mobileNoList, $value)}}</td> 	
							@endif 							
							@if(Session::get('vCol')=='1')
							<td>
								
								<a  class="btn btn-sm btn-info" href="{{ URL::to('vdmVehicles/stops/' . $value,'normal') }}" >Show Stops</a>
								
								<a class="btn btn-sm btn-danger" href="{{ URL::to('vdmVehicles/removeStop/' . $value,'normal') }}">Remove Stops</a>
								
								<!--<a  class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/stops/' . $value,'alternate') }}" >Show ALTS</a>
								
								<a class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/removeStop/' . $value,'alternate') }}">Remove ALTS</a>-->
								
								
<!-- 								<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/' . $value) }}">Show Vehicle</a>
 -->								
								<a class="btn btn-sm btn-success" href="{{ URL::to('vdmVehicles/migration/' . $value) }}">Migration</a>
					
								<a class="btn btn-sm btn-primary" href="{{ URL::to('vdmVehicles/edit/' . $value ) }}">Edit</a>
								
								<a class="btn btn-sm btn-warning" href="{{ URL::to('vdmVehicles/calibrateOil/' . $value,'0') }}">Calibrate</a>
								 
								<a class="btn btn-sm btn-info" href="{{ URL::to('vdmVehicles/rename/' . $value) }}">Rename</a>
							
			
				
							</td>@endif 
						</tr>
						@endforeach
						
						
							<script>




  function ConfirmDelete()
  {
  var x = confirm("Confirm to remove?");
  if (x)
    return true;
  else
    return false;
  }

</script>
					</tbody>
                </table>
          
    
</div>
</div>
</div>
@include('includes.js_index')
</body>
</html>
