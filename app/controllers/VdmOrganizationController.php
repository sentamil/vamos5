<?php
class VdmOrganizationController extends \BaseController {
    
  
    
    public function create() {
         if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        
        
        return View::make('vdm.organization.create');   
    }


    public function store()
    {
        
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $rules = array(
                'organizationId'       => 'required',
                'routes' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('vdmSchools/create')
            ->withErrors($validator);
            
        } else {
            // store
            
            $organizationId       = Input::get('organizationId');
            $routes      = Input::get('routes');
            $email      = Input::get('email');
            $description      = Input::get('description');
            $address      = Input::get('address');
            $routes      = Input::get('routes');

            $orgDataArr = array (
                    'routes' => $routes,
                    'email' => $email,
                    'address' => $address,
                    'routes' => $routes,
            );
            
            $orgDataJson = json_encode ( $orgDataArr );
         
            
            $redis = Redis::connection();
            $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
            $redis->sadd('S_Organizations_'. $fcode, $organizationId);
            
            $redis->hset('H_Orgnizations_'.$fcode,$organizationId,$orgDataJson );
            
            $routesArr = explode(",",$routes);
            
            foreach($routesArr as $route) {
                if(empty($route)) {
                    continue;
                }
                $redis->sadd('S_Organization_Route_'.$organizationId .'_'. $fcode, $route);
            }

            // redirect
            Session::flash('message', 'Successfully created ' . $organizationId . '!');
            return Redirect::to('vdmOrganization');
        }
        
    }
    
     public function index() {
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        
        log::info( 'User name  ::' . $username);
        
        
        $redis = Redis::connection ();
        
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        
        Log::info('fcode=' . $fcode);
        
        $orgListId = 'S_Organizations_' . $fcode;
        
        Log::info('orgListId=' . $orgListId);

        $orgList = $redis->smembers ( $orgListId);
        
       
      //  Log::info(' $orgList ' . $orgList);
        
        $orgArray = array();
        
        foreach ( $orgList as $org ) {
            
            $orgArray = array_add($orgArray, $org,$org);
            //TODO --- more details obtained here
        }
        
         
        return View::make ( 'vdm.organization.index', array (
                'orgList' => $orgList 
        ) );
    }

    public function destroy($id)
    {
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $redis = Redis::connection();
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
        
        Log::info(' delete id =' . $id);
        
        $redis->srem('S_Organizations_' . $fcode,$id);
        $redis->hdel('H_Organizations_'.$fcode,$id);
        
        $userList = $redis->smembers('S_Users_' . $fcode);
        
        foreach ( $userList as $user ) {
            //S_Orgs_prasanna
            //TODO while creating users -- create a set S_Orgs_<username> - add the 
            //organization he is attached to
            $redis->srem('S_Orgs_' . $user . '_' . $fcode,$id);  
        }
            
        Session::flash('message', 'Successfully deleted ' . $id . '!');
        return Redirect::to('vdmOrganization');
    }
    
    
    public function edit($id)
    {
                if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        
     
        
        $redis = Redis::connection();
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
        $jsonData =  $redis->hget('H_Orgnizations_'.$fcode,$id );
        
        $orgDataArr = json_decode ( $jsonData, true );
        
        $email =isset($orgDataArr['email'])?$orgDataArr['email']:' ';
        $address =isset($orgDataArr['address'])?$orgDataArr['address']:' ';
        $description =isset($orgDataArr['description'])?$orgDataArr['description']:' ';
        $routes =isset($orgDataArr['routes'])?$orgDataArr['routes']:' ';
        
        log::info( 'routes ::' . $routes);
        
        return View::make('vdm.organization.edit')->with('routes',$routes)->with('description',$description)->with('address',$address)->
        with('organizationId',$id)->with('email',$email);   
        
    }
    
    public function update($id)
    {
        
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        
        //no rules as of now
        $rules=array();
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('vdmSchools/create')
            ->withErrors($validator);
            
        } else {
            // store
            $routes      = Input::get('routes');
            $email      = Input::get('email');
            $description      = Input::get('description');
            $address      = Input::get('address');
            $organizationId = $id;
            $orgDataArr = array (
                    
                    'routes' => $routes,
                    'email' => $email,
                    'address' => $address,
                    'description' => $description,
            );
            
            $orgDataJson = json_encode ( $orgDataArr );
         
            
            $redis = Redis::connection();
            $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
            $redis->sadd('S_Organizations_'. $fcode, $organizationId);
            
            $redis->hset('H_Orgnizations_'.$fcode,$organizationId,$orgDataJson );
            
            $routesArr = explode(",",$routes);
            
            foreach($routesArr as $route) {
                if(empty($route)) {
                    continue;
                }
                $redis->sadd('S_Organization_Route_'.$organizationId .'_'. $fcode, $route);
            }

            // redirect
            Session::flash('message', 'Successfully created ' . $organizationId . '!');
            return Redirect::to('vdmOrganization');
        }
        
    }   
           
}