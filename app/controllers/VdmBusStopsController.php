<?php

class VdmBusStopsController extends \BaseController {
	
	
	
	
    /**
     * 
     * This show is invoked from vdm school controller
     * 
     */
	public function show($id)
	{
		
		Log::info(" VdmBusStopsController @ show++ " .$id);
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

        $stopList = $redis->lrange('L_Stops_'.$schoolId .'_'.$routeNo .'_' . $fcode,0,-1);
        
        
        return View::make('vdm.busStops.index', array('stopList'=> $stopList))->with('routeNo',$routeNo)->with('schoolId',$schoolId);
	}
    public function edit($id)
    {
        
        Log::info(" VdmBusStopsController @ edit " .$id);
         if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user()->username;
        $redis = Redis::connection();
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
        
        
      
        
         $in = explode(":",$id);
          $schoolId=$in[0];
        $routeNo=$in[1];
        $stopNo =$in[2];
      
        $key = $routeNo . ':' . $stopNo;
        $stopDetails = $redis->hget('H_Bus_Stops_' . $schoolId . '_' .$fcode,$key);
        $stopDetails=json_decode($stopDetails);
        return View::make('vdm.busStops.edit', array('stopDetails'=> $stopDetails));
                
        
    }
}