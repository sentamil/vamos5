<?php

class HomeController extends BaseController {

	

	

	public function showLogin()
	{
		// show the form
		return View::make('login');
	}
	
	public function admin()
	{
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		$username = Auth::user ()->username;
		if($username !='vamos') {
			return Redirect::to('login')->with('flash_notice', 'Unauthorized user. Futher attempts will be
					treated as hacking, will be prosecuted under Cyber laws.');
		
		}
		else {
			return View::make('admin');
		}
	}
	
	
	public function ipAddressManager()
	{
		
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		$username = Auth::user ()->username;
		if($username !='vamos') {
			return Redirect::to('login')->with('flash_notice', 'Unauthorized user. Futher attempts will be
					treated as hacking, will be prosecuted under Cyber laws.');
		
		}

		$redis = Redis::connection ();
		$ipAddress = $redis->hget('H_IP_Address','ipAddress');
		$gt06nCount = $redis->hget('H_IP_Address','gt06nCount');
		$tr02Count = $redis->hget('H_IP_Address','tr02Count');
		$gt03aCount = $redis->hget('H_IP_Address','gt03aCount');
		
		
		return View::make ( 'IPAddress', array (
				'ipAddress' => $ipAddress ) )->with ( 'gt06nCount', $gt06nCount )->with('tr02Count',$tr02Count)->with('gt03aCount',$gt03aCount);
		
		
	}
	
	public function saveIpAddress() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		$username = Auth::user ()->username;
		if($username !='vamos') {
			return Redirect::to('login')->with('flash_notice', 'Unauthorized user. Futher attempts will be
					treated as hacking, will be prosecuted under Cyber laws.');
		
		}
		
		$rules = array (
				'ipAddress' => 'required',
				'gt06nCount' => 'numeric',
				'tr02Count' => 'numeric',
				'gt03aCount' => 'numeric'
		
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::to ( 'ipAddressManager' )->withErrors ( $validator );
		} else {
			$redis = Redis::connection ();
			$ipAddress= Input::get ( 'ipAddress' );
			$gt06nCount= Input::get ( 'gt06nCount' );
			$tr02Count= Input::get ( 'tr02Count' );
			$gt03aCount= Input::get ( 'gt03aCount' );
			$redis->hmset('H_IP_Address','ipAddress',$ipAddress,'gt06nCount',$gt06nCount,
				'tr02Count',$tr02Count,'gt03aCount',$gt03aCount);
             
             $init=$redis->lindex('L_GT06N_AVBL_PORTS',0);
             $currentCount   = isset($init)?$init:10000;
             
             $endCount = $currentCount+$gt06nCount;
             
             for($count=$currentCount;$count<=$endCount;$count++) {
                   $redis->rpush('L_GT06N_AVBL_PORTS',$count);
             }   
		}
		Session::flash ( 'message', 'Successfully added ipAddress details'. '!' );
		
		return Redirect::to ( 'admin' );
		
	}
	
	public function reverseGeoLocation()
	{		
		Log::info("Into reverse Geo Location");
		$lat = Input::get('lat');
		$lng=Input::get('lng');
		
		Log::info("lat" . $lat . 'lng : ' . $lng);
		//https://maps.google.com/maps/api/geocode/json?latlng
		
		$url = "https://maps.google.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&key=AIzaSyBQFgD9_Pm59zGz0ZfLYCUiH_7zbuZ_bFM";
		$data = @file_get_contents($url);
		$jsondata = json_decode($data,true);
		if(is_array($jsondata) && $jsondata['status'] == "OK")
		{
			Log::info("address:" . $jsondata['results']['1']['formatted_address']);
			echo $jsondata['results']['1']['formatted_address'];
		}
		else {
			Log::info("empty");
		}
		
	}
	
	public function livelogin()
	{
		// show the form
		return View::make('livelogin');
	}


	
	public function doLogin()
	{
		// validate the info, create rules for the inputs
		$rules = array(
			'userName'    => 'required', // make sure the email is an actual email
			'password' => 'required|min:3' // password can only be alphanumeric and has to be greater than 3 characters
		);
    Log::info('do Login');
		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);
		$remember = (Input::has('remember')) ? true : false;
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			return Redirect::to('login')
				->withErrors($validator) // send back all errors to the login form
				->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
		} else {

			// create our user data for the authentication
			$userdata = array(
				'userName' 	=> Input::get('userName'),
				'password' 	=> Input::get('password')
			);
            Log::info('Login details ' . Input::get('userName') .' '. Input::get('password') );
			// attempt to do the login
			if (Auth::attempt($userdata,$remember)) {
				log::info(' inside the login ');
                $username = Auth::user()->username;
                  $redis = Redis::connection();
                  $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
                  $url = Request::url();
                   Log::info('Login details $url ' . $url);
                 /* if($redis->sismember('S_Users_' . $fcode,$username)) {
                       Log::info('Login details ' . $fcode);
                      return  Redirect::to('live');
                  }
            */
				// validation successful!
				
				//return  Redirect::to('vdmVehicles');
				
			/*	if(Session::get('cur')=='dealer' || Session::get('cur')=='admin')
				{
					log::info( '------login 1---------- '.Session::get('cur'));
					$redis->sadd('S_Organisations_Dealer_'.$username.'_'.$fcode,$organizationId);
					 return  Redirect::to('vdmVehicles');
				}*/
			  if(strpos($username,'admin')!==false ) {
             //do nothing
			log::info( '---------- inside if filter adminauth----------' . $username) ;
			//Auth::session(['cur' => 'admin']);
			 return  Redirect::to('Business');
      }
	 
      else {
        $redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$val1= $redis->sismember ( 'S_Dealers_' . $fcode, $username );
		$val = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		  if($val1==1 && isset($val)) {
			Log::info('---------------is dealer adminauth:--------------');			
			 return  Redirect::to('DashBoard');
			
		}
		
           
      }
			 // Log::info('Login details $url ' . $url);
		// $redis = Redis::connection ();
		// $fcodeKey = $redis->hget( 'H_UserId_Cust_Map',$username . ':fcode' );
		// $franchiseDetails = $redis->hget( 'H_Franchise', $fcodeKey);
		// $getFranchise=json_decode($franchiseDetails,true);
		// // log::info($getFranchise);
		// $apiKey='';
		// if(isset($getFranchise['apiKey'])==1)
		// 	$apiKey=$getFranchise['apiKey'];
		// Session::put('apikey', $apiKey);
		// Log::info('apikey '.$apiKey);	
		// log::info(Session::get('apikey'));
		// $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		return  Redirect::to('track');
				

			} else {	 	
				return Redirect::to('login')
				->withInput(Input::except('password'))
				->with('flash_notice', 'Your username/password combination was incorrect.');
				// validation not successful, send back to form	
				//return Redirect::to('login');

			}

		}
	}
	

