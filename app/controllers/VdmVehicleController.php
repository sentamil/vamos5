<?php
class VdmVehicleController extends \BaseController {

/**
* Display a listing of the resource.
*
* @return Response
*/
public function index() {
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
    }
    else if(Session::get('cur')=='admin')
    {
        $vehicleListId='S_Vehicles_Admin_'.$fcode;
    }
    else{
        $vehicleListId = 'S_Vehicles_' . $fcode;
    }



    Log::info('vehicleListId=' . $vehicleListId);

    $vehicleList = $redis->smembers ( $vehicleListId);

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
    foreach ( $vehicleList as $vehicle ) {

        Log::info('$vehicle ' .$vehicle);
        $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );

        if(isset($vehicleRefData)) {
            Log::info('$vehicle ' .$vehicleRefData);
        }else {
            continue;
        }

        $vehicleRefData=json_decode($vehicleRefData,true);

        $deviceId = $vehicleRefData['deviceId'];

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
    }
    $demo='ahan';
    $user=null;

    $user1= new VdmDealersController;
    $user=$user1->checkuser();
    return View::make ( 'vdm.vehicles.index', array (
        'vehicleList' => $vehicleList
        ) )->with ( 'deviceList', $deviceList )->with('shortNameList',$shortNameList)->with('portNoList',$portNoList)->with('mobileNoList',$mobileNoList)->with('demo',$demo)->with ( 'user', $user )->with ( 'orgIdList', $orgIdList )->with ( 'deviceModelList', $deviceModelList )->with ( 'expiredList', $expiredList );
}





/**
* Show the form for creating a new resource.
*
* @return Response
*/



public function create($id) {
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
//get the Org list
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
    $orgList=array_add($orgList,'Default','Default');
    foreach ( $tmpOrgList as $org ) {
        $orgList = array_add($orgList,$org,$org);

    }


    if(Session::get('cur')=='dealer')
    {             
        $key='H_Pre_Onboard_Dealer_'.$username.'_'.$fcode;                                              

    }
    else if(Session::get('cur')=='admin')
    {
        $key='H_Pre_Onboard_Admin_'.$fcode;
    }
    $details=$redis->hget($key,$id);
    Log::info('id=' . $id);
    $valueData=json_decode($details,true);
    $deviceId = $valueData['deviceid'];
    $deviceModel = $valueData['deviceidtype'];
    $deviceidCheck = $redis->sismember('S_Device_' . $fcode, $deviceId);
    log::info( '------vehicleIdCheck---------- ::'.$deviceidCheck);
    if($deviceidCheck==1) {
//Session::flash ( 'message', 'DeviceId' . $deviceidCheck . 'already exist. Please choose another one' );
        log::info( '------vehicleIdCheck1---------- ::'.$deviceidCheck);
        $value =$redis->hget('H_Vehicle_Device_Map_'.$fcode,$deviceId);
        $vehicleId='gpsvts_'.substr($deviceId, -5);

        return Redirect::to ('vdmVehicles/' . $value . '/edit1');
    }


    return View::make ( 'vdm.vehicles.createnew' )->with ( 'orgList', $orgList )->with('deviceId',$deviceId)->with('deviceModel',$deviceModel);
}



public function dashboard() {
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    $redis = Redis::connection ();

    log::info( '------login dashboard---------- '.Session::get('cur'));

    return Redirect::to ( 'DashBoard' );
}





/**
* Store a newly created resource in storage.
* TODO validations should be improved to prevent any attacks
*
* @return Response
*/

/* Validator::extend('alpha_spaces', function($attribute, $value)
{
return preg_match('/^[\pL\s]+$/u', $value);
});*/

//if change done in store should done in update1 also
public function store() {
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }

    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
    log::info( '------date---------- ::'.microtime(true));
    $rules = array (
        'deviceId' => 'required|alpha_dash',
        'vehicleId' => 'required|alpha_dash',
        'shortName' => 'required|alpha_dash',
        'regNo' => 'required',
        'vehicleType' => 'required',
        'oprName' => 'required',
        'deviceModel' => 'required',
        'odoDistance' => 'required',
        'gpsSimNo' => 'required'

        );
    $validator = Validator::make ( Input::all (), $rules );
    $redis = Redis::connection ();
    $vehicleId = Input::get ( 'vehicleId' );
    $deviceId = Input::get ( 'deviceId' );
    $vehicleIdCheck = $redis->sismember('S_Vehicles_' . $fcode, $vehicleId);
    $deviceidCheck = $redis->sismember('S_Device_' . $fcode, $deviceId);
    log::info( '------vehicleIdCheck---------- ::');

    if($vehicleIdCheck==1) {
        Session::flash ( 'message', 'VehicleId' . $vehicleId . 'already exist. Please choose another one' );
        log::info( '------3---------- ::');
        return Redirect::to ( 'vdmVehicles/create/'.$deviceId );
    }
    if($deviceidCheck==1) {
        log::info( '------1---------- ::');
        Session::flash ( 'message', 'DeviceId' . $deviceidCheck . 'already exist. Please choose another one' );
        return Redirect::to ( 'vdmVehicles/create/'.$deviceId );
    }

    if ($validator->fails ()) {
        log::info( '------2---------- ::');
        return Redirect::to ( 'vdmVehicles/create/'.$deviceId )->withErrors ( $validator );
    } else {
// store

        $deviceId = Input::get ( 'deviceId' );
        $vehicleId = Input::get ( 'vehicleId' );
        $shortName = Input::get ( 'shortName' );
        $regNo = Input::get ( 'regNo' );
        $vehicleMake = Input::get ( 'vehicleMake' );
        $vehicleType = Input::get ( 'vehicleType' );
        $oprName = Input::get ( 'oprName' );
        $mobileNo = Input::get ( 'mobileNo' );
        $overSpeedLimit = Input::get ( 'overSpeedLimit' );
        $deviceModel = Input::get ( 'deviceModel' );
        $odoDistance = Input::get ('odoDistance');
        $driverName = Input::get ('driverName');
        $gpsSimNo = Input::get ('gpsSimNo');
        $email = Input::get ('email');
        $sendGeoFenceSMS = Input::get ('sendGeoFenceSMS');
        $morningTripStartTime = Input::get ('morningTripStartTime');
        $eveningTripStartTime = Input::get ('eveningTripStartTime');
        $fuel=Input::get ('fuel');
        $orgId = Input::get ('orgId');
        $altShortName= Input::get ('altShortName');
        $parkingAlert = Input::get('parkingAlert');
        $isRfid = Input::get('isRfid');
        $v=idate("d") ;
//            $paymentType=Input::get ( 'paymentType' );
        $paymentType='yearly';
        log::info('paymentType--->'.$paymentType);
        if($paymentType=='halfyearly')
        {
            $paymentmonth=6;
        }elseif($paymentType=='yearly'){
            $paymentmonth=11;
        }
        if($v>15)
        {
            log::info('inside if');
            $paymentmonth=$paymentmonth+1;

        }
        for ($i = 1; $i <=$paymentmonth; $i++){

            $new_date = date('F Y', strtotime("+$i month"));
            $new_date2 = date('FY', strtotime("$i month"));
        }
        $new_date1 = date('F d Y', strtotime("+0 month"));
        log::info( $new_date);
        $refDataArr = array (
            'deviceId' => $deviceId,
            'shortName' => $shortName,
            'deviceModel' => $deviceModel,
            'regNo' => $regNo,
            'vehicleMake' => $vehicleMake,
            'vehicleType' => $vehicleType,
            'oprName' => $oprName,
            'mobileNo' => $mobileNo,
            'overSpeedLimit' => $overSpeedLimit,
            'odoDistance' => $odoDistance,
            'driverName' => $driverName,
            'gpsSimNo' => $gpsSimNo,
            'email' => $email,
            'sendGeoFenceSMS' => $sendGeoFenceSMS,
            'morningTripStartTime' => $morningTripStartTime,
            'eveningTripStartTime' => $eveningTripStartTime,
            'orgId'=>$orgId,
            'parkingAlert'=>$parkingAlert,
            'altShortName' => $altShortName,
            'date' =>$new_date1,
            'paymentType'=>$paymentType,
            'expiredPeriod'=>$new_date,
            'fuel'=>$fuel,
            'isRfid'=>$isRfid,


            );

        $refDataJson = json_encode ( $refDataArr );

// H_RefData
        $expireData=$redis->hget ( 'H_Expire_' . $fcode, $new_date2);

        $redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );

        $cpyDeviceSet = 'S_Device_' . $fcode;

        $redis->sadd ( $cpyDeviceSet, $deviceId );

        $redis->hmset ( $vehicleDeviceMapId, $vehicleId , $deviceId, $deviceId, $vehicleId );

//this is for security check                                    
        $redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );

        $redis->hset('H_Device_Cpy_Map',$deviceId,$fcode);
        $redis->sadd('S_Vehicles_'.$orgId.'_'.$fcode , $vehicleId);
        if($expireData==null)
        {
            $redis->hset ( 'H_Expire_' . $fcode, $new_date2,$vehicleId);
        }else{
            $redis->hset ( 'H_Expire_' . $fcode, $new_date2,$expireData.','.$vehicleId);
        }

        $time =microtime(true);
