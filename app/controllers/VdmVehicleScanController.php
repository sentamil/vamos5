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
		$text_trim= str_replace(' ', '', $text_word1);
		$text_word = strtoupper($text_trim);
        $vehicleList = $redis->smembers ( $vehicleListId); //log::info($vehicleList);
      //$cou = $redis->SCARD($vehicleListId); //log::info($cou);
        $cou = $redis->hlen($vehicleNameMob);
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
	$onboardDateList = null;
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
		$expiredPeriod=isset($vehicleRefData['vehicleExpiry'])?$vehicleRefData['vehicleExpiry']:'-';
        $nullval=strlen($expiredPeriod);
        log::info($nullval);
        if($nullval==0 || $expiredPeriod=="null")
        {
            $expiredPeriod='-';
        }

        $expiredList = array_add($expiredList,$vehicle,$expiredPeriod);
        $statusVehicle = $redis->hget ( 'H_ProData_' . $fcode, $vehicle );
        $statusSeperate = explode(',', $statusVehicle);
        $statusList = array_add($statusList, $vehicle, $statusSeperate[7]);
		$onboardDate=isset($vehicleRefData['onboardDate'])?$vehicleRefData['onboardDate']:'-';
        $onboardDateList = array_add($onboardDateList,$vehicle,$onboardDate);

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
        ) )->with ( 'deviceList', $deviceList )->with('shortNameList',$shortNameList)->with('portNoList',$portNoList)->with('mobileNoList',$mobileNoList)->with('demo',$demo)->with ( 'user', $user )->with ( 'orgIdList', $orgIdList )->with ( 'deviceModelList', $deviceModelList )->with ( 'expiredList', $expiredList )->with ( 'tmp', 0 )->with ('statusList', $statusList)->with('dealerId',$dealerId)->with('onboardDateList', $onboardDateList); 
}
public function sendExcel()
{
    $username = Auth::user ()->username;
    $redis = Redis::connection ();
    $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );                    
    $devicesList=$redis->smembers( 'S_Device_' . $fcode);
    $file = Excel::create('Vehicles List', function($excel) 
    {
        $excel->sheet('Sheetname', function($sheet)  
        {   
            $redis = Redis::connection ();
            $username = Auth::user ()->username;
            log::info('inside the vehiclescan sendExcel---');
            $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
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
                               
            $vehicleList=$redis->smembers($vehicleListId);
            $temp=0;
            //$vehicleMap=array();
            $statusList = null;
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
            for($i =0;$i<count($vehicleList);$i++)
            {
                $refData    = $redis->hget ( 'H_RefData_' . $fcode, $vehicleList[$i] );
                $refData    = json_decode($refData,true);
                $deviceId =isset($refData['deviceId'])?$refData['deviceId']:''; 
		        $shortName = isset($refData['shortName'])?$refData['shortName']:'nill';
                $orgId=isset($refData['orgId'])?$refData['orgId']:'Default';
                $gpsNo=isset($refData['gpsSimNo'])?$refData['gpsSimNo']:99999;
                $statusVehicle = $redis->hget ( 'H_ProData_' . $fcode, $vehicleList[$i] );
                $statusSeperate = explode(',', $statusVehicle);
                $statusList1 = isset($statusSeperate[7])?$statusSeperate[7]:'';
                if($statusList1=='P')
                {
                    $statusList='Parking';
                }
                else if($statusList1=='M')
                {
                   	$statusList='Moving';
                }
                else if($statusList1=='S')
                {
                   	$statusList='Idle';
                }
                else if($statusList1=='N')
                {
                  	$statusList='New Device';
                }
                else 
                {
                   	$statusList='No data';
                }
                $deviceModel=isset($refData['deviceModel'])?$refData['deviceModel']:'nill';
                $vehLi=count($vehicleList);           
                $j=$i+2;
                $sheet->setWidth(array(
                                     'A'     =>  25,
                                     'C'    =>   30,
                                     'D'    =>   35,
                                     'E'    =>   15,
                                     'F'    =>   15,
                                     'G'    =>   15,
                                     'B'     =>  30
                             ));          

                $sheet->row(1,function ($row) 
				{
             	 $row->setFontWeight('bold');
                 $row->setAlignment('center');

                });
                  
                $sheet->row(2,function ($row) {
                $row->setAlignment('left');
                });
                    //$sheet->setAutoSize(true) ;
                $sheet->row(1, array('Asset Id','Vehicle Name','Organization Name','Device Id','GPS Sim No','Status','Device Model'));
                $sheet->row($j, array($vehicleList[$i],$shortName,$orgId,$deviceId,$gpsNo,$statusList,$deviceModel));
                }
                }); 

            });//->download('xls');
              $emailFcode=$redis->hget('H_Franchise', $fcode);
              $emailFile=json_decode($emailFcode,true);
              $email1=$emailFile['email2'];
              $email2=$emailFile['email1'];
              $data[]='get all de';
              log::info('--------------email outsite------------------>');
              $mymail=Mail::send( 'vdm.business.empty',$data,function($message) use($file,$email1)
              {
                $message->to($email1);
                //$message->to('ramakrishnan.vamosys@gmail.com');
                $message->subject('Welcome to Vamosys');
                $message->attach($file->store("xls",false,true)['full']);
                log::info('-----------email send------------------>');
              });
    return Redirect::back();
}
/*
* Show the form for creating a new resource.
* @return Response
*/	
}