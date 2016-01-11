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

Route::get('/replay', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('maps.replay');
});

Route::get('/history', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('maps.history');
});

Route::get('/track', function() {
   
    return View::make('maps.track');
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

Route::get('/sites', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('reports.siteDetails');
});


View::addExtension('html', 'php');
Route::get('/reports', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('reports.current');
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

Route::get('/getActionReport', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle Locations');
    return View::make('vls.getActionReport');
});


Route::get('/getOverallVehicleHistory', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle Locations');
    return View::make('vls.getOverallVehicleHistory');
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

// route to show the login form
//Route::get('register', array('uses' => 'RegisterController@showRegister'));			//Reg

Route::get('login', array('uses' => 'HomeController@showLogin'));

Route::get('password/reset', array('uses' => 'RemindersController@getRemind', 'as' => 'password.remind'));

Route::post('password/reset', array('uses' => 'RemindersController@postRemind' , 'as' => 'password.postremind'));

Route::get('password/reset/{token}', array('uses' => 'RemindersController@reset', 'as' => 'password.reset'));

Route::post('password/reset/{token}', array('before' => 'csrf', 'uses' => 'RemindersController@update', 'as' => 'password.update'));



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
Route::post('vdmVehicles/updateCalibration', array('uses' => 'VdmVehicleController@updateCalibration'));
Route::get('vdmVehicles/multi', array('uses' => 'VdmVehicleController@multi'));
Route::get('vdmVehicles/dealerSearch', array('uses' => 'VdmVehicleController@dealerSearch'));

Route::post('vdmVehicles/findDealerList', array('uses' => 'VdmVehicleController@findDealerList'));

Route::get('vdmVehicles/stops/{param}/{param1}', array('uses' => 'VdmVehicleController@stops'));
//ramB/{param}/C/{param1?
Route::get('vdmVehicles/dashboard', array('uses' => 'VdmVehicleController@dashboard'));

Route::get('vdmVehicles/{param}/edit1', array('uses' => 'VdmVehicleController@edit1'));
Route::post('vdmVehicles/update1', array('uses' => 'VdmVehicleController@update1'));


Route::get('vdmVehicles/migration/{param1}', array('uses' => 'VdmVehicleController@migration'));

Route::get('vdmVehicles/removeStop/{param}/{param1}', array('uses' => 'VdmVehicleController@removeStop'));

Route::get('vdmVehicles/stops1/{param}/{param1}', array('uses' => 'VdmVehicleController@stops1'));

Route::get('vdmVehicles/removeStop1/{param}/{param1}', array('uses' => 'VdmVehicleController@removeStop1'));


Route::post('vdmVehicles/generate', array('uses' => 'VdmVehicleController@generate'));

Route::post('vdmVehicles/migrationUpdate', array('uses' => 'VdmVehicleController@migrationUpdate'));


Route::post('vdmVehicles/storeMulti', array('uses' => 'VdmVehicleController@storeMulti'));

Route::resource('vdmGroups', 'VdmGroupController');
Route::get('vdmVehicles/create1/{param1}', array('uses' => 'VdmVehicleController@create1'));
Route::resource('vdmVehicles', 'VdmVehicleController');
Route::resource('DashBoard', 'DashBoardController');


Route::resource('Business', 'BusinessController');

Route::post('Business/adddevice', array('uses' => 'BusinessController@adddevice'));

Route::post('Business/batchSale', array('uses' => 'BusinessController@batchSale'));

Route::resource('vdmUsers', 'VdmUserController');

Route::resource('vdmDealers', 'VdmDealersController');

Route::get('vdmDealers/editDealer/{param}', array('uses' => 'VdmDealersController@editDealer'));

Route::resource('vdmSchools', 'VdmSchoolController');

Route::resource('vdmBusRoutes', 'VdmBusRoutesController');

Route::resource('vdmBusStops', 'VdmBusStopsController');

Route::resource('vdmGeoFence', 'VdmGeoFenceController');
Route::get('vdmOrganization/placeOfInterest', array('uses' => 'VdmOrganizationController@placeOfInterest'));
Route::post('vdmOrganization/addpoi', array('uses' => 'VdmOrganizationController@addpoi'));
Route::get('vdmOrganization/{param}/poiView', array('uses' => 'VdmOrganizationController@poiView'));

Route::get('vdmOrganization/{param}/poiEdit', array('uses' => 'VdmOrganizationController@poiEdit'));

Route::get('vdmOrganization/{param}/poiDelete', array('uses' => 'VdmOrganizationController@poiDelete'));

Route::get('vdmOrganization/{param}/getSmsReport', array('uses' => 'VdmOrganizationController@getSmsReport'));
Route::resource('vdmOrganization', 'VdmOrganizationController');
Route::post('vdmVehicles/calibrate/analog', array('uses' => 'VdmVehicleController@analogCalibrate'));




});   //admin auth ends here
Route::post('AddSiteController/store', array('uses' => 'AddSiteController@store'));
Route::resource('AddSite', 'AddSiteController');
Route::post('AddSiteController/update', array('uses' => 'AddSiteController@update'));

Route::get('vdmSmsReportFilter', array('uses' => 'VdmSmsController@filter'));

Route::post('vdmSmsReport', array('uses' => 'VdmSmsController@show'));



Route::get('vdmGeoFence/{token}', array('uses' => 'VdmGeoFenceController@show'));

Route::get('vdmGeoFence/{token}/view', array('uses' => 'VdmGeoFenceController@view'));

Route::post('vdmVehicles/storeMulti', array('uses' => 'VdmVehicleController@storeMulti'));
Route::resource('vdmFranchises', 'VdmFranchiseController');





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
