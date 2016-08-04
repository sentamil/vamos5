<?php
 
/*
 * |-------------------------------------------------------------------------- | Application Routes |-------------------------------------------------------------------------- | | Here is where you can register all of the routes for an application. | It's a breeze. Simply tell Laravel the URIs it should respond to | and give it the Closure to execute when that URI is requested. |
 */
View::addExtension('html', 'php');
Route::get('/live', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    } else {
        return View::make('maps.index');
    }
});
 
Route::get('/apiAcess', function() {
    Log::info(' api acess ');
    return View::make('maps.api');

});

Route::get('/replay', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('maps.replay');
});


 
Route::get('/ElectionCommisionTrustedClient', function() {
    Log::info( '-------login-----' );
    $user=Input::get('userId');
    Log::info(' users name ' . $user);
    $redis = Redis::connection ();
//ElectionCommisionUser
    if($redis->sismember('ElectionCommisionUser', $user)=='1') {
        $user1=User::where('username', '=', $user)->firstOrFail();
        Log::info(' users name ' . $user1);
        Auth::login($user1);
        return View::make('maps.index');
    }
    else {
        return Redirect::to('login');

    }
});


Route::get('/history', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('maps.history');
});

View::addExtension('html', 'php');
Route::get('/track', function() {
  
    return View::make('maps.trackSingleVeh');
});

Route::get('/trackSingleVeh', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $single = strstr($actual_link , 'single');
    $multi = strstr($actual_link , 'multiTrack');

    if ($single)
    {

        return View::make('maps.track');
        log::info(' single -->'.$actual_link);
    } 
    else if ($multi) 
    {
        return View::make('maps.multiTracking');
        log::info(' multi -->'.$actual_link);
    }
});

Route::get('/multiple_vehicle', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('maps.multiTracking');
});

Route::get('/alarm', function(){
    if(!Auth::check()){
        return Redirect::to('login');
    }
    return View::make('reports.alarmReport');
});
 
Route::get('/allVehicles', function() {
    $user = User::find(1);
    Auth::login($user);
    return View::make('maps.index');
});
// View::addExtension('html', 'php');
// Route::get('/liveTrack', function() {
   
//     return View::make('maps.track');//
// });
 
Route::get('/settings', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('maps.settings');
});
 
View::addExtension('html', 'php');
Route::get('/menu', function() {
    // if (!Auth::check()) {
    //     return Redirect::to('login');
    // }
    return View::make('maps.menu.menu');
});
 
 

 
View::addExtension('html', 'php');
Route::get('/reports', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('reports.current');
});
 
Route::get('/fuel', function(){
    if(!Auth::check()){
        return Redirect::to('login');
    }
    return View::make('reports.fuel');
});
 
View::addExtension('html', 'php');
Route::get('/downloadreport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('reports.downloadreport');
});
 
View::addExtension('html', 'php');
Route::get('/history', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('reports.history');
});
 
View::addExtension('html', 'php');
Route::get('/downloadhistory', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('reports.downloadhistory');
});

Route::get('/tripkms', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get getTripkms');
    return View::make('reports.tripReportKms');
});


Route::get('/temperature', function(){
    if(!Auth::check()){
        return Redirect::to('login');
    }
    Log::info(' temperature ');
    return View::make('reports.temperReport');
});

Route::get('/printStops', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('reports.stops');
});


Route::get('/trip', function(){
    if(!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('trip report');
    return View::make('reports.tripReport');
});
 
View::addExtension('html', 'php');
Route::get('/statistics', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('reports.statistics');
});
 
View::addExtension('html', 'php');
Route::get('/downloadstatistics', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('reports.downloadstatistics');
});
 
View::addExtension('html', 'php');
Route::get('/vehiclemanagement', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('reports.vehiclemanagement');
});
 
Route::get('/getVehicleLocations', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle Locations');
    return View::make('vls.getVehicleLocations');
});


Route::get('/getSelectedVehicleLocation1', function() {
     if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle getSelectedVehicleLocation1');
    return View::make('vls.getSelectedVehicleLocation1');
});

Route::get('/getTripSummary', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get getTripSummary');
    return View::make('vls.getTripSummary');
});
 
Route::get('/getActionReport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle Locations');
    return View::make('vls.getActionReport');
});
 

