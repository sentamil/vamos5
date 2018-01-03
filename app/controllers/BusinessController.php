<?php
use Carbon\Carbon;
class BusinessController extends \BaseController {
	
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
		return View::make ( 'vdm.business.deviceAddCopy' )->with ( 'orgList', $orgList )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList );
		}
		else if(Session::get('cur')=='dealer')
		{
			$key='H_Pre_Onboard_Dealer_'.$username.'_'.$fcode;


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
	
		$userList = $redis->smembers ( $redisUserCacheId);
		//get the intersection of users set and groups set
		//$userList=$redis->sinter($redisUserCacheId,'S_Organisations_'.$fcode);
		
		$orgArra = array();
		$orgArra =array_add($orgArra, 'select','select');
      foreach($userList as $org) {
           if(!$redis->sismember ( 'S_Users_Virtual_' . $fcode, $org )==1)
		 {
		 	 $orgArra = array_add($orgArra, $org,$org);
		 }	
			
        }
			$userList=$orgArra;
		
		
			return View::make ( 'vdm.business.business1')->with('devices',$devices)->with('devicestypes',$devicestypes)->with('dealerId',$dealerId)->with('userList',$userList)->with ( 'orgList', $orgList );
		
		}
        
	}
	
	
	public function create() {
		DatabaseConfig::checkDb();
		if (! Auth::check ()) {
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
		return View::make ( 'vdm.business.deviceAdd' )->with ( 'orgList', $orgList )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList );
	}
	public function checkUser()
	{
		log::info( 'ahan'.'-------- laravel test ::----------'.Input::get('id'));
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$error=' ';
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        $fcode1=strtoupper($fcode);
		$userId = Input::get ( 'id');
		$userId1 =strtoupper($userId);		
		  $val = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':fcode' );
		  $val1= $redis->sismember ( 'S_Users_' . $fcode, $userId );
		  $valOrg= $redis->sismember('S_Organisations_'. $fcode, $userId);	
		   $valOrg1=$redis->sismember('S_Organisations_Admin_'.$fcode,$userId);
		   $valGroup=$redis->sismember('S_Groups_' . $fcode, $userId1 . ':' . $fcode1);
		   $valGroup1=$redis->sismember('S_Groups_Admin_'.$fcode,$userId1 . ':' . $fcode1);
				
				if($valGroup==1 || $valGroup1==1 ) {
					log::info('id group exist '.$userId);
					$error= 'Name already exist' ;
				}
				if($valOrg==1 || $valOrg1==1 ) {
					log::info('id org exist '.$userId);
					$error='Name already exist' ;
				}
				if($val1==1 || isset($val)) {
					log::info('id already exist '.$userId);
					$error= 'User Id already exist' ;
				}
				if (strpos($userId, 'admin') !== false || strpos($userId, 'ADMIN') !== false) {
					$error= 'Name with admin not acceptable' ;
				}

				$refDataArr = array (

			'error' => $error

			);
		$refDataJson = json_encode ( $refDataArr );

		log::info('changes value '.$error);            
		return Response::json($refDataArr);	
	}
	public function checkDevice()
	{
		log::info( 'ahan'.'-------- laravel test ::----------'.Input::get('id'));
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}

		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

		$deviceid = Input::get ( 'id');


		$dev=$redis->hget('H_Device_Cpy_Map',$deviceid);
		$error=' ';
		if($dev!==null)
		{
			$error='Device Id already present '.$deviceid;
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
		
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}

		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

		$vehicleId = Input::get ( 'id');
        $vehicleU = strtoupper($vehicleId);
	log::info( $fcode.'-------- laravel test ::----------'.$vehicleId);
		$vehicleIdCheck = $redis->sismember('S_Vehicles_' . $fcode, $vehicleId);
		$vehicleIdCheck2 = $redis->sismember('S_KeyVehicles', $vehicleU);
		$error=' ';
		if($vehicleIdCheck==1 && $vehicleIdCheck2==1) 
		{
			$error='Vehicle Id already present '.$vehicleId;
		}
		$refDataArr = array (

			'error' => $error

			);
		$refDataJson = json_encode ( $refDataArr );

		log::info('changes value '.$vehicleIdCheck);
        log::info('changes value keyVehi '.$vehicleIdCheck2);		
		return Response::json($refDataArr);
	}
	
public function getGroup()
	{
		
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}

		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

		$userId = Input::get ( 'id');
log::info( $fcode.'-------- laravel test1 ::----------'.$userId);
$vehRfidYesList=array();
	$vehicleGroups = $redis->smembers ( $userId );
		
		//$vehicleGroups = implode ( '<br/>', $vehicleGroups );


 foreach ( $vehicleGroups as $vehicle ) {
$vehRfidYesList = array_add($vehRfidYesList,$vehicle,$vehicle);
 }$error=' ';
if(count($vehicleGroups)==0)
{
	$error='No groups available ,Please select another user';
}

		$refDataArr = array (

			'groups' => $vehRfidYesList,
			'error'=>$error,
			);

	$refDataJson = json_encode ( $refDataArr );
                     
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
		log::info( '-------- store in  ::----------');
        $availableLincence=Input::get ( 'availableLincence' );
		if ($validator->fails ()) {
			return View::make ( 'vdm.business.deviceAddCopy' )->withErrors ( $validator )->with ( 'orgList', null )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', null )->with ( 'dealerId', null )->with ( 'userList', null )->with('orgList',null);
		} 
		else{
			
			$numberofdevice = Input::get ( 'numberofdevice' );
			// store
			
			
			log::info( '-------- av license in  ::----------'.$availableLincence);
			if($numberofdevice>$availableLincence)
			{
				return View::make ( 'vdm.business.deviceAddCopy' )->withErrors ( "Your license count is less" )->with ( 'orgList', null )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', null )->with ( 'dealerId', null )->with ( 'userList', null )->with('orgList',null);
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
		
		 return View::make ( 'vdm.business.createCopy' )->with ( 'orgList', $orgList )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList )->with('orgList',$orgList)->with('Licence',$Licence1)->with('Payment_Mode',$Payment_Mode1)->with('protocol', $protocol);
		}
		
		
	}



public static function getUser()
{
	$username = Auth::user ()->username;
			$redis = Redis::connection ();
			$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
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
		$userList=$redis->smembers($redisUserCacheId);
		$orgArra = array();
		$orgArra = array_add($orgArra, 'select','select');
      foreach($userList as $key => $org) {
      	 if(!$redis->sismember ( 'S_Users_Virtual_' . $fcode, $org )==1)
		 {
		 	 $orgArra = array_add($orgArra, $org,$org);
		 }	
        }
			$userList=$orgArra;

			return $userList;
}

