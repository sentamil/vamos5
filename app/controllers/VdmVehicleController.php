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
    $statusList = null;
    foreach ( $vehicleList as $key => $vehicle  ) {

        Log::info($key.'$vehicle ' .$vehicle);
        $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );

        if(isset($vehicleRefData)) {
            Log::info('$vehicle ' .$vehicleRefData);
        }else {
            continue;
        }
        $vehicleRefData=json_decode($vehicleRefData,true);

        $deviceId = $vehicleRefData['deviceId'];


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
        $statusSeperate = explode(',', $statusVehicle); //log::info($statusVehicle);
        $statusSeperate1=isset($statusSeperate[7])?$statusSeperate[7]:'N';
        $statusList = array_add($statusList, $vehicle, $statusSeperate1); 
       // $statusList = array_add($statusList, $vehicle, $statusSeperate[7]);


        }
        else
        {
             Log::info('$inside remove ' .$vehicle);
            unset($vehicleList[$key]);
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
    return View::make ( 'vdm.vehicles.index', array (
        'vehicleList' => $vehicleList
        ) )->with ( 'deviceList', $deviceList )->with('shortNameList',$shortNameList)->with('portNoList',$portNoList)->with('mobileNoList',$mobileNoList)->with('demo',$demo)->with ( 'user', $user )->with ( 'orgIdList', $orgIdList )->with ( 'deviceModelList', $deviceModelList )->with ( 'expiredList', $expiredList )->with ( 'tmp', 0 )->with ('statusList', $statusList)->with('dealerId',$dealerId); 
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
        $vehicleId='GPSVTS_'.substr($deviceId, -6);

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
	    $vehicleExpiry = Input::get ( 'vehicleExpiry' );
	    $onboardDate = Input::get ( 'onboardDate' );
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
        $rfidType = Input::get('rfidType');
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
if(Session::get('cur')=='dealer')
{
   $ownership=$username;
}
else if(Session::get('cur')=='admin')
{
    $ownership='OWN';
}


        $refDataArr = array (
            'deviceId' => $deviceId,
            'shortName' => $shortName,
            'deviceModel' => $deviceModel,
            'regNo' => $regNo,
            'vehicleMake' => $vehicleMake,
            'vehicleType' => $vehicleType,
            'oprName' => $oprName,
            'mobileNo' => $mobileNo,
		    'vehicleExpiry' => $vehicleExpiry,
		    'onboardDate' => $onboardDate,
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
            'rfidType'=>$rfidType,
            'OWN'=>$ownership,

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
$tmpPositon =  '13.104870,80.303138,0,N,' . $time . ',0.0,N,N,ON,' .$odoDistance. ',S,N';
if(isset($franchiseDetails['fullAddress'])==1)
{
    $fullAddress=$franchiseDetails['fullAddress'];
    $data_arr = BusinessController::geocode($fullAddress);
    if($data_arr){        
        $latitude = $data_arr[0];
        $longitude = $data_arr[1];
        log::info( '------lat lang---------- '.$latitude.','.$longitude);
        $tmpPositon =  $latitude.','.$longitude.',0,N,' . $time . ',0.0,N,N,ON,' .$odoDistance. ',S,N';
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
	 $refData = array_add($refData, 'vehicleExpiry', 'null');
	 $refData = array_add($refData, 'onboardDate', ' ');
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
        $refData= array_add($refData, 'rfidType', 'no');
        $refData= array_add($refData, 'shortName', 'nill');
        $refData= array_add($refData, 'Licence', '');
        $refData= array_add($refData, 'Payment_Mode', '');
        $refData= array_add($refData, 'descriptionStatus', '');
        $refData= array_add($refData, 'ipAddress', '');
        $refData= array_add($refData, 'portNo', '');
        $refData= array_add($refData, 'analog1', '');
        $refData= array_add($refData, 'analog2', '');
        $refData= array_add($refData, 'digital1', '');
        $refData= array_add($refData, 'digital2', '');
        $refData= array_add($refData, 'serial1', '');
        $refData= array_add($refData, 'serial2', '');
        $refData= array_add($refData, 'digitalout', '');
        $refData= array_add($refData, 'mintemp', '');
        $refData= array_add($refData, 'maxtemp', '');
        $refData= array_add($refData, 'routeName', '');

        

        
        
//            $refData= array_add($refData, 'fuelType', 'digital');
        $refDataFromDB = json_decode ( $details, true );




        $refDatatmp = array_merge($refData,$refDataFromDB);

        $refData=$refDatatmp;
//S_Schl_Rt_CVSM_ALH



        $orgId =isset($refDataFromDB['orgId'])?$refDataFromDB['orgId']:'NotAvailabe';
        Log::info(' orgId = ' . $orgId);

        $routeList  = $redis->smembers('S_RouteList_'.$orgId.'_'.$fcode);
        

        $routeLIST =null;
        $routeLIST = array_add($routeLIST,'nil','nill');
        foreach ( $routeList as $route ) {
            $routeLIST = array_add($routeLIST,$route,$route);
        }


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

        $protocol = VdmFranchiseController::getProtocal();

        return View::make ( 'vdm.vehicles.edit', array (



            'vehicleId' => $vehicleId ) )->with ( 'refData', $refData )->with ( 'orgList', $orgList )->with('Licence',$Licence1)->with('Payment_Mode',$Payment_Mode1)->with ('protocol', $protocol)->with ('routeName',$routeLIST);
    }catch(\Exception $e)
    {
        log::info( '------exception---------- '.$e->getMessage());
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
        $refData= array_add($refData, 'Licence', '');
        $refData= array_add($refData, 'Payment_Mode', '');
         $refData= array_add($refData, 'descriptionStatus', '');
         $refData= array_add($refData, 'mintemp', '');
         $refData= array_add($refData, 'maxtemp', '');

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

//  var_dump($refData);
        return View::make ( 'vdm.vehicles.edit1', array (
            'vehicleId' => $vehicleId ) )->with ( 'refData', $refData )->with ( 'orgList', $orgList )->with('Licence',$Licence1)->with('Payment_Mode',$Payment_Mode1);
    }catch(\Exception $e)
    {
        return Redirect::to ( 'vdmVehicles' );
    }
}

public function updateCalibration() {
    
    $calibrateCount = Session::get('key');
    Session::forget('key');
   
    Log::info('-------------inside calibrate add-----------'.$calibrateCount);

    $temp=0;
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $vehicleId = Input::get ('vehicleId');
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );


    if($calibrateCount <=0 || $calibrateCount == null) 
    {
        $calibrateCount = $redis->ZCOUNT ( 'Z_Sensor_'.$vehicleId.'_'.$fcode,'-inf','+inf');
        Log::info('-------zcount value--------'.$calibrateCount);
    }

    $redis->del ( 'Z_Sensor_'.$vehicleId.'_'.$fcode );
    // log::info(' forloop count  '.$)
    for ($p = $calibrateCount; $p>=$temp; $p--)
    {
        $volt=Input::get ('volt'.$p);
        $litre=Input::get ('litre'.$p);
        log::info($volt.'---------------vechile--11----------- ::' .$litre);

        if($litre>0 && ($volt==null || $volt == 0)){

            $volt =  0.001;

            log::info($volt.'---------------vechile--12----------- ::' .$litre);

        }
        
        if((!$litre==null || $litre==0) && (!$volt==null))
        {
           
            
            log::info(' volt value '.$volt.' count '.$calibrateCount);
            log::info( $volt.'---------------vechile------- ::' .$litre);
            $redis->zadd ( 'Z_Sensor_'.$vehicleId.'_'.$fcode,$volt,$litre);
        }


    }
    Log::info('-------------outside calibrate add-----------');
    
    
return Redirect::to ( 'vdmVehicles' );
}





public static function calibrate($id,$temp) {

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
    log::info(array($address1));
    // $temp=null;
    $v=0;
    foreach($address1 as $org => $rowId)
    {



        $ahan=$rowId[1].':'.$rowId[0];

        log::info( $rowId[1].'inside no'.$ahan.' result' .$rowId[0]);
        //$place = array_add($place, $rowId[1].':'.$rowId[0],$ahan);
        $place = array_add($place,$v,$ahan);
        $v++;
    }

// $volt=Input::get ('count_Calib');
// log::info(' volt  '.$volt);
// $valu = count($place);

// log(' valua  ===> '+$valu);
// if((count($place)>0 && $temp==0) || ($temp!=0 && $temp<=count($place)))
// {
//     log::info( $temp.'---------------place------------- ::' .count($place));
//     $temp = count($place);
// }

    $temp= $temp-count($place);

    log::info(' temp plavce '.count($place));
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


public function calibrateCount(){
    
    $calibrateVehi = Input::get ( 'vehicleId' );
    $listValue = Input::get ( 'listvalue' );
    $count = Input::get ( 'count_Calib' );
    if($count == null){
        $count = 0;
    }
    $total = (int)$count+(int)$listValue;
    Session::forget('key');
    Session::put('key', $total);
    $url = 'vdmVehicles/calibrateOil/'.$calibrateVehi.'/'.$total;
    log::info($url);
    return Redirect::to ( $url );
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
//ram-new-key---
$refDataJson1=$redis->hget ( 'H_RefData_' . $fcode, $vehicleId);
$refDataJson1=json_decode($refDataJson1,true);
//log::info($refDataJson1);
$shortName1=$refDataJson1['shortName'];
$shortNameOld=strtoupper($shortName1);
$mobileNoOld=isset($refDataJson1['mobileNo'])?$refDataJson1['mobileNo']:'';
$orgIdOld1=$refDataJson1['orgId'];
$orgIdOld=strtoupper($orgIdOld1);
 $orgId=$refDataJson1['orgId'];	
 $orgId1=strtoupper($orgId);	
 $own=$refDataJson1['OWN'];	
//log::info('areeeerrrrr'.$own);	
//---    

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
        $shortName1 = Input::get ( 'shortName' );
		$shortName = strtoupper($shortName1);
        $regNo = Input::get ( 'regNo' );
        $vehicleMake = Input::get ( 'vehicleMake' );
        $vehicleType = Input::get ( 'vehicleType' );
        $oprName = Input::get ( 'oprName' );
        $mobileNo = Input::get ( 'mobileNo' );
	  $vehicleExpiry = Input::get ( 'vehicleExpiry' );
	  $onboardDate = Input::get ( 'onboardDate' );
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
        $rfidType=Input::get ('rfidType');
        $ipAddress=Input::get ('ipAddress');
        $portNo=Input::get ('portNo');
        $analog1=Input::get ('analog1');
        $analog2=Input::get ('analog2');
        $digital1=Input::get ('digital1');
        $digital2=Input::get ('digital2');
        $serial1=Input::get ('serial1');
        $serial2=Input::get ('serial2');
        $digitalout=Input::get ('digitalout');
        $mintemp=Input::get ('mintemp');
        $maxtemp=Input::get ('maxtemp');
        $routeName = Input::get('routeName');
        

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


        $Licence=Input::get ( 'Licence1');    
        $Licence=!empty($Licence) ? $Licence : 'Advance';
        $descriptionStatus=Input::get ( 'descriptionStatus');    
        $descriptionStatus=!empty($descriptionStatus) ? $descriptionStatus : '';

        $Payment_Mode=Input::get ( 'Payment_Mode1');  
        $Payment_Mode=!empty($Payment_Mode) ? $Payment_Mode : 'Monthly';

//    $odoDistance=$vehicleRefData['odoDistance'];
//gpsSimNo
//    $gpsSimNo=$vehicleRefData['gpsSimNo'];
if(Session::get('cur')=='dealer')
{
   $ownership=$username;
}
else if(Session::get('cur')=='admin')
{
    $ownership='OWN';
}


        $refDataArr = array (
            'deviceId' => $deviceId,
            'shortName' => $shortName,
            'deviceModel' => $deviceModel,
            'regNo' => $regNo,
            'vehicleMake' => $vehicleMake,
            'vehicleType' => $vehicleType,
            'oprName' => $oprName,
            'mobileNo' => $mobileNo,
			//'vehicleExpiry' => $vehicleExpiry,
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
            'rfidType'=>$rfidType,
            'OWN'=>$ownership,
            'Licence'=>$Licence,
            'Payment_Mode'=>$Payment_Mode,
            'descriptionStatus'=>$descriptionStatus,
            'ipAddress'=>$ipAddress,
            'portNo'=>$portNo,
            'analog1'=>$analog1,
            'analog2'=>$analog2,
            'digital1'=>$digital1,
            'digital2'=>$digital2,
            'serial1'=>$serial1,
            'serial2'=>$serial2,
            'digitalout'=>$digitalout,
            'mintemp'=>$mintemp,
            'maxtemp'=>$maxtemp,
            'routeName'=>$routeName,
		'vehicleExpiry' => $vehicleExpiry,
		'onboardDate' => $onboardDate,
            );

try{
$licence_id = DB::select('select licence_id from Licence where type = :type', ['type' => $Licence]);
$payment_mode_id = DB::select('select payment_mode_id from Payment_Mode where type = :type', ['type' => $Payment_Mode]);
        log::info( $licence_id[0]->licence_id.'-------- av  in  ::----------'.$payment_mode_id[0]->payment_mode_id);
        
$licence_id=$licence_id[0]->licence_id;
$payment_mode_id=$payment_mode_id[0]->payment_mode_id;
        DB::table('Vehicle_details')
            ->where('vehicle_id', $vehicleId)
            ->where('fcode', $fcode)
            ->update(array('licence_id' => $licence_id,
                'payment_mode_id' => $payment_mode_id,
                'status'=>$descriptionStatus,
                ));

}catch(\Exception $e)
        {
            Log::info($vehicleId.'--------------------inside Exception--------------------------------');
        }
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
//ram-new-key--
$orgId1=strtoupper($orgId);
$redis->hdel ('H_VehicleName_Mobile_Org_' .$fcode, $vehicleId.':'.$deviceId.':'.$shortNameOld.':'.$orgIdOld.':'.$mobileNoOld);
$redis->hset ('H_VehicleName_Mobile_Org_' .$fcode, $vehicleId.':'.$deviceId.':'.$shortName.':'.$orgId1.':'.$mobileNo, $vehicleId);
//---
   if($own!=='OWN')	
	   {
		$redis->hdel('H_VehicleName_Mobile_Dealer_'.$own.'_Org_'.$fcode, $vehicleId.':'.$deviceId.':'.$shortNameOld.':'.$orgIdOld.':'.$mobileNoOld);	
		$redis->hset('H_VehicleName_Mobile_Dealer_'.$own.'_Org_'.$fcode, $vehicleId.':'.$deviceId.':'.$shortName.':'.$orgId1.':'.$mobileNo, $vehicleId );
	   }
    else if($own=='OWN')
    {
	 $redis->hdel('H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode, $vehicleId.':'.$deviceId.':'.$shortNameOld.':'.$orgIdOld.':'.$mobileNoOld.':OWN');	
	 $redis->hset('H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode, $vehicleId.':'.$deviceId.':'.$shortName.':'.$orgId1.':'.$mobileNo.':OWN', $vehicleId );
	}		
//



//$redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);
$redis->sadd ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleId);

$redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );

$redis->hmset ( $vehicleDeviceMapId, $vehicleId, $deviceId, $deviceId, $vehicleId );
$redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );
$redis->hset('H_Device_Cpy_Map',$deviceId,$fcode);
// redirect
Session::flash ( 'message', 'Successfully updated ' . $vehicleId . '!' );

/*
    checking old and new refdata for sending mail...

$devices=null;
				$devicestypes=null;
				$i=0;
				foreach($details as $key => $value)
				{
					$valueData=json_decode($value,true);
					$devices = array_add($devices, $i,$valueData['deviceid']);
					$devicestypes = array_add($devicestypes,$i,$valueData['deviceidtype']);


*/

log::info('     gettype of the var     ');
$refVehicle   = json_encode ( $vehicleRefData );
log::info(gettype($refVehicle));
log::info(gettype($refDataJson));
if($refVehicle != $refDataJson)
{  
        $devices=array();
        $devicestypes=array();
 $mapping_Array = array(

        "shortName" => "Vehicle Name",
        "deviceModel" => "Device Model",
        "regNo" => "Vehicle Registration Number",
        "vehicleType" => "Vehicle Type",
        "oprName" => "Telecom Operator Name",
        "mobileNo" => "Mobile Number for Alerts",
	"vehicleExpiry" => "Vehicle_Expire",
	"onboardDate" => "Onboard-Date",
        "overSpeedLimit" => "OverSpeed Limit",
        "odoDistance" => "Odometer Reading",
        "driverName" => "Driver Name",
        "gpsSimNo" => "GPS Sim Number",
        "email" => "Email for Notification",
        "orgId" => "Org/College Name",
        "altShortName" => "Alternate Vehicle Name",
        "fuelType" => "Fuel Type",
        "isRfid" => "IsRFID",
        "rfidType" => "Rfid Type",
        "Licence" => "Licence",
        "Payment_Mode" => "Payment Mode",
        "descriptionStatus" => "Description",
        "ipAddress" => "IP Address",
        "portNo" => "Port Number",
        "analog1" => "Analog input 1",
        "analog2" => "Analog input 2",
        "digital1" => "Digital input 1",
        "digital2" => "Digital input 2",
        "serial1" => "Serial input 1",
        "serial2" => "Serial input 2",
        "digitalout" => "Digital output",
        "mintemp" => "Minimum Temperature",
        "maxtemp" => "Maximum Temperature",
        "routeName" => "Route Name",

    );
    $updated_Value  = json_decode($refDataJson,true);
    // $oldJsonValue   = json_decode($refVehicle,true);
    foreach ($updated_Value as $update_Key => $update_Value)
    {
        try{

	log::info("------old_Key-----");
        // log::info($update_Key);
        // log::info($update_Value);
        // log::info($updated_Value[$update_Key]);
//if(isset($vehicleRefData[$update_Key])){
        if($vehicleRefData[$update_Key] != $update_Value){

                log::info(' mapping ' );
                log::info(isset($mapping_Array[$update_Key]));
                if(isset($mapping_Array[$update_Key]))
                {
                        $devices = array_add($devices, $mapping_Array[$update_Key],$vehicleRefData[$update_Key]);
                        $devicestypes = array_add($devicestypes, $mapping_Array[$update_Key],$updated_Value[$update_Key]);
                }
        }
//} else {


 //           $devices = array_add($devices, $mapping_Array[$update_Key],"");
  //          $devicestypes = array_add($devicestypes, $mapping_Array[$update_Key],$updated_Value[$update_Key]);

   //     }
}catch(\Exception $e)
        {
            Log::info($vehicleId.'--------------------inside Exception--------------------------------');
                //$devices = array_add($devices, $mapping_Array[$update_Key],"");
                //$devicestypes = array_add($devicestypes, $mapping_Array[$update_Key],$updated_Value[$update_Key]);
                log::info($e);
        }
    }

if($ownership == 'OWN'){

        $gettingMail    =   $redis->hget ( 'H_Franchise' , $fcode );
        $emailKeys      =   'email2';

    } else{

        $gettingMail    =   $redis->hget ( 'H_DealerDetails_' . $fcode, $ownership );
        $emailKeys      =   'email';

    }
    $gettingMail=json_decode($gettingMail,true);
    log::info(" --------  gettingMail  ------");
    
    try{

        Session::put('email',$gettingMail[$emailKeys]);
        Mail::queue('emails.updateDetails', array('fname'=>$fcode,'userId'=>$vehicleId, 'oldRef'=>$devices, 'newRef'=>$devicestypes), function($message) use ($vehicleId)
        {
            Log::info("Inside email :" . Session::get ( 'email' ));
            $message->to(Session::pull ( 'email' ))->subject('Vehicle data updated -'.$vehicleId);
        });
    } catch (\Exception $e){

        Log::info('  Mail Error ');
    }
    log::info( "Message sent one  successfully...");
}
//ram-vehicleExpiry
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
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
//
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
        $mobileNo = Input::get ( 'mobileNo' );
		$routeName = Input::get ( 'routeName' );
        log::info(' mobileNo value  '.$mobileNo.'  vehihile type   '.$vehicleType);
		//
		$vehicleExpiry = Input::get ( 'vehicleExpiry' );
        log::info(' vehicleExpiry value  '.$vehicleExpiry.'  vehicle type   '.$vehicleType);
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
        if(isset($vehicleRefData['OWN'])==1)
            $ownership=$vehicleRefData['OWN'];
        else
            $ownership='OWN';  
        if(isset($vehicleRefData['isRfid'])==1)
            $isRfid=$vehicleRefData['isRfid'];
        else
            $isRfid='no';  
        if(isset($vehicleRefData['rfidType'])==1)
            $rfidType=$vehicleRefData['rfidType'];
        else
            $rfidType='no';  
        if(isset($vehicleRefData['ipAddress'])==1)
            $ipAddress=$vehicleRefData['ipAddress'];
        else
            $ipAddress='';  
        if(isset($vehicleRefData['portNo'])==1)
            $portNo=$vehicleRefData['portNo'];
        else
            $portNo=''; 
        if(isset($vehicleRefData['analog1'])==1)
            $analog1=$vehicleRefData['analog1'];
        else
            $analog1='no';  
        if(isset($vehicleRefData['analog2'])==1)
            $analog2=$vehicleRefData['analog2'];
        else
            $analog2='no';  
        if(isset($vehicleRefData['digital1'])==1)
            $digital1=$vehicleRefData['digital1'];
        else
            $digital1='no';  
        if(isset($vehicleRefData['digital2'])==1)
            $digital2=$vehicleRefData['digital2'];
        else
            $digital2='no';  
        if(isset($vehicleRefData['serial1'])==1)
            $serial1=$vehicleRefData['serial1'];
        else
            $serial1='no';  
        if(isset($vehicleRefData['serial2'])==1)
            $serial2=$vehicleRefData['serial2'];
        else
            $serial2='no';  
        if(isset($vehicleRefData['digitalout'])==1)
            $digitalout=$vehicleRefData['digitalout'];
        else
            $digitalout='no'; 
        if(isset($vehicleRefData['mintemp'])==1)
            $mintemp=$vehicleRefData['mintemp'];
        else
            $mintemp=-50; 
        if(isset($vehicleRefData['maxtemp'])==1)
            $maxtemp=$vehicleRefData['maxtemp'];
        else
            $maxtemp=50;  

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
        $refDataArr = array (
            'deviceId' => $deviceId,
            'shortName' => $shortName,
            'deviceModel' => $deviceModel,
            'regNo' => $regNo,
            'vehicleMake' => $vehicleMake,
            'vehicleType' => $vehicleType,
            'oprName' => $oprName,
            'mobileNo' => $mobileNo,
		 'vehicleExpiry' => $vehicleExpiry,
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
            'OWN'=>$ownership,
            'ipAddress'=>$ipAddress,
            'portNo'=>$portNo,
            'analog1'=>$analog1,
            'analog2'=>$analog2,
            'digital1'=>$digital1,
            'digital2'=>$digital2,
            'serial1'=>$serial1,
            'serial2'=>$serial2,
            'digitalout'=>$digitalout,
            'isRfid'=>$isRfid,
            'rfidType'=>$rfidType,
            'mintemp'=>$mintemp,
            'maxtemp'=>$maxtemp,
			'routeName'=>$routeName,
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
        'shortName' => 'required',
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
	 $vehicleExpiry = Input::get ( 'vehicleExpiry' );
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
        $rfidType=Input::get ('rfidType');
        $orgId = Input::get ('orgId');
        $altShortName= Input::get ('altShortName');
        $parkingAlert = Input::get('parkingAlert');

    $Licence=Input::get ( 'Licence1');    
    $Licence=!empty($Licence) ? $Licence : 'Advance';
    $descriptionStatus=Input::get ( 'descriptionStatus');    
    $descriptionStatus=!empty($descriptionStatus) ? $descriptionStatus : '';

    $mintemp=Input::get ( 'mintemp');    
    $mintemp=!empty($mintemp) ? $mintemp : '';
    $maxtemp=Input::get ( 'maxtemp');    
    $maxtemp=!empty($maxtemp) ? $maxtemp : '';
    $Payment_Mode=Input::get ( 'Payment_Mode1');  
    $Payment_Mode=!empty($Payment_Mode) ? $Payment_Mode : 'Monthly';

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
        if(Session::get('cur')=='dealer')
        {
           $ownership=$username;
        }
        else if(Session::get('cur')=='admin')
        {
            $ownership='OWN';
        }
        $refDataArr = array (
            'deviceId' => $deviceId,
            'shortName' => $shortName,
            'deviceModel' => $deviceModel,
            'regNo' => $regNo,
            'vehicleMake' => $vehicleMake,
            'vehicleType' => $vehicleType,
            'oprName' => $oprName,
            'mobileNo' => $mobileNo,
		  'vehicleExpiry' => $vehicleExpiry,
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
            'rfidType'=>$rfidType,
            'OWN'=>$ownership,
            'Licence'=>$Licence,
            'Payment_Mode'=>$Payment_Mode,
            'descriptionStatus'=>$descriptionStatus,
            'mintemp'=>$mintemp,
            'maxtemp'=>$maxtemp,

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
$tmpPositon =  '13.104870,80.303138,0,N,' . $time . ',0.0,N,N,ON,' .$odoDistance. ',S,N';
if(isset($franchiseDetails['fullAddress'])==1)
{
    $fullAddress=$franchiseDetails['fullAddress'];
    $data_arr = BusinessController::geocode($fullAddress);
    if($data_arr){        
        $latitude = $data_arr[0];
        $longitude = $data_arr[1];
        log::info( '------lat lang---------- '.$latitude.','.$longitude);
        $tmpPositon =  $latitude.','.$longitude.',0,N,' . $time . ',0.0,N,N,ON,' .$odoDistance. ',S,N';
    }
}
log::info( '------prodata---------- '.$tmpPositon);
$redis->hset ( 'H_ProData_' . $fcode, $vehicleId, $tmpPositon );
// redirect

Session::flash ( 'message', 'Successfully updated ' . $vehicleId . '!' );
return Redirect::to('Business');
}


}


public function renameUpdate() {
    log::info('-----------inside renameUpdate---------');
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
$current = Carbon::now();
        $rajeev=$current->format('Y-m-d');
  log::info($rajeev);
  $tomorrow = Carbon::now()->addDay();
  log::info($tomorrow);

    $vehicleId1 = Input::get ( 'vehicleId' );
    $vehicleId2=str_replace('.', '-', $vehicleId1);
    $vehicleId = strtoupper($vehicleId2);
    $deviceId = Input::get ( 'deviceId' );
    $vehicleId =preg_replace('/\s+/', '', $vehicleId);
    $deviceId =preg_replace('/\s+/', '', $deviceId);

    $vehicleIdOld= Input::get ( 'vehicleIdOld' );
    $deviceIdOld = Input::get ( 'deviceIdOld' );
     $expiredPeriodOld = Input::get ( 'expiredPeriodOld' );
    
    $username = Auth::user ()->username; log::info('ram...............'.$username);
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
///get orgId
  $detailsR = $redis->hget ( 'H_RefData_' . $fcode, $vehicleIdOld );
  $refDataFromDBR = json_decode ( $detailsR, true );
  $orgId =isset($refDataFromDBR['orgId'])?$refDataFromDBR['orgId']:'default';
  Log::info(' orgIdOK = ' . $orgId);
  $mobileNo1 =isset($refDataFromDBR['mobileNo'])?$refDataFromDBR['mobileNo']:'';
  $mobileNo=strtoupper($mobileNo1);
  $shortName1 =isset($refDataFromDBR['shortName'])?$refDataFromDBR['shortName']:'';
  $shortName =strtoupper($shortName1);
//
///ram vehicleIdCheck
    $vehicleIdcheck=$redis->sismember( 'S_Vehicles_' . $fcode, $vehicleId); log::info('reamaa.....'.$vehicleIdcheck);
     if($vehicleIdcheck==1)
     {
    Session::flash ( 'message', 'Asset Id/Vehicle Id already exists ' .'!' );
    log::info('reamaa.....1'.$vehicleIdcheck);
    $deviceId= $deviceIdOld;
    $vehicleId= $vehicleIdOld;
    return View::make ( 'vdm.vehicles.rename', array ('vehicleId' => $vehicleId ) )->with('deviceId',$deviceIdOld)->with('expiredPeriod',$expiredPeriodOld);
     } else 
     {
       log::info('krish.....0'.$vehicleIdcheck);
        $rget=$redis->hget ( 'H_RefData_' . $fcode, $vehicleIdOld );
        $redis->hdel ( 'H_RefData_' . $fcode, $vehicleIdOld );
        $redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $rget );
     }
    $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
    $franchiesJson  =   $redis->hget('H_Franchise_Mysql_DatabaseIP', $fcode);
//log::info($franchiesJson);

    $servername = $franchiesJson;
    //$servername = "128.199.159.130";
    //if (!$servername){
    if (strlen($servername) > 0 && strlen(trim($servername) == 0)){
        // $servername = "188.166.237.200";
        return 'Ipaddress Failed !!!';
    }
    //$servername1 = "128.199.159.130";
    $usernamedb = "root";
    $password = "#vamo123";
    $dbname = $fcode;
    log::info('franci..----'.$fcode);
    log::info('ip----'.$servername);
    $conn = mysqli_connect($servername, $usernamedb, $password, $dbname);
   
    if( !$conn ) {
        die('Could not connect: ' . mysqli_connect_error());
        return 'Please Update One more time Connection failed';
    } else { 

        log::info(' created connection ');
    
        
        $update = "UPDATE yearly_vehicle_history SET vehicleId = '$vehicleId' WHERE vehicleId ='$vehicleIdOld'"; 
         $conn->multi_query($update); 
         $update1 = "UPDATE vehicle_history SET vehicleId = '$vehicleId' WHERE vehicleId ='$vehicleIdOld'";
         $conn->multi_query($update1);
         $update2 = "UPDATE Executive_Details SET vehicleId = '$vehicleId' WHERE vehicleId ='$vehicleIdOld'";
         $conn->multi_query($update2);
         $update3 = "UPDATE sensor_history SET vehicleId = '$vehicleId' WHERE vehicleId ='$vehicleIdOld'";
         $conn->multi_query($update3);
         $update4 = "UPDATE rfid_history SET vehicleId = '$vehicleId' WHERE vehicleId ='$vehicleIdOld'";
         $conn->multi_query($update4);
         $update5 = "UPDATE TollgateDetails SET vehicleId = '$vehicleId' WHERE vehicleId ='$vehicleIdOld'";
         $conn->multi_query($update5);
         $update6 = "UPDATE Sms_Audit SET vehicleId = '$vehicleId' WHERE vehicleId ='$vehicleIdOld'";
         $conn->multi_query($update6);
         $update7 = "UPDATE ScheduledReport SET vehicleId = '$vehicleId' WHERE vehicleId ='$vehicleIdOld'";
         $conn->multi_query($update7);
          $update8 = "UPDATE Poi_History SET vehicleId = '$vehicleId' WHERE vehicleId ='$vehicleIdOld'";
         $conn->multi_query($update8);
         $update9 = "UPDATE PERFORMANCE SET vehicle_Id = '$vehicleId' WHERE vehicle_Id ='$vehicleIdOld'";
         $conn->multi_query($update9);
         $update10 = "UPDATE DailyPerformance SET vehicle_Id = '$vehicleId' WHERE vehicle_Id ='$vehicleIdOld'";
         $conn->multi_query($update10);
         $update11 = "UPDATE FuelReports SET vehicleId = '$vehicleId' WHERE vehicleId ='$vehicleIdOld'";
         $conn->multi_query($update11);

        log::info(' Sucessfully inserted/updated !!! ');
        
    $conn->close();
    //return 'correct';
    }
    //
      $raguldeviceId=$redis->hget ( $vehicleDeviceMapId, $vehicleIdOld );
        $redis->hdel ( $vehicleDeviceMapId, $vehicleIdOld );                               
        $redis->hdel ( $vehicleDeviceMapId, $deviceIdOld );                                
        $redis->hset ( $vehicleDeviceMapId, $vehicleId, $raguldeviceId );                     
        $redis->hset ( $vehicleDeviceMapId, $raguldeviceId ,$vehicleId);

    //
////new keys
      $UNvalue=$redis->hget('H_UserId_Notification_map_'. $fcode, $vehicleIdOld);
      $redis->hdel('H_UserId_Notification_map_'. $fcode, $vehicleIdOld);
      $redis->hset('H_UserId_Notification_map_'. $fcode, $vehicleId, $UNvalue);

      $DDvalue=$redis->hget('H_Delta_Distance_'. $fcode, $vehicleIdOld);
      $redis->hdel('H_Delta_Distance_'. $fcode, $vehicleIdOld);
      $redis->hset('H_Delta_Distance_'. $fcode, $vehicleId, $DDvalue);
 
     $LHistfor='L_HistforOutOfOrderData_'. $vehicleId .'_'. $fcode .'_'. $rajeev;
     $LHistforOld='L_HistforOutOfOrderData_'. $vehicleIdOld .'_'. $fcode .'_'. $rajeev;
     $LHset='L_HistforOutOfOrderData_*'. $fcode .'_'. $rajeev;
     $LHok=$redis->keys($LHset);
     foreach ($LHok as $LHf => $valueLHf) {
            if($valueLHf==$LHistforOld)
            {
           $redis->rename($LHistforOld, $LHistfor);
            }
        } 
    
     $LAhist='L_Alarm_Hist_'. $vehicleId .'_'. $fcode;
     $LAhistOld='L_Alarm_Hist_'. $vehicleIdOld .'_'. $fcode;
     $LAset='L_Alarm_Hist_*'. $fcode;
     $LAok=$redis->keys($LAset);
     foreach ($LAok as $LAf => $valueLAf) {
            if($valueLAf==$LAhistOld)
            {
           $redis->rename($LAhistOld, $LAhist);
            }
        } 

     $LRfitd='L_Rfid_Hist_'. $vehicleId .'_'. $fcode .'_'. $rajeev;
     $LRfitdOld='L_Rfid_Hist_'. $vehicleIdOld .'_'. $fcode .'_'. $rajeev;
     $LRfitdSet='L_Rfid_Hist_*'. $fcode .'_'. $rajeev;
     $LRfitdOk=$redis->keys($LRfitdSet);
     foreach ($LRfitdOk as $LRok => $valueLRok) {
           if($valueLRok==$LRfitdOld)
           {
            $redis->rename($LRfitdOld, $LRfitd);
           }
       }  

     $RouteDeviation='RouteDeviation:'. $vehicleId .':'. $fcode;
     $RouteDeviationOld='RouteDeviation:'. $vehicleIdOld .':'. $fcode;
     $RouteDeviationSet='RouteDeviation:*'. $fcode;
     $RouteDeviationOk=$redis->keys($RouteDeviationSet);
     foreach ($RouteDeviationOk as $RDok => $valueRDok) {
           if($valueRDok==$RouteDeviationOld)
           {
            $redis->rename($RouteDeviationOld, $RouteDeviation);
           }
       } 


////       
        $redis->srem ( 'S_Vehicles_' . $fcode, $vehicleIdOld );

        $redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );

        $redis->srem ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleIdOld);
        $redis->sadd ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleId);
      
    ///ram prodata 
       $vo=$redis->hget ( 'H_ProData_'. $fcode, $vehicleIdOld);
        $redis->hset ( 'H_ProData_'. $fcode, $vehicleId, $vo);
        $redis->hdel ( 'H_ProData_'. $fcode, $vehicleIdOld);
    ///
    ///ram L_Hist
        $LHist='L_Hist_'. $vehicleId .'_'. $fcode .'_'. $rajeev;
        $LHistOld='L_Hist_'. $vehicleIdOld .'_'. $fcode .'_'. $rajeev;
        log::info($LHist);  
        log::info($LHistOld); 
        $ram='L_Hist_*'. $fcode .'_'. $rajeev;
        $ok=$redis->keys($ram);
        foreach ($ok as $first => $value) {
            if($value==$LHistOld)
            {
           $redis->rename($LHistOld, $LHist);
            }
        }  
    ///
    ///ram L_Sensor_Hist*    
        $Lsensor='L_Sensor_Hist_'. $vehicleId .'_'. $fcode .'_'. $rajeev;
        $LsensorOld='L_Sensor_Hist_'. $vehicleIdOld .'_'. $fcode .'_'. $rajeev;
        $setkey='L_Sensor_Hist_*'. $fcode .'_'. $rajeev;
        $sensorV=$redis->keys($setkey);
        foreach ($sensorV as $raj => $valueS) {
            if($valueS==$LsensorOld)
            {
                $redis->rename($LsensorOld, $Lsensor);
            }
        }
    ///
    ///ram Z_sensor*
       $Zsensor='Z_Sensor_'. $vehicleId .'_'. $fcode;
       $ZsensorOld='Z_Sensor_'. $vehicleIdOld .'_'. $fcode;  
       $setZsensor='Z_Sensor_*'. $fcode;
       $ZsensorKey=$redis->keys($setZsensor);
       foreach ($ZsensorKey as $Zskey => $valueZs) {
           if($valueZs==$ZsensorOld)
           {
             $redis->rename($ZsensorOld, $Zsensor);
           }
       }
    ///
    ///ram nodata
        $nodata='NoData24:'. $fcode .':'. $vehicleId;
        $nodataOld='NoData24:'. $fcode .':'. $vehicleIdOld;   
        $setnodata='NoData24:'. $fcode .':*';
        $nodataKey=$redis->keys($setnodata);
        foreach ($nodataKey as $ndkey => $valuend) {
            if($valuend==$nodataOld)
            {
                $redis->rename($nodataOld, $nodata);
            }
        }
    ///
        $groupList = $redis->smembers('S_Groups_' . $fcode);

        foreach ( $groupList as $group ) {
            if($redis->sismember($group,$vehicleIdOld)==1)
            {
                $result = $redis->srem($group,$vehicleIdOld);
                $redis->sadd($group,$vehicleId);
            }

//            Log::info('going to delete vehicle from group ' . $group . $redisVehicleId . $result);
        }
      $deviceId =isset($refDataFromDBR['deviceId'])?$refDataFromDBR['deviceId']:'';
	  $shortNameOld =isset($refDataFromDBR['shortName'])?$refDataFromDBR['shortName']:'';
	  $orgId1=strtoupper($orgId);
	  $redis->hdel ('H_VehicleName_Mobile_Org_' .$fcode, $vehicleIdOld.':'.$deviceId.':'.$shortNameOld.':'.$orgId1.':'.$mobileNo);
	  $redis->hset ('H_VehicleName_Mobile_Org_' .$fcode, $vehicleId.':'.$deviceId.':'.$shortName.':'.$orgId1.':'.$mobileNo, $vehicleId);
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
		$redis->hdel ('H_VehicleName_Mobile_Dealer_'.$username.'_Org_' .$fcode, $vehicleIdOld.':'.$deviceId.':'.$shortNameOld.':'.$orgId1.':'.$mobileNo);
		$redis->hset ('H_VehicleName_Mobile_Dealer_'.$username.'_Org_' .$fcode, $vehicleId.':'.$deviceId.':'.$shortName.':'.$orgId1.':'.$mobileNo, $vehicleId);
            $groupList1 = $redis->smembers('S_Groups_Dealer_'.$username.'_' . $fcode);
        }
        else if(Session::get('cur')=='admin')
        {
            log::info('-----------inside admin-----------');
            $redis->srem('S_Vehicles_Admin_'.$fcode,$vehicleIdOld);
            $redis->sadd('S_Vehicles_Admin_'.$fcode,$vehicleId);
			$redis->hdel ('H_VehicleName_Mobile_Admin_OWN_Org_' .$fcode, $vehicleIdOld.':'.$deviceId.':'.$shortNameOld.':'.$orgId1.':'.$mobileNo.':OWN');
			$redis->hset ('H_VehicleName_Mobile_Admin_OWN_Org_' .$fcode, $vehicleId.':'.$deviceId.':'.$shortName.':'.$orgId1.':'.$mobileNo.':OWN', $vehicleId);
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


  //  }

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









public function migrationUpdate() {
    log::info('-----------inside migrationUpdate---------');
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $vehicleId1 = Input::get ( 'vehicleId' );
	$vehicleId2 = str_replace('.', '-', $vehicleId1);
    $vehicleId = strtoupper($vehicleId2);
	$deviceId = Input::get ( 'deviceId' );
    $vehicleId =preg_replace('/\s+/', '', $vehicleId);
    $deviceId =preg_replace('/\s+/', '', $deviceId);

    $vehicleIdOld= Input::get ( 'vehicleIdOld' );
    $deviceIdOld = Input::get ( 'deviceIdOld' );
     $expiredPeriodOld = Input::get ( 'expiredPeriodOld' );
    
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    $vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;


    $rules = array (
        'vehicleId' => 'required',
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
            $tempDeviceCheck=$redis->hget('H_Device_Cpy_Map',$deviceId);
        if( $tempDeviceCheck!==null)
        {
            Session::flash ( 'message', 'Device Id already present ' . '!' );
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
                Session::flash ( 'message', 'Asset Id/Vehicle Id already exists ' .'!' );
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
                Session::flash ( 'message', 'Asset Id/Vehicle Id already exists ' .'!' );
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
            $tempDeviceCheck=$redis->hget('H_Device_Cpy_Map',$deviceId);
        if( $tempDeviceCheck!==null)
        {
            Session::flash ( 'message', 'Device Id already present ' . '!' );
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
     //ram-new-key---
    $shortName1=$refDataJson1['shortName'];
    $shortName=strtoupper($shortName1);
    //log::info($refDataJson1);
    $mobileNoOld=isset($refDataJson1['mobileNo'])?$refDataJson1['mobileNo']:'';
    $mobileNo1=isset($refDataJson1['mobileNo'])?$refDataJson1['mobileNo']:'0123456789';
    $mobileNo=strtoupper($mobileNo1);
    //----
        $orgId=isset($refDataJson1['orgId'])?$refDataJson1['orgId']:'default';
    $orgId1=strtoupper($orgId); 
        $time =microtime(true);
        $time = round($time * 1000);
        $franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
        $franchiseDetails=json_decode($franDetails_json,true);
        $tmpPositon =  '13.104870,80.303138,0,N,' . $time . ',0.0,N,N,ON,0,S,N';
        if(isset($franchiseDetails['fullAddress'])==1)
        {
            $fullAddress=$franchiseDetails['fullAddress'];
            $data_arr = BusinessController::geocode($fullAddress);
            if($data_arr){        
                $latitude = $data_arr[0];
                $longitude = $data_arr[1];
                log::info( '------lat lang---------- '.$latitude.','.$longitude);
                $tmpPositon =  $latitude.','.$longitude.',0,N,' . $time . ',0.0,N,N,ON,0,S,N';
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
       if(Session::get('cur')=='dealer')
        {
           $ownership=$username;
        }
        else if(Session::get('cur')=='admin')
        {
            $ownership='OWN';
        }
        $refDataArr = array (
            'deviceId' => $deviceId,
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
            'orgId' =>isset($refDataJson1['orgId'])?$refDataJson1['orgId']:'default',
            'sendGeoFenceSMS' => isset($refDataJson1['sendGeoFenceSMS'])?$refDataJson1['sendGeoFenceSMS']:'no',
            'morningTripStartTime' => isset($refDataJson1['morningTripStartTime'])?$refDataJson1['morningTripStartTime']:' ',
            'eveningTripStartTime' => 'TIMEZONE',
		  //'eveningTripStartTime' => isset($refDataJson1['eveningTripStartTime'])?$refDataJson1['eveningTripStartTime']:' ',
            'parkingAlert' => isset($refDataJson1['parkingAlert'])?$refDataJson1['parkingAlert']:'no',
            'altShortName'=>isset($refDataJson1['altShortName'])?$refDataJson1['altShortName']:'nill',
            'date' =>isset($refDataJson1['date'])?$refDataJson1['date']:' ',
            'paymentType'=>isset($refDataJson1['paymentType'])?$refDataJson1['paymentType']:' ',
            'expiredPeriod'=>isset($refDataJson1['expiredPeriod'])?$refDataJson1['expiredPeriod']:' ',
            'fuel'=>isset($refDataJson1['fuel'])?$refDataJson1['fuel']:'no',
            'fuelType'=>isset($refDataJson1['fuelType'])?$refDataJson1['fuelType']:' ',
            'isRfid'=>isset($refDataJson1['isRfid'])?$refDataJson1['isRfid']:'no',
            'rfidType'=>isset($refDataJson1['rfidType'])?$refDataJson1['rfidType']:'no',
            'OWN'=>$ownership,
            'Licence'=>isset($refDataJson1['Licence'])?$refDataJson1['Licence']:'',
            'Payment_Mode'=>isset($refDataJson1['Payment_Mode'])?$refDataJson1['Payment_Mode']:'',
            'descriptionStatus'=>isset($refDataJson1['descriptionStatus'])?$refDataJson1['descriptionStatus']:'',
            'mintemp'=>isset($refDataJson1['mintemp'])?$refDataJson1['mintemp']:'',
            'maxtemp'=>isset($refDataJson1['maxtemp'])?$refDataJson1['maxtemp']:'',
            );

        $refDataJson = json_encode ( $refDataArr );
        

try{
$licence_id = DB::select('select licence_id from Licence where type = :type', ['type' => isset($refDataJson1['Licence'])?$refDataJson1['Licence']:'Advance']);
$payment_mode_id = DB::select('select payment_mode_id from Payment_Mode where type = :type', ['type' => isset($refDataJson1['Payment_Mode'])?$refDataJson1['Payment_Mode']:'Monthly']);
        log::info( $licence_id[0]->licence_id.'-------- av  in  ::----------'.$payment_mode_id[0]->payment_mode_id);
        
$licence_id=$licence_id[0]->licence_id;
$payment_mode_id=$payment_mode_id[0]->payment_mode_id;
        DB::table('Vehicle_details')
            ->where('vehicle_id', $vehicleIdOld)
            ->where('fcode', $fcode)
            ->update(array('vehicle_id' => $vehicleId,
                'device_id' => $deviceId,
                ));

}catch(\Exception $e)
        {
            Log::info($vehicleId.'--------------------inside Exception--------------------------------');
        }


        $redis->hdel ( 'H_RefData_' . $fcode, $vehicleIdOld );
        $redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );

        $redis->srem ( 'S_Vehicles_' . $fcode, $vehicleIdOld );

        $redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );

        $redis->srem ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleIdOld);
        $redis->sadd ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleId);

//ram-new-key---
$redis->hdel ('H_VehicleName_Mobile_Org_' .$fcode, $vehicleIdOld.':'.$deviceIdOld.':'.$shortName.':'.$orgId1.':'.$mobileNoOld);
$redis->hset ('H_VehicleName_Mobile_Org_' .$fcode, $vehicleId.':'.$deviceId.':'.$shortName.':'.$orgId1.':'.$mobileNo, $vehicleId);
///----



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
		$redis->hdel ('H_VehicleName_Mobile_Dealer_'.$username.'_Org_'.$fcode, $vehicleIdOld.':'.$deviceIdOld.':'.$shortName.':'.$orgId1.':'.$mobileNoOld);
        $redis->hset ('H_VehicleName_Mobile_Dealer_'.$username.'_Org_'.$fcode, $vehicleId.':'.$deviceId.':'.$shortName.':'.$orgId1.':'.$mobileNoOld, $vehicleId);
            $groupList1 = $redis->smembers('S_Groups_Dealer_'.$username.'_' . $fcode);
        }
        else if(Session::get('cur')=='admin')
        {
            log::info('-----------inside admin-----------');
            $redis->srem('S_Vehicles_Admin_'.$fcode,$vehicleIdOld);
            $redis->sadd('S_Vehicles_Admin_'.$fcode,$vehicleId);
		$redis->hdel ('H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode, $vehicleIdOld.':'.$deviceIdOld.':'.$shortName.':'.$orgId1.':'.$mobileNo.':OWN');
		$redis->hset ('H_VehicleName_Mobile_Admin_OWN_Org_'.$fcode, $vehicleId.':'.$deviceId.':'.$shortName.':'.$orgId1.':'.$mobileNo.':OWN', $vehicleId);
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



public function moveDealer() {
    Log::info('------- inside dealerSearch--------');
    if (! Auth::check ()) {
        return Redirect::to ( 'login' );
    }
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
    Log::info(' inside multi ' );
    $vehicleList= Input::get ( 'vehicleList' );
    $dealerId= Input::get ( 'dealerId' );
 Log::info('------- inside dealerSearch--------'.count($vehicleList));
 if($vehicleList!==null)
 {
     foreach ($vehicleList as $key => $value) {
       Log::info('------- inside vehicle--------'.$value);
        $redis->sadd('S_Vehicles_Dealer_'.$dealerId.'_'.$fcode,$value);
        $redis->srem('S_Vehicles_Admin_'.$fcode,$value);
    }
 }
   

    // $redis->sadd('S_Vehicles_Dealer_'.$ownerShip.'_'.$fcode,$vehicleId);
    // $redis->srem('S_Vehicles_Admin_'.$fcode,$vehicleId);


return Redirect::to ( 'vdmVehicles' );

   // return View::make ( 'vdm.vehicles.dealerSearch' )->with('dealerId',$dealerId);       

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



/*

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
*/

//show stops
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
    // log::info('thiru '.$value[0]);
    try
    {
        foreach($value as $org => $geoAddress) {                                   
            $rowId1 = json_decode($geoAddress,true);
            $t =0;
            /*foreach($rowId1 as $org1 => $rowId2) {
                log::info(' thiiii '.$rowId2)
                if ($t==1)
                {
                    if(isset( $rowId1['time'] ) )
                    {
                        if($rowId1['time'] != null &&  $rowId1['time'] != '')
                        {
                            $address = array_add($address, $org,$rowId2.' '.$rowId1['time']);
                            log::info( $org.' 3 :' . $t .$rowId2.$rowId1['time']);
                        }
                    }else
                    {
                        $address = array_add($address, $org,$rowId2);
                        log::info( $org.' 3 :' . $t .$rowId2);   
                    }     

                }

                $t++;
            }*/
            if(isset( $rowId1['time'] ) )
            {
                if($rowId1['time'] != null &&  $rowId1['time'] != '')
                {
                    $address = array_add($address,$org,$rowId1['geoAddress'].' '.$rowId1['time']);
                    
                }
            }else
            {
                $address = array_add($address,$org, $rowId1['geoAddress']);
                log::info(' org '. $org);
            }  

            log::info( ' final :'.$t);    
        }     
    }catch(\Exception $e)
    {
        log::info($e);
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

public function rename($id)
{

    Log::info('.........rename........');
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
    log::info( '------rename 1---------- '.Session::get('cur'));
    if(Session::get('cur')=='dealer')
    {
        log::info( '------rename 2---------- '.Session::get('cur'));
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
    return View::make ( 'vdm.vehicles.rename', array (
        'vehicleId' => $vehicleId ) )->with ( 'deviceId', $deviceId )->with('expiredPeriod',$expiredPeriod);


}

/*
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

}*/

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

    $dbIpaddress = $redis->hget('H_Franchise_Mysql_DatabaseIP',$fcode);
   // $servername = $dbIpaddress;

    if (strlen($dbIpaddress) > 0 && strlen(trim($dbIpaddress) == 0)){
        return 'Ipaddress Failed !!!';
    }

    $usernamedb = "root";
    $password = "#vamo123";
    $dbname = $fcode;
    $servername= $ipaddress;
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
            $conn = mysqli_connect($servername, $usernamedb, $password, $dbname);

            if( !$conn )
            {
                log::info(' connection not created');
                die('Could not connect: ' . mysqli_connect_error());
            }else {
                log::info('connection created');
                $query = "SELECT * FROM StudentSmsDetails where route='".$routeNo."'";
                log::info($query);
                $results = mysqli_query($conn,$query);
                while ($row = mysqli_fetch_array($results)) {
                     log::info('clearr');
                     log::info($row['mobileNumber']);
                     $mobileNumber = $row['mobileNumber'];
                     $redis->hdel('H_Mblno_'.$orgId.'_'.$fcode, $mobileNumber);
                    }
                $DeleteQuery = "DELETE FROM StudentSmsDetails where route='".$routeNo."'"; 
                $conn->query($DeleteQuery);  
            }
         $conn->close();
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
            $conn = mysqli_connect($servername, $usernamedb, $password, $dbname);

            if( !$conn )
            {
                log::info(' connection not created');
                die('Could not connect: ' . mysqli_connect_error());
            }else {
                log::info('connection created');
                $query = "SELECT * FROM StudentSmsDetails where route='".$routeNo."'";
                log::info($query);
                $results = mysqli_query($conn,$query);
                while ($row = mysqli_fetch_array($results)) {
                     log::info($row['mobileNumber']);
                     $mobileNumber = $row['mobileNumber'];
                     $redis->hdel('H_Mblno_'.$orgId.'_'.$fcode, $mobileNumber);
                    }
                $DeleteQuery = "DELETE FROM StudentSmsDetails where route='".$routeNo."'"; 
                $conn->query($DeleteQuery);     
            }
            $conn->close();
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


