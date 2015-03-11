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
        
         return View::make('vdm.school.create');
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
            return Redirect::to('vdmSchool/create')
            ->withErrors($validator);
            
        } else {
            // store
            
            $schoolId       = Input::get('schoolId');
            $routes      = Input::get('routes');
            
            $redis = Redis::connection();
            $fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
            $redis->sadd('S_School_' . $fcode, $groupId . ':' . $fcode);
            
            $routesArr = explode(",",$routes);
            
            foreach($routesArr as $route) {
                $redis->sadd('S_'. ':' . $fcode,$route);
            }

            // redirect
            Session::flash('message', 'Successfully created ' . $groupId . '!');
            return Redirect::to('vdmGroups');
            }
        
    }
    
 
    
}
