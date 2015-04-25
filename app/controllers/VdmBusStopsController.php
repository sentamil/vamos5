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
        $schoolId=$in[0];
        $routeNo=$in[1];
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');

        //K_Morning_StopSeq_CVSM_R1

        $stopList = $redis->get('K_Morning_StopSeq_'.$schoolId .'_'.$routeNo.'_' . $fcode);
        
        
        return View::make('vdm.busStops.index', array('stopList'=> $stopList))->with('routeNo',$routeNo)
        ->with('schoolId',$schoolId);
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