/*latitude,longitude,speed,alert,date,distanceCovered,direction,position,status,odoDistance,msgType,insideGeoFence
13.104870,80.303138,0,N,$time,0.0,N,P,ON,$odoDistance,S,N
13.04523,80.200222,0,N,0,0.0,null,null,null,0.0,null,N vehicleId=Prasanna_Amaze
*/
$redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);
$time = round($time * 1000);


if(Session::get('cur')=='dealer')
{
    log::info( '------login 1---------- '.Session::get('cur'));
    $redis->sadd('S_Vehicles_Dealer_'.$username.'_'.$fcode,$vehicleId);
}
else if(Session::get('cur')=='admin')
{
    $redis->sadd('S_Vehicles_Admin_'.$fcode,$vehicleId);
}
$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
$franchiseDetails=json_decode($franDetails_json,true);
$tmpPositon =  '13.104870,80.303138,0,N,' . $time . ',0.0,N,P,ON,' .$odoDistance. ',S,N';
if(isset($franchiseDetails['fullAddress'])==1)
{
    $fullAddress=$franchiseDetails['fullAddress'];
    $data_arr = BusinessController::geocode($fullAddress);
    if($data_arr){        
        $latitude = $data_arr[0];
        $longitude = $data_arr[1];
        log::info( '------lat lang---------- '.$latitude.','.$longitude);
        $tmpPositon =  $latitude.','.$longitude.',0,N,' . $time . ',0.0,N,P,ON,' .$odoDistance. ',S,N';
    }
}
log::info( '------prodata---------- '.$tmpPositon);
$redis->hset ( 'H_ProData_' . $fcode, $vehicleId, $tmpPositon );
// redirect
Session::flash ( 'message', 'Successfully created ' . $vehicleId . '!' );
return Redirect::to ( 'Business' );
}
}



function geocode($address){

// url encode the address
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
public function analogCalibrate() {
    if (! Auth::check ())
    {
        return Redirect::to ( 'login' );
    }
    $redis = Redis::connection ();
    $username = Auth::user ()->username;

    $tanksize = Input::get ( 'tanksize' );
    $vehicleId = Input::get ( 'vehicleId' );

    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $details = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );
    Log::info($vehicleId.'-------------inside analogCalibrate-----------'.$tanksize);
    $refDataFromDB = json_decode ( $details, true );
    $fuelType =isset($refDataFromDB['fuelType'])?$refDataFromDB['fuelType']:'NotAvailabe';
    if($fuelType=='analog')
    {


        $maxmin=$redis->hget('H_Auto_'.$fcode,$vehicleId);
        if($maxmin!==null)
        {

            $maxmin=explode(',',$maxmin);
            $volt=$maxmin[0];
            Log::info($volt.'------------- analogCalibrate-----------'.$maxmin[0]);
            $litre=$tanksize;

            $voltDiff=$maxmin[1]-$maxmin[0];
            $voltDiff=$voltDiff/5;
            $tanksize=$tanksize/5; 
            if($voltDiff>=0 && $tanksize>=1)
            {
                $redis->del('Z_Sensor_'.$vehicleId.'_'.$fcode);
                for($i = 0; $i <=10; $i++){                                                                              

                    log::info( $volt.'---------------vechile------------- ::' .$litre);
                    if($litre<0)
                        break;
                    $redis->zadd ( 'Z_Sensor_'.$vehicleId.'_'.$fcode,$volt,round($litre));
                    $volt=$volt+$voltDiff;
                    $litre=$litre-$tanksize;

                }
            }                                                                             

        }
        else{

        }

    }
    return Redirect::to ( 'vdmVehicles' );     
}

public function edit($id) {
    try{
        Log::info('entering edit......');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;

        $redis = Redis::connection ();
        $vehicleId = $id;
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
        $deviceId = $redis->hget ( $vehicleDeviceMapId, $vehicleId );

        $details = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );

        $refData=null;
        $refData = array_add($refData, 'deviceModel', 'nill');
        $refData = array_add($refData, 'regNo', 'nill');
        $refData = array_add($refData, 'vehicleMake', ' ');
        $refData = array_add($refData, 'vehicleType', ' ');
        $refData = array_add($refData, 'oprName', ' ');
        $refData = array_add($refData, 'mobileNo', '0123456789');
        $refData = array_add($refData, 'overSpeedLimit', '50');
        $refData = array_add($refData, 'driverName', '');
        $refData = array_add($refData, 'gpsSimNo', '0123456789');
        $refData = array_add($refData, 'email', ' ');
        $refData = array_add($refData, 'odoDistance', '0');
        $refData = array_add($refData, 'sendGeoFenceSMS', 'no');
        $refData = array_add($refData, 'morningTripStartTime', ' ');
        $refData = array_add($refData, 'eveningTripStartTime', ' ');
        $refData= array_add($refData, 'altShortName',' ');
        $refData= array_add($refData, 'date',' ');
        $refData= array_add($refData, 'paymentType',' ');
        $refData= array_add($refData, 'expiredPeriod',' ');
        $refData= array_add($refData, 'fuel', 'no');
        $refData= array_add($refData, 'isRfid', 'no');
        $refData= array_add($refData, 'shortName', 'nill');
//            $refData= array_add($refData, 'fuelType', 'digital');
        $refDataFromDB = json_decode ( $details, true );




        $refDatatmp = array_merge($refData,$refDataFromDB);

        $refData=$refDatatmp;
//S_Schl_Rt_CVSM_ALH



        $orgId =isset($refDataFromDB['orgId'])?$refDataFromDB['orgId']:'NotAvailabe';
        Log::info(' orgId = ' . $orgId);
        $refData = array_add($refData, 'orgId', $orgId);
        $parkingAlert = isset($refDataFromDB->parkingAlert)?$refDataFromDB->parkingAlert:0;
        $refData= array_add($refData,'parkingAlert',$parkingAlert);
        $tmpOrgList = $redis->smembers('S_Organisations_' . $fcode);
        log::info( '------login 1---------- '.Session::get('cur'));
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
        $orgList=array_add($orgList,'Default','Default');
        foreach ( $tmpOrgList as $org ) {
            $orgList = array_add($orgList,''.$org,$org);
        }
        
        return View::make ( 'vdm.vehicles.edit', array (
            'vehicleId' => $vehicleId ) )->with ( 'refData', $refData )->with ( 'orgList', $orgList );
    }catch(\Exception $e)
    {
        return Redirect::to ( 'vdmVehicles' );
    }
}

public function edit1($id) {
    try{
        Log::info('entering edit......');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;

        $redis = Redis::connection ();
        $vehicleId = $id;
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
        $deviceId = $redis->hget ( $vehicleDeviceMapId, $vehicleId );

        $details = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );

        $refData=null;
        $refData = array_add($refData, 'overSpeedLimit', '50');
        $refData = array_add($refData, 'driverName', '');
        $refData = array_add($refData, 'gpsSimNo', '');
        $refData = array_add($refData, 'email', ' ');
        $refData = array_add($refData, 'odoDistance', '0');
        $refData = array_add($refData, 'sendGeoFenceSMS', 'no');
        $refData = array_add($refData, 'morningTripStartTime', ' ');
        $refData = array_add($refData, 'eveningTripStartTime', ' ');
        $refData= array_add($refData, 'altShortName',' ');
        $refData= array_add($refData, 'date',' ');
        $refData= array_add($refData, 'paymentType',' ');
        $refData= array_add($refData, 'expiredPeriod',' ');
        $refData= array_add($refData, 'fuel', 'no');
        $refDataFromDB = json_decode ( $details, true );




        $refDatatmp = array_merge($refData,$refDataFromDB);

        $refData=$refDatatmp;
//S_Schl_Rt_CVSM_ALH



        $orgId =isset($refDataFromDB['orgId'])?$refDataFromDB['orgId']:'NotAvailabe';
        Log::info(' orgId = ' . $orgId);
        $refData = array_add($refData, 'orgId', $orgId);
        $parkingAlert = isset($refDataFromDB->parkingAlert)?$refDataFromDB->parkingAlert:0;
        $refData= array_add($refData,'parkingAlert',$parkingAlert);
        $tmpOrgList = $redis->smembers('S_Organisations_' . $fcode);
        log::info( '------login 1---------- '.Session::get('cur'));
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
        $orgList=array_add($orgList,'Default','Default');
        foreach ( $tmpOrgList as $org ) {
            $orgList = array_add($orgList,$org,$org);

        }

//  var_dump($refData);
        return View::make ( 'vdm.vehicles.edit1', array (
            'vehicleId' => $vehicleId ) )->with ( 'refData', $refData )->with ( 'orgList', $orgList );
    }catch(\Exception $e)
    {
        return Redirect::to ( 'vdmVehicles' );
    }
}

public function updateCalibration() {
    Log::info('-------------inside calibrate add-----------');
    $temp=0;
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;

    $redis = Redis::connection ();
    $vehicleId = Input::get ('vehicleId');
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $redis->del ( 'Z_Sensor_'.$vehicleId.'_'.$fcode );
    for ($p = 9; $p>=$temp; $p--)
    {
        $volt=Input::get ('volt'.$p);
        $litre=Input::get ('litre'.$p);
        log::info( $volt.'---------------vechile------------- ::' .$litre);
        if((!$litre==null || $litre==0) && !$volt==null)
        {
// log::info( $volt.'---------------vechile------------- ::' .$litre);
            $redis->zadd ( 'Z_Sensor_'.$vehicleId.'_'.$fcode,$volt,$litre);
        }


    }
    Log::info('-------------outside calibrate add-----------');
    return Redirect::to ( 'vdmVehicles' );
}






