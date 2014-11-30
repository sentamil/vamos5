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
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		
		$redisUserCacheId = 'S_Users_' . $cpyCode;
	
		$userList = $redis->smembers ( $redisUserCacheId);
		
		$userGroups = null;
		$userGroupsArr = null;
		foreach ( $userList as $key => $value ) {
			
			
			$userGroups = $redis->smembers ( $value);
			
			$userGroups = implode ( '<br/>', $userGroups );
			
			$userGroupsArr = array_add ( $userGroupsArr, $value, $userGroups );
		}
		
		return View::make ( 'vdm.users.index' )->with ( 'cpyCode', $cpyCode )->with ( 'userGroupsArr', $userGroupsArr )->with ( 'userList', $userList );
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
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		
		$redisGrpId = 'S_Groups_' . $cpyCode;
		// $vehicleGroups=array("No groups found");
		$vehicleGroups = null;
		$size = $redis->scard ( $redisGrpId );
		if ($size > 0) {
			
			$groups= $redis->smembers ( $redisGrpId );
			
			foreach ( $groups as $key => $value ) {
				$vehicleGroups = array_add ( $vehicleGroups, $value, $value );
			}
		}
		
		return View::make ( 'vdm.users.create' )->with ( 'vehicleGroups', $vehicleGroups );
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
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		
		$rules = array (
				'userId' => 'required',
				'email' => 'required|email',
				'vehicleGroups' => 'required'  // TODO need to fix illegal groups
				);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmUsers/create' )->withErrors ( $validator );
		} else {
			// store
			
			$userId = Input::get ( 'userId' );
			$email = Input::get ( 'email' );
			$vehicleGroups = Input::get ( 'vehicleGroups' );
			$mobileNo = Input::get ( 'mobileNo' );
			
			foreach ( $vehicleGroups as $grp ) {
				$redis->sadd ( $userId, $grp );
			}
			
			$redis->sadd ( 'S_Users_' . $cpyCode, $userId );
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':cpyCode', $cpyCode, $userId . ':mobileNo', $mobileNo,$userId.':email',$email );
			$password='awsome';
			User::create(array(
			'name'     => $userId,
			'username' => $userId,
			'email'    => $email,
			'password' => Hash::make($password),
			));
			
			$data= array('fname'=>$userId,'userId'=>$userId,'password'=>$password);
				
			Mail::send('emails.welcome', $data, function($message)
			{
				$message->to('prkothan@gmail.com')->cc('pkgopalpillai@gmail.com');
					
					
			});
			
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
		$size = $redis->llen ( $userId );
		$mobileNo = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':mobileNo' );
		$email = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':email' );
		$vehicleGroups = $redis->lrange ( $userId, 0, $size );
		
		$vehicleGroups = implode ( '<br/>', $vehicleGroups );
		
		return View::make ( 'vdm.users.show', array (
				'userId' => $userId 
		) )->with ( 'vehicleGroups', $vehicleGroups )->with('mobileNo',$mobileNo)->with('email',$email);
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
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		
		$mobileNo = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':mobileNo' );
		$email = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':email' );
		
		
		$redisGrpId = 'L_Groups_' . $cpyCode;
		$size = $redis->llen ( $redisGrpId );
		$groupList = $redis->lrange ( $redisGrpId, 0, $size );
		
		$vehicleGroups = null;
		
		foreach ( $groupList as $key => $value ) {
			$vehicleGroups = array_add ( $vehicleGroups, $value, $value );
		}
		
		return View::make ( 'vdm.users.edit', array (
				'userId' => $userId 
		) )->with ( 'vehicleGroups', $vehicleGroups )->with ( 'mobileNo', $mobileNo )->with('email',$email);
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
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		
		$rules = array (
				'mobileNo' => 'required',
				'vehicleGroups' => 'required' 
		);
		$validator = Validator::make ( Input::all(), $rules );
		if ($validator->fails ()) {
			Log::error('VDM User Controller update validation failed');
			return Redirect::to ( 'vdmUsers/update' )->withErrors ( $validator );
			//return Redirect::to ( 'vdmUsers/edit' )->withErrors ( $validator );
		} else {
			// store
			
			
			$vehicleGroups = Input::get ( 'vehicleGroups' );
			
			$mobileNo = Input::get ( 'mobileNo' );
			$redis->del ( $userId );
			foreach ( $vehicleGroups as $grp ) {
				$redis->sadd ( $userId, $grp );
			}
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':cpyCode', $cpyCode, $userId . ':mobileNo', $mobileNo,$userId.':email',$email );
			
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
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		
		$redis->srem ( 'S_Users_' . $cpyCode, $userId );
		$redis->del ( $userId );
		$redis->hdel ( 'H_UserId_Cust_Map', $userId );
		$redis->del ( 'K_' . $userId );
		Session::flash ( 'message', 'Successfully deleted ' . $userId . '!' );
		return Redirect::to ( 'vdmUsers' );
	}
}
