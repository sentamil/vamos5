@include('includes.header_create') <!-- Main Wrapper -->
<style>
#tabNew table {
width: 100%; 
}
#tabNew tr:nth-of-type(odd) {
 /* background: white;*/
}
@media  only screen and (max-width: 760px), 
 (min-device-width: 320px) and (max-device-width: 780px) {
  #tabNew table, #tabNew thead, #tabNew tbody, #tabNew th, #tabNew td, #tabNew tr {  
    display: block;
  }
   #tabNew thead tr {
    position: absolute;
        top: -9999px;
        left: -9999px;
  }
  #tabNew tr {
    border: 1px solid #eee; 
  }
  #tabNew td {
    border-bottom: 1px solid #eee;
    position: relative;
    white-space: normal;
   /* width: 50%;*/
    font-size: 17px;
  }
 #tabNew td:before {
     position:relative;
    color: #8AC007; 
    padding: 2px;
    right: 10px;
   border: 2px solid #aaa;  
  }
  #tabNew td:nth-of-type(1):before {content: "ID ";}
  #tabNew td:nth-of-type(2):before {content: "Organization ID ";}
  #tabNew td:nth-of-type(3):before {content: "Actions ";}
 } 
</style>
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-heading">
						<h4><font><b>Organization Search</b></font></h4>
					</div>
					<div class="panel-body">
          {{ Form::open(array('url' => 'vdmOrganization/adhi','method' => 'post' ,)) }}
                 <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6">
                        <div class="row">

                           {{ Form::text('text_word' , Input::old('text_word'),  array('class' => 'form-control', 'placeholder'=>'Search Organization'))}}
                           <div style="font-size: 9px; text-align: center;"><b>Note : </b> Use * for getting all Organizations</div>
					  </div>
                      
                      </div>
                      <div class="col-md-6">
                        {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}{{ Form::close() }}
                      </div>
                    <hr>
                </div>
                      </div>
								<div id="tabNew">
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
                         


 <a class="btn btn-warning" href="{{ URL::to('vdmOrganization/' . $value . '/edit') }}">Edit Organization </a>
 <a class="btn btn-warning" href="{{ URL::to('vdmOrganization/' . $value . '/editAlerts') }}">Edit Alerts </a> 
 <a class="btn btn-small btn-info" href="{{ URL::to('vdmOrganization/' . $value . '/pView') }}">View POI </a>
 <a class="btn btn-warning" href="{{ URL::to('vdmOrganization/' . $value . '/poiEdit') }}">Edit POI </a>
 <a class="btn btn-small btn-info" href="{{ URL::to('vdmOrganization/' . $value . '/poiDelete') }}">Delete POI </a>
 <a class="btn btn-small btn-info" href="{{ URL::to('vdmOrganization/' . $value . '/getSmsReport') }}">SMS Report </a>
 <a class="btn btn-warning" href="{{ URL::to('vdmOrganization/' . $value . '/siteNotification') }}">Site Notification </a>

{{ Form::open(array('url' => 'vdmOrganization/' . $value, 'onsubmit' => 'return ConfirmDelete()')) }}
                                                    {{ Form::hidden('_method', 'DELETE') }}
                                                    {{ Form::submit('Delete Organization', array('class' => 'btn btn-warning')) }}
                          
                                                    {{ Form::close() }}</td>

                          
                          
                          
                          

  

 
                          
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
            </div>
            @include('includes.js_create')
            </body>
            </html>