public function calibrate($id) {

    Log::info('-------------inside calibrate-----------');
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;

    $redis = Redis::connection ();
    $vehicleId = $id;
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
    $deviceId = $redis->hget ( $vehicleDeviceMapId, $vehicleId );

    $details = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );

    $refData=null;

    $refData = json_decode ( $details, true );

    $address1=array();
    $place=array();
    $place1=array();

    $latandlan=array();
    $address1= $redis->zrange( 'Z_Sensor_'.$vehicleId.'_'.$fcode,0,-1,'withscores');

    $temp=null;
    $v=0;
    foreach($address1 as $org => $rowId)
    {



        $ahan=$rowId[1].':'.$rowId[0];

        log::info( $rowId[1].'inside no'.$ahan.' result' .$rowId[0]);
//$place = array_add($place, $rowId[1].':'.$rowId[0],$ahan);
        $place = array_add($place,$v,$ahan);
        $v++;
    }


    $temp=10-count($place);
    log::info( $temp.'---------------place------------- ::' .count($place));
    for ($p = 0; $p < $temp; $p++)
    {
        log::info( '---------------in------------- ::' );
        $place = array_add($place, "".':'."litre".$p,'');

    }

    $i=0;
    $j=0;$k=0;$m=0;





    $tanksize='';

//  var_dump($refData);
    return View::make ( 'vdm.vehicles.calibrate', array (
        'vehicleId' => $vehicleId ) )->with ( 'deviceId', $deviceId )->with ( 'place', $place )->with ( 'k', $k )->with ( 'j', $j )->with ( 'i', $i )->with ( 'm', $m )->with ( 'place1', $place1 )->with('tanksize',$tanksize);

}




/**
* Update the specified resource in storage.
*
* @param int $id            
* @return Response
*/

//if change done in updates should done in updateLive also
public function update($id) {
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $vehicleId = $id;
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;


    $rules = array (
        'shortName' => 'required',
        'regNo' => 'required',
        'vehicleType' => 'required',
//            'oprName' => 'required',
//            'mobileNo' => 'required',
//                            'overSpeedLimit' => 'required',
        );

    $validator = Validator::make ( Input::all (), $rules );

    if ($validator->fails ()) {
        Log::error(' VdmVehicleConrtoller update validation failed++' );
        return Redirect::to ( 'vdmVehicles/edit' )->withErrors ( $validator );
    } else {
// store
        $shortName = Input::get ( 'shortName' );
        $regNo = Input::get ( 'regNo' );
        $vehicleMake = Input::get ( 'vehicleMake' );
        $vehicleType = Input::get ( 'vehicleType' );
        $oprName = Input::get ( 'oprName' );
        $mobileNo = Input::get ( 'mobileNo' );
        $overSpeedLimit = Input::get ( 'overSpeedLimit' );
        $deviceModel = Input::get ( 'deviceModel' );
        $driverName = Input::get ( 'driverName' );
        $email = Input::get ( 'email' );
        $orgId = Input::get ( 'orgId' );
        $sendGeoFenceSMS = Input::get ( 'sendGeoFenceSMS' );
        $gpsSimNo = Input::get ('gpsSimNo');
        $odoDistance = Input::get ('odoDistance');
        $morningTripStartTime = Input::get ('morningTripStartTime');
        $eveningTripStartTime = Input::get ('eveningTripStartTime');
        $parkingAlert = Input::get ('parkingAlert');
        $fuel=Input::get ('fuel');
        $altShortName=Input::get ('altShortName');
        $fuelType=Input::get ('fuelType');
        $isRfid=Input::get ('isRfid');
        $redis = Redis::connection ();
        $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );

        $vehicleRefData=json_decode($vehicleRefData,true);

        $deviceId=$vehicleRefData['deviceId'];
        try{
            $date=$vehicleRefData['date'];
            $paymentType=$vehicleRefData['paymentType'];
            $expiredPeriod=$vehicleRefData['expiredPeriod'];
        }catch(\Exception $e)
        {
            $date=' ';
            $paymentType=' ';
            $expiredPeriod=' ';
        }

//    $odoDistance=$vehicleRefData['odoDistance'];
//gpsSimNo
//    $gpsSimNo=$vehicleRefData['gpsSimNo'];
        $refDataArr = array (
            'deviceId' => $deviceId,
            'shortName' => $shortName,
            'deviceModel' => $deviceModel,
            'regNo' => $regNo,
            'vehicleMake' => $vehicleMake,
            'vehicleType' => $vehicleType,
            'oprName' => $oprName,
            'mobileNo' => $mobileNo,
            'overSpeedLimit' => $overSpeedLimit,
            'odoDistance' => $odoDistance,
            'driverName' => $driverName,
            'gpsSimNo' => $gpsSimNo,
            'email' => $email,
            'orgId' =>$orgId,
            'sendGeoFenceSMS' => $sendGeoFenceSMS,
            'morningTripStartTime' => $morningTripStartTime,
            'eveningTripStartTime' => $eveningTripStartTime,
            'parkingAlert' => $parkingAlert,
            'altShortName'=>$altShortName,
            'date' =>$date,
            'paymentType'=>$paymentType,
            'expiredPeriod'=>$expiredPeriod,
            'fuel'=>$fuel,
            'fuelType'=>$fuelType,
            'isRfid'=>$isRfid,
            );

        $refDataJson = json_encode ( $refDataArr );
// H_RefData
         Log::info($vehicleId.'--------------------org--------------------------------'.$orgId);
$refDataJson1=$redis->hget ( 'H_RefData_' . $fcode, $vehicleId);//ram
$refDataJson1=json_decode($refDataJson1,true);

$torg = isset($refDataJson1['orgId'])?$refDataJson1['orgId']:'default';
$org=isset($vehicleRefData->orgId)?$vehicleRefData->orgId:$torg;
$oldroute=isset($vehicleRefData->shortName)?$vehicleRefData->shortName:$refDataJson1['shortName'];

if($org!==$orgId)
{
    Log::info($vehicleId.'--------------------inside equal--------------------------------'.$org);
    $redis->srem ( 'S_Vehicles_' . $org.'_'.$fcode, $vehicleId);
    $redis->srem('S_Organisation_Route_'.$org.'_'.$fcode,$oldroute);
    $redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);
}
if($oldroute!==$shortName && $org==$orgId)
{
    Log::info($vehicleId.'--------------------inside equal1--------------------------------'.$org);
    $redis->srem('S_Organisation_Route_'.$orgId.'_'.$fcode,$oldroute);
    $redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);

}
$vec=$redis->hget('H_ProData_' . $fcode, $vehicleId);
$details= explode(',',$vec);
$temp=null;
$i=0;
foreach ( $details as $gr ) {
    $i++;

    if($temp==null)
    {
        $temp=$gr;
    }
    else{
        if($i==10 && $vehicleRefData['odoDistance']!==$odoDistance)
        {
            Log::info('-----------inside log----------'.$odoDistance);
            $temp=$temp.','.$odoDistance;
        }
        else{
            $temp=$temp.','.$gr;
        }

    }                                                                             
}


$redis->hset ( 'H_ProData_' . $fcode, $vehicleId, $temp );




//$redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);
$redis->sadd ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleId);

$redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );

$redis->hmset ( $vehicleDeviceMapId, $vehicleId, $deviceId, $deviceId, $vehicleId );
$redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );
$redis->hset('H_Device_Cpy_Map',$deviceId,$fcode);
// redirect
Session::flash ( 'message', 'Successfully updated ' . $vehicleId . '!' );

return Redirect::to ( 'vdmVehicles' );
//            return VdmVehicleController::edit($vehicleId);
}
}

