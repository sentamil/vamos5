<?php

class VdmBusStopsController extends \BaseController {
	
	
	
	
    /**
     * 
     * This show is invoked from vdm school controller
     * 
     */
	public function show($id)
	{
		
		Log::info(" VdmBusStopsController @ show " .$id);
        $in = explode(":",$id);
        Log::info($in[0] . ":::" . $in[1]);
		 if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user()->username;
        $redis = Redis::connection();
        $orgId=$in[0];
        $routeNo=$in[1];
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');

         //K_Morning_StopSeq_PONV_R5_SMO
         //K_Evening_StopSeq_CVSM_R3_ALH
         
         $morningList = $redis->get('K_Morning_StopSeq_' . $orgId .'_' .  $routeNo . '_' . $fcode);
         
         $eveningList = $redis->get('K_Evening_StopSeq_' . $orgId .'_' .  $routeNo . '_' . $fcode);
        
          $stopList = explode(',',$morningList);
          
          $mobileNosList = array();
          $stopNameList = array();
          
          foreach($stopList as $stop) {
              $stopData = $redis->hget('H_Bus_Stops_' . $orgId . '_' . $fcode, $routeNo . ':stop' . $stop);
       
              //H_Bus_Stops_PONV1_SMO -- R1:1
              $stopJson = json_decode($stopData,true);
               Log::info('stopNo' . $stop);
              $mobileNosList = array_add($mobileNosList, 'stopName:'.$stop,  $stopJson['mobileNo']);
              $stopNameList = array_add($stopNameList, 'stopName:'.$stop,  $stopJson['stopName']);
              
          }
        
         
         //PONV1:R3
         //L_Stops_orgId_rouetNo_fcode
         
                
        return View::make('vdm.busStops.index', array('stopNameList'=> $stopNameList))->with('routeNo',$routeNo)
        ->with('orgId',$orgId)->with('morningList',$morningList)->with('eveningList',$eveningList)->with('mobileNosList',$mobileNosList);
	}
    
 
    
    public function edit($id)
    {
        
        Log::info(" VdmBusStopsController @ edit " .$id);
         if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user()->username;
        $redis = Redis::connection();
         $in = explode(":",$id);
         $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
          $schoolId=$in[0];
        $routeNo=$in[1];
         $routeType=$in[2];
        $stopNo =$in[3];
        //   $key = $routeId . ':' . $routeType .':'. $stopsDetailsArr[0];
        $stopDetails = $redis->hget('H_Bus_Stops_' . $schoolId . '_' . $fcode,$routeNo .':' . $routeType.':' .$stopNo);
        var_dump($stopDetails);
    }
}