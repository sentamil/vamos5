<?php
class RfidController extends \BaseController {







    public function create() {
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        return View::make ( 'vdm.rfid.create' );
    }


    public function create1() {
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        log::info( '------lahan---------- '.Session::get('cur'));
        $id=1;
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
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
        $vehRfidYesList=null;
        foreach ( $vehicleList as $vehicle ) {

            Log::info('$vehicle ' .$vehicle);
            $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );

            if(isset($vehicleRefData)) {
                Log::info('$vehicle ' .$vehicleRefData);
            }else {
                continue;
            }
            $vehicleRefData=json_decode($vehicleRefData,true);
            $isRfid=isset($vehicleRefData['isRfid'])?$vehicleRefData['isRfid']:'no';
            if($isRfid=='yes')
            {
                $vehRfidYesList = array_add($vehRfidYesList,$vehicle,$vehicle);
            }

        }               
        return View::make ( 'vdm.rfid.create' )->with('vehRfidYesList', $vehRfidYesList);
    }





    public function store() {
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }

        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

        $rules = array (
            'tags' => 'required|numeric'          
            );

        $validator = Validator::make ( Input::all (), $rules );
        log::info( '-------- tags 1 ::----------');

        if ($validator->fails ()) {
            return Redirect::to ( 'rfid/create' )->withErrors ( $validator );
        } 
        else{ 
            $tags = Input::get ( 'tags' );
            Session::put('tags',$tags);
            log::info( '--------inside tag add in  ::----------');
            
            $orgListId = 'S_Organisations_' . $fcode;

            if(Session::get('cur')=='dealer')
            {
                log::info( '------login 1---------- '.Session::get('cur'));
                $orgListId = 'S_Organisations_Dealer_'.$username.'_'.$fcode;
            }
            else if(Session::get('cur')=='admin')
            {
                $orgListId = 'S_Organisations_Admin_'.$fcode;
            }


            //Log::info('orgListId=' . $orgListId);

            $orgList = $redis->smembers ( $orgListId);
            $orgArray = array();

            foreach ( $orgList as $org ) {

                $orgArray = array_add($orgArray, $org,$org);
//TODO --- more details obtained here
            }
            $vehRfidYesList=null;
 $vehRfidYesList = array_add($vehRfidYesList, 'select','select');
 $vehList=null;
 $vehList = array_add($vehList, 'select','select');
            $orgList=$orgArray ;
    Log::info('tag=' . $tags);
            return View::make ( 'vdm.rfid.addTags',array ('vehList' => $vehList) )->with ( 'tags', $tags )->with ( 'vehRfidYesList', $vehRfidYesList )->with ( 'orgList', $orgList )->with('ahan','ahan');
        }


    }




    public function addTags() {
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }

        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

