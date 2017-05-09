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
		return View::make ( 'vdm.dealers.create' )->with ( 'vehicleGroups', $vehicleGroups )->with('user',$user)
		->with('smsP',VdmFranchiseController::smsP());
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
		Log::info(' image Name----> '.$userId);
		$mobileNo = $detail['mobileNo'];
		$email =$detail['email'];
		if(isset($detail['website'])==1)
			$website=$detail['website'];
		else
			$website='';
		if(isset($detail['smsSender'])==1)
			$smsSender=$detail['smsSender'];
		else
			$smsSender='';
		if(isset($detail['smsProvider'])==1)
			$smsProvider=$detail['smsProvider'];
		else
			$smsProvider='nill';
		if(isset($detail['providerUserName'])==1)
			$providerUserName=$detail['providerUserName'];
		else
			$providerUserName='';
		if(isset($detail['providerPassword'])==1)
			$providerPassword=$detail['providerPassword'];
		else
			$providerPassword='';		
		$vehicleGroups = $redis->smembers ( $userId );
		
		$path = $_SERVER['REQUEST_URI'];
		$pathValue =  explode("/", $path);
		$imgLoc = '/'.$pathValue[1].'/public/uploads/';

		$imageName = $this->utilMethod($website);

		//Log::info(' image Name----> '.$userId);
		$imgSmall = $imgLoc.$imageName.'.small.png';
		$imgMob = $imgLoc.$imageName.'.png';
		$imgLogo = $imgLoc.$userId.'.png';
		//Log::info(' image as '.$imgSmall);
		
		return View::make ( 'vdm.dealers.show', array (
				'userId' => $userId 
		) )->with('mobileNo',$mobileNo)->with('email',$email)->with('website',$website)->with('smsSender',$smsSender)->with('smsProvider',$smsProvider)->with('providerUserName',$providerUserName)->with('providerPassword',$providerPassword)->with('imgSmall', $imgSmall)->with('imgMob',$imgMob)->with('imgLogo',$imgLogo);
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
		//$zoho = $detail['zoho'];
		if(isset($detail['website'])==1)
			$website=$detail['website'];
		else
			$website='';	
		if(isset($detail['smsSender'])==1)
			$smsSender=$detail['smsSender'];
		else
			$smsSender='';
		//ram-zoho
		if(isset($detail['zoho'])==1)
		 	$zoho=$detail['zoho'];
		 else
		 	$zoho='';
		 if(isset($detail['mapKey'])==1)
		 	$mapKey=$detail['mapKey'];
		 else
		 	$mapKey='';
		 if(isset($detail['addressKey'])==1)
		 	$addressKey=$detail['addressKey'];
		 else
		 	$addressKey='';
		 if(isset($detail['notificationKey'])==1)
		 	$notificationKey=$detail['notificationKey'];
		 else
		 	$notificationKey='';
		if(isset($detail['smsProvider'])==1)
			$smsProvider=$detail['smsProvider'];
		else
			$smsProvider='nill';
		if(isset($detail['providerUserName'])==1)
			$providerUserName=$detail['providerUserName'];
		else
			$providerUserName='';
		if(isset($detail['providerPassword'])==1)
			$providerPassword=$detail['providerPassword'];
		else
			$providerPassword='';			
		return View::make ( 'vdm.dealers.edit', array (
				'dealerid' => $dealerid 
		) )->with ( 'mobileNo', $mobileNo )-> 
		with('email',$email)-> with('zoho',$zoho)->with('mapKey',$mapKey)->with('addressKey',$addressKey)->with('notificationKey',$notificationKey)->  with('website',$website)->with('smsSender',$smsSender)->with('smsProvider',$smsProvider)->with('providerUserName',$providerUserName)->with('providerPassword',$providerPassword)->with('smsP',VdmFranchiseController::smsP());
		 
	 }
	 
	 
	 
	 //arun edit
	 
	public function edit($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$userId = $id;
		Log::info(' user id  '.$userId);
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$detailJson=$redis->hget ( 'H_DealerDetails_' . $fcode, $id);
			$detail=json_decode($detailJson,true);
		
		$dealerid=$id;
		$mobileNo = $detail['mobileNo'];
		$email =$detail['email'];
		//$zoho = $detail['zoho'];	
		if(isset($detail['website'])==1)
			$website=$detail['website'];
		else
			$website='';
		if(isset($detail['smsSender'])==1)
			$smsSender=$detail['smsSender'];
		else
			$smsSender='';
		//ram-zoho
		if(isset($detail['zoho'])==1)
		 	$zoho=$detail['zoho'];
		 else
		 	$zoho='';
		if(isset($detail['mapKey'])==1)
		 	$mapKey=$detail['mapKey'];
		 else
		 	$mapKey='';
		 if(isset($detail['addressKey'])==1)
		 	$addressKey=$detail['addressKey'];
		 else
		 	$addressKey='';
		 if(isset($detail['notificationKey'])==1)
		 	$notificationKey=$detail['notificationKey'];
		 else
		 	$notificationKey='';
		if(isset($detail['smsProvider'])==1)
			$smsProvider=$detail['smsProvider'];
		else
			$smsProvider='nill';
		if(isset($detail['providerUserName'])==1)
			$providerUserName=$detail['providerUserName'];
		else
			$providerUserName='';
		if(isset($detail['providerPassword'])==1)
			$providerPassword=$detail['providerPassword'];
		else
			$providerPassword='';

		return View::make ( 'vdm.dealers.edit', array (
				'dealerid' => $dealerid 
		) )->with ( 'mobileNo', $mobileNo )->
		with('email',$email)->with('zoho',$zoho)->with('mapKey',$mapKey)->with('addressKey',$addressKey)->with('notificationKey',$notificationKey)->  with('website',$website)->with('smsSender',$smsSender)->with('smsProvider',$smsProvider)->with('providerUserName',$providerUserName)->with('providerPassword',$providerPassword)->with('smsP',VdmFranchiseController::smsP());
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function update($id) {
	
		$dealerid = $id;
		Log::info(Input::get ( 'providerPassword' ).' update id  '.$dealerid);
		
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		$rules = array (
				'mobileNo' => 'required',
				'email' => 'required',
				//'zoho'  => 'required',
		);
		$validator = Validator::make ( Input::all(), $rules );
		if ($validator->fails ()) {
			Log::error('VDM User Controller update validation failed');
			Session::flash ( 'message', 'Update failed. Please check logs for more details' . '!' );
			return Redirect::to ( 'vdmDealers/update' )->withErrors ( $validator );
		} else {
			// store
			$path = $_SERVER['REQUEST_URI'];
			$pathValue =  explode("/", $path);
			
			$oldDealerList  = $redis->hget( 'H_DealerDetails_'.$fcode, $dealerid );
			
			$mobileNo = Input::get ( 'mobileNo' );
			$email = Input::get ( 'email' );
		$zoho  = Input::get ( 'zoho');
		$mapKey=Input::get('mapKey');
		$addressKey=Input::get('addressKey');
		$notificationKey=Input::get('notificationKey');
			$website=Input::get ( 'website' );
			$smsSender=Input::get ( 'smsSender' );
			$smsProvider=Input::get ( 'smsProvider' );
			$providerUserName=Input::get ( 'providerUserName' );
			$providerPassword=Input::get ( 'providerPassword' );
			$detail=array(
			'email'	=> $email,
			'mobileNo' => $mobileNo,
		'zoho'   => $zoho,
		'mapKey' => $mapKey,
		'addressKey' => $addressKey,
		'notificationKey' => $notificationKey, 
			'website' => $website,
			'smsSender'=>$smsSender,
			'smsProvider'=>$smsProvider,
			'providerUserName'=>$providerUserName,
			'providerPassword'=>$providerPassword,
			);
			Log::info('---------------Dealers 11:--------------');
			$detailJson=json_encode($detail);
			$redis->hset ( 'H_DealerDetails_' . $fcode, $dealerid, $detailJson );
			$redis->hmset ( 'H_UserId_Cust_Map', $dealerid . ':fcode', $fcode, $dealerid . ':mobileNo', $mobileNo,$dealerid.':email',$email, $dealerid.':zoho',$zoho, $dealerid.':mapKey',$mapKey, $dealerid.':addressKey',$addressKey, $dealerid.':notificationKey',$notificationKey );
			

			$oldDealerList = json_decode($oldDealerList, true);

			$imageName = $this->utilMethod($website);

			$mailId = array();
        	$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
        	$franchiseDetails=json_decode($franDetails_json,true);
        	if(isset($franchiseDetails['email2'])==1){
                $mailId[]               = $franchiseDetails['email2'];
        		log::info(array_values($mailId));
        	}

        	$oldList = array();
        	$newList = array();

        	if($oldDealerList['mobileNo'] != $mobileNo){
        		$oldList = array_add($oldList, 'Mobile No ', $oldDealerList['mobileNo']);
        		$newList = array_add($newList, 'Mobile No ', $mobileNo);        		
        	}
    
           if($oldDealerList['zoho'] != $zoho){
        		$oldList = array_add($oldList, 'zoho ', $oldDealerList['zoho']);
        		$newList = array_add($newList, 'zoho ', $zoho);        		
        	}
			
           if($oldDealerList['mapKey'] != $mapKey){
        		$oldList = array_add($oldList, 'Map Key ', $oldDealerList['mapKey']);
        		$newList = array_add($newList, 'Map Key ', $mapKey);        		
        	}
           if($oldDealerList['addressKey'] != $addressKey){
        		$oldList = array_add($oldList, 'Address Key ', $oldDealerList['addressKey']);
        		$newList = array_add($newList, 'Address Key ', $addressKey);        		
        	}
        	if($oldDealerList['notificationKey'] != $notificationKey){
        		$oldList = array_add($oldList, 'Notification Key ', $oldDealerList['notificationKey']);
        		$newList = array_add($newList, 'Notification Key ', $notificationKey);        		
        	}
			
        	if($oldDealerList['email'] != $email){
        		$oldList = array_add($oldList, 'Email ', $oldDealerList['email']);
        		$newList = array_add($newList, 'Email ', $email);        			
        	}

        	if($oldDealerList['website'] != $website){
        		$oldList = array_add($oldList, 'website ', $oldDealerList['website']);
        		$newList = array_add($newList, 'website ', $website);        			
        	}
        	if($oldDealerList['smsSender'] != $smsSender){
        		$oldList = array_add($oldList, 'Sms Sender ', $oldDealerList['smsSender']);
        		$newList = array_add($newList, 'Sms Sender ', $smsSender);        			
        	}
        	if($oldDealerList['smsProvider'] != $smsProvider){
        		$oldList = array_add($oldList, 'Sms Provider ', $oldDealerList['smsProvider']);
        		$newList = array_add($newList, 'Sms Provider ', $smsProvider);        			
        	}

        	if($oldDealerList['providerUserName'] != $providerUserName){
        		$oldList = array_add($oldList, 'Provider UserName ', $oldDealerList['providerUserName']);
        		$newList = array_add($newList, 'Provider UserName ', $providerUserName);        			
        	}
        	if($oldDealerList['providerPassword'] != $providerPassword){
        		$oldList = array_add($oldList, 'Provider Password ', $oldDealerList['providerPassword']);
        		$newList = array_add($newList, 'Provider Password ', $providerPassword);        			
        	}

        	if((count($oldList) >0) && (count($newList) >0))
	        	if(sizeof($mailId) > 0)
	        		try{
	        			log::info(' inside the try function ');
	        			$caption = "Dealer Id";
			        	Mail::queue('emails.user', array('username'=>$fcode, 'groupName'=>$dealerid, 'oldVehi'=>$oldList, 'newVehi'=> $newList, 'cap'=>$caption), function($message) use ($mailId, $dealerid)
			        	{
			                //Log::info("Inside email :" . Session::get ( 'email' ));
			        		$message->to($mailId)->subject('Dealer Name Updated -' . $dealerid);
			        	});
			        } catch(\Swift_TransportException $e){
				        log::info($e->getMessage());
				    }

			
			$upload_folder = '/var/www/'.$pathValue[1].'/public/uploads/'; ///var/www/gitsrc/vamos/public/assets/imgs/
			if (Input::hasFile('logo_smallEdit'))
			{

				$logoSmall=  Input::file('logo_smallEdit');
			    // $link=  Input::file('logo_mob');
			    // $link=  Input::file('logo_desktop');
			    list($width, $height, $type, $attr) = getimagesize($logoSmall);
			    if($height==52 && $width==52 && $type == 3){
			    	
			    	$file_name_small = $imageName. '.'.'small'.'.' . $logoSmall->getClientOriginalExtension();
			   		$logoSmall->move($upload_folder, $file_name_small);
			    } else {
			    	return Redirect::to ( 'vdmDealers/'.$dealerid.'/edit' )->withErrors ( 'Image Size 52*52 or Image format png is missing.' );
			    }
			}  
			if (Input::hasFile('logo_mobEdit'))
			{
				$logoMob=  Input::file('logo_mobEdit');
				// $link=  Input::file('logo_mob');
			    // $link=  Input::file('logo_desktop');
			    list($width, $height, $type, $attr) = getimagesize($logoMob);
			    if($height==144 && $width==272 && $type == 3){
			    	$file_name_Mob = $imageName.'.' . $logoMob->getClientOriginalExtension();
			   		$logoMob->move($upload_folder, $file_name_Mob);
			    } else {
			    	return Redirect::to ( 'vdmDealers/'.$dealerid.'/edit' )->withErrors ( 'Image Size 272*144 or Image format png is missing.' );
			    }
			}
			if (Input::hasFile('logo_deskEdit'))
			{
				$logoDesk =  Input::file('logo_deskEdit');
				// $link=  Input::file('logo_mob');
			    // $link=  Input::file('logo_desktop');
			    list($width, $height, $type, $attr) = getimagesize($logoDesk);
			    if($height==144 && $width==144 && $type == 3){
			    	$file_name_Desk = $dealerid.'.' . $logoDesk->getClientOriginalExtension();
			   		$logoDesk->move($upload_folder, $file_name_Desk);
			    } else {
			    	return Redirect::to ( 'vdmDealers/'.$dealerid.'/edit' )->withErrors ( 'Image Size 144*144 or Image format png is missing.' );
			    }
			}


			// redirect
			Session::flash ( 'message', 'Successfully updated ' . $dealerid . '!' );
			if(Session::get('cur')=='dealer')
			{
				Session::flash ( 'message', 'Updates Successfully !' );
			return View::make ( 'vdm.dealers.edit', array (
				'dealerid' => $dealerid 
		) )->with ( 'mobileNo', $mobileNo )->
		with('email',$email)->with('zoho',$zoho)->with('mapKey',$mapKey)->with('addressKey',$addressKey)->with('notificationKey',$notificationKey)->with('website',$website)->with('smsSender',$smsSender)->with('smsProvider',$smsProvider)->with('providerUserName',$providerUserName)->with('providerPassword',$providerPassword)->with('smsP',VdmFranchiseController::smsP());
			}
			
			return Redirect::to ( 'vdmDealers' );
		}
	}



	public function utilMethod($imageName){
		if(substr($imageName,0,3) == 'www')
	       	$imageName = substr($imageName, 4);

		return $imageName;
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
				'mobileNo' => 'required',  
				'zoho'   => 'required'
				);
                
                
		$validator = Validator::make ( Input::all (), $rules );
       
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmDealers/create' )->withErrors ( $validator );
		}else {
		      $dealerId = Input::get ( 'dealerId' );
              $val = $redis->hget ( 'H_UserId_Cust_Map', $dealerId . ':fcode' );
              $val1= $redis->sismember ( 'S_Dealers_' . $fcode, $dealerId );
		}
		if (strpos($dealerId, 'admin') !== false || strpos($dealerId, 'ADMIN') !== false || strpos($dealerId, 'Admin') !== false) 
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
			
			$path = $_SERVER['REQUEST_URI'];
			$pathValue =  explode("/", $path);

			$dealerId = Input::get ( 'dealerId' );
			$email = Input::get ( 'email' );
			$mobileNo = Input::get ( 'mobileNo' ); 
            $website=Input::get ( 'website' );
            $smsSender=Input::get ( 'smsSender' );
            $smsProvider=Input::get ( 'smsProvider' );
            $providerUserName=Input::get ( 'providerUserName' );
            $providerPassword=Input::get ( 'providerPassword' );
            $zoho=Input::get ( 'zoho' ); //log::info($zoho);
			$mapKey=Input::get ( 'mapKey' );
            $addressKey=Input::get ( 'addressKey' );
            $notificationKey=Input::get ('notificationKey');
            // /var/www/gitsrc/vamos/public/assets/imgs/ for production path
            $upload_folder = '/var/www/'.$pathValue[1].'/public/uploads/';

            $imageName = $this->utilMethod($website);

            

            if (Input::hasFile('logo_small'))
			{
				$logoSmall=  Input::file('logo_small');
			    // $link=  Input::file('logo_mob');
			    // $link=  Input::file('logo_desktop');
			    list($width, $height, $type, $attr) = getimagesize($logoSmall);
			    if($height==52 && $width==52 && $type == 3){

			    	$file_name_small = $imageName. '.'.'small'.'.' . $logoSmall->getClientOriginalExtension();
			   		$logoSmall->move($upload_folder, $file_name_small);
			    } else {
			    	return Redirect::to ( 'vdmDealers/create' )->withErrors ( 'Image Size 52*52 or Image format png is missing.' );
			    }
			}  
			if (Input::hasFile('logo_mob'))
			{
				$logoMob=  Input::file('logo_mob');
			    // $link=  Input::file('logo_mob');
			    // $link=  Input::file('logo_desktop');
			    list($width, $height, $type, $attr) = getimagesize($logoMob);
			    if($height==144 && $width==272 && $type == 3){
			    	$file_name_Mob = $imageName.'.' . $logoMob->getClientOriginalExtension();
			   		$logoMob->move($upload_folder, $file_name_Mob);
			    } else {
			    	return Redirect::to ( 'vdmDealers/create' )->withErrors ( 'Image Size 272*144 or Image format png is missing.' );
			    }
			}
			if (Input::hasFile('logo_desk'))
			{
				$logoDesk =  Input::file('logo_desk');
			    // $link=  Input::file('logo_mob');
			    // $link=  Input::file('logo_desktop');
			    list($width, $height, $type, $attr) = getimagesize($logoDesk);
			    if($height==144 && $width==144 && $type == 3){
			    	$file_name_Desk = $dealerId.'.' . $logoDesk->getClientOriginalExtension();
			   		$logoDesk->move($upload_folder, $file_name_Desk);
			    } else {
			    	return Redirect::to ( 'vdmDealers/create' )->withErrors ( 'Image Size 144*144 or Image format png is missing.' );
			    }
			}


			 
				

			
			// ram
			$detail=array(
			'email'	=> $email,
			'mobileNo' => $mobileNo,
			'website'=>$website,
			'smsSender'=>$smsSender,
			'smsProvider'=>$smsProvider,
			'providerUserName'=>$providerUserName,
			'providerPassword'=>$providerPassword,
			'zoho'=>$zoho,
			'mapKey'=>$mapKey,
			'addressKey'=>$addressKey,
			'notificationKey'=>$notificationKey,
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


	public function dealerCheck(){

		if(!Auth::check()){
			return Redirect::to('login');
		}
		$username =	Auth::user()->username;
		$redis = Redis::connection();
		$newGroupId = Input::get ( 'id');
		$fcode = $redis->hget('H_UserId_Cust_Map',$username.':fcode');
		$dealerName = $redis->sismember('S_Dealers_'.$fcode, $newGroupId);
		$dealerVal = $redis->hget ( 'H_UserId_Cust_Map', $newGroupId . ':fcode' );

		

		if (strpos($newGroupId, 'admin') !== false || strpos($newGroupId, 'ADMIN') !== false || strpos($newGroupId, 'Admin') !== false) 
		{
			return  'Name with admin not acceptable';
		}
		if($dealerName == 1 || isset($dealerVal)){
			return 'Dealer Name already written !';
		}
	}

}
