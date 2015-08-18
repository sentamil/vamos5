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


    public function store()
    {
        
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $rules = array(
                'organizationId'       => 'required',
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
			$startTime =$time1;
			$endTime=$time2;
            $orgDataArr = array (
                    'description' => $description,
                    'email' => $email,
                    'address' => $address,
                    'mobile' => $mobile,
					'startTime' => $startTime,
					'endTime'  => $endTime
            );
            
            $orgDataJson = json_encode ( $orgDataArr );
         
            
            $redis = Redis::connection();
			$companymap=$redis->hget('H_Org_Company_Map',$organizationId);
			if($companymap!=null)
			{
				Log::info('-------------------');
			 Session::flash ( 'message', 'Organistaion is already exist. Please choose another one' );
			 return Redirect::to('vdmOrganization/create');
			}
			
            $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
            $redis->sadd('S_Organisations_'. $fcode, $organizationId);
            
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
        log::info( 'time1 ::' . $time1);
         log::info( 'time2 ::' . $time2);
		$address1=array();
		 $place=array();
		 $place1=array();
		  $latandlan=array();
		$address1= $redis->hgetall('H_Poi_'.$id.'_'.$fcode);
		
		foreach($address1 as $org => $rowId) {
			  
				  $place = array_add($address1, $org,$org);
					$latandlan = array_add($address1, $rowId,$rowId);
					
					 $geocode=file_get_contents("http://maps.google.com/maps/api/geocode/json?address=".$org);

				$output= json_decode($geocode);
				 if($output->status=="ZERO_RESULTS")
				 {
					 log::info( 'inside no result' );
					 $place3 = '';
			
				 }
				 else{
					 try
					 {
						  $place3 =  $output->results[0]->address_components[0]->long_name .','.$output->results[0]->address_components[1]->long_name ;
					 }catch(\Exception $e)
					{
						$place3 =  $output->results[0]->address_components[0]->long_name;
					   Log::error($e->getMessage());
					}
					
					 log::info( '---------------place------------- ::' . $place3);
					 
					 
					
					
					 
				 }	
					$place1 = array_add($place1, $place3,$place3);
			 
		 }
		$i=0;
		$j=0;$k=0;$m=0;
        return View::make('vdm.organization.edit')->with('mobile',$mobile)->with('description',$description)->with('address',$address)->
        with('organizationId',$id)->with('email',$email)->with('place',$place)->with('i',$i)->with('j',$j)->with('k',$k)->with('m',$m)->with('time1',$time1)->with('time2',$time2)->with('place1',$place1);   
        
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
			
			
            $mobile      = Input::get('mobile');
            $email      = Input::get('email');
            $description      = Input::get('description');
            $address      = Input::get('address');
            $organizationId = $id;
			
					$startTime =$time1;
			$endTime=$time2;
            $orgDataArr = array (
                    
                    'mobile' => $mobile,
                    'email' => $email,
                    'address' => $address,
                    'description' => $description,
					'startTime' => $startTime,
					'endTime'  => $endTime
            );
            
            $orgDataJson = json_encode ( $orgDataArr );
         
            
            $redis = Redis::connection();
            $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
            $redis->sadd('S_Organisations_'. $fcode, $organizationId);
            
            $redis->hset('H_Organisations_'.$fcode,$organizationId,$orgDataJson );
            
            $routesArr = explode(",",$mobile);
            
           


for ($i = 0; $i < 10; $i++) {
	//$latandlan      = Input::get('place'.$i);
	$place      = Input::get('latandlan'.$i);
	$oldplace      = Input::get('oldlatandlan'.$i);
	//log::info( '---------------latand lan------------- ::' . $latandlan);
	log::info( '---------------oldplace------------- ::' . $oldplace);
	
	if(!$place==null)
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