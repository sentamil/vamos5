@include('includes.header_create')
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
    font-size: 17px;
  }
 td:before { 
    position:relative;
    color: #8AC007; 
    padding: 2px;
    right: 10px;
   overflow: scroll;
   border: 2px solid #aaa;  
  }
  td:nth-of-type(1):before {content: "ID ";}
  td:nth-of-type(2):before {content: "User ID ";}
  td:nth-of-type(3):before {content: "User Groups ";}
  td:nth-of-type(4):before {content: "Code ";}
  td:nth-of-type(5):before {content: "Actions ";}
 } 
</style>
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
                   <h4><b><font> User List  </font></b></h4>
                </div>
                <div class="panel-body">
                {{ Form::open(array('url' => 'vdmUserScan/user','method' => 'post')) }}
                <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6">
                        <div class="row">
                           {{ Form::text('text_word' , Input::old('text_word'),  array('class' => 'form-control', 'placeholder'=>'Search User'))}}
                           <div style="font-size: 9px; text-align: center;"><b>Note : </b> Use * for getting all Users</div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}{{ Form::close() }}
                      </div>
                      <hr>
                  </div>
                </div>
                <!-- {{ Form::open(array('url' => 'vdmUserScan/user','method' => 'post')) }}

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
                <td style=" overflow: hidden;">
                <a class="btn btn-small btn-success" href="{{ URL::to('vdmUsers/' . $value) }}">Show this User</a>

                  <a class="btn btn-small btn-info" href="{{ URL::to('vdmUsers/' . $value . '/edit') }}">Edit this User</a> 
                  <a class="btn btn-small btn-success" href="{{ URL::to('vdmUsers/notification/' . $value ) }}">Edit Notification</a>       
                  {{ Form::open(array('url' => 'vdmUsers/' . $value, 'class' => 'pull-right' ,'onsubmit' => 'return ConfirmDelete()')) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete this User', array('class' => 'btn btn-warning')) }}
                  {{ Form::close() }}

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
@include('includes.js_create')
</body>
</html>