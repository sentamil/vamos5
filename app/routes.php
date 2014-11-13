	<?php
	
	/*
	 * |-------------------------------------------------------------------------- | Application Routes |-------------------------------------------------------------------------- | | Here is where you can register all of the routes for an application. | It's a breeze. Simply tell Laravel the URIs it should respond to | and give it the Closure to execute when that URI is requested. |
	 */
	Route::get ( '/hello', function () {
		return View::make ( 'test.hello' );
	} );
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
	
	Route::get ( '/maps1', function () {
			return View::make ( 'maps.el.index' );
		} );
	
	Route::get ( '/page2', function () {
		return View::make ( 'page2' );
	} );
	
	Route::get ( '/admin_blade', function () {
		return View::make ( 'admin_blade' );
	} );
	
	Route::get ( '/', function () {
		return View::make ( 'pages.home' );
	} );
	Route::get ( 'about', function () {
		return View::make ( 'pages.about' );
	} );
	Route::get ( 'projects', function () {
		return View::make ( 'pages.projects' );
	} );
	Route::get ( 'contact', function () {
		return View::make ( 'pages.contact' );
	} );
	
	
	
	Route::get ( 'form', function () {
		// render app/views/form.blade.php
		return View::make ( 'form' );
	} );
	Route::post ( 'form-submit', array (
			'before' => 'csrf',
			function () {
				// form validation come here
				echo "its working";
				$var = Input::all ();
				var_dump ( $var );
				
				// echo $var['username'];
				
				$value = Cookie::get ( 'name' );
				
				// echo $value;
			} 
	) );
	
	// route to show the login form
	Route::get ( 'login', array (
			'uses' => 'HomeController@showLogin' 
	) );
	
	Route::get ( 'login2', array (
	'uses' => 'HomeController@showLogin2'
			) );
	
	Route::get ( 'livelogin', array (
	'uses' => 'HomeController@livelogin'
			) );
	
	
	// route to process the form
	Route::post ( 'login', array (
			'before' => 'csrf',
			'uses' => 'HomeController@doLogin' 
	) );

        Route::post ( 'login3', array (
                        'before' => 'csrf',
                        'uses' => 'HomeController@doLogin3'
        ) );
	
	Route::get ( 'logout', array (
			'uses' => 'HomeController@doLogout' 
	) );
	

	
	Route::resource('vdmGroups', 'VdmGroupController');
	
	Route::resource('vdmVehicles', 'VdmVehicleController');

	Route::resource('vdmUsers', 'VdmUserController');
	
	Route::resource('vdmPOI', 'VdmPOIController');
	
