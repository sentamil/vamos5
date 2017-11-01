<?php
use Carbon\Carbon;
class DashBoardController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function indexToDelete() {
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
								$vehicleListId='S_Vehicles_'.$fcode;
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
		$new_date1 = date('FY', strtotime("+12 month"));
		$presentMonth=$redis->hget ( 'H_Expire_' . $fcode, $new_date1);
		log::info($presentMonth.'month '.$new_date1);
		
		$prsentMonthCount=DashBoardController::getCount($presentMonth,$fcode,$username);
		Log::info('count present '.$prsentMonthCount);
		$new_date2 = date('FY', strtotime("+13 month"));
		$nextMonth=$redis->hget ( 'H_Expire_' . $fcode, $new_date2);
		log::info($nextMonth.'month '.$new_date2);
		$nextMonthCount=DashBoardController::getCount($nextMonth,$fcode,$username);
		Log::info('next count '.$nextMonthCount);
		
		
		$new_date3 = date('FY', strtotime("+11 month"));
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
	
public function getprevmonth($curmonth, $prevmonth)
{
	$monthlist 	= [1,2,3,4,5,6,7,8,9,10,11,12];
	return ($curmonth < $prevmonth) ? $monthlist[(count($monthlist)-($prevmonth - $curmonth))-1] : $monthlist[(count($monthlist)-($curmonth - $prevmonth))-1];
}

public function getnextmonth($curmonth, $nextmonth)
{
	$monthlist 	= [1,2,3,4,5,6,7,8,9,10,11,12];
	return ((($curmonth+$nextmonth) < 12)? $monthlist[($curmonth+$nextmonth)-1] : $monthlist[(($curmonth+$nextmonth)-count($monthlist))-1]);
}

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
		$temp=array();
		$dealerId=array();


			if(Session::get('cur')=='dealer')
			{
				$count=DB::table('Vehicle_details')
				            ->where('fcode', $fcode)->where('belongs_to', $username)->count();
						//$dealer = $redis->smembers('S_Dealers_'. $fcode); 

						$month=date("m");
						$year=date("Y");

						log::info(' month_ ' .$month.' _year_ '.$year);
						log::info(DashBoardController::getprevmonth($month, 7));
						log::info(DashBoardController::getDateT(59,59,23,15,$month,$year));

						

				$prsentMonthCount=DB::table('Vehicle_details')
				            ->where('fcode', $fcode)->whereBetween('sold_date', array(DashBoardController::getDateT(0,0,0,16,DashBoardController::getprevmonth($month, 1),$year), DashBoardController::getDateT(59,59,23,15,$month,$year)))->where('belongs_to', $username)->count();

				            

	           $prevMonthCount=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->whereBetween('sold_date', array(DashBoardController::getDateT(0,0,0,16,DashBoardController::getprevmonth($month, 2),$year), DashBoardController::getDateT(59,59,23,15,DashBoardController::getprevmonth($month, 1),$year)))->where('belongs_to', $username)->count();
	            
	            log::info($prevMonthCount);
				log::info(' prev ');

				//$nextMonthCount=DB::table('Vehicle_details')
	           // ->where('fcode', $fcode)->whereBetween('sold_date', array(DashBoardController::getDateT(0,0,0,16,$month,$year), DashBoardController::getDateT(59,59,23,15,DashBoardController::getnextmonth($month, 1),$year)))->where('belongs_to', $username)->count();
			}
			else if(Session::get('cur')=='admin')
			{

				$count=DB::table('Vehicle_details')
				            ->where('fcode', $fcode)->count();
						$dealer = $redis->smembers('S_Dealers_'. $fcode);  
						foreach($dealer as $org1) 
						{
							log::info( 'dealar name' . $org1);
							$count1=DB::table('Vehicle_details')
				            ->where('fcode', $fcode)->where('belongs_to', $org1)->count();

				            $dealerId = array_add($dealerId, $org1,strval($count1));
						}
						$month=date("m");
						$year=date("Y");
						// log::info( 'pdate --->' . DashBoardController::getDateT(0,0,0,15,$month-1,$year));
						// log::info( 'pdate --->' . DashBoardController::getDateT(59,59,23,15,$month,$year));
						// log::info( 'prdate --->' . DashBoardController::getDateT(0,0,0,15,$month-2,$year));
						// log::info( 'prdate --->' .DashBoardController::getDateT(59,59,23,15,$month-1,$year));
						// log::info( 'ndate --->' . DashBoardController::getDateT(0,0,0,15,$month,$year));
						// log::info( 'ndate --->' . DashBoardController::getDateT(59,59,23,15,$month+1,$year));

				$prsentMonthCount=DB::table('Vehicle_details')
				            ->where('fcode', $fcode)->whereBetween('sold_date', array(DashBoardController::getDateT(0,0,0,16,DashBoardController::getprevmonth($month, 1),$year), DashBoardController::getDateT(59,59,23,15,$month,$year)))->count();

	           $prevMonthCount=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->whereBetween('sold_date', array(DashBoardController::getDateT(0,0,0,16,DashBoardController::getprevmonth($month, 2),$year), DashBoardController::getDateT(59,59,23,15,DashBoardController::getprevmonth($month, 1),$year)))->count();
				//$nextMonthCount=DB::table('Vehicle_details')
	            //->where('fcode', $fcode)->whereBetween('sold_date', array(DashBoardController::getDateT(0,0,0,16,$month,$year), DashBoardController::getDateT(59,59,23,15,DashBoardController::getnextmonth($month, 1),$year)))->count();

			}



        $nextMonthCount=[];
		
		$vechile=array();$vechileEx=0;$vechileEx1=0;
		return View::make ( 'vdm.vehicles.dashboard')->with('count',$count)->with('dealerId',$dealerId)->with('vechile',$vechile)->with('temp',$temp)->with('vechileEx',$vechileEx)->with('vechileEx1',$vechileEx1)->with('prsentMonthCount',$prsentMonthCount)->with('nextMonthCount',$nextMonthCount)->with('prevMonthCount',$prevMonthCount);
	}

 public function getDateT($second,$min,$hour,$day,$month,$year)
{
	$xmasThisYear = Carbon::createFromDate($year, $month, $day);			
	$xmasThisYear->hour = $hour;
	$xmasThisYear->minute = $min;
	$xmasThisYear->second = $second;
	
	return $xmasThisYear;
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
