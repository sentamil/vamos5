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
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('maps.track');
});
Route::get('/settings', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    return View::make('maps.settings');
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


Route::get('/publicTrack', function() {
        Log::info('get publicTracking Vehicle Locations');
         return View::make('maps.publictrack');
});


Route::get('/publicTracking', function() {
        Log::info('get publicTracking Vehicle Locations');
         return View::make('vls.publicTracking');
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
    if (!Auth::check()) {
        return Redirect::to('login');
    }
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

Route::get('/getVehicleHistory', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle Locations');
    return View::make('vls.getVehicleHistory');
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

Route::get('example', function() {
    return View::make('example');
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
Route::post('login', array('before' => 'csrf', 'uses' => 'HomeController@doLogin'));

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

//Route::group(array('before' => 'adminauth'), function(){   //admin auth starts here
    

Route::get('vdmVehicles/multi', array('uses' => 'VdmVehicleController@multi'));
//ram
Route::get('vdmVehicles/stops/{token}', array('uses' => 'VdmVehicleController@stops'));

Route::get('vdmVehicles/removeStop/{token}', array('uses' => 'VdmVehicleController@removeStop'));

Route::post('vdmVehicles/generate', array('uses' => 'VdmVehicleController@generate'));

Route::post('vdmVehicles/storeMulti', array('uses' => 'VdmVehicleController@storeMulti'));

Route::resource('vdmGroups', 'VdmGroupController');

Route::resource('vdmVehicles', 'VdmVehicleController');

Route::resource('vdmUsers', 'VdmUserController');

Route::resource('vdmSchools', 'VdmSchoolController');

Route::resource('vdmBusRoutes', 'VdmBusRoutesController');

Route::resource('vdmBusStops', 'VdmBusStopsController');

Route::resource('vdmGeoFence', 'VdmGeoFenceController');

Route::resource('vdmOrganization', 'VdmOrganizationController');


//});   //admin auth ends here

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
