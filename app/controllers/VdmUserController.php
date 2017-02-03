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
			$vehicleGroups = Input::get ( 'vehicleGroups' );
			$mobileNo = Input::get ( 'mobileNo' );
            foreach ( $vehicleGroups as $grp ) {
				$redis->sadd ( $userId, $grp );
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
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo', $mobileNo,$userId.':email',$email ,$userId.':password',$password,$userId.':OWN',$OWN);
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
			return Redirect::to ( 'vdmUsers' );
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
		with('email',$email)->with('selectedGroups',$selectedGroups)->with('orgsList',$orgsList)->with('selectedOrgsList',$selectedOrgsList)->with('value',$value);
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
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$rules = array (
				'mobileNo' => 'required',
				'email' => 'required',
				'vehicleGroups' => 'required' 
		);
		$validator = Validator::make ( Input::all(), $rules );
		if ($validator->fails ()) {
			Log::error('VDM User Controller update validation failed');
			Session::flash ( 'message', 'Update failed. Please check logs for more details' . '!' );
			return Redirect::to ( 'vdmUsers/update' )->withErrors ( $validator );
		} else {
			// store
			
			
			$vehicleGroups = Input::get ( 'vehicleGroups' );
			
			$mobileNo = Input::get ( 'mobileNo' );
			$email = Input::get ( 'email' );
			$redis->del ( $userId );
			foreach ( $vehicleGroups as $grp ) {
				$redis->sadd ( $userId, $grp );
			}
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo', $mobileNo,$userId.':email',$email );

			 $virtualaccount=Input::get ( 'virtualaccount' );

			 if($virtualaccount=='value')
			 {
			 	$redis->sadd ( 'S_Users_Virtual_' . $fcode, $userId );
			 }else
			 {
			 	$redis->srem ( 'S_Users_Virtual_' . $fcode, $userId );
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
			return Redirect::to ( 'vdmUsers' );
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

}
