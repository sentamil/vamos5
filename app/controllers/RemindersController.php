<?php

class RemindersController extends Controller {

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getRemind()
	{
		return View::make('password.remind');
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemind()
	{
		log::info(" postRemind ");
		 $data = Input::all();
		//var_dump($data);
		$redis = Redis::connection ();
		$username=Input::get ('userId');
		log::info(" user ");
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );		
		if(($fcode!=null && $username!=null) || $username=='vamos')
		{
			try
	   {
			log::info("valid user ".$username);
			$emailTemp=$redis->hget ( 'H_UserId_Cust_Map', $username . ':email');
						
				Session::put('email',$emailTemp);
				//$ipaddress = $redis->get('ipaddress');
				$ipaddress='188.166.244.126';
				$temp=$username.$fcode.time().$ipaddress;				
				$hashurl=Hash::make($temp);
				$hashurl=str_replace("/","a",$hashurl);
				log::info($emailTemp."valid user ".$username.' token '.$temp." hash url ".$hashurl);
				$url='http://' .$ipaddress . '/gps/public/password/reset/'.$hashurl;
					$response=Mail::send('emails.reset', array('url'=>$url), function($message){
				$message->to(Session::pull ( 'email' ))->subject('PASSWORD RESET!');
			});
			$redis->set($hashurl, $username);
			$redis->expire($hashurl, 3600);
			try{
				$emailT=explode('@',$emailTemp);
				$mailId= substr($emailT[0], 0, -3).'***';
				$emailTemp=$mailId.'@'.$emailT[1];
			}
			catch(\Exception $e)
			   {
				
			   }
				
		return Redirect::to('login')->with('flash_notice','Please check '.$emailTemp.' mail for password details.');	
	   }
	   catch(\Exception $e)
	   {
		return Redirect::to('login')->with('flash_notice','Invalid mail Id.'); 
	   }
			
			
		}
		else
		{
			return Redirect::to('login')->with('flash_notice','invalid user please check the userId.');
		}
		
	}
	
	public function request()
	{
	//	$credentials = array('email' => Input::get('email'), 'password' => Input::get('password'));
	
	//	return Password::remind($credentials);
	
		$redis = Redis::connection ();
		$userId = Input::only('userId');
		$email=null;
		
		$response = Password::remind($email, function($message)
		{
			$message->subject('Password Reminder');
		});
		
		switch ($response)
		{
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));
		
