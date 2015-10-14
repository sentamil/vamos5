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
		
		$fcodeArray = $redis->smembers('S_Franchises');
		
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
		$redis = Redis::connection ();
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
		$userId = Input::get ('userId');
		$fcode = Input::get ( 'fcode' );
		$val = $redis->sismember('S_Franchises',$fcode);
		$val1= $redis->sismember ( 'S_Users_' . $fcode, $userId );

			
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmFranchises/create' )->withErrors ( $validator );
		}else if($val==1 ) {
			Session::flash ( 'message', $fcode . 'Franchise already exist ' . '!' );
			return Redirect::to ( 'vdmFranchises/create' );
		}
		else if($val1==1) {
			Session::flash ( 'message', $userId . ' already exist. Please use different id ' . '!' );
			return Redirect::to ( 'vdmFranchises/create' );
		}
		else {
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
			
	
			
			// $refDataArr = array('regNo'=>$regNo,'vehicleMake'=>$vehicleMake,'vehicleType'=>$vehicleType,'oprName'=>$oprName,
			// 'mobileNo'=>$mobileNo,'vehicleCap'=>$vehicleCap,'deviceModel'=>$deviceModel);
			

			$redis->sadd('S_Franchises',$fcode);
			
			
	
			$redis->hmset ( 'H_Franchise', $fcode.':fname',$fname,$fcode.':description',$description,
					$fcode.':landline',$landline,$fcode.':mobileNo1',$mobileNo1,$fcode.':mobileNo2',$mobileNo2,
					$fcode.':email1',$email1,$fcode.':email2',$email2,$fcode.':userId',$userId);
			

			
			$redis->sadd ( 'S_Users_' . $fcode, $userId );
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo', $mobileNo1,$userId.':email',$email1 );
			

			
			$password='awesome';
			
			$user = new User;
			
			$user->name = $fname;
			$user->username=$userId;
			$user->email=$email1;
			$user->mobileNo=$mobileNo1;
			$user->password=Hash::make($password);
			$user->save();

			Log::info("going to email..");
			
			/** 
			 * Add vamos admin user for each franchise
			 * 
			 */
			$user = new User;
			$vamosid='vamos'.$fcode;	
			$user->name = 'vamos'.$fname;
			$user->username=$vamosid;
			$user->email='support@vamosys.com';
			$user->mobileNo='9840898818';
			$user->password=Hash::make($password);
			$user->save();
			$redis->sadd ( 'S_Users_' . $fcode, $vamosid );
			$redis->hmset ( 'H_UserId_Cust_Map', $vamosid . ':fcode', $fcode);
		
        /*				
			Mail::queue('emails.welcome', array('fname'=>$fname,'userId'=>$userId,'password'=>$password), function($message)
			{
				Log::info("Inside email :" . Input::get ( 'email1' ));
				
				$message->to(Input::get ( 'email1' ))->subject('Welcome to VAMO Systems');
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
		
		$franchiseDetails = $redis->hmget ( 'H_Franchise', $fcode.':fname',$fcode.':description',
				$fcode.':landline',$fcode.':mobileNo1',$fcode.':mobileNo2',
				$fcode.':email1',$fcode.':email2',$fcode.':userId',$fcode.':fullAddress',$fcode.':otherDetails');
			
		
		
		return View::make ( 'vdm.franchise.edit', array (
				'fname' => $franchiseDetails[0] 
		) )->with ( 'fcode', $fcode )->with ( 'franchiseDetails', $franchiseDetails )
		->with('description',$franchiseDetails[1])
		->with('landline',$franchiseDetails[2])
		->with('mobileNo1',$franchiseDetails[3])
		->with('mobileNo2',$franchiseDetails[4])
		->with('email1',$franchiseDetails[5])
		->with('email2',$franchiseDetails[6])
		->with('userId',$franchiseDetails[7])
		->with('fullAddress',$franchiseDetails[8])
		->with('otherDetails',$franchiseDetails[9]);
	

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

		$rules = array (

				'description' => 'required',
				'fullAddress' => 'required',
				'landline' => 'required',
				'mobileNo1' => 'required|numeric',
				'mobileNo2' => 'numeric',
				'email1' => 'required|email',
				'email2' => 'email',
				'otherDetails' => 'required'
		);
		$validator = Validator::make ( Input::all (), $rules );

		
		if ($validator->fails ()) {
			Log::info(" failed ");
			Session::flash ( 'message', 'Update failed. Please check logs for more details' . '!' );
				
		//	return Redirect::to ( 'vdmFranchises' )->withErrors ( $validator );

			return Redirect::to ( 'vdmFranchises/update' )->withErrors ( $validator );
		} 
		else {
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
				
		
			$val = $redis->sadd('S_Franchises',$fcode);
				
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
			
			$redis->hmset ( 'H_Franchise', $fcode.':description',$description,
					$fcode.':landline',$landline,$fcode.':mobileNo1',$mobileNo1,$fcode.':mobileNo2',$mobileNo2,
					$fcode.':email1',$email1,$fcode.':email2',$email2);
			
			
			$redis->sadd ( 'S_Users_' . $fcode, $userId );
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo',
					 $mobileNo1,$userId.':email',$email1 );

			
			DB::table('users')
			->where('username', $userId)
			->update(array('email' => $email1));
			
	

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
		$fname = $redis->hget('H_Franchise',$fcode.':fname');
		
		$email1 = $redis->hget('H_Franchise', $fcode.':email1');
		
		$redis->hdel ( 'H_Franchise', $fcode.':fname',$fcode.':description',
				$fcode.':landline',$fcode.':mobileNo1',$fcode.':mobileNo2',
				$fcode.':email1',$fcode.':email2',$fcode.':userId');
		
		$redis->srem('S_Franchises',$fcode);

		$redis->srem ( 'S_Users_' . $fcode, $userId );
		$redis->hdel ( 'H_UserId_Cust_Map', $userId . ':fcode', $userId . ':mobileNo', $userId.':email');
		
		Log::info(" about to delete user" .$userId);
		
		DB::table('users')->where('username', $userId)->delete();
		
		$vamosid = 'vamos'.$fcode;
		
		
		$redis->srem ( 'S_Users_' . $fcode, $vamosid );
		$redis->hdel ( 'H_UserId_Cust_Map', $vamosid . ':fcode');
		
		
		Session::put('email1',$email1);
		Log::info("Email Id :" . Session::get ( 'email1' ));
		
		/*Mail::queue('emails.welcome', array('fname'=>$fname,'userId'=>$userId), function($message)
		{
			Log::info("Inside email :" . Session::get ( 'email1' ));
		
			$message->to(Session::pull ( 'email1' ))->subject('User Id deleted');
		});
		*/
	
		
		Session::flash ( 'message', 'Successfully deleted ' . 'fname:'.$fcode . '!' );
		return Redirect::to ( 'vdmFranchises' );
	}
}
