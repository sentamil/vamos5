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
  #tabNew td:nth-of-type(2):before {content: "Dealer ID ";}
  #tabNew td:nth-of-type(3):before {content: "Mobile No ";}
  #tabNew td:nth-of-type(4):before {content: "Code ";}
  #tabNew td:nth-of-type(5):before {content: "Actions ";}
 } 
 </style>
<div id="wrapper">
  <div class="content animate-panel">
    <div class="row">
      <div class="col-lg-12">
        <div class="hpanel">
          <div class="panel-heading">
		  @if(Session::has('message'))
				<p  n class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
			@endif
            <h4><font><b>Dealer Search</b></font></h4>
          </div>
          <div class="panel-body">
          {{ Form::open(array('url' => 'vdmDealersScan/Search','method' => 'post')) }}
                <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6">
                        <div class="row">
                           {{ Form::text('text_word' , Input::old('text_word'),  array('class' => 'form-control', 'placeholder'=>'Search Dealers'))}}
                           <div style="font-size: 9px; text-align: center;"><b>Note : </b> Use * for getting all Dealers </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}{{ Form::close() }}
                      </div>
                      <hr>
                  </div>
                </div>  
<!-- {{ Form::open(array('url' => 'vdmDealersScan/Search','method' => 'post')) }}

 {{ Form::text('text_word') }}
<input type="submit" name="$value" value="search">
{{ Form::close() }} -->
		<div id="tabNew">
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
          </table>
		  </div>
        </div>
        </div>
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