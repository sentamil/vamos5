<?php
class VdmOrganizationController extends \BaseController {
    
  
    
    public function create() {
         if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        
        
        return View::make('vdm.organization.create');   
    }
	
	
	 public function placeOfInterest() 
	 {
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		
		$Places = $redis->zrange('S_Places_India',0,-1);
			
		
		$userplace=null;
		$shortName =null;
        $shortNameList = null;
        try{
			Log::info('-------------- $try-----------');
			if($Places!=null)
			{
				Log::info('-------------- $try11-----------');
				foreach ($Places as $key=>$value) {
			$userplace=array_add($userplace, $value, $value);
            $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $value );
            $vehicleRefData=json_decode($vehicleRefData,true);
             $shortName = $vehicleRefData['shortName']; 
            $shortNameList = array_add($shortNameList,$value,$shortName);
			}
			
			
			
            
		}
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
        foreach ( $tmpOrgList as $org ) {
                $orgList = array_add($orgList,$org,$org);
                
            }
		Log::info('-------------- $out-----------');
		}catch(\Exception $e)
	   {
		
	   }
	   
       return View::make('vdm.organization.placeOfInterest')->with('userplace',$userplace)->with ( 'orgList', $orgList ); 
    }
public function addpoi()
	{
		
	log::info( ' url :-----' );
		
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$rules = array(
				'orgId'       => 'required|alpha_dash',
				'radiusrange' => 'required',
				'poi' => 'required'
 		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('vdmOrganization/placeOfInterest')
			->withErrors($validator);
			
 		} else {
			// store
			log::info( ' url :------------');
			$orgId  = Input::get('orgId');
			$poi = Input::get('poi');
			$radiusrange =Input::get('radiusrange');
			$redis = Redis::connection();
			$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
			
			$jsonData =  $redis->hget('H_Organisations_'.$fcode,$orgId );
        
        $orgDataArr = json_decode ( $jsonData, true );
        
        $email =isset($orgDataArr['email'])?$orgDataArr['email']:' ';
        $address =isset($orgDataArr['address'])?$orgDataArr['address']:' ';
        $description =isset($orgDataArr['description'])?$orgDataArr['description']:' ';
        $mobile =isset($orgDataArr['mobile'])?$orgDataArr['mobile']:' ';
        $time1=isset($orgDataArr['startTime'])?$orgDataArr['startTime']:' ';
		$time2=isset($orgDataArr['endTime'])?$orgDataArr['endTime']:' ';
		$etc=isset($orgDataArr['etc'])?$orgDataArr['etc']:' ';
		$mtc=isset($orgDataArr['mtc'])?$orgDataArr['mtc']:' ';
		$atc=isset($orgDataArr['atc'])?$orgDataArr['atc']:' ';
		$parkDuration=isset($orgDataArr['parkDuration'])?$orgDataArr['parkDuration']:'';
		$idleDuration=isset($orgDataArr['idleDuration'])?$orgDataArr['idleDuration']:'';
		$parkingAlert=isset($orgDataArr['parkingAlert'])?$orgDataArr['parkingAlert']:'';
		$idleAlert=isset($orgDataArr['idleAlert'])?$orgDataArr['idleAlert']:'';
		$overspeedalert=isset($orgDataArr['overspeedalert'])?$orgDataArr['overspeedalert']:'';
		$sendGeoFenceSMS=isset($orgDataArr['sendGeoFenceSMS'])?$orgDataArr['sendGeoFenceSMS']:'';
		$radius=$radiusrange;
			 $orgDataArr = array (
                    
                    'mobile' => $mobile,
                    'email' => $email,
                    'address' => $address,
                    'description' => $description,
					'startTime' => $time1,
					'endTime'  => $time2,
					'atc' => $atc,
					'etc' =>$etc,
					'mtc' =>$mtc,
					'parkingAlert'=>$parkingAlert,
					'idleAlert'=>$idleAlert,
					'parkDuration'=>$parkDuration,
					'idleDuration'=>$idleDuration,
					'overspeedalert'=>$overspeedalert,
					'sendGeoFenceSMS'=>$sendGeoFenceSMS,
					'radius'=>$radiusrange
            );
            
            $orgDataJson = json_encode ( $orgDataArr );
			$redis->hset('H_Organisations_'.$fcode,$orgId ,$orgDataJson);
			
			
			$temp='S_Poi_'.$orgId.'_'.$fcode.':temp';
			$redis->del($temp);
			foreach($poi as $place) {
				$redis->sadd($temp,$place);
			}
			$ipaddress = $redis->get('ipaddress');
			 $url = 'http://' .$ipaddress . ':9000/getPoiPlace?key=' . $temp;
			$url=htmlspecialchars_decode($url);
	 
			log::info( ' url :' . $url);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$response = curl_exec($ch);
			log::info( ' response :' . $response);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
			curl_close($ch);
			
			}
			
 			Session::flash('message', 'Successfully created !');
 			return Redirect::to('vdmOrganization');
	 		}
		
		
	public function poiEdit($id)
	{
		
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		$orgId=$id;
		Log::info(' $orgId ' . $orgId);
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');

		$places = $redis->zrange('S_Places_India',0,-1);
		
		
		
		$selectedVehicles =  $redis->zrange('S_Poi_'.$id.'_'.$fcode,0,-1);
		      
        $shortNameList = null;
		$placeList=null;
		
		foreach($places as $key=>$value) {
			Log::info('-------------- $orgId in-----------'.$value);
		    
           
			$placeList=array_add($placeList, $value, $value);
		}
		
		$vehicleRefData = $redis->hget ( 'H_Organisations_' . $fcode, $id );
            $vehicleRefData=json_decode($vehicleRefData,true);
             $radiusrange = isset($vehicleRefData['radius'])?$vehicleRefData['radius']:'';
		
		Log::info('-------------- $orgId 2 -----------');
		return View::make('vdm.organization.poiEdit',array('orgId'=>$orgId))->with('userplace', $placeList)->
		with('selectedVehicles',$selectedVehicles)->with('radiusrange',$radiusrange);
	}
	
	
	
	
	
	public function poiDelete($id)
	{
		
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		$orgId=$id;
		Log::info(' $orgId ' . $orgId);
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');		
		
		 $redis->del('S_Poi_'.$id.'_'.$fcode);
		 $redis->del('S_Poi_'.$id.'_'.$fcode.':temp');
		
       return Redirect::to('vdmOrganization');
		
	}
	
	
	public function getSmsReport($id)
	{
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		$ipaddress = $redis->get('ipaddress');
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		
		$url = 'http://' .$ipaddress . ':9000/getSmsAuditOrg?fcode=' . $fcode . '&orgId=' . $id;
        $url=htmlspecialchars_decode($url);
 
		 log::info( ' url :' . $url);    
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $url);
    // Include header in result? (0 = yes, 1 = no)
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		  $response = curl_exec($ch);
         log::info( ' response :' . $response);
         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
         curl_close($ch);
         log::info( 'finished');
		 
        $report = json_decode($response,true);
          if($report==null)
		{
			 log::info( ' ---------inside null--------- :');
			return Redirect::to('vdmOrganization');
		}
		//$smsCount=$report['error'];
		//$value = $sugStop['suggestedStop'];
	     log::info( ' 1 :');
	     $address = array();
		  
		   $details = array();
		 $address=$report;
	     log::info( ' 2 :');
         foreach($address as $org => $rowId) {			
		$temp = array();
		 foreach($rowId as $org1 => $rowId2) {
			  
			if($org1!='year' && $org1!='month')
			{
				log::info($rowId2. ' final :'.$org1);
				$temp=array_add($temp, $org1,$rowId2);
			}				
			
		 }
		 $details=array_add($details, $org,$temp); 
		 log::info( ' final :'.$org);		
        }
		
		
       return View::make('vdm.organization.smsReport')->with('details',$details); 
	 		}
		
	
	public function poiView($id)
	{
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		
		$Places = $redis->zrange('S_Poi_'.$id.'_'.$fcode,0,-1);
			
		
		$userplace=null;
		$radius =null;
        $shortNameList = null;
        try{
			Log::info('-------------- $try-----------');
			if($Places!=null)
			{
				Log::info('-------------- $try11-----------');
				foreach ($Places as $key=>$value) {
			$userplace=array_add($userplace, $value, $value);
            
            
			}
			
			$vehicleRefData = $redis->hget ( 'H_Organisations_' . $fcode, $id );
            $vehicleRefData=json_decode($vehicleRefData,true);
             $radius = $vehicleRefData['radius']; 
			 Log::info('-------------- radius-----------'.$radius);
			
            
		}
		
		Log::info('-------------- $out-----------');
		}catch(\Exception $e)
	   {
		
	   }
	   
       return View::make('vdm.organization.poiView')->with('userplace',$userplace)->with ( 'radius', $radius )->with('orgId',$id); 
	 		}
		
	

    public function store()
    {
        
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $rules = array(
                'organizationId'       => 'required|alpha_dash',
                'mobile' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('vdmOrganization/create')
            ->withErrors($validator);
            
        } else {
            // store
             $time1= Input::get('time1');
		 $time2= Input::get('time2');
		 if(!$time1==null && $time2==null)
		 {
			   
			  $rules = array(
                'time2'       => 'required'
               
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('vdmOrganization/create')
            ->withErrors($validator);
            
        }
		 }
		 
		  if($time1==null && !$time2==null)
		 {
			  $rules = array(
                'time1'       => 'required'
               
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('vdmOrganization/create')
            ->withErrors($validator);
            
        }
		 }
            $organizationId       = Input::get('organizationId');
            $description      = Input::get('description');
            $email      = Input::get('email');
            $description      = Input::get('description');
            $address      = Input::get('address');
            $mobile      = Input::get('mobile');
			$atc= Input::get('atc');
			$etc=Input::get('etc');
			$mtc=Input::get('mtc');
			$parkingAlert=Input::get('parkingAlert');
			$idleAlert=Input::get('idleAlert');
			$parkDuration=Input::get('parkDuration');
			$idleDuration=Input::get('idleDuration');
			$overspeedalert=Input::get('overspeedalert');
			$startTime =$time1;
			$endTime=$time2;
			$sendGeoFenceSMS = Input::get ('sendGeoFenceSMS');
			$radius=0;
            $orgDataArr = array (
                    'description' => $description,
                    'email' => $email,
                    'address' => $address,
                    'mobile' => $mobile,
					'startTime' => $startTime,
					'endTime'  => $endTime,
					'atc' => $atc,
					'etc' =>$etc,
					'mtc' =>$mtc,
					'parkingAlert'=>$parkingAlert,
					'idleAlert'=>$idleAlert,
					'parkDuration'=>$parkDuration,
					'idleDuration'=>$idleDuration,
					'overspeedalert'=>$overspeedalert,
					'sendGeoFenceSMS'=>$sendGeoFenceSMS,
					'radius'=>$radius
					
            );
			 $redis = Redis::connection();
			 $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
          
			
			
			
            $orgDataJson = json_encode ( $orgDataArr );
         
            
           
			$companymap=$redis->hget('H_Org_Company_Map',$organizationId);
			if($companymap!=null)
			{
				Log::info('-------------------');
			 Session::flash ( 'message', 'Organistaion is already exist. Please choose another one' );
			 return Redirect::to('vdmOrganization/create');
			}
			
           
            $redis->sadd('S_Organisations_'. $fcode, $organizationId);
			
			if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				$redis->sadd('S_Organisations_Dealer_'.$username.'_'.$fcode,$organizationId);
			}
			else if(Session::get('cur')=='admin')
			{
				$redis->sadd('S_Organisations_Admin_'.$fcode,$organizationId);
			}
			
			  if($etc!=null)
			{
				$etccron = array (
                    'timeCron' => $etc,
                    'methodToCall' => 'getReadyForEveningSchoolTrips',
                    'fcode' => $fcode,
                    'orgId' => $organizationId,
					'className' => 'com.el.tasks.scheduled.ScheduledTasks'
            );
			
			$etcjson = json_encode ( $etccron );
			$redis->hset('H_Scheduler_'.$fcode,'getReadyForEveningSchoolTrips_'.$organizationId,$etcjson);
			
			}
			  if($mtc!=null)
			{
				$mtccron = array (
                    'timeCron' => $mtc,
                    'methodToCall' => 'getReadyForMorningSchoolTrips',
                    'fcode' => $fcode,
                    'orgId' => $organizationId,
					'className' => 'com.el.tasks.scheduled.ScheduledTasks'
            );
			
			$mtcjson = json_encode ( $mtccron );
			$redis->hset('H_Scheduler_'.$fcode,'getReadyForMorningSchoolTrips_'.$organizationId,$mtcjson);
			
			}
			if($atc!=null)
			{
				$atccron = array (
                    'timeCron' => $atc,
                    'methodToCall' => 'getReadyForAfternoonSchoolTrips',
                    'fcode' => $fcode,
                    'orgId' => $organizationId,
					'className' => 'com.el.tasks.scheduled.ScheduledTasks'
            );
			
			$atcjson = json_encode ( $atccron );
			$redis->hset('H_Scheduler_'.$fcode,'getReadyForAfternoonSchoolTrips_'.$organizationId,$atcjson);
			
			}
			
            
            $redis->hset('H_Organisations_'.$fcode,$organizationId,$orgDataJson );
			$redis->hset('H_Org_Company_Map',$organizationId,$fcode);
            
			
			
			$orgArray = array();
        
			for ($i = 0; $i < 10; $i++) {
				
			
            $poi= Input::get('poi'.$i);
			 log::info( 'poi ::' . $poi);
			 if(!$poi==null)
			 {
				 $orgArray = array_add($orgArray, $poi,$poi);
				 
				 $geocode=file_get_contents("http://maps.google.com/maps/api/geocode/json?address=".$poi);

				$output= json_decode($geocode);
				 if($output->status=="ZERO_RESULTS")
				 {
					 log::info( 'inside no result' );
					 $latandlan = '';
			
				 }
				 else{
					 $latandlan = $output->results[0]->geometry->location->lat.','.$output->results[0]->geometry->location->lng;
					
					 
				 }	
					 
				 $redis->hset('H_Poi_'.$organizationId .'_'.$fcode,$poi,$latandlan);		
			
			 }      
            
			}
		
		
         log::info( 'Time1 ::' . $time1);
		  log::info( 'Time2 ::' . $time2);
            Session::flash('message', 'Successfully created ' . $organizationId . '!');
            return Redirect::to('vdmOrganization');
        }
        
    }
    
     public function index() {
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        
        log::info( 'User name  ::' . $username);
        
        
        $redis = Redis::connection ();
        
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        
        Log::info('fcode=' . $fcode);
        
        $orgListId = 'S_Organisations_' . $fcode;
		
		if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				 $orgListId = 'S_Organisations_Dealer_'.$username.'_'.$fcode;
			}
			else if(Session::get('cur')=='admin')
			{
				 $orgListId = 'S_Organisations_Admin_'.$fcode;
			}
		
        
        Log::info('orgListId=' . $orgListId);

        $orgList = $redis->smembers ( $orgListId);
        
       
      //  Log::info(' $orgList ' . $orgList);
        
        $orgArray = array();
        
        foreach ( $orgList as $org ) {
            
            $orgArray = array_add($orgArray, $org,$org);
            //TODO --- more details obtained here
        }
        
         
        return View::make ( 'vdm.organization.index', array (
                'orgList' => $orgList 
        ) );
    }

    public function destroy($id)
    {
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $redis = Redis::connection();
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
        
        Log::info('--------------------s--------------------------------' . $id);
        $organizationId = $id;
        $redis->srem('S_Organisations_' . $fcode,$id);
		$redis->srem('S_Organisations_Dealer_'.$username.'_'.$fcode,$id);
		$redis->srem('S_Organisations_Admin_'.$fcode,$id);
		
		
		
        $redis->hdel('H_Organisations_'.$fcode,$id);//Orgnizations
        $redis->hdel('H_Org_Company_Map', $id);
        $userList = $redis->smembers('S_Users_' . $fcode);
        
		
		
		
		$vehicleSet=$redis->smembers('S_Vehicles_'.$id.'_'.$fcode);
		foreach ( $vehicleSet as $list ) {
                Log::info('--------------------s--------------------------------' . $list);
				$refData=$redis->hget('H_RefData_'.$fcode, $list);
				
				$refData=json_decode($refData,true);		
				
				$redis->del('L_Suggest_'.$refData['shortName'].'_'.$refData['orgId'].'_'.$fcode);
				
				$redis->hdel('H_Stopseq_'.$refData['orgId'].'_'.$fcode , $refData['shortName'].':'.'morning');
				$redis->hdel('H_Stopseq_'.$refData['orgId'].'_'.$fcode , $refData['shortName'].':'.'evening');
				
				try{
					
				Log::info('--------------------org--------------------------------' . $list);
				$redis->del('L_Suggest_'.$refData['altShortName'].'_'.$refData['orgId'].'_'.$fcode);
				
				$redis->hdel('H_Stopseq_'.$refData['orgId'].'_'.$fcode , $refData['altShortName'].':'.'morning');
				$redis->hdel('H_Stopseq_'.$refData['orgId'].'_'.$fcode , $refData['altShortName'].':'.'evening');
				}
				catch(\Exception $e)
				{
					
				}
				try{
					$vehiclemake=$refData['vehicleMake'];
				}
				catch(\Exception $e)
				{
					$vehiclemake=' ';
				}
				
				$refDataArr = array (
					'deviceId' => $refData['deviceId'],
					'shortName' => $refData['shortName'],
					'deviceModel' => $refData['deviceModel'],
					'regNo' => $refData['regNo'],
					
					'vehicleType' => $refData['vehicleType'],
					'oprName' => $refData['oprName'],
					'mobileNo' => $refData['mobileNo'],
					'overSpeedLimit' => $refData['overSpeedLimit'],
					'odoDistance' => $refData['odoDistance'],
					'driverName' => $refData['driverName'],
					'gpsSimNo' => $refData['gpsSimNo'],
					'email' => $refData['email'],
					'orgId' =>'Default',
					'altShortName'=>'Default',
					'sendGeoFenceSMS' => $refData['sendGeoFenceSMS'],
					'morningTripStartTime' => $refData['morningTripStartTime'],
					'eveningTripStartTime' => $refData['eveningTripStartTime'],
					'parkingAlert' => $refData['parkingAlert'],
					'vehicleMake' => $vehiclemake,
			);
			
			$refDataJson = json_encode ( $refDataArr );
			$redis->hset ( 'H_RefData_' . $fcode, $list, $refDataJson );
        }
		
		
        foreach ( $userList as $user ) {
            //S_Orgs_prasanna
            //TODO while creating users -- create a set S_Orgs_<username> - add the 
            //organization he is attached to
           $redis->srem('S_Orgs_' . $user . '_' . $fcode,$id);  
        }
         $redis->del('H_Poi_'.$organizationId .'_'.$fcode);  
		$redis->del('H_Bus_Stops_'.$organizationId.'_'.$fcode);
		
        Session::flash('message', 'Successfully deleted ' . $id . '!');
        return Redirect::to('vdmOrganization');
    }
    
    
    public function edit($id)
    {
                if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        
     
        
        $redis = Redis::connection();
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
        $jsonData =  $redis->hget('H_Organisations_'.$fcode,$id );
        
        $orgDataArr = json_decode ( $jsonData, true );
        
        $email =isset($orgDataArr['email'])?$orgDataArr['email']:' ';
        $address =isset($orgDataArr['address'])?$orgDataArr['address']:' ';
        $description =isset($orgDataArr['description'])?$orgDataArr['description']:' ';
        $mobile =isset($orgDataArr['mobile'])?$orgDataArr['mobile']:' ';
        $time1=isset($orgDataArr['startTime'])?$orgDataArr['startTime']:' ';
		$time2=isset($orgDataArr['endTime'])?$orgDataArr['endTime']:' ';
		$etc=isset($orgDataArr['etc'])?$orgDataArr['etc']:' ';
		$mtc=isset($orgDataArr['mtc'])?$orgDataArr['mtc']:' ';
		$atc=isset($orgDataArr['atc'])?$orgDataArr['atc']:' ';
		$parkDuration=isset($orgDataArr['parkDuration'])?$orgDataArr['parkDuration']:'';
		$idleDuration=isset($orgDataArr['idleDuration'])?$orgDataArr['idleDuration']:'';
		$parkingAlert=isset($orgDataArr['parkingAlert'])?$orgDataArr['parkingAlert']:'';
		$idleAlert=isset($orgDataArr['idleAlert'])?$orgDataArr['idleAlert']:'';
		$overspeedalert=isset($orgDataArr['overspeedalert'])?$orgDataArr['overspeedalert']:'';
		$sendGeoFenceSMS=isset($orgDataArr['sendGeoFenceSMS'])?$orgDataArr['sendGeoFenceSMS']:'';
		$radius=isset($orgDataArr['radius'])?$orgDataArr['radius']:'';
		
        log::info( 'time1 ::' . $time1);
         log::info( 'time2 ::' . $time2);
		$address1=array();
		 $place=array();
		 $place1=array();
		  $latandlan=array();
		$address1= $redis->hgetall('H_Poi_'.$id.'_'.$fcode);
		
		$temp=null;
		foreach($address1 as $org => $rowId)
	{
			 
			  log::info( 'inside no result' .count($address1));
			 
				 $place = array_add($address1, $org,$org);
					$latandlan = array_add($address1, $rowId,$rowId);
					
				
					
			 
		 }
		 
		
		 
		$i=0;
		$j=0;$k=0;$m=0;
        return View::make('vdm.organization.edit')->with('mobile',$mobile)->with('description',$description)->with('address',$address)->
        with('organizationId',$id)->with('email',$email)->with('place',$place)->with('i',$i)->with('j',$j)->with('k',$k)->with('m',$m)->with('time1',$time1)->with('time2',$time2)->with('atc',$atc)->with('etc',$etc)->with('mtc',$mtc)->with('idleAlert',$idleAlert)->with('parkingAlert',$parkingAlert)->with('idleDuration',$idleDuration)->with('parkDuration',$parkDuration)->with('overspeedalert',$overspeedalert)->with('sendGeoFenceSMS',$sendGeoFenceSMS)->with('radius',$radius);   
        
    }
    
    public function update($id)
    {
        
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        
		 log::info( '---------------inside------------- ::' . $id);
		
        //no rules as of now
        $rules=array();
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('vdmSchools/create')
            ->withErrors($validator);
            
        } else {
            // store
			
			 $time1= Input::get('time1');
		 $time2= Input::get('time2');
		 if(!$time1==null && $time2==null)
		 {
			  $rules = array(
                'time2'       => 'required'
               
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('vdmOrganization/create')
            ->withErrors($validator);
            
        }
		 }
		 
		  if($time1==null && !$time2==null)
		 {
			  $rules = array(
                'time1'       => 'required'
               
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('vdmOrganization/create')
            ->withErrors($validator);
            
        }
		 }
		  $organizationId = $id;
		  $redis = Redis::connection();
            $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
            $redis->sadd('S_Organisations_'. $fcode, $organizationId);
			$orgDataJson1=$redis->hget('H_Organisations_'.$fcode,$organizationId );
			$orgDataJson1 = json_decode ( $orgDataJson1, true );
        
        $radius =isset($orgDataJson1['radius'])?$orgDataJson1['radius']:'';
            $mobile      = Input::get('mobile');
            $email      = Input::get('email');
            $description      = Input::get('description');
            $address      = Input::get('address');
           
			$atc= Input::get('atc');
			$etc=Input::get('etc');
			$mtc=Input::get('mtc');
			$parkingAlert=Input::get('parkingAlert');
			$idleAlert=Input::get('idleAlert');
			$parkDuration=Input::get('parkDuration');
			$idleDuration=Input::get('idleDuration');
			$overspeedalert=Input::get('overspeedalert');
			$sendGeoFenceSMS=Input::get('sendGeoFenceSMS');
					$startTime =$time1;
			$endTime=$time2;
            $orgDataArr = array (
                    
                    'mobile' => $mobile,
                    'email' => $email,
                    'address' => $address,
                    'description' => $description,
					'startTime' => $startTime,
					'endTime'  => $endTime,
					'atc' => $atc,
					'etc' =>$etc,
					'mtc' =>$mtc,
					'parkingAlert'=>$parkingAlert,
					'idleAlert'=>$idleAlert,
					'parkDuration'=>$parkDuration,
					'idleDuration'=>$idleDuration,
					'overspeedalert'=>$overspeedalert,
					'sendGeoFenceSMS'=>$sendGeoFenceSMS,
					'radius'=>$radius
            );
            
            $orgDataJson = json_encode ( $orgDataArr );
         
            
           
            
            $redis->hset('H_Organisations_'.$fcode,$organizationId,$orgDataJson );
            
            $routesArr = explode(",",$mobile);
            
			  if($etc!=null)
			{
				$etccron = array (
                    'timeCron' => $etc,
                    'methodToCall' => 'getReadyForEveningSchoolTrips',
                    'fcode' => $fcode,
                    'orgId' => $organizationId,
					'className' => 'com.el.tasks.scheduled.ScheduledTasks'
            );
			
			$etcjson = json_encode ( $etccron );
			$redis->hset('H_Scheduler_'.$fcode,'getReadyForEveningSchoolTrips_'.$organizationId,$etcjson);
			
			}
			  if($mtc!=null)
			{
				$mtccron = array (
                    'timeCron' => $mtc,
                    'methodToCall' => 'getReadyForMorningSchoolTrips',
                    'fcode' => $fcode,
                    'orgId' => $organizationId,
					'className' => 'com.el.tasks.scheduled.ScheduledTasks'
            );
			
			$mtcjson = json_encode ( $mtccron );
			$redis->hset('H_Scheduler_'.$fcode,'getReadyForMorningSchoolTrips_'.$organizationId,$mtcjson);
			
			}
			if($atc!=null)
			{
				$atccron = array (
                    'timeCron' => $atc,
                    'methodToCall' => 'getReadyForAfternoonSchoolTrips',
                    'fcode' => $fcode,
                    'orgId' => $organizationId,
					'className' => 'com.el.tasks.scheduled.ScheduledTasks'
            );
			
			$atcjson = json_encode ( $atccron );
			$redis->hset('H_Scheduler_'.$fcode,'getReadyForAfternoonSchoolTrips_'.$organizationId,$atcjson);
			
			}
           


for ($i = 0; $i < 10; $i++) {
	//$latandlan      = Input::get('place'.$i);
	$place      = Input::get('latandlan'.$i);
	$oldplace      = Input::get('oldlatandlan'.$i);
	//log::info( '---------------latand lan------------- ::' . $latandlan);
	log::info( '---------------oldplace------------- ::' . $oldplace);
	
	if(!$place==null && !is_numeric($place))
	{
		log::info( '---------------place------------- ::' . $place);
		
		 $geocode=file_get_contents("http://maps.google.com/maps/api/geocode/json?address=".$place);

				$output= json_decode($geocode);
				 if($output->status=="ZERO_RESULTS")
				 {
					 log::info( 'inside no result' );
					 $latandlan1 = '';
			
				 }
				 else{
					 $latandlan1 = $output->results[0]->geometry->location->lat.','.$output->results[0]->geometry->location->lng;
					
				 }	
					$redis->hdel('H_Poi_'.$organizationId .'_'.$fcode,$oldplace); 
					log::info( '---------------latandlan1------------- ::' . $latandlan1);
				 $redis->hset('H_Poi_'.$organizationId .'_'.$fcode,$place,$latandlan1);
	}
}
            // redirect
            Session::flash('message', 'Successfully created ' . $organizationId . '!');
            return Redirect::to('vdmOrganization');
        }
        
    }   
           
}