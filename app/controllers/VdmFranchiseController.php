<?php
class VdmFranchiseController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		log::info( 'User name  :' . $username);
		
		$redis = Redis::connection ();
		
		$fcodeArray = $redis->smembers('S_Customers');
		
		$fnameArray=null;
		
		foreach ( $fcodeArray as $key => $value ) {
			$fname = $redis->hget('H_Franchise',$value.':fname');
			
			$fnameArray=array_add($fnameArray, $value, $fname);
		}
		//dd($fnameArray);
		
		
		return View::make ( 'vdm.franchise.index', array (
				'fcodeArray' => $fcodeArray 
		) )->with ( 'fnameArray', $fnameArray );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		return View::make ( 'vdm.franchise.create' );
	}
	
	/**

		Frabchise is created by VAMOS Admin
		Franchise name
		Franchise ID (company ID)
		Franchise description
		Franchise full address
		Franchise landline no
		Franchise mobile number 1
		Franchise mobile number 2
		Franchise email id1
		Franchise email id2
		Franchise other details
		Franchise login details
		
	 * 
	 * @return Response
	 */
	public function store() {
		Log::info("reached franchise store");
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		$rules = array (
				'fname' => 'required',
				'fcode' => 'required',
				'description' => 'required',
				'fullAddress' => 'required',
				'landline' => 'required',
				'mobileNo1' => 'required|numeric',
				'mobileNo2' => 'numeric',
				'email1' => 'required|email',
				'email2' => 'email',
				'userId' => 'required', 
				'otherDetails' => 'required' 
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmFranchises/create' )->withErrors ( $validator );
		} else {
			// store
			
			$fname = Input::get ( 'fname' );
			$fcode = Input::get ( 'fcode' );
			$description = Input::get ( 'description' );
			$fullAddress = Input::get ( 'fullAddress' );
			$landline = Input::get ( 'landline' );
			$mobileNo1 = Input::get ( 'mobileNo1' );
			$mobileNo2 = Input::get ( 'mobileNo2' );
			$email1 = Input::get ( 'email1' );
			$email2 = Input::get ( 'email2' );
			$userId = Input::get ('userId');
			$otherDetails = Input::get ('otherDetails');
			
			$redis = Redis::connection ();
			
			// $refDataArr = array('regNo'=>$regNo,'vehicleMake'=>$vehicleMake,'vehicleType'=>$vehicleType,'oprName'=>$oprName,
			// 'mobileNo'=>$mobileNo,'vehicleCap'=>$vehicleCap,'deviceModel'=>$deviceModel);
			

			$val = $redis->sadd('S_Customers',$fcode);
			
			log::info(" redis return code :"+$val);
			//TODO
			/**
			 * If code is not unique this method fail and return - suggesting 
			 * that the ID should be unique. 
			 * 
			 * Possible improvement..implement ajax call - verify the code while typing itself
			 * 
			 */
			
	
			$redis->hmset ( 'H_Franchise', $fcode.':fname',$fname,$fcode.':description:',$description,
					$fcode.':landline',$landline,$fcode.':mobileNo1',$mobileNo1,$fcode.':mobileNo2',$mobileNo2,
					$fcode.':email1',$email1,$fcode.':email2',$email2,$fcode.':userId',$userId);
			
			$cpyCode=$fcode;
			
			$redis->sadd ( 'S_Users_' . $cpyCode, $userId );
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':cpyCode', $cpyCode, $userId . ':mobileNo', $mobileNo1,$userId.':email',$email1 );
			
			Log::info("going to email");
			
			$password='awesome';
			
			$user = new User;
			
			$user->name = $fname;
			$user->username=$userId;
			$user->email=$email1;
			$user->password=Hash::make($password);
			$user->save();

			/*
			
			Mail::queue('emails.welcome', array('fname'=>$fname,'userId'=>$userId,'password'=>$password), function($message)
			{
				$message->to($email)->subject('Welcome to VAMO Systems');
			});
			*/
			
			// redirect
			Session::flash ( 'message', 'Successfully created ' . $fname . '!' );
			return Redirect::to ( 'vdmFranchises' );
		}
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function show($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
	
		$fcode=$id;
		$redis = Redis::connection ();
		
	
		$franDetails = $redis->hmget ( 'H_Franchise', $fcode.':fname',$fcode.':descrption:',
				$fcode.':landline',$fcode.':mobileNo1',$fcode.':mobileNo2',
				$fcode.':email1',$fcode.':email2',$fcode.':userId');
		
		
		$franchiseDetails = implode ( '<br/>', $franDetails );
		
		return View::make ( 'vdm.franchise.show', array (
				'fname' => $franDetails[0] 
		) )->with ( 'fcode', $fcode )->with ( 'franchiseDetails', $franchiseDetails );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function edit($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		$redis = Redis::connection ();
		$fcode = $id;
		
		$franchiseDetails = $redis->hmget ( 'H_Franchise', $fcode.':fname',$fcode.':descrption:',
				$fcode.':landline',$fcode.':mobileNo1',$fcode.':mobileNo2',
				$fcode.':email1',$fcode.':email2',$fcode.':userId');
			
		
		
		return View::make ( 'vdm.franchise.edit', array (
				'fname' => $franchiseDetails[0] 
		) )->with ( 'fcode', $fcode )->with ( 'franchiseDetails', $franchiseDetails )->with('userId',$franchiseDetails[7]);
	

	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function update($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$fcode = $id;
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		var_dump( Input::all ());
		var_dump($id);
		$rules = array (

				'description' => 'required',
				'fullAddress' => 'required',
				'landline' => 'required|numeric',
				'mobileNo1' => 'required|numeric',
				'mobileNo2' => 'numeric',
				'email1' => 'required|email',
				'email2' => 'email',
				'otherDetails' => 'required'
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			Log::info(" failed ");
		
			return Redirect::to ( 'vdmFranchises/update' )->withErrors ( $validator );
		} else {
			// store
				

			$description = Input::get ( 'description' );
			$fullAddress = Input::get ( 'fullAddress' );
			$landline = Input::get ( 'landline' );
			$mobileNo1 = Input::get ( 'mobileNo1' );
			$mobileNo2 = Input::get ( 'mobileNo2' );
			$email1 = Input::get ( 'email1' );
			$email2 = Input::get ( 'email2' );

			$otherDetails = Input::get ('otherDetails');
				
			$redis = Redis::connection ();
				
			// $refDataArr = array('regNo'=>$regNo,'vehicleMake'=>$vehicleMake,'vehicleType'=>$vehicleType,'oprName'=>$oprName,
			// 'mobileNo'=>$mobileNo,'vehicleCap'=>$vehicleCap,'deviceModel'=>$deviceModel);
				
		
			$val = $redis->sadd('S_Customers',$fcode);
				
			log::info(" redis return code :"+$val);
			//TODO
			/**
			* If code is not unique this method fail and return - suggesting
			* that the ID should be unique.
			*
			* Possible improvement..implement ajax call - verify the code while typing itself
			*
			*/
			
			$userId = $redis->hget('H_Franchise',$fcode.':userId');
			$fname =$redis->hget('H_Franchise',$fcode.':fname');
			
			$redis->hmset ( 'H_Franchise', $fcode.':description:',$description,
					$fcode.':landline',$landline,$fcode.':mobileNo1',$mobileNo1,$fcode.':mobileNo2',$mobileNo2,
					$fcode.':email1',$email1,$fcode.':email2',$email2);
			
			$cpyCode = $fcode;
			
			$redis->sadd ( 'S_Users_' . $cpyCode, $userId );
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':cpyCode', $cpyCode, $userId . ':mobileNo',
					 $mobileNo1,$userId.':email',$email1 );
		
/*
			$user = User::where('userId', '=', $userId);
			$user->email=$email1;
			$user->save();
	*/		
			
			DB::table('users')
			->where('username', $userId)
			->update(array('email' => $email1));
			
			
			Log::info(" about to send mail");
			/*	
			Mail::send('emails.welcome', array('fname'=>$fname,'userId'=>$userId,'password'=>$password), function($message)
			{
				$message->to($email1)->subject('Welcome to VAMO Systems');
			});
				
			*/
		}
					
		// redirect
		Session::flash ( 'message', 'Successfully updated ' . $fname . '!' );
		return Redirect::to ( 'vdmFranchises' );
		
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function destroy($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		
		$fcode = $id;

		$userId = $redis->hget('H_Franchise',$fcode.':userId');
		$redis->hdel ( 'H_Franchise', $fcode.':fname',$fcode.':descrption:',
				$fcode.':landline',$fcode.':mobileNo1',$fcode.':mobileNo2',
				$fcode.':email1',$fcode.':email2',$fcode.':userId');
		
		$redis->srem('S_Customers',$fcode);
		$cpyCode=$fcode;
		$redis->srem ( 'S_Users_' . $cpyCode, $userId );
		$redis->hdel ( 'H_UserId_Cust_Map', $userId . ':cpyCode', $userId . ':mobileNo', $userId.':email');
		
		
		Session::flash ( 'message', 'Successfully deleted ' . 'fname:'.$fcode . '!' );
		return Redirect::to ( 'vdmFranchises' );
	}
}
