@extends('includes.vdmheader')

@section('mainContent')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
               		 <div class="panel-heading" align="center">
                  		<h4><b><font> Show Tags </font></b></h4>
                	</div>
               		 <div class="panel-body">
               		 	<hr>
						{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'rfid/index1')) }}
						<div class="row">
							<div class="col-md-3"></div>
							<div class="col-md-3" style="font-size: 20px">Select Organisation Name :</div>
							<div class="col-md-3">{{ Form::select('org' , $orgList, Input::old('orgname'),array('id' => 'orgid', 'class'=>'form-control selectpicker show-menu-arrow','data-live-search' => 'true')) }}</div></br>
						</div>
						<br>
						<div class="row">
							<div class="col-md-3"></div>
							
						</div>
						<br>
						<div class="row">
							<div class="col-md-3"></div>
							
						</div>
						<br>
						<div class="row">
							<div class="col-md-3"></div>
							<div class="col-md-6">
								<table id="example1" class="table table-bordered dataTable">
									<thead>
										<tr>
											
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-6"></div>
							<div class="col-md-3">{{ Form::submit('Submit', array('class' => 'btn btn-primary ')) }}{{ Form::close() }}</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<br>
<hr>
<div align="center">@stop</div>
</body>
</html>	