public static function getdealer()
{
	$username = Auth::user ()->username;
			$redis = Redis::connection ();
			$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
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

		return $dealerId;
}




public static function getOrg()
{
	$username = Auth::user ()->username;
			$redis = Redis::connection ();
			$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
	$orgList=null;
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
        $orgArray = array();  
        $orgArray = array_add($orgArray, '','select');
        foreach ( $orgList as $org ) {
            
            $orgArray = array_add($orgArray, $org,$org);
        }
        
        $orgList=$orgArray;

return $orgList;
}	

public function adddevice() {
			if (! Auth::check ()) {
			return Redirect::to ( 'login' );
			}
			//dd(Input::get());
			$username = Auth::user ()->username;
			$redis = Redis::connection ();
			$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
			$fcode1=strtoupper($fcode);
			$ownerShip      = Input::get('dealerId');
			$userId      = Input::get('userId');
			$userId1 = strtoupper($userId);
			$mobileNoUser      = Input::get('mobileNoUser');
			$emailUser      = Input::get('emailUser');
			$password      = Input::get('password');
			$type      = Input::get('type');
			$type1      = Input::get('type1');
			
			$numberofdevice = Input::get ( 'numberofdevice1' );
			// $orgList=array();
			// $orgList=BusinessController::getOrg();

			$availableLincence=Input::get ( 'availableLincence' );
			$userList=array();
			$userList=BusinessController::getUser();
			$orgList=array();
			$orgList=BusinessController::getOrg();
			$dealerId=array();
			$dealerId=BusinessController::getdealer();
			//thiru
			if( $type1 != null && $type1 =='new')
			{
				if(Session::get('cur')=='dealer')		
        		{		
            		log::info( '------login 1---------- '.Session::get('cur'));		
            		
            		$totalReports = $redis->smembers('S_Users_Reports_Dealer_'.$username.'_'.$fcode);		
        		}		
        		else if(Session::get('cur')=='admin')		
        		{		
					$totalReports = $redis->smembers('S_Users_Reports_Admin_'.$fcode);
        		}
        		if($totalReports != null)
        		{
        			foreach ($totalReports as $key => $value) {
        				$redis-> sadd('S_Users_Reports_'.$userId.'_'.$fcode, $value);
        			}
        		}
				// addReportsForNewUser($userId);
			}
			if($type1=='existing')
			{
				$userId      = Input::get('userIdtemp');
				if($userId==null ||$userId=='select')
				{
					Session::flash ( 'message', 'Invalid user Id !' );
					return Redirect::to ( 'Business' )->withErrors ( 'Invalid user Id' );
				}	
			}
			if($type==null && Session::get('cur')=='admin')
			{
				log::info($ownerShip.'valuse ----------->'.Input::get('userIdtemp'));
				Session::flash ( 'message', 'select the sale!' );
				//return View::make ( 'vdm.business.store');
				//return Redirect::back()->withErrors('select the sale!');
				return View::make ( 'vdm.business.create' )->withErrors ( "select the sale!" )->with ( 'orgList', null )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList )->with('orgList',$orgList);

			}	
			if($type=='Sale' && Session::get('cur')!='dealer')
			{
				log::info($ownerShip.'1 ----------->'.$type);

				$ownerShip      = 'OWN';
			}
			if(Session::get('cur')=='dealer' ){
				log::info($ownerShip.'2 --a--------->'.Session::get('cur'));
				if($type1==null)
				{
					//return Redirect::to ( 'Business' )->withErrors ( 'select the sale' );
					return Redirect::back()->withErrors('select the sale!');
				}
					$type='Sale';
					$ownerShip = $username;
					$mobArr = explode(',', $mobileNo);
			}
			if($type=='Sale' && $type1==null)
			{
				//return Redirect::to ( 'Business' )->withErrors ( 'Select the user' );
				return Redirect::back()->withErrors('Select the user !');
			}
			 if($type=='Sale' && $type1=='new')
			{
				log::info($ownerShip.'3----a------->'.Session::get('cur'));
				 $rules = array (
				'userId' => 'required|alpha_dash',
				'emailUser' => 'required|email',
				
				);             
                
				$validator = Validator::make ( Input::all (), $rules );			   
				if ($validator->fails ()) {
					return Redirect::back()->withErrors ( $validator );
					//return Redirect::back()->withErrors('Select the user !');
				}else {
					  $val = $redis->hget ( 'H_UserId_Cust_Map', $userId . ':fcode' );
					  $val1= $redis->sismember ( 'S_Users_' . $fcode, $userId );
					  $valOrg= $redis->sismember('S_Organisations_'. $fcode, $userId);	
					   $valOrg1=$redis->sismember('S_Organisations_Admin_'.$fcode,$userId);
					   $valGroup=$redis->sismember('S_Groups_' . $fcode, $userId1 . ':' . $fcode1);
					   $valGroup1=$redis->sismember('S_Groups_Admin_'.$fcode,$userId1 . ':' . $fcode1);
				}
				if($valGroup==1 || $valGroup1==1 || $valOrg==1 || $valOrg1==1 ) {
					return View::make ( 'vdm.business.create' )->withErrors ( "Name already exist" )->with ( 'orgList', null )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList )->with('orgList',$orgList);
				}
				if($val1==1 || isset($val)) {
					return View::make ( 'vdm.business.create' )->withErrors ( "User Id already exist" )->with ( 'orgList', null )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList )->with('orgList',$orgList);
				}
				if (strpos($userId, 'admin') !== false || strpos($userId, 'ADMIN') !== false) {
					//return Redirect::back()->withErrors ( 'Name with admin not acceptable' );

					return View::make ( 'vdm.business.create' )->withErrors ( "Name with admin not acceptable" )->with ( 'orgList', null )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList )->with('orgList',$orgList);
				}
				

			    $mobArr = explode(',', $mobileNoUser);
				foreach($mobArr as $mob){
					$val1= $redis->sismember ( 'S_Users_' . $fcode, $userId );
				if($val1==1 ) 
				{
                	log::info('id alreasy exist '.$mob);
                	//return Redirect::back()->withErrors ( );

                	return View::make ( 'vdm.business.create' )->withErrors ( $mob . ' User Id already exist')->with ( 'orgList', null )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList )->with('orgList',$orgList);
                 }
				}	


			}
				if($type=='Sale')
				{
					
					$groupname1      = Input::get('groupname');
					$groupname       = strtoupper($groupname1);
					log::info($userId.'-------------- groupname- 1-------------'.$groupname);
					if($type1=='existing' && ($groupname==null || $groupname=='' || $groupname=='0'))
					{
						
						return View::make ( 'vdm.business.create' )->withErrors ( ' Invalid groupname')->with ( 'orgList', null )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList )->with('orgList',$orgList);
					}

					
					
				}
			log::info('value type---->'.$type);
			$organizationId=$userId;
			$orgId=$organizationId;
			$groupId=$orgId;
			$groupId1=strtoupper($groupId);
			if($ownerShip=='OWN' && $type!='Sale')
			{
				$orgId='Default';
				
			}
			if($userId==null)
			{
				$orgId='Default';
				$organizationId='Default';
			}
												



