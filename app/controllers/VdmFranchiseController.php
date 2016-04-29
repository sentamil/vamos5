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
			$details = $redis->hget('H_Franchise',$value);
			$details_json=json_decode($details,true);
			$fnameArray=array_add($fnameArray, $value, $details_json['fname']);
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
		$smsP=VdmFranchiseController::smsP();
		return View::make ( 'vdm.franchise.create' )->with('smsP',$smsP);
	}
	
public static function smsP()
{
		$smsP=array();
		$smsP=array_add($smsP, 'nill','Nill');
		$smsP=array_add($smsP, 'KapSMS','Kap');
		$smsP=array_add($smsP, 'Mvaayoo','Mvaayoo');
		$smsP=array_add($smsP, 'UniversalSMS','Universal');
		$smsP=array_add($smsP, 'Voicegateway','Voicegateway');
	return $smsP;
}

	public function fransearch()
	{
		  Log::info('------- inside fransearch--------');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        Log::info(' inside multi ' );
       
        $fransId = $redis->smembers('S_Franchises');
       
     
       
        $orgArr = array();
        foreach($fransId as $org) {
            $orgArr = array_add($orgArr, $org,$org);
        }
        $fransId = $orgArr;
     
                 
 
                 
        return View::make ( 'vdm.franchise.frans' )->with('fransId',$fransId);       
       
	}




