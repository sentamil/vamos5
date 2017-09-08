<?php
class VdmVehicleScanController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	
	 */	
public function index()
    {
        log::info(' reach the road speed function ');
        $orgLis = [];
            return View::make('vdm.vehicles.vehicleScan')->with('vehicleList', $orgLis);
    }   
public function store() {
    if (! Auth::check () ) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;

    log::info( 'User name  ::' . $username);
    Session::forget('page');
    Session::put('vCol',1);
    $redis = Redis::connection ();
    log::info( 'User 1  ::' );
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    Log::info('fcode=' . $fcode);
    if(Session::get('cur')=='dealer')
    {
        $vehicleListId='S_Vehicles_Dealer_'.$username.'_'.$fcode;
		$vehicleNameMob='H_VehicleName_Mobile_Dealer_'.$username.'_Org_'.$fcode;
    }
    else if(Session::get('cur')=='admin')
    {
        $vehicleListId='S_Vehicles_Admin_'.$fcode;
		$vehicleNameMob='H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode;
    }
    else{
        $vehicleListId = 'S_Vehicles_' . $fcode;
		$vehicleNameMob='H_VehicleName_Mobile_Org_'.$fcode;
    }
        $text_word1 = Input::get('text_word');
		$text_word = strtoupper($text_word1);
        $vehicleList = $redis->smembers ( $vehicleListId); //log::info($vehicleList);
        $cou = $redis->SCARD($vehicleListId); //log::info($cou);
		$orgLi = $redis->HScan( $vehicleNameMob, 0,  'count', $cou, 'match', '*'.$text_word.'*');
       // $orgLi = $redis->sScan( $vehicleListId, 0,  'count', $cou, 'match', $text_word); //log::info($orgLi);
        $orgL = $orgLi[1];
    $deviceList = null;
    $deviceId = null;
    $shortName =null;
    $shortNameList = null;
    $portNo =null;
    $portNoList = null;
    $mobileNo =null;
    $mobileNoList = null;
    $orgIdList = null;
    $deviceModelList = null;
    $expiredList = null;
    $statusList = null;
    foreach ( $orgL as $key => $vehicle  ) {

        Log::info($key.'$vehicle ' .$vehicle);
        $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );

        if(isset($vehicleRefData)) {
            Log::info('$vehicle ' .$vehicleRefData);
        }else {
            continue;
        }
        $vehicleRefData=json_decode($vehicleRefData,true);
        $deviceId =isset($vehicleRefData['deviceId'])?$vehicleRefData['deviceId']:'';
        //$deviceId = $vehicleRefData['deviceId'];
        if((Session::get('cur')=='dealer' &&  $redis->sismember('S_Pre_Onboard_Dealer_'.$username.'_'.$fcode, $deviceId)==0) || Session::get('cur')=='admin')
        {
            Log::info(Session::get('cur').'in side ' .$redis->sismember('S_Pre_Onboard_Dealer_'.$username.'_'.$fcode, $deviceId));
        $deviceList = array_add ( $deviceList, $vehicle,$deviceId );
        $shortName = isset($vehicleRefData['shortName'])?$vehicleRefData['shortName']:'nill';
        $shortNameList = array_add($shortNameList,$vehicle,$shortName);
        $portNo=isset($vehicleRefData['portNo'])?$vehicleRefData['portNo']:9964;
        $portNoList = array_add($portNoList,$vehicle,$portNo);
        $mobileNo=isset($vehicleRefData['gpsSimNo'])?$vehicleRefData['gpsSimNo']:99999;
        $mobileNoList = array_add($mobileNoList,$vehicle,$mobileNo);
        $orgId=isset($vehicleRefData['orgId'])?$vehicleRefData['orgId']:'Default';
        $orgIdList = array_add($orgIdList,$vehicle,$orgId);
        $deviceModel=isset($vehicleRefData['deviceModel'])?$vehicleRefData['deviceModel']:'nill';
        $deviceModelList = array_add($deviceModelList,$vehicle,$deviceModel);
        $expiredPeriod=isset($vehicleRefData['expiredPeriod'])?$vehicleRefData['expiredPeriod']:'nill';
        $expiredList = array_add($expiredList,$vehicle,$expiredPeriod);
        $statusVehicle = $redis->hget ( 'H_ProData_' . $fcode, $vehicle );
        $statusSeperate = explode(',', $statusVehicle);
        $statusList = array_add($statusList, $vehicle, $statusSeperate[7]);
        }
        else
        {
             Log::info('$inside remove ' .$vehicle);
            unset($orgL[$key]);
        }
    }
    $demo='ahan';
    $user=null;
    $user1= new VdmDealersController;
    $user=$user1->checkuser();
    $dealerId = $redis->smembers('S_Dealers_'. $fcode);
    $orgArr = array();
    foreach($dealerId as $org) {
        $orgArr = array_add($orgArr, $org,$org);
    }
    $dealerId = $orgArr;
    return View::make ( 'vdm.vehicles.vehicleScan', array (
        'vehicleList' => $orgL
        ) )->with ( 'deviceList', $deviceList )->with('shortNameList',$shortNameList)->with('portNoList',$portNoList)->with('mobileNoList',$mobileNoList)->with('demo',$demo)->with ( 'user', $user )->with ( 'orgIdList', $orgIdList )->with ( 'deviceModelList', $deviceModelList )->with ( 'expiredList', $expiredList )->with ( 'tmp', 0 )->with ('statusList', $statusList)->with('dealerId',$dealerId); 
}
/*
* Show the form for creating a new resource.
* @return Response
*/	
}