$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
$franchiseDetails=json_decode($franDetails_json,true);
if(isset($franchiseDetails['availableLincence'])==1)
$availableLincence=$franchiseDetails['availableLincence'];
else
$availableLincence='0';
$count=0;
$numberofdevice = Input::get ( 'numberofdevice1' );
log::info( '--------number of  device::----------'.$numberofdevice);
$vehicleIdarray=array();
$deviceidarray=array();
$KeyVehicles=array();
if($type=='Sale' && $type1!=='new')
{
	$orgId=Input::get ( 'orgId');
}
//ram vehicleExpiry default date
$current = Carbon::now();
$onboardDate=$current->format('d-m-Y');
$trkpt=$current->addYears(1)->format('Y-m-d');
log::info($trkpt);
//
$dbarray=array();
$dbtemp=0;
for($i =1;$i<=$numberofdevice;$i++)
{
	$deviceid = Input::get ( 'deviceid'.$i);
	$vehicleId1 = Input::get ( 'vehicleId'.$i);
	//
	$pattern = '/[\'\/~`\!@#\$%\^&\*\(\)\ \\\+=\{\}\[\]\|;:"\<\>,\.\?\\\']/';
    if (preg_match($pattern, $vehicleId1))
    {
        return Redirect::back()->withErrors ('Vehicle ID should be in alphanumeric with _ or - Characters '.$vehicleId1);
    }
    else if (preg_match($pattern, $deviceid))
    {
       return Redirect::back()->withErrors ( 'Device ID should be in alphanumeric with _ or - Characters '.$deviceid);
    }
//	$vehicleId2 = str_replace('.', '-', $vehicleId1);
	$vehicleId = strtoupper($vehicleId1);
	
	$vehicleId=!empty($vehicleId) ? $vehicleId : 'GPSVTS_'.substr($deviceid, -6);
	//isset($vehicleRefData['shortName'])?$vehicleRefData['shortName']:'nill';
	log::info( Input::get('deviceidtype50').'--------number of  name::----------'.$i);
	$deviceid=str_replace(' ', '', $deviceid);
	$vehicleId=str_replace(' ', '', $vehicleId);
	$deviceidtype=Input::get('deviceidtype'.$i);
	
	$deviceModel=$deviceidtype;
	$shortName1=Input::get ( 'shortName'.$i);
	$shortName = strtoupper($shortName1);
	$shortName=!empty($shortName) ? $shortName : $vehicleId;				
	$regNo=Input::get ( 'regNo'.$i);	
	$regNo=!empty($regNo) ? $regNo : 'XXXX';
	
	$orgId=!empty($orgId) ? $orgId : 'Default';
	if($ownerShip!=='OWN')
	{
		$orgId='Default';
	}
	$vehicleType=Input::get ( 'vehicleType'.$i);	
	$vehicleType=!empty($vehicleType) ? $vehicleType : 'Bus';
	$oprName=Input::get ( 'oprName'.$i);
	$oprName=!empty($oprName) ? $oprName : 'Airtel';
	$mobileNo=Input::get ( 'mobileNo'.$i);
	$mobileNo=!empty($mobileNo) ? $mobileNo : '0123456789';
	$odoDistance=Input::get ( 'odoDistance'.$i);	
	$odoDistance=!empty($odoDistance) ? $odoDistance : '0';
	$overSpeedLimit=Input::get ( 'overSpeedLimit'.$i);
	$overSpeedLimit=!empty($overSpeedLimit) ? $overSpeedLimit : '60';	
	$driverName=Input::get ( 'driverName'.$i);	
	$driverName=!empty($driverName) ? $driverName : '';	
	$email=Input::get ( 'email'.$i);	
	$email=!empty($email) ? $email : '';	
	//
	$vehicleExpiry=Input::get ( 'vehicleExpiry');
	if($fcode == 'TRKPT') {
	$vehicleExpiry=!empty($vehicleExpiry) ? $vehicleExpiry : $trkpt;
    }
    else
	{
    $vehicleExpiry=!empty($vehicleExpiry) ? $vehicleExpiry : '';	
    }
    //$vehicleExpiry=!empty($vehicleExpiry) ? $vehicleExpiry : 'nill';
	$altShortName=Input::get ( 'altShortName'.$i);	
	$altShortName=!empty($altShortName) ? $altShortName : '';						
	$sendGeoFenceSMS=Input::get ( 'sendGeoFenceSMS'.$i);
	$sendGeoFenceSMS=!empty($sendGeoFenceSMS) ? $sendGeoFenceSMS : 'no';	
	$morningTripStartTime=Input::get ( 'morningTripStartTime'.$i);
	$morningTripStartTime=!empty($morningTripStartTime) ? $morningTripStartTime : '';	
	$eveningTripStartTime=Input::get ( 'eveningTripStartTime'.$i);	
	$eveningTripStartTime=!empty($eveningTripStartTime) ? $eveningTripStartTime : '';

	$gpsSimNo=Input::get ( 'gpsSimNo'.$i);	
	$gpsSimNo=!empty($gpsSimNo) ? $gpsSimNo : '0123456789';

	$Licence=Input::get ( 'Licence'.$i);	
	$Licence=!empty($Licence) ? $Licence : 'Advance';
	$descriptionStatus=Input::get ( 'descr'.$i);	
	$descriptionStatus=!empty($descriptionStatus) ? $descriptionStatus : '';

	$Payment_Mode=Input::get ( 'Payment_Mode'.$i);	
	$Payment_Mode=!empty($Payment_Mode) ? $Payment_Mode : 'Monthly';


$licence_id = DB::select('select licence_id from Licence where type = :type', ['type' => $Licence]);
$payment_mode_id = DB::select('select payment_mode_id from Payment_Mode where type = :type', ['type' => $Payment_Mode]);
		
		
$licence_id=$licence_id[0]->licence_id;
$payment_mode_id=$payment_mode_id[0]->payment_mode_id;
	log::info( $deviceid.'-------- av  in  ::----------'.$deviceidtype.' '.$licence_id.' '.$payment_mode_id);
	if($deviceid!==null && $deviceidtype!==null && $licence_id!==null && $payment_mode_id!==null)
	{
		log::info('------temp-----a----- ');
		$dev=$redis->hget('H_Device_Cpy_Map',$deviceid);
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		$back=$redis->hget($vehicleDeviceMapId, $deviceid);
		$back = $redis->sismember('S_Vehicles_' . $fcode, $vehicleId);
		$back1 = $redis->sismember('S_KeyVehicles', $vehicleId);
		log::info('------keyvehicle---------- '.$back1);
		if($back==1)
		{	
			$vehicleIdarray=array_add($vehicleIdarray,$vehicleId,$vehicleId);
		}
		if($back1==1)
		{	
			$KeyVehicles=array_add($KeyVehicles,$vehicleId,$vehicleId);
		}
		if(Session::get('cur')=='dealer')
		{					
			$tempdev=$redis->hget('H_Pre_Onboard_Dealer_'.$username.'_'.$fcode,$deviceid);
		}
		else if(Session::get('cur')=='admin')
		{
			$tempdev=$redis->hget('H_Pre_Onboard_Admin_'.$fcode,$deviceid);
		}
		if($dev==null && $tempdev==null && $back==0 && $back1==0)
		{
			log::info('------temp---------- ');
			$count++;
			$deviceDataArr = array (
				'deviceid' => $deviceid,
				'deviceidtype' => $deviceidtype
			);
			$deviceDataJson = json_encode ( $deviceDataArr );
			
			$deviceDataArr = array (
					'deviceid' => $deviceid,
					'deviceidtype' => $deviceModel,
				);
			$deviceDataJson = json_encode ( $deviceDataArr );
			$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
			$back=$redis->hget($vehicleDeviceMapId, $deviceid);
			if($back!==null)
			{
				log::info('------temp---------- ');
				$vehicleId=$back;
			}
			else
			{
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

					$v=idate("d") ;
					$monthTemp=idate("m") ;
					//log::info($monthTemp.'------monthTemp---------- ');
					$paymentmonth=11;
					if($v>15)
					{
						log::info('inside if');
						$paymentmonth=$paymentmonth+1;		
					}
					if($monthTemp==1)
					{
						if($v==29 || $v==30 || $v==31)
						{
							$paymentmonth=0;	
							$new_date = 'February '.(date('Y', strtotime("0 month"))+1);
							$new_date2 = 'February'.(date('Y', strtotime("0 month"))+1);
							//log::info($new_date.'------new_date feb---------- '.$new_date2);
						}
					}
					for ($m = 1; $m <=$paymentmonth; $m++){

						$new_date = date('F Y', strtotime("$m month"));
							$new_date2 = date('FY', strtotime("$m month"));
							//log::info($new_date.'------ownership---------- '.$m);
						}
						$new_date1 = date('F d Y', strtotime("+0 month"));
						$refDataArr = array (
								'deviceId' => $deviceid,					
								'deviceModel' => $deviceModel,
								'shortName' => $shortName,
								'regNo' => $regNo,
								'orgId'=>$orgId,
								'vehicleType' => $vehicleType,
								'oprName' => $oprName,
								'mobileNo' => $mobileNo,
								'odoDistance' => $odoDistance,
								'gpsSimNo' => $gpsSimNo,
								'date' =>$new_date1,
								'paymentType'=>'yearly',
								'OWN'=>$ownerShip,
								'expiredPeriod'=>$new_date,					
								'overSpeedLimit' => $overSpeedLimit,					
								'driverName' => $driverName,					
								'email' => $email,
								//'vehicleExpiry' => $vehicleExpiry,
								'altShortName'=>$altShortName,
								'sendGeoFenceSMS' => $sendGeoFenceSMS,
								'morningTripStartTime' => $morningTripStartTime,
								'eveningTripStartTime' => $eveningTripStartTime,
								'parkingAlert' => 'no',
								'vehicleMake' => '',
								'Licence'=>$Licence,
								'Payment_Mode'=>$Payment_Mode,
								'descriptionStatus'=>$descriptionStatus,
								'vehicleExpiry' => $vehicleExpiry,
								'onboardDate' => $onboardDate,
							);
						$refDataJson = json_encode ( $refDataArr );
						//log::info('json data --->'.$refDataJson);
						$expireData=$redis->hget ( 'H_Expire_' . $fcode, $new_date2);
						$redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );
					    $orgId1=strtoupper($orgId);
					    $redis->hset ( 'H_VehicleName_Mobile_Org_' .$fcode, $vehicleId.':'.$deviceid.':'.$shortName.':'.$orgId1.':'.$gpsSimNo, $vehicleId );
						///$redis->hset('H_Vehicle_Map_Uname_' . $fcode, $vehicleId.'/'.$groupId . ':' . $fcode, $userId);
						$cpyDeviceSet = 'S_Device_' . $fcode;
						$redis->sadd ( $cpyDeviceSet, $deviceid );
						$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
						$redis->hmset ( $vehicleDeviceMapId, $vehicleId , $deviceid, $deviceid, $vehicleId );		
						$redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );
						$redis->hset('H_Device_Cpy_Map',$deviceid,$fcode);
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
						$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
						$franchiseDetails=json_decode($franDetails_json,true);
						$tmpPositon =  '13.104870,80.303138,0,N,' . $time . ',0.0,N,N,ON,' .$odoDistance. ',S,N';
						if(isset($franchiseDetails['fullAddress'])==1)
						{
							$fullAddress=$franchiseDetails['fullAddress'];
							$data_arr = BusinessController::geocode($fullAddress);
						  if($data_arr){         
								$latitude = $data_arr[0];
								$longitude = $data_arr[1];
								
								$tmpPositon =  $latitude.','.$longitude.',0,N,' . $time . ',0.0,N,N,ON,' .$odoDistance. ',S,N';
						  }
						}	
						$redis->hset ( 'H_ProData_' . $fcode, $vehicleId, $tmpPositon );
				}
				$shortName=str_replace(' ', '', $shortName);
				if($ownerShip!=='OWN')
				{
					$redis->sadd('S_Vehicles_Dealer_'.$ownerShip.'_'.$fcode,$vehicleId);
					$redis->srem('S_Vehicles_Admin_'.$fcode,$vehicleId);
					$redis->hset('H_VehicleName_Mobile_Dealer_'.$ownerShip.'_Org_'.$fcode, $vehicleId.':'.$deviceid.':'.$shortName.':'.$orgId1.':'.$gpsSimNo, $vehicleId );
				}
				else if($ownerShip=='OWN')
				{
					$redis->sadd('S_Vehicles_Admin_'.$fcode,$vehicleId);
					$redis->srem('S_Vehicles_Dealer_'.$ownerShip.'_'.$fcode,$vehicleId);
					$redis->hset('H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode, $vehicleId.':'.$deviceid.':'.$shortName.':'.$orgId1.':'.$gpsSimNo.':OWN', $vehicleId );
				}		
				$details=$redis->hget('H_Organisations_'.$fcode,$organizationId);
				//Log::info($details.'before '.$ownerShip);



				if($type=='Sale')
				{
					// DB::table('Vehicle_details')->insert(
					//     array('vehicle_id' => $vehicleId, 
					//     	'fcode' => $fcode,
					//     	'sold_date' =>Carbon::now(),
					//     	'renewal_date'=>Carbon::now(),
					//     	'sold_time_stamp' => round(microtime(true) * 1000),
					//     	'month' => date('m'),
					//     	'year' => date('Y'),
					//     	'payment_mode_id' => $payment_mode_id,
					//     	'licence_id' => $licence_id,
					//     	'belongs_to'=>'OWN',
					//     	'device_id'=>$deviceid,
					//     	'status'=>$descriptionStatus)
					// );

					$dbarray[$dbtemp++]=array('vehicle_id' => $vehicleId, 
					    	'fcode' => $fcode,
					    	'sold_date' =>Carbon::now(),
					    	'renewal_date'=>Carbon::now(),
					    	'sold_time_stamp' => round(microtime(true) * 1000),
					    	'month' => date('m'),
					    	'year' => date('Y'),
					    	'payment_mode_id' => $payment_mode_id,
					    	'licence_id' => $licence_id,
					    	'belongs_to'=>'OWN',
					    	'device_id'=>$deviceid,
					    	'status'=>$descriptionStatus,
					    	'orgId'=>$orgId);

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
							'vehicleExpiry' => '',
							'onboardDate' => '',
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
					
					$groupname1      = Input::get('groupname');
					$groupname       = strtoupper($groupname1);
					log::info($userId.'-------------- groupname- out-------------'.$groupId);
					if($type1=='existing' && $groupname!==null && $groupname!=='')
					{
						
						$groupId=explode(":",$groupname)[0];
						log::info('-------------- groupname--------------'.$groupId);
					}

					$redis->sadd($groupId1 . ':' . $fcode1,$vehicleId);
					
				}
				if(Session::get('cur')=='dealer')
				{					
					$redis->srem('S_Pre_Onboard_Dealer_'.$username.'_'.$fcode,$deviceid);
					$redis->hdel('H_Pre_Onboard_Dealer_'.$username.'_'.$fcode,$deviceid);
				}
				else if(Session::get('cur')=='admin')
				{
					$redis->srem('S_Pre_Onboard_Admin_'.$fcode,$deviceid);
					$redis->hdel('H_Pre_Onboard_Admin_'.$fcode,$deviceid);
					if($ownerShip!=='OWN')
					{
						$redis->sadd('S_Pre_Onboard_Dealer_'.$ownerShip.'_'.$fcode,$deviceid);
						$redis->hset('H_Pre_Onboard_Dealer_'.$ownerShip.'_'.$fcode,$deviceid,$deviceDataJson);
					}
					
				}								
		
	}


	if($dev==null && $tempdev==null)
	{

	}
	else{
	log::info('--------------already present--------------'.$deviceid);

	$deviceidarray=array_add($deviceidarray,$deviceid,$deviceid);

	}

	}
	$deviceid=null;
	$deviceidtype=null;



}
log::info('--------------count($dbarray--------------'.count($dbarray));
if(count($dbarray)!==0)
{
	
	log::info('--------------before--------------'.time());
	DB::table('Vehicle_details')->insert(
					    $dbarray
					);

	log::info('--------------after--------------'.time());
}