public function updateLive($id) {
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $vehicleId = $id;
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;


    $rules = array (
        'shortName' => 'required',
        'regNo' => 'required',
        'vehicleType' => 'required',
//            'oprName' => 'required',
//            'mobileNo' => 'required',
//                            'overSpeedLimit' => 'required',
        );

    $validator = Validator::make ( Input::all (), $rules );

    if ($validator->fails ()) {
        Log::error(' VdmVehicleConrtoller update validation failed++' );
        return Redirect::to ( 'vdmVehicles/edit' )->withErrors ( $validator );
    } else {
// store
        $shortName = Input::get ( 'shortName' );
        $regNo = Input::get ( 'regNo' );
        $overSpeedLimit = Input::get ( 'overSpeedLimit' );
        $vehicleType = Input::get ( 'vehicleType' );
        $driverName = Input::get ( 'driverName' );
        $odoDistance = Input::get ('odoDistance');
        $redis = Redis::connection ();
        $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );
        $vehicleRefData=json_decode($vehicleRefData,true);

        if(isset($vehicleRefData['vehicleMake'])==1)
            $vehicleMake=$vehicleRefData['vehicleMake'];
        else
            $vehicleMake='';
        if(isset($vehicleRefData['oprName'])==1)
            $oprName=$vehicleRefData['oprName'];
        else
            $oprName='';
        if(isset($vehicleRefData['mobileNo'])==1)
            $mobileNo=$vehicleRefData['mobileNo'];
        else
            $mobileNo='';
        if(isset($vehicleRefData['deviceModel'])==1)
            $deviceModel=$vehicleRefData['deviceModel'];
        else
            $deviceModel='';
        if(isset($vehicleRefData['email'])==1)
            $email=$vehicleRefData['email'];
        else
            $email='';
        if(isset($vehicleRefData['orgId'])==1)
            $orgId=$vehicleRefData['orgId'];
        else
            $orgId='';
        if(isset($vehicleRefData['sendGeoFenceSMS'])==1)
            $sendGeoFenceSMS=$vehicleRefData['sendGeoFenceSMS'];
        else
            $sendGeoFenceSMS='';
        if(isset($vehicleRefData['gpsSimNo'])==1)
            $gpsSimNo=$vehicleRefData['gpsSimNo'];
        else
            $gpsSimNo='';
        if(isset($vehicleRefData['morningTripStartTime'])==1)
            $morningTripStartTime=$vehicleRefData['morningTripStartTime'];
        else
            $morningTripStartTime='';
        if(isset($vehicleRefData['eveningTripStartTime'])==1)
            $eveningTripStartTime=$vehicleRefData['eveningTripStartTime'];
        else
            $eveningTripStartTime='0';
        if(isset($vehicleRefData['parkingAlert'])==1)
            $parkingAlert=$vehicleRefData['parkingAlert'];
        else
            $parkingAlert='0';
        if(isset($vehicleRefData['fuel'])==1)
            $fuel=$vehicleRefData['fuel'];
        else
            $fuel='';
        if(isset($vehicleRefData['altShortName'])==1)
            $altShortName=$vehicleRefData['altShortName'];
        else
            $altShortName='';
        if(isset($vehicleRefData['fuelType'])==1)
            $fuelType=$vehicleRefData['fuelType'];
        else
            $fuelType='digital';          

        $deviceId=$vehicleRefData['deviceId'];
        try{
            $date=$vehicleRefData['date'];
            $paymentType=$vehicleRefData['paymentType'];
            $expiredPeriod=$vehicleRefData['expiredPeriod'];
        }catch(\Exception $e)
        {
            $date=' ';
            $paymentType=' ';
            $expiredPeriod=' ';
        }

//    $odoDistance=$vehicleRefData['odoDistance'];
//gpsSimNo
//    $gpsSimNo=$vehicleRefData['gpsSimNo'];
        $refDataArr = array (
            'deviceId' => $deviceId,
            'shortName' => $shortName,
            'deviceModel' => $deviceModel,
            'regNo' => $regNo,
            'vehicleMake' => $vehicleMake,
            'vehicleType' => $vehicleType,
            'oprName' => $oprName,
            'mobileNo' => $mobileNo,
            'overSpeedLimit' => $overSpeedLimit,
            'odoDistance' => $odoDistance,
            'driverName' => $driverName,
            'gpsSimNo' => $gpsSimNo,
            'email' => $email,
            'orgId' =>$orgId,
            'sendGeoFenceSMS' => $sendGeoFenceSMS,
            'morningTripStartTime' => $morningTripStartTime,
            'eveningTripStartTime' => $eveningTripStartTime,
            'parkingAlert' => $parkingAlert,
            'altShortName'=>$altShortName,
            'date' =>$date,
            'paymentType'=>$paymentType,
            'expiredPeriod'=>$expiredPeriod,
            'fuel'=>$fuel,
            'fuelType'=>$fuelType,
            );

        $refDataJson = json_encode ( $refDataArr );
// H_RefData
$refDataJson1=$redis->hget ( 'H_RefData_' . $fcode, $vehicleId);//ram
$refDataJson1=json_decode($refDataJson1,true);

$torg = isset($refDataJson1['orgId'])?$refDataJson1['orgId']:'default';
$org=isset($vehicleRefData->orgId)?$vehicleRefData->orgId:$torg;
$oldroute=isset($vehicleRefData->shortName)?$vehicleRefData->shortName:$refDataJson1['shortName'];

if($org!==$orgId)
{
    Log::info($vehicleId.'--------------------inside equal--------------------------------'.$org);
    $redis->srem ( 'S_Vehicles_' . $org.'_'.$fcode, $vehicleId);
    $redis->srem('S_Organisation_Route_'.$org.'_'.$fcode,$oldroute);
    $redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);
}
if($oldroute!==$shortName && $org==$orgId)
{
    Log::info($vehicleId.'--------------------inside equal1--------------------------------'.$org);
    $redis->srem('S_Organisation_Route_'.$orgId.'_'.$fcode,$oldroute);
    $redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);

}
$vec=$redis->hget('H_ProData_' . $fcode, $vehicleId);
$details= explode(',',$vec);
$temp=null;
$i=0;
foreach ( $details as $gr ) {
    $i++;

    if($temp==null)
    {
        $temp=$gr;
    }
    else{
        if($i==10 && $vehicleRefData['odoDistance']!==$odoDistance)
        {
            Log::info('-----------inside log----------'.$odoDistance);
            $temp=$temp.','.$odoDistance;
        }
        else{
            $temp=$temp.','.$gr;
        }

    }                                                                             
}


$redis->hset ( 'H_ProData_' . $fcode, $vehicleId, $temp );




//$redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);
$redis->sadd ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleId);

$redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );

$redis->hmset ( $vehicleDeviceMapId, $vehicleId, $deviceId, $deviceId, $vehicleId );
$redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );
$redis->hset('H_Device_Cpy_Map',$deviceId,$fcode);
// redirect
Session::flash ( 'message', 'Successfully updated ' . $vehicleId . '!' );

return Redirect::to ( 'vdmVehicles' );
//            return VdmVehicleController::edit($vehicleId);
}
}

public function update1() {
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }

    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
    log::info( '------date---------- ::'.microtime(true));
    $rules = array (
        'deviceId' => 'required|alpha_dash',
        'vehicleId' => 'required|alpha_dash',
        'shortName' => 'required|alpha_dash',
        'regNo' => 'required',
        'vehicleType' => 'required',
        'oprName' => 'required',
        'deviceModel' => 'required',
        'odoDistance' => 'required',
        'gpsSimNo' => 'required'

        );
    $validator = Validator::make ( Input::all (), $rules );
    $redis = Redis::connection ();
    $vehicleId = Input::get ( 'vehicleId' );
    $vehicleIdTemp = Input::get ( 'vehicleIdTemp' );
    $deviceId = Input::get ( 'deviceId' );
    $vehicleIdCheck = $redis->sismember('S_Vehicles_' . $fcode, $vehicleId);
    $deviceidCheck = $redis->sismember('S_Device_' . $fcode, $deviceId);



    if ($validator->fails ()) {
        return Redirect::to ( 'vdmVehicles/create' )->withErrors ( $validator );
    } else {
// store

        $deviceId = Input::get ( 'deviceId' );
        $vehicleId = Input::get ( 'vehicleId' );
        $shortName = Input::get ( 'shortName' );
        $regNo = Input::get ( 'regNo' );
        $vehicleMake = Input::get ( 'vehicleMake' );
        $vehicleType = Input::get ( 'vehicleType' );
        $oprName = Input::get ( 'oprName' );
        $mobileNo = Input::get ( 'mobileNo' );
        $overSpeedLimit = Input::get ( 'overSpeedLimit' );
        $deviceModel = Input::get ( 'deviceModel' );
        $odoDistance = Input::get ('odoDistance');
        $driverName = Input::get ('driverName');
        $gpsSimNo = Input::get ('gpsSimNo');
        $email = Input::get ('email');
        $sendGeoFenceSMS = Input::get ('sendGeoFenceSMS');
        $morningTripStartTime = Input::get ('morningTripStartTime');
        $eveningTripStartTime = Input::get ('eveningTripStartTime');
        $fuel=Input::get ('fuel');
        $isRfid=Input::get ('isRfid');
        $orgId = Input::get ('orgId');
        $altShortName= Input::get ('altShortName');
        $parkingAlert = Input::get('parkingAlert');
        $v=idate("d") ;
//            $paymentType=Input::get ( 'paymentType' );
        $paymentType='yearly';
        log::info('paymentType--->'.$paymentType);
        if($paymentType=='halfyearly')
        {
            $paymentmonth=6;
        }elseif($paymentType=='yearly'){
            $paymentmonth=11;
        }
        if($v>15)
        {
            log::info('inside if');
            $paymentmonth=$paymentmonth+1;

        }
        for ($i = 1; $i <=$paymentmonth; $i++){

            $new_date = date('F Y', strtotime("+$i month"));
            $new_date2 = date('FY', strtotime("$i month"));
        }
        $new_date1 = date('F d Y', strtotime("+0 month"));
        log::info( $new_date);
        $refDataArr = array (
            'deviceId' => $deviceId,
            'shortName' => $shortName,
            'deviceModel' => $deviceModel,
            'regNo' => $regNo,
            'vehicleMake' => $vehicleMake,
            'vehicleType' => $vehicleType,
            'oprName' => $oprName,
            'mobileNo' => $mobileNo,
            'overSpeedLimit' => $overSpeedLimit,
            'odoDistance' => $odoDistance,
            'driverName' => $driverName,
            'gpsSimNo' => $gpsSimNo,
            'email' => $email,
            'sendGeoFenceSMS' => $sendGeoFenceSMS,
            'morningTripStartTime' => $morningTripStartTime,
            'eveningTripStartTime' => $eveningTripStartTime,
            'orgId'=>$orgId,
            'parkingAlert'=>$parkingAlert,
            'altShortName' => $altShortName,
            'date' =>$new_date1,
            'paymentType'=>$paymentType,
            'expiredPeriod'=>$new_date,
            'fuel'=>$fuel,
            'isRfid'=>$isRfid,


            );
        VdmVehicleController::destroy($vehicleIdTemp);
        $refDataJson = json_encode ( $refDataArr );

// H_RefData
        $expireData=$redis->hget ( 'H_Expire_' . $fcode, $new_date2);

        $redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );

        $cpyDeviceSet = 'S_Device_' . $fcode;

        $redis->sadd ( $cpyDeviceSet, $deviceId );

        $redis->hmset ( $vehicleDeviceMapId, $vehicleId , $deviceId, $deviceId, $vehicleId );

