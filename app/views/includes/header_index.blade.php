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
<div id="header">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version">
    <!--  <span>
           GPS Admin
        </span> -->
    </div>
    <nav role="navigation">
        <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <span class="text-primary">HOMER APP</span>
        </div>
        <!--<form role="search" class="navbar-form-custom" method="post" action="#">
            <div class="form-group">
                <input type="text" placeholder="Search something special" class="form-control" name="search">
            </div>
        </form>-->
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
    <div id="navigation">
        <div class="profile-picture">
    <a href="live">
            
               <!--  <img src="assets/imgs/logo.png" class="img-circle m-b" alt="logo"> -->
            
            </a>
 <div class="stats-label text-color">
                <span class="font-extra-bold font-uppercase"><font color="#8aa52d">{{Auth::user ()->username }}</font>-GPS ADMIN</span>
            </div>

        </div>  
        <ul class="nav" id="side-menu">

        
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
             <!-- <li>
                <a href="vdmGroups"> <span class="nav-label">Groups List</span></a>
            </li> -->
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
            <li>  <a href="vdmDealers/create"> <span class="nav-label">Dealer Create</span></a></li>
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
    </div>
</aside>