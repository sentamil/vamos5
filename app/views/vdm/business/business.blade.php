@include('includes.header_index')
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
                    Dash Board
                </div>
                <div class="panel-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6">
                <div class="col-sm-6"><div id="example2_filter" class="dataTables_filter"></div></div></div><div class="row">
                	<div class="col-sm-12">
                	
					<div class="form-group">
					{{ Form::label('tnovo', 'Total number of Vehicles onBoard  :') }}
        
		
					{{ Form::label('count', $count .'Vehicles') }}
					</div>
					<br/>
					<div class="form-group">
					 @if(isset($dealerId))
						 <br/>
					 {{ Form::label('vehicles', 'Number of Vehicles onBoard in each dealers  :') }}
					 <br/><br/>
					@foreach($dealerId as $key => $value)
					 {{ Form::label('li', $key, array('class' => 'form-control','disabled' => 'disabled')) }}
					 {{ Form::label('l', $value .'  '.'Vehicles', array('class' => 'form-control','disabled' => 'disabled')) }}
					 <br/><br/>
					  @endforeach
					  
					  @endif
					</div>
					
					
					</div>
					
					<div class="form-group">
					 @if(isset($vechile))
						 <br/>
					 {{ Form::label('vechileEx', 'Number of Vehicles Expired this month') }}
					 
					   {{ Form::label('l', count($vechile), array('class' => 'form-control')) }}
					 <br/><br/>
					  {{ Form::label('vechileEx', 'The Vehicles are :') }}<br/>
					@foreach($vechile as $key => $value)
					 {{ Form::label('li', $key, array('class' => 'form-control','disabled' => 'disabled')) }}
					 <br/><br/>
					  @endforeach
					  
					  @endif
					</div>
						<div class="form-group">
					 @if(isset($temp))
						 <br/>
					 {{ Form::label('vechileEx', $vechileEx) }}
					 
					   
					 <br/><br/>
					  {{ Form::label('vechileEx1', $vechileEx1) }}<br/>
					@foreach($temp as $key => $value)
					 {{ Form::label('li', $key, array('class' => 'form-control','disabled' => 'disabled')) }}
					 {{ Form::label('l', $value .'  '.'Vehicles', array('class' => 'form-control','disabled' => 'disabled')) }}
					 <br/><br/>
					  @endforeach
					  
					  @endif
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