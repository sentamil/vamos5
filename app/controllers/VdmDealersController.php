<?php
class VdmDealersController extends \BaseController {
	
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
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		Log::info('username:' . $username . '  :: fcode' . $fcode);
		$redisDealerCacheID = 'S_Dealers_' . $fcode;
	
		$dealerlist = $redis->smembers ( $redisDealerCacheID);
		
		$userGroups = null;
		$userGroupsArr = null;
		foreach ( $dealerlist as $key => $value ) {
			
			
			$userGroups = $redis->smembers ( $value);
			
			$userGroups = implode ( '<br/>', $userGroups );
			$detailJson=$redis->hget ( 'H_DealerDetails_' . $fcode, $value);
			$detail=json_decode($detailJson,true);
			$userGroupsArr = array_add ( $userGroupsArr, $value, $detail['mobileNo'] );
		}
		
		return View::make ( 'vdm.dealers.index' )->with ( 'fcode', $fcode )->with ( 'userGroupsArr', $userGroupsArr )->with ( 'dealerlist', $dealerlist );
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
		
	
		Log::info('---------------create:--------------');
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$dealer = 'S_dealer_' . $fcode;
		// $vehicleGroups=array("No groups found");
		$vehicleGroups = null;
		$user=null;
		
		$user1= new VdmDealersController;
		$user=$user1->checkuser();
		return View::make ( 'vdm.dealers.create' )->with ( 'vehicleGroups', $vehicleGroups )->with('user',$user);
	}
	
	
	
	public function checkuser()
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
	}
	/**
	 * Store a newly created resource in storage.
	 * TODO validations should be improved to prevent any attacks
	 * 
	 * @return Response
	 */
	
	
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
		Log::info('---------------inside show:--------------'.$id);
		$redis = Redis::connection ();
		
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$detailJson=$redis->hget ( 'H_DealerDetails_' . $fcode, $id);
			$detail=json_decode($detailJson,true);
		
		$userId=$id;
		$mobileNo = $detail['mobileNo'];
		$email =$detail['email'];
		if(isset($detail['website'])==1)
			$website=$detail['website'];
		else
			$website='';
		$vehicleGroups = $redis->smembers ( $userId );
		
		
		
		return View::make ( 'vdm.dealers.show', array (
				'userId' => $userId 
		) )->with('mobileNo',$mobileNo)->with('email',$email)->with('website',$website);
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	 
	 
	 public function editDealer($id)
	 {
		 log::info( '------@@@@-----------inside edit dealer------@@@@@-------'.$id) ;
		
		 
		 
		 
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$userId = Session::get('user');
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		 log::info( '---------- user----------' . $username) ;
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$detailJson=$redis->hget ( 'H_DealerDetails_' . $fcode, Session::get('user'));
			$detail=json_decode($detailJson,true);
		
		$dealerid=Session::get('user');
		$mobileNo = $detail['mobileNo'];
		$email =$detail['email'];
		if(isset($detail['website'])==1)
			$website=$detail['website'];
		else
			$website='';		
		return View::make ( 'vdm.dealers.edit', array (
				'dealerid' => $dealerid 
		) )->with ( 'mobileNo', $mobileNo )->
		with('email',$email)->with('website',$website);
		 
	 }
	 
	 
	 
	 
	 
	public function edit($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$userId = $id;
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$detailJson=$redis->hget ( 'H_DealerDetails_' . $fcode, $id);
			$detail=json_decode($detailJson,true);
		
		$dealerid=$id;
		$mobileNo = $detail['mobileNo'];
		$email =$detail['email'];	
		if(isset($detail['website'])==1)
			$website=$detail['website'];
		else
			$website='';		
		return View::make ( 'vdm.dealers.edit', array (
				'dealerid' => $dealerid 
		) )->with ( 'mobileNo', $mobileNo )->
		with('email',$email)->with('website',$website);
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function update($id) {
	
		$dealerid = $id;
		
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$rules = array (
				'mobileNo' => 'required',
				'email' => 'required',
				
		);
		$validator = Validator::make ( Input::all(), $rules );
		if ($validator->fails ()) {
			Log::error('VDM User Controller update validation failed');
			Session::flash ( 'message', 'Update failed. Please check logs for more details' . '!' );
			return Redirect::to ( 'vdmDealers/update' )->withErrors ( $validator );
		} else {
			// store
			
			
			
			
			$mobileNo = Input::get ( 'mobileNo' );
			$email = Input::get ( 'email' );
			$website=Input::get ( 'website' );
			$detail=array(
			'email'	=> $email,
			'mobileNo' => $mobileNo,
			'website' => $website,
			);
			
			$detailJson=json_encode($detail);
			$redis->hset ( 'H_DealerDetails_' . $fcode, $dealerid, $detailJson );
			$redis->hmset ( 'H_UserId_Cust_Map', $dealerid . ':fcode', $fcode, $dealerid . ':mobileNo', $mobileNo,$dealerid.':email',$email );
			
			// redirect
			Session::flash ( 'message', 'Successfully updated ' . $dealerid . '!' );
			if(Session::get('cur')=='dealer')
			{
				Session::flash ( 'message', 'Updates Successfully !' );
			return View::make ( 'vdm.dealers.edit', array (
				'dealerid' => $dealerid 
		) )->with ( 'mobileNo', $mobileNo )->
		with('email',$email)->with('website',$website);
			}
			
			return Redirect::to ( 'vdmDealers' );
		}
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	 public function store() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
			Log::info('---------------Dealers:--------------');
		$rules = array (
				'dealerId' => 'required|alpha_dash',
				'email' => 'required|email',
				'mobileNo' => 'required'  
				);
                
                
		$validator = Validator::make ( Input::all (), $rules );
       
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmDealers/create' )->withErrors ( $validator );
		}else {
		      $dealerId = Input::get ( 'dealerId' );
              $val = $redis->hget ( 'H_UserId_Cust_Map', $dealerId . ':fcode' );
              $val1= $redis->sismember ( 'S_Dealers_' . $fcode, $dealerId );
		}
		if (strpos($dealerId, 'admin') !== false || strpos($dealerId, 'ADMIN') !== false) 
		{
			return Redirect::to ( 'vdmDealers/create' )->withErrors ( 'Name with admin not acceptable' );
		}
		if($val1==1 || isset($val)) {
			Log::info('---------------already prsent:--------------');
			Session::flash ( 'message', $dealerId . ' already exist. Please use different id ' . '!' );
			return Redirect::to ( 'vdmDealers/create' );
		}
		else {
			// store
			
			$dealerId = Input::get ( 'dealerId' );
			$email = Input::get ( 'email' );
			$mobileNo = Input::get ( 'mobileNo' );
           $website=Input::get ( 'website' );
			// ram
			$detail=array(
			'email'	=> $email,
			'mobileNo' => $mobileNo,
			'website'=>$website,
			);
			
			$detailJson=json_encode($detail);
			
			$redis->sadd ( 'S_Dealers_' .$fcode, $dealerId );
			$redis->hset ( 'H_DealerDetails_' . $fcode, $dealerId, $detailJson );
			$password=Input::get ( 'password' );
			if($password==null)
			{
				$password='awesome';
			}
			$redis->hmset ( 'H_UserId_Cust_Map', $dealerId . ':fcode', $fcode, $dealerId . ':mobileNo', $mobileNo,$dealerId.':email',$email );
			$user = new User;
			$user->name = $dealerId;
			$user->username=$dealerId;
			$user->email=$email;
			$user->mobileNo=$mobileNo;
			$user->password=Hash::make($password);
			$user->save();
			
			Session::flash ( 'message', 'Successfully created ' . $dealerId . '!' );
			return Redirect::to ( 'vdmDealers' );
		}
	}
	public function destroy($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$userId = $id;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$redis->srem ( 'S_Dealers_' . $fcode, $userId );
		$redis->hdel ( 'H_DealerDetails_' . $fcode, $userId);
		$email=$redis->hget('H_UserId_Cust_Map',$userId.':email');
		$mobileNo=$redis->hget('H_UserId_Cust_Map',$userId.':mobileNo');
		$redis->hdel ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo', $mobileNo,$userId.':email',$email );
		
		$vehicles=$redis->smembers('S_Vehicles_Dealer_'.$userId.'_'.$fcode);
		foreach ($vehicles as $key => $value) {
			Log::info(" vehicle " .$value);
			$redis->srem('S_Vehicles_Dealer_'.$userId.'_'.$fcode,$value);
			$redis->sadd('S_Vehicles_Admin_'.$fcode,$value);
			//$redis->sadd ( 'S_Vehicles_' . $fcode, $vehicle );
		}

		$groups=$redis->smembers('S_Groups_Dealer_'.$userId.'_'.$fcode);
		foreach ($groups as $key => $value1) {
			Log::info(" group " .$value1);
			$redis->srem('S_Groups_Dealer_'.$userId.'_'.$fcode,$value1);
			$redis->sadd('S_Groups_Admin_'.$fcode,$value1);
		}

		$orgDel=$redis->smembers('S_Organisations_Dealer_'.$userId.'_'.$fcode);
		foreach ($orgDel as $key => $value2) {
			Log::info(" org " .$value2);
			$redis->srem('S_Organisations_Dealer_'.$userId.'_'.$fcode,$value2);
			$redis->sadd('S_Organisations_Admin_'.$fcode,$value2);
		}

		$users=$redis->smembers('S_Users_Dealer_'.$userId.'_'.$fcode);
		foreach ($users as $key => $value3) {
			Log::info(" user " .$value3);
			$redis->srem('S_Users_Dealer_'.$userId.'_'.$fcode,$value3);
			$redis->sadd('S_Users_Admin_'.$fcode,$value3);
		}
		Log::info(" about to delete dealer" .$userId);
		
		DB::table('users')->where('username', $userId)->delete();
		
		
		Session::put('email',$email);
		Log::info("Email Id :" . Session::get ( 'email1' ));
	/*	
		Mail::queue('emails.welcome', array('fname'=>$fcode,'userId'=>$userId), function($message)
		{
			Log::info("Inside email :" . Session::get ( 'email' ));
		
			$message->to(Session::pull ( 'email' ))->subject('User Id deleted');
		});
*/
		Session::flash ( 'message', 'Successfully deleted ' . $userId . '!' );
		return Redirect::to ( 'vdmDealers' );
	}
}
