<?php

class VdmSchoolController extends \BaseController {
 
 /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $redis = Redis::connection();
        
        $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
        
         return View::make('vdm.schools.create');
    }
    
    public function store()
    {
        
        if(!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user()->username;
        $rules = array(
                'schoolId'       => 'required',
                'routes' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('vdmSchools/create')
            ->withErrors($validator);
            
        } else {
            // store
            
            $schoolId       = Input::get('schoolId');
            $routes      = Input::get('routes');
            
            $redis = Redis::connection();
            $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
            $redis->sadd('S_Schools_'. $fcode, $schoolId);
            
            $routesArr = explode(",",$routes);
            
            foreach($routesArr as $route) {
                if(empty($route)) {
                    continue;
                }
                $route = strtoupper($route);
                $redis->sadd('S_School_Route_'.$schoolId .'_'. $fcode, $route);
            }

            // redirect
            Session::flash('message', 'Successfully created ' . $schoolId . '!');
            return Redirect::to('vdmSchools');
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
        
        $schoolListId = 'S_Schools_' . $fcode;
        
        Log::info('schoolListId=' . $schoolListId);

        $schoolList = $redis->smembers ( $schoolListId);
        
       
        
        foreach ( $schoolList as $school ) {
            
        
            //TODO --- more details obtained here
        }
        
       Log::info('Forwarding to schools index view');
         
        return View::make ( 'vdm.schools.index', array (
                'schoolList' => $schoolList 
        ) );
    }
    
 
    public function destroy($id) {
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        
        $schoolId = $id;
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        
    }
    
}