//this is for security check                                    
        $redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );

        $redis->hset('H_Device_Cpy_Map',$deviceId,$fcode);
        $redis->sadd('S_Vehicles_'.$orgId.'_'.$fcode , $vehicleId);
        if($expireData==null)
        {
            $redis->hset ( 'H_Expire_' . $fcode, $new_date2,$vehicleId);
        }else{
            $redis->hset ( 'H_Expire_' . $fcode, $new_date2,$expireData.','.$vehicleId);
        }

        $time =microtime(true);
/*latitude,longitude,speed,alert,date,distanceCovered,direction,position,status,odoDistance,msgType,insideGeoFence
13.104870,80.303138,0,N,$time,0.0,N,P,ON,$odoDistance,S,N
13.04523,80.200222,0,N,0,0.0,null,null,null,0.0,null,N vehicleId=Prasanna_Amaze
*/
$redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);
$time = round($time * 1000);


if(Session::get('cur')=='dealer')
{
    log::info( '------login 1---------- '.Session::get('cur'));
    $redis->sadd('S_Vehicles_Dealer_'.$username.'_'.$fcode,$vehicleId);
}
else if(Session::get('cur')=='admin')
{
    $redis->sadd('S_Vehicles_Admin_'.$fcode,$vehicleId);
}
$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
$franchiseDetails=json_decode($franDetails_json,true);
$tmpPositon =  '13.104870,80.303138,0,N,' . $time . ',0.0,N,P,ON,' .$odoDistance. ',S,N';
if(isset($franchiseDetails['fullAddress'])==1)
{
    $fullAddress=$franchiseDetails['fullAddress'];
    $data_arr = BusinessController::geocode($fullAddress);
    if($data_arr){        
        $latitude = $data_arr[0];
        $longitude = $data_arr[1];
        log::info( '------lat lang---------- '.$latitude.','.$longitude);
        $tmpPositon =  $latitude.','.$longitude.',0,N,' . $time . ',0.0,N,P,ON,' .$odoDistance. ',S,N';
    }
}
log::info( '------prodata---------- '.$tmpPositon);
$redis->hset ( 'H_ProData_' . $fcode, $vehicleId, $tmpPositon );
// redirect

Session::flash ( 'message', 'Successfully created ' . $vehicleId . '!' );
return VdmVehicleController::edit1($vehicleId);
}


}











