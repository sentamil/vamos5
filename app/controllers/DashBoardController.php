<?php
class DashBoardController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		if (! Auth::check () ) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		log::info( 'User name  ::' . $username);
		Session::forget('page');
		
		$redis = Redis::connection ();
		
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		Log::info('fcode=' . $fcode);
		$dealerId =null;
		$count=0;
		$new_date = date('FY', strtotime("+1 month"));
		$expireData=$redis->hget ( 'H_Expire_' . $fcode, $new_date);
		
		$vechile=array();$temp=array();
		if($expireData!==null)
		{
			
			$vehiclesExpire = explode(',',$expireData);
			if(Session::get('cur')=='dealer')
			{
				foreach($vehiclesExpire as $org) {
					$vehicleListId='S_Vehicles_Dealer_'.$username.'_'.$fcode;
					$value=$redis->SISMEMBER($vehicleListId,$org);
					if($value==1)
					{
						log::info( 'value if--->' . $value);
						$vechile = array_add($vechile, $org, $org);
					}
				}
			}
			else if(Session::get('cur')=='admin')
			{
				$dealer = $redis->smembers('S_Dealers_'. $fcode);  
				
						foreach($dealer as $org1) 
						{$temp3=0;
							$vechile1 =array();
								foreach($vehiclesExpire as $org) {
								$vehicleListId='S_Vehicles_Admin_'.$fcode;
								$value=$redis->SISMEMBER($vehicleListId,$org);
						
								if($value==1)
								{
									$vechile = array_add($vechile, $org, $org);
								}
							else{
							
									
									$vehicleListId='S_Vehicles_Dealer_'.$org1.'_'.$fcode;
									$value1=$redis->SISMEMBER($vehicleListId,$org);
									if($value1==1)
									{
										$vechile1 = array_add($vechile1, $org, $org);
										$temp2=count($vechile1);
									
									if($temp2!=0)
									{
										log::info('value not zero'.$temp3);
										try
										{
											$temp3=$temp[org1]+$temp2;
										}
										catch(\Exception $e)
										{
											$temp3=$temp2;
										}
											unset($temp[$org1]);
											$temp = array_add($temp, $org1,strval($temp3));
									}
									}
									
									
								$value1=0;
								//
								}						
							}
					
						}
			}
				
		}
		Log::info('count');
		Log::info(count($vechile));
		$new_date1 = date('FY', strtotime("+11 month"));
		$presentMonth=$redis->hget ( 'H_Expire_' . $fcode, $new_date1);
		log::info($presentMonth.'month '.$new_date1);
		
		$prsentMonthCount=DashBoardController::getCount($presentMonth,$fcode,$username);
		Log::info('count present '.$prsentMonthCount);
		$new_date2 = date('FY', strtotime("+12 month"));
		$nextMonth=$redis->hget ( 'H_Expire_' . $fcode, $new_date2);
		log::info($nextMonth.'month '.$new_date2);
		$nextMonthCount=DashBoardController::getCount($nextMonth,$fcode,$username);
		Log::info('next count '.$nextMonthCount);
		
		
		$new_date3 = date('FY', strtotime("+10 month"));
		$prevMonth=$redis->hget ( 'H_Expire_' . $fcode, $new_date3);
		log::info($prevMonth.'month '.$new_date3);
		$prevMonthCount=DashBoardController::getCount($prevMonth,$fcode,$username);
		Log::info('prev count '.$prevMonthCount);
		
		
		
		
		
		
		
		if(Session::get('cur')=='dealer')
		{
			$vehicleListId='S_Vehicles_Dealer_'.$username.'_'.$fcode;
			$count=$redis->scard($vehicleListId);
			$vechileEx=' ';
			$vechileEx1=' ';
		}
		else if(Session::get('cur')=='admin')
		{
			$vechileEx='Number of Vehicles Expired this month for dealers :';
			$vechileEx1=' ';
			$vehicleListId='S_Vehicles_Admin_'.$fcode;
			$count=$redis->scard($vehicleListId);
			log::info( 'count  ::' . $count);
			$dealerId = $redis->smembers('S_Dealers_'. $fcode);        
			$orgArr = array();
			foreach($dealerId as $org) {
				log::info( 'Dealer  ::' . $org);
				$vehicleListId='S_Vehicles_Dealer_'.$org.'_'.$fcode;
				$count=$count+$redis->scard($vehicleListId);
				$orgArr = array_add($orgArr, $org,$redis->scard($vehicleListId));
				log::info( 'count in  ::' . $count);
			}
			$dealerId = $orgArr;
			log::info( 'count  ::' . $count);
		}
		else{
			$vehicleListId = 'S_Vehicles_' . $fcode;
		}
		
		return View::make ( 'vdm.vehicles.dashboard')->with('count',$count)->with('dealerId',$dealerId)->with('vechile',$vechile)->with('temp',$temp)->with('vechileEx',$vechileEx)->with('vechileEx1',$vechileEx1)->with('prsentMonthCount',$prsentMonthCount)->with('nextMonthCount',$nextMonthCount)->with('prevMonthCount',$prevMonthCount);
	}
	
	
	public function getCount($presentData,$fcode,$username)
	{
		$redis = Redis::connection ();
		$vechilePre=array();$tempPre=array();
		if($presentData!==null)
		{
			
			$vehiclesExpire = explode(',',$presentData);
			if(Session::get('cur')=='dealer')
			{
				foreach($vehiclesExpire as $orgPre) {
					$vehicleListIdPre='S_Vehicles_Dealer_'.$username.'_'.$fcode;
					$valuePre=$redis->SISMEMBER($vehicleListIdPre,$orgPre);
					if($valuePre==1)
					{
						log::info( 'value if--->' . $valuePre);
						$vechilePre = array_add($vechilePre, $orgPre, $orgPre);
					}
				}
			}
			else if(Session::get('cur')=='admin')
			{
				log::info( 'else if--->' . count($vehiclesExpire));		
							
					foreach($vehiclesExpire as $orgPre) {
					$vehicleListIdPre='S_Vehicles_'.$fcode;
					$valuePre=$redis->SISMEMBER($vehicleListIdPre,$orgPre);
			
					if($valuePre==1)
					{
						$vechilePre = array_add($vechilePre, $orgPre, $orgPre);
					}
					else{
						log::info( 'else if--->' . $orgPre);
					}
												
							
					
			}
			}
				
		}
		return count($vechilePre);
	}
	
	 protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            //DB::table('recent_users')->delete();
			
        })->everyMinute();
    }
	}
