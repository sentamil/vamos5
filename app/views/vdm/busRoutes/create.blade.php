@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                 <div class="hpanel">
                     <div class="panel-heading">
                         Vehicles Create 
                     </div>
                    <div class="panel-body">
                        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    
                                    {{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'vdmBusRoutes')) }}
<div class="row">
    
    <div class="col-md-4">   
         <div class="form-group">
        {{ Form::label('stops', 'Bus Stops') }}
        {{ Form::textarea('stops', '', array('class' => 'form-control')) }}
    </div>
    
     </div>
    
    <div class="col-md-4">   
    <div class="form-group">
        {{ Form::label('schoolId', 'School Id') }}
        {{ Form::select('schoolId', $schoolList,Input::old('schoolId'),array('class' => 'form-control')) }}
    </div>         
 
   <div class="form-group">
        {{ Form::label('routeId', 'Bus Route Number') }}
        {{ Form::text('routeId', Input::old('routeId'), array('class' => 'form-control')) }}

    </div>
   
  
    
    </div>
   
  </div>

    {{ Form::submit('Create Bus routes with Stops!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
                                    
                                            </div>
                                        </div>
                                </div>
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