	public function getApi()
	{
		
		log::info(' inside the api key ');
		$username = Input::get ( 'id' );
		$redis = Redis::connection ();
		$fcodeKey = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$franchiseDetails = $redis->hget( 'H_Franchise', $fcodeKey);
		$getFranchise=json_decode($franchiseDetails,true);
		// log::info($getFranchise);
		
		if(isset($getFranchise['apiKey'])==1)
			$apiKey=$getFranchise['apiKey'];
		else
			$apiKey='';

		
		return $apiKey;
	}

	public function track(){
		
		return View::make('track');
	}

	
    public function adhocMail() {
        return View::make('vls.adhocmail');
    }

    public function sendAdhocMail() {
        
        
        Log::info(" inside send adhoc mail");
        $userId  = Input::get('toAddress');
        $ccAddress  = Input::get('ccAddress');
        $subject  = Input::get('subject');
        $body  = Input::get('body');
        
         Mail::queue('emails.welcome', array('fname'=>$userId,'userId'=>$userId,'password'=>$password), function($message)
            {
                $message->to(Input::get('toAddress'))->subject(Input::get('subject')) ;
            });
            
        
        Session::flash ( 'message', 'Mail sent ' . $to . '!' );    
        return View::make('vls.adhocmail');
    }


    public function authName() {

    	log::info(' inside the api key ');
    	$assetValue = array();
		$username = Auth::user()->username;
		$redis = Redis::connection ();
		$fcodeKey = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$franchiseDetails = $redis->hget( 'H_Franchise', $fcodeKey);
		$getFranchise=json_decode($franchiseDetails,true);
		// log::info($getFranchise);
		
		if(isset($getFranchise['apiKey'])==1)
			$apiKey=$getFranchise['apiKey'];
		else
			$apiKey='';

		$assetValue[] = $apiKey;
		$assetValue[] = $username;

        return $assetValue;
     } 

