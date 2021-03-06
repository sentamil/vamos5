<?php
class VdmUserController extends \BaseController {
	
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
		$redisUserCacheId = 'S_Users_' . $fcode;
		if(Session::get('cur')=='dealer')
		{
			log::info( '------login 1---------- '.Session::get('cur'));
			
			$redisUserCacheId = 'S_Users_Dealer_'.$username.'_'.$fcode;
		}
		else if(Session::get('cur')=='admin')
		{
			$redisUserCacheId = 'S_Users_Admin_'.$fcode;
		}
	
		$userList = $redis->smembers ( $redisUserCacheId);
		
		$userGroups = null;
		$userGroupsArr = null;
		foreach ( $userList as $key => $value ) {
			
			
			$userGroups = $redis->smembers ( $value);
			
			$userGroups = implode ( '<br/>', $userGroups );
			
			$userGroupsArr = array_add ( $userGroupsArr, $value, $userGroups );
		}
		
		return View::make ( 'vdm.users.index' )->with ( 'fcode', $fcode )->with ( 'userGroupsArr', $userGroupsArr )->with ( 'userList', $userList );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function search()		
    {		
        log::info(' reach the road speed function ');		
        $orgLis = [];		
            return View::make('vdm.users.scan')->with('userList', $orgLis);		
    }		
	public function scan() {		
       if (! Auth::check ()) {		
            return Redirect::to ( 'login' );		
        }		
        $username = Auth::user ()->username;		
        $redis = Redis::connection ();		
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );		
        Log::info('username:' . $username . '  :: fcode' . $fcode);		
        $redisUserCacheId = 'S_Users_' . $fcode;		
        if(Session::get('cur')=='dealer')		
        {		
            log::info( '------login 1---------- '.Session::get('cur'));		
            		
            $redisUserCacheId = 'S_Users_Dealer_'.$username.'_'.$fcode;		
        }		
        else if(Session::get('cur')=='admin')		
        {		
            $redisUserCacheId = 'S_Users_Admin_'.$fcode;		
        }		
        $userList = $redis->smembers ( $redisUserCacheId);		
        $text_word = Input::get('text_word');		
        $cou = $redis->SCARD($redisUserCacheId); // log::info($cou);		
        $orgLi = $redis->sScan( $redisUserCacheId, 0, 'count', $cou, 'match', '*'.$text_word.'*'); // log::info($orgLi);		
        $orgL = $orgLi[1];		
        $userGroups = null;		
        $userGroupsArr = null;		
        foreach ( $orgL as $key => $value ) {                   		
            $userGroups = $redis->smembers ( $value);           		
            $userGroups = implode ( '<br/>', $userGroups );   		
            $userGroupsArr = array_add ( $userGroupsArr, $value, $userGroups );		
        }   		
        return View::make ( 'vdm.users.scan' )->with ( 'fcode', $fcode )->with ( 'userGroupsArr', $userGroupsArr )->with ( 'userList', $orgL );		
    }		
    /**		
     * Show the form for creating a new resource.		
     * @return Response		
     */
	public function create() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		Log::info('---------------users:--------------');
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$redisGrpId = 'S_Groups_' . $fcode;
		if(Session::get('cur')=='dealer')
		{
			log::info( '------login 1---------- '.Session::get('cur'));
			$redisGrpId = 'S_Groups_Dealer_'.$username.'_'.$fcode;
		}
		else if(Session::get('cur')=='admin')
		{
			$redisGrpId = 'S_Groups_Admin_'.$fcode;
		}
		
		// $vehicleGroups=array("No groups found");
		$vehicleGroups = null;
		$size = $redis->scard ( $redisGrpId );
		if ($size > 0) {
			
			$groups= $redis->smembers ( $redisGrpId );
			
			foreach ( $groups as $key => $value ) {
				$vehicleGroups = array_add ( $vehicleGroups, $value, $value );
			}
		}
        
        $size = $redis->scard('S_Organisations_'.$fcode);
        $orgsList=array();
        if ($size > 0) {
            $orgs = $redis->smembers('S_Organisations_'.$fcode);
            foreach ( $orgs as $key => $value ) {
                $orgsList = array_add ( $orgsList, $value, $value );
            }
        }
        $user=null;
		
		$user1= new VdmDealersController;
		$user=$user1->checkuser();
		return View::make ( 'vdm.users.create' )->with ( 'vehicleGroups', $vehicleGroups )->with('orgsList',$orgsList)->with ( 'user', $user );
	}
	
	/**
	 * Store a newly created resource in storage.
	 * TODO validations should be improved to prevent any attacks
	 * 
	 * @return Response
	 */
	public function store() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		
		$rules = array (
				'userId' => 'required|alpha_dash',
				'email' => 'required|email',
				'vehicleGroups' => 'required'  
				);
                
                
		$validator = Validator::make ( Input::all (), $rules );
       
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmUsers/create' )->withErrors ( $validator );
		}else {
		      $userId = Input::get ( 'userId' );
              $val = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':fcode' );
              $val1= $redis->sismember ( 'S_Users_' . $fcode, $userId );
		}
		if (strpos($userId, 'admin') !== false || strpos($userId, 'ADMIN') !== false) 
		{
			return Redirect::to ( 'vdmUsers/create' )->withErrors ( 'Name with admin not acceptable' );
		}
		if($val1==1 || isset($val)) {
			Session::flash ( 'message', $userId . ' already exist. Please use different id ' . '!' );
			return Redirect::to ( 'vdmUsers/create' );
		}
		else {
			// store
			
			$userId = Input::get ( 'userId' );
			$email = Input::get ( 'email' );
			$vehicleGroups = Input::get ( 'vehicleGroups' ); log::info($vehicleGroups);
			$mobileNo = Input::get ( 'mobileNo' );
			$zoho = Input::get ( 'zoho' ); log::info($zoho);
            foreach ( $vehicleGroups as $grp ) {
				$redis->sadd ( $userId, $grp );
				///ram noti
				 $redis->sadd ( 'S_'.$grp, $userId );
				 ///
				// log::info($grp);  
				// $grpVehi=$redis->smembers($grp);
				// foreach ($grpVehi as $keyV => $valueV) 
				// {
				// 	$checkU=$redis->hget('H_Vehicle_Map_Uname_'.$fcode, $valueV.'/'.$grp);
				// 	if(empty($checkU)) 
				// 	{
				// 	   log::info("vehi data empty");
    //                    $redis->hset('H_Vehicle_Map_Uname_'.$fcode, $valueV.'/'.$grp, $userId);               
    //            	    } 
    //            		else {
    //            		   $redis->hset('H_Vehicle_Map_Uname_'.$fcode, $valueV.'/'.$grp, $checkU.'/'.$userId);
    //            		}
                        
    //            	}
				///
			}
            // thirumani set Reports
                        if(Session::get('cur')=='dealer')
                        {
                        log::info( '------login 1---------- '.Session::get('cur'));

                        $totalReports = $redis->smembers('S_Users_Reports_Dealer_'.$username.'_'.$fcode);
                        }
                        else if(Session::get('cur')=='admin')
                        {
                                        $totalReports = $redis->smembers('S_Users_Reports_Admin_'.$fcode);
                        }
                        if($totalReports != null)
                        {
                                foreach ($totalReports as $key => $value) {
                                        $redis-> sadd('S_Users_Reports_'.$userId.'_'.$fcode, $value);
                                }
                        }

            $virtualaccount=Input::get ( 'virtualaccount' );


			$notificationset = 'S_VAMOS_NOTIFICATION';
			$groupList = $redis->smembers ( $notificationset);
			$notiString=implode(",", $groupList);
			$redis->hset("H_Notification_Map_User",$userId,$notiString);   
			log::info(Input::get ( 'virtualaccount' ). '------login 1---------- ');

			if($virtualaccount=='value')
			{
				$redis->sadd ( 'S_Users_Virtual_' . $fcode, $userId );
			}

			$redis->sadd ( 'S_Users_' . $fcode, $userId );
			if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				$redis->sadd('S_Users_Dealer_'.$username.'_'.$fcode,$userId);
				$OWN=$username;
			}
			else if(Session::get('cur')=='admin')
			{
				$redis->sadd('S_Users_Admin_'.$fcode,$userId);
				$OWN='admin';
			}
			
			$password=Input::get ( 'password' );
			if($password==null)
			{
				$password='awesome';
			} 
			log::info( '------login 8---------- ' );
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo', $mobileNo,$userId.':email',$email ,$userId.':password',$password,$userId.
				':zoho',$zoho, $userId.':OWN',$OWN);
			log::info( '------login 9---------- ' );
			$user = new User;
			
			$user->name = $userId;
			$user->username=$userId;
			$user->email=$email;
			$user->mobileNo=$mobileNo;
			
			$user->password=Hash::make($password);
			$user->save();

			$notificationset = 'S_VAMOS_NOTIFICATION';
			$notificationGroups =  $redis->smembers ( $notificationset);
			if(count($notificationGroups)>0)
			{
				$notification=implode(",",$notificationGroups);
				$redis->hset("H_Notification_Map_User",$userId,$notification);
			}
			
			
			// redirect
			Session::flash ( 'message', 'Successfully created ' . $userId . '!' );
			return Redirect::to ( 'vdmUserScan/user'.$userId );
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
		
		$redis = Redis::connection ();
		$userId = $id;
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$mobileNo = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':mobileNo' );
		$email = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':email' );
		$vehicleGroups = $redis->smembers ( $userId );
		//$redis->sadd ( 'S_Users_Virtual_' . $fcode, $userId );
		$value=false;
		 if($redis->sismember ( 'S_Users_Virtual_' . $fcode, $userId )==1)
		 {
		 	$value=true;
		 }

		$vehicleGroups = implode ( '<br/>', $vehicleGroups );
		
		return View::make ( 'vdm.users.show', array (
				'userId' => $userId 
		) )->with ( 'vehicleGroups', $vehicleGroups )->with('mobileNo',$mobileNo)->with('email',$email)->with('value',$value);
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
		$userId = $id;
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$mobileNo = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':mobileNo' );
		$email = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':email' );
		
		$zoho = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':zoho' );
		
		$redisGrpId = 'S_Groups_' . $fcode;
		if(Session::get('cur')=='dealer')
		{
			log::info( '------login 1---------- '.Session::get('cur'));
			$redisGrpId = 'S_Groups_Dealer_'.$username.'_'.$fcode;
		}
		else if(Session::get('cur')=='admin')
		{
			$redisGrpId = 'S_Groups_Admin_'.$fcode;
		}
		
	
		$groupList = $redis->smembers ( $redisGrpId);
		
		$vehicleGroups = null;
		
		$selectedGroups = $redis->smembers($userId); 
		
		
		foreach ( $groupList as $key => $value ) {
			$vehicleGroups = array_add ( $vehicleGroups, $value, $value );
		}
        
        $size = $redis->scard('S_Organisations_'.$fcode);
       
        $orgsList=array();
        if ($size > 0) {
            $orgs = $redis->smembers('S_Organisations_'.$fcode);
            foreach ( $orgs as $key => $value ) {
                $orgsList = array_add ( $orgsList, $value, $value );
            }
        }
        
        $size = $redis->scard('S_Orgs_' .$userId . '_' . $fcode);
        
        $selectedOrgsList=array();
        if($size >0) {
             $orgs = $redis->smembers('S_Orgs_' .$userId . '_' . $fcode);
            foreach ( $orgs as $key => $value ) {
                $selectedOrgsList = array_add ( $selectedOrgsList, $value, $value );
            }
            
        }
  		$value=false;
		 if($redis->sismember ( 'S_Users_Virtual_' . $fcode, $userId )==1)
		 {
		 	$value=true;
		 }
		
		return View::make ( 'vdm.users.edit', array (
				'userId' => $userId 
		) )->with ( 'vehicleGroups', $vehicleGroups )->with ( 'mobileNo', $mobileNo )->
		with('email',$email)->with ('zoho', $zoho )->with('selectedGroups',$selectedGroups)->with('orgsList',$orgsList)->with('selectedOrgsList',$selectedOrgsList)->with('value',$value);
	}




	public function notification($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$userId = $id;
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		
		
		
		$notificationset = 'S_VAMOS_NOTIFICATION';
		
		$notification=$redis->hget("H_Notification_Map_User",$userId);
		$notificationArray=array();
		if($notification!==null)
		{
			$notificationArray=explode(",",$notification);
		}
		$groupList = $redis->smembers ( $notificationset);
		
		$notificationGroups = null;
		
		 
		
		
		foreach ( $groupList as $key => $value ) {
			$notificationGroups = array_add ( $notificationGroups, $value, $value );
		}
        
       
        
      
  		
		return View::make ( 'vdm.users.notification', array (
				'userId' => $userId 
		) )->with ( 'notificationGroups', $notificationGroups )
		->with('notificationArray',$notificationArray);
	}