Route::get('/getTemperatureReport', function(){
    if(!Auth::check()){
        return Redirect::to('login');
    }
    Log::info(' get temperature api ');
    return View::make('vls.getTemperatureReport');
});


Route::get('/getOverallVehicleHistory', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle Locations');
    return View::make('vls.getOverallVehicleHistory');
});

Route::get('/getAlarmReport',function(){
    if(!Auth::check()){
        return Redirect::to('login');
    }
    Log::info('getAlarmReport');
    return View::make('vls.getAlarmReport');
});

Route::get('/getTripReport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('getTripReport');
    return View::make('vls.getTripReport');
});
 
Route::get('/getDriverPerformanceDaily', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('getDriverPerformanceDaily');
    return View::make('vls.getDriverPerformanceDaily');
});
 
Route::get('/getSiteReport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('getSiteReport');
    return View::make('vls.getSiteReport');
});
 
Route::get('/getVehicleExp', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle Expiry');
    return View::make('vls.getVehicleExp');
});
 
 
 
Route::get('/getPoiHistory', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get POI History');
    return View::make('vls.getPoiHistory');
});
 
 


 
 
Route::get('/liveTrack', function() {
        Log::info('get publicTracking Vehicle Locations');
         return View::make('maps.publictrack');
});
 
 
Route::get('/publicTracking', function() {
        Log::info('get publicTracking Vehicle Locations');
         return View::make('vls.publicTracking');
});
 
Route::get('/performance', function() {
          if (!Auth::check()) {
              return Redirect::to('login');
          }
          return View::make('reports.performanceChart');
      });
Route::get('/getOverallDriverPerformance', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('getOverallDriverPerformance');
    return View::make('vls.getOverallDriverPerformance');
});

 
Route::get('/getLoadReport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('getLoadReport');
    return View::make('vls.getLoadReport');
}); 
 
Route::get('/playBack', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('playback');
    return View::make('vls.simulator');
});
 
Route::get('/getGeoFenceReport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle GeoFence Reportt');
 
    return View::make('vls.getGeoFenceReport');
});
 
Route::get('/getGeoFenceView', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle GeoFence View');
 
    return View::make('vls.getGeoFenceView');
});
 
Route::get('/getSelectedVehicleLocation', function() {
 
    Log::info('get Selected Vehicle Location');
    return View::make('vls.getSelectedVehicleLocation');
});

Route::get('/getExecutiveReport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('getExecutiveReport');
    return View::make('vls.getExecutiveReport');
});

Route::get('/getOverallSiteLocationReport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('getOverallSiteLocationReport');
    return View::make('vls.getOverallSiteLocationReport');
});

Route::get('/getFuelDropFillReport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('getFuelDropFillReport');
    return View::make('vls.getFuelDropFillReport');
});
 
Route::get('getDistanceTimeFuelReport', function(){
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('vls.getDistanceTimeFuelReport');
});
 
 
Route::get('/getIndividualDriverPerformance', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('getIndividualDriverPerformance');
    return View::make('vls.getIndividualDriverPerformance');
});
 
Route::get('/getVehicleHistory', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle Locations');
    return View::make('vls.getVehicleHistory');
});
 
Route::get('/event', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('event report');
    return View::make('reports.eventReport');
});

Route::get('/siteReport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('site report');
    return View::make('reports.sitePerVehicle');
});

Route::get('/loadDetails', function(){
    if(!Auth::check()){
        return Redirect::to('login');
    }
    Log::info(' load deatils  ');
    return View::make('reports.loadReport');
});

Route::get('/getActionReport', function() {    
    if (!Auth::check()) {      
        return Redirect::to('login');      
    }      
    Log::info('get Vehicle Locations');    
    return View::make('vls.getActionReport');      
});    
 
 
Route::get('/admin', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('admin');
});
 
Route::get('/', array('uses' => 'HomeController@showLogin'));// Reg
 
 
Route::get('about', function() {
    return View::make('pages.about');
});
 
Route::get('register', function() {
    return View::make('pages.register');
});
 
Route::get('register', function() {
    return View::make('pages.register');
});
 
Route::get('viewSite', function() {
    return View::make('vls.viewSite');
});
 
;


