@include('includes.header_index')
<!-- Main Wrapper -->
<div id="wrapper">

<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
				@if(Session::has('message'))
					<p  n class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
				@endif
                    <h4><b><font>Dealer List</b> </font> </h4>
                </div>
                <div class="panel-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row">
                	<div class="row">
                	<div class="col-sm-12">
                	<table id="example1" class="table table-bordered dataTable">
               			 <thead>
							<tr>
								<th style="text-align: center;">ID</th>
								<th style="text-align: center;">Dealer ID</th>
								<th style="text-align: center;">Mobile  No</th>
								<th style="text-align: center;">Website</th>
								<th style="text-align: center;">Code</th>
								<th style="text-align: center;">Actions</th>
					
							</tr>
						</thead>
					
						
						<tbody>
						@foreach($dealerlist as $key => $value)
							<tr>
								<td>{{ ++$key }}</td>
								<td>{{ $value }}</td>
								<td>{{ array_get($userGroupsArr, $value)}}</td>	
								<td>{{ array_get($dealerWeb, $value)}}</td>
								<td>{{ $fcode }}</td>
								<td>
									{{ Form::open(array('url' => 'vdmDealers/' . $value, 'class' => 'pull-right' ,'onsubmit' => 'return ConfirmDelete()')) }}
										{{ Form::hidden('_method', 'DELETE') }}
										{{ Form::submit('Delete this Dealer', array('class' => 'btn btn-warning')) }}
									{{ Form::close() }}
									<a class="btn btn-small btn-success" href="{{ URL::to('vdmDealers/' . $value) }}">Show Dealer</a>
									<a class="btn btn-small btn-info" href="{{ URL::to('vdmDealers/' . $value . '/edit') }}">Edit Dealer</a>					
								    <a class="btn btn-small btn-success" href="{{ URL::to('vdmUsers/reports/' . $value) }}">Edit Dealer Reports</a>
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
<script>

  function ConfirmDelete()
  {
  var x = confirm("It will removes all dealer based informations?");
  if (x)
    return true;
  else
    return false;
  }

</script>
@include('includes.js_index')
</body>
</html>