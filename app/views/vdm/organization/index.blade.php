@include('includes.header_index')
<div id="wrapper">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                       <h4><b><font> Organization List</font></b></h4>
                    </div>
                    <div class="panel-body">
                        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="col-sm-6">
                                        <div id="example2_filter" class="dataTables_filter"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="example1" class="table table-bordered dataTable">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">ID</th>
                                                    <th style="text-align: center;">Organization ID</th>
                                                    <th style="text-align: center;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orgList as $key => $value)
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td>{{ $value }}</td>

                                                    <!-- we will also add show, edit, and delete buttons -->
                                                    <td> 
													<table>
<tr><td>

 <a class="btn btn-warning" href="{{ URL::to('vdmOrganization/' . $value . '/edit') }}">Edit Organization </a>
 <a class="btn btn-warning" href="{{ URL::to('vdmOrganization/' . $value . '/editAlerts') }}">Edit Alerts </a> 
 <a class="btn btn-small btn-info" href="{{ URL::to('vdmOrganization/' . $value . '/pView') }}">View POI </a>
 <a class="btn btn-warning" href="{{ URL::to('vdmOrganization/' . $value . '/poiEdit') }}">Edit POI </a>
 <a class="btn btn-small btn-info" href="{{ URL::to('vdmOrganization/' . $value . '/poiDelete') }}">Delete POI </a>
 <a class="btn btn-small btn-info" href="{{ URL::to('vdmOrganization/' . $value . '/getSmsReport') }}">SMS Report </a>
 <a class="btn btn-warning" href="{{ URL::to('vdmOrganization/' . $value . '/siteNotification') }}">Site Notification </a>
</td>
<td>{{ Form::open(array('url' => 'vdmOrganization/' . $value, 'onsubmit' => 'return ConfirmDelete()')) }}
                                                    {{ Form::hidden('_method', 'DELETE') }}
                                                    {{ Form::submit('Delete Organization', array('class' => 'btn btn-warning')) }}
													
                                                    {{ Form::close() }}</td>
</tr></table>													  
													
													
													
													

  

 
													
<script>

  function ConfirmDelete()
  {
  var x = confirm("It will removes all stops generated in this routes?");
  if (x)
    return true;
  else
    return false;
  }

</script>

                                                      
                                                 
                                                    
                                                    
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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