if($type=='Sale' )
{
	
	log::info( '------sale-2--------- '.$ownerShip);
	$redis->sadd('S_Groups_' . $fcode, $groupId1 . ':' . $fcode1);
	if($ownerShip!='OWN')
	{
		log::info( '------login 1---------- '.Session::get('cur'));
		$redis->sadd('S_Groups_Dealer_'.$ownerShip.'_'.$fcode,$groupId1 . ':' . $fcode1);
		$redis->sadd('S_Users_Dealer_'.$ownerShip.'_'.$fcode,$userId);
	}
	else if($ownerShip=='OWN')
	{
		$redis->sadd('S_Groups_Admin_'.$fcode,$groupId1 . ':' . $fcode1);
		$redis->sadd('S_Users_Admin_'.$fcode,$userId);
	}
	$redis->sadd ( $userId, $groupId1 . ':' . $fcode1 );
	$redis->sadd ( 'S_Users_' . $fcode, $userId );				
	if(Session::get('cur')=='dealer')
	{
		log::info( '------login 1---------- '.Session::get('cur'));
		$OWN=$username;
	}
	else if(Session::get('cur')=='admin')
	{
		$OWN='admin';
	}
						
	if($type1=='new')
	{
		log::info( '------sale--3-------- '.$ownerShip);
		$password=Input::get ( 'password' );
		if($password==null)
		{
			$password='awesome';
		}
		$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo', $mobileNoUser,$userId.':email',$emailUser ,$userId.':password',$password,$userId.':OWN',$OWN);						
		$user = new User;	
		$user->name = $userId;
		$user->username=$userId;
		$user->email=$emailUser;
		$user->mobileNo=$mobileNoUser;
		$user->password=Hash::make($password);
		$user->save();

		//mobile number dont need user
		// foreach($mobArr as $mob)
		// {					 
		// 	if($mob!=='')
		// 	{
		// 		log::info( '------mobile number---------- '.$mob);
		// 	  if($ownerShip!='OWN')
  //              {
  //                      log::info( '------login 1---------- '.Session::get('cur'));
  //                      $redis->sadd('S_Users_Dealer_'.$ownerShip.'_'.$fcode,$mob);
  //              }
		// 	  else if($ownerShip=='OWN')
		// 		{
		// 				$redis->sadd('S_Users_Admin_'.$fcode,$mob);
		// 		}
		// 	  log::info(' mobile number saved successfully');
		// 	  $redis->sadd ( $mob, $groupId . ':' . $fcode );
  //             $redis->sadd ( 'S_Users_' . $fcode, $mob );   
		// 	  $password=Input::get ( 'password' );
		// 		if($password==null)
		// 		{
		// 				$password='awesome';
		// 		}
		// 		$redis->hmset ( 'H_UserId_Cust_Map', $mob . ':fcode', $fcode, $mob . ':mobileNo', $mobileNo,$mob.' :email',$email,$mob.':password',$password,$mob.':OWN',$OWN);

		// 					$user = new User;

		// 					$user->name = $mob;
		// 					$user->username=$mob;
		// 					$user->email=$email;
		// 					$user->mobileNo=$mobileNo;
		// 					$user->password=Hash::make($password);
		// 					$user->save();
		// 		}
  //           }					
		}
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
		if(count($vehicleIdarray)!==0 || count($deviceidarray)!==0 || count($KeyVehicles)!==0 )
		{
			$error2=' ';
			$error=' ';
			$error3=' ';
			if($deviceidarray!==null)
			{
				$error=implode(",",$deviceidarray);
			}
			if($vehicleIdarray!==null)
			{
				$error2=implode(",", $vehicleIdarray);
			}
			if($KeyVehicles!==null)
            {
        	    $error3=implode(",", $KeyVehicles);
            }
		
			$error='These names are already exist  '.$error.' '.$error2.' '.$error3;
		}
		else
		{
			$error='successfully Added';
		}
$redis = Redis::connection ();
$parameters = 'fcode='.$fcode . '&expiryDate='.$vehicleExpiry. '&vehicleId='.$vehicleId;
//TODO - remove ..this is just for testing
// $ipaddress = 'localhost';
    $ipaddress = $redis->get('ipaddress');
    $url = 'http://' .$ipaddress . ':9000/getVehicleExpiryDetailsUpdate?' . $parameters;
    $url=htmlspecialchars_decode($url);
    log::info( ' url :' . $url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
// Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

return Redirect::to ( 'Business' )->withErrors($error);


	}
	public function deviceDetails() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
								
		$devicesList=$redis->smembers( 'S_Device_' . $fcode);
		log::info( '------device list size---------- '.count($devicesList));
		$temp=0;
		$deviceMap=array();
		for($i =0;$i<count($devicesList);$i++){
			$vechicle=$redis->hget ( 'H_Vehicle_Device_Map_' . $fcode, $devicesList[$i] );
			$deviceMap = array_add($deviceMap,$i,$vechicle.','.$devicesList[$i]);
			$temp++;
		}
		log::info( '------device map---------- '.count($deviceMap));
		return View::make ( 'vdm.business.device', array (
				'deviceMap' => $deviceMap ) );
		
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
			$fcode1=strtoupper($fcode);
			$deviceList      = Input::get('vehicleList');
			$deviceType      = Input::get('deviceType');
			$ownerShip      = Input::get('dealerId');
			$userId      = Input::get('userId');
			$userId1    = strtoupper($userId);
			$mobileNo      = Input::get('mobileNo');
			$email      = Input::get('email');
			$password      = Input::get('password');
			$type      = Input::get('type');
			$type1      = Input::get('type1');
			log::info($ownerShip.'type ----------->'.$type1);
			log::info($ownerShip.'valuse ----------->'.Input::get('userIdtemp'));
			if($type1=='existing')
			{
				$userId      = Input::get('userIdtemp');
				if($userId==null || $userId=='select')
				{
					return Redirect::to ( 'Business' )->withErrors ( 'Invalid user Id' );
				}
				
				
			}
			
			
				if($type1==null)
				{
					return Redirect::to ( 'Business' )->withErrors ( 'select the sale' );
				}
					$type='Sale';
					$ownerShip = $username;
					    $mobArr = explode(',', $mobileNo);
			
			if( $type1==null)
			{
				return Redirect::to ( 'Business' )->withErrors ( 'Select the user' );
			}
			 if($type1=='new')
            {
                if(Session::get('cur')=='dealer'){
                    $totalReports = $redis->smembers('S_Users_Reports_Dealer_'.$username.'_'.$fcode);
                }
                if($totalReports != null)
                {
                   foreach ($totalReports as $key => $value) {
                   $redis-> sadd('S_Users_Reports_'.$userId.'_'.$fcode, $value);
                  }
                }
        log::info($ownerShip.'3----a------->'.Session::get('cur'));
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
					  $valOrg= $redis->sismember('S_Organisations_'. $fcode, $userId);	
					   $valOrg1=$redis->sismember('S_Organisations_Admin_'.$fcode,$userId);
					   $valGroup=$redis->sismember('S_Groups_' . $fcode, $userId1 . ':' . $fcode1);
					   $valGroup1=$redis->sismember('S_Groups_Admin_'.$fcode,$userId1 . ':' . $fcode1);
				}
				if($valGroup==1 || $valGroup1==1 ) {
					log::info('id group exist '.$userId);
					return Redirect::to ( 'Business' )->withErrors ( 'Name already exist' );
				}
				if($valOrg==1 || $valOrg1==1 ) {
					log::info('id org exist '.$userId);
					return Redirect::to ( 'Business' )->withErrors ( 'Name already exist' );
				}
				if($val1==1 || isset($val)) {
					log::info('id already exist '.$userId);
					return Redirect::to ( 'Business' )->withErrors ( 'User Id already exist' );
				}
				if (strpos($userId, 'admin') !== false || strpos($userId, 'ADMIN') !== false) {
					return Redirect::to ( 'Business' )->withErrors ( 'Name with admin not acceptable' );
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
			$groupId0=$orgId;
			$groupId=strtoupper($groupId0);
			if($ownerShip=='OWN' && $type!='Sale')
			{
				$orgId='Default';
				
			}
			if($userId==null)
			{
				$orgId='Default';
				$organizationId='Default';
			}
			if($type=='Sale' && $type1!=='new')
			{
				$orgId=Input::get ( 'orgId');
				$orgId=!empty($orgId) ? $orgId : 'Default';
				//$organizationId=$orgId;
			}
			if($deviceList!=null)
			{
				$temp=0;
				$dbarray=array();
				$dbtemp=0;
					foreach($deviceList as $device) {
					log::info( '------ownership---------- '.$ownerShip);
					$myArray = explode(',', $device);
					$vehicleId='GPSVTS_'.substr($myArray[0], -6);
					$deviceId=$myArray[0];
					$deviceDataArr = array (
							'deviceid' => $deviceId,
							'deviceidtype' => $myArray[1],
						);
						$deviceDataJson = json_encode ( $deviceDataArr );
					$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
					$back=$redis->hget($vehicleDeviceMapId, $deviceId);
							if($back!==null)
							{
								$vehicleId=$back;


								$refDataJson1=$redis->hget ( 'H_RefData_' . $fcode, $vehicleId);
        						$refDataJson1=json_decode($refDataJson1,true);
        						$temOrg=isset($refDataJson1['orgId'])?$refDataJson1['orgId']:'default';
						



				$licence_id = DB::select('select licence_id from Licence where type = :type', ['type' => isset($refDataJson1['Licence'])?$refDataJson1['Licence']:'Advance']);
				$payment_mode_id = DB::select('select payment_mode_id from Payment_Mode where type = :type', ['type' => isset($refDataJson1['Payment_Mode'])?$refDataJson1['Payment_Mode']:'Monthly']);
						log::info( $licence_id[0]->licence_id.'-------- av  in  ::----------'.$payment_mode_id[0]->payment_mode_id);
		
				$licence_id=$licence_id[0]->licence_id;
				$payment_mode_id=$payment_mode_id[0]->payment_mode_id;
try{

	// DB::table('Vehicle_details')->insert(
	// 				    array('vehicle_id' => $vehicleId, 
	// 				    	'fcode' => $fcode,
	// 				    	'sold_date' =>Carbon::now(),
	// 				    	'renewal_date'=>Carbon::now(),
	// 				    	'sold_time_stamp' => round(microtime(true) * 1000),
	// 				    	'month' => date('m'),
	// 				    	'year' => date('Y'),
	// 				    	'payment_mode_id' => $payment_mode_id,
	// 				    	'licence_id' => $licence_id,
	// 				    	'belongs_to'=>$username,
	// 				    	'device_id'=>$deviceId,
	// 				    	'status'=>isset($refDataJson1['descriptionStatus'])?$refDataJson1['descriptionStatus']:'')
	// 				);
$dbarray[$dbtemp++]= array('vehicle_id' => $vehicleId, 
					    	'fcode' => $fcode,
					    	'sold_date' =>Carbon::now(),
					    	'renewal_date'=>Carbon::now(),
					    	'sold_time_stamp' => round(microtime(true) * 1000),
					    	'month' => date('m'),
					    	'year' => date('Y'),
					    	'payment_mode_id' => $payment_mode_id,
					    	'licence_id' => $licence_id,
					    	'belongs_to'=>$username,
					    	'device_id'=>$deviceId,
					    	'orgId'=>$orgId,
					    	'status'=>isset($refDataJson1['descriptionStatus'])?$refDataJson1['descriptionStatus']:'');

        						// if($temOrg=='Default' || $temOrg=='default')
        }catch(\Exception $e)
        {

        }						// {// ram testing


			 $refDataArr = array (
			            'deviceId' => $deviceId,
			            'shortName' => isset($refDataJson1['shortName'])?$refDataJson1['shortName']:'',
			            'deviceModel' => isset($refDataJson1['deviceModel'])?$refDataJson1['deviceModel']:'GT06N',
			            'regNo' => isset($refDataJson1['regNo'])?$refDataJson1['regNo']:'XXXXX',
			            'vehicleMake' => isset($refDataJson1['vehicleMake'])?$refDataJson1['vehicleMake']:' ',
			            'vehicleType' =>  isset($refDataJson1['vehicleType'])?$refDataJson1['vehicleType']:'Bus',
			            'oprName' => isset($refDataJson1['oprName'])?$refDataJson1['oprName']:'airtel',
			            'mobileNo' =>isset($refDataJson1['mobileNo'])?$refDataJson1['mobileNo']:'0123456789',
			            'overSpeedLimit' => isset($refDataJson1['overSpeedLimit'])?$refDataJson1['overSpeedLimit']:'60',
			            'odoDistance' => isset($refDataJson1['odoDistance'])?$refDataJson1['odoDistance']:'0',
			            'driverName' => isset($refDataJson1['driverName'])?$refDataJson1['driverName']:'XXX',
			            'gpsSimNo' => isset($refDataJson1['gpsSimNo'])?$refDataJson1['gpsSimNo']:'0123456789',
			            'email' => isset($refDataJson1['email'])?$refDataJson1['email']:' ',
			            'orgId' =>$orgId,
			            'sendGeoFenceSMS' => isset($refDataJson1['sendGeoFenceSMS'])?$refDataJson1['sendGeoFenceSMS']:'no',
			            'morningTripStartTime' => isset($refDataJson1['morningTripStartTime'])?$refDataJson1['morningTripStartTime']:' ',
			            'eveningTripStartTime' => isset($refDataJson1['eveningTripStartTime'])?$refDataJson1['eveningTripStartTime']:' ',
			            'parkingAlert' => isset($refDataJson1['parkingAlert'])?$refDataJson1['parkingAlert']:'no',
			            'altShortName'=>isset($refDataJson1['altShortName'])?$refDataJson1['altShortName']:'',
			            'date' =>date('F d Y', strtotime("+0 month")),
			            'paymentType'=>isset($refDataJson1['paymentType'])?$refDataJson1['paymentType']:' ',
			            'expiredPeriod'=>isset($refDataJson1['expiredPeriod'])?$refDataJson1['expiredPeriod']:' ',
			            'fuel'=>isset($refDataJson1['fuel'])?$refDataJson1['fuel']:'no',
			            'fuelType'=>isset($refDataJson1['fuelType'])?$refDataJson1['fuelType']:' ',
			            'isRfid'=>isset($refDataJson1['isRfid'])?$refDataJson1['isRfid']:'no',
			            'OWN'=>$ownerShip,

 						'Licence'=>isset($refDataJson1['Licence'])?$refDataJson1['Licence']:'',
           				 'Payment_Mode'=>isset($refDataJson1['Payment_Mode'])?$refDataJson1['Payment_Mode']:'',
           				 'descriptionStatus'=>isset($refDataJson1['descriptionStatus'])?$refDataJson1['descriptionStatus']:'',
						'vehicleExpiry'=>isset($refDataJson1['vehicleExpiry'])?$refDataJson1['vehicleExpiry']:'',
                        'onboardDate'=>isset($refDataJson1['onboardDate'])?$refDataJson1['onboardDate']:'',
			            );

			        $refDataJson = json_encode ( $refDataArr );
			        
			       // $redis->hdel ( 'H_RefData_' . $fcode, $vehicleIdOld );
			        $redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );

//}ram testing

							}
									log::info( '------login 1---------- '.Session::get('cur'));
									$redis->sadd('S_Vehicles_Dealer_'.$ownerShip.'_'.$fcode,$vehicleId);
									$redis->srem('S_Vehicles_Admin_'.$fcode,$vehicleId);
                                 $refDataJson=json_decode($refDataJson,true);
                                 $shortName1=isset($refDataJson['shortName'])?$refDataJson['shortName']:'default';
                                 $mobileNo1=isset($refDataJson['mobileNo'])?$refDataJson['mobileNo']:'0123456789';
								 $gpsSimNo1=isset($refDataJson['gpsSimNo1'])?$refDataJson['gpsSimNo1']:'0123456789';
                                 $orgIdOld1=isset($refDataJson['orgId'])?$refDataJson['orgId']:'default';
                                 $orgIdOld=strtoupper($orgIdOld1);
                                 $orgId1=strtoupper($orgId);
                                 $redis->hset('H_VehicleName_Mobile_Dealer_'.$ownerShip.'_Org_'.$fcode, $vehicleId.':'.$deviceId.':'.$shortName1.':'.$orgId1.':'.$gpsSimNo1, $vehicleId );
                                 $redis->hdel('H_VehicleName_Mobile_Dealer_'.$ownerShip.'_Org_'.$fcode, $vehicleId.':'.$deviceId.':'.$shortName1.':DEFAULT:'.$gpsSimNo1, $vehicleId );								 
								/// $redis->hdel('H_Vehicle_Map_Uname_' . $fcode, $vehicleId.'/:'.$fcode);
								/// $redis->hset('H_Vehicle_Map_Uname_' . $fcode, $vehicleId.'/'.$groupId . ':' . $fcode, $userId);				
												$details=$redis->hget('H_Organisations_'.$fcode,$organizationId);
												Log::info($details.'before '.$ownerShip);
												if($type=='Sale' && $type1=='new' && $organizationId!=='default' && $organizationId!=='Default')
												{
													if($details==null)
													{
														Log::info('new organistion going to create');
										$redis->sadd('S_Organisations_'. $fcode, $organizationId);	
										log::info( '------login ---------- '.Session::get('cur'));
										$redis->sadd('S_Organisations_Dealer_'.$ownerShip.'_'.$fcode,$organizationId);
												
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
									


												$groupname1      = Input::get('groupname');
												$groupname       = strtoupper($groupname1);
											log::info($userId.'-------------- groupname- out-------------'.$groupId);
											if($type1=='existing' && $groupname!==null && $groupname!=='')
											{
												
												$groupId1=explode(":",$groupname)[0];
												$groupId=strtoupper($groupId1);
												log::info('-------------- groupname--------------'.$groupId);
											}

											$redis->sadd($groupId1 . ':' . $fcode1,$vehicleId);


												
											}
														
								$redis->srem('S_Pre_Onboard_Dealer_'.$username.'_'.$fcode,$deviceId);
								$redis->hdel('H_Pre_Onboard_Dealer_'.$username.'_'.$fcode,$deviceId);
							
					$temp++;
				}
				
log::info('-------------- count($dbarray)--------------'.count($dbarray));
if(count($dbarray)!==0)
{
	DB::table('Vehicle_details')->insert(
					    $dbarray
					);
}

					if($type=='Sale' )
					{
						log::info( '------sale-2--------- '.$ownerShip);
						
							$redis->sadd('S_Groups_' . $fcode, $groupId . ':' . $fcode1);
							log::info( '------login 1---------- '.Session::get('cur'));
							$redis->sadd('S_Groups_Dealer_'.$ownerShip.'_'.$fcode,$groupId . ':' . $fcode1);
							$redis->sadd('S_Users_Dealer_'.$ownerShip.'_'.$fcode,$userId);
							
						$redis->sadd ( $userId, $groupId . ':' . $fcode1 );
						$redis->sadd ( 'S_Users_' . $fcode, $userId );
							$OWN=$username;
						
						if($type1=='new')
						{
							log::info( '------sale--3-------- '.$ownerShip);
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
							///mobile number dont need user
							//  foreach($mobArr as $mob){
								 
							// 	 if($mob!=='')
							// {
								
							
								 
							// log::info( '------mobile number---------- '.$mob);
       //                             log::info( '------login 1---------- '.Session::get('cur'));
       //                             $redis->sadd('S_Users_Dealer_'.$ownerShip.'_'.$fcode,$mob);
                           
						 //  log::info(' mobile number saved successfully');
						 //  $redis->sadd ( $mob, $groupId . ':' . $fcode );
       //                    $redis->sadd ( 'S_Users_' . $fcode, $mob ); 
					    
						 //  $password=Input::get ( 'password' );
							// if($password==null)
							// {
							// 		$password='awesome';
							// }

							// $redis->hmset ( 'H_UserId_Cust_Map', $mob . ':fcode', $fcode, $mob . ':mobileNo', $mobileNo,$mob.' :email',$email,$mob.':password',$password,$mob.':OWN',$OWN);

							// $user = new User;

							// $user->name = $mob;
							// $user->username=$mob;
							// $user->email=$email;
							// $user->mobileNo=$mobileNo;
							// $user->password=Hash::make($password);
							// $user->save();
							// }
       //                   }
							
						}
                            
							

					     

					}
				
				
			}
			
 			return Redirect::to('Business');
	 		
	}
	
	
	public static function geocode($address){
 
    // url encode the address
try{


    $address = urlencode($address);
     
    // google map geocode api url
    $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){
 
        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }else{
        return false;
    }
     }catch(\Exception $e)
    {
         return false;
    }
}
	
	    protected function schedule(Schedule $schedule){
           	$schedule->call(function () {
            //DB::table('recent_users')->delete();
        	})->everyMinute();
        }
	
	}
