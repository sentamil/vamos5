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
        $org=$id;
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');

        $routeList = $redis->smembers('S_Organisation_Route_'.$id .'_'. $fcode);
        return View::make('vdm.busRoutes.index', array('routeList'=> $routeList));
    }
     
    
    /**
     * 
     * This show is invoked from vdm org controller
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
        $orgId=$id;
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');

        $routeList = $redis->smembers('S_Organisation_Route_'.$orgId .'_'. $fcode);
        return View::make('vdm.busRoutes.index', array('routeList'=> $routeList))->with('orgId',$orgId);
    }
    
    public function create() {
        
        Log::info(" VdmBusRoutesController @ create");
        
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
         $orgListId = 'S_Organisations_' . $fcode;
        
        Log::info('orgListId=' . $orgListId);

        $orgArr = $redis->smembers ( $orgListId);
        
        $orgList= array();
        foreach ($orgArr as $key => $value) {
            $orgList=array_add($orgList, $value,$value);
        }
        
        return View::make ( 'vdm.busRoutes.create' )->with('orgList',$orgList);
    }
    
    public function store()
    {
        
          Log::info(" VdmBusRoutesController @ store");
        
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $rules = array(
                'orgId'       => 'required',
                'routeId' => 'required',
                'stops'=>'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('vdmBusRoutes/create')
            ->withErrors($validator);
            
        } else {
            // store
            
            $orgId       = Input::get('orgId');
            $routeId      = Input::get('routeId');
            $stops      = Input::get('stops');
       
            
            $redis = Redis::connection();
            $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
            
            //store the data H_Bus_Stops_CVSM_ALH
             //17A:stop1
             
            $allStopsArr = explode("\r\n",$stops);
            
          
          //  dd($allStopsArr);
            
            $stopsData=array();
            
            $morningSeqList = Input::get('morningSeq');
            $eveningSeqList = Input::get('eveningSeq');
            
             $redis->set('K_Morning_StopSeq_'.$orgId. '_' .$routeId .'_' . $fcode,$morningSeqList);
             $redis->set('K_Evening_StopSeq_'.$orgId. '_' .$routeId.'_' . $fcode,$eveningSeqList);
             

           foreach ( $allStopsArr as $stop) {
              $stopsDetailsArr = explode(':',$stop);
              $key = $routeId  .':'. $stopsDetailsArr[0];
              $redis->hdel('H_Bus_Stops_' . $orgId . '_' .$fcode,$key);
           }
            
            //stop:geo:mobile:stopname
            foreach ( $allStopsArr as $stop) {
            
                Log::info(' stop--- ' . $stop);
                $stopsDetailsArr = explode(':',$stop);
                $key = $routeId  .':'. $stopsDetailsArr[0];
                $origMobileNos='';
                Log::info(' $key '. $key);
                $stopDetails = $redis->hget('H_Bus_Stops_' . $orgId . '_' .$fcode,$key);
               
                
                if(isset($stopDetails)) {
                    $stopDetailsNewArr = json_decode($stopDetails,true);
                   
                    $origMobileNos = $stopDetailsNewArr['mobileNo'];    
                    Log::info( ' $origMobileNos ' . $origMobileNos);
                }
               
                $stopsData=array();
                if(isset($stopsDetailsArr[1])) { //geoLocation
                    $stopsData = array_add($stopsData,'geoLocation',trim($stopsDetailsArr[1]));
                    
                    Log::info('$stopsDetailsArr[1] ' . $stopsDetailsArr[1]);
                }else {
                    continue;
                }
                if(isset($stopsDetailsArr[2])) { //mobile
                    $cumMobileNos = $stopsDetailsArr[2] . ','.$origMobileNos;
                    $stopsData = array_add($stopsData,'mobileNo',$cumMobileNos);
                    
                    Log::info( ' $cumMobileNos ' . $cumMobileNos);
                }
                if(isset($stopsDetailsArr[3])) {
                    $stopsData = array_add($stopsData,'stopName',$stopsDetailsArr[3]);
                }
                $stopsDataJson = json_encode ( $stopsData );
                Log::info ('$stopsDataJson ' . $stopsDataJson);
                $redis->sadd('S_Organisation_Route_'.$orgId .'_'. $fcode,$routeId);
               // $redis->rpush($stopList,$stopsDetailsArr[0]);
                
                //key routeIs: stop1 -- R1:stop1
           
                
                $redis->hset('H_Bus_Stops_' . $orgId . '_' .$fcode,$key,$stopsDataJson);
                Log::info('json data ' . $stopsDataJson);
            }
            
            Session::flash('message', 'Successfully created route with stops' . $routeId . '!');
            return Redirect::to('vdmBusRoutes/' . $orgId);
        }
        
    }
    
}