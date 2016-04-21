@include('includes.header_index')
<!-- Main Wrapper -->
<div id="wrapper">

<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
                   <h4><b><font> User List  </font></b></h4>
                </div>
                <div class="panel-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row">
                	<div class="row">
                	<div class="col-sm-12">
                	<table id="example1" class="table table-bordered dataTable">
               			 <thead>
							<tr>
								<th style="text-align: center;">ID</th>
								<th style="text-align: center;">User ID</th>
								<th style="text-align: center;">User Groups</th>
								<th style="text-align: center;">Code</th>
								<th style="text-align: center;">Actions</th>
					
							</tr>
						</thead>
					
						
						<tbody>
						@foreach($userList as $key => $value)
							<tr>
								<td>{{ ++$key }}</td>
								<td>{{ $value }}</td>
								<td>{{ array_get($userGroupsArr, $value)}}</td>	
								<td>{{ $fcode }}</td>
								<td>
									{{ Form::open(array('url' => 'vdmUsers/' . $value, 'class' => 'pull-right' ,'onsubmit' => 'return ConfirmDelete()')) }}
										{{ Form::hidden('_method', 'DELETE') }}
										{{ Form::submit('Delete this User', array('class' => 'btn btn-warning')) }}
									{{ Form::close() }}
									<a class="btn btn-small btn-success" href="{{ URL::to('vdmUsers/' . $value) }}">Show this User</a>
									<a class="btn btn-small btn-info" href="{{ URL::to('vdmUsers/' . $value . '/edit') }}">Edit this User</a>					
								</td>
							</tr>
						@endforeach
						</tbody>
                </table></div></div>
            </div>
    </div>
</div>
</div>

<script>

  function ConfirmDelete()
  {
  var x = confirm("It will removes all users based information?");
  if (x)
    return true;
  else
    return false;
  }

</script>
</div>
@include('includes.js_index')
</body>
</html>