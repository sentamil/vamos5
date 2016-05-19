<?php
use Carbon\Carbon;
class LicenceController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function indexT() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
       
		$tmpOrgList = $redis->smembers('S_Organisations_' . $fcode);
		
		if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				 $tmpOrgList = $redis->smembers('S_Organisations_Dealer_'.$username.'_'.$fcode);
			}
			else if(Session::get('cur')=='admin')
			{
				 $tmpOrgList = $redis->smembers('S_Organisations_Admin_'.$fcode);
			}
			$orgList=null;
			$orgList=array_add($orgList,'','select');
		$orgList=array_add($orgList,'Default','Default');
        foreach ( $tmpOrgList as $org ) {
                $orgList = array_add($orgList,$org,$org);
                
            }
       if(Session::get('cur')=='admin')
		{
			
			$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
			$franchiseDetails=json_decode($franDetails_json,true);
			if(isset($franchiseDetails['availableLincence'])==1)
				$availableLincence=$franchiseDetails['availableLincence'];
			else
				$availableLincence='0';
        
            $numberofdevice=0;$dealerId=null;$userList=null;
		return View::make ( 'vdm.business.deviceAddCopy' )->with ( 'orgList', $orgList )->with ( 'availableLincence', $availableLincence )->with ( 'numberofdevice', $numberofdevice )->with ( 'dealerId', $dealerId )->with ( 'userList', $userList );
		}
		else if(Session::get('cur')=='dealer')
		{
			$key='H_Pre_Onboard_Dealer_'.$username.'_'.$fcode;


				$details=$redis->hgetall($key);
				$devices=null;
				$devicestypes=null;
				$i=0;
				foreach($details as $key => $value)
				{
					$valueData=json_decode($value,true);
					$devices = array_add($devices, $i,$valueData['deviceid']);
					$devicestypes = array_add($devicestypes,$i,$valueData['deviceidtype']);
					Log::info('i=' . $i);
					$i++;
				}
				
				
				Log::info(' inside multi ' );
        
        $dealerId = $redis->smembers('S_Dealers_'. $fcode);
        
      
        
        $orgArr = array();
		        // $orgArr = array_add($orgArr, 'OWN','OWN');
		if($dealerId!=null)
		{
			foreach($dealerId as $org) {
            $orgArr = array_add($orgArr, $org,$org);
			
        }
		$dealerId = $orgArr;
		}
		else{
			$dealerId=null;
			//$orgArr = array_add($orgArr, 'OWN','OWN');
			$dealerId = $orgArr;
		}

        	$redisUserCacheId = 'S_Users_' . $fcode; //dummy installation
		$redisGrpCacheId='S_Users_';
		
		if(Session::get('cur')=='dealer')
		{
			log::info( '------login 1---------- '.Session::get('cur'));
			
			$redisUserCacheId = 'S_Users_Dealer_'.$username.'_'.$fcode;
			
		}
		else if(Session::get('cur')=='admin')
		{
			$redisUserCacheId = 'S_Users_Admin_'.$fcode;
		}
	
		$userList = $redis->smembers ( $redisUserCacheId);
		//get the intersection of users set and groups set
		//$userList=$redis->sinter($redisUserCacheId,'S_Organisations_'.$fcode);
		
		$orgArra = array();
		$orgArra =array_add($orgArra, 'select','select');
      foreach($userList as $org) {
           if(!$redis->sismember ( 'S_Users_Virtual_' . $fcode, $org )==1)
		 {
		 	 $orgArra = array_add($orgArra, $org,$org);
		 }	
			
        }
			$userList=$orgArra;
		
		
			return View::make ( 'vdm.business.business1')->with('devices',$devices)->with('devicestypes',$devicestypes)->with('dealerId',$dealerId)->with('userList',$userList)->with ( 'orgList', $orgList );
		
		}
        
	}
	
	
	public function index() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        //get the Org list
        $month=LicenceController::getMonthT();

		$year=LicenceController::getYear();


        $own=LicenceController::getDealer();
		$Payment_Mode1 =array();
		$Payment_Mode = DB::select('select type from Payment_Mode');
		//log::info( '-------- av  in  ::----------'.count($Payment_Mode));
		foreach($Payment_Mode as  $org1) {
      	$Payment_Mode1 = array_add($Payment_Mode1, $org1->type,$org1->type);
        }
		$Licence1 =array();
		$Licence = DB::select('select type from Licence');
		$Licence1 = array_add($Licence1, 'Both','Both');
		foreach($Licence as  $org) {
      	$Licence1 = array_add($Licence1, $org->type,$org->type);
        }

		 return View::make ( 'vdm.licence.licence' )->with ( 'year', $year )->with ( 'month', $month )->with('Payment_Mode',$Payment_Mode1)->with('Licence',$Licence1)->with('own',$own)->with('monthT',date('m'))->with('yearT',date('Y'))->with('modeT',null)->with('typeT',null)->with('ownT',null);;
	}




