<?php
use Carbon\Carbon;
class RemoveController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		log::info('inside the remove RemoveController');
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}

        $username = Auth::user ()->username;

        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
       
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
			$orgList=null;
			$orgList=array_add($orgList,'','select');
		$orgList=array_add($orgList,'Default','Default');
        foreach ( $tmpOrgList as $org ) {
                $orgList = array_add($orgList,$org,$org);
                
            }
       if(Session::get('cur')=='admin')
		{
			
			$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
			$franchiseDetails=json_decode($franDetails_json,true);
			if(isset($franchiseDetails['availableLincence'])==1)
				$availableLincence=$franchiseDetails['availableLincence'];
			else
				$availableLincence='0';
        
            $numberofdevice=0;$dealerId=null;$userList=null;
		return View::make ( 'vdm.business.parentRemoveDevice' )->with ( 'orgList', $orgList )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList );
		}
	
        
	}
	
	
		
	public function create() {
		log::info('------------inside the remove Controller create()-------------------------');
		DatabaseConfig::checkDb();
		if (! Auth::check ()) 
			{
			return Redirect::to ( 'login' );
		}
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        //get the Org list
        $tmpOrgList = $redis->smembers('S_Organisations_' . $fcode);
       
		
		//log::info( '------login 1---------- '.date('m'));
		if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				 $tmpOrgList = $redis->smembers('S_Organisations_Dealer_'.$username.'_'.$fcode);
			}
			else if(Session::get('cur')=='admin')
			{
				 $tmpOrgList = $redis->smembers('S_Organisations_Admin_'.$fcode);
			}
			$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
			$franchiseDetails=json_decode($franDetails_json,true);
			if(isset($franchiseDetails['availableLincence'])==1)
				$availableLincence=$franchiseDetails['availableLincence'];
			else
				$availableLincence='0';
        $orgList=null;
		$orgList=array_add($orgList,'Default','Default');
        foreach ( $tmpOrgList as $org ) {
                $orgList = array_add($orgList,$org,$org);
                
            }
