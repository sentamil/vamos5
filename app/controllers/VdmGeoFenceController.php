<?php

class VdmGeoFenceController extends \BaseController {
	
	
	/**
	 * 
	 * Geo Fencing controlled in the following manner
	 * 
	 * Set - S_{vehicleId}_GF
	 * HashMap - H_Vehicle_
	 * 
	 * @param unknown $id
	 */
	
	
	public function index($id)
    {
        
        Log::info(" inside VDM index++ " .$id);
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user()->username;
        $redis = Redis::connection();
        $vehicle=$id;
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');

        $gfSet = $redis->lrange('L_'.$vehicle.'_'. $fcode .'_GF',0,-1);

        $poiList=null;
        
        foreach ( $gfSet as $gfId ) {
            
            //this will give the json, which will contain poiName,mobileNumbers,direction(onward or return), 
            //just using dummy poiName
            $gfJson = $redis->hget('H_Vehicle_' . $fcode .'_GF',$vehicle.':'.$gfId);
            $in = json_decode($gfJson,true);
            $poiList = array_add ( $poiList,$vehicle.':'.$gfId,$in['poiName'] );
            
        
        }
       
        return View::make('vdm.geoFence.index', array('poiList'=> $poiList));
    }
	
    
    /**
     * 
     * This show is invoked from vdmVehicles
     * 
     */
	public function show($id)
	{
		
		Log::info(" inside VDM index " .$id);
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		$vehicle=$id;
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		
		
		$gfSet = $redis->lrange('L_'.$vehicle.'_'. $fcode .'_GF',0,-1);

		$poiList=null;
		
		foreach ( $gfSet as $gfId ) {
			
		   $gfJson = $redis->hget('H_Vehicle_' . $fcode .'_GF',$vehicle.':'.$gfId);
            $in = json_decode($gfJson,true);
			$poiList = array_add ( $poiList,$vehicle.':'.$gfId,$in['poiName'] );
		
		}
		
		return View::make('vdm.geoFence.index', array('poiList'=> $poiList));
	}
    
    public function view($id)
    {
        
        Log::info(" inside view  " .$id);
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user()->username;
        $redis = Redis::connection();
        $vehicle=$id;
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
        
         $gfJson = $redis->hget('H_Vehicle_' . $fcode .'_GF',$id);
        
         $in = json_decode($gfJson,true);
      
        return View::make('vdm.geoFence.view', array('mobileNos'=> $in['mobileNos']))->with('poiName',$in['poiName']);
    }
    
    public function edit($id)
    {
        
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $redis = Redis::connection();
        $geoFenceId=$id;
        
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
     
        $gfJson = $redis->hget('H_Vehicle_' . $fcode .'_GF',$id);
        
        $in = json_decode($gfJson,true);

        $poiName=isset($in['poiName'])?$in['poiName']:null;
        $mobileNos=isset($in['mobileNos'])?$in['mobileNos']:null;
        $geoLocation=isset($in['geoLocation'])?$in['geoLocation']:null;
        $geoAddress=isset($in['geoAddress'])?$in['geoAddress']:null;
        $direction=isset($in['direction'])?$in['direction']:null;
        $proximityLevel=isset($in['proximityLevel'])?$in['proximityLevel']:null;
        $geoFenceType=isset($in['geoFenceType'])?$in['geoFenceType']:null;
        $message= isset($in['message'])? $in['message'] : null;
         $email = isset($in['email'])? $in['email'] : null;
        
       
        return View::make('vdm.geoFence.edit',array('geoFenceId'=>$geoFenceId))->with('poiName',$poiName)
        ->with('mobileNos',$mobileNos)->with('geoLocation',$geoLocation)->with('geoAddress',
        $geoAddress)->with('direction',$direction)->with('proximityLevel',$proximityLevel)->with('geoFenceType',
        $geoFenceType)->with('message',$message)->with('email',$email);
  
  
    }
    