// store
        $tagArray=null;
        for($i =1;$i<=Session::get('tags');$i++)
        {
            $tagid = Input::get ( 'tagid'.$i);

            $tagid=trim($tagid,"");
            $belongsTo=Input::get('belongsTo');
            log::info( '--------inside belongsTo in  ::----------'.$belongsTo);
            $swipedBy=Input::get('sports');
            $org=Input::get('org');
            // log::info( '--------inside tagname in  ::----------'.$tagname);
            if($tagid!==null && $tagid!=='' && $belongsTo!==null && $belongsTo!=='' && $belongsTo!=='select' && $org!==null && $org!=='' && $org!=='select')
            {
                log::info( '-------- add test  ::----------'.Session::get('tags'));
                $val=$redis->sismember ( 'S_Rfid_Tags_' . $fcode, $tagid);
                $val1=$redis->hget('H_Rfid_Map',$tagid);
                if($val==1 || $val1!==null)
                {
                    $tagArray=array_add($tagArray,$tagid,$tagid);
                }
                else
                {
                    log::info( '--------inside tagid in  ::----------'.$tagid);
                    $tagname=Input::get('tagname'.$i);
                    
                    
                    $swipevalue='';
                    if($swipedBy!=null)
                    {
                        $swipevalue=implode(",",$swipedBy);
                    }
                    $redis->sadd('S_Rfid_Tags_' . $fcode,$tagid);
                    $redis->hset('H_Rfid_Map',$tagid,$fcode);
                    $redis->sadd('S_Rfid_Org_'.$org.'_' . $fcode,$tagid);
                        $refDataArr = array (
                                        'tagid' => $tagid,
                                        'distance' => 0,
                                        'org' => $org,
                                        'belongsTo' => $belongsTo,
                                        'tagname' => $tagname,
                                        'swipevalue' => $swipevalue,
                        );
                     $refDataJson = json_encode ( $refDataArr );                          
                    if(Session::get('cur')=='dealer')
                    {             
                        $redis->hset('H_Rfid_Dealer_'.$username.'_'.$fcode,$tagid,$refDataJson);   
                    }
                    else if(Session::get('cur')=='admin')
                    {
                        $redis->hset('H_Rfid_Admin_'.$fcode,$tagid,$refDataJson); 
                    }       



                }
            }





        }
        $error='';
        if($tagArray!=null)
        {
            $error=implode(" ",$tagArray);
            $error='Enter correct details for  '.$error;
        }



        return Redirect::to ( 'rfid' )->withErrors ( $error );



    }






    public function index() {
        if (! Auth::check () ) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;

        log::info( 'User name  ::' . $username); 
        $redis = Redis::connection ();

        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

        $key='';
        if(Session::get('cur')=='dealer')
        {    
            $key= 'H_Rfid_Dealer_'.$username.'_'.$fcode;     
        }
        else if(Session::get('cur')=='admin')
        {
            $key='H_Rfid_Admin_'.$fcode;
        }       
        $values=$redis->hgetall($key);
        $taglist =null;
        $distanceList = null;
        $orgList =null;
        $belongsToList = null;
        $tagnameList =null;
        $swipevalueList = null;
        foreach ($values as $key => $value) {
            $valueT=json_decode($value,true);
             $tagid=isset($valueT['tagid'])?$valueT['tagid']:'nill';
            $taglist = array_add($taglist,$key,$tagid);
            $distance=isset($valueT['distance'])?$valueT['distance']:'0';
            $distanceList = array_add($distanceList,$key,$distance);
            $org=isset($valueT['org'])?$valueT['org']:'nill';
            $orgList = array_add($orgList,$key,$org);
            $belongsTo=isset($valueT['belongsTo'])?$valueT['belongsTo']:'nill';
            $belongsToList = array_add($belongsToList,$key,$belongsTo);
            $tagname=isset($valueT['tagname'])?$valueT['tagname']:'nill';
            $tagnameList = array_add($tagnameList,$key,$tagname);
            $swipevalue=isset($valueT['swipevalue'])?$valueT['swipevalue']:'nill';
            $swipevalueList = array_add($swipevalueList,$key,$swipevalue);
        }
        return View::make ( 'vdm.rfid.index')->with('values',$values)->with('taglist',$taglist)->with('distanceList',$distanceList)->with('orgList',$orgList)->with('belongsToList',$belongsToList)->with('tagnameList',$tagnameList)->with('swipevalueList',$swipevalueList);
    }





    public function destroy($id)
    {
        $tagid=$id;
        if (! Auth::check () ) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;

        log::info( 'User name  ::' . $username); 
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        $redis->srem('S_Rfid_Tags_' . $fcode,$tagid);
        $redis->hdel('H_Rfid_Map',$tagid);

        if(Session::get('cur')=='dealer')
        {    
            $keyt='H_Rfid_Dealer_'.$username.'_'.$fcode; 
        }
        else if(Session::get('cur')=='admin')
        {
            $keyt='H_Rfid_Admin_'.$fcode;
        }       
        $valueT=$redis->hget($keyt,$tagid);
        $orgname =explode(";",$valueT)[0];
        $redis->srem('S_Rfid_Org_'.$orgname.'_' . $fcode,$tagid);
        $redis->hdel($keyt,$tagid); 


        return Redirect::to('rfid');
    }

    public function edit($id)
    {
        if (! Auth::check () ) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;

        log::info( 'User name  ::' . $username); 
        $redis = Redis::connection ();

        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

        $key='';
        if(Session::get('cur')=='dealer')
        {    
            $key= 'H_Rfid_Dealer_'.$username.'_'.$fcode;     
        }
        else if(Session::get('cur')=='admin')
        {
            $key='H_Rfid_Admin_'.$fcode;
        }       
        $values=$redis->hget($key,$id);

        $tagid =$id;

            $valueT=json_decode($values,true);
             $tagname=isset($valueT['tagname'])?$valueT['tagname']:'nill';
            $orgname=isset($valueT['org'])?$valueT['org']:'nill';
             $belongsto=isset($valueT['belongsTo'])?$valueT['belongsTo']:'nill';
              $swipeby=isset($valueT['swipevalue'])?$valueT['swipevalue']:'nill';
                $swipeby=explode(',',$swipeby);
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
        $vehRfidYesList=null;
        $vehList=null;
        $vehRfidYesList = array_add($vehRfidYesList,'select','select');
         $vehList = array_add($vehList,'select','select');   
        foreach ( $vehicleList as $vehicle ) {
            $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );

            if(isset($vehicleRefData)) {
            }else {
                continue;
            }
            $vehicleRefData=json_decode($vehicleRefData,true);
            $isRfid=isset($vehicleRefData['isRfid'])?$vehicleRefData['isRfid']:'no';
            $org=isset($vehicleRefData['orgId'])?$vehicleRefData['orgId']:'Default';
                 if($isRfid=='yes' && $org==$orgname)
                {
                   
                    $vehRfidYesList = array_add($vehRfidYesList,$vehicle,$vehicle);
                }  
                if($org==$orgname)
                {
                    
                      $vehList = array_add($vehList,$vehicle,$vehicle);  
                }       
        }               
        $orgListId = 'S_Organisations_' . $fcode;

        if(Session::get('cur')=='dealer')
        {
            log::info( '------login 1---------- '.Session::get('cur'));
            $orgListId = 'S_Organisations_Dealer_'.$username.'_'.$fcode;
        }
        else if(Session::get('cur')=='admin')
        {
            $orgListId = 'S_Organisations_Admin_'.$fcode;
        }


        Log::info('orgListId=' . $orgListId);

        $orgList = $redis->smembers ( $orgListId);
        $orgArray = array();

        foreach ( $orgList as $org ) {

            $orgArray = array_add($orgArray, $org,$org);
//TODO --- more details obtained here
        }
        $orgList=$orgArray ;


        return View::make ( 'vdm.rfid.edit',array ('vehList' => $vehList) )->with ( 'vehRfidYesList', $vehRfidYesList )->with ( 'tagid', $tagid )->with ( 'tagname', $tagname )->with ( 'orgname', $orgname )->with ( 'belongsto', $belongsto )->with ( 'swipeby', $swipeby )->with ( 'orgList', $orgList );

    }

    public function update()
    {
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }

        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

