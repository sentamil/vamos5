<?php
class VdmVehicleViewController extends \BaseController {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
  public function index() {
        
        $user = new VdmVehicleController;
        $page=$user->index();
        Session::put('vCol',2);
        return $page;
    }
    
  public function move_vehicle($id) {
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $vehicleId = $id;
    $vehicle = explode(',', $vehicleId); log::info($vehicle);
    foreach($vehicle as $key => $value) {
    if($key == 0){
        $vehicleId=$value;
     }
    }
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
    $deviceId = $redis->hget ( $vehicleDeviceMapId, $vehicleId );
    $dealerId = $redis->smembers('S_Dealers_'. $fcode);
    $orgArr = array();
    foreach($dealerId as $org) {
        $orgArr = array_add($orgArr, $org,$org);
    }
    $dealerId = $orgArr;
    $ownset[]='OWN';
    $ownarray = array();
    foreach($ownset as $owq) {
       $ownarray = array_add($ownarray, $owq,$owq);
   }
   $ownset = $ownarray;

    $details = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );
    $refData = json_decode ( $details, true );
    $OWN =isset($refData['OWN'])?$refData['OWN']:'';
     log::info(' OWN = ' . $OWN);
    if($OWN!='OWN') {
      $dealerId1=$ownset;
    }
    else {
     $dealerId1=$dealerId;
    }
    $expiredPeriod =isset($refDataFromDB['expiredPeriod'])?$refDataFromDB['expiredPeriod']:'NotAvailabe';
    $expiredPeriod=str_replace(' ', '', $expiredPeriod);
    log::info( '------expiredPeriod ---------- '.$expiredPeriod);
    return View::make ( 'vdm.vehicles.move_vehicle', array (
        'vehicleId' => $vehicleId ) )->with ( 'deviceId', $deviceId )->with('expiredPeriod',$expiredPeriod)->with('dealerId',$dealerId1)->with('OWN',$OWN);

    }
  public function moveVehicleUpdate() {
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
     }

    $vehicleId = Input::get ( 'vehicleId' );
    $deviceId = Input::get ( 'deviceId' );
    $vehicleId =preg_replace('/\s+/', '', $vehicleId);
    $deviceId =preg_replace('/\s+/', '', $deviceId);
    $dealerId = Input::get ( 'dealerId' );
    $vehicleIdOld= Input::get ( 'vehicleIdOld' );
    $dealerIdOld= Input::get ( 'dealerIdOld' );  
    $deviceIdOld = Input::get ( 'deviceIdOld' );
    $expiredPeriodOld = Input::get ( 'expiredPeriodOld' );
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
///get orgId
    $detailsR = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );
    $refDataFromDBR = json_decode ( $detailsR, true );
    $orgId =isset($refDataFromDBR['orgId'])?$refDataFromDBR['orgId']:'Default';
    //log::info(' orgIdOK = ' . $orgId);
    $OWN =isset($refDataFromDBR['OWN'])?$refDataFromDBR['OWN']:'';
    //log::info(' OWN = ' . $OWN);

        //$redis->srem ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleIdOld);
       // $redis->sadd ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleId);
      
    $groupList = $redis->smembers('S_Groups_' . $fcode);
        foreach ( $groupList as $group ) {
            if($redis->sismember($group,$vehicleIdOld)==1)
            {
                $result = $redis->srem($group,$vehicleIdOld);
                //$redis->sadd($group,$vehicleId);
            }
        }
        $deviceId =isset($refDataFromDBR['deviceId'])?$refDataFromDBR['deviceId']:'';
        $orgId1=strtoupper($orgId);
        $expiredPeriod=$redis->hget('H_Expire_'.$fcode,$vehicleIdOld);
        log::info(' expire---->'.$expiredPeriodOld);
        if(!$expiredPeriod==null)
        {
            log::info('inside expire---->'.$expiredPeriod);
            $expiredPeriod=str_replace($vehicleIdOld, $vehicleIdOld, $expiredPeriod);
            $redis->hset('H_Expire_'.$fcode,$expiredPeriodOld,$expiredPeriod);
        } 
        $refDataJson1=$redis->hget ( 'H_RefData_' . $fcode, $vehicleIdOld);
        $refDataJson1=json_decode($refDataJson1,true);
        $dealerIdOld=$refDataJson1['OWN'];
        $shortName1 =isset($refDataJson1['shortName'])?$refDataJson1['shortName']:'';
        $shortName = strtoupper($shortName1);
        $gpsSimNo =isset($refDataJson1['gpsSimNo'])?$refDataJson1['gpsSimNo']:'';
        $orgId3 =isset($refDataJson1['orgId'])?$refDataJson1['orgId']:'';
        $orgId2 = strtoupper($orgId3);
        $refDataArr = array (
            'deviceId' => isset($refDataJson1['deviceId'])?$refDataJson1['deviceId']:' ',
            'shortName' => isset($refDataJson1['shortName'])?$refDataJson1['shortName']:'nill',
            'deviceModel' => isset($refDataJson1['deviceModel'])?$refDataJson1['deviceModel']:'GT06N',
            'regNo' => isset($refDataJson1['regNo'])?$refDataJson1['regNo']:'XXXXX',
            'vehicleMake' => isset($refDataJson1['vehicleMake'])?$refDataJson1['vehicleMake']:' ',
            'vehicleType' =>  isset($refDataJson1['vehicleType'])?$refDataJson1['vehicleType']:'Bus',
            'oprName' => isset($refDataJson1['oprName'])?$refDataJson1['oprName']:'airtel',
            'mobileNo' =>isset($refDataJson1['mobileNo'])?$refDataJson1['mobileNo']:'0123456789',
            'vehicleExpiry' =>isset($refDataJson1['vehicleExpiry'])?$refDataJson1['vehicleExpiry']:'null',
            'onboardDate' =>isset($refDataJson1['onboardDate'])?$refDataJson1['onboardDate']:' ',
            'overSpeedLimit' => isset($refDataJson1['overSpeedLimit'])?$refDataJson1['overSpeedLimit']:'60',
            'odoDistance' => isset($refDataJson1['odoDistance'])?$refDataJson1['odoDistance']:'0',
            'driverName' => isset($refDataJson1['driverName'])?$refDataJson1['driverName']:'XXX',
            'gpsSimNo' => isset($refDataJson1['gpsSimNo'])?$refDataJson1['gpsSimNo']:'0123456789',
            'email' => isset($refDataJson1['email'])?$refDataJson1['email']:' ',
            'orgId' =>isset($refDataJson1['orgId'])?$refDataJson1['orgId']:'Default',
            'sendGeoFenceSMS' => isset($refDataJson1['sendGeoFenceSMS'])?$refDataJson1['sendGeoFenceSMS']:'no',
            'morningTripStartTime' => isset($refDataJson1['morningTripStartTime'])?$refDataJson1['morningTripStartTime']:' ',
            'eveningTripStartTime' => 'TIMEZONE',
           // 'eveningTripStartTime' => isset($refDataJson1['eveningTripStartTime'])?$refDataJson1['eveningTripStartTime']:' ',
            'parkingAlert' => isset($refDataJson1['parkingAlert'])?$refDataJson1['parkingAlert']:'no',
            'altShortName'=>isset($refDataJson1['altShortName'])?$refDataJson1['altShortName']:'nill',
            'date' =>isset($refDataJson1['date'])?$refDataJson1['date']:' ',
            'paymentType'=>isset($refDataJson1['paymentType'])?$refDataJson1['paymentType']:' ',
            'expiredPeriod'=>isset($refDataJson1['expiredPeriod'])?$refDataJson1['expiredPeriod']:' ',
            'fuel'=>isset($refDataJson1['fuel'])?$refDataJson1['fuel']:'no',
            'fuelType'=>isset($refDataJson1['fuelType'])?$refDataJson1['fuelType']:' ',
            'isRfid'=>isset($refDataJson1['isRfid'])?$refDataJson1['isRfid']:'no',
            'rfidType'=>isset($refDataJson1['rfidType'])?$refDataJson1['rfidType']:'no',
            'OWN'=>$dealerId,
            'Licence'=>isset($refDataJson1['Licence'])?$refDataJson1['Licence']:'',
            'Payment_Mode'=>isset($refDataJson1['Payment_Mode'])?$refDataJson1['Payment_Mode']:'',
            'descriptionStatus'=>isset($refDataJson1['descriptionStatus'])?$refDataJson1['descriptionStatus']:'',
            'mintemp'=>isset($refDataJson1['mintemp'])?$refDataJson1['mintemp']:'',
            'maxtemp'=>isset($refDataJson1['maxtemp'])?$refDataJson1['maxtemp']:'',
            'safetyParking'=>isset($refDataJson1['safetyParking'])?$refDataJson1['safetyParking']:'no',

            );

        $refDataJson = json_encode ( $refDataArr );
        log::info('new array................');
        $refDataArr2 = array (
            'deviceId' => isset($refDataJson1['deviceId'])?$refDataJson1['deviceId']:' ',
            'shortName' => isset($refDataJson1['shortName'])?$refDataJson1['shortName']:'nill',
            'deviceModel' => isset($refDataJson1['deviceModel'])?$refDataJson1['deviceModel']:'GT06N',
            'regNo' => isset($refDataJson1['regNo'])?$refDataJson1['regNo']:'XXXXX',
            'vehicleMake' => isset($refDataJson1['vehicleMake'])?$refDataJson1['vehicleMake']:' ',
            'vehicleType' =>  isset($refDataJson1['vehicleType'])?$refDataJson1['vehicleType']:'Bus',
            'oprName' => isset($refDataJson1['oprName'])?$refDataJson1['oprName']:'airtel',
            'mobileNo' =>isset($refDataJson1['mobileNo'])?$refDataJson1['mobileNo']:'0123456789',
            'vehicleExpiry' =>isset($refDataJson1['vehicleExpiry'])?$refDataJson1['vehicleExpiry']:'null',
            'onboardDate' =>isset($refDataJson1['onboardDate'])?$refDataJson1['onboardDate']:' ',
            'overSpeedLimit' => isset($refDataJson1['overSpeedLimit'])?$refDataJson1['overSpeedLimit']:'60',
            'odoDistance' => isset($refDataJson1['odoDistance'])?$refDataJson1['odoDistance']:'0',
            'driverName' => isset($refDataJson1['driverName'])?$refDataJson1['driverName']:'XXX',
            'gpsSimNo' => isset($refDataJson1['gpsSimNo'])?$refDataJson1['gpsSimNo']:'0123456789',
            'email' => isset($refDataJson1['email'])?$refDataJson1['email']:' ',
            'orgId' =>isset($refDataJson1['orgId'])?$refDataJson1['orgId']:'Default',
            'sendGeoFenceSMS' => isset($refDataJson1['sendGeoFenceSMS'])?$refDataJson1['sendGeoFenceSMS']:'no',
            'morningTripStartTime' => isset($refDataJson1['morningTripStartTime'])?$refDataJson1['morningTripStartTime']:' ',
            'eveningTripStartTime' => 'TIMEZONE',
           // 'eveningTripStartTime' => isset($refDataJson1['eveningTripStartTime'])?$refDataJson1['eveningTripStartTime']:' ',
            'parkingAlert' => isset($refDataJson1['parkingAlert'])?$refDataJson1['parkingAlert']:'no',
            'altShortName'=>isset($refDataJson1['altShortName'])?$refDataJson1['altShortName']:'nill',
            'date' =>isset($refDataJson1['date'])?$refDataJson1['date']:' ',
            'paymentType'=>isset($refDataJson1['paymentType'])?$refDataJson1['paymentType']:' ',
            'expiredPeriod'=>isset($refDataJson1['expiredPeriod'])?$refDataJson1['expiredPeriod']:' ',
            'fuel'=>isset($refDataJson1['fuel'])?$refDataJson1['fuel']:'no',
            'fuelType'=>isset($refDataJson1['fuelType'])?$refDataJson1['fuelType']:' ',
            'isRfid'=>isset($refDataJson1['isRfid'])?$refDataJson1['isRfid']:'no',
            'rfidType'=>isset($refDataJson1['rfidType'])?$refDataJson1['rfidType']:'no',
            'OWN'=>'OWN',
            'Licence'=>isset($refDataJson1['Licence'])?$refDataJson1['Licence']:'',
            'Payment_Mode'=>isset($refDataJson1['Payment_Mode'])?$refDataJson1['Payment_Mode']:'',
            'descriptionStatus'=>isset($refDataJson1['descriptionStatus'])?$refDataJson1['descriptionStatus']:'',
            'mintemp'=>isset($refDataJson1['mintemp'])?$refDataJson1['mintemp']:'',
            'maxtemp'=>isset($refDataJson1['maxtemp'])?$refDataJson1['maxtemp']:'',
            'safetyParking'=>isset($refDataJson1['safetyParking'])?$refDataJson1['safetyParking']:'no',
            );

        $refDataJson2 = json_encode ( $refDataArr2 );


   // if($dealerIdOld!='OWN')
    // {
        ///ram noti
        $vehiDel=$redis->del('S_'.$vehicleIdOld.'_'.$fcode);
        ///
        $qq=$redis->sismember('S_Vehicles_Admin_'.$fcode,$vehicleIdOld);
        $oo=$redis->sismember('S_Vehicles_Dealer_'.$dealerId.'_'.$fcode,$vehicleIdOld);
        if($qq=='1')
       {
            $redis->hdel('H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode, $vehicleIdOld.':'.$deviceIdOld.':'.$shortName.':'.$orgId2.':'.$gpsSimNo.':OWN', $vehicleIdOld );
            $redis->hset('H_VehicleName_Mobile_Dealer_'.$dealerId.'_Org_'.$fcode, $vehicleIdOld.':'.$deviceIdOld.':'.$shortName.':'.$orgId2.':'.$gpsSimNo, $vehicleIdOld );
            $redis->srem('S_Vehicles_Admin_'.$fcode,$vehicleIdOld);
            $redis->srem('S_Vehicles_Dealer_'.$dealerIdOld.'_'.$fcode,$vehicleIdOld);
            $redis->sadd('S_Vehicles_Dealer_'.$dealerId.'_'.$fcode,$vehicleIdOld);
            $redis->hdel ( 'H_RefData_' . $fcode, $vehicleIdOld );
            $redis->hset ( 'H_RefData_' . $fcode, $vehicleIdOld, $refDataJson );
        }
        elseif($qq=='0' || $oo=='1')
        {
            $redis->hdel('H_VehicleName_Mobile_Dealer_'.$dealerIdOld.'_Org_'.$fcode, $vehicleIdOld.':'.$deviceIdOld.':'.$shortName.':'.$orgId2.':'.$gpsSimNo, $vehicleIdOld );
            $redis->hset('H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode, $vehicleIdOld.':'.$deviceIdOld.':'.$shortName.':'.$orgId2.':'.$gpsSimNo.':OWN', $vehicleIdOld );
            $redis->srem('S_Vehicles_Dealer_'.$dealerId.'_'.$fcode,$vehicleIdOld);
             $redis->srem('S_Vehicles_Dealer_'.$dealerIdOld.'_'.$fcode,$vehicleIdOld);
            $redis->sadd('S_Vehicles_Admin_'.$fcode,$vehicleIdOld);
            $redis->hdel ( 'H_RefData_' . $fcode, $vehicleIdOld );
            $redis->hset ( 'H_RefData_' . $fcode, $vehicleIdOld, $refDataJson2 );
        }
   // }
    if($dealerIdOld!='OWN')
    {
      $error='Vehicle moved successfully ';
    }
    else {
      $error=' Vehicle movement is not allowed';
    }
    Session::flash ( 'message', $vehicleIdOld . ' is updated successfully. ' );
    Session::flash('alert-class', 'alert-success');
    return Redirect::to('Device');
 }

/**
* Remove the specified resource from storage.
*
* @param int $id            
* @return Response
*/
   
}