Route::group(array('before' => 'userauth'), function(){ 
        Route::get('/sites', function() {
            if (!Auth::check()) {
                return Redirect::to('login');
            }
            return View::make('reports.siteDetails');
        });
         
        Route::get('/password_check', function() {
            if (!Auth::check()) {
                return Redirect::to('login');
            }
            return View::make('reports.admin_Auth');
        });

Route::post('vdmVehicles/updateLive/{param}', array('uses' => 'VdmVehicleController@updateLive'));

});
 
// route to show the login form
//Route::get('register', array('uses' => 'RegisterController@showRegister'));                                      //Reg
 
Route::get('login', array('uses' => 'HomeController@showLogin'));
 
Route::get('password/reset', array('uses' => 'RemindersController@getRemind', 'as' => 'password.remind'));
 
Route::post('password/reset', array('uses' => 'RemindersController@postRemind' , 'as' => 'password.postremind'));
 
Route::get('password/reset/{token}', array('uses' => 'RemindersController@reset', 'as' => 'password.reset'));
 
Route::post('password/reset/{token}', array('before' => 'csrf', 'uses' => 'RemindersController@update', 'as' => 'password.update'));
 
Route::post('userIds', array('as' => 'ajax.apiKeyAcess', 'uses'=>'HomeController@getApi')); 


 
  //vdmGeoFence
 
Route::get('passwordremind', array('uses' => 'RemindersController@getRemind'));
 
// route to process the form
//Route::group(array('before' => 'adminauth'), function(){
Route::post('login', array('before' => 'csrf', 'uses' => 'HomeController@doLogin'));
//});
Route::get('logout', array('uses' => 'HomeController@doLogout'));
 
Route::get('admin', array('uses' => 'HomeController@admin'));
Route::get('adhocMail', array('uses' => 'HomeController@adhocMail'));
 
Route::post('sendAdhocMail', array('before' => 'csrf', 'uses' => 'HomeController@sendAdhocMail'));
 
Route::get('liveReport', array('uses' => 'ReportsController@liveReport'));
 
 
 Route::get ( 'playBack', array (
 'uses' => 'PlayBackController@replay'
 ) );
 
Route::get('ipAddressManager', array('uses' => 'HomeController@ipAddressManager'));
 
Route::get('reverseGeoLocation', array('uses' => 'HomeController@reverseGeoLocation'));
 
Route::get('nearByVehicles', array('uses' => 'HomeController@nearByVehicles'));
 
Route::get('/getNearByVehicles', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Near By Vehicle Locations');
    return View::make('vls.getNearByVehicles');
});
 
Route::get('/vdmReports/movingOverview', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Near By Vehicle Locations');
    return View::make('reports.movingOverview');
});
Route::get('/vdmReports/mileageReport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Near By Vehicle Locations');
    return View::make('reports.mileageReport');
});
 
Route::post('ipAddressManager', array('before' => 'csrf', 'uses' => 'HomeController@saveIpAddress'));
 
//adminauth
 