// store
        $tagArray=null;
        // for($i =1;$i<=Session::get('tags');$i++)
        // {
            $tagid = Input::get ( 'tagid');
            $tagidtemp = Input::get ( 'tagidtemp');

            $tagid=trim($tagid,"");
            $belongsTo=Input::get('belongsTo');
            log::info( '--------inside belongsTo in  ::----------'.$belongsTo);
            $swipedBy=Input::get('sports');
            $org=Input::get('org');
            // log::info( '--------inside tagname in  ::----------'.$tagname);
            if($tagid!==null && $tagid!=='' && $belongsTo!==null && $belongsTo!=='' && $belongsTo!=='select' && $org!==null && $org!=='' && $org!=='select')
            {
                log::info( '-------- add test  ::----------'.Session::get('tags'));
                $val=$redis->sismember ( 'S_Rfid_Tags_' . $fcode, $tagid);
                $val1=$redis->hget('H_Rfid_Map',$tagid);
                if(($val==1 || $val1!==null) && $tagid!==$tagidtemp)
                {
                    $tagArray=array_add($tagArray,$tagid,$tagid);
                }
                else
                {
                    log::info( '--------inside tagid in  ::----------'.$tagid);
                    $tagname=Input::get('tagname');
                    
                    
                    $swipevalue='';
                    if($swipedBy!=null)
                    {
                        $swipevalue=implode(",",$swipedBy);
                    }
                    $redis->sadd('S_Rfid_Tags_' . $fcode,$tagid);
                     $redis->srem('S_Rfid_Tags_' . $fcode,$tagidtemp);
                    $redis->hset('H_Rfid_Map',$tagid,$fcode);
                    $redis->hdel('H_Rfid_Map',$tagidtemp);
                    $redis->sadd('S_Rfid_Org_'.$org.'_' . $fcode,$tagid);
                    $redis->srem('S_Rfid_Org_'.$org.'_' . $fcode,$tagidtemp);

                        $refDataArr = array (
                                        'tagid' => $tagid,
                                        'distance' => 0,
                                        'org' => $org,
                                        'belongsTo' => $belongsTo,
                                        'tagname' => $tagname,
                                        'swipevalue' => $swipevalue,
                        );
                     $refDataJson = json_encode ( $refDataArr );       

                    if(Session::get('cur')=='dealer')
                    {             
                        $redis->hset('H_Rfid_Dealer_'.$username.'_'.$fcode,$tagid,$refDataJson); 
                        $redis->hdel('H_Rfid_Dealer_'.$username.'_'.$fcode,$tagidtemp);   
                    }
                    else if(Session::get('cur')=='admin')
                    {
                        $redis->hset('H_Rfid_Admin_'.$fcode,$tagid,$refDataJson);
                        $redis->hdel('H_Rfid_Admin_'.$fcode,$tagidtemp); 
                    }       



                }
            }





       // }
        $error='';
        if($tagArray!=null)
        {
            $error=implode(" ",$tagArray);
            $error='Enter correct details for  '.$error;
        }



        return Redirect::to ( 'rfid' )->withErrors ( $error );
    }