public function migrationUpdate() {
    log::info('-----------inside migrationUpdate---------');
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $vehicleId = Input::get ( 'vehicleId' );
    $deviceId = Input::get ( 'deviceId' );
    $vehicleIdOld= Input::get ( 'vehicleIdOld' );
    $deviceIdOld = Input::get ( 'deviceIdOld' );
     $expiredPeriodOld = Input::get ( 'expiredPeriodOld' );
    
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;


    $rules = array (
        'vehicleId' => 'required|alpha_dash',
        'deviceId' => 'required',
        );

    $validator = Validator::make ( Input::all (), $rules );

    if ($validator->fails ()) {
        Log::error(' VdmVehicleConrtoller update validation failed++' );
        return Redirect::to ( 'vdmVehicles/edit' )->withErrors ( $validator );
    } else {
// store

        if($vehicleId==$vehicleIdOld && $deviceId==$deviceIdOld)
        {
            log::info('-----------inside same vehicleid and device Id no change');
            Session::flash ( 'message', 'Same vehicle Id and device Id no change' .'!' );
            $deviceId= $deviceIdOld;
            $vehicleId= $vehicleIdOld;
            return View::make ( 'vdm.vehicles.migration', array (
                'vehicleId' => $vehicleId ) )->with ( 'deviceId', $deviceId )->with('expiredPeriod',$expiredPeriodOld);
        }
        else if($vehicleId==$vehicleIdOld && $deviceId!==$deviceIdOld)
        {
            log::info('-----------inside same vehicleid and different device Id ');
            $deviceIdTemp = $redis->hget ( $vehicleDeviceMapId, $deviceId );
            log::info('-----------inside same vehicleid and different device Id '.$deviceIdTemp);
            if($deviceIdTemp!==null)
            {
                Session::flash ( 'message', 'Device Id Already Present ' .'!' );
                $deviceId= $deviceIdOld;
                $vehicleId= $vehicleIdOld;
                return View::make ( 'vdm.vehicles.migration', array (
                    'vehicleId' => $vehicleId ) )->with ( 'deviceId', $deviceId )->with('expiredPeriod',$expiredPeriodOld);
            }
        }
        else if($vehicleId!==$vehicleIdOld && $deviceId==$deviceIdOld)
        {
            log::info('-----------inside different vehicleid and same device Id');
            $vehicleIdTemp = $redis->hget ( $vehicleDeviceMapId, $vehicleId );
            if($vehicleIdTemp!==null)
            {
                Session::flash ( 'message', 'Vehicle Id Already Present ' .'!' );
                $deviceId= $deviceIdOld;
                $vehicleId= $vehicleIdOld;
                return View::make ( 'vdm.vehicles.migration', array (
                    'vehicleId' => $vehicleId ) )->with ( 'deviceId', $deviceId )->with('expiredPeriod',$expiredPeriodOld);
            }
        }
        else if($vehicleId!==$vehicleIdOld && $deviceId!==$deviceIdOld)
        {
            log::info('-----------inside different vehicleid and different device Id ');
            $vehicleIdTemp = $redis->hget ( $vehicleDeviceMapId, $vehicleId );
            if($vehicleIdTemp!==null)
            {
                Session::flash ( 'message', 'Vehicle Id Already Present ' .'!' );
                $deviceId= $deviceIdOld;
                $vehicleId= $vehicleIdOld;
                return View::make ( 'vdm.vehicles.migration', array (
                    'vehicleId' => $vehicleId ) )->with ( 'deviceId', $deviceId )->with('expiredPeriod',$expiredPeriodOld);
            }
            $deviceIdTemp = $redis->hget ( $vehicleDeviceMapId, $deviceId );
            if($deviceIdTemp!==null)
            {
                Session::flash ( 'message', 'Device Id Already Present ' . '!' );
                $deviceId= $deviceIdOld;
                $vehicleId= $vehicleIdOld;
                return View::make ( 'vdm.vehicles.migration', array (
                    'vehicleId' => $vehicleId ) )->with ( 'deviceId', $deviceId )->with('expiredPeriod',$expiredPeriodOld);
            }
            if($deviceIdTemp!==null && $vehicleIdTemp!==null)
            {
                Session::flash ( 'message', 'Device Id and Vehicle Id Already Present ' . '!' );
                $deviceId= $deviceIdOld;
                $vehicleId= $vehicleIdOld;
                return View::make ( 'vdm.vehicles.migration', array (
                    'vehicleId' => $vehicleId ) )->with ( 'deviceId', $deviceId )->with('expiredPeriod',$expiredPeriodOld);
            }

        }

        $redis->hdel ( $vehicleDeviceMapId, $vehicleIdOld );                               
        $redis->hdel ( $vehicleDeviceMapId, $deviceIdOld );                                
        $redis->hset ( $vehicleDeviceMapId, $vehicleId, $deviceId );                     
        $redis->hset ( $vehicleDeviceMapId, $deviceId ,$vehicleId);


//            $redis->hdel ( 'H_RefData_' . $fcode, $vehicleId );

        $redis->hdel('H_Device_Cpy_Map',$deviceIdOld);

        $redis->hset('H_Device_Cpy_Map',$deviceId, $fcode);

        $cpyDeviceSet = 'S_Device_' . $fcode;

        $redis->srem ( $cpyDeviceSet, $deviceIdOld );

        $redis->sadd ( $cpyDeviceSet, $deviceId );







        $refDataJson1=$redis->hget ( 'H_RefData_' . $fcode, $vehicleIdOld);
        $refDataJson1=json_decode($refDataJson1,true);

        $orgId=isset($refDataJson1['orgId'])?$refDataJson1['orgId']:'default';
        $time =microtime(true);
        $time = round($time * 1000);
        $franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
        $franchiseDetails=json_decode($franDetails_json,true);
        $tmpPositon =  '13.104870,80.303138,0,N,' . $time . ',0.0,N,P,ON,0,S,N';
        if(isset($franchiseDetails['fullAddress'])==1)
        {
            $fullAddress=$franchiseDetails['fullAddress'];
            $data_arr = BusinessController::geocode($fullAddress);
            if($data_arr){        
                $latitude = $data_arr[0];
                $longitude = $data_arr[1];
                log::info( '------lat lang---------- '.$latitude.','.$longitude);
                $tmpPositon =  $latitude.','.$longitude.',0,N,' . $time . ',0.0,N,P,ON,0,S,N';
            }
        }
        log::info( '------prodata---------- '.$tmpPositon);
        $redis->hset ( 'H_ProData_' . $fcode, $vehicleId, $tmpPositon );
        try{
            $expiredPeriod=isset($refDataJson1['expiredPeriod'])?$refDataJson1['expiredPeriod']:'expiredPeriod';
            $vec=$redis->hget('H_Expire_'.$fcode,$expiredPeriod);
            if($vec!==null)
            {
                $details= explode(',',$vec);
                $temp=null;
                foreach ( $details as $gr ) {

                    if($gr==$vehicleIdOld)
                    {
                        $gr=$vehicleId;
                    }
                    if($temp==null)
                    {
                        $temp=$gr;
                    }
                    else{
                        $temp=$temp.','.$gr;
                    }                                                                             
                }

                $redis->hset('H_Expire_'.$fcode,$expiredPeriod,$temp);
            }
        }catch(\Exception $e)
        {

        }
        $shortName = isset($refDataJson1['shortName'])?$refDataJson1['shortName']:'nill';
        $regNo = isset($refDataJson1['regNo'])?$refDataJson1['regNo']:'XXXXX';
        $vehicleMake =  isset($refDataJson1['vehicleMake'])?$refDataJson1['vehicleMake']:' ';
        $vehicleType =  isset($refDataJson1['vehicleType'])?$refDataJson1['vehicleType']:'Bus';
        $opname =  isset($refDataJson1['oprName'])?$refDataJson1['oprName']:'airtel';I
        $oprName = isset($refDataJson1['oprName'])?$refDataJson1['oprName']:' ';
        $mobileNo = isset($refDataJson1['mobileNo'])?$refDataJson1['mobileNo']:'0123456789';
        $overSpeedLimit = isset($refDataJson1['overSpeedLimit'])?$refDataJson1['overSpeedLimit']:'60';
        $deviceModel =isset($refDataJson1['deviceModel'])?$refDataJson1['deviceModel']:'GT06N'; 
        $email = isset($refDataJson1['email'])?$refDataJson1['email']:' '; 
        $orgId = isset($refDataJson1['orgId'])?$refDataJson1['orgId']:'default'; 
        $sendGeoFenceSMS = isset($refDataJson1['sendGeoFenceSMS'])?$refDataJson1['sendGeoFenceSMS']:'no'; 
        $gpsSimNo = isset($refDataJson1['gpsSimNo'])?$refDataJson1['gpsSimNo']:'0123456789'; 
        $odoDistance = isset($refDataJson1['odoDistance'])?$refDataJson1['odoDistance']:'0'; 
        $morningTripStartTime = isset($refDataJson1['morningTripStartTime'])?$refDataJson1['morningTripStartTime']:' '; 
        $eveningTripStartTime = isset($refDataJson1['eveningTripStartTime'])?$refDataJson1['eveningTripStartTime']:' '; 
        $parkingAlert = isset($refDataJson1['parkingAlert'])?$refDataJson1['parkingAlert']:'no'; 
        $fuel=isset($refDataJson1['fuel'])?$refDataJson1['fuel']:'no';
        $altShortName=isset($refDataJson1['altShortName'])?$refDataJson1['altShortName']:'nill'; 
        $fuelType=isset($refDataJson1['fuelType'])?$refDataJson1['fuelType']:' '; 
        $isRfid=isset($refDataJson1['isRfid'])?$refDataJson1['isRfid']:'no'; 
      
        try{
            $date=isset($refDataJson1['date'])?$refDataJson1['date']:' '; 
            $paymentType=isset($refDataJson1['paymentType'])?$refDataJson1['paymentType']:' '; 
            $expiredPeriod=isset($refDataJson1['expiredPeriod'])?$refDataJson1['expiredPeriod']:' '; 
        }catch(\Exception $e)
        {
            
        }
        $refDataArr = array (
            'deviceId' => $deviceId,
            'shortName' => $shortName,
            'deviceModel' => $deviceModel,
            'regNo' => $regNo,
            'vehicleMake' => $vehicleMake,
            'vehicleType' => $vehicleType,
            'oprName' => $opname,
            'mobileNo' => $mobileNo,
            'overSpeedLimit' => $overSpeedLimit,
            'odoDistance' => $odoDistance,
            'driverName' => $driverName,
            'gpsSimNo' => $gpsSimNo,
            'email' => $email,
            'orgId' =>$orgId,
            'sendGeoFenceSMS' => $sendGeoFenceSMS,
            'morningTripStartTime' => $morningTripStartTime,
            'eveningTripStartTime' => $eveningTripStartTime,
            'parkingAlert' => $parkingAlert,
            'altShortName'=>$altShortName,
            'date' =>$date,
            'paymentType'=>$paymentType,
            'expiredPeriod'=>$expiredPeriod,
            'fuel'=>$fuel,
            'fuelType'=>$fuelType,
            'isRfid'=>$isRfid,
            );

        $refDataJson = json_encode ( $refDataArr );
        
        $redis->hdel ( 'H_RefData_' . $fcode, $vehicleIdOld );
        $redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );

        $redis->srem ( 'S_Vehicles_' . $fcode, $vehicleIdOld );

        $redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );

        $redis->srem ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleIdOld);
        $redis->sadd ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleId);





        $groupList = $redis->smembers('S_Groups_' . $fcode);

        foreach ( $groupList as $group ) {
            if($redis->sismember($group,$vehicleIdOld)==1)
            {
                $result = $redis->srem($group,$vehicleIdOld);
                $redis->sadd($group,$vehicleId);
            }

//            Log::info('going to delete vehicle from group ' . $group . $redisVehicleId . $result);
        }

        $expiredPeriod=$redis->hget('H_Expire_'.$fcode,$vehicleIdOld);
        log::info(' expire---->'.$expiredPeriodOld);
        if(!$expiredPeriod==null)
        {
            log::info('inside expire---->'.$expiredPeriod);
            $expiredPeriod=str_replace($vehicleIdOld, $vehicleId, $expiredPeriod);
            $redis->hset('H_Expire_'.$fcode,$expiredPeriodOld,$expiredPeriod);
        }

        if(Session::get('cur')=='dealer')
        {
            log::info('-----------inside dealer-----------');
            $redis->srem('S_Vehicles_Dealer_'.$username.'_'.$fcode,$vehicleIdOld);
            $redis->sadd('S_Vehicles_Dealer_'.$username.'_'.$fcode,$vehicleId);
            $groupList1 = $redis->smembers('S_Groups_Dealer_'.$username.'_' . $fcode);
        }
        else if(Session::get('cur')=='admin')
        {
            log::info('-----------inside admin-----------');
            $redis->srem('S_Vehicles_Admin_'.$fcode,$vehicleIdOld);
            $redis->sadd('S_Vehicles_Admin_'.$fcode,$vehicleId);
            $groupList1 = $redis->smembers('S_Groups_Admin_'.$fcode);
        }
        foreach ( $groupList1 as $group ) {
            if($redis->sismember($group,$vehicleIdOld)==1)
            {
                $result = $redis->srem($group,$vehicleIdOld);
                $redis->sadd($group,$vehicleId);
            }

//            Log::info('going to delete vehicle from group ' . $group . $redisVehicleId . $result);
        }


    }

    // log::info('device id--->'.$deviceId);
    // log::info('vechicle id-->'.$vehicleId);
    Session::flash ( 'message', 'Successfully updated ' . '!' );
    return Redirect::to ( 'vdmVehicles' );

//            return View::make ( 'vdm.vehicles.migration', array ('vehicleId' => $vehicleId ) )->with ( 'deviceId', $deviceId );

}









/**
* Remove the specified resource from storage.
*
* @param int $id            
* @return Response
*/
public function destroy($id) {
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    $redis = Redis::connection ();

    $vehicleId = $id;
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
    $cpyDeviceSet = 'S_Device_' . $fcode;

    $deviceId = $redis->hget ( $vehicleDeviceMapId, $vehicleId );

    $redis->srem ( $cpyDeviceSet, $deviceId );

$refDataJson1=$redis->hget ( 'H_RefData_' . $fcode, $vehicleId);//ram
$refDataJson1=json_decode($refDataJson1,true);

$orgId=$refDataJson1['orgId'];

$redis->hdel ( 'H_RefData_' . $fcode, $vehicleId );

$redis->hdel('H_Device_Cpy_Map',$deviceId);

$redisVehicleId = $redis->hget ( $vehicleDeviceMapId, $deviceId );

$redis->hdel ( $vehicleDeviceMapId, $redisVehicleId );

$redis->hdel ( $vehicleDeviceMapId, $deviceId );

$redis->srem ( 'S_Vehicles_' . $fcode, $redisVehicleId );

$redis->srem ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleId);

$groupList = $redis->smembers('S_Groups_' . $fcode);

foreach ( $groupList as $group ) {

    $result = $redis->srem($group,$redisVehicleId);
//            Log::info('going to delete vehicle from group ' . $group . $redisVehicleId . $result);
}



$redis->srem('S_Vehicles_Dealer_'.Session::get('page').'_'.$fcode,$vehicleId);
$redis->srem('S_Vehicles_Dealer_'.$username.'_'.$fcode,$vehicleId);

$redis->srem('S_Vehicles_Admin_'.$fcode,$vehicleId);




Session::flash ( 'message', 'Successfully deleted ' . $deviceId . '!' );
return Redirect::to ( 'vdmVehicles' );
}


