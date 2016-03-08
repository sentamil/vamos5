<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Satheesh">
    <title>GPS</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/jVanilla.css" rel="stylesheet">
    <link href="assets/css/site.css" rel="stylesheet">
    <link href="assets/font-awesome-4.2.0/css/font-awesome.css" rel="stylesheet">
    <style type="text/css">
        #map_canvas{
            width:100%;
            height: 100vh; 
        }
    </style>
</head>
<body ng-app="mapApp">
    <div id="wrapper" ng-controller="mainCtrl">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand"><a href="javascript:void(0);"><img src="assets/imgs/logo.png"/></i></a></li>
                <li class="track"><a href="../public/live"><div></div><label>Track</label></a></li>
                <li class="history"><a href="../public/replay"><div></div><label>History</label></a></li>
                <li class="alert01"><a href="../public/reports"><div></div><label>Reports</label></a></li>
                <li class="stastics"><a href="../public/statistics"><div></div><label>Statistics</label></a></li>
                <li class="admin"><a href="../public/performance"><div></div><label>Performance</label></a></li>
                <!-- <li class="settings"><a href="../public/settings"  class="active"><div></div><label>Settings</label></a></li>
                <li class="admin"><a href="javascript:void(0);"><div></div><label>Admin</label></a></li> -->
                <li><a href="../public/logout"><img src="assets/imgs/logout.png"/></a></li>
            </ul>
        </div>
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div style="position: absolute;top: 0px;left: 35%; z-index:999999;">
                            <div style="box-shadow:0px 8px 17px rgba(0, 0, 0, 0.2)  !important;">
                                    <nav>
                                        <ul class="menu-hover">
                                            <li class="list"> <a href=""> Maps </a> 
                                                <ul class="menu-hover">
                                                    <li class="list"><a href="../public/replay">History</a></li>
                                                    <li class="list"><a href="../public/sites">Site Details</a></li>
                                                </ul>
                                            </li>
                                            <li class="list"> <a href=""> Categories </a>
                                                <ul class="menu-hover">
                                                    <li class="list"><a href="">All jQuery </a></li>
                                                    <li class="list"><a href="">Accordion</a></li>
                                                    <li class="list"><a href="">All jQuery </a></li>
                                                    <li class="list"><a href="">Accordion</a></li>
                                                    <li class="list"><a href="">All jQuery </a></li>
                                                    <li class="list"><a href="">Accordion</a></li>
                                                </ul>
                                            </li>
                                            <li class="list"> <a href=""> jQuery Plugins </a>
                                                <ul class="menu-hover">
                                                    <li class="list"><a href=""> Latest </a></li>
                                                    <li class="list"><a href=""> Additions ha ad a a</a></li>
                                                    <li class="list"><a href="">All jQuery </a></li>
                                                    <li class="list"><a href="">Accordion</a></li>
                                                </ul>
                                            </li>
                                            <li class="list"> <a href=""> Popular </a> </li>
                                            <li class="list"> <a href=""> Recommended </a></li>
                                            <li class="list"> <a href=""> Publishing </a>
                                                <ul class="menu-hover">
                                                    <li class="list"><a href="#"> Test 1 </a></li>
                                                    <li class="list"><a href="#"> Test 2 </a></li>
                                                    <li class="list"><a href="#"> Test 3 </a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                <div style="position: absolute;top: 30px;right: 20px; z-index:999999; width: 300px;padding: 10px;background: #fff;-webkit-border-radius: 12px;-moz-border-radius: 12px;border-radius: 12px;">
                    <div style="width:100%; background: #F4F4F4; font-weight: bold; font-size: 12px; padding : 3px" align="center">Add Sites</div>
                        <div class="siteInput" > 
                            <table cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr><td colspan="2"></td></tr>
                                    <tr>
                                        <td><b>Site Name</b></td>
                                        <td><input name="txt" type="text" ng-model="textValue"/> </td>
										 <td>

									<select id="drop" ng-model="dropValue" value = $orgArr>
                                                
											</select>						
										 </td>
                                    </tr>
                                    <tr>
                                        <td><b>Type</b></td>
                                        <td>
                                            <select id="drop" ng-model="dropValue">
                                                <option value = "Home Site">Home Site</option>
                                                <option value = "Client Site">Client Site</option>
                                                <option value = "Restricted Site">Restricted Site</option>
                                                <option value = "Other">Other</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <input type="button" value="Create" ng-click="drawline()"/>
                                            <input type="button" value="Update"/>
                                            <input type="button" value="Clear Map" ng-click="clearline()"/>
                                        </td>                            
                                    </tr>
                                </tbody>
                            </table>
							{{ Form::select('orgId', array($orgArr),  array('class' => 'form-control')) }} 
							var_dump($orgArr);
							var_dump($Sites);
							@foreach($orgArr as $key => $value)
										<tr style="text-align: center;">
							
							<td>{{ $value }}</td>
							</tr>
										@endforeach
                            <hr/>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Site Name</th>
                                        <th>Type</th>
                                        <th>Action</th>
										@foreach($Sites as $key => $value)
										<tr style="text-align: center;">
							<td>{{ $key }}</td>
							<td>{{ $value }}</td>
							</tr>
										@endforeach
										
										
						
                                    </tr>
                                </thead>
                                <tbody>
                                    <td>
                                       
                                    </td>
                                    <td></td>
                                </tbody>
                            </table>    
                        </div>       
                    </div>
                <div id="maploc">
                    <map id="map_canvas" style="height:100vh; width:100%;"></map>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/static.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places" type="text/javascript"></script>
    <script src="assets/js/vamoApp.js"></script>
    <script src="assets/js/services.js"></script>
    <script src="assets/js/addsite.js"></script>
</body>
</html>