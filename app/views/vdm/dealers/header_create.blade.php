<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>VAMOSGPS</title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->

    <!-- Vendor styles -->
	<link rel="stylesheet" href="../vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="../vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="../vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="../vendor/bootstrap/dist/css/bootstrap.css" />

    <!-- App styles -->
    <link rel="stylesheet" href="../fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="../fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="../styles/style.css">
	<link href="../plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css">
</head>
<body>

<!-- Simple splash screen-->
<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1>Welcome To VAMOSGPS</h1><img src="images/loading-bars.svg" width="64" height="64" /> </div> </div>
<!--[if lt IE 7]>
<p class="alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<!-- Header -->
<div id="header">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version">
        <span>
           VAMOSGPS
        </span>
    </div>
    <nav role="navigation">
        <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <span class="text-primary">HOMER APP</span>
        </div>
        <form role="search" class="navbar-form-custom" method="post" action="#">
            <div class="form-group">
                <input type="text" placeholder="Search something special" class="form-control" name="search">
            </div>
        </form>
        <div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                <li class="dropdown">
                    <a href="../logout">
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
            <a href="index.html">
                <img src="../assets/imgs/logo.png" class="img-circle m-b" alt="logo">
            </a>

            <div class="stats-label text-color">
                <span class="font-extra-bold font-uppercase">Admin</span>
            </div>
        </div>
        <ul class="nav" id="side-menu">
            <li>
                <a href="../vdmVehicles"> <span class="nav-label">Vehicles List</span> <span class="label label-success pull-right">v.2</span> </a>
            </li>
            <li>
                <a href="../vdmVehicles/create"> <span class="nav-label">Add Vehicles</span></a>
                
            </li>
            <li>
                <a href="../vdmVehicles/multi"> <span class="nav-label">Add Multiple Vehicles</span></a>
                
            </li>
            <li>
                <a href="../vdmGroups"> <span class="nav-label">Groups List</span></a>
            </li>
			<li>
                <a href="../vdmGroups/create"> <span class="nav-label">Create Group</span></a>
            </li>
            
            <li {{ Request::is( '../Routes') ? 'active' : '' }}>
                <a href="../Routes"> <span class="nav-label">Routes</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="../vdmSchools">Schools List</a></li>
                      <li><a href="../vdmBusRoutes/create">Create Bus Routes with Stops</a></li>
                    <li><a href="../vdmGroups">Group List-2</a></li>
                </ul>
            </li>
            
            <li>
                <a href="../vdmUsers"> <span class="nav-label">User List</span></a>
            </li>
            <li>
                <a href="../vdmUsers/create"> <span class="nav-label">User Create</span></a>
            </li>
			<li>
                <a href="../vdmMessage/create"> <span class="nav-label">Message Reports</span></a>
                
            </li>
        </ul>
    </div>
</aside>
