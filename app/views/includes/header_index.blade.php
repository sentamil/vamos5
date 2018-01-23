<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>GPS Admin</title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->

    <!-- Vendor styles -->
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.css" />
    
    <!-- search box -->
    <link rel="stylesheet" href="assets/dropDown/css/bootstrap-select.min.css">
    
    <link rel="stylesheet" href="plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css" type="text/css">
    <!-- App styles -->
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="styles/style.css">
    <!-- <link href="plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"> -->
</head>
<body onload="VdmDealersController.checkuser()">

<!-- Simple splash screen-->
<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1>GPS Admin</h1> </div> </div>
<!--[if lt IE 7]>
<p class="alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<!-- Header -->
<div id="header" style="height: 92px;">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version" style="padding-bottom: 67px; text-align: center;">
    <!--  <span>
           GPS Admin
        </span> -->
    </div>
    <nav role="navigation">
        <div class="header-link hide-menu" style="padding-top: 48px;"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <span class="text-primary">
			<table style="border: 2px solid #2d9b5e; margin-top: -95px; size:25px; margin-left: 81px; border-radius: 5px; border-spacing: 3px;
               border-collapse: separate; width: 75%;" >
                <tr><td style="padding-right:5px; border-right: 3px solid #eee; width: 37%;">
                     <select style="border: 0px;width: 100%;font-size: 75%;" id="choosen1" data-show-subtext="true" data-live-search="true">
                        <option value="">Search</option>
						<option value="device">Vehicle</option>
						<option value="group">Group</option>
						<option value="user">User</option>
						<option value="dealer">Dealer</option>
						<option value="org">Organization</option>
					</select></td>
					<td style="padding-left:5px;">
					<input id="getsearch1" type="search" style="border: 0px; width: 100%;" placeholder="Search" name="search" onkeyup ="validate(this)"></input></td>
					<td style="padding-left:5px;background-color:#2d9b5e; "> <a href="" id="ref1"><input  type="button" id="setsearch1" value="Submit" style="color:white;background-color:#2d9b5e; border: 0px;"/></a></td>
				</tr>
			</table>
			<table style="border: 2px solid #2d9b5e; margin-top: 5px;; size:25px; margin-left: 81px; border-radius: 5px; border-spacing: 3px;
				border-collapse: separate; width: 75%;" >
				<tr><td style="padding-right:5px; border-right: 3px solid #eee; width:37%;">
					<select style="border: 0px; width: 100%;font-size: 75%;" id="chooseToAdd1" data-show-subtext="true" data-live-search="true">
					@if(Session::get('cur')=='admin')   
					<option value="">Add</option>
					<option value="device">Vehicles</option>
					<option value="group">Group</option>
					<option value="user">User</option>
					<option value="dealer">Dealer</option>
					<option value="org">Organization</option> 
					@endif
					@if(Session::get('cur')=='dealer')
					<option value="">Add</option>
					<option value="group">Group</option>
					<option value="user">User</option>
					<option value="dealer">Dealer</option>
					<option value="org">Organization</option>
					@endif 
					</select></td>
				<td style="padding-left:5px;">
				<input id="getadd1" type="number" style="border: 0px; width: 100%;" placeholder="Enter Quantity to add" name="Searchthis" min="1" max="50"></input></td>
				<td style="padding-left:5px;background-color:#2d9b5e; "> <a href="" id="addref1"><input id="setadd1" type="button" value="Add" style="color:white;background-color:#2d9b5e; border: 0px; padding-left: 28px;"/></a></td>
				</tr>
			</table>
		</span>
        </div>
        <form class="navbar-form-custom" method="post">
		<table style="border: 2px solid #2d9b5e; margin-top: 23px; size:20px; margin-left: 25px; border-radius: 5px; border-spacing: 3px;
				border-collapse: separate; width: 500px; height: 37px;">
		<tr><td style="padding-right:5px; border-right: 3px solid #eee; width: 100px;">
			<select style="border: 0px;" id="choosen" data-show-subtext="true" data-live-search="true">
				<option value="">Search</option>
				<option value="device">Vehicle</option>
				<option value="group">Group</option>
				<option value="user">User</option>
				<option value="dealer">Dealer</option>
				<option value="org">Organization</option>
			</select></td>
			<td style="padding-left:5px;">
				<input id="getsearch" type="search" style="border: 0px; width: 320px" placeholder="Search" name="search" onkeyup ="validate(this)"></input></td>
			<td style="padding-left:5px;background-color:#2d9b5e; "> <a href="" id="ref"><input  type="button" id="setsearch" value="Submit" style="color:white;background-color:#2d9b5e; border: 0px;"/></a></td>
		</tr>
		</table>
        <table style="border: 2px solid #2d9b5e; margin-top: -37px;; size:20px; margin-left: 580px; border-radius: 5px; border-spacing: 3px;
			border-collapse: separate; width: 514px; height: 37px;" >
		<tr>
			<td style="padding-right:5px; border-right: 3px solid #eee; width: 100px;">
				<select style="border: 0px;" id="chooseToAdd" data-show-subtext="true" data-live-search="true">
				@if(Session::get('cur')=='admin')
					<option value="">Add</option>
					<option value="device">Vehicles</option>
					<option value="group">Group</option>
					<option value="user">User</option>
					<option value="dealer">Dealer</option>
					<option value="org">Organization</option>
				@endif 
				@if(Session::get('cur')=='dealer')
					<option value="">Add</option>
					<option value="group">Group</option>
					<option value="user">User</option>
					<option value="dealer">Dealer</option>
					<option value="org">Organization</option>
				@endif  
                </select></td>
			<td style="padding-left:5px;">
				<input id="getadd" type="number" style="border: 0px; width: 320px" placeholder="Enter Quantity to add" name="Searchthis"  min="1" max="50"></input></td>
			<td style="padding-left:17px;background-color:#2d9b5e; "> <a href="" id="addref"><input id="setadd" type="button" value="Add" style="color:white;background-color:#2d9b5e; border: 0px;"/></a></td>
		</tr>
		</table>
	</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript">
