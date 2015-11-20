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
		
		$response = Password::remind(Input::only('email'), function($message)
		{
			$message->subject('Password Reminder');
		});
		
		switch ($response)
		{
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));

			case Password::REMINDER_SENT:
				return Redirect::to('login')->with('flash_notice','Please check your mail for password details.');

				//return Redirect::back()->with('status', Lang::get($response));
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
		
	
		return View::make('password.reset')->with('token', $token);
	}
	
	
	public function update()
	{
		//$credentials = array('email' => Input::get('email'));
		log::info(" update");
		$credentials = Input::only(
				'userId', 'password', 'password_confirmation', 'token'
		);
		
		$response = Password::reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);
	
			$user->save();
			log::info(" saved");
			return Redirect::to('login')->with('message', 'Your password has been reset');
		});
		switch ($response)
		{
			case Password::INVALID_PASSWORD:
				
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				log::info(" invalid");
				return Redirect::back()->withErrors('Invalid User or Password');
		
			case Password::PASSWORD_RESET:
				return Redirect::to('login')->with('flash', 'Your password has been reset');
		}
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
