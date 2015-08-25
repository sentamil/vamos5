<?php


/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

Route::filter('adminauth',function()
{

    if (! Auth::check ()) {
            return Redirect::to ( 'login' );
      }
      $username = Auth::user ()->username;
      Session::put('user',$username);
       $uri = Route::current()->getName();
      Log::info('URL  '. $uri);
      if(strpos($username,'admin')!==false ) {
             //do nothing
 log::info( '---------- inside if filter adminauth----------' . $username) ;
			//Auth::session(['cur' => 'admin']);
			Session::put('cur','admin');
      }
	 
      else {
           
		  $redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		log::info( '-----------------inside else filter adminauth-------------' . $username .$fcode) ;
		$val1= $redis->sismember ( 'S_Dealers_' . $fcode, $username );
		$val = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		  if($val1==1 && isset($val)) {
			Log::info('---------------is dealer adminauth:--------------');
			$breadcrumb=null;
			
			Session::put('cur','dealer');
			
		}
		else{
			return Redirect::to ( 'live' ); //TODO should be replaced with aunthorized page - error
		}
           
      }
});


Route::filter('dealerauth',function()
{

    if (! Auth::check ()) {
            return Redirect::to ( 'login' );
      }
      $username = Auth::user ()->username;
      
       $uri = Route::current()->getName();
      Log::info('URL  '. $uri);
      if(strpos($username,'admin')!==false || $uri=='vdmVehicles.edit') {
             //do nothing
 log::info( '---------- inside if filter----------' . $username) ;
 return 'admin';
      }
	 
      else {
           
		  $redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		log::info( '-----------------inside else filter-------------' . $username .$fcode) ;
		$val1= $redis->sismember ( 'S_Dealers_' . $fcode, $username );
		$val = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		  if($val1==1 || isset($val)) {
			Log::info('---------------is dealer:--------------');
			return 'dealer';
			
		}
		else{
			return null; //TODO should be replaced with aunthorized page - error
		}
           
      }
});



/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