public function multi() {
    Log::info(' inside multi....');
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    Log::info(' inside multi ' );

    $orgList = $redis->smembers('S_Organisations_'. $fcode);



    $orgArr = array();
    foreach($orgList as $org) {
        $orgArr = array_add($orgArr, $org,$org);
    }
    $orgList = $orgArr;

    return View::make ( 'vdm.vehicles.multi' )->with('orgList',$orgList);       

}


public function dealerSearch() {
    Log::info('------- inside dealerSearch--------');
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    Log::info(' inside multi ' );

    $dealerId = $redis->smembers('S_Dealers_'. $fcode);



    $orgArr = array();
    foreach($dealerId as $org) {
        $orgArr = array_add($orgArr, $org,$org);
    }
    $dealerId = $orgArr;




    return View::make ( 'vdm.vehicles.dealerSearch' )->with('dealerId',$dealerId);       

}




public function findDealerList() {
    log::info( '-----------List----------- ::');
    if (! Auth::check () ) {
        return Redirect::to ( 'login' );
    }

    $username = Input::get ( 'dealerId' );



    if($username==null)
    {
        log::info( '--------use one----------' );
        $username = Session::get('page');
    }
    else{
        log::info( '--------use two----------' );
        Session::put('page',$username);
    }

    try{
        $user=User::where('username', '=', $username)->firstOrFail();
        log::info( '--------new name----------' .$user);
        Auth::login($user);
    }catch(\Exception $e)
    {
        return Redirect::to ( 'vdmVehicles/dealerSearch' ); 
    }
//$user = User::find(10);




    return Redirect::to ( 'Business' );
}





//ram

public function stops($id,$demo) {
    Log::info(' --------------inside 1-----------------'.$id);
    Log::info(' --------------inside url-----------------'.Request::url() );

    $redis = Redis::connection();
    $ipaddress = $redis->get('ipaddress');
    Log::info(' stops Ip....'.$ipaddress);
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    Log::info('id------------>'.$username);
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    Log::info('id------------>'.$fcode);
    $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $id );           
    $vehicleRefData=json_decode($vehicleRefData,true);

    $orgId=$vehicleRefData['orgId'];
    Log::info('id------------>'.$orgId);
    $type=0;
    $url = 'http://' .$ipaddress . ':9000/getSuggestedStopsForVechiles?vehicleId=' . $id . '&fcode=' . $fcode . '&orgcode=' .$orgId . '&type=' .$type.'&demo='.$demo;
    $url=htmlspecialchars_decode($url);

    log::info( ' url :' . $url);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
// Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $response = curl_exec($ch);
    log::info( ' response :' . $response);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    log::info( 'finished');

    $sugStop = json_decode($response,true);
    log::info( ' user :');
    if(!$sugStop['error']==null)
    {
        log::info( ' ---------inside null--------- :');

//return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$id)->with('demo',$demo);

    }               
// var_dump($sugStop);
    $value = $sugStop['suggestedStop'];
    log::info( ' 1 :');
//  var_dump($value);

    $address = array();
    log::info( ' 2 :');
    try
    {


        foreach($value as $org => $geoAddress) {                                   
            $rowId1 = json_decode($geoAddress,true);
            $t =0;
            foreach($rowId1 as $org1 => $rowId2) {
                if ($t==1)
                {
                    $address = array_add($address, $org,$rowId2.' '.$rowId1['time']);
                    log::info( $org.' 3 :' . $t .$rowId2.$rowId1['time']);

                }

                $t++;
            }
            log::info( ' final :'.$t);    
        }     
    }catch(\Exception $e)
    {
        return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$id)->with('demo',$demo); 
    }                          
    $sugStop = $address;              
    log::info( ' success :');
    return View::make ( 'vdm.vehicles.showStops' )->with('sugStop',$sugStop)->with('vehicleId',$id);       

}



public function migration($id)
{

    Log::info('.........migration........');
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;

    $redis = Redis::connection ();
    $vehicleId = $id;
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
    $deviceId = $redis->hget ( $vehicleDeviceMapId, $vehicleId );

    $details = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );

    $refData=null;
    $refData = array_add($refData, 'overSpeedLimit', '50');
    $refData = array_add($refData, 'driverName', '');
    $refData = array_add($refData, 'gpsSimNo', '');
    $refData = array_add($refData, 'email', ' ');
    $refData = array_add($refData, 'odoDistance', '0');
    $refData = array_add($refData, 'sendGeoFenceSMS', 'no');
    $refData = array_add($refData, 'morningTripStartTime', ' ');
    $refData = array_add($refData, 'eveningTripStartTime', ' ');
    $refData= array_add($refData, 'altShortName',' ');
    $refData= array_add($refData, 'date',' ');
    $refData= array_add($refData, 'paymentType',' ');
    $refData= array_add($refData, 'expiredPeriod',' ');

    $refDataFromDB = json_decode ( $details, true );




    $refDatatmp = array_merge($refData,$refDataFromDB);

    $refData=$refDatatmp;
//S_Schl_Rt_CVSM_ALH



    $orgId =isset($refDataFromDB['orgId'])?$refDataFromDB['orgId']:'NotAvailabe';
    Log::info(' orgId = ' . $orgId);
    $expiredPeriod =isset($refDataFromDB['expiredPeriod'])?$refDataFromDB['expiredPeriod']:'NotAvailabe';
    $expiredPeriod=str_replace(' ', '', $expiredPeriod);
    log::info( '------expiredPeriod ---------- '.$expiredPeriod);

    $refData = array_add($refData, 'orgId', $orgId);
    $parkingAlert = isset($refDataFromDB->parkingAlert)?$refDataFromDB->parkingAlert:0;
    $refData= array_add($refData,'parkingAlert',$parkingAlert);
    $tmpOrgList = $redis->smembers('S_Organisations_' . $fcode);
    log::info( '------migration 1---------- '.Session::get('cur'));
    if(Session::get('cur')=='dealer')
    {
        log::info( '------migration 2---------- '.Session::get('cur'));
        $tmpOrgList = $redis->smembers('S_Organisations_Dealer_'.$username.'_'.$fcode);
    }
    else if(Session::get('cur')=='admin')
    {
        $tmpOrgList = $redis->smembers('S_Organisations_Admin_'.$fcode);
    }


    $orgList=null;
    $orgList=array_add($orgList,'Default','Default');
    foreach ( $tmpOrgList as $org ) {
        $orgList = array_add($orgList,$org,$org);               
    }
    $deviceId=$refData['deviceId'];
//  var_dump($refData);
    return View::make ( 'vdm.vehicles.migration', array (
        'vehicleId' => $vehicleId ) )->with ( 'deviceId', $deviceId )->with('expiredPeriod',$expiredPeriod);


}


public function removeStop($id,$demo) {
    Log::info(' --------------inside remove-----------------'.$id);

    Log::info(' --------------inside remove-----------------'.$demo);
    $redis = Redis::connection();
    $ipaddress = $redis->get('ipaddress');
    Log::info(' stops Ip....'.$ipaddress);
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    Log::info('id------------>'.$username);
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    Log::info('id------------>'.$fcode);
    $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $id );           
    $vehicleRefData=json_decode($vehicleRefData,true);

    $orgId=$vehicleRefData['orgId'];
    $routeNo=$vehicleRefData['shortName'];
    Log::info('org------------>'.$orgId);
    Log::info('route------------>'.$routeNo);


    $suggeststop=$redis->LRANGE ('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode , 0, -1);
    $suggeststop1=$redis->LRANGE ('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode , 0, -1);
    if(!$suggeststop==null)
    {
        if($demo=='normal')
        {



            $arraystop= $redis->lrange('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode ,0 ,-1);
            foreach($arraystop as $org => $geoAddress){
                Log::info('inside value present------------>'.$org);
                $redis->hdel('H_Bus_Stops_'.$orgId.'_'.$fcode , $routeNo.':stop'.$org);
            }
            $redis->del('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode);
            $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , $routeNo.':morning');
            $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , $routeNo.':evening');
            $redis->srem('S_Organisation_Route_'.$orgId.'_'.$fcode,$routeNo);



//HDEL myhash
            return Redirect::to ( 'vdmVehicles' ); 
        }
    }
    if(!$suggeststop1==null)
    {
        Log::info('1');
        if($demo=='alternate')
        {
            Log::info('2');
            $arraystop= $redis->lrange('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode ,0 ,-1);
            foreach($arraystop as $org => $geoAddress){
                Log::info('inside value present------------>'.$org);
                $redis->hdel('H_Bus_Stops_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':stop'.$org);
            }
            $redis->del('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode);
            $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':morning');
            $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':evening');




//HDEL myhash
            return Redirect::to ( 'vdmVehicles' ); 
        }
    }
    else{
        Log::info('inside no value present------------>');
        return Redirect::to ( 'vdmVehicles' );
    }
// L_Suggest_$routeNo_$orgId_$fcode
//H_Stopseq_$orgId_$fcode $routeNo:morning
//H_Stopseq_$orgId_$fcode $routeNo:evening
// return View::make ( 'vdm.vehicles.showStops' )->with('sugStop',$sugStop)->with('vehicleId',$id);       

}



public function stops1($id,$demo) {
    Log::info(' --------------inside 1-----------------'.$id);
    Log::info(' --------------inside url-----------------'.Request::url() );

    $redis = Redis::connection();
    $ipaddress = $redis->get('ipaddress');
    Log::info(' stops Ip....'.$ipaddress);
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    Log::info('id------------>'.$username);
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    Log::info('id------------>'.$fcode);
    $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $id );           
    $vehicleRefData=json_decode($vehicleRefData,true);

    $orgId=$vehicleRefData['orgId'];
    Log::info('id------------>'.$orgId);
    $type=0;
    $url = 'http://' .$ipaddress . ':9000/getSuggestedStopsForVechiles?vehicleId=' . $id . '&fcode=' . $fcode . '&orgcode=' .$orgId . '&type=' .$type.'&demo='.$demo;
    $url=htmlspecialchars_decode($url);

    log::info( ' url :' . $url);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
// Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $response = curl_exec($ch);
    log::info( ' response :' . $response);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    log::info( 'finished');

    $sugStop = json_decode($response,true);
    log::info( ' user :');
    if(!$sugStop['error']==null)
    {
        log::info( ' ---------inside null--------- :');

//return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$id)->with('demo',$demo);

    }               
// var_dump($sugStop);
    $value = $sugStop['suggestedStop'];
    log::info( ' 1 :');
//  var_dump($value);

    $address = array();
    log::info( ' 2 :');
    try
    {


        foreach($value as $org => $geoAddress) {                                   
            $rowId1 = json_decode($geoAddress,true);
            $t =0;
            foreach($rowId1 as $org1 => $rowId2) {
                if ($t==1)
                {
                    $address = array_add($address, $org,$rowId2.' '.$rowId1['time']);
                    log::info( $org.' 3 :' . $t .$rowId2);

                }

                $t++;
            }
            log::info( ' final :'.$t);    
        }     
    }catch(\Exception $e)
    {
        return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$id)->with('demo',$demo); 
    }                          
    $sugStop = $address;              
    log::info( ' success :');
    return View::make ( 'vdm.vehicles.dealerSearch' )->with('sugStop',$sugStop)->with('vehicleId',$id);       

}