public function getDealer()
{
	 $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
	 $own=array();
        $own=array_add($own, 'OWN','OWN');
        $dealerId = $redis->smembers('S_Dealers_'. $fcode);



			
			foreach($dealerId as $or) {
			$own = array_add($own, $or,$or);
			}

			return $own;
}


public function getMonthT()
{
	$month=array();
        $month=array_add($month, 1,'January');
        $month=array_add($month, 2,'February');
        $month=array_add($month, 3,'March');
        $month=array_add($month, 4,'April');
        $month=array_add($month, 5,'May');
        $month=array_add($month, 6,'June');
        $month=array_add($month, 7,'July');
        $month=array_add($month, 8,'August');
        $month=array_add($month, 9,'September');
        $month=array_add($month, 10,'October');
        $month=array_add($month, 11,'November');
        $month=array_add($month, 12,'December');

        return $month;
}

public function getYear()
{
	 $year=array();
        $year=array_add($year, 2015,2015);
        $year=array_add($year, 2016,2016);
        $year=array_add($year, 2017,2017);
        $year=array_add($year, 2018,2018);
        $year=array_add($year, 2019,2019);
        $year=array_add($year, 2020,2020);
        return $year;
}






	public function store() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        //get the Org list


			$month      = Input::get('month');
			$year      = Input::get('year');
			$mode      = Input::get('Payment_Mode');
			$type      = Input::get('Licence');
			$own      = Input::get('own');

			$monthT=$month;
			$yearT =$year ;
			$modeT=$mode;
			$typeT=$type;
			if(Session::get('cur')=='dealer')
	        {
	        	$own=$username;
	        }
			$ownT=$own;
			 
			//DashBoardController::getDateT(59,59,23,15,$month,$year);
			$dateT = new DashBoardController;
		


			//$daylast=new DateTime('last day of this month'); 
			

			$preMonthly=0;$monthly=0;
			$perQuater=0;$quaterly=0;
			$perHalfyearfly=0;$halfyearfly=0;
			$yearfly=0;
			$yearfly2=0;
			$type1=1;
			$type2=2;
			if($type=='Basic' )
			{
				$type1=1;
				$type2=1;
			}
			else if ($type=='Advance') {
				$type1=2;
				$type2=2;
			}


				
				$preMonthly=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $own)->where('payment_mode_id',1)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,16,$month-1,$year), $dateT->getDateT(59,59,23,15,$month,$year)))->count();
	            $monthly=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $own)->where('payment_mode_id',1)->whereIn('licence_id', array($type1, $type2))->where('renewal_date', '<=', $dateT->getDateT(59,59,23,15,$month,$year))->count();

	            if($month==1 || $month==4 || $month==7 || $month==10)
	            {
	            	$perQuater=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $own)->where('payment_mode_id',2)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,16,$month-1,$year), $dateT->getDateT(59,59,23,15,$month+2,$year)))->count();
	            $quaterly=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $own)->where('payment_mode_id',2)->whereIn('licence_id', array($type1, $type2))->where('renewal_date', '<=', $dateT->getDateT(59,59,23,15,$month+2,$year))->count();
	            }
	            if($month==4 || $month==10)
	            {
	            	$perHalfyearfly=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $own)->where('payment_mode_id',3)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,16,$month-1,$year), $dateT->getDateT(59,59,23,15,$month+5,$year)))->count();
	            $halfyearfly=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $own)->where('payment_mode_id',3)->whereIn('licence_id', array($type1, $type2))->where('renewal_date', '<=', $dateT->getDateT(59,59,23,15,$month+5,$year))->count();
	            }
	            $yearfly=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $own)->where('payment_mode_id',4)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,0,$month,$year-1), $dateT->getDateT(0,0,0,0,$month+1,$year-1)))->count();
	            // $yearfly=DB::table('Vehicle_details')
	            // ->where('fcode', $fcode)->where('payment_mode_id',3)->where('sold_date', '<=', $dateT->getDateT(59,59,23,15,$month+12,$year))->count();

	            $yearfly2=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $own)->where('payment_mode_id',5)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,0,$month,$year-2), $dateT->getDateT(0,0,0,0,$month+1,$year-2)))->count();

	             $yearfly3=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $own)->where('payment_mode_id',6)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,0,$month,$year-3), $dateT->getDateT(0,0,0,0,$month+1,$year-3)))->count();

	             $yearfly4=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $own)->where('payment_mode_id',7)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,0,$month,$year-4), $dateT->getDateT(0,0,0,0,$month+1,$year-4)))->count();
	            $yearfly5=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $own)->where('payment_mode_id',8)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,0,$month,$year-5), $dateT->getDateT(0,0,0,0,$month+1,$year-5)))->count();
	            // $yearfly2=DB::table('Vehicle_details')
	            // ->where('fcode', $fcode)->where('payment_mode_id',12)->where('sold_date', '<=', $dateT->getDateT(59,59,23,15,$month+(12*2),$year))->count();

			
			// else
			// {
			// 	$perHalfyearfly=DB::table('Vehicle_details')
	  //           ->where('fcode', $fcode)->where('payment_mode_id',2)->whereBetween('sold_date', array($dateT->getDateT(0,0,0,16,$month-1,$year), $dateT->getDateT(59,59,23,15,$month+2,$year)))->count();
	  //           $quaterly=DB::table('Vehicle_details')
	  //           ->where('fcode', $fcode)->where('payment_mode_id',2)->where('sold_date', '<=', $dateT->getDateT(59,59,23,15,$month+2,$year))->count();
			// }

