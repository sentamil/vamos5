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
		
		
		
		return View::make ( 'vdm.dealers.show', array (
				'userId' => $userId 
		) )->with('mobileNo',$mobileNo)->with('email',$email)->with('website',$website)->with('smsSender',$smsSender)->with('smsProvider',$smsProvider)->with('providerUserName',$providerUserName)->with('providerPassword',$providerPassword);
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
		return View::make ( 'vdm.dealers.edit', array (
				'dealerid' => $dealerid 
		) )->with ( 'mobileNo', $mobileNo )->
		with('email',$email)->with('website',$website)->with('smsSender',$smsSender)->with('smsProvider',$smsProvider)->with('providerUserName',$providerUserName)->with('providerPassword',$providerPassword)->with('smsP',VdmFranchiseController::smsP());
		 
	 }
	 
	 
	 
	 
	 
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
		return View::make ( 'vdm.dealers.edit', array (
				'dealerid' => $dealerid 
		) )->with ( 'mobileNo', $mobileNo )->
		with('email',$email)->with('website',$website)->with('smsSender',$smsSender)->with('smsProvider',$smsProvider)->with('providerUserName',$providerUserName)->with('providerPassword',$providerPassword)->with('smsP',VdmFranchiseController::smsP());
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function update($id) {
	
		$dealerid = $id;
		Log::info(' update id  '.$dealerid);
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
			$smsSender=Input::get ( 'smsSender' );
			$smsProvider=Input::get ( 'smsProvider' );
			$providerUserName=Input::get ( 'providerUserName' );
			$providerPassword=Input::get ( 'providerPassword' );
			$detail=array(
			'email'	=> $email,
			'mobileNo' => $mobileNo,
			'website' => $website,
			'smsSender'=>$smsSender,
			'smsProvider'=>$smsProvider,
			'providerUserName'=>$providerUserName,
			'providerPassword'=>$providerPassword,
			);
			Log::info('---------------Dealers 11:--------------');
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
		with('email',$email)->with('website',$website)->with('smsSender',$smsSender)->with('smsProvider',$smsProvider)->with('providerUserName',$providerUserName)->with('providerPassword',$providerPassword)->with('smsP',VdmFranchiseController::smsP());
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
			
			$dealerId = Input::get ( 'dealerId' );
			$email = Input::get ( 'email' );
			$mobileNo = Input::get ( 'mobileNo' );
            $website=Input::get ( 'website' );
            $smsSender=Input::get ( 'smsSender' );
            $smsProvider=Input::get ( 'smsProvider' );
            $providerUserName=Input::get ( 'providerUserName' );
            $providerPassword=Input::get ( 'providerPassword' );

            $upload_folder = '/var/www/codebase/vamos/public/assets/imgs';
            if (Input::hasFile('logo_small'))
			{
				$logoSmall=  Input::file('logo_small');
			    // $link=  Input::file('logo_mob');
			    // $link=  Input::file('logo_desktop');
			    list($width, $height, $type, $attr) = getimagesize($logoSmall);
			    if($height==52 && $width==52 && $type == 3){
			    	
			    	$file_name_small = $_SERVER['HTTP_HOST']. '.'.'small'.'.' . $logoSmall->getClientOriginalExtension();
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
			    	$file_name_Mob = $_SERVER['HTTP_HOST'].'.' . $logoMob->getClientOriginalExtension();
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


			 //    $fileSize = $_FILES['avatar']["size"];

			 //    $file_name = preg_replace('#[^a-z.0-9]#i', '', $file_name); // filter the $filename
				
				// $kaboom = explode(".", $file_name); // Split file name into an array using the dot
				
				// Log::info(count($kaboom));
				// $fileExt = end($kaboom);

				// $target_file = "uploads/$fileName";
				// $resized_file = "uploads/resized_$fileName";
				// $wmax = 200;
				// $hmax = 150;


				// function ak_img_resize($target, $newcopy, $w, $h, $ext) {
				//     list($w_orig, $h_orig) = getimagesize($target);
				//     $scale_ratio = $w_orig / $h_orig;
				//     if (($w / $h) > $scale_ratio) {
				//            $w = $h * $scale_ratio;
				//     } else {
				//            $h = $w / $scale_ratio;
				//     }
				//     $img = "";
				//     $ext = strtolower($ext);
				//     if ($ext == "gif"){ 
				//       $img = imagecreatefromgif($target);
				//     } else if($ext =="png"){ 
				//       $img = imagecreatefrompng($target);
				//     } else { 
				//       $img = imagecreatefromjpeg($target);
				//     }
				//     $tci = imagecreatetruecolor($w, $h);
				//     // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
				//     imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
				//     imagejpeg($tci, $newcopy, 80);
				// }

				// ak_img_resize($upload_folder, $upload_folder, $wmax, $hmax, $fileExt);

				// $fileExt = end($kaboom); // Now target the last array element to get the file extension
				// // $target_file = "uploads/resized_$fileName";
			 //    $new_jpg = "/Applications/XAMPP/htdocs/vamo/public/assets/uploads/".$kaboom[0].".png";
			 //    ak_img_convert_to_jpg($upload_folder, $new_jpg, $fileExt);
			 //    Log::info(' file_size ----------'.$fileSize.'-----'.$file_name.'----------------'.$fileExt);
			    // $actual_link = $_SERVER['HTTP_HOST'];
			    // Log::info(' url '. $actual_link);

			    // $upload_folder = 'images-folder/';
			    // $img=file_get_contents($link);
			    // $fileSave = file_put_contents($upload_folder.substr($link, strrpos($link,'/')), $img);
		        

				

			
			// ram
			$detail=array(
			'email'	=> $email,
			'mobileNo' => $mobileNo,
			'website'=>$website,
			'smsSender'=>$smsSender,
			'smsProvider'=>$smsProvider,
			'providerUserName'=>$providerUserName,
			'providerPassword'=>$providerPassword,
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
