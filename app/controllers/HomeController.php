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
		$gt06nCount = $redis->hget('H_IP_Address','gt06nCount');
		$tr02Count = $redis->hget('H_IP_Address','tr02Count');
		$gt03aCount = $redis->hget('H_IP_Address','gt03aCount');
		
		
		return View::make ( 'IPAddress', array (
				'ipAddress' => $ipAddress ) )->with ( 'gt06nCount', $gt06nCount )->with('tr02Count',$tr02Count)->with('gt03aCount',$gt03aCount);
		
		
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
				'gt06nCount' => 'numeric',
				'tr02Count' => 'numeric',
				'gt03aCount' => 'numeric'
		
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::to ( 'ipAddressManager' )->withErrors ( $validator );
		} else {
			$redis = Redis::connection ();
			$ipAddress= Input::get ( 'ipAddress' );
			$gt06nCount= Input::get ( 'gt06nCount' );
			$tr02Count= Input::get ( 'tr02Count' );
			$gt03aCount= Input::get ( 'gt03aCount' );
			$redis->hmset('H_IP_Address','ipAddress',$ipAddress,'gt06nCount',$gt06nCount,
				'tr02Count',$tr02Count,'gt03aCount',$gt03aCount);
             
             $init=$redis->lindex('L_GT06N_AVBL_PORTS',0);
             $currentCount   = isset($init)?$init:10000;
             
             $endCount = $currentCount+$gt06nCount;
             
             for($count=$currentCount;$count<=$endCount;$count++) {
                   $redis->rpush('L_GT06N_AVBL_PORTS',$count);
             }   
		}
		Session::flash ( 'message', 'Successfully added ipAddress details'. '!' );
		
		return Redirect::to ( 'admin' );
		
	}
	
	public function reverseGeoLocation()
	{
		
		Log::info("Into reverse Geo Location");
		$lat = Input::get('lat');
		$lng=Input::get('lng');
		
		Log::info("lat" . $lat . 'lng : ' . $lng);
		//https://maps.google.com/maps/api/geocode/json?latlng
		
		$url = "https://maps.google.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&key=AIzaSyBQFgD9_Pm59zGz0ZfLYCUiH_7zbuZ_bFM";
		$data = @file_get_contents($url);
		$jsondata = json_decode($data,true);
		if(is_array($jsondata) && $jsondata['status'] == "OK")
		{
			Log::info("address:" . $jsondata['results']['1']['formatted_address']);
			echo $jsondata['results']['1']['formatted_address'];
		}
		else {
			Log::info("empty");
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
    Log::info('do Login');
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
            Log::info('Login details ' . Input::get('userName') .' '. Input::get('password') );
			// attempt to do the login
			if (Auth::attempt($userdata,$remember)) {

                $username = Auth::user()->username;
                  $redis = Redis::connection();
                  $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
                  $url = Request::url();
                   Log::info('Login details $url ' . $url);
                 /* if($redis->sismember('S_Users_' . $fcode,$username)) {
                       Log::info('Login details ' . $fcode);
                      return  Redirect::to('live');
                  }
            */
				// validation successful!
				
				//return  Redirect::to('vdmVehicles');
				
				 return  Redirect::to('live');
				

			} else {	 	
				return Redirect::to('login')
				->withInput(Input::except('password'))
				->with('flash_notice', 'Your username/password combination was incorrect.');
				// validation not successful, send back to form	
				//return Redirect::to('login');

			}

		}
	}
	
	public function track(){
		
		return View::make('track');
	}

	


	public function doLogout()
	{
		Auth::logout(); // log the user out of our application
		return Redirect::to('login'); // redirect the user to the login screen
	}

}