log::info($monthly.'----'.$preMonthly.'----'.$mode.'-----'.$dateT->getDateT(59,59,23,15,$month,$year-5).'-----'.$dateT->getDateT(0,0,0,16,$month-1,$year-5));
log::info($perQuater.'----'.$perQuater.'----'.$mode.'-----');
       $month=LicenceController::getMonthT();

		$year=LicenceController::getYear();


        $own=LicenceController::getDealer();
		$Payment_Mode1 =array();
		$Payment_Mode = DB::select('select type from Payment_Mode');
		//log::info( '-------- av  in  ::----------'.count($Payment_Mode));
		foreach($Payment_Mode as  $org1) {
      	$Payment_Mode1 = array_add($Payment_Mode1, $org1->type,$org1->type);
        }
		$Licence1 =array();
		$Licence = DB::select('select type from Licence');
		$Licence1 = array_add($Licence1, 'Both','Both');
		foreach($Licence as  $org) {
      	$Licence1 = array_add($Licence1, $org->type,$org->type);
        }

log::info($perQuater.'----'.$perQuater.'----'.$mode.'-----');
		 return View::make ( 'vdm.licence.licenceCopy' )->with ( 'year', $year )->with ( 'month', $month )->with('Payment_Mode',$Payment_Mode1)->with('Licence',$Licence1)->with('own',$own)->with('preMonthly',$preMonthly)->with('monthly',$monthly)->with('perQuater',$perQuater)->with('quaterly',$quaterly)->with('perHalfyearfly',$perHalfyearfly)->with('halfyearfly',$halfyearfly)->with('yearfly',$yearfly)->with('yearfly2',$yearfly2)->with('yearfly3',$yearfly3)->with('yearfly4',$yearfly4)->with('yearfly5',$yearfly5)->with('monthT',$monthT)->with('yearT',$yearT)->with('modeT',$modeT)->with('typeT',$typeT)->with('ownT',$ownT);

	}



	public function ViewDevices($value)
	{
		log::info($value.'---inside ViewDevices---');

		$values=explode(";",$value);
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );

		$dateT = new DashBoardController;
        $type1=1;
			$type2=2;
			if($values[3]=='Basic' )
			{
				$type1=1;
				$type2=1;
			}
			else if ($values[3]=='Advance') {
				$type1=2;
				$type2=2;
			}
		$details=null;


	

			if($values[2]==1)
			{
				 $details=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $values[4])->where('payment_mode_id',1)->whereIn('licence_id', array($type1, $type2))->where('renewal_date', '<=', $dateT->getDateT(59,59,23,15,$values[0],$values[1]))->get();
			}
			else if($values[2]==2 && ($values[0]==1 || $values[0]==4 || $values[0]==7 || $values[0]==10))
			{
				$details=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $values[4])->where('payment_mode_id',2)->whereIn('licence_id', array($type1, $type2))->where('renewal_date', '<=', $dateT->getDateT(59,59,23,15,$values[0]+2,$values[1]))->get();;
			}
			else if($values[2]==3 && ($values[0]==4 || $values[0]==10))
			{
				 $details=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $values[4])->where('payment_mode_id',3)->whereIn('licence_id', array($type1, $type2))->where('renewal_date', '<=', $dateT->getDateT(59,59,23,15,$values[0]+5,$values[1]))->get();
			}
			else if($values[2]==4)
			{
				$details=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $values[4])->where('payment_mode_id',4)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,0,$values[0],$values[1]-1), $dateT->getDateT(0,0,0,0,$values[0]+1,$values[1]-1)))->get();
			}
			else if($values[2]==5)
			{
				$details=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $values[4])->where('payment_mode_id',5)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,0,$values[0],$values[1]-2), $dateT->getDateT(0,0,0,0,$values[0]+1,$values[1]-2)))->get();
			}else if($values[2]==6)
			{
				$details=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $values[4])->where('payment_mode_id',6)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,0,$values[0],$values[1]-3), $dateT->getDateT(0,0,0,0,$values[0]+1,$values[1]-3)))->get();
			}
			else if($values[2]==7)
			{
				$details=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $values[4])->where('payment_mode_id',7)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,0,$values[0],$values[1]-4), $dateT->getDateT(0,0,0,0,$values[0]+1,$values[1]-4)))->get();
			}
			else if($values[2]==8)
			{
				$details=DB::table('Vehicle_details')
	            ->where('fcode', $fcode)->where('belongs_to', $values[4])->where('payment_mode_id',8)->whereIn('licence_id', array($type1, $type2))->whereBetween('renewal_date', array($dateT->getDateT(0,0,0,0,$values[0],$values[1]-5), $dateT->getDateT(0,0,0,0,$values[0]+1,$values[1]-5)))->get();
			}


		log::info($value.'---success ViewDevices---'.count($details));
			$lastdue=strtotime($dateT->getDateT(59,59,23,0,date('m')+2,date('Y')));

			$predue=strtotime($dateT->getDateT(59,59,23,0,$values[0],$values[1]));
			$lastdue=$lastdue-$predue;

		$i=0;	// }
		if(($values[2]==8 || $values[2]==7 || $values[2]==6 || $values[2]==5 || $values[2]==4 )&& ($lastdue)>0)
		{
			$i=1;

			log::info($lastdue.'----daylast--------'.$predue.' '.$values[0].' '.$values[1]);
		}
		$value=(string)$value;
			return View::make ( 'vdm.licence.deviceView' )->with ( 'details', $details)->with('i',$i)->with('valueT',$value);

	}
	