Route::group(array('before' => 'adminauth'), function(){   //admin auth starts here
   

Route::get('vdmVehicles/calibrateOil/{param}', array('uses' => 'VdmVehicleController@calibrate'));


Route::get('vdmVehicles/calibrateOil/{param}/{param1}', array('uses' => 'VdmVehicleController@calibrate'));
Route::post('vdmVehicles/updateCalibration', array('uses' => 'VdmVehicleController@updateCalibration'));
Route::get('vdmVehicles/multi', array('uses' => 'VdmVehicleController@multi'));
Route::post('vdmVehicles/moveDealer', array('uses' => 'VdmVehicleController@moveDealer'));
Route::get('vdmVehicles/index1', array('uses' => 'VdmVehicleController@index1'));
 
Route::get('vdmVehicles/migration/{param1}', array('uses' => 'VdmVehicleController@migration'));
Route::post('vdmVehicles/findDealerList', array('uses' => 'VdmVehicleController@findDealerList'));
 
Route::get('vdmVehicles/stops/{param}/{param1}', array('uses' => 'VdmVehicleController@stops'));
//ramB/{param}/C/{param1?
Route::get('vdmVehicles/dashboard', array('uses' => 'VdmVehicleController@dashboard'));
 
Route::get('vdmVehicles/{param}/edit1', array('uses' => 'VdmVehicleController@edit1'));
Route::post('vdmVehicles/update1', array('uses' => 'VdmVehicleController@update1'));
 
 
 
 
Route::get('vdmVehicles/removeStop/{param}/{param1}', array('uses' => 'VdmVehicleController@removeStop'));
 
Route::get('vdmVehicles/stops1/{param}/{param1}', array('uses' => 'VdmVehicleController@stops1'));
 
Route::get('vdmVehicles/removeStop1/{param}/{param1}', array('uses' => 'VdmVehicleController@removeStop1'));
 
 
Route::post('vdmVehicles/generate', array('uses' => 'VdmVehicleController@generate'));
 
Route::post('vdmVehicles/migrationUpdate', array('uses' => 'VdmVehicleController@migrationUpdate'));
 
 
Route::post('vdmVehicles/storeMulti', array('uses' => 'VdmVehicleController@storeMulti'));
 
Route::resource('vdmGroups', 'VdmGroupController');
Route::get('vdmVehicles/create/{param1}', array('uses' => 'VdmVehicleController@create'));
Route::get('vdmVehicles/dealerSearch', array('uses' => 'VdmVehicleController@dealerSearch'));

Route::resource('vdmVehicles', 'VdmVehicleController');
Route::get('vdmVehicles/edit/{param1}', array('uses' => 'VdmVehicleController@edit'));
Route::resource('vdmVehiclesView', 'VdmVehicleViewController');
 
Route::resource('DashBoard', 'DashBoardController');
 
 
Route::resource('Business', 'BusinessController');
Route::resource('rfid', 'RfidController');
 Route::get('rfid/{param}/destroy', array('uses' => 'RfidController@destroy'));
 Route::get('rfid/editRfid/{param}', array('uses' => 'RfidController@edit1'));
 Route::post('rfid/index1', array('uses' => 'RfidController@index1'));

Route::post('Business/adddevice', array('uses' => 'BusinessController@adddevice'));

Route::post('rfid/addTags', array('uses' => 'RfidController@addTags'));
Route::post('rfid/update', array('uses' => 'RfidController@update'));
 Route::post('user_select', array('as' => 'ajax.user_select', 'uses' => 'RfidController@getVehicle'));
 Route::post('select', array('as' => 'ajax.checkvehicle', 'uses' => 'BusinessController@checkvehicle'));
 Route::post('select1', array('as' => 'ajax.checkDevice', 'uses' => 'BusinessController@checkDevice'));
 Route::post('select3', array('as' => 'ajax.checkUser', 'uses' => 'BusinessController@checkUser'));
  Route::post('select2', array('as' => 'ajax.getGroup', 'uses' => 'BusinessController@getGroup'));
Route::post('Business/batchSale', array('uses' => 'BusinessController@batchSale'));

// ajax call
Route::post('groupId', array('as' => 'ajax.groupIdCheck', 'uses'=>'VdmGroupController@groupIdCheck'));
Route::post('dealerId', array('as' => 'ajax.dealerCheck', 'uses'=>'VdmDealersController@dealerCheck'));
Route::post('orgId', array('as' => 'ajax.ordIdCheck', 'uses'=>'VdmOrganizationController@ordIdCheck'));
Route::post('userId', array('as' => 'ajax.userIdCheck', 'uses'=>'VdmUserController@userIdCheck'));


Route::post('vdmVehicles/calibrate/count', array('uses'=>'VdmVehicleController@calibrateCount'));

Route::resource('Device', 'DeviceController');
Route::resource('vdmUsers', 'VdmUserController');
Route::resource('Licence', 'LicenceController');
Route::get('vdmUsers/notification/{param}', array('uses' => 'VdmUserController@notification'));
Route::post('vdmUsers/updateNotification', array('uses' => 'VdmUserController@updateNotification'));

Route::get('Licence/ViewDevices/{param}', array('uses' => 'LicenceController@viewDevices'));
 
Route::resource('vdmDealers', 'VdmDealersController');
 
Route::get('vdmDealers/editDealer/{param}', array('uses' => 'VdmDealersController@editDealer'));
 
Route::resource('vdmSchools', 'VdmSchoolController');
 
Route::resource('vdmBusRoutes', 'VdmBusRoutesController');
 
Route::resource('vdmBusStops', 'VdmBusStopsController');
 
Route::resource('vdmGeoFence', 'VdmGeoFenceController');
Route::get('vdmOrganization/{param}/pView', array('uses' => 'VdmOrganizationController@pView'));
Route::get('vdmOrganization/{param}/editAlerts', array('uses' => 'VdmOrganizationController@editAlerts'));
Route::get('vdmOrganization/placeOfInterest', array('uses' => 'VdmOrganizationController@placeOfInterest'));
Route::post('vdmOrganization/addpoi', array('uses' => 'VdmOrganizationController@addpoi'));
Route::post('vdmOrganization/updateNotification', array('uses' => 'VdmOrganizationController@updateNotification'));

 
Route::get('vdmOrganization/{param}/poiEdit', array('uses' => 'VdmOrganizationController@poiEdit'));
 
Route::get('vdmOrganization/{param}/poiDelete', array('uses' => 'VdmOrganizationController@poiDelete'));
 
Route::get('vdmOrganization/{param}/getSmsReport', array('uses' => 'VdmOrganizationController@getSmsReport'));
Route::resource('vdmOrganization', 'VdmOrganizationController');
Route::post('vdmVehicles/calibrate/analog', array('uses' => 'VdmVehicleController@analogCalibrate'));
 
 
 
});   //admin auth ends here
Route::post('AddSiteController/store', array('uses' => 'AddSiteController@store'));
Route::post('AddSiteController/update', array('uses' => 'AddSiteController@update'));
Route::post('AddSiteController/delete', array('uses' => 'AddSiteController@delete'));
Route::post('AddSiteController/checkPwd', array('uses' => 'AddSiteController@checkPwd'));
Route::resource('AddSite', 'AddSiteController');
Route::get('vdmSmsReportFilter', array('uses' => 'VdmSmsController@filter'));
 