    /*   public function authName(){

    	log::info(' inside the api key ');

    	$assetValue = array();
		$username   = Auth::user()->username;
		$redis      = Redis::connection();

		$fcodeKey   = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        $dealerName = $redis->hget ( 'H_UserId_Cust_Map', $username . ':OWN' );

        log::info('------------------- Dealer Name : '.$dealerName.'--------------------------------' );


    	if( $dealerName !='' && $dealerName !='admin'  ){

            log::info('-------------- inside dealer ------------------');

          	$detailJson     = $redis->hget ( 'H_DealerDetails_' . $fcodeKey, $dealerName);
			$detailsDealer  = json_decode($detailJson,true); 
			  // log::info( $detailsDealer );

             if(isset($detailsDealer['mapKey'])==1){

                	log::info('-------------- inside dealer if...------------------');  
                   
			         $apiKey = $detailsDealer['mapKey'];

			         //log::info( $detailsDealer );
                  
                  if( $apiKey !='') {
                      $assetValue[] = $apiKey;
		              $assetValue[] = $username;
		          }
		          else{

                       	log::info('-------------- inside dealer and then franchise if if if ...... ------------------');

			        $franchiseDetails = $redis->hget( 'H_Franchise', $fcodeKey);
	                $getFranchise=json_decode($franchiseDetails,true);
		            //log::info($getFranchise);
		
		         if(isset($getFranchise['apiKey'])==1){
		        	$apiKey=$getFranchise['apiKey'];
		         }
		         else{
		         	$apiKey='';
		          }

		         $assetValue[] = $apiKey;
		         $assetValue[] = $username;

		          }
		      }
		     else{

		     	log::info('-------------- inside dealer and then franchise ------------------');

			    $franchiseDetails = $redis->hget( 'H_Franchise', $fcodeKey);
	            $getFranchise=json_decode($franchiseDetails,true);
		        //log::info($getFranchise);
		
		         if(isset($getFranchise['apiKey'])==1){
		        	$apiKey=$getFranchise['apiKey'];
		         }
		         else{
		         	$apiKey='';
		          }

		         $assetValue[] = $apiKey;
		         $assetValue[] = $username;
		    }
        }else{

         log::info('-------------- inside franchise ------------------');

         $franchiseDetails = $redis->hget( 'H_Franchise', $fcodeKey);
	     $getFranchise=json_decode($franchiseDetails,true);
		 //log::info($getFranchise);
		
		 if(isset($getFranchise['apiKey'])==1){
			$apiKey=$getFranchise['apiKey'];
		 }
		 else{
			$apiKey='';
		 }

		$assetValue[] = $apiKey;
		$assetValue[] = $username;

       }

       log::info('------------------------- return api value starts -------------------------------');
       log::info($assetValue); 
       log::info('------------------------- return api value ends -------------------------------');
       
    return $assetValue;
    }
*/

	public function doLogout()
	{
		Auth::logout(); // log the user out of our application
		// return View::make('logout');
		// return View::make('logout');
		return Redirect::to('login');
		//return Redirect::to('logoutWeb'); // redirect the user to the login screen
	}

}
