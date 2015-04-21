<?php

class VdmBusRoutesController extends \BaseController {
	
	
	
	public function index($id)
    {
        
 
        Log::info(" VdmBusRoutesController @ index" .$id);
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user()->username;
        $redis = Redis::connection();
        $school=$id;
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');

        $routeList = $redis->smembers('S_School_Route_'.$id .'_'. $fcode);
        return View::make('vdm.busRoutes.index', array('routeList'=> $routeList));
    }
	
    
    /**
     * 
     * This show is invoked from vdm school controller
     * 
     */
	public function show($id)
	{
		
		Log::info(" VdmBusRoutesController @ show " .$id);
		 if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user()->username;
        $redis = Redis::connection();
        $schoolId=$id;
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');

        $routeList = $redis->smembers('S_School_Route_'.$schoolId .'_'. $fcode);
        return View::make('vdm.busRoutes.index', array('routeList'=> $routeList))->with('schoolId',$schoolId);
	}
    
    public function create() {
        
        Log::info(" VdmBusRoutesController @ create");
        
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
         $schoolListId = 'S_Schools_' . $fcode;
        
        Log::info('schoolListId=' . $schoolListId);

        $schools = $redis->smembers ( $schoolListId);
        
        $schoolList= array();
        foreach ($schools as $key => $value) {
            $schoolList=array_add($schoolList, $value,$value);
        }
        
        return View::make ( 'vdm.busRoutes.create' )->with('schoolList',$schoolList);
    }
    
     public function store()
    {
        
          Log::info(" VdmBusRoutesController @ store");
        
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $rules = array(
                'schoolId'       => 'required',
                'routeId' => 'required',
                'stops'=>'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('vdmBusRoutes/create')
            ->withErrors($validator);
            
        } else {
            // store
            
            $schoolId       = Input::get('schoolId');
            $routeId      = Input::get('routeId');
            $stops      = Input::get('stops');
            
            $redis = Redis::connection();
            $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
            
            //store the data H_Bus_Stops_CVSM_ALH
             //17A:stop1
             
            $allStopsArr = explode("\r\n",$stops);
            
          
          //  dd($allStopsArr);
            
            $stopsData=array();
            //'L_Stops_'.$schoolId .'_'.$routeNo .'_' . $fcode
            
            $stopList = 'L_Stops_'.$schoolId .'_'.$routeId .'_' . $fcode;
            
            Log::info(' schoolId ...' . $stopList);
            
           
            
            foreach ( $allStopsArr as $stop) {
            
                Log::info(' stop--- ' . $stop);
                $stopsDetailsArr = explode(':',$stop);
                $key = $routeId . ':' . $stopsDetailsArr[0];
                $stopsData=array();
                if(isset($stopsDetailsArr[1])) {
                    $stopsData = array_add($stopsData,'geoLocation',trim($stopsDetailsArr[1]));
                    
                    Log::info('$stopsDetailsArr[1] ' . $stopsDetailsArr[1]);
                }else {
                    continue;
                }
                if(isset($stopsDetailsArr[2])) {
                    $stopsData = array_add($stopsData,'mobileNo',$stopsDetailsArr[2]);
                }
                $stopsDataJson = json_encode ( $stopsData );
                Log::info ('$stopsDataJson ' . $stopsDataJson);
                $redis->sadd('S_School_Route_'.$schoolId .'_'. $fcode,$routeId);
                $redis->rpush($stopList,$stopsDetailsArr[0]);
                $redis->hset('H_Bus_Stops_' . $schoolId . '_' .$fcode,$key,$stopsDataJson);
            }
            
            Session::flash('message', 'Successfully created route with stops' . $routeId . '!');
            return Redirect::to('vdmBusRoutes/' . $schoolId);
        }
        
    }
    
}