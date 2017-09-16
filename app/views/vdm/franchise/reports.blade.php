@extends('includes.vdmheader')
@section('mainContent')
<div id="wrapper" onload="hideButton();">

	<div class="container-fluid" >
	
					<div class="hpanel">



						<div class="navbar navbar-inverse navbar-fixed-top">
							{{ Form::open(array('url' => 'vdmFranchises/updateReports')) }}
							<div class="row">
		            		
		            			<div class=" heading col"  >{{ Form::label('fcode', 'fcode :')}} {{ Form::label('fcode', $fcode)  }}
		            				{{ Form::hidden('fcode', $fcode) }}</div>
		            			
		            		 <div class="PlaceButton col">{{ Form::submit('Update the User!', array('class' => 'btn btn-warning')) }}</div>
		            		  <div class="PlaceButton col"><p class="btn btn-info" id="togglee" onclick = "toggle('fun',3)">Select All</p></div>
		            		  <div class="PlaceButton col"><p class="btn btn-danger" id="toggleeOff" onclick = "toggle('fun',4)">Unselect All</p></div>

		            		</div>
		            	</div>

		            	
		            	<div class="row" style="margin-top: 9%;">
		            		@foreach($totalList as $keys => $reportName)
		            		<div class="col-sm-3" style="padding: 2%; min-height:500px; max-height:500px; ">
   								 <div class="reportHeading row"  style="padding: 10px;">
							    	<p class="btn btn-success col" onclick = "toggle('{{ $keys }}',1)"> <span  onclick = "toggle('{{ $keys }}',1)" class="glyphicon glyphicon-ok"></span></p> 
							    	<p class="btn btn-danger col" onclick="toggle('{{ $keys }}',2)" ><span class="glyphicon glyphicon-remove">	</p>
							    	&nbsp;&nbsp;
						      		<span class="headingFont col" style="padding-top: 5px;padding-left: 28px;">{{Form::label('ReportName ', $keys)}}</span>
						    	</div>

  								
  							@foreach($reportName as $value)
  							<div class="card card-cascade" style="padding-left:10px;padding-top: 5px;" >
  								<span >{{ Form::checkbox('reportName[]', $value, in_array($value,$userReports), ['class' => $keys,'id' => 'questionCheckBox']) }}</span>
  								<span class="basicFont">{{Form::label('ReportName ', $value)}}</span>
  							</div>
  							@endforeach
  							
  							</div>
  							
  							@endforeach
		            	</div>

		            		
		 		        </div>
		            		{{ Form::close() }}
		            	</div>
						
		</div>
	</div>

<script language="JavaScript">





function toggle(val,fn) {

	// alert(fn);
	
    var defult =`.`;
    var className = defult+val;
    if (fn == 1){
        

        $(className).prop('checked', true); 

    }
    if (fn == 2){
        $(className).attr('checked', false); 
    }

    if (fn == 3)
    {
    	$("input[id='questionCheckBox']:checkbox").prop('checked',true);
    	 document.getElementById('togglee').style.visibility = 'hidden';
    	 document.getElementById('toggleeOff').style.visibility = '';

    }
     if (fn == 4)
    {
    	$("input[id='questionCheckBox']:checkbox").prop('checked',false);
    	 document.getElementById('toggleeOff').style.visibility = 'hidden';
    	 document.getElementById('togglee').style.visibility = '';

    }
 // document.getElementById('questionCheckBox').checked = true;
    console.log("Hello "+className);
}




</script>

<style type="text/css">
		
.heading,.UserId{

	float: left;
	margin-left: 1%;
	margin-top: 10px;
	font-size: 18px;
    color: white;
    font-family: 'Quicksand', sans-serif;
}

.navbar{

	min-height : 40px !important;
}

.PlaceButton{

	margin-top: 10px;
	font-family: 'Quicksand', sans-serif;
	font-size: 15px ;
	float: right;
	margin-right: 2%;
	
}

.headingFont{

	font-family: 'Quicksand', sans-serif;
	font-size: 13px ;
}

.basicFont{

	font-family: 'Quicksand', sans-serif;
	font-size: 13px ;
	font-weight: 500;
}

@media (min-width: 768px)
{
.navbar {
    border-radius: 4px;
    margin-right: 19%;
    height: 48px;
    width: 100%;
    padding-left: 10px;
  }
}

.reportHeading {

	background-color: #d3d3d3;
	border-radius: 5px;
	color: black;
	padding-left: 10px;
	border-color: black;
	border-style: solid;
	border-width: 2px;
}


</style>

<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