			case Password::REMINDER_SENT:
				return Redirect::to('login')->with('flash_notice','Please check your mail for password details.');
			//	return Redirect::to('/')->with('flash_notice', 'Please check your mail for password details');
		}
		
	}
	
	public function reset($token)
	{
		$redis = Redis::connection ();
		$username=$redis->get($token);
		if($username!=null)
		{
			return View::make('password.reset')->with('token', $token)->with('userId', $username);
		}
		else{
			return View::make('password.expire');
		}
	
		
	}
	
	
	public function update()
	{
		//$credentials = array('email' => Input::get('email'));
		log::info(" update" );
		$redis = Redis::connection ();
		$userId=Input::get('userId1');
		$token=Input::get('token');
		$password=Input::get('password');
		$passwordCon=Input::get('password_confirmation');
		log::info(" user name ". $userId.' token '.$token.'password 1 '.$password.' password conf '.$passwordCon);
		if($password==null || $passwordCon==null)
		{
			return Redirect::back()->withErrors('Enter Both password');
		}
		else if($password!=$passwordCon)
		{
			return Redirect::back()->withErrors('Password not match');
		}
		$id = DB::table('users')->where('username', $userId)->pluck('id');
		DB::table('users')
            ->where('id', $id)
            ->update(array('password' => Hash::make($password)));
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':password',$password);
			$redis->del($token);
	return Redirect::to('login')->with('flash_notice', 'Your password has been reset');	
			
			
		
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);

		return View::make('password.reset')->with('token', $token);
	}

	

	/*
		check old password
	*/

	public function menuResetPassword(){

		log::info(' menu reset reminder');
		$username 	= Auth::user()->username;
		$tableValue = DB::select('select username, password from users where username = :username',['username' => $username]);
		$oldPwd 	= Input::get('pwd');
		
		foreach($tableValue as $key=>$value)
		{
			log::info(array_values(get_object_vars($value))[0]);
			log::info(array_values(get_object_vars($value))[1]);
			if(Hash::check($oldPwd, array_values(get_object_vars($value))[1]) == 1){

				log::info(' Sucess  ');
				return 'sucess';
			} else {

				log::info(' fail  ');
				return 'fail';
			}
			
		}

		// log::info(array_values(DB::table('users')->where('username', 'MSS')));

		
		// foreach($Licence as $mob){	
			// log::info('for');
			// if(array_values(get_object_vars($mob))[0] == 'MSS'){
			// log::info(' Licence ');
			// log::info(Hash::make(array_values(get_object_vars($mob))[1]));
			// log::info(array_values(get_object_vars($mob))[1]);
			// $pwd = array_values(get_object_vars($mob))[1];
			// log::info(Hash::check($oldPwd, array_values(get_object_vars($mob))[1]));
			// log::info('end if');

 			// }     	
		// }
	}


	/*
		update password
	*/

	public function menuUpdatePassword(){

		log::info(' menu update password');
		// try {
		$redis 			= Redis::connection ();
		$username 		= Auth::user()->username;
		$password 		= Input::get('pwd');
		$oldpassword 	= Input::get('old');
		// log::info($password);
		// log::info($oldpassword);
		try {
			
			$tableValue = DB::select('select username, password, email, id from users where username = :username',['username' => $username]);
			foreach($tableValue as $key=>$value)
			{
				// log::info(array_values(get_object_vars($value))[0]);
				// log::info(array_values(get_object_vars($value))[1]);
				// log::info(array_values(get_object_vars($value))[2]);
				// log::info(array_values(get_object_vars($value))[3]);
				if(Hash::check($oldpassword, array_values(get_object_vars($value))[1]) == 1){
					log::info('  old password correct  ');
					DB::table('users') ->where('id', array_values(get_object_vars($value))[3]) ->update(array('password' => Hash::make($password)));
					$redis->hmset ( 'H_UserId_Cust_Map', $username . ':password',$password);

					try {
						
						Session::put('email', array_values(get_object_vars($value))[2]);
						$response=Mail::send('emails.menuReset', array('url'=>$username), function($message){
							$message->to(Session::pull ( 'email' ))->subject('PASSWORD RESET!');
						});	

					} catch (Exception $e) {
							
						log::info(' mail error ');
						log::info($e);
					}
					// return 'sucess'; Session::pull ( 'email' )
				} else {
					log::info('  old password wrong  ');
					
					return 'oldPwd';
				}
				
			}
			return 'sucess';
		} catch (Exception $e) {
			log::info($e);
			return '';
		}

		// 	$redis = Redis::connection ();
		// 	$mailId = DB::select('select email from users where username = :username',['username' => $username]);
		// 	$id = DB::table('users')->where('username', $username)->pluck('id');
		// 	DB::table('users') ->where('id', $id) ->update(array('password' => Hash::make($password)));
		// 	// $mailId ='';
		// 	foreach($mailId as $key=>$value)
		// 	{
		// 		$mailId 	= array_values(get_object_vars($value))[0];
		// 	}
		// 	log::info(gettype($mailId));
		// 	log::info($mailId);
		// 	try {
		// 		// log::info($mailId);
		// 		Session::put('email',$mailId);
		// 		$response=Mail::send('emails.menuReset', array('url'=>$username), function($message){
		// 			$message->to(Session::pull ( 'email' ))->subject('PASSWORD RESET!');
		// 		});	

		// 	} catch (Exception $e) {
					
		// 		log::info(' error ');
		// 		log::info($e);
		// 	}
			
		// 	$redis->hmset ( 'H_UserId_Cust_Map', $username . ':password',$password);

		// 	return 'sucess';
		// } catch (Exception $exce) {
		// 	log::info($exce);
		// 	return 'fail';
		// }	




	}

}
