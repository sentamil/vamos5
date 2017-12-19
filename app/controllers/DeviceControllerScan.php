<?php
use Maatwebsite\Excel\Facades\Excel;
class DeviceControllerScan extends \BaseController {

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function index()
      { 
       $orgLis = [];
       $devicesList=[];
       $text='Search Devices';
       return View::make('vdm.business.scan')->with('deviceMap', $orgLis)->with('devicesList',$devicesList)->with('text',$text);
       }
        public function store() {
                if (! Auth::check ()) {
                        return Redirect::to ( 'login' );
                }

                $username = Auth::user ()->username;
                $redis = Redis::connection ();
                $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

                $devicesList=$redis->smembers( 'S_Device_' . $fcode); 
                $orgArr = array();
               foreach($devicesList as $org) {
                 $orgArr = array_add($orgArr, $org,$org);
               }
               $devicesList1 = $orgArr;//log::info($devicesList);
                log::info( '------device list size---------- '.count($devicesList));
                 $ram = Input::get('value'); log::info($ram);
                 $text_word = Input::get('text_word'); log::info($text_word);
                 $cou = $redis->SCARD('S_Device_' . $fcode); log::info($cou);
                 $orgLi = $redis->sScan( 'S_Device_' . $fcode, 0,  'count', $cou, 'match', '*'.$text_word.'*'); log::info($orgLi);
                 $orgL = $orgLi[1];

                $temp=0;
                $deviceMap=array();
                for($i =0;$i<count($orgL);$i++){
                        $vechicle=$redis->hget ( 'H_Vehicle_Device_Map_' . $fcode, $orgL[$i] );
                        log::info($vechicle);

                        if($vechicle!==null)
                        {
                                $refData        = $redis->hget ( 'H_RefData_' . $fcode, $vechicle );  //        log::info($refData);
                                $refData        = json_decode($refData,true); log::info($refData);
                                $orgId          = isset($refData['OWN'])?$refData['OWN']:' '; //log::info($orgL[$i]);
                                $onboardDate=isset($refData['onboardDate'])?$refData['onboardDate']:'null';
                                $vehicleExpiry=isset($refData['vehicleExpiry'])?$refData['vehicleExpiry']:'null';
                                // log::info(isset($refData['OWN']));
                                 
                                $deviceMap= array_add($deviceMap,$i,$vechicle.','.$orgL[$i].','.$orgId.','.$onboardDate.','.$vehicleExpiry);
                        }

                        $temp++;
                }
                log::info( '------device map---------- '.count($deviceMap));
                //log::info($orgL);
               log::info($text_word);
                return View::make ( 'vdm.business.scan', array (
                                'deviceMap' => $deviceMap ) )->with ( 'devicesList', $devicesList1 )->with ('text', $text_word);

        }


    public function userNoti() {
        log::info('inside------------------------->');
        $username= Auth::user ()->username;
        $redis= Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username. ':fcode' );
        log::info($fcode);
        return Redirect::to ( 'Business' );

    }
    public function sendExcel()
        {
          log::info('insite excel');
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );                    
        $devicesList=$redis->smembers( 'S_Device_' . $fcode);
        $file = Excel::create('Device List', function($excel) 
            {
              $excel->sheet('Sheetname', function($sheet)  
               {
                $redis = Redis::connection ();
                $username = Auth::user ()->username;
                $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );                   
                $devicesList=$redis->smembers( 'S_Device_' . $fcode);
                $temp=0;
                $deviceMap=array();
                  for($i =0;$i<count($devicesList);$i++)
                  {
                   $vechicle=$redis->hget ( 'H_Vehicle_Device_Map_' . $fcode, $devicesList[$i] );
                    if($vechicle!==null)
                    {
                     $refData    = $redis->hget ( 'H_RefData_' . $fcode, $vechicle );
                     $refData    = json_decode($refData,true);
                     $orgId      = isset($refData['OWN'])?$refData['OWN']:' ';
                     $onboardDate=isset($refData['onboardDate'])?$refData['onboardDate']:'null';
                     $vehicleExpiry=isset($refData['vehicleExpiry'])?$refData['vehicleExpiry']:'null'; 
                    }
            
                    $temp++;
                    $condevmap=count($deviceMap);
                    $devLi=count($devicesList);           
                    
                    $j=$i+2;
                    $sheet->row(1, array('Device ID','Vechicle ID','OrgId','OnboardDate','VehicleExpiry'));
                    $sheet->row($j, array($devicesList[$i],$vechicle,$orgId,$onboardDate,$vehicleExpiry));   

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

        return Redirect::to('DeviceScan');
        } 

    }
