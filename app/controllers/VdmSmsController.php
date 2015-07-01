<?php

class VdmSmsController extends \BaseController {

    public function filter() {

        log::info(" VdmSmsController @ filter");
        if (!Auth::check()) {
            return Redirect::to('login');
        }
        $username = Auth::user() -> username;
        $redis = Redis::connection();
        $fcode = $redis -> hget('H_UserId_Cust_Map', $username . ':fcode');

        //get school list

        $orgsList = $redis -> smembers('S_Organizations_' . $fcode);

        $orgsArr = array();

        foreach ($orgsList as $org) {
            $orgsArr = array_add($orgsArr, $org, $org);

        }
        
        //why routeNo is not used -- because multiple vechiles can have same routes
        
        //route list == S_School_Route_CVSM_fcode
        //TODO -- bring in ajax request ---to send route List 
       /* 
        $routes = $redis -> smembers('S_School_Route_CVSM_' . $fcode);  //TODO -- CVSM hardcoding should be removed
        $routeList = array();
         foreach ($routes as $route) {
            $routeList = array_add($routeList, $route, $route);

        }
        
        */
        
        return View::make('vdm.sms.index') -> with('orgsArr', $orgsArr);



    }

    public function show() {

        Log::info(" VdmSmsController @ show");

        $orgId = Input::get('orgId');
        $tripType = Input::get('tripType');
        $date = Input::get('date');
        $vehicleId = Input::get('vehicleId');

        $username = Auth::user() -> username;
        $redis = Redis::connection();
        $fcode = $redis -> hget('H_UserId_Cust_Map', $username . ':fcode');
        
        $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );
        $vehicleRefData=json_decode($vehicleRefData,true);
        
            
        Log::info(' vehicleId ' . $vehicleId . ' date=' . $date . ' orgId='.$orgId . ' $tripType='.$tripType);
        
        $routeNo = $vehicleRefData['shortName'];     
        
        Log::info(' routeNo from redData ' . $routeNo);
        $ipaddress = $redis->get('ipaddress');
        $parameters='?userId='. $username;
        $parameters=$parameters . '&vehicleId=' . $vehicleId . '&date=' . $date;
        $url = 'http://' .$ipaddress .':9000/getSchoolBusSMSReport' . $parameters;
        
        Log::info(' url ' . $url);
        
        
    //    $url = "http://vamosys.com:9000/getSchoolBusSMSReport?userId=alhadeed&routeNo=R16A&vehicleId=CV-D5994&date=2015-04-30";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $result = json_decode($response,true);
        
     //   var_dump($result);
        $vehicleId = $result['vehicleId'];
        
        Log::info(' vehicleId = ' . $vehicleId);
        
        $deliveryDate = $result['deliveryDate'];
        $stopNames = $result['stopNames'];
        
         //Log::info(' $stopNames = ' . $stopNames);
         
         //var_dump($stopNames);
        $mobileNos = $result['mobileNos'];
        $deliveryTime = $result['deliveryTime'];
        
        
        foreach ($stopNames as $value) {
        }
        foreach ($mobileNos as $value) {
        }
        foreach ($deliveryTime as $value) {
        }
        Log::info(' before view');

        return View::make('vdm.sms.report') -> with('stopNames', $stopNames) -> with('mobileNos', $mobileNos) -> with('deliveryTime', $deliveryTime) -> with('vehicleId', $vehicleId) -> with('routeNo', $routeNo) -> with('deliveryDate', $deliveryDate);

    }

}
