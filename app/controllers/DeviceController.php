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
				$refData 	= $redis->hget ( 'H_RefData_' . $fcode, $vechicle );
				$refData	= json_decode($refData,true);
				$orgId 		= isset($refData['OWN'])?$refData['OWN']:' ';
				// log::info(isset($refData['OWN']));
				// log::info($orgId);
				// log::info('  dealer name   ');
				$vehicleExpiry=isset($refData['vehicleExpiry'])?$refData['vehicleExpiry']:'null';
                // log::info($vehicleExpiry);
				$deviceMap 	= array_add($deviceMap,$i,$vechicle.','.$devicesList[$i].','.$orgId.','.$vehicleExpiry);
			}
			
			$temp++;
		}
		log::info( '------device map---------- '.count($deviceMap));
		return View::make ( 'vdm.business.device', array (
				'deviceMap' => $deviceMap ) );
		
	}
	
	
	}