   public function update($id)
    {
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        //TODO Add validation
        
            $username = Auth::user()->username;
            $poiName       = Input::get('poiName');
            $mobileNos       = Input::get('mobileNos');
            $geoLocation       = Input::get('geoLocation');
            $geoAddress       = Input::get('geoAddress');
            $direction       = Input::get('direction');
            $geoFenceType       = Input::get('geoFenceType');
            $proximityLevel       = Input::get('proximityLevel'); 
             $message           = Input::get('message'); 
             $email           = Input::get('email'); 
            $redis = Redis::connection();
            $poiList=null;
               $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
            $poiList = array_add ( $poiList, 'poiName',$poiName );
            $poiList = array_add ( $poiList, 'mobileNos',$mobileNos );
            $poiList = array_add ( $poiList, 'geoLocation',$geoLocation );
            $poiList = array_add ( $poiList, 'geoAddress',$geoAddress );
            $poiList = array_add ( $poiList, 'direction',$direction ); 
            $poiList = array_add ( $poiList, 'geoFenceType',$geoFenceType );
             $poiList = array_add ( $poiList, 'proximityLevel',$proximityLevel );
             $poiList = array_add ( $poiList, 'message',$message );
             $poiList = array_add ( $poiList, 'email',$email );
            
           $out= json_encode($poiList);
                
           $redis->hset('H_Vehicle_' . $fcode .'_GF',$id,$out);
            Log::info(' update id ' . $id);      
           $tok = explode(':', $id);
            $vehicleId=$tok[0];
            Session::flash('message', 'Successfully updated ' . $id . '!');
            
            return Redirect::to('vdmGeoFence/' . $vehicleId);
    }

    public function create() {
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user()->username;
          $redis = Redis::connection();
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
        
        $vehicleList = $redis->smembers('S_Vehicles_' . $fcode);
        
        $userVehicles=null;
        
        foreach ($vehicleList as $key=>$value) {
            $userVehicles=array_add($userVehicles, $value, $value);
        }
        $vehicleList=$userVehicles;

        return View::make ( 'vdm.geoFence.create' )->with('vehicleList',$vehicleList);
        
    }
    
     public function store()
    {
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        //TODO Add validation
       $rules = array (
                 'vehicleId' => 'required',
                'geoFenceId' => 'required',
                'poiName' => 'required'
              
        );
        $validator = Validator::make ( Input::all (), $rules );
        if ($validator->fails ()) {
            return Redirect::to ( 'vdmGeoFence/create' )->withErrors ( $validator );
        } else {
            $username = Auth::user()->username;
            $vehicleId      = Input::get('vehicleId');
            $geoFenceId       = Input::get('geoFenceId');
            $poiName       = Input::get('poiName');
            $mobileNos       = Input::get('mobileNos');
            $geoLocation       = Input::get('geoLocation');
            $geoAddress       = Input::get('geoAddress');
            $direction       = Input::get('direction');
            $geoFenceType       = Input::get('geoFenceType');
            $proximityLevel       = Input::get('proximityLevel');
            $message        = Input::get('message');  
            $email        = Input::get('email');  
            $redis = Redis::connection();
            $poiList=null;
            
            $poiList = array_add ( $poiList, 'geoFenceId',$geoFenceId );
            $poiList = array_add ( $poiList, 'poiName',$poiName );
            $poiList = array_add ( $poiList, 'mobileNos',$mobileNos );
            $poiList = array_add ( $poiList, 'geoLocation',$geoLocation );
            $poiList = array_add ( $poiList, 'geoAddress',$geoAddress );
            $poiList = array_add ( $poiList, 'direction',$direction ); 
            $poiList = array_add ( $poiList, 'geoFenceType',$geoFenceType );
            $poiList = array_add ( $poiList, 'proximityLevel',$proximityLevel );
            $poiList = array_add ( $poiList, 'message',$message );
            $poiList = array_add ( $poiList, 'email',$email );
         
            $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
           $out= json_encode($poiList);
                
           $redis->rpush('L_'.$vehicleId.'_'.$fcode .'_GF',$geoFenceId);
           $redis->hset('H_Vehicle_' . $fcode .'_GF',$vehicleId.':'.$geoFenceId,$out);
           $id=$vehicleId;     
           Log::info( ' id '.$id);
           
           // redirect
            Session::flash('message', 'Successfully updated ' . $vehicleId.':'.$geoFenceId . '!');
            
            return Redirect::to('vdmGeoFence/' . $id);
            
       }
    }


    public function destroy($id) {
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
         Log::info( ' key received ' . $id);
        $tok = explode(':', $id);
        $vehicleId=$tok[0];
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        Log::info( ' tok[1] ' . $tok[1]);
        $redis->lrem ( 'L_'.$vehicleId. '_' . $fcode .'_GF',1,$tok[1]);
        $redis->hdel('H_Vehicle_' . $fcode .'_GF',$id);
        Session::flash ( 'message', 'Successfully deleted ' . $id .'!' );
        Log::info( ' vehicleId ' . $vehicleId);
        return Redirect::to ( 'vdmGeoFence/' . $vehicleId );
    }

}