public function removeStop1($id,$demo) {
    Log::info(' --------------inside remove1-----------------'.$id);

    Log::info(' --------------inside remove1-----------------'.$demo);
    $redis = Redis::connection();
    $ipaddress = $redis->get('ipaddress');
    Log::info(' stops Ip....'.$ipaddress);
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    Log::info('id------------>'.$username);
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    Log::info('id------------>'.$fcode);
    $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $id );           
    $vehicleRefData=json_decode($vehicleRefData,true);

    $orgId=$vehicleRefData['orgId'];
    $routeNo=$vehicleRefData['shortName'];
    Log::info('org------------>'.$orgId);
    Log::info('route------------>'.$routeNo);


    $suggeststop=$redis->LRANGE ('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode , 0, -1);
    $suggeststop1=$redis->LRANGE ('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode , 0, -1);
    if(!$suggeststop==null)
    {
        if($demo=='normal')
        {



            $arraystop= $redis->lrange('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode ,0 ,-1);
            foreach($arraystop as $org => $geoAddress){
                Log::info('inside value present------------>'.$org);
                $redis->hdel('H_Bus_Stops_'.$orgId.'_'.$fcode , $routeNo.':stop'.$org);
            }
            $redis->del('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode);
            $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , $routeNo.':morning');
            $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , $routeNo.':evening');
            $redis->srem('S_Organisation_Route_'.$orgId.'_'.$fcode,$routeNo);



//HDEL myhash
            return Redirect::to ( 'vdmVehicles/dealerSearch' ); 
        }
    }
    if(!$suggeststop1==null)
    {
        Log::info('1');
        if($demo=='alternate')
        {
            Log::info('2');
            $arraystop= $redis->lrange('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode ,0 ,-1);
            foreach($arraystop as $org => $geoAddress){
                Log::info('inside value present------------>'.$org);
                $redis->hdel('H_Bus_Stops_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':stop'.$org);
            }
            $redis->del('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode);
            $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':morning');
            $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':evening');




//HDEL myhash
            return Redirect::to ( 'vdmVehicles/dealerSearch' ); 
        }
    }
    else{
        Log::info('inside no value present------------>');
        return Redirect::to ( 'vdmVehicles/dealerSearch' );
    }
// L_Suggest_$routeNo_$orgId_$fcode
//H_Stopseq_$orgId_$fcode $routeNo:morning
//H_Stopseq_$orgId_$fcode $routeNo:evening
// return View::make ( 'vdm.vehicles.showStops' )->with('sugStop',$sugStop)->with('vehicleId',$id);       

}



public function generate()
{

    log::info(" inside generate");
    $vehicleId=Input::get('vehicleId');
    $type=Input::get('type');
    $demo=Input::get('demo');
    log::info("id------------>".$type);
    log::info("demo------------>".$demo);
    log::info(" inside generate .." . $vehicleId);         
    $rules = array (
        'date' => 'required|date:dd-MM-yyyy|',
        'mst' => 'required|date_format:H:i',
        'met' => 'required|date_format:H:i',
        'est' => 'required|date_format:H:i',
        'eet' => 'required|date_format:H:i',
        'type' => 'required'

        );
    log::info(" inside 1 .." . $vehicleId);
    $validator = Validator::make ( Input::all (), $rules );
    log::info(" inside 2 .." . $vehicleId);
    if ($validator->fails ()) {
        log::info(" inside 4 .." . $vehicleId);
        return Redirect::to ( 'vdmVehicles/stops/'.$vehicleId .'/'.$demo)->withErrors ( $validator );
    } else {
        log::info(" inside 3 .." . $vehicleId);
        $date=Input::get('date');
        $mst=Input::get('mst');
        $met=Input::get('met');
        $est=Input::get('est');
        $eet=Input::get('eet');

        Log::info(' inside generate....'.$date .$mst .$met .$est .$eet. $vehicleId);                                             
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection();
        $ipaddress = $redis->get('ipaddress');
        $parameters='?userId='. $username;
        Log::info('id------------>'.$username);
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        Log::info('id------------>'.$fcode);
        $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );           
        $vehicleRefData=json_decode($vehicleRefData,true);

        $orgId=$vehicleRefData['orgId'];
        Log::info('id------------>'.$orgId);
        $parameters=$parameters . '&vehicleId=' . $vehicleId . '&fcode=' . $fcode . '&orgcode=' .$orgId. '&presentDay=' . $date . '&mST=' .$mst. '&mET=' .$met. '&eST=' .$est . '&eET='.$eet .'&type='.$type.'&demo='.$demo;                               
        $url = 'http://' .$ipaddress . ':9000/getSuggestedStopsForVechiles?'. $parameters;
        $url=htmlspecialchars_decode($url);
        log::info( ' url :' . $url);   
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
// Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 150);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $response = curl_exec($ch);
        log::info( ' response :' . $response);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $sugStop1 = json_decode($response,true);
        log::info( ' ------------check----------- :');
        if(!$sugStop1['error']==null)
        {
            log::info( ' ---------inside null--------- :');
            return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$vehicleId)->with('demo',$demo);
        }

        $url = 'http://' .$ipaddress . ':9000/getSuggestedStopsForVechiles?vehicleId=' . $vehicleId . '&fcode=' . $fcode . '&orgcode=' .$orgId . '&type=' .$type.'&demo='.$demo;
        $url=htmlspecialchars_decode($url);

        log::info( ' url :' . $url);   
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
// Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $response = curl_exec($ch);
        log::info( ' response :' . $response);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        log::info( 'finished');

        $sugStop = json_decode($response,true);
        log::info( ' user :');
        if(!$sugStop['error']==null)
        {
            log::info( ' ---------inside null--------- :');
            return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$vehicleId)->with('demo',$demo);
        }
        $value = $sugStop['suggestedStop'];
        log::info( ' 1 :');
        $address = array();
        log::info( ' 2 :');
        foreach($value as $org => $rowId) {                                              
            $rowId1 = json_decode($rowId,true);
            $t =0;
            foreach($rowId1 as $org1 => $rowId2) {
                if ($t==1)
                {
                    $address = array_add($address, $org,$rowId2.' '.$rowId1['time']);
                    log::info( $org.' a3 :' . $t .$rowId2.$rowId1['time']);

                }

                $t++;
            }
            log::info( ' final :'.$t);                    
        }                     
        $sugStop = $address;              
        log::info( ' success :'); 
        return View::make ( 'vdm.vehicles.showStops' )->with('sugStop',$sugStop)->with('vehicleId',$vehicleId)->with('demo',$demo);  

    }

}

public function storeMulti() {
    Log::info(' inside multiStore....');
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

    $vehicleDetails = Input::get ( 'vehicleDetails' );

    $orgId = Input::get('orgId');

    Log::info(' inside multi ' . $orgId);

    $redis = Redis::connection ();
    $redis->set('MultiVehicle:'.$fcode, $vehicleDetails) ;
    $who=Session::get('cur');
    $parameters = 'key='.'MultiVehicle:'.$fcode . '&orgId='.$orgId. '&who='.$who. '&username='.$username;

//TODO - remove ..this is just for testing
// $ipaddress = 'localhost';
    $ipaddress = $redis->get('ipaddress');
    $url = 'http://' .$ipaddress . ':9000/addMultipleVehicles?' . $parameters;
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

    return Redirect::to ( 'vdmVehicles' );  



}


}