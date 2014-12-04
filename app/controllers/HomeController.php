<?php

class HomeController extends BaseController {

	

	

	public function showLogin()
	{
		// show the form
		return View::make('login');
	}
	
	public function admin()
	{
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		$username = Auth::user ()->username;
		if($username !='vamos') {
			return Redirect::to('login')->with('flash_notice', 'Unauthorized user. Futher attempts will be
					treated as hacking, will be prosecuted under Cyber laws.');
		
		}
		else {
			return View::make('admin');
		}
	}
	
	
	public function ipAddressManager()
	{
		
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		$username = Auth::user ()->username;
		if($username !='vamos') {
			return Redirect::to('login')->with('flash_notice', 'Unauthorized user. Futher attempts will be
					treated as hacking, will be prosecuted under Cyber laws.');
		
		}

		$redis = Redis::connection ();
		$ipAddress = $redis->hget('H_IP_Address','ipAddress');
		$deviceHandler = $redis->hget('H_IP_Address','deviceHandler:GT06N');
		$port = $redis->hget('H_IP_Address','portNo:GT06N');
		$range = $redis->hget('H_IP_Address','range:GT06N');
		
		
		return View::make ( 'IPAddress', array (
				'ipAddress' => $ipAddress ) )->with ( 'deviceHandler_gt06n', $deviceHandler )->with('portNo_gt06n',$port)->with('range_gt06n',$range);
		
		
	}
	
	public function saveIpAddress() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		$username = Auth::user ()->username;
		if($username !='vamos') {
			return Redirect::to('login')->with('flash_notice', 'Unauthorized user. Futher attempts will be
					treated as hacking, will be prosecuted under Cyber laws.');
		
		}
		
		$rules = array (
				'ipAddress' => 'required',
				'deviceHandler_gt06n' => 'required',
				'portNo_gt06n' => 'required',
				'range_gt06n' => 'required'
		
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::to ( 'ipAddressManager' )->withErrors ( $validator );
		} else {
			$redis = Redis::connection ();
			$ipAddress= Input::get ( 'ipAddress' );
			$deviceHandler= Input::get ( 'deviceHandler_gt06n' );
			$port= Input::get ( 'portNo_gt06n' );
			$range= Input::get ( 'range_gt06n' );
			$redis->hmset('H_IP_Address','ipAddress',$ipAddress,'deviceHandler:GT06N',$deviceHandler,
				'portNo:GT06N',$port,'range:GT06N',$range);
		}
		Session::flash ( 'message', 'Successfully added ipAddress details'. '!' );
		
		return Redirect::to ( 'admin' );
		
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
