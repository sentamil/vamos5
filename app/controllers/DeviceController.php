<?php
class DeviceController extends \BaseController {
	
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
								
		$devicesList=$redis->smembers( 'S_Device_' . $fcode);
		log::info( '------device list size---------- '.count($devicesList));
		$temp=0;
		$deviceMap=array();
		for($i =0;$i<count($devicesList);$i++){
			$vechicle=$redis->hget ( 'H_Vehicle_Device_Map_' . $fcode, $devicesList[$i] );
			if($vechicle!==null)
			{
				$deviceMap = array_add($deviceMap,$i,$vechicle.','.$devicesList[$i]);
			}
			
			$temp++;
		}
		log::info( '------device map---------- '.count($deviceMap));
		return View::make ( 'vdm.business.device', array (
				'deviceMap' => $deviceMap ) );
		
	}
	
	
	}