public function updateFn()
{
	$userId = Input::get ( 'userId' );
	if (! Auth::check ()) {
		return Redirect::to ( 'login' );
	}
	$username = Auth::user ()->username;
	$redis = Redis::connection ();
	$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
	$notificationGroups = Input::get ( 'notificationGroups' );
	if(count($notificationGroups)>0)
	{
		$notification=implode(",",$notificationGroups);
		$redis->hset("H_Notification_Map_User",$userId,$notification);
	} else {
		$redis->hset("H_Notification_Map_User",$userId,'');
	}

	log::info($userId);
}


	


public function updateNotification() {
	
	// $userId = Input::get ( 'userId' );
	// if (! Auth::check ()) {
	// 	return Redirect::to ( 'login' );
	// }
	$notify= new VdmUserController;
	$notify->updateFn();
	// Session::flash ( 'message', 'Successfully updated  Notification' . $userId . '!' );
	return Redirect::to ( 'vdmUsers' );
		
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function update($id) {
	
		$userId = $id;
		
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username 	= Auth::user ()->username;
		$redis 		= Redis::connection ();
		$fcode 		= $redis->hget ( 'H_UserId_Cust_Map', $userId . ':fcode' );
		$oldMob 	= $redis->hget ( 'H_UserId_Cust_Map', $userId . ':mobileNo' );
		$oldmail	= $redis->hget ( 'H_UserId_Cust_Map', $userId . ':email' );
		$oldzoho       = $redis->hget ( 'H_UserId_Cust_Map', $userId .  ':zoho' );
		$oldVirtual = $redis->sismember ( 'S_Users_Virtual_'. $fcode,  $userId);
		$oldGroup	= (array) $redis->smembers( $userId );


		$rules = array (
				'mobileNo' => 'required',
				'email' => 'required',
				'email' => 'required',
				'vehicleGroups' => 'required' 
		);
		$validator = Validator::make ( Input::all(), $rules );
		if ($validator->fails ()) {
			Log::error('VDM User Controller update validation failed');
			Session::flash ( 'message', 'Update failed. Please check logs for more details' . '!' );
			return Redirect::to ( 'vdmUsers/'.$id.'/edit' )->withErrors ( $validator );
		} else {
			// store
			
			
			$vehicleGroups = Input::get ( 'vehicleGroups' );
			///ram noti
			$result=array_diff($oldGroup,$vehicleGroups);
			foreach ( $result as $delGrp )
			{
		        $delVehiList=$redis->smembers('S_'.$delGrp);
		        log::info($delVehiList);
		        $checkUD=$redis->srem('S_'.$delGrp, $userId);
		    }
		    $resultAdd=array_diff($vehicleGroups,$oldGroup);
			foreach ( $resultAdd as $addGrp )
			{
			    $addVehiList=$redis->smembers('S_'.$addGrp);
			    log::info($addVehiList);
			    $addU=$redis->sadd('S_'.$addGrp, $userId);
            }
            ///
			$mobileNo = Input::get ( 'mobileNo' );
			$email = Input::get ( 'email' );
			$zoho = Input::get ( 'zoho' );
			$redis->del ( $userId );
			foreach ( $vehicleGroups as $grp ) {
				$redis->sadd ( $userId, $grp );
			}
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo', $mobileNo,$userId.':email',$email ,$userId . ':zoho',$zoho);

			 $virtualaccount=Input::get ( 'virtualaccount' );

			 if($virtualaccount=='value')
			 {
			 	$redis->sadd ( 'S_Users_Virtual_' . $fcode, $userId );
			 }else
			 {
			 	$redis->srem ( 'S_Users_Virtual_' . $fcode, $userId );
			 }
			

			$mailId = array();
        	$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
        	$franchiseDetails=json_decode($franDetails_json,true);
        	if(isset($franchiseDetails['email1'])==1){
                $mailId[]               = $franchiseDetails['email2'];
        		log::info(array_values($mailId));
        	}
        	
        	if(Session::get('cur')=='dealer')
        	{
        		log::info( '------login 1---------- '.$redis->hget ( 'H_UserId_Cust_Map', $username . ':email' ));
                $mailId[] =   $redis->hget ( 'H_UserId_Cust_Map', $username . ':email' );
        		
        	}

        	$oldList = array();
        	$newList = array();

        	if($mobileNo != $oldMob){
				$oldList = array_add($oldList, 'Mobile No ', $oldMob);
        		$newList = array_add($newList, 'Mobile No ', $mobileNo);        		
        	}

        	if($oldmail != $email){
        		$oldList = array_add($oldList, 'Email ', $oldmail);
        		$newList = array_add($newList, 'Email ', $email);        			
        	}
            
            if($zoho != $oldzoho){
				$oldList = array_add($oldList, 'Zoho', $oldzoho);
        		$newList = array_add($newList, 'Zoho ', $zoho);        		
        	}

        	if($redis->sismember ( 'S_Users_Virtual_'. $fcode,  $userId) != $oldVirtual){
        		$oldList = array_add($oldList, 'Virtual Account ', (($oldVirtual == 1) ? true : false));
        		$newList = array_add($newList, 'Virtual Account ', (($redis->sismember ( 'S_Users_Virtual_'. $fcode,  $userId) ==1 )? true : false));       	
        	}

        	if($vehicleGroups != $oldGroup){
        		$oldList = array_add($oldList, 'Group List ', implode(",",$vehicleGroups));
        		$newList = array_add($newList, 'Group List ', implode(",",$oldGroup));
        	}
        	
        	
        	// log::info(array_values($oldList));
        	// log::info(array_values($newList));
        	// log::info(array_values($mailId));
        	if((count($oldList) >0) && (count($newList) >0))
	        	if(sizeof($mailId) > 0)
	        		try{
	        			log::info(' inside the try function ');
	        			$caption = "User Id";
			        	Mail::queue('emails.user', array('username'=>$fcode, 'groupName'=>$id, 'oldVehi'=>$newList, 'newVehi'=> $oldList, 'cap'=>$caption), function($message) use ($mailId, $id)
			        	{
			                //Log::info("Inside email :" . Session::get ( 'email' ));
			        		$message->to($mailId)->subject('User Id Updated -' . $id);
			        	});
			        } catch(\Swift_TransportException $e){
				        log::info($e->getMessage());
				    }
           // 
           /* $orgsList = Input::get ( 'orgsList' );
            // $orgs = $redis->smembers('S_Orgs_' .$userId . '_' . $fcode);
            $redis->del('S_Orgs_' .$userId . '_' . $fcode);
            
           if(empty($orgsList)) {
               
           }
           else {
               foreach ( $orgsList as $org ) {
                    $redis->sadd ( 'S_Orgs_' .$userId . '_' . $fcode, $org );
                }
            }*/
                
                
			// redirect
			Session::flash ( 'message', 'Successfully updated ' . $userId . '!' );
			return Redirect::to ( 'vdmUserScan/user'.$userId );
		}
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
		$userId = $id;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$redis->srem ( 'S_Users_' . $fcode, $userId );
		$redis->srem('S_Users_Dealer_'.$username.'_'.$fcode,$userId);
		$redis->srem('S_Users_Admin_'.$fcode,$userId);
		///ram noti
		$getUser=$redis->smembers($userId);
		foreach ($getUser as $key => $getU) {
			 $redis->srem('S_'.$getU, $userId);
		}
		///
		
		$redis->del ( $userId );
         $redis->del('S_Orgs_' .$userId . '_' . $fcode);

		$email=$redis->hget('H_UserId_Cust_Map',$userId.':email');
		$redis->hdel ( 'H_UserId_Cust_Map', $userId . ':fcode', $userId . ':mobileNo', $userId.':email',$userId.':password');
		$redis->hdel ( 'H_Notification_Map_User', $userId);
		
		Log::info(" about to delete user" .$userId);
		
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
		return Redirect::to ( 'vdmUsers' );
	}

	public function userIdCheck(){
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		Log::info(' inside the function ');
		$userId = Input::get ( 'id' );
      	$val = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':fcode' );
      	$val1= $redis->sismember ( 'S_Users_' . $fcode, $userId );
      	if($val1==1 || isset($val)) {
      		return 'Already exist. Please use different id ';
      	}
      	if($userId== 'ADMIN' || $userId == 'Admin' || $userId == 'admin'){
      		return 'Name with admin not acceptable ';
      	}
	}


	public function notificationFrontend() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$notificationset = 'S_VAMOS_NOTIFICATION';
		$notification=$redis->hget("H_Notification_Map_User",$username);
		$notificationArray=array();
		if($notification!==null)
		{
			$notificationArray=explode(",",$notification);
		}
		$groupList = $redis->smembers ( $notificationset);
		$notificationGroups = array();
		foreach ( $groupList as $key => $value ) {

			$notificationGroups = array_add ( $notificationGroups, $value, $value );
			$notificationGroups[$value] = 'false';
			
			if(sizeof($notificationArray) > 0)
			{
				foreach ($notificationArray as $ky => $val) {
				
					if($val ==  $value){
						$notificationGroups[$value] = 'true';
					}
				}
			}

		}
        
        
		return (sizeof($notificationGroups) > 0) ? $notificationGroups : 'fail';
	}

	public function notificationFrontendUpdate(){

		try{
			$notify= new VdmUserController;
			$notify->updateFn();
			return 'success';
		}catch(\Exception $e) {
			return 'fail';
		}
	}

    public function reports($id)
    {
     if (! Auth::check ()) {
        return Redirect::to ( 'login' );
     }
     $totalReportList = array();
     $list = array();
     $totalList = array();
     $reportsList = array();
     $username = Auth::user ()->username;
     $user = $id;
     $redis = Redis::connection ();
     $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
     $totalReport = null;
     if(Session::get('cur')=='dealer')
        {
            log::info( '------login 1---------- '.Session::get('cur'));
            $dealeReportLen = $redis->scard('S_Users_Reports_Dealer_'.$username.'_'.$fcode);
            $adminLength =      $redis->scard('S_Users_Reports_Admin_'.$fcode);
            if($adminLength >= $dealeReportLen)
            {
                $totalReports = $redis->smembers('S_Users_Reports_Dealer_'.$username.'_'.$fcode);
            }else{
                $totalReports = $redis->smembers('S_Users_Reports_Admin_'.$fcode);
                log::info('its incorrect report');
                $redis->del('S_Users_Reports_Dealer_'.$username.'_'.$fcode);
                foreach ($totalReports as $key => $value) {
                        $redis->sadd('S_Users_Reports_Dealer_'.$username.'_'.$fcode, $value);
                }
            }
        }
        else if(Session::get('cur')=='admin')
        {
            $totalReports = $redis->smembers('S_Users_Reports_Admin_'.$fcode);
        }
        $isVirtualuser = $redis->sismember("S_Users_Virtual_".$fcode, $user);
        $virtualReports = array();
        log::info(gettype($virtualReports));
        if($isVirtualuser)
         {
           $virtualReports = $redis->smembers('S_UserVirtualReports');
         }
        if($totalReports != null){
                foreach ($totalReports as $key => $value) {
                        if(in_array($value, $virtualReports))
                        {
                                log::info('checking');
                        }else{

                                $totalReport[explode(":",$value)[1]][] = $value;
                        }
                }
                $totalList = $totalReport;
                }
        if($totalList == null)
        {
                $totalReport = $redis->keys("*_Reports");
                $report[] = array();
                        foreach ($totalReport as $key => $getReport) {
                        $specReports = $redis->smembers($getReport);
                        foreach ($specReports as $key => $ReportName) {
                                // $report[]=$ReportName.':'.$reportType[0];
                        }
                        // log::info($report);
                        $reportsList[] = $getReport;
                        $totalReportList[] = $report;
                        $totalList[$getReport] = $report;
                        $report = null;
                $dealerOrUser = $redis->sismember('S_Dealers_'.$fcode, $user);
                if($dealerOrUser)
                        {
                                return Redirect::to ( 'vdmDealers' );
                        }else
                        {
                                return Redirect::to ( 'vdmUsers' );
                        }
                        // log::info('mmmmm');
                        // log::info($totalReport);
                }
        }
                $dealerOrUser = $redis->sismember('S_Dealers_'.$fcode, $user);
                if($dealerOrUser)
                {
                        $userReports = $redis->smembers("S_Users_Reports_Dealer_".$user.'_'.$fcode);
                }else
                {
                        $userReports = $redis->smembers("S_Users_Reports_".$user.'_'.$fcode);
                }
                log::info($totalList);
				if($userReports==null && $totalReportList==null )
                {
                Session::flash ( 'message', ' No Reports Found' . '!' );
                return View::make ( 'vdm.users.reports', array (
                                'userId' => $user
                ) )->with ( 'reportsList', $reportsList )
                ->with('totalReportList',$totalReportList)
                ->with('totalList',$totalList)
                ->with('userReports',$userReports);
                
                }
                else
                {
                return View::make ( 'vdm.users.reports', array (
                                'userId' => $user
                ) )->with ( 'reportsList', $reportsList )
                ->with('totalReportList',$totalReportList)
                ->with('totalList',$totalList)
                ->with('userReports',$userReports);
				}
        }

        public function updateReports()
        {
                if (! Auth::check ()) {
                        return Redirect::to ( 'login' );
                }
                log::info('Entry');
                $userId = Input::get('userId');
                log::info($userId);
                $username = Auth::user ()->username;
                log::info($username);
            $reportName = Input::get('reportName');
                $redis = Redis::connection ();
                $fcode = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':fcode' );
                $dealerOrUser = false;
                $dealerOrUser = $redis->sismember('S_Dealers_'.$fcode, $userId);
				if($reportName != null)
                {
                        log::info($dealerOrUser);
                        if($dealerOrUser)
                        {
                                log::info('Dealer');
                                $prevReportList = $redis->smembers('S_Users_Reports_Dealer_'.$userId.'_'.$fcode);
                                $redis->del("S_Users_Reports_Dealer_".$userId.'_'.$fcode);
                                foreach ($reportName as $key => $value) {
                                        $redis->sadd("S_Users_Reports_Dealer_".$userId.'_'.$fcode, $value);
                                }
                                $removeList = array();
                                $addList=array();
                                $currentReportList = $redis->smembers('S_Users_Reports_Dealer_'.$userId.'_'.$fcode);
                                foreach ($prevReportList as $key => $value) {
                                if (in_array($value, $currentReportList)) {
                                        $addList[]=$value;
                                        log::info("GotErix");
                                }else
                                {
                                        $removeList[] = $value;
                                        log::info("GotErix2");
                                }
                            }
                        $userList = $redis->smembers("S_Users_Dealer_".$userId.'_'.$fcode);
                        log::info($userList);
                        foreach ($userList as $key => $dealer) {
                                $userReport=$redis->smembers("S_Users_Reports_".$dealer.'_'.$fcode);
                                {
                                        if($userReport==null)
                                        {
                                        
                                        foreach ($reportName as $key => $value) {
                                        $redis->sadd("S_Users_Reports_".$dealer.'_'.$fcode,$value );
                                        
                                        }
                                        }
                                        
                                   
                                }
                                }
                                
                        log::info($removeList);
                        if($removeList != null)
                        {
                                $userList = $redis->smembers("S_Users_Dealer_".$userId.'_'.$fcode);
                                log::info($userList);
                                foreach ($userList as $key => $dealer) {
                                        $redis->srem("S_Users_Reports_".$dealer.'_'.$fcode, $removeList);
                                }

                        }
                        }
                
                        else if(!$dealerOrUser)
                        {
                                log::info('user define');
                                $redis->del("S_Users_Reports_".$userId.'_'.$fcode);
                                $redis->sadd("S_Users_Reports_".$userId.'_'.$fcode, $reportName);
                        }
                }
                else {
                        log::info('jjjjjjjj');

						if($dealerOrUser)
                        {
                                $redis->del("S_Users_Reports_Dealer_".$userId.'_'.$fcode);
								$userList = $redis->smembers("S_Users_Dealer_".$userId.'_'.$fcode);
                                foreach ($userList as $key => $dealer) {
                                $redis->del("S_Users_Reports_".$dealer.'_'.$fcode);
                                }
                        }else
                        {
                                log::info(" correct identification ");
                                 $redis->del("S_Users_Reports_".$userId.'_'.$fcode);
                        }

                }

                log::info($reportName);


                //$redis->sadd('S_Reports_'.$userId.'_'.$fcode, 'SINGLE_TRACK:Tracking', 'HISTORY:Tracking','MULTITRACK:Tracking','CURRENT_STATUS:Consolidatedvehicles','MOVEMENT:Analytics','OVERSPEED:Analytics','PARKED:Analytics','IDLE:Analytics','DAILY:Statistics', 'DAILY_PERFORMANCE:Performance','PAYMENT_REPORTS:Useradmin','RESET_PASSWORD:Useradmin');
                if($dealerOrUser)
                {
                        return Redirect::to ( 'vdmDealers' );
                }else
                {
                        return Redirect::to ( 'vdmUserScan/user'.$userId );
                }
        }
     public function scanNew($id) {		
       if (! Auth::check ()) {		
            return Redirect::to ( 'login' );		
        }		
        $username = Auth::user ()->username;		
        $redis = Redis::connection ();		
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );		
        Log::info('username:' . $username . '  :: fcode' . $fcode);		
        $redisUserCacheId = 'S_Users_' . $fcode;		
        if(Session::get('cur')=='dealer')		
        {		
            log::info( '------login 1---------- '.Session::get('cur'));		
            		
            $redisUserCacheId = 'S_Users_Dealer_'.$username.'_'.$fcode;		
        }		
        else if(Session::get('cur')=='admin')		
        {		
            $redisUserCacheId = 'S_Users_Admin_'.$fcode;		
        }		
        $userList = $redis->smembers ( $redisUserCacheId);
        $text_word1 = $id;
        $text_word= str_replace(' ', '', $text_word1);
		$text_word2 = strtoupper($text_word1);	
		log::info($text_word2);	
        $cou = $redis->SCARD($redisUserCacheId); // log::info($cou);		
        $orgLi = $redis->sScan( $redisUserCacheId, 0, 'count', $cou, 'match', '*'.$text_word.'*'); 
        $orgL=$orgLi[1];
        $userGroups = null;		
        $userGroupsArr = null;	
        	
        foreach ( $orgL as $key => $value ) { 

            $userGroups = $redis->smembers ( $value);
            log::info($userGroups); 
            log::info($value.'--------------');          		
            $userGroups = implode ( '<br/>', $userGroups );   		
            $userGroupsArr = array_add ( $userGroupsArr, $value, $userGroups );	

        }   		
        return View::make ( 'vdm.users.scan' )->with ( 'fcode', $fcode )->with ( 'userGroupsArr', $userGroupsArr )->with ( 'userList', $orgL );		
    }	

		
		
}
	
