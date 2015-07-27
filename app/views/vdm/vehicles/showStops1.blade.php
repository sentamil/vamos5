@include('includes.header_index')
<div id="wrapper">
<div class="content animate-panel">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
                <div class="panel-heading">
                    Vehicles List  
                </div>
                <div class="panel-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6">
                <div class="col-sm-6"><div id="example2_filter" class="dataTables_filter"></div></div></div><div class="row">
          
		  
		  {{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmVehicles/stops')) }}
<div class="row" >
   
   </br>
   </br>
    <table align="center" frame="box" rowspan="20">
	
	<tr>
	<td>
	@foreach($sugStop as $key => $rowId)
	<div style="width:1000" align="left">
	 
	 {{ ++$key }}. {{ $rowId }}.
	 
	 
	</div>
	
	@endforeach
	
	</td>
	</tr>
	
	
	</table>

    {{ Form::submit('back', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
	
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