@include('includes.header_create')
<!-- Main Wrapper -->
<style>
table {
width: 100%; 
}
tr:nth-of-type(odd) {
 /* background: white;*/
}
@media  only screen and (max-width: 760px), 
 (min-device-width: 320px) and (max-device-width: 780px) {

  table, thead, tbody, th, td, tr {
    display: block;
  }
   thead tr {
    position: absolute;
        top: -9999px;
        left: -9999px;
  }
  tr {
    border: 1px solid #eee; 
  }
  td {
    border-bottom: 1px solid #eee;
    position: relative; 
    white-space: normal;
   /* width: 50%;*/
    font-size: 17px;
  }
 td:before { 
    position:relative;
    color: #8AC007;
    padding: 2px;
    right: 10px;
border: 2px solid #aaa; 
  }
  td:nth-of-type(1):before {content: "ID ";}
  td:nth-of-type(2):before {content: "Group ID ";}
  td:nth-of-type(3):before {content: "Vehicles ";}
  td:nth-of-type(4):before {content: "Short Name ";}
  td:nth-of-type(5):before {content: "Actions ";}
 } 
</style>
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
                   <h4><b>Group List </b></h4>
                </div>
                <div class="panel-body">
                {{ Form::open(array('url' => 'vdmGroupsScan/Search','method' => 'post')) }}
                <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6">
                        <div class="row">
                           {{ Form::text('text_word' , Input::old('text_word'),  array('class' => 'form-control', 'placeholder'=>'Search Groups'))}}
                           <div style="font-size: 9px; text-align: center;"><b>Note : </b> Use * for getting all Groups </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}{{ Form::close() }}
                      </div>
                      <hr>
                  </div>
                </div>
                <!-- {{ Form::open(array('url' => 'vdmGroupsScan/Search','method' => 'post')) }}

 {{ Form::text('text_word') }}
<input type="submit" name="$value" value="search">
{{ Form::close() }} -->
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
                {{ Form::open(array('url' => 'vdmGroups/' . $value, 'class' => 'pull-right','onsubmit' => 'return ConfirmDelete()')) }}
                  {{ Form::hidden('_method', 'DELETE') }}
                  {{ Form::submit('Delete Group', array('class' => 'btn btn-warning')) }}
                {{ Form::close() }}
                <!-- show the nerd (uses the show method found at GET /nerds/{id} -->
                <!-- <a class="btn btn-small btn-success" href="{{ URL::to('vdmGroups/' . $value) }}">Show</a> -->
        
                <!-- edit this nerd (uses the edit method found at GET /nerds/{id}/edit -->
                <a class="btn btn-small btn-info" href="{{ URL::to('vdmGroups/' . $value . '/edit') }}">Edit Group</a>
        
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
@include('includes.js_create')
</body>
</html>