public function users()
	{
		  Log::info('------- inside fransearch--------');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        Log::info(' inside multi ' );
       
        $userId = $redis->smembers('S_Franchises');
       
     
       
        $orgArr = array();
        foreach($userId as $org) {
        	$temp=$redis->smembers( 'S_Users_' . $org);
        	foreach ($temp as $key) {
        		 $orgArr = array_add($orgArr, $key,$key);
        	}

           
        }
        $userId = $orgArr;
     
                 
 
                 
        return View::make ( 'vdm.franchise.users' )->with('userId',$userId);       
       
	}

		public function findFransList() {
                                log::info( '-----------List----------- ::');
                                if (! Auth::check () ) {
                                                return Redirect::to ( 'login' );
                                }
                                 $redis = Redis::connection ();
                               $username = Input::get ( 'frans' );
							$franDetails_json = $redis->hget ( 'H_Franchise', $username);
							$franchiseDetails=json_decode($franDetails_json,true);
						
								if(isset($franchiseDetails['userId'])==1)
									$username=$franchiseDetails['userId'];
								else
									$username=null;

					          if($username==null)
                                {
                                                log::info( '--------use one----------' );
                                                $username = Session::get('page');
                                }
                                else{
                                                log::info( '--------use two----------'.$username);
                                                Session::put('page',$username);
                                }
                                               
                                                try{
                                                                 $user=User::where('username', '=', $username)->firstOrFail();
												log::info( '--------new name----------' .$user);
					                                Auth::login($user);
                                                }catch(\Exception $e)
								                   {
								                                return Redirect::to ( 'vdmFranchises/fransearch' ); 
								                   }
                                 //$user = User::find(10);
                               
                               
                               
                               
                                return Redirect::to ( 'Business' );
                }





                public function findUsersList() {
                                log::info( '-----------List----------- ::');
                                if (! Auth::check () ) {
                                                return Redirect::to ( 'login' );
                                }
                                 $redis = Redis::connection ();
                               $username = Input::get ( 'users' );

					          if($username==null)
                                {
                                                log::info( '--------use one----------' );
                                                $username = Session::get('page');
                                }
                                else{
                                                log::info( '--------use two----------'.$username);
                                                Session::put('page',$username);
                                }
                                               
                                                try{
                                                                 $user=User::where('username', '=', $username)->firstOrFail();
												log::info( '--------new name----------' .$user);
					                                Auth::login($user);
                                                }catch(\Exception $e)
								                   {
								                                return Redirect::to ( 'vdmFranchises/users' ); 
								                   }
                                 //$user = User::find(10);
                               
                               
                               
                               
                                return Redirect::to ( 'Business' );
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
				'website' => 'required', 
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
			$website = Input::get ( 'website' );
			$email1 = Input::get ( 'email1' );
			$email2 = Input::get ( 'email2' );
			$userId = Input::get ('userId');
			$otherDetails = Input::get ('otherDetails');
			$numberofLicence = Input::get ('numberofLicence');
			$smsSender=Input::get ('smsSender');
			$smsProvider=Input::get ('smsProvider');
			$providerUserName=Input::get ('providerUserName');
			$providerPassword=Input::get ('providerPassword');
	
			
			// $refDataArr = array('regNo'=>$regNo,'vehicleMake'=>$vehicleMake,'vehicleType'=>$vehicleType,'oprName'=>$oprName,
			// 'mobileNo'=>$mobileNo,'vehicleCap'=>$vehicleCap,'deviceModel'=>$deviceModel);
			

			$redis->sadd('S_Franchises',$fcode);
			
			
	
			/*$redis->hmset ( 'H_Franchise', $fcode.':fname',$fname,$fcode.':description',$description,
					$fcode.':landline',$landline,$fcode.':mobileNo1',$mobileNo1,$fcode.':mobileNo2',$mobileNo2,
					$fcode.':email1',$email1,$fcode.':email2',$email2,$fcode.':userId',$userId);*///ram what to do migration
			
			$details = array (
					'fname' => $fname,
					'description' => $description,
					'landline' => $landline,
					'mobileNo1' => $mobileNo1,					
					'mobileNo2' => $mobileNo2,
					'email1' => $email1,
					'email2' => $email2,
					'userId' => $userId,
					'fullAddress' => $fullAddress,
					'otherDetails' => $otherDetails,
					'numberofLicence' => $numberofLicence,
					'availableLincence'=>$numberofLicence,
					'website'=>$website,
					'smsSender'=>$smsSender,
					'smsProvider'=>$smsProvider,
					'providerUserName'=>$providerUserName,
					'providerPassword'=>$providerPassword,
					
					
					
			);
			
			$detailsJson = json_encode ( $details );
			$redis->hmset ( 'H_Franchise', $fcode,$detailsJson);
			
			$redis->sadd ( 'S_Users_' . $fcode, $userId );
			$password='awesome';
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo', $mobileNo1,$userId.':email',$email1 ,$userId.':password',$password,$userId.':OWN','admin');
			

			
			
			
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
			$user->mobileNo='1234567890';
			$user->username=$vamosid;
			$user->email='support@vamosys.com';
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
		
	
		/*$franDetails = $redis->hmget ( 'H_Franchise', $fcode.':fname',$fcode.':descrption:',
				$fcode.':landline',$fcode.':mobileNo1',$fcode.':mobileNo2',
				$fcode.':email1',$fcode.':email2',$fcode.':userId');*/
				$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);	
				$franDetails=json_decode($franDetails_json,true);
		$franchiseDetails = implode ( '<br/>', $franDetails );
		
		return View::make ( 'vdm.franchise.show', array (
				'fname' => $franDetails['fname'] 
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
		
		/*$franchiseDetails = $redis->hmget ( 'H_Franchise', $fcode.':fname',$fcode.':description',
				$fcode.':landline',$fcode.':mobileNo1',$fcode.':mobileNo2',
				$fcode.':email1',$fcode.':email2',$fcode.':userId',$fcode.':fullAddress',$fcode.':otherDetails');*/
			
		$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
				$franchiseDetails=json_decode($franDetails_json,true);
		
		if(isset($franchiseDetails['description'])==1)
			$description=$franchiseDetails['description'];
		else
			$description='';
		if(isset($franchiseDetails['landline'])==1)
			$landline=$franchiseDetails['landline'];
		else
			$landline='';
		if(isset($franchiseDetails['mobileNo1'])==1)
			$mobileNo1=$franchiseDetails['mobileNo1'];
		else
			$mobileNo1='';
		if(isset($franchiseDetails['mobileNo2'])==1)
			$mobileNo2=$franchiseDetails['mobileNo2'];
		else
			$mobileNo2='';
			
		if(isset($franchiseDetails['email1'])==1)
			$email1=$franchiseDetails['email1'];
		else
			$email1='';
		if(isset($franchiseDetails['email2'])==1)
			$email2=$franchiseDetails['email2'];
		else
			$email2='';
		if(isset($franchiseDetails['userId'])==1)
			$userId=$franchiseDetails['userId'];
		else
			$userId='';
		if(isset($franchiseDetails['fullAddress'])==1)
			$fullAddress=$franchiseDetails['fullAddress'];
		else
			$fullAddress='';
		if(isset($franchiseDetails['otherDetails'])==1)
			$otherDetails=$franchiseDetails['otherDetails'];
		else
			$otherDetails='';
		if(isset($franchiseDetails['numberofLicence'])==1)
			$numberofLicence=$franchiseDetails['numberofLicence'];
		else
			$numberofLicence='0';
		if(isset($franchiseDetails['availableLincence'])==1)
			$availableLincence=$franchiseDetails['availableLincence'];
		else
			$availableLincence='0';
		if(isset($franchiseDetails['website'])==1)
			$website=$franchiseDetails['website'];
		else
			$website='';
		if(isset($franchiseDetails['smsSender'])==1)
			$smsSender=$franchiseDetails['smsSender'];
		else
			$smsSender='';
		if(isset($franchiseDetails['smsProvider'])==1)
			$smsProvider=$franchiseDetails['smsProvider'];
		else
			$smsProvider='nill';
		if(isset($franchiseDetails['providerUserName'])==1)
			$providerUserName=$franchiseDetails['providerUserName'];
		else
			$providerUserName='';
		if(isset($franchiseDetails['providerPassword'])==1)
			$providerPassword=$franchiseDetails['providerPassword'];
		else
			$providerPassword='';




		Session::put('available',$availableLincence);
		Session::put('numberofLicence',$numberofLicence);
		return View::make ( 'vdm.franchise.edit', array (
				'fname' => $franchiseDetails['fname'] 
		) )->with ( 'fcode', $fcode )->with ( 'franchiseDetails', $franchiseDetails )
		->with('description',$description)
		->with('landline',$landline)
		->with('mobileNo1',$mobileNo1)
		->with('mobileNo2',$mobileNo2)
		->with('email1',$email1)
		->with('email2',$email2)
		->with('userId',$userId)
		->with('fullAddress',$fullAddress)
		->with('otherDetails',$otherDetails)
		->with('numberofLicence',$numberofLicence)
		->with('availableLincence',$availableLincence)
		->with('website',$website)
		->with('smsSender',$smsSender)
		->with('smsProvider',$smsProvider)
		->with('providerUserName',$providerUserName)
		->with('providerPassword',$providerPassword)
		->with('smsP',VdmFranchiseController::smsP());
	

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

				'email1' => 'required|email',
				'email2' => 'email',
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
			$numberofLicence = Input::get ('addLicence');	
			$website= Input::get ('website');
			$smsSender=Input::get ('smsSender');
			$smsProvider=Input::get ('smsProvider');
			$providerUserName=Input::get ('providerUserName');
			$providerPassword=Input::get ('providerPassword');
			$redis = Redis::connection ();
				
				if($numberofLicence==null)
				{
					$numberofLicence=0;
				}
				/*if($numberofLicence<Session::get('available'))
				{
					log::info('--------------inside less value-----------');
					return Redirect::to ( 'vdmFranchises/update' )->withErrors ( 'Please check the License count' );
				}
				else{*/
					$availableLincence=$numberofLicence+Session::get('available');
					$numberofLicence=$numberofLicence+Session::get('numberofLicence');
				//}
				
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
			$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
				$franchiseDetails=json_decode($franDetails_json,true);
			
			$userId = $franchiseDetails['userId'];
			$fname =$franchiseDetails['fname'];
			
			/*$redis->hmset ( 'H_Franchise', $fcode.':description',$description,
					$fcode.':landline',$landline,$fcode.':mobileNo1',$mobileNo1,$fcode.':mobileNo2',$mobileNo2,
					$fcode.':email1',$email1,$fcode.':email2',$email2);*/
					
					$details = array (
					'fname' => $fname,
					'description' => $description,
					'landline' => $landline,
					'mobileNo1' => $mobileNo1,					
					'mobileNo2' => $mobileNo2,
					'email1' => $email1,
					'email2' => $email2,
					'userId' => $userId,
					'fullAddress' => $fullAddress,
					'otherDetails' => $otherDetails,
					'numberofLicence' => $numberofLicence,
					'availableLincence'=>$availableLincence,
					'website'=>$website,
					'smsSender'=>$smsSender,
					'smsProvider'=>$smsProvider,
					'providerUserName'=>$providerUserName,
					'providerPassword'=>$providerPassword,		
			);
			$detailsJson = json_encode ( $details );
			$redis->hmset ( 'H_Franchise', $fcode,$detailsJson);
			
			
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
		
		$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
				$franchiseDetails=json_decode($franDetails_json,true);
			
			$userId = $franchiseDetails['userId'];
			$fname =$franchiseDetails['fname'];
			$email1 = $franchiseDetails['email1'];
		
		
		/*$userId = $redis->hget('H_Franchise',$fcode.':userId');
		$fname = $redis->hget('H_Franchise',$fcode.':fname');
		
		$email1 = $redis->hget('H_Franchise', $fcode.':email1');*/
		
		$redis->hdel ( 'H_Franchise', $fcode);
				
				
				
		
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
		
		Mail::queue('emails.welcome', array('fname'=>$fname,'userId'=>$userId), function($message)
		{
			Log::info("Inside email :" . Session::get ( 'email1' ));
		
			$message->to(Session::pull ( 'email1' ))->subject('User Id deleted');
		});
	
		
		Session::flash ( 'message', 'Successfully deleted ' . 'fname:'.$fcode . '!' );
		return Redirect::to ( 'vdmFranchises' );
	}
}

