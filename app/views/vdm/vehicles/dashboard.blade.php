@include('includes.header_index')
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
                   <h4> Dash Board</h4>
                </div>
                <div class="panel-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6">
                <div class="col-sm-6"><div id="example2_filter" class="dataTables_filter"></div></div></div><div class="row">
			<div class="col-sm-12">
			<div class="form-group">
					<font face="tahoma" size="3" style="color:orange">{{ Form::label('tnovo', 'Total Vehicles OnBoard :') }}
					{{ Form::label('count', $count.' ') }}</font>
					</div></br>
					<div claa="col-sm-12">
					<div class="form-group">
					<div class="row">
					<div class="col-sm-12">
					<font face="tahoma" size="3" style="color:green"> {{ Form::label('vechileEx', 'Number of Vehicles Expires this month') }}

					   {{ Form::label('l', count($vechile), array('class' => 'form-control')) }}</font>
					 </div></br><br>
					<div class="col-sm-12">
			                <font face="tahoma" size="3" color="blue"> {{ Form::label('vechileEx', 'Vehicles Details :') }}</font>
					</br><br>
					<table id="example1" class="table table-bordered dataTable">
					<thead>
						<font face="tahoma" color="rose">

						<tr>
							<th style="text-align: center;">VEHICLE ID</th>
						</tr>
					</font>

					</thead>
					<tbody>
					@if(isset($vechile))
					@foreach($vechile as $key => $value)
					<tr style="text-align: center;">
					 <td>{{ Form::label('li', $key) }}</td>
					 </tr>
					  @endforeach
					  </tbody>
					  @endif
					</table>
					</div>
					</div>
					<br>
					<div class="row">
					<div class="col-sm-12">
					<div class="form-group">
       					<font face="tahoma" size="3" color="blue">{{ Form::label('vehicles', 'Vehicles OnBoard with each Dealers :') }}</font>
					<table id="example1" class="table table-bordered dataTable">
					<thead>
						<tr>
							<th style="text-align: center;">DEALER ID</th>
							<th style="text-align: center;">NUMBER OF VEHICLES</th>
						</tr>
					</thead>
					<tbody>
					@if(isset($dealerId))
					@foreach($dealerId as $key => $value)
						<tr style="text-align: center;">
							<td> {{ Form::label('li', $key) }}</td>
							<td> {{ Form::label('l',$value .' '.' ') }}</td>
						</tr>
					@endforeach
					</tbody>
					@endif
					</table>
				
					</br>
					</div>
					</div>
					<div class="row">
					<div class="col-sm-12">
					<div class="form-group">
					<div class="col-sm-12">
					<font face="tahoma" size="3" style="color:blue"> {{ Form::label('vechileEx', $vechileEx) }}</font>
					<div class="row">
					<div class="col-sm-12">
					<div class="form-group"> 
					{{ Form::label('vechileEx1', $vechileEx1) }}
					</div>
					<table id="example1" class="table table-bordered dataTable">
					<thead>
						<tr>
							<th style="text-align: center;">DEALER ID</th>
							<th style="text-align: center;">NUMBER OF VEHICLES</th>
						</tr>
					</thead>
					<tbody>
					 @if(isset($temp))
					@foreach($temp as $key => $value)
					<tr style="text-align: center;">
					<td> {{ Form::label('li', $key) }}</td>
					<td> {{ Form::label('l', $value .'  '.' ') }}</td>
					 </tr>
					  @endforeach
					  </tbody>
					  @endif
					</table>
					{{ Form::close() }}
					</div>
					</div>
				</div>
            </div>
    </div>
</div>
</div>
</div>
@include('includes.js_index')
</body>
</html>