public function getVehicle()
{
    log::info( 'ahan'.'-------- laravel test ::----------'.User::find(Input::get('id')));
    if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }

        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

$orgId = Input::get ( 'id');

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
            $vehRfidYesList=null;
            $vehList=null;
            $i=0;$j=0;
             $vehRfidYesList = array_add($vehRfidYesList,'select','select');
              $vehList = array_add($vehList,'select','select');  
            foreach ( $vehicleList as $vehicle ) {

                Log::info('$vehicle ' .$vehicle);
                $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );

                if(isset($vehicleRefData)) {
                    Log::info('$vehicle ' .$vehicleRefData);
                }else {
                    continue;
                }
                $vehicleRefData=json_decode($vehicleRefData,true);
                 $org=isset($vehicleRefData['orgId'])?$vehicleRefData['orgId']:'Default';
                $isRfid=isset($vehicleRefData['isRfid'])?$vehicleRefData['isRfid']:'no';

                if($isRfid=='yes' && $org==$orgId)
                {
                    $i++;
                    $vehRfidYesList = array_add($vehRfidYesList,$vehicle,$vehicle);
                }  
                if($org==$orgId)
                {
                      $j++;
                      $vehList = array_add($vehList,$vehicle,$vehicle);  
                }
                      
            }  



$refDataArr = array (

        'rfidlist' => $vehRfidYesList,
        'vehicle' => $vehList

);

$refDataJson = json_encode ( $refDataArr );
            
            log::info('changes value '.$orgId);            
    return Response::json($refDataArr);
}

}