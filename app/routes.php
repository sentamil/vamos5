	<?php
	
	/*
	 * |-------------------------------------------------------------------------- | Application Routes |-------------------------------------------------------------------------- | | Here is where you can register all of the routes for an application. | It's a breeze. Simply tell Laravel the URIs it should respond to | and give it the Closure to execute when that URI is requested. |
	 */

	View::addExtension('html', 'php');
        Route::get ( '/live', function () {
                return View::make ( 'maps.eldemo2.index' );
        } );
    Route::get ( '/getVehicleLocations', function () {
                return View::make ( 'vls.getVehicleLocations' );
    } );

	Route::get ( '/admin', function () {
		return View::make ( 'admin' );
	} );
	
	Route::get ( '/', array (
			'uses' => 'HomeController@showLogin' 
	) );
	Route::get ( 'about', function () {
		return View::make ( 'pages.about' );
	} );
	
	// route to show the login form
	Route::get ( 'login', array (
			'uses' => 'HomeController@showLogin' 
	) );
	
	
	Route::get('password/reset', array(
	'uses' => 'RemindersController@getRemind',
	'as' => 'password.remind'
	));
	
	Route::post('password/reset', array(
	'before' => 'csrf',
	'uses' => 'RemindersController@request',
	'as' => 'password.request'
	));
	
	
	Route::get('password/reset/{token}', array(
	'uses' => 'RemindersController@reset',
	'as' => 'password.reset'
	));
	
	Route::post('password/reset/{token}', array(
	'before' => 'csrf',
	'uses' => 'RemindersController@update',
	'as' => 'password.update'
	));
	
	Route::get('passwordremind',array(
		'uses' => 'RemindersController@getRemind'
	));
	

	// route to process the form
	Route::post ( 'login', array (
			'before' => 'csrf',
			'uses' => 'HomeController@doLogin' 
	) );


	
	Route::get ( 'logout', array (
			'uses' => 'HomeController@doLogout' 
	) );
	
	Route::get ( 'admin', array (
	'uses' => 'HomeController@admin'
			) );
	
	Route::get ( 'liveReport', array (
	'uses' => 'ReportsController@liveReport'
			) );

	Route::get ( 'ipAddressManager', array (
	'uses' => 'HomeController@ipAddressManager'
			) );
	
	
	Route::post ( 'ipAddressManager', array (
	'before' => 'csrf',
	'uses' => 'HomeController@saveIpAddress'
			) );
	
	Route::resource('vdmGroups', 'VdmGroupController');
	
	Route::resource('vdmVehicles', 'VdmVehicleController');

	Route::resource('vdmUsers', 'VdmUserController');
	
	Route::resource('vdmPOI', 'VdmPOIController');
	
	Route::resource('vdmFranchises', 'VdmFranchiseController');
	
	
	
	Route::get('file/download', function()
	{
		$file = 'path_to_my_file.pdf';
		return Response::download($file);
	});
	
	Route::get('/download', function (){
		//PDF file is stored under project/public/download/info.pdf
		$file= public_path(). "/reports/sample.txt.gz";
		$headers = array(
				'Content-Type: application/zip',
		);
		return Response::download($file, 'sample.txt.gz', $headers);
	});