Route::post('vdmSmsReport', array('uses' => 'VdmSmsController@show'));
 

 
Route::get('vdmGeoFence/{token}', array('uses' => 'VdmGeoFenceController@show'));
 
Route::get('vdmGeoFence/{token}/view', array('uses' => 'VdmGeoFenceController@view'));
 
Route::post('vdmVehicles/storeMulti', array('uses' => 'VdmVehicleController@storeMulti'));
Route::get('vdmFranchises/fransearch', array('uses' => 'VdmFranchiseController@fransearch'));
Route::get('vdmFranchises/users', array('uses' => 'VdmFranchiseController@users'));
Route::resource('vdmFranchises', 'VdmFranchiseController');
Route::post('vdmFranchises/findFransList', array('uses' => 'VdmFranchiseController@findFransList'));
Route::post('vdmFranchises/findUsersList', array('uses' => 'VdmFranchiseController@findUsersList'));
 
 
 
 
 
Route::get('/setPOIName', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('setPOIName');
    return View::make('vls.setPOIName');
});
 
Route::get('file/download', function() {
    $file = 'path_to_my_file.pdf';
    return Response::download($file);
});
 
Route::get('/store', function() {
    return View::make('vls.geoCode');
});
 
Route::get('/download', function() {
    // PDF file is stored under project/public/download/info.pdf
    $file = public_path() . "/reports/sample.txt.gz";
    $headers = array('Content-Type: application/zip');
    return Response::download($file, 'sample.txt.gz', $headers);
});
 
//Route::get('/json', array('uses' =>'ManiController@testMani'));
//Route::post('/meena', array('uses' => 'MeenaController@loginMeena'));
//Route::get('/lmeena', array('uses' => 'MeenaController@testMeena'));
//Route::get('/Meena1',array('uses'=>'Meena1Controller@testmeena'));
//Route::get('/Meena2',array('uses'=>'Meena2Controller@testMeena2'));
//Route::get('/Meena3',array('uses'=>'Meena3Controller@testMeena3'));
//Route::get('/Fish',array('uses'=>'FishController@testFish'));
//Route::get('/Array',array('uses'=>'ArrayController@testArray'));
//Route::get('/Correction',array('uses'=>'CorrectionController@testCorrection'));
Route::get('/SmsReport',array('uses'=>'SmsReportController@testSmsReport'));
Route::post('/SmsReport',array('uses'=>'SmsReportController@testSmsReport'));
Route::get('/Test',array('uses'=>'TestController@postAuth'));
Route::get('/Example',array('uses'=>'ExampleController@testExample'));
Route::get('/Hello',array('uses'=>'HelloController@testHello'));
Route::post('/meenatest',array('uses'=>'HelloController@meenatest'));