$numberofdevice=0;$dealerId=null;$userList=null;
								return View::make ( 'vdm.business.parentRemoveDevice' )->with ( 'orgList', $orgList )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList );
            
	}
	public function checkDevice()
	{
		
log::info( 'ahan'.'-------- check device id::----------'.Input::get('id'));
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}

		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

		$deviceid = Input::get ( 'id');


		$dev=$redis->hget('H_Device_Cpy_Map',$deviceid);
		$vid=$redis->hget('H_Vehicle_Device_Map_'.$fcode,$deviceid);
		log::info('Vehicle ID--->'.$vid);
         $details=$redis->Hget('H_RefData_'.$fcode,$vid);
          //log::info($details);
                            
           //$did=$redis->hget('H_Vehicle_Device_Map_'.$fcode,$vid);
           //log::info('DeviceID-----'.$did);

           $details=json_decode($details,true);
                
                $owner = $details['OWN'];
                
		$error=' ';
		if($dev==null||$owner!='OWN')
		{
			$error='Device Id not already present in admin '.$deviceid;
		}
		$refDataArr = array (

			'error' => $error

			);
		$refDataJson = json_encode ( $refDataArr );

		log::info('changes value '.$error);            
		return Response::json($refDataArr);
	}
	
	public function checkvehicle()
	{
		log::info( '-------- check vehicle id::----------'.Input::get('id'));
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}

		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

		$vehicleId = Input::get ( 'id');
        $vehicleU = strtoupper($vehicleId);
       
		$vehicleIdCheck = $redis->sismember('S_Vehicles_' . $fcode, $vehicleId);
		$vehicleIdCheck2 = $redis->sismember('S_KeyVehicles', $vehicleU);
		
		$details=$redis->Hget('H_RefData_'.$fcode,$vehicleId);
                   
             
           $details=json_decode($details,true);
                               
				$owner = $details['OWN'];
                
		$error=' ';
		if(($vehicleIdCheck!=1 && $vehicleIdCheck2!=1)||($owner!='OWN')) 
		{
			$error='Vehicle not Id already present in admin '.$vehicleId;
		}
		
		$refDataArr = array (

			'error' => $error

			);
		$refDataJson = json_encode ( $refDataArr );

		log::info('changes value '.$vehicleIdCheck);
        log::info('changes value keyVehi '.$vehicleIdCheck2);		
		return Response::json($refDataArr);
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
		log::info( '-------- store in remove devices ::----------');
        $availableLincence=Input::get ( 'availableLincence' );
		if ($validator->fails ()) {
			return View::make ( 'vdm.business.parentRemoveDevice' )->withErrors ( $validator )->with ( 'orgList', null )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', null )->with ( 'dealerId', null )->with ( 'userList', null )->with('orgList',null);
		} 
		else{
			
			$numberofdevice = Input::get ( 'numberofdevice' );
			// store
			
			
			log::info( '-------- av license in  ::----------'.$availableLincence);
			if($numberofdevice>$availableLincence)
			{
				return View::make ( 'vdm.business.parentRemoveDevice' )->withErrors ( "Your license count is less" )->with ( 'orgList', null )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', null )->with ( 'dealerId', null )->with ( 'userList', null )->with('orgList',null);
			}
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
		$userList=array();
		$userList=BusinessController::getUser();
		$orgList=array();
		$orgList=BusinessController::getOrg();
		$protocol = VdmFranchiseController::getProtocal();
		$Payment_Mode1 =array();
		$Payment_Mode = DB::select('select type from Payment_Mode');
		//log::info( '-------- av  in  ::----------'.count($Payment_Mode));
		foreach($Payment_Mode as  $org1) {
      	$Payment_Mode1 = array_add($Payment_Mode1, $org1->type,$org1->type);
        }
		$Licence1 =array();
		$Licence = DB::select('select type from Licence');
		foreach($Licence as  $org) {
      	$Licence1 = array_add($Licence1, $org->type,$org->type);
        }
		
		 return View::make ( 'vdm.business.parentRemove' )->with ( 'orgList', $orgList )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList )->with('orgList',$orgList)->with('Licence',$Licence1)->with('Payment_Mode',$Payment_Mode1)->with('protocol', $protocol);
		}
		
		
	}
	public function removedevice()
	{
      log::info('--------inside the removecontrroller----removedevices-----');
       if (! Auth::check () ) 
       	{
         return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;

        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        $nod = Input::get('numberofdevice1');
        $availL = Input::get('availableLincence');
        $type = Input::get('type');
        if($type=='Device')
        {
        log::info('----inside Device ID------');
      	    for($i =1;$i<=$nod;$i++)
            {
            $did=Input::get('deviceid'.$i);
            $vid=$redis->hget('H_Vehicle_Device_Map_'.$fcode,$did);     
            $details=$redis->Hget('H_RefData_'.$fcode,$vid);
            $details=json_decode($details,true);
            $shortName = $details['shortName'];
            $shortName1=strtoupper($shortName); 
            $orgId = $details['orgId'];
            $orgId1 = strtoupper($orgId);
            $owner = $details['OWN'];
            $mon=isset($details['mobileNo'])?$details['mobileNo']:'';
			$gpsSimNo=isset($details['gpsSimNo'])?$details['gpsSimNo']:'';
        
             if($owner=='OWN')
                {
                $vname=$redis->hdel('H_VehicleName_Mobile_Org_'.$fcode,$vid.':'.$did.':'.$shortName1.':'.$orgId1.':'.$gpsSimNo);
                $v1name=$redis->hdel('H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode,$vid.':'.$did.':'.$shortName1.':'.$orgId1.':'.$gpsSimNo.':OWN');
                $rorg=$redis->srem('S_Organisations_Admin_'.$fcode,$shortName);
                ///ram noti
                $delVehi=$redis->del('S_'.$vid.'_'.$fcode);
                ///
                $prodata=$redis->hdel('H_ProData_' .$fcode, $vid);
                $hdiv=$redis->hdel('H_Device_Cpy_Map',$did);
                $adminVehi=$redis->srem('S_Vehicles_Admin_'.$fcode,$vid);
                $svid=$redis->srem('S_Vehicles_'.$fcode,$vid);
                $reData=$redis->hdel('H_RefData_'.$fcode,$vid);
                $sdvid=$redis->srem('S_Device_'.$fcode,$did);
                $svehi=$redis->srem('S_Vehicles_'.$fcode,$vid);
                $mapvehId=$redis->hdel('H_Vehicle_Device_Map_'.$fcode,$vid);
                $mapdivId=$redis->hdel('H_Vehicle_Device_Map_'.$fcode,$did);
                $groupList = $redis->smembers('S_Groups_' . $fcode) ;
     	        foreach($groupList as $key=>$group) 
		        	{
	                Log::info(' ---------inside---------------- '.$group);		
			        $vehicleList = $redis->smembers($group);
			        $avid=strtoupper($vid);
			        foreach ( $vehicleList as $value )
			        {            
					if($avid==$value)
	            		{  
	            		 
                            $vehicleg=$redis->srem($group, $value);
                            $mem=$redis->smembers($group);
                            $coun=$redis->scard($group);
                            if($coun==0)
                                {
            	                Log::info(' ---------empty group is also removed---------------- '.$group);
            	                $redisUserCacheId = 'S_Users_' . $fcode;
            	                $userList = $redis->smembers ( $redisUserCacheId);
            	                foreach ( $userList as $key => $value1 )
            	                    {   
            	                    $userGroups = $redis->smembers ( $value1);
            	                 	foreach ( $userGroups as $value2 )
									    {
									    $group1=strtoupper($group);
                                        $val=strtoupper($value2);
                                        if($group1==$val)
            	                 	    $u=$redis->srem ($value1,$value2);
              	                 		$count1=$redis->scard($value1);
                           			    if($count1==0)
                           				 	{
                           				 	$user=$redis->srem('S_Users_'.$fcode,$value1);
                           				 	
                           				 	$user1=$redis->srem('S_Users_Virtual_'.$fcode,$value1);
                           				 	
                           				 	$user2=$redis->srem('S_Users_Admin_'.$fcode,$value1);
                           				 	
                           				 	$user3=$redis->hdel('H_Notification_Map_User',$value1);
                           				 	
                           				 	$user4=$redis->hdel('H_UserId_Cust_Map',$value1.':fcode');

                           				 	log::info('user is removed');
                           				 	}


            	                 		}
            	                 	}

            	                }
            	                $rgroup=$redis->srem('S_Groups_Admin_'.$fcode,$group);
            	                $r1group=$redis->srem('S_Groups_'.$fcode,$group);
            	                 
            	                 Log::info(' ---------empty group is also removed---------------- '.$group);

                               }
                               else
                               {
                               	Log::info(' --------- group is not empty---------------- ');
                               }

	            				
	            				
	            			}

	            				
                    	}
                    
                	}
                	else
                	{
                		log::info('this is not admin');	  	
	  					return Redirect::to('Remove/create')->withErrors( "Device not removed" );
                	}

	    		}
			}
		
	    	else 
            {
      	    log::info('-----inside vehicle ID-----');
      	    for($i =1;$i<=$nod;$i++)
            {
            $vidid = Input::get('vehicleId'.$i);
            $vid=strtoupper($vidid);
            $did=$redis->hget('H_Vehicle_Device_Map_'.$fcode,$vid);
            $details=$redis->Hget('H_RefData_'.$fcode,$vid);        
            $details=json_decode($details,true);
            $shortName = $details['shortName'];
            $shortName1=strtoupper($shortName); 
            $orgId = $details['orgId'];
            $orgId1 = strtoupper($orgId);
            $owner = $details['OWN'];
            $mon=isset($details['mobileNo'])?$details['mobileNo']:'';  
            $gpsSimNo=isset($details['gpsSimNo'])?$details['gpsSimNo']:''; 			
                
            if($owner=='OWN')
                {
                $vname=$redis->hdel('H_VehicleName_Mobile_Org_'.$fcode,$vid.':'.$did.':'.$shortName1.':'.$orgId1.':'.$gpsSimNo);
				$v1name=$redis->hdel('H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode,$vid.':'.$did.':'.$shortName1.':'.$orgId1.':'.$gpsSimNo.':OWN');
                $rorg=$redis->srem('S_Organisations_Admin_'.$fcode,$shortName);
             
                $prodata=$redis->hdel('H_ProData_' .$fcode, $vid);
                $hdiv=$redis->hdel('H_Device_Cpy_Map',$did);
                $adminVehi=$redis->srem('S_Vehicles_Admin_'.$fcode,$vid);
                $svid=$redis->srem('S_Vehicles_'.$fcode,$vid);
                $reData=$redis->hdel('H_RefData_'.$fcode,$vid);
                $sdvid=$redis->srem('S_Device_'.$fcode,$did);
                $svehi=$redis->srem('S_Vehicles_'.$fcode,$vid);
                $mapvehId=$redis->hdel('H_Vehicle_Device_Map_'.$fcode,$vid);
                $mapdivId=$redis->hdel('H_Vehicle_Device_Map_'.$fcode,$did);
                $groupList = $redis->smembers('S_Groups_' . $fcode) ;
     	        foreach($groupList as $key=>$group) 
		        	{
	                Log::info(' ---------inside---------------- '.$group);		
			        $vehicleList = $redis->smembers($group);
			        $avid=strtoupper($vid);
			        foreach ( $vehicleList as $value )
			        {            
					if($avid==$value)
	            		{  
	            		 
                            $vehicleg=$redis->srem($group, $value);
                            $mem=$redis->smembers($group);
                            $coun=$redis->scard($group);
                            if($coun==0)
                                {
            	                Log::info(' ---------empty group is also removed---------------- '.$group);
            	                $redisUserCacheId = 'S_Users_' . $fcode;
            	                $userList = $redis->smembers ( $redisUserCacheId);
            	                foreach ( $userList as $key => $value1 )
            	                    {   
            	                    $userGroups = $redis->smembers ( $value1);
            	                 	foreach ( $userGroups as $value2 )
									    {
									    $group1=strtoupper($group);
                                        $val=strtoupper($value2);
                                        if($group1==$val)
            	                 	    $us=$redis->srem ($value1,$value2);
              	                 		$count1=$redis->scard($value1);
                           			    if($count1==0)
                           				 	{
                           				 	$user=$redis->srem('S_Users_'.$fcode,$value1);
                           				 	
                           				 	$user1=$redis->srem('S_Users_Virtual_'.$fcode,$value1);
                           				 	
                           				 	$user2=$redis->srem('S_Users_Admin_'.$fcode,$value1);
                           				 	
                           				 	$user3=$redis->hdel('H_Notification_Map_User',$value1);
                           				 	
                           				 	$user4=$redis->hdel('H_UserId_Cust_Map',$value1.':fcode');
                           				 	}


            	                 		}
            	                 	}

            	                }
            	                $rgroup=$redis->srem('S_Groups_Admin_'.$fcode,$group);
            	                $r1group=$redis->srem('S_Groups_'.$fcode,$group);
            	                 
            	                 Log::info(' ---------empty group is also removed---------------- '.$group);

                               }
                               else
                               {
                               	Log::info(' --------- group is not empty---------------- ');
                               }

	            				
	            				
	            			}

	            				
                    	}
                    
                	}
                	else
                	{
                		log::info('this is not admin');	  	
	  					return Redirect::to('Remove/create')->withErrors( "Device not removed" );

                	}

	    		}
			}
        
   

			log::info('inside count present');
			$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
			$franchiseDetails=json_decode($franDetails_json,true);
			$franchiseDetails['availableLincence']=$franchiseDetails['availableLincence']+$nod;
			log::info('inside count present'.$franchiseDetails['availableLincence']);
			$detailsJson = json_encode ( $franchiseDetails);
			//log::ingo('509----'.$detailsJson);
			$n=$redis->hmset ( 'H_Franchise', $fcode,$detailsJson);
			

    return View::make ( 'vdm.business.parentRemoveDevice' )->withErrors ( "Device removed successfully " )->with ( 'availableLincence', $franchiseDetails['availableLincence'])->with ( 'orgList', null )->with ( 'numberofdevice', null )->with ( 'dealerId', null )->with ( 'userList', null )->with('orgList',null);


   
}


}