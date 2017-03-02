<?php
class AddSiteController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		
/*		log::info('------inside show---------- ::');
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$ipaddress = $redis->get('ipaddress');		
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );  
		$url = 'http://' .$ipaddress . ':9000/viewSite?userId=' .$username . '&fcode=' . $fcode;	 
		 $ch = curl_init();
		$url=htmlspecialchars_decode($url);
		log::info( ' url :' . $url);		 
		  curl_setopt($ch, CURLOPT_URL, $url);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		  $response = curl_exec($ch);
			 $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		  curl_close($ch);
		  $sitesJson = json_decode($response,true);
	    log::info( ' ------------check----------- :');
		if(!$sitesJson['error']==null)
		{
			 log::info( ' ---------inside null--------- :');
			return Redirect::to ( 'sites');
		}
		$Sites=array();





	$siteArray=$sitesJson['siteParent'];
		log::info(' ---------inside --------- :'.count($siteArray));
		foreach($siteArray as $org => $rowId)
		{
			
			foreach($rowId as $or => $row)
			{
				foreach($row as $orT => $ro)
				{
					foreach($ro as $orT1 => $ro1)
					{
						$Sites=array_add($Sites, $orT1,json_encode ( $ro1 ));
					}
					log::info($or. ' ---------inside3 --------- :'.json_encode ($ro));
					
				}
				 
			}
			
		}
		$orgArray=$sitesJson['orgIds'];
		$orgArr=array();
		foreach($orgArray as $temp => $rowIdTemp)
		{
			
				
					log::info($temp. ' ---------inside --------- :'.$rowIdTemp);
					$orgArr=array_add($orgArr, $temp,$rowIdTemp);
				
			

	}*/
		return View::make ( 'vdm.saveSite.siteDetails'
				
		) ;
	}
	
	
	
	
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function destroy($id,$orgId) {		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$ipaddress = $redis->get('ipaddress');		
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' ); 
		$url = 'http://' .$ipaddress . ':9000/deleteSite?userId=' .$username . '&fcode=' . $fcode. '&orgId=' . $orgId. '&siteName=' . $id;	 
		 $ch = curl_init();
		$url=htmlspecialchars_decode($url);
		log::info( ' url :' . $url);		 
		  curl_setopt($ch, CURLOPT_URL, $url);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		  $response = curl_exec($ch);
			 $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		  curl_close($ch);
		  log::info('response '.$response);
		  
	}
	
	
	
	 
	public function store() {
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$ipaddress = $redis->get('ipaddress');
		
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );        
        $siteName = Input::get ( 'siteName' );
		$siteType = Input::get ( 'siteType' );
		$latLng = Input::get ( 'latLng' );
		 log::info($username. '------site name---------- ::'.$siteName);
		$rules = array (
				'siteName' => 'required|alpha_dash',
				'siteType' => 'required|alpha_dash',				
		);
		
		
        log::info($latLng[0]. '------site name---------- ::'.$siteName);
		log::info(count($latLng). '------site type---------- ::'.$siteType);
		$orgId=Input::get ( 'org' );
		$ch = curl_init();
		$siteType= curl_escape($ch,$siteType);
		$url = 'http://' .$ipaddress . ':9000/saveSite?latLng=' . implode(",",$latLng) . '&fcode=' . $fcode . '&orgId=' .$orgId . '&siteName=' .rawurlencode($siteName).'&siteType='.$siteType.'&userId='.$username;
		 
		
		$url=htmlspecialchars_decode($url);
		//urlencode($url);
		log::info( ' url :' . $url);
    
		 
		  curl_setopt($ch, CURLOPT_URL, $url);
			// Include header in result? (0 = yes, 1 = no)
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		  $response = curl_exec($ch);
			 $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		  curl_close($ch);
		log::info( ' response :' . $response);
         log::info( 'finished');
	}
	
	
	public function update() {
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$ipaddress = $redis->get('ipaddress');
		
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );        
        $siteName = Input::get ( 'siteName' );
		$siteType = Input::get ( 'siteType' );
		$siteNameOld=Input::get ( 'siteNameOld' );
		$latLng = Input::get ( 'latLng' );
		 log::info($username. '------site name---------- ::'.$siteName);
		$rules = array (
				'siteName' => 'required|alpha_dash',
				'siteType' => 'required|alpha_dash',
				'siteNameOld' => 'required|alpha_dash',				
		);
		
		
        log::info($latLng[0]. '------site name---------- ::'.$siteName);
		 log::info(count($latLng). '------site type---------- ::'.$siteType);
		 $orgId=Input::get ( 'org' );
		 $ch = curl_init();
		 $siteType= curl_escape($ch,$siteType);
		 $url = 'http://' .$ipaddress . ':9000/saveSite?latLng=' . implode(",",$latLng) . '&fcode=' . $fcode . '&orgId=' .$orgId . '&siteName=' .rawurlencode($siteName).'&siteType='.$siteType.'&userId='.$username.'&type='.'update'.'&siteNameOld='.rawurlencode($siteNameOld);
		 
		
		$url=htmlspecialchars_decode($url);
		//urlencode($url);
		log::info( ' url :' . $url);
    
		 
		  curl_setopt($ch, CURLOPT_URL, $url);
			// Include header in result? (0 = yes, 1 = no)
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		  $response = curl_exec($ch);
			 $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		  curl_close($ch);
		log::info( ' response :' . $response);
         log::info( 'finished');
	}
	public function delete() {
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$ipaddress = $redis->get('ipaddress');
		
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );        
        $siteName = Input::get ( 'siteName' );
		$orgId = Input::get ( 'org' );
		
		 log::info($username. '------site name---------- ::'.$siteName);
		$rules = array (
				'siteName' => 'required|alpha_dash',					
		);       
		 $ch = curl_init();
		 //$siteType= curl_escape($ch,$siteType);
		 $url = 'http://' .$ipaddress . ':9000/deleteSite?fcode=' . $fcode . '&orgId=' .$orgId . '&siteName=' .rawurlencode($siteName).'&userId='.$username;		
		$url=htmlspecialchars_decode($url);
		//urlencode($url);
		log::info( ' url :' . $url);		 
		  curl_setopt($ch, CURLOPT_URL, $url);
			// Include header in result? (0 = yes, 1 = no)
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		  $response = curl_exec($ch);
			 $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		  curl_close($ch);
		log::info( ' response :' . $response);
         log::info( 'finished');
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function show($id) {
		 Log::info(' inside show....');
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
	
		
		$redis = Redis::connection ();
		$deviceId = $id;
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		$deviceRefData = $redis->hget ( 'H_RefData_'.$fcode , $deviceId );
		$refDataArr = json_decode ( $deviceRefData, true );
		$deviceRefData = null;
		if (is_array ( $refDataArr )) {
			foreach ( $refDataArr as $key => $value ) {
				
				$deviceRefData = $deviceRefData . $key . ' : ' . $value . ',<br/>';
			}
		} else {
			echo 'JSON decode failed';
			var_dump ( $refDataArr );
		}
		$vehicleId = $redis->hget ( $vehicleDeviceMapId, $deviceId );
		if($vehicleId==null)
		{
			return Redirect::to('vdmVehicles/dealerSearch');
		}
		
		return View::make ( 'vdm.vehicles.show', array (
				'deviceId' => $deviceId 
		) )->with ( 'deviceRefData', $deviceRefData )->with ( 'vehicleId', $vehicleId );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	
	
	
	
	public function checkPwd(){
		
		$username 	= Auth::user ()->username;
		$redis 		= Redis::connection ();
		$passWord 	= Input::get('pwd');
		$pwd 		= $redis->hget('H_UserId_Cust_Map',  $username .':password');
		if($passWord == $pwd){
			return 'correct';
		} else {
			return 'incorrect';
		}
		
	}
	
	
	   
    
}