function validate(element){
var word=element.value
var new1 = word.replace(/[\'\/~`\!@#\$%\^&\*\(\)\ \\\+=\{\}\[\]\|;:"\<\>,\.\?\\\']/,"")
element.value=new1
}
$('#getsearch').keypress(function (e) {
	if (e.which == 13) {
		$('#setsearch').click();
	}
});
$('#setsearch').on('click', function() {
 var data1={
    'val1':$('#choosen').val(),
    'val2':$('#getsearch').val()

 };
 if (data1.val1!=''&& data1.val2=='')
 {
    alert('Please enter search value or text' ); 
    $('#ref').attr('href', 'Business');
 }
 else
 {
	if(data1.val1 == 'device'){
		$('#ref').attr('href', 'VdmVehicleScan'+data1.val2);
	}
	else if(data1.val1 == 'group'){
		$('#ref').attr('href', 'vdmGroupsScan/Search'+data1.val2);
		//document.getElementById("getadd").value = "My value";
	}
	else if(data1.val1 == 'user'){
		//$('#myFormId').attr('action', 'vdmUserScan/user');
		$('#ref').attr('href', 'vdmUserScan/user'+data1.val2);
	}
	else if(data1.val1 == 'dealer'){
		$('#ref').attr('href', 'vdmDealersScan/Search'+data1.val2);
	}
	else if(data1.val1 == 'org'){
		$('#ref').attr('href', 'vdmOrganization/adhi'+data1.val2);		
	}
	else{
		alert('Please Select Your Search Option' );
		$('#ref').attr('href', 'Business');
	}
}
//alert('this is'+data1.val1+' '+data1.val2);
});

$('#chooseToAdd').keypress(function (e) {
	console.log('+++++++++++++++');
	if (e.which == 13) {
	$('#setadd').click();
	}
});

$('#getadd').keypress(function (e) {
	check=document.getElementById("getadd").value
	if(check!=null)
	{
		if (e.which == 13) {
		$('#setadd').click();
		}
	}
});

$('#chooseToAdd').on('change', function() {
	var data={
	'val1':$('#chooseToAdd').val()
	}
    if(data.val1 =='')
	{
		alert('Please Select Your Add option');
		$('#addref').attr('href', 'Business');
	}
	else{
		if(data.val1 == 'device')
		{
			document.getElementById("getadd").value = "";
			document.getElementById("getadd").style.backgroundColor = "#ffffff ";
			document.getElementById("getadd").readOnly = false;
			$('#setadd').on('click', function() {
				var datas={
				'val':$('#getadd').val()
				}  
				if(datas.val=='')
				{
					alert('Enter Quantity to Add');
					$('#addref').attr('href', 'Business');
				}
				else if (datas.val>='1' && datas.val <='50')
				{
					$('#addref').attr('href', 'addDevice'+datas.val);
				}
				else
				{
					$('#addref').attr('href', 'Business');
				}
			});

		} 
		else if(data.val1 == 'user')
		{
			document.getElementById("getadd").value =" "; 
			document.getElementById("getadd").style.backgroundColor = "#eff1f4";
			document.getElementById("getadd").readOnly = true;
			document.getElementById("getadd").placeholder ="";
			$('#addref').attr('href', 'vdmUsers/create');
			//alert($('#getadd').val());
		}
		else if(data.val1 == 'group')
		{
			document.getElementById("getadd").value =" ";
			document.getElementById("getadd").style.backgroundColor = "#eff1f4";
			document.getElementById("getadd").readOnly = true;
			document.getElementById("getadd").placeholder ="";
			$('#addref').attr('href', 'vdmGroups/create');
		}
		else if(data.val1 == 'dealer')
		{
			document.getElementById("getadd").value = " ";
			document.getElementById("getadd").style.backgroundColor = "#eff1f4";
			document.getElementById("getadd").readOnly = true;
			document.getElementById("getadd").placeholder ="";
			$('#addref').attr('href', 'vdmDealers/create');
		}
		else if(data.val1 == 'org')
		{
			document.getElementById("getadd").value = " ";
			document.getElementById("getadd").style.backgroundColor = "#eff1f4";
			document.getElementById("getadd").readOnly = true;
			document.getElementById("getadd").placeholder ="";
			$('#addref').attr('href', 'vdmOrganization/create');
		}
		else
		{ console.log('nothing----selected');
			alert('Please Select Your Add Option');
			$('#addref').attr('href', 'Business');
		}
	}
});

$('#addref').on('click', function() {
	var data={
	'val1':$('#chooseToAdd').val()
	}
	if(data.val1 == '')
	{
		alert('Please Select Your Add Option');
		$('#addref').attr('href', 'Business');
	}
});

//script two

$('#setsearch1').on('click', function() {
	var data1={
    'val1':$('#choosen1').val(),
    'val2':$('#getsearch1').val()
	};
	if (data1.val1!='' && data1.val2=='')
    {
       alert('Please enter search value or text' ); 
       $('#ref1').attr('href', 'Business');
	}
   else{
		if(data1.val1 == 'device')
		{
			$('#ref1').attr('href', 'VdmVehicleScan'+data1.val2);
		}
		else if(data1.val1 == 'group')
		{
			$('#ref1').attr('href', 'vdmGroupsScan/Search'+data1.val2);
		}
		else if(data1.val1 == 'user')
		{
			$('#ref1').attr('href', 'vdmUserScan/user'+data1.val2);
		}
		else if(data1.val1 == 'dealer')
		{
			$('#ref1').attr('href', 'vdmDealersScan/Search'+data1.val2);
		}
		else if(data1.val1 == 'org')
		{
			$('#ref1').attr('href', 'vdmOrganization/adhi'+data1.val2);
		}
		else{
			alert('Please Select Your Search Option' );
			$('#ref1').attr('href', 'Business');
		}
	}
//alert('this is'+data1.val1+' '+data1.val2);

});

$('#chooseToAdd1').on('change', function() {
	var data={
	'val1':$('#chooseToAdd1').val()
	} 
	if(data.val1 == 'device')
	{
		document.getElementById("getadd1").value = "";
		document.getElementById("getadd1").style.backgroundColor = "#ffffff ";
		document.getElementById("getadd1").readOnly = false;
		$('#setadd1').on('click', function() {
		var datas={
		'val':$('#getadd1').val()
		}
		if(datas.val=='')
		{
			alert('Enter Quantity to Add');
			$('#addref1').attr('href', 'Business');
		}
    	else if (datas.val>='1' && datas.val <='50')
		{
			$('#addref1').attr('href', 'addDevice'+datas.val);
		}
		else
		{
        $('#addref1').attr('href', 'Business');
		}
		});
	}
	else if(data.val1 == 'user')
	{
		document.getElementById("getadd1").value =" "; 
		document.getElementById("getadd1").style.backgroundColor = "#eff1f4";
		document.getElementById("getadd1").readOnly = true;
		document.getElementById("getadd1").placeholder ="";
		$('#addref1').attr('href', 'vdmUsers/create');
	}
	else if(data.val1 == 'group')
	{
		document.getElementById("getadd1").value =" ";
		document.getElementById("getadd1").style.backgroundColor = "#eff1f4";
		document.getElementById("getadd1").readOnly = true;
		document.getElementById("getadd1").placeholder ="";
		$('#addref1').attr('href', 'vdmGroups/create');
	}
	else if(data.val1 == 'dealer')
	{
		document.getElementById("getadd1").value = " ";
		document.getElementById("getadd1").style.backgroundColor = "#eff1f4";
		document.getElementById("getadd1").readOnly = true;
		document.getElementById("getadd1").placeholder ="";
		$('#addref1').attr('href', 'vdmDealers/create');
	}
	else if(data.val1 == 'org')
	{
		document.getElementById("getadd1").value = " ";
		document.getElementById("getadd1").style.backgroundColor = "#eff1f4";
		document.getElementById("getadd1").readOnly = true;
		document.getElementById("getadd1").placeholder ="";
		$('#addref1').attr('href', 'vdmOrganization/create');
	}
	else
	{
		alert('Please Select Your Add Option');
		$('#addref1').attr('href', 'Business');
	}
});

$('#addref1').on('click', function() {
	var data={
	'val1':$('#chooseToAdd1').val()
	}
	if(data.val1 == '')
	{
		alert('Please Select Your Add Option');
		$('#addref1').attr('href', 'Business');
	}
});
</script>
		
        <div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                <li class="dropdown">
                    <a href="logout">
                        <i class="pe-7s-upload pe-rotate-90"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!-- Navigation -->
<aside id="menu">
	<ul class="nav" id="side-menu" style="padding-top: 0px; margin-top: 30px;">
		<li style="padding-bottom: 13px; padding-top: 13px; text-align: center; background-color: white;">
			<span class="font-extra-bold font-uppercase nav-label"><font color="#8aa52d">{{Auth::user ()->username }}</font>-GPS ADMIN</span>
        </li>	
        <li>
			<a href="vdmVehicles/dashboard"> <span class="nav-label">Dashboard</span></a>
        </li>
        
    <!-- <li>
            <a href="Licence"> <span class="nav-label">Licence</span></a>
         </li> -->

        
        <li>
            <a href="Routes"> <span class="nav-label">Business</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level">
                    
            @if(Session::get('cur')=='admin')                   
                     <li>
                <a href="Business/create"> <span class="nav-label">Add Device</span></a>
            </li>
            <li>
                <a href="Remove/create"> <span class="nav-label">Remove Device</span></a>
            </li>
            <li>
                <a href="Device"> <span class="nav-label">Onboard Devices</span></a>
            </li>
            <li>
                <a href="DeviceScan"> <span class="nav-label">Onboard Devices Search</span><span class="label label-success pull-right">NEW</span></a>
            </li>
            @endif 
            @if(Session::get('cur')=='dealer')
            <li><a href="Business"> <span class="nav-label">Device List</span> <span class="label label-success pull-right">v.2</span> </a></li>
        @endif 
                </ul>
            
                
            </li>
        
        

            <li>
            <a href="Routes"> <span class="nav-label">Vehicles</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level">
            
            <!--
            <li>
                <a href="vdmVehicles/create"> <span class="nav-label">Add Vehicles</span></a>
            </li>
            -->
            <li><a href="VdmVehicleScan"> <span class="nav-label">Vehicles Search</span> </a></li>
            <li><a href="vdmVehicles"> <span class="nav-label">Vehicles List</span> </a></li>
            
            <li><a href="vdmVehiclesView"> <span class="nav-label">View Vehicles</span> </a></li>

            <li><a href="rfid/create"> <span class="nav-label">Add Tags</span> <span class="label label-success pull-right"></span> </a></li>
            <li><a href="rfid"> <span class="nav-label">View Tags</span> <span class="label label-success pull-right"></span> </a></li>
            <!--
            <li>
                <a href="vdmVehicles/multi"> <span class="nav-label">Add Multiple Vehicles</span></a>
                
            </li>
            -->
        
                </ul>
            
                
            </li>
           
            
            <li>
            <a href="Routes"> <span class="nav-label">Groups</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level">
                     <li>                            
                <a href="vdmGroups/Search"> <span class="nav-label">Groups Search</span> <span class="label label-success pull-right">v.3</span> </a>       
            </li>
             <li>
                <a href="vdmGroups"> <span class="nav-label">Groups List</span></a>
            </li> 
            <li> <a href="vdmGroups/create"> <span class="nav-label">Add Group</span></a></li>
                </ul>
            
               
            </li>
         
            
        
            <li >
            
            <a href="Routes"> <span class="nav-label">Users</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level">
                     <li>                            
                <a href="vdmUserSearch/Scan" > <span class="nav-label" >User Search</span> <span class="label label-success pull-right">v.3</span> </a>     
            </li>
                     <li>
                <a href="vdmUsers" > <span class="nav-label" >User List</span></a>
            </li>
            <li>  <a href="vdmUsers/create"> <span class="nav-label">Add User</span></a></li>
                </ul>
            
            
                
            </li>
            
            @if(Session::get('cur')=='admin')
            <li>
        
        <a href="Routes"> <span class="nav-label">Dealers</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level">
                     <li>                            
                <a href="vdmDealersSearch/Scan"> <span class="nav-label">Dealers Search</span> <span class="label label-success pull-right">v.3</span> </a>     
            </li>
                     <li>
                <a href="vdmDealers"> <span class="nav-label">Dealers List</span></a>
            </li>
            <li>  <a href="vdmDealers/create"> <span class="nav-label">Create Dealer</span></a></li>
                </ul>
                       
            </li>@endif 
            
            
             <li>
             
             <a href="Routes"> <span class="nav-label">Organisation</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level">
                     <li>
               <a href="vdmOrganization/Scan"> <span class="nav-label">Organization Search</span> <span class="label label-success pull-right">v.3</span> </a>
            </li>
                     <li>
               <a href="vdmOrganization"> <span class="nav-label">Organization List</span></a>
            </li>
            <li>  <a href="vdmOrganization/create"> <span class="nav-label">Add Organization</span></a></li>
                
             <li>  <a href="vdmOrganization/placeOfInterest"> <span class="nav-label">Add POI</span></a></li>
                </ul>
             
                
            </li>
            
            <li {{ Request::is( 'Routes') ? 'active' : '' }}>
                <a href="Routes"> <span class="nav-label">Routes</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                     <li><a href="vdmBusRoutes/create">Create Bus Routes with Stops</a></li>
                     @if(Session::get('cur')=='admin')
                     <li><a href="vdmBusRoutes/roadSpeed">Road Speed</a></li>
                     @endif 
                </ul>
            </li>
            
            <li>
                <a href="vdmSmsReportFilter"> <span class="nav-label">SMS Reports</span></a>
            </li>
                @if(Session::get('cur')=='admin')
            <li>
                <a href="vdmVehicles/dealerSearch"> <span class="nav-label">Switch Login</span></a>
            </li>@endif 
            
            <li>
               @if(Session::get('cur')=='dealer')
             
        <a href="{{ URL::to('vdmDealers/editDealer/' .Session::get('cur')) }}"> <span class="nav-label">My Profile</span></a> 
        @endif 
        
            </li>
             <li><a href="logout"> <span class="nav-label">LogOut</span></a></li> 

        </ul>
   </aside>