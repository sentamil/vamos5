@include('includes.header_index')
<!-- Main Wrapper -->
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
                    Group List  
                </div>
                <div class="panel-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6">
                <div class="col-sm-6"><div id="example2_filter" class="dataTables_filter"></div></div></div><div class="row">
                	<div class="col-sm-12">
                	<table id="example1" class="table table-bordered dataTable">
                <thead>
						<tr>
							<th style="text-align: center;">ID</th>
							<th style="text-align: center;">Group ID</th>
							<th style="text-align: center;">Vehicles</th>
							<th style="text-align: center;">Short Name</th>
							<th style="text-align: center;">Actions</th>
				
						</tr>
					</thead>
					<tbody>
					@foreach($groupList as $key => $value)  
						<tr>
							<td>{{ ++$key }}</td>
							<td>{{ $value }}</td>
							<td>{{ array_get($vehicleListArr,$value)}}</td>	
							<td>{{ array_get($shortNameListArr,$value)}}</td>  
				
								 
							<!-- we will also add show, edit, and delete buttons -->
							<td>
				
								<!-- delete the nerd (uses the destroy method DESTROY /nerds/{id} -->
								<!-- we will add this later since its a little more complicated than the other two buttons -->
								{{ Form::open(array('url' => 'vdmGroups/' . $value, 'class' => 'pull-right')) }}
									{{ Form::hidden('_method', 'DELETE') }}
									{{ Form::submit('Delete this Group', array('class' => 'btn btn-warning')) }}
								{{ Form::close() }}
								<!-- show the nerd (uses the show method found at GET /nerds/{id} -->
								<a class="btn btn-small btn-success" href="{{ URL::to('vdmGroups/' . $value) }}">Show this Group</a>
				
								<!-- edit this nerd (uses the edit method found at GET /nerds/{id}/edit -->
								<a class="btn btn-small btn-info" href="{{ URL::to('vdmGroups/' . $value . '/edit') }}">Edit this Group</a>
				
							</td>
						</tr>
					@endforeach
					</tbody>
                </table></div></div>
            </div>
    </div>
</div>
</div>
</div>
@include('includes.js_index')
</body>
</html>