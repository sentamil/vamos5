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

Route::get('/getVehicleLocations', function() {
    if (!Auth::check()) {
        return Redirect::to('login');
    }
    Log::info('get Vehicle Locations');
    return View::make('vls.getVehicleLocations');
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

Route::get('/', array('uses' => 'HomeController@showLogin'));
Route::get('about', function() {
    return View::make('pages.about');
});

// route to show the login form
Route::get('login', array('uses' => 'HomeController@showLogin'));

Route::get('password/reset', array('uses' => 'RemindersController@getRemind', 'as' => 'password.remind'));

Route::post('password/reset', array('before' => 'csrf', 'uses' => 'RemindersController@request', 'as' => 'password.request'));

Route::get('password/reset/{token}', array('uses' => 'RemindersController@reset', 'as' => 'password.reset'));

Route::post('password/reset/{token}', array('before' => 'csrf', 'uses' => 'RemindersController@update', 'as' => 'password.update'));
//vdmGeoFence

Route::get('passwordremind', array('uses' => 'RemindersController@getRemind'));

// route to process the form
Route::post('login', array('before' => 'csrf', 'uses' => 'HomeController@doLogin'));

Route::get('logout', array('uses' => 'HomeController@doLogout'));

Route::get('admin', array('uses' => 'HomeController@admin'));

Route::get('liveReport', array('uses' => 'ReportsController@liveReport'));
/*
 Route::get ( 'playBack', array (
 'uses' => 'PlayBackController@replay'
 ) );
 */
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

Route::resource('vdmGroups', 'VdmGroupController');

Route::resource('vdmVehicles', 'VdmVehicleController');

Route::resource('vdmUsers', 'VdmUserController');

Route::resource('vdmGF', 'VdmGFController');

Route::resource('vdmGeoFence', 'VdmGeoFenceController');

Route::get('vdmGeoFence/{token}', array('uses' => 'VdmGeoFenceController@show'));

Route::get('vdmGeoFence/{token}/view', array('uses' => 'VdmGeoFenceController@view'));

Route::resource('vdmFranchises', 'VdmFranchiseController');

Route::get('file/download', function() {
    $file = 'path_to_my_file.pdf';
    return Response::download($file);
});

Route::get('/download', function() {
    // PDF file is stored under project/public/download/info.pdf
    $file = public_path() . "/reports/sample.txt.gz";
    $headers = array('Content-Type: application/zip');
    return Response::download($file, 'sample.txt.gz', $headers);
});
