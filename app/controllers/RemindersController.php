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
		/*	try
	   {*/
			log::info("valid user ".$username);
			$emailTemp=$redis->hget ( 'H_UserId_Cust_Map', $username . ':email');
						
				Session::put('email',$emailTemp);
				$ipaddress = $redis->get('ipaddress');
				$temp=$username.$fcode.time().$ipaddress;				
				$hashurl=Hash::make($temp);
				$hashurl=str_replace("/","a",$hashurl);
				log::info($emailTemp."valid user ".$username.' token '.$temp." hash url ".$hashurl);
				$url='http://' .$ipaddress . '/vamo/public/password/reset/'.$hashurl;
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
	  /* }
	   catch(\Exception $e)
	   {
		return Redirect::to('login')->with('flash_notice','Invalid mail Id.'); 
	   }*/
			
			
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
		$id = DB::table('users')->where('name', $userId)->pluck('id');
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

	

}
