<?php
class BusinessController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		if (! Auth::check () ) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		log::info( 'User name  ::' . $username);
		Session::forget('page');
		
		$redis = Redis::connection ();
		
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		
		
				if(Session::get('cur')=='dealer')
				{	
					$key='H_Pre_Onboard_Dealer_'.$username.'_'.$fcode;			
					
				}
				else if(Session::get('cur')=='admin')
				{
					$key='H_Pre_Onboard_Admin_'.$fcode;
				}
				$details=$redis->hgetall($key);
				$devices=null;
				$devicestypes=null;
				$i=0;
				foreach($details as $key => $value)
				{
					$valueData=json_decode($value,true);
					$devices = array_add($devices, $i,$valueData['deviceid']);
					$devicestypes = array_add($devicestypes,$i,$valueData['deviceidtype']);
					Log::info('i=' . $i);
					$i++;
				}
				
				
				Log::info(' inside multi ' );
        
        $dealerId = $redis->smembers('S_Dealers_'. $fcode);
        
      
        
        $orgArr = array();
		        // $orgArr = array_add($orgArr, 'OWN','OWN');
		if($dealerId!=null)
		{
			foreach($dealerId as $org) {
            $orgArr = array_add($orgArr, $org,$org);
			
        }
		$dealerId = $orgArr;
		}
		else{
			$dealerId=null;
			//$orgArr = array_add($orgArr, 'OWN','OWN');
			$dealerId = $orgArr;
		}

        	$redisUserCacheId = 'S_Users_' . $fcode; //dummy installation
		$redisGrpCacheId='S_Users_';
		
		if(Session::get('cur')=='dealer')
		{
			log::info( '------login 1---------- '.Session::get('cur'));
			
			$redisUserCacheId = 'S_Users_Dealer_'.$username.'_'.$fcode;
			
		}
		else if(Session::get('cur')=='admin')
		{
			$redisUserCacheId = 'S_Users_Admin_'.$fcode;
		}
	
		//$userList = $redis->smembers ( $redisUserCacheId);
		//get the intersection of users set and groups set
		$userList=$redis->sinter($redisUserCacheId,'S_Organisations_'.$fcode);
		$orgArra = array();
      foreach($userList as $org) {
            $orgArra = array_add($orgArra, $org,$org);
			
        }
			$userList=$orgArra;
		
		
		if(Session::get('cur')=='dealer')
		{	return View::make ( 'vdm.business.business1')->with('devices',$devices)->with('devicestypes',$devicestypes)->with('dealerId',$dealerId)->with('userList',$userList);
		}
		else if(Session::get('cur')=='admin')
		{
			$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
			$franchiseDetails=json_decode($franDetails_json,true);
			if(isset($franchiseDetails['availableLincence'])==1)
				$availableLincence=$franchiseDetails['availableLincence'];
			else
				$availableLincence='0';
			Session::put('availableLincence',$availableLincence);
			return View::make ( 'vdm.business.business')->with('devices',$devices)->with('devicestypes',$devicestypes)->with('dealerId',$dealerId)->with('userList',$userList)->with('availableLincence',$availableLincence);
		}
	}
	
	
	public function create() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        //get the Org list
        $tmpOrgList = $redis->smembers('S_Organisations_' . $fcode);
		
		if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				 $tmpOrgList = $redis->smembers('S_Organisations_Dealer_'.$username.'_'.$fcode);
			}
			else if(Session::get('cur')=='admin')
			{
				 $tmpOrgList = $redis->smembers('S_Organisations_Admin_'.$fcode);
			}
		$lincence=Session::get('availableLincence');
        $orgList=null;
		$orgList=array_add($orgList,'Default','Default');
        foreach ( $tmpOrgList as $org ) {
                $orgList = array_add($orgList,$org,$org);
                
            }
		return View::make ( 'vdm.business.create' )->with ( 'orgList', $orgList )->with ( 'lincence', $lincence );
	}
	
	
	
	public function store() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$rules = array (
				'numberofdevice' => 'required|numeric'			
		);
		
		$validator = Validator::make ( Input::all (), $rules );
		log::info( '-------- store in  ::----------');
        
		if ($validator->fails ()) {
			return Redirect::to ( 'Business/create' )->withErrors ( $validator );
		} 
		else{
			
			$numberofdevice = Input::get ( 'numberofdevice' );
			// store
			
			
			if($numberofdevice>Session::get('availableLincence'))
			{
				return Redirect::to ( 'Business/create' )->withErrors ( "Your license count is less" );
			}
			
			Session::put('numberofdevice',$numberofdevice);
			log::info( '--------inside store in  ::----------');
			return View::make ( 'vdm.business.addDevice')->with ( 'numberofdevice', $numberofdevice );
		}
		
		
	}
	
	
	
	public function adddevice() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		/*$rules = array (
				'numberofdevice' => 'required|numeric'			
		);
		
		$validator = Validator::make ( Input::all (), $rules );
		log::info( '-------- store in  ::----------');
        
		if ($validator->fails ()) {
			return Redirect::to ( 'Business/create' )->withErrors ( $validator );
		} 
		else{*/
			
		
			// store
			$deviceidarray=null;
			$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
			$franchiseDetails=json_decode($franDetails_json,true);
			if(isset($franchiseDetails['availableLincence'])==1)
				$availableLincence=$franchiseDetails['availableLincence'];
			else
				$availableLincence='0';
			$count=0;
			for($i =1;$i<=Session::get('numberofdevice');$i++)
			{
				$deviceid = Input::get ( 'deviceid'.$i);
				log::info( '--------inside deviceid in  ::----------'.$deviceid);
				$deviceidtype=Input::get('deviceidtype'.$i);
				log::info( '--------inside deviceidtype in  ::----------'.$deviceidtype);
				if($deviceid!=null && $deviceidtype!=null)
				{
					
					$dev=$redis->hget('H_Device_Cpy_Map',$deviceid);
						if(Session::get('cur')=='dealer')
						{					
							$tempdev=$redis->hget('H_Pre_Onboard_Dealer_'.$username.'_'.$fcode,$deviceid);
						}
						else if(Session::get('cur')=='admin')
						{
							$tempdev=$redis->hget('H_Pre_Onboard_Admin_'.$fcode,$deviceid);
						}
					if($dev==null && $tempdev==null)
					{
						
							$count++;
						
						
						$deviceDataArr = array (
							'deviceid' => $deviceid,
							'deviceidtype' => $deviceidtype
						);
						$deviceDataJson = json_encode ( $deviceDataArr );
						log::info( '------login 1---------- '.Session::get('cur'));
						if(Session::get('cur')=='dealer')
						{					
							$redis->sadd('S_Pre_Onboard_Dealer_'.$username.'_'.$fcode,$deviceid);
							$redis->hset('H_Pre_Onboard_Dealer_'.$username.'_'.$fcode,$deviceid,$deviceDataJson);
						}
						else if(Session::get('cur')=='admin')
						{
							$redis->sadd('S_Pre_Onboard_Admin_'.$fcode,$deviceid);
							$redis->hset('H_Pre_Onboard_Admin_'.$fcode,$deviceid,$deviceDataJson);
						}
						
					}
					else{
						log::info('--------------already present--------------'.$deviceid);
						
						$deviceidarray=array_add($deviceidarray,$deviceid,$deviceid);
						
					}
					
				}
				$deviceid=null;
				$deviceidtype=null;
				
			}
			if($count>0)
			{
				log::info('inside count present');
				$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
				$franchiseDetails=json_decode($franDetails_json,true);
				$franchiseDetails['availableLincence']=$franchiseDetails['availableLincence']-$count;
				log::info('inside count present'.$franchiseDetails['availableLincence']);
				$detailsJson = json_encode ( $franchiseDetails );
				$redis->hmset ( 'H_Franchise', $fcode,$detailsJson);
				
			}
				$error='';
				 if($deviceidarray!=null)
				 {
					 $error=implode(" ",$deviceidarray);
					 $error='These Device Id are already exist  '.$error;
				 }
			
			 
			
			return Redirect::to ( 'Business' )->withErrors ( $error );
		
		
		
	}
	
	
	public function batchSale()
	{
		
	
		
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		log::info( '------batch Sale---------- ');
		$username = Auth::user()->username;
			$redis = Redis::connection();
			$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
			$deviceList      = Input::get('vehicleList');
			$deviceType      = Input::get('deviceType');
			$ownerShip      = Input::get('dealerId');
			$userId      = Input::get('userId');
			$mobileNo      = Input::get('mobileNo');
			$email      = Input::get('email');
			$password      = Input::get('password');
			$type      = Input::get('type');
			$type1      = Input::get('type1');
			log::info($ownerShip.'type ----------->'.$type);
			log::info($ownerShip.'valuse ----------->'.Input::get('userIdtemp'));
			if($type1=='existing')
			{
				$userId      = Input::get('userIdtemp');
				
				
			}
			
			
			if($type=='Sale' && Session::get('cur')!='dealer')
			{
				$ownerShip      = 'OWN';
			}
			if(Session::get('cur')=='dealer')
			{	
					$type='Sale';
					$ownerShip = $username;
					    $mobArr = explode(',', $mobileNo);
			}
			else if($type=='Sale' && $type1=='new')
			{
				
				 $rules = array (
				'userId' => 'required|alpha_dash',
				'email' => 'required|email',
				
				);             
                
				$validator = Validator::make ( Input::all (), $rules );
			   
				if ($validator->fails ()) {
					return Redirect::to ( 'Business' )->withErrors ( $validator );
				}else {
					  $val = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':fcode' );
					  $val1= $redis->sismember ( 'S_Users_' . $fcode, $userId );
				}
				
				if($val1==1 || isset($val)) {
					log::info('id already exist '.$userId);
					return Redirect::to ( 'Business' )->withErrors ( 'User Id already exist' );
				}


			    $mobArr = explode(',', $mobileNo);
				foreach($mobArr as $mob){
					$val1= $redis->sismember ( 'S_Users_' . $fcode, $userId );
					if($val1==1 ) {
                                        	log::info('id alreasy exist '.$mob);
                                        	return Redirect::to ( 'Business' )->withErrors ($mob . ' User Id already exist' );
                                	}
				}	


			}
			log::info('value type---->'.$type);
			$organizationId=$userId;
			$orgId=$organizationId;
			$groupId=$orgId;
			if($ownerShip=='OWN' && $type!='Sale')
			{
				$orgId='Default';
				
			}
			if($userId==null)
			{
				$orgId='Default';
				$organizationId='Default';
			}
												
			
			
			
			if($deviceList!=null)
			{
				$temp=0;
				
					foreach($deviceList as $device) {
					log::info( '------ownership---------- '.$ownerShip);
					$myArray = explode(',', $device);
					$vehicleId='gpsvts_'.substr($myArray[0], -5);
					$deviceId=$myArray[0];
					$deviceDataArr = array (
							'deviceid' => $deviceId,
							'deviceidtype' => $myArray[1],
						);
						$deviceDataJson = json_encode ( $deviceDataArr );
					$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
					$back=$redis->hget($vehicleDeviceMapId, $deviceId);
					if($back!=null)
					{
						$vehicleId=$back;
					}
					else{
							$v=idate("d") ;
							$paymentmonth=12;
							if($v>15)
							{
								log::info('inside if');
								$paymentmonth=$paymentmonth+1;		
							}
							for ($i = 1; $i <=$paymentmonth; $i++){

								$new_date = date('F Y', strtotime("+$i month"));
									$new_date2 = date('FY', strtotime("$i month"));
								}
								$new_date1 = date('F d Y', strtotime("+0 month"));
								
							
							
							$shortName='nill';
							$odoDistance=0;
							$refDataArr = array (
									'deviceId' => $myArray[0],					
									'deviceModel' => $myArray[1],
									'shortName' => $shortName,
									'regNo' => 'regNo',
									'orgId'=>$orgId,
									'vehicleType' => 'Truck',
									'oprName' => 'Airtel',
									'mobileNo' => '1234567890',
									'odoDistance' => $odoDistance,
									'gpsSimNo' => '1234567890',
									'date' =>$new_date1,
									'paymentType'=>'yearly',
									'expiredPeriod'=>$new_date,					
									'overSpeedLimit' => '50',					
									'driverName' => '',					
									'email' => '',
									'altShortName'=>'Default',
									'sendGeoFenceSMS' => 'no',
									'morningTripStartTime' => '',
									'eveningTripStartTime' => '',
									'parkingAlert' => 'no',
									'vehicleMake' => '',
								);
								$refDataJson = json_encode ( $refDataArr );
								
								log::info('json data --->'.$refDataJson);
								$expireData=$redis->hget ( 'H_Expire_' . $fcode, $new_date2);
				
								$redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );
								
								$cpyDeviceSet = 'S_Device_' . $fcode;
								
								$redis->sadd ( $cpyDeviceSet, $deviceId );
								$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
								$redis->hmset ( $vehicleDeviceMapId, $vehicleId , $deviceId, $deviceId, $vehicleId );
								
							   //this is for security check			
								$redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );
								
								$redis->hset('H_Device_Cpy_Map',$deviceId,$fcode);
								$redis->sadd('S_Vehicles_'.$orgId.'_'.$fcode , $vehicleId);
								if($expireData==null)
								{
									$redis->hset ( 'H_Expire_' . $fcode, $new_date2,$vehicleId);
								}else{
									 $redis->hset ( 'H_Expire_' . $fcode, $new_date2,$expireData.','.$vehicleId);
								}
								
								$time =microtime(true);
								$redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);
								$time = round($time * 1000);
								
								
								
								$tmpPositon =  '13.104870,80.303138,0,N,' . $time . ',0.0,N,P,ON,' .$odoDistance. ',S,N';
								$redis->hset ( 'H_ProData_' . $fcode, $vehicleId, $tmpPositon );
						}
								if($ownerShip!='OWN')
								{
									log::info( '------login 1---------- '.Session::get('cur'));
									$redis->sadd('S_Vehicles_Dealer_'.$ownerShip.'_'.$fcode,$vehicleId);
									$redis->srem('S_Vehicles_Admin_'.$fcode,$vehicleId);
								}
								else if($ownerShip=='OWN')
								{
									$redis->sadd('S_Vehicles_Admin_'.$fcode,$vehicleId);
									$redis->srem('S_Vehicles_Dealer_'.$ownerShip.'_'.$fcode,$vehicleId);
								}
								log::info( '------vehicle id---------- '.$vehicleId);
					
					
						
							
												
												$details=$redis->hget('H_Organisations_'.$fcode,$organizationId);
												Log::info($details.'before '.$ownerShip);
												if($type=='Sale')
												{
													if($details==null)
													{
														Log::info('new organistion going to create');
														$redis->sadd('S_Organisations_'. $fcode, $organizationId);			
														if($ownerShip!='OWN')
														{
															log::info( '------login 1---------- '.Session::get('cur'));
															$redis->sadd('S_Organisations_Dealer_'.$ownerShip.'_'.$fcode,$organizationId);
														}
														else if($ownerShip=='OWN')
														{
															$redis->sadd('S_Organisations_Admin_'.$fcode,$organizationId);
														}
														  $orgDataArr = array (
															'mobile' => '1234567890',
															'description' => '',
															'email' => '',
															'address' => '',
															'mobile' => '',
															'startTime' => '',
															'endTime'  => '',
															'atc' => '',
															'etc' =>'',
															'mtc' =>'',
															'parkingAlert'=>'',
															'idleAlert'=>'',
															'parkDuration'=>'',
															'idleDuration'=>'',
															'overspeedalert'=>'',
															'sendGeoFenceSMS'=>'',
															'radius'=>''
															);
															 $orgDataJson = json_encode ( $orgDataArr );
														$redis->hset('H_Organisations_'.$fcode,$organizationId,$orgDataJson );
														$redis->hset('H_Org_Company_Map',$organizationId,$fcode);
														
													}
												}
											if($type=='Sale')
											{
												log::info( '------sale-1--------- '.$ownerShip);
												$redis->sadd($groupId . ':' . $fcode,$vehicleId);
												
											}
											
											
												
												
							
							 
							if(Session::get('cur')=='dealer')
							{					
								$redis->srem('S_Pre_Onboard_Dealer_'.$username.'_'.$fcode,$deviceId);
								$redis->hdel('H_Pre_Onboard_Dealer_'.$username.'_'.$fcode,$deviceId);
							}
							else if(Session::get('cur')=='admin')
							{
								$redis->srem('S_Pre_Onboard_Admin_'.$fcode,$deviceId);
								$redis->hdel('H_Pre_Onboard_Admin_'.$fcode,$deviceId);
								if($ownerShip!='OWN')
								{
									$redis->sadd('S_Pre_Onboard_Dealer_'.$ownerShip.'_'.$fcode,$deviceId);
									$redis->hset('H_Pre_Onboard_Dealer_'.$ownerShip.'_'.$fcode,$deviceId,$deviceDataJson);
								}
								
							}								
							
							
							
					$temp++;
				}
				
					if($type=='Sale' )
					{
						log::info( '------sale-2--------- '.$ownerShip);
						
							$redis->sadd('S_Groups_' . $fcode, $groupId . ':' . $fcode);
							if($ownerShip!='OWN')
							{
								log::info( '------login 1---------- '.Session::get('cur'));
								$redis->sadd('S_Groups_Dealer_'.$ownerShip.'_'.$fcode,$groupId . ':' . $fcode);
								$redis->sadd('S_Users_Dealer_'.$ownerShip.'_'.$fcode,$userId);
							}
							else if($ownerShip=='OWN')
							{
								$redis->sadd('S_Groups_Admin_'.$fcode,$groupId . ':' . $fcode);
								$redis->sadd('S_Users_Admin_'.$fcode,$userId);
							}
						$redis->sadd ( $userId, $groupId . ':' . $fcode );
						$redis->sadd ( 'S_Users_' . $fcode, $userId );
					
						
						
						if($type1=='new')
						{
							log::info( '------sale--3-------- '.$ownerShip);
							$password=Input::get ( 'password' );
							if($password==null)
							{
								$password='awesome';
							}
							$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo', $mobileNo,$userId.':email',$email ,$userId.':password',$password);
							
							$user = new User;
							
							$user->name = $userId;
							$user->username=$userId;
							$user->email=$email;
							$user->mobileNo=$mobileNo;
							$user->password=Hash::make($password);
							$user->save();
							
							 foreach($mobArr as $mob){

						  if($ownerShip!='OWN')
                           {
                                   log::info( '------login 1---------- '.Session::get('cur'));
                                                                $redis->sadd('S_Users_Dealer_'.$ownerShip.'_'.$fcode,$mob);
                           }
						  else if($ownerShip=='OWN')
								{
										$redis->sadd('S_Users_Admin_'.$fcode,$mob);
								}

						  log::info(' mobile number saved successfully');
						  $redis->sadd ( $mob, $groupId . ':' . $fcode );
                          $redis->sadd ( 'S_Users_' . $fcode, $mob ); 
					    
						  $password=Input::get ( 'password' );
							if($password==null)
							{
									$password='awesome';
							}
							$redis->hmset ( 'H_UserId_Cust_Map', $mob . ':fcode', $fcode, $mob . ':mobileNo', $mobileNo,$mob.' :email',$email,$userId.':password',$password);
							$user = new User;

							$user->name = $mob;
							$user->username=$mob;
							$user->email=$email;
							$user->mobileNo=$mobileNo;
							$user->password=Hash::make($password);
							$user->save();
                         }
							
						}
                            
							

					     

					}
				
				
			}
			
 			return Redirect::to('Business');
	 		
	}
	
	 protected function schedule(Schedule $schedule)
    	{
        	$schedule->call(function () {
            //DB::table('recent_users')->delete();
        	})->everyMinute();
    	       }
	}