public function update()
{
		$value=Input::get ( 'tempVal' );
		$vehicles=Input::get ( 'vehicleList' );
		log::info('---in ViewDevices---'.$value);
		$values=explode(";",$value);
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        $temp="'";
        $i=0;
        if(count($vehicles)>0){
        	log::info('---success ViewDevices---'.$value);
        	$payee=4;
		foreach ($vehicles as $key => $val) {
		
			$valT=explode(';', $val);

			if($i==0)
			{
				$payee=$valT[1];
				$temp= $temp.$valT[0]."'";
			}
			else
			{
				$temp= $temp.",'".$valT[0]."'";
			}
			
			$i++;
		}
$Pmode=4;
		switch ($payee) {
			case '4':
				$Pmode='"+1 year"';
				break;
			case '5':
				$Pmode='"+2 year"';
				break;
			case '6':
				$Pmode='"+3 year"';
				break;
			case '7':
				$Pmode='"+4 year"';
				break;
			case '7':
				$Pmode='"+5 year"';
				break;
			
			default:
				# code...
				break;
		}

log::info('---success ViewDevices---'.$temp);
$conn=DB::connection()->getPdo();

$sql = 'update Vehicle_details set renewal_date=(SELECT date (Vehicle_details1.renewal_date,'.$Pmode.') as date FROM Vehicle_details as Vehicle_details1  where vehicle_id=Vehicle_details.vehicle_id) where vehicle_id in ('.$temp.');
';


log::info('---success ViewDevices---'.$sql);
$stmt = $conn->prepare($sql);


$stmt->execute();

    // echo a message to say the UPDATE succeeded

log::info('Record updated successfully'.$stmt->rowCount());
//     echo 

// if ($conn->query($sql) === TRUE) {
//     log::info('Record updated successfully');
// } else {
//     log::info('Error updating record: '. $conn->error);
// }
	// $details=DB::table('Vehicle_details')
	//             ->where('fcode', $fcode)->whereIn('vehicle_id',$temp)->update(['renewal_date' => Carbon::now()]);


	             // update Vehicle_details set renewal_date=(SELECT date (Vehicle_details1.renewal_date,'+1 day') as date FROM Vehicle_details as Vehicle_details1  where vehicle_id=Vehicle_details.vehicle_id) where vehicle_id in ('gpsvts_7150','gpsvts_gi99','gpsvts_gi98');


// $details=DB::table('Vehicle_details')
// 	            ->where('fcode', $fcode)->whereIn('vehicle_id',$temp)->update(['renewal_date=(SELECT date (Vehicle_details1.renewal_date,''+1 day'') as date FROM Vehicle_details as Vehicle_details1  where vehicle_id='$temp'')]);
// $details=DB::table('Vehicle_details')
// 	            ->where('fcode', $fcode)->whereIn('vehicle_id',$temp)->update(['renewal_date'=>'SELECT date (Vehicle_details1.renewal_date,+1 day) as date FROM Vehicle_details as Vehicle_details1  where vehicle_id=Vehicle_details.vehicle_id']);

// $val=DB::table('Vehicle_details as Vehicle_details1')
// ->select('date (Vehicle_details1.renewal_date,+1 day)')->get();
// $val = DB::table('Vehicle_details')->where('DATE_ADD(renewal_date, INTERVAL 2 day)')->get();

// dd($val);
// log::info('---success first1---'.count($val));
// foreach ($val as $key => $v) {
// 	log::info('---success first2---'.$key);
// 	log::info('---success first---'.$v->day);
// }


// 	            $details=DB::table('Vehicle_details')
// 	            ->where('fcode', $fcode)->whereIn('vehicle_id',$temp)->update(['renewal_date'=>
// DB::table('Vehicle_details as Vehicle_details1')
// ->select('date (Vehicle_details1.renewal_date,+1 day) as date')->get()->date]);

	            	// 'SELECT date (Vehicle_details1.renewal_date,+1 day) as date FROM Vehicle_details as Vehicle_details1  where vehicle_id=Vehicle_details.vehicle_id']);

		// SELECT date (Vehicle_details1.renewal_date,'+1 day') as date FROM Vehicle_details as Vehicle_details1 where vehicle_id=


	            // update Vehicle_details set renewal_date=(SELECT date (Vehicle_details1.renewal_date,'+1 day') as date FROM Vehicle_details as Vehicle_details1  where vehicle_id='gpsvts_7150') where vehicle_id='gpsvts_7150';
	            return Redirect::to ( 'Licence' )->withErrors ( 'Successfully renewaled' );
}
else
{
	log::info('---fail ViewDevices---'.$value);
	// Redirect::back()->withErrors ( 'Nothing renewaled' );

	//  Session::flash ( 'message', 'VehicleId' . $vehicleId . 'already exist. Please choose another one' );
 //        log::info( '------3---------- ::');
        return Redirect::back()->withErrors ( 'Nothing renewaled' );
}
		
		
}




	}

