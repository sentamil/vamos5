<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller free
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	

	public function showLogin()
	{
		// show the form
		return View::make('login');
	}
	
	public function admin()
	{
		
		$username = Auth::user ()->username;
		if($username !='vamos') {
			return Redirect::to('login')->with('flash_notice', 'Unauthorized user. Futher attempts will be
					treated as hacking, will be prosecuted under Cyber laws.');
		;
		}
		else {
			return View::make('admin');
		}
	}
	
	public function livelogin()
	{
		// show the form
		return View::make('livelogin');
	}

	public function doLogin()
	{
		// validate the info, create rules for the inputs
		$rules = array(
			'userName'    => 'required', // make sure the email is an actual email
			'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
		);

		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);
		$remember = (Input::has('remember')) ? true : false;
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			return Redirect::to('login')
				->withErrors($validator) // send back all errors to the login form
				->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
		} else {

			// create our user data for the authentication
			$userdata = array(
				'userName' 	=> Input::get('userName'),
				'password' 	=> Input::get('password')
			);

			// attempt to do the login
			if (Auth::attempt($userdata,$remember)) {

				// validation successful!
				
				return  Redirect::to('vdmVehicles');
			//	return  Redirect::to('http://128.199.175.189:8080/maps/eldemo2/');
				

			} else {	 	
				return Redirect::to('login')
				->withInput(Input::except('password'))
				->with('flash_notice', 'Your username/password combination was incorrect.');
				// validation not successful, send back to form	
				//return Redirect::to('login');

			}

		}
	}

	


	public function doLogout()
	{
		Auth::logout(); // log the user out of our application
		return Redirect::to('login'); // redirect the user to the login screen
	}

}
