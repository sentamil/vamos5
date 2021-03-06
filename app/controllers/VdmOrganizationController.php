<?php
class VdmOrganizationController extends \BaseController {
    
  
    
    public function create() {
         if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        
        
        return View::make('vdm.organization.create')->with('smsP',VdmFranchiseController::smsP());   
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
			
		
		$userplace=array();
		$shortName =null;
        $shortNameList = null;
        try{
			Log::info('-------------- $try-----------');
			if($Places!=null)
			{
				$t=0;
				foreach ($Places as $key=>$value) {
					
					$userplace[$t]=$value;
					$t++;
					Log::info($t.'-------------- $try11-----------'.$value);
			//$userplace=array_add($userplace, $value, $value);
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
				'orgId'       => 'required',
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
			Log::info($poi);
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
		$smsSender=isset($orgDataArr['smsSender'])?$orgDataArr['smsSender']:'';
		$sosAlert=isset($orgDataArr['sosAlert'])?$orgDataArr['sosAlert']:'';
		$live=isset($orgDataArr['live'])?$orgDataArr['live']:'';
		$smsProvider=isset($orgDataArr['smsProvider'])?$orgDataArr['smsProvider']:'';
		$providerUserName=isset($orgDataArr['providerUserName'])?$orgDataArr['providerUserName']:'';
		$providerPassword=isset($orgDataArr['providerPassword'])?$orgDataArr['providerPassword']:'';
		$deleteHistoryEod=isset($orgDataArr['deleteHistoryEod'])?$orgDataArr['deleteHistoryEod']:'no';
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
					'radius'=>$radiusrange,
					'smsSender'=>$smsSender,
					'sosAlert'=>$sosAlert,
					'live'=>$live,
					'smsProvider'=>$smsProvider,
					'providerUserName'=>$providerUserName,
					'providerPassword'=>$providerPassword,
					'deleteHistoryEod'=>$deleteHistoryEod,
            );
            
            $orgDataJson = json_encode ( $orgDataArr );
			$redis->hset('H_Organisations_'.$fcode,$orgId ,$orgDataJson);
			
			
			$temp='S_Poi_'.$orgId.'_'.$fcode.':temp';
			$redis->del($temp);
			foreach($poi as $place) {
				$redis->sadd($temp,$place);
				Log::info($place);
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
 			return Redirect::to('vdmOrganization/adhi'.$orgId);
	 		}



	public function updateNotification()
	{	
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		$orgId =Input::get('orgId');
		$emailList =Input::get('emailList');
		$smsList =Input::get('smsList');
		if(count($emailList)>0)
		{
			$email=implode(",",$emailList);
			$redis->hset("H_EMAIL_".$fcode,$orgId,$email);
		}
		else
		{
			$redis->hdel("H_EMAIL_".$fcode,$orgId);
		}
		if(count($smsList)>0)
		{
			$sms=implode(",",$smsList);
			$redis->hset("H_SMS_".$fcode,$orgId,$sms);
		}
		else
		{
			$redis->hdel("H_SMS_".$fcode,$orgId);
		}
		Log::info('orgid is -->'.$orgId);
		Session::flash('message', 'Successfully created !');
		return Redirect::to('vdmOrganization/adhi'.$orgId);
	 }
	
public function siteUpdate()
	{	
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		$orgId =Input::get('orgId');
		$emailList =Input::get('emailList');
		$smsList =Input::get('smsList');
		$timeList =Input::get('time');
		$enableList =Input::get('enable');
		$alertList =Input::get('key');
		$siteName =Input::get('value');
		$alertList=$redis->hgetall('H_Site_'.$orgId.'_'.$fcode);
            foreach($alertList as $keyS => $value)
        { 
         if($keyS%2 != 0)
         continue; //The key is odd, skip
         //do your stuff
        $siteList[] = $keyS;
        }
	  $dd=[];
      $siteList1=isset($siteList)?$siteList:$dd;
      $rr=count($siteList1);
		for ($i=0;$i<$rr;$i++)
		{
			$siteName=$siteList[$i];
			$timeS=$timeList[$i];
			$enableS=$enableList[$i];
    log::info($siteName);
	log::info('////////////////'.$timeS);
	log::info($enableS);
			$refDataJson1=$redis->hget ('H_Site_'.$orgId.'_'.$fcode, $siteName);
		 log::info($refDataJson1);
        $refDataJson1=json_decode($refDataJson1,true);

        $refDataArr = array (
            'siteName' => isset($refDataJson1['siteName'])?$refDataJson1['siteName']:$siteName,
            'siteType' => isset($refDataJson1['siteType'])?$refDataJson1['siteType']:' ',
            'userId' => isset($refDataJson1['userId'])?$refDataJson1['userId']:'XXXXX',
            'latLng' => isset($refDataJson1['latLng'])?$refDataJson1['latLng']:' ',
            'OrgId' =>  isset($refDataJson1['OrgId'])?$refDataJson1['OrgId']:'',
            'time' => $timeS,
            'enable' => $enableS,
            );

         $refDataJson = json_encode ( $refDataArr );
    $redis->hdel ( 'H_Site_'.$orgId.'_'.$fcode, $siteName );
    $redis->hset ( 'H_Site_'.$orgId.'_'.$fcode, $siteName, $refDataJson );
		}
		Log::info('orgid is -->'.$orgId);
		Session::flash('message', 'Successfully created !');
		return Redirect::to('vdmOrganization/adhi'.$orgId);
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
		$placeList=array();
		$t=0;
		foreach($places as $key=>$value) {
			Log::info('-------------- $orgId in-----------'.$value);
		    
           $placeList[$t]=$value;
           $t++;
			//$placeList=array_add($placeList, $value, $value);
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
		
	
	public function pView($id)
	{
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		Log::info($id.'-----'.$fcode);
		$poiViewValue = $redis->zrange('S_Poi_'.$id.'_'.$fcode,0,-1);
		Log::info('-----count-----'.count($poiViewValue));
		
		$userplace=array();
		$radius =null;
        $shortNameList = null;
        try{

			// Log::info('-------------- $try------1-----'.$Places);
			if($poiViewValue!=null)
			{
				Log::info('-------------- $try11-----------');
				$t=0;
				foreach ($poiViewValue as $key=>$value) {
					$userplace[$t]=$value;
					$t++;
			//$userplace=array_add($userplace, $value, $value);
            
            
			}
			
			$vehicleRefData = $redis->hget ( 'H_Organisations_' . $fcode, $id );
            $vehicleRefData=json_decode($vehicleRefData,true);
             $radius = $vehicleRefData['radius']; 
			 Log::info('-------------- radius-----------'.$radius);
			
            
		}
		
		Log::info('-------------- $out-----------');
		}catch(\Exception $e)
	   {	
		 Log::info('-------------- radius-----------'.$e);
	   }
	   
       return View::make('vdm.organization.poiView')->with('userplace',$userplace)->with ( 'radius', $radius )->with('orgId',$id); 
	 		}
		
	


	public function editAlerts($id)
	{
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		
		$alertList=array();
		$smsArray=array();
		$emailArray=array();
		
        try{

			$alertList=$redis->smembers("S_VAMOS_SMS");
			$email=$redis->hget("H_EMAIL_".$fcode,$id);
			$sms=$redis->hget("H_SMS_".$fcode,$id);
			if($email!==null)
			{
				$emailArray=explode(",",$email);
			}
			
			if($sms!==null)
			{
				$smsArray=explode(",",$sms);
			}



		
		Log::info('-------------- $out-----------');
		}catch(\Exception $e)
	   {	
		 Log::info('-------------- radius-----------'.$e);
	   }
	   
	   
       return View::make('vdm.organization.editAlerts')->with('orgId',$id)->with('alertList',$alertList)->with('tmp',0)->with('smsArray',$smsArray)->with('emailArray',$emailArray); 
	 		}

	public function siteNotification($id)
	{
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		$time = array('' => '', '10' => '10 minutes', '20' => '20 minutes', '30' => '30 minutes', '40' => '40 minutes', '50' => '50 minutes', '60' => '60 minutes', '70' => '70 minutes', '80' => '80 minutes', '90' => '90 minutes', '100' => '100 minutes'); 
		$enable = array('N' => 'NO', 'Y' => 'YES');
		log::info($time);
		$alertList=array();
		$smsArray=array();
		$emailArray=array();
		$alertList=$redis->hgetall('H_Site_'.$id.'_'.$fcode);
         foreach($alertList as $key => $value)
          { 
          if($key%2 != 0)
            continue; //The key is odd, skip
             //do your stuff
           $siteList[] = $key;
         foreach($siteList as $keys => $values)
          $site=$redis->hget('H_Site_'.$id.'_'.$fcode, $values);
          $refDataJson1=json_decode($site,true);
          $time1=isset($refDataJson1['time'])?$refDataJson1['time']:'';
          $enable1=isset($refDataJson1['enable'])?$refDataJson1['enable']:'';
          $times[]=$time1;
          $enables[]=$enable1;
         }
         $ee=[]; 
         $siteList =isset($siteList) ? $siteList :$ee;
         $counts=count($siteList);	
         $times =isset($times) ? $times :$ee;
		 $enables =isset($enables) ? $enables :$ee;
       
   return View::make('vdm.organization.siteNotification')->with('orgId',$id)->with('times', $times)->with('enables', $enables)->with('counts',$counts)->with('alertList',$siteList)->with('tmp',0)->with('smsArray',$smsArray)->with('emailArray',$emailArray)->with('time',$time)->with('enable',$enable); 
	 		}

    public function store()
    {
        
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $rules = array(
                'organizationId'=> 'required',
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
            $organizationId = Input::get('organizationId');
            $organizationId=str_replace(' ', '_', $organizationId);
             $organizationId=str_replace('.', '_', $organizationId);
            $description      = Input::get('description');
            $email      = Input::get('email');
            $description      = Input::get('description');
            $address      = Input::get('address');
            $live=Input::get('live');
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
			$sosAlert = Input::get ('sosAlert');
			$radius=0;
			$smsSender=Input::get ('smsSender');
			$smsProvider=Input::get ('smsProvider');
			$providerUserName=Input::get ('providerUserName');
			$providerPassword=Input::get ('providerPassword');
			$geofense=Input::get('geofence');
			$safemove=Input::get('safemove');
			$deleteHistoryEod=Input::get('deleteHistoryEod');
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
					'radius'=>$radius,
					'smsSender'=>$smsSender,
					'sosAlert'=>$sosAlert,
					'live'=>$live,
					'smsProvider'=>$smsProvider,
					'providerUserName'=>$providerUserName,
					'providerPassword'=>$providerPassword,
					'geofense'=>$geofense,
					'safemove'=>$safemove,
					'deleteHistoryEod'=>$deleteHistoryEod,
					
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
            return Redirect::to('vdmOrganization/adhi'.$organizationId);
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
        $i=1;
        foreach ( $orgList as $org ) {
             $org1=strtoupper($org);	
			if($org==$org1)
        	{ 
            $orgArray = array_add($orgArray, $i,$org);
            //TODO --- more details obtained here
			}
			$i=$i+1;
        }
        
         
        return View::make ( 'vdm.organization.index', array (
                'orgList' => $orgArray 
        ) );
    }
    
	public function Search()
    {
        log::info(' advance search function for organization');
        $orgLis = [];
            return View::make('vdm.organization.orgScan')->with('orgList', $orgLis);
    }
	
	//advance search organization
	public function Scan() {

        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        log::info( 'User name  ::' . $username);
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		Log::info('fcode=' . $fcode);
        $orgListId = 'S_Organisations_' . $fcode;
		$text_word = strtoupper(Input::get('text_word'));
        if(Session::get('cur')=='dealer')
        {
            $orgListId = 'S_Organisations_Dealer_'.$username.'_'.$fcode;
        }
        else if(Session::get('cur')=='admin')
        {
            $orgListId = 'S_Organisations_Admin_'.$fcode;
        }
        $orgLis = $redis->smembers($orgListId);
		$cou = $redis->SCARD($orgListId);
        $orgLi = $redis->sScan( $orgListId, 0,  'count', $cou, 'match', '*'.$text_word.'*' ); 
		log::info(' organization advance search function end');
		return View::make ( 'vdm.organization.orgScan', array ('orgList' => $orgLi[1] ));
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
		$redis->hdel("H_EMAIL_".$fcode,$id);
		$redis->hdel("H_SMS_".$fcode,$id);
		
		
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
					'fcode'=>$refData['fcode'],
					'OWN'=>$refData['OWN'],
					'onboardDate'=>$refData['onboardDate'],
					'paymentType'=>$refData['paymentType'],
					'expiredPeriod'=>$refData['expiredPeriod'],
					'sendGeoFenceSMS' => $refData['sendGeoFenceSMS'],
					'morningTripStartTime' => $refData['morningTripStartTime'],
					'eveningTripStartTime' => $refData['eveningTripStartTime'],
					'parkingAlert' => $refData['parkingAlert'],
					'vehicleMake' => $vehiclemake,
			);
			
			$refDataJson = json_encode ( $refDataArr );
			$redis->hset ( 'H_RefData_' . $fcode, $list, $refDataJson );
			$deviceId1=$refData['deviceId'];
            $deviceId=strtoupper($deviceId1);
			$shortNameOld1=$refData['shortName'];
			$shortNameOld=strtoupper($shortNameOld1);
			$orgIdOld1=$refData['orgId'];
            $orgIdOld=strtoupper($orgIdOld1);
			$gpsSimNoOld=$refData['gpsSimNo'];
			$orgIdNew='DEFAULT';
            $OWN1=$refData['OWN'];
  			$OWN=strtoupper($OWN1);
			$redis->hdel('H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode, $list.':'.$deviceId.':'.$shortNameOld.':'.$orgIdOld.':'.$gpsSimNoOld.':'.$OWN);
			$redis->hset('H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode, $list.':'.$deviceId.':'.$shortNameOld.':'.$orgIdNew.':'.$gpsSimNoOld.':'.$OWN,$list);
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
		$smsSender=isset($orgDataArr['smsSender'])?$orgDataArr['smsSender']:'';
		$sosAlert=isset($orgDataArr['sosAlert'])?$orgDataArr['sosAlert']:'';
		$live=isset($orgDataArr['live'])?$orgDataArr['live']:'no';
		$smsProvider=isset($orgDataArr['smsProvider'])?$orgDataArr['smsProvider']:'nill';
		$providerUserName=isset($orgDataArr['providerUserName'])?$orgDataArr['providerUserName']:'';
		$providerPassword=isset($orgDataArr['providerPassword'])?$orgDataArr['providerPassword']:'';
		$geofence=isset($orgDataArr['geofence'])?$orgDataArr['geofence']:'nill';
		$safemove=isset($orgDataArr['safemove'])?$orgDataArr['safemove']:'no';
		$smsPattern=isset($orgDataArr['schoolPattern'])?$orgDataArr['schoolPattern']:'nill';
		$deleteHistoryEod=isset($orgDataArr['deleteHistoryEod'])?$orgDataArr['deleteHistoryEod']:'no';
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
			 
			$place = array_add($address1, $org,$org);
			$latandlan = array_add($address1, $rowId,$rowId);
			 
		}
		 
		$i=0;
		$j=0;$k=0;$m=0;
        return View::make('vdm.organization.edit')->with('mobile',$mobile)->with('description',$description)->with('address',$address)->
        with('organizationId',$id)->with('email',$email)->with('place',$place)->with('i',$i)->with('j',$j)->with('k',$k)->with('m',$m)->with('time1',$time1)->with('time2',$time2)->with('atc',$atc)->with('etc',$etc)->with('mtc',$mtc)->with('idleAlert',$idleAlert)->with('parkingAlert',$parkingAlert)->with('idleDuration',$idleDuration)->with('parkDuration',$parkDuration)->with('overspeedalert',$overspeedalert)->with('sendGeoFenceSMS',$sendGeoFenceSMS)->with('radius',$radius)->with('smsSender',$smsSender)->with('sosAlert',$sosAlert)->with('live',$live)->with('smsProvider',$smsProvider)->with('providerUserName',$providerUserName)->with('providerPassword',$providerPassword)->with('smsP',VdmFranchiseController::smsP())->with('smsPattern',$smsPattern)->with('geofence',$geofence)->with('safemove',$safemove)->with('deleteHistoryEod',$deleteHistoryEod);   
        
    }
    
    public function update($id)
    {
        log::info(' update function lets watch ');
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        
		 log::info( '---------------inside------------- ::' . $id);
		Log::info(' sendGeoFenceSMS---> '.Input::get('sendGeoFenceSMS'));
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
			$oldOrgData = $orgDataJson1;
			$orgDataJson1 = json_decode ( $orgDataJson1, true );
        
        $radius =isset($orgDataJson1['radius'])?$orgDataJson1['radius']:'';
	
        $mobileOld =isset($orgDataJson1['mobile'])?$orgDataJson1['mobile']:' ';
        $emailOld =isset($orgDataJson1['email'])?$orgDataJson1['email']:' ';
        $addressOld =isset($orgDataJson1['address'])?$orgDataJson1['address']:' ';
        $descriptionOld =isset($orgDataJson1['description'])?$orgDataJson1['description']:' ';      
        $startTimeOld=isset($orgDataJson1['startTime'])?$orgDataJson1['startTime']:' ';
		$endTimeOld=isset($orgDataJson1['endTime'])?$orgDataJson1['endTime']:' ';
		$atcOld=isset($orgDataJson1['atc'])?$orgDataJson1['atc']:' ';
		$etcOld=isset($orgDataJson1['etc'])?$orgDataJson1['etc']:' ';
		$mtcOld=isset($orgDataJson1['mtc'])?$orgDataJson1['mtc']:' ';		
		$parkDurationOld=isset($orgDataJson1['parkDuration'])?$orgDataJson1['parkDuration']:'';
		$idleDurationOld=isset($orgDataJson1['idleDuration'])?$orgDataJson1['idleDuration']:'';
		$parkingAlertOld=isset($orgDataJson1['parkingAlert'])?$orgDataJson1['parkingAlert']:'';
		$idleAlertOld=isset($orgDataJson1['idleAlert'])?$orgDataJson1['idleAlert']:'';
		$overspeedalertOld=isset($orgDataJson1['overspeedalert'])?$orgDataJson1['overspeedalert']:'';
		$sendGeoFenceSMSOld=isset($orgDataJson1['sendGeoFenceSMS'])?$orgDataJson1['sendGeoFenceSMS']:'';
		$radiusOld=isset($orgDataJson1['radius'])?$orgDataJson1['radius']:'';
		$smsSenderOld=isset($orgDataJson1['smsSender'])?$orgDataJson1['smsSender']:'';
		$sosAlertOld=isset($orgDataJson1['sosAlert'])?$orgDataJson1['sosAlert']:'';
		$liveOld=isset($orgDataJson1['live'])?$orgDataJson1['live']:'no';
		$smsProviderOld=isset($orgDataJson1['smsProvider'])?$orgDataJson1['smsProvider']:'nill';
		$providerUserNameOld=isset($orgDataJson1['providerUserName'])?$orgDataJson1['providerUserName']:'';
		$providerPasswordOld=isset($orgDataJson1['providerPassword'])?$orgDataJson1['providerPassword']:'';
		$schoolPatternOld=isset($orgDataJson1['schoolPattern'])?$orgDataJson1['schoolPattern']:'nill';
		$geofenceOld=isset($orgDataJson1['geofence'])?$orgDataJson1['geofence']:'no';
		$deleteHistoryEodOld=isset($orgDataJson1['deleteHistoryEod'])?$orgDataJson1['deleteHistoryEod']:'no';

            $mobile      = Input::get('mobile');
            $email      = Input::get('email');
            $description      = Input::get('description');
            $address      = Input::get('address');
           	$live=Input::get('live');
			$atc= Input::get('atc');
			$etc=Input::get('etc');
			$mtc=Input::get('mtc');
			$parkingAlert=Input::get('parkingAlert');
			$idleAlert=Input::get('idleAlert');
			$sosAlert=Input::get('sosAlert');
			$parkDuration=Input::get('parkDuration');
			$idleDuration=Input::get('idleDuration');
			$overspeedalert=Input::get('overspeedalert');
			$sendGeoFenceSMS=Input::get('sendGeoFenceSMS');
			
			$smsSender=Input::get('smsSender');
			$smsProvider=Input::get('smsProvider');
			$providerUserName=Input::get('providerUserName');
			$providerPassword=Input::get('providerPassword');
			$smsPattern=Input::get('smsPattern');
			$geofence=Input::get('geofence');
			$safemove=Input::get('safemove');
			$deleteHistoryEod=Input::get('deleteHistoryEod');
			$live=Input::get('live');
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
					'radius'=>$radius,
					'smsSender'=>$smsSender,
					'sosAlert'=>$sosAlert,
					'live'=>$live,
					'smsProvider'=>$smsProvider,
					'providerUserName'=>$providerUserName,
					'providerPassword'=>$providerPassword,
					'schoolPattern'=>$smsPattern,
					'geofence'=>$geofence,
					'safemove'=>$safemove,
					'deleteHistoryEod'=>$deleteHistoryEod,
					
            );
            
            $mapping_Array = array(

		        'mobile' => 'Mobile',
                'email' => 'Email',
                'address' => 'Address',
                'description' => 'Description',
				'startTime' => 'Start Time',
				'endTime'  => 'End Time',
				'atc' => 'AfterNoon Trip Cron',
				'etc' =>'Evening Trip Cron',
				'mtc' =>'Morning Trip Cron',
				'parkingAlert'=>'Parking Alert',
				'idleAlert'=>'Idle Alert',
				'parkDuration'=>'Park Duration',
				'idleDuration'=>'Idle Duration',
				'overspeedalert'=>'Over Speed Alert',
				'sendGeoFenceSMS'=>'Send GeoFence SMS',
				'radius'=>'Radius',
				'smsSender'=>'SMS Sender',
				'sosAlert'=>'SOS Alert',
				'live'=>'Show Live Site',
				'smsProvider'=>'SMS Provider',
				'providerUserName'=>'Provider UserName',
				'providerPassword'=>'Provider Password',
				'schoolPattern'=>'SMS Pattern',
				'geofence'=>'GeoFence',
                'safemove'=>'Safety Movement Alert',
				'deleteHistoryEod'=>'Delete History Eod',

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

           
            $oldOrg = array();
			$NewOrg = array();


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

          	if($oldOrgData != $orgDataJson){

          	
          		log::info(' change value ');
          		$updateJson 	= json_decode($orgDataJson, true);
          		$updateJson1 = array(
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
					'radius'=>$radius,
					'smsSender'=>$smsSender,
					'sosAlert'=>$sosAlert,
					'live'=>$live,
					'smsProvider'=>$smsProvider,
					'providerUserName'=>$providerUserName,
					'providerPassword'=>'********',
					'schoolPattern'=>$smsPattern,
          		);
          		$mapping_Array1 = array(

		        'mobile' => 'Mobile',
                'email' => 'Email',
                'address' => 'Address',
                'description' => 'Description',
				'startTime' => 'Start Time',
				'endTime'  => 'End Time',
				'atc' => 'AfterNoon Trip Cron',
				'etc' =>'Evening Trip Cron',
				'mtc' =>'Morning Trip Cron',
				'parkingAlert'=>'Parking Alert',
				'idleAlert'=>'Idle Alert',
				'parkDuration'=>'Park Duration',
				'idleDuration'=>'Idle Duration',
				'overspeedalert'=>'Over Speed Alert',
				'sendGeoFenceSMS'=>'Send GeoFence SMS',
				'radius'=>'Radius',
				'smsSender'=>'SMS Sender',
				'sosAlert'=>'SOS Alert',
				'live'=>'Show Live Site',
				'smsProvider'=>'SMS Provider',
				'providerUserName'=>'Provider UserName',
				'providerPassword'=>'Provider Password',
				'schoolPattern'=>'SMS Pattern',

		    );
			$orgDataJsonOld = array (
                    
                    'mobile' => $mobileOld,
                    'email' => $emailOld,
                    'address' => $addressOld,
                    'description' => $descriptionOld,
					'startTime' => $startTimeOld,
					'endTime'  => $endTimeOld,
					'atc' => $atcOld,
					'etc' =>$etcOld,
					'mtc' =>$mtcOld,
					'parkingAlert'=>$parkingAlertOld,
					'idleAlert'=>$idleAlertOld,
					'parkDuration'=>$parkDurationOld,
					'idleDuration'=>$idleDurationOld,
					'overspeedalert'=>$overspeedalertOld,
					'sendGeoFenceSMS'=>$sendGeoFenceSMSOld,
					'radius'=>$radiusOld,
					'smsSender'=>$smsSenderOld,
					'sosAlert'=>$sosAlertOld,
					'live'=>$liveOld,
					'smsProvider'=>$smsProviderOld,
					'providerUserName'=>$providerUserNameOld,
					'providerPassword'=>"*******",
					'schoolPattern'=>$schoolPatternOld,
					'geofence'=>$geofenceOld,
					'deleteHistoryEod'=>$deleteHistoryEodOld,
            );
          		$orgDataJson1Old = json_encode ( $orgDataJsonOld );
          		$orgDataJsonOld1	= json_decode($orgDataJson1Old, true);

          		$orgDataJson2 = json_encode ( $updateJson1 );
          		$updateJson3	= json_decode($orgDataJson2, true);
          		foreach ($updateJson3 as $key => $value) {
          			
          			if(isset($orgDataJsonOld1[$key]) == 1) {
          				
          				if($orgDataJsonOld1[$key] != $updateJson3[$key]){
          					$oldOrg 	= array_add($oldOrg, $mapping_Array[$key], $orgDataJsonOld1[$key]);
          					$NewOrg 	= array_add($NewOrg, $mapping_Array[$key], $updateJson3[$key]);
          				}
          			} else {

          				$oldOrg 	= array_add($oldOrg, $mapping_Array[$key], '');
          				$NewOrg 	= array_add($NewOrg, $mapping_Array[$key], $updateJson3[$key]);
          			}
          			
          		}

          		
          // 	if((count($oldList) >0) && (count($newList) >0))
	        	if(sizeof($mailId) > 0)
	        		try{
	        			log::info(' inside the try function ');
	        			$caption = "Organization Id";
			        	Mail::queue('emails.user', array('username'=>$fcode, 'groupName'=>$id, 'oldVehi'=>$oldOrg, 'newVehi'=> $NewOrg, 'cap'=>$caption), function($message) use ($mailId, $id)
			        	{
			                //Log::info("Inside email :" . Session::get ( 'email' ));
			        		$message->to($mailId)->subject(' Organization Updated -' . $id);
			        	});
			        } catch(\Swift_TransportException $e){
				        log::info($e->getMessage());
				    }

          	
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
            return Redirect::to('vdmOrganization/adhi'.$organizationId);
        }
        
    }   
         
     public function ordIdCheck()
     {
     	if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $organizationId = Input::get('id');
     	$redis = Redis::connection();
     	$organizationId = $redis->hget('H_Org_Company_Map',$organizationId);
     	if($organizationId != null)
     	{
     		return 'Organistaion is already exist. Please choose another one ';
     	}

     }


    public function storedOrg(){
    	
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username 	= Auth::user()->username;
        $redis 		= Redis::connection ();
        $fcode 		= $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        $orgid 		= Input::get('orgId');
   		$orgArr = array();
	    foreach (Input::get('orgId') as $key => $value) {
	    	$routeName 	= $redis->smembers ( 'S_RouteList_'. $value.'_'.$fcode);
	    	foreach ($routeName as $k => $v) {
	    		$orgArr[] = $v;
	    	}
	    }
	    log::info(array_values($orgArr));
	    return $orgArr;
    }

public function _editRoutes(){

	log::info(' edit routes ');
	if(!Auth::check()) {
        return Redirect::to('login');
    }
    $_new_V 	= Input::get('newValue');
	$_old_V 	= Input::get('oldValue');
	$_ord_L		= Input::get('orgIds');
	$redis 		= Redis::connection ();
	$username 	= Auth::user()->username;
    $fcode 		= $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

    if($_new_V != ''){
	    foreach ($_ord_L as $key => $value) {
	    	
	    	$routeName 	= $redis->smembers ( 'S_RouteList_'. $value.'_'.$fcode);
	    	foreach ($routeName as $k => $val) {
	    		if($_old_V == $val)
	    		{
	    			$redis->srem ('S_RouteList_'. $value.'_'.$fcode, $_old_V);
	    			$redis->sadd ('S_RouteList_'. $value.'_'.$fcode, $_new_V);
	    			$redis->rename('Z_'.$_old_V.'_'.$value.'_'.$fcode, 'Z_'.$_new_V.'_'.$value.'_'.$fcode);
	    			$redis->rename('L_'.$_old_V.'_'.$value.'_'.$fcode, 'L_'.$_new_V.'_'.$value.'_'.$fcode);
	    		}
	    	}

	    }
		return 'true';
	}
	else return 'false'; 

}

public function _mapHistory(){
          if(!Auth::check()) {
            return Redirect::to('login');
        }
      //  log::info('map history');

        $_mapr_V   =   Input::get('maproute');
        $_orgid_L  =   Input::get('organId');
        $redis     =   Redis::connection();
        $username  =   Auth::user()->username;
        $fcode     =   $redis->hget('H_UserId_Cust_Map', $username . ':fcode');

             if($_mapr_V != ''){
                     
                 foreach ($_orgid_L as $key => $value) {
                   	
                        $routeName= $redis->smembers('S_RouteList_'.$value.'_'.$fcode);

                    foreach ($routeName as $k => $val) {
	    		              
                                 if($_mapr_V == $val){

                                 	log::info($val);
                                    
                                    log::info($value);

                                     $route_list  =$redis->lrange('L_'.$_mapr_V.'_'.$value.'_'.$fcode,0,-1);
	                                
	                                
                                 }

	    	               }
                    }    

             }
         
         return $route_list;
             
      }

	
public function _deleteRoutes(){

	log::info(' edit routes ');
	if(!Auth::check()) {
        return Redirect::to('login');
    }
    $redis 		= Redis::connection ();
	$username 	= Auth::user()->username;
    $fcode 		= $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $_del_V 	= Input::get('delete');
    $_org_L 	= Input::get('orgIds');
    if($_del_V != '')
    	foreach ($_org_L as $key => $value) {
	    	
	    	$routeName 	= $redis->smembers ( 'S_RouteList_'. $value.'_'.$fcode);
	    	foreach ($routeName as $k => $val) {
	    		if($_del_V == $val)
	    			$redis->srem ('S_RouteList_'. $value.'_'.$fcode, $_del_V);
	    			$redis->del ('Z_'.$_del_V.'_'.$value.'_'.$fcode);
	    			$redis->del ('L_'.$_del_V.'_'.$value.'_'.$fcode);
	    		
	    	}

	    }
	// return 'true';
}

public function getStopName(){

	log::info('###### getStopeName function starts ######');
	if(!Auth::check()) {
        return Redirect::to('login');
    }
    $redis 		= Redis::connection ();
    $username 	= Auth::user()->username;
    $fcode 		= $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $orgIds 	= Input::get('orgId');
    $stopName 	= [];
    if(count($orgIds) > 0)
    	foreach ($orgIds as $k => $val) {
	    	$stopName	 	=  array_add($stopName, $val, $redis->smembers('S_Organisation_Route_'.$val.'_'.$fcode));
	    }
	log::info('###### getStopeName function end ######');
    return $stopName;

}
public function ScanNew($id) {
       // log::info(']]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        log::info( 'User name  ::' . $username);
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		Log::info('fcode=' . $fcode);
        $orgListId = 'S_Organisations_' . $fcode;
		$text_word = strtoupper($id);
		//$text_word = Input::get('val2');
		log::info($text_word);
        if(Session::get('cur')=='dealer')
        {
            $orgListId = 'S_Organisations_Dealer_'.$username.'_'.$fcode;
        }
        else if(Session::get('cur')=='admin')
        {
            $orgListId = 'S_Organisations_Admin_'.$fcode;
        }
        $orgLis = $redis->smembers($orgListId);
		$cou = $redis->SCARD($orgListId);
        $orgLi = $redis->sScan( $orgListId, 0,  'count', $cou, 'match', '*'.$text_word.'*' ); 
		log::info(' organization advance search function end');
		return View::make( 'vdm.organization.orgScan', array ('orgList' => $orgLi[1] ));

    } 


}