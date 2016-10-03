<?php
class VdmFranchiseController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		log::info( 'User name  :' . $username);
		
		$redis = Redis::connection ();
		
		$fcodeArray = $redis->smembers('S_Franchises');
		
		$fnameArray=null;
		
		foreach ( $fcodeArray as $key => $value ) {
			$details = $redis->hget('H_Franchise',$value);
			$details_json=json_decode($details,true);
			$fnameArray=array_add($fnameArray, $value, $details_json['fname']);
		}
		//dd($fnameArray);
		
		
		return View::make ( 'vdm.franchise.index', array (
				'fcodeArray' => $fcodeArray 
		) )->with ( 'fnameArray', $fnameArray );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$smsP=VdmFranchiseController::smsP();
		$dbIp=VdmFranchiseController::dbIp();
		log::info( 'VdmFranchiseController  :' . count($dbIp));
		return View::make ( 'vdm.franchise.create' )->with('smsP',$smsP)->with('dbIp',$dbIp)->with('timeZoneC',VdmFranchiseController::timeZoneC())->with('backType',VdmFranchiseController::backTypeC());
	}


public static function dbIp()
{
		$dbIp=array();
		$dbIp[0]='188.166.237.200';
		$dbIp[1]='188.166.244.126';
		$dbIp[2]='128.199.94.62';
	return $dbIp;
}


public static function backTypeC()
{
		$backType=array();
		$backType=array_add($backType, 'sqlite','Sqlite');
		$backType=array_add($backType, 'mysql','Mysql');
	return $backType;
}
	
public static function smsP()
{
	if (! Auth::check ()) {
		return Redirect::to ( 'login' );
	}
	$redis = Redis::connection ();
	$smsProvider = $redis->lrange('L_SMS_Provider', 0, -1);
	$smsP=array();
	foreach ($smsProvider as $sms) {
    	$smsP=array_add($smsP, $sms, $sms);
	}
	return $smsP;
}
public static function timeZoneC()
{
		$timeZoneC=array();
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+12','Etc/GMT+12');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+11','Etc/GMT+11');
		$timeZoneC=array_add($timeZoneC, 'MIT','MIT');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Apia','Pacific/Apia');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Midway','Pacific/Midway');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Niue','Pacific/Niue');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Pago_Pago','Pacific/Pago_Pago');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Samoa','Pacific/Samoa');
		$timeZoneC=array_add($timeZoneC, 'US/Samoa','US/Samoa');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Apia','Pacific/Apia');
		$timeZoneC=array_add($timeZoneC, 'America/Adak','America/Adak');
		$timeZoneC=array_add($timeZoneC, 'America/Atka','America/Atka');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+10','Etc/GMT+10');
		$timeZoneC=array_add($timeZoneC, 'HST','HST');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Fakaofo','Pacific/Fakaofo');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Honolulu','Pacific/Honolulu');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Johnston','Pacific/Johnston');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Rarotonga','Pacific/Rarotonga');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Tahiti','Pacific/Tahiti');
		$timeZoneC=array_add($timeZoneC, 'SystemV/HST10','SystemV/HST10');
		$timeZoneC=array_add($timeZoneC, 'US/Aleutian','US/Aleutian');
		$timeZoneC=array_add($timeZoneC, 'US/Hawaii','US/Hawaii');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Marquesas','Pacific/Marquesas');
		$timeZoneC=array_add($timeZoneC, 'AST','AST');
		$timeZoneC=array_add($timeZoneC, 'America/Anchorage','America/Anchorage');
		$timeZoneC=array_add($timeZoneC, 'America/Juneau','America/Juneau');
		$timeZoneC=array_add($timeZoneC, 'America/Nome','America/Nome');
		$timeZoneC=array_add($timeZoneC, 'America/Yakutat','America/Yakutat');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+9','Etc/GMT+9');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Gambier','Pacific/Gambier');
		$timeZoneC=array_add($timeZoneC, 'SystemV/YST9','SystemV/YST9');
		$timeZoneC=array_add($timeZoneC, 'SystemV/YST9YDT','SystemV/YST9YDT');
		$timeZoneC=array_add($timeZoneC, 'US/Alaska','US/Alaska');
		$timeZoneC=array_add($timeZoneC, 'America/Dawson','America/Dawson');
		$timeZoneC=array_add($timeZoneC, 'America/Ensenada','America/Ensenada');
		$timeZoneC=array_add($timeZoneC, 'America/Los_Angeles','America/Los_Angeles');
		$timeZoneC=array_add($timeZoneC, 'America/Tijuana','America/Tijuana');
		$timeZoneC=array_add($timeZoneC, 'America/Vancouver','America/Vancouver');
		$timeZoneC=array_add($timeZoneC, 'America/Whitehorse','America/Whitehorse');
		$timeZoneC=array_add($timeZoneC, 'Canada/Pacific','Canada/Pacific');
		$timeZoneC=array_add($timeZoneC, 'Canada/Yukon','Canada/Yukon');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+8','Etc/GMT+8');
		$timeZoneC=array_add($timeZoneC, 'Mexico/BajaNorte','Mexico/BajaNorte');
		$timeZoneC=array_add($timeZoneC, 'PST','PST');
		$timeZoneC=array_add($timeZoneC, 'PST8PDT','PST8PDT');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Pitcairn','Pacific/Pitcairn');
		$timeZoneC=array_add($timeZoneC, 'SystemV/PST8','SystemV/PST8');
		$timeZoneC=array_add($timeZoneC, 'SystemV/PST8PDT','SystemV/PST8PDT');
		$timeZoneC=array_add($timeZoneC, 'US/Pacific','US/Pacific');
		$timeZoneC=array_add($timeZoneC, 'US/Pacific-New','US/Pacific-New');
		$timeZoneC=array_add($timeZoneC, 'America/Boise','America/Boise');
		$timeZoneC=array_add($timeZoneC, 'America/Cambridge_Bay','America/Cambridge_Bay');
		$timeZoneC=array_add($timeZoneC, 'America/Chihuahua','America/Chihuahua');
		$timeZoneC=array_add($timeZoneC, 'America/Dawson_Creek','America/Dawson_Creek');
		$timeZoneC=array_add($timeZoneC, 'America/Denver','America/Denver');
		$timeZoneC=array_add($timeZoneC, 'America/Edmonton','America/Edmonton');
		$timeZoneC=array_add($timeZoneC, 'America/Hermosillo','America/Hermosillo');
		$timeZoneC=array_add($timeZoneC, 'America/Inuvik','America/Inuvik');
		$timeZoneC=array_add($timeZoneC, 'America/Mazatlan','America/Mazatlan');
		$timeZoneC=array_add($timeZoneC, 'America/Phoenix','America/Phoenix');
		$timeZoneC=array_add($timeZoneC, 'America/Shiprock','America/Shiprock');
		$timeZoneC=array_add($timeZoneC, 'America/Yellowknife','America/Yellowknife');
		$timeZoneC=array_add($timeZoneC, 'Canada/Mountain','Canada/Mountain');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+7','Etc/GMT+7');
		$timeZoneC=array_add($timeZoneC, 'MST','MST');
		$timeZoneC=array_add($timeZoneC, 'MST7MDT','MST7MDT');
		$timeZoneC=array_add($timeZoneC, 'Mexico/BajaSur','Mexico/BajaSur');
		$timeZoneC=array_add($timeZoneC, 'Navajo','Navajo');
		$timeZoneC=array_add($timeZoneC, 'PNT','PNT');
		$timeZoneC=array_add($timeZoneC, 'SystemV/MST7','SystemV/MST7');
		$timeZoneC=array_add($timeZoneC, 'SystemV/MST7MDT','SystemV/MST7MDT');
		$timeZoneC=array_add($timeZoneC, 'US/Arizona','US/Arizona');
		$timeZoneC=array_add($timeZoneC, 'US/Mountain','US/Mountain');
		$timeZoneC=array_add($timeZoneC, 'America/Belize','America/Belize');
		$timeZoneC=array_add($timeZoneC, 'America/Cancun','America/Cancun');
		$timeZoneC=array_add($timeZoneC, 'America/Chicago','America/Chicago');
		$timeZoneC=array_add($timeZoneC, 'America/Costa_Rica','America/Costa_Rica');
		$timeZoneC=array_add($timeZoneC, 'America/El_Salvador','America/El_Salvador');
		$timeZoneC=array_add($timeZoneC, 'America/Guatemala','America/Guatemala');
		$timeZoneC=array_add($timeZoneC, 'America/Managua','America/Managua');
		$timeZoneC=array_add($timeZoneC, 'America/Menominee','America/Menominee');
		$timeZoneC=array_add($timeZoneC, 'America/Merida','America/Merida');
		$timeZoneC=array_add($timeZoneC, 'America/Mexico_City','America/Mexico_City');
		$timeZoneC=array_add($timeZoneC, 'America/Monterrey','America/Monterrey');
		$timeZoneC=array_add($timeZoneC, 'America/North_Dakota/Center','America/North_Dakota/Center');
		$timeZoneC=array_add($timeZoneC, 'America/Rainy_River','America/Rainy_River');
		$timeZoneC=array_add($timeZoneC, 'America/Rankin_Inlet','America/Rankin_Inlet');
		$timeZoneC=array_add($timeZoneC, 'America/Regina','America/Regina');
		$timeZoneC=array_add($timeZoneC, 'America/Swift_Current','America/Swift_Current');
		$timeZoneC=array_add($timeZoneC, 'America/Tegucigalpa','America/Tegucigalpa');
		$timeZoneC=array_add($timeZoneC, 'America/Winnipeg','America/Winnipeg');
		$timeZoneC=array_add($timeZoneC, 'CST','CST');
		$timeZoneC=array_add($timeZoneC, 'CST6CDT','CST6CDT');
		$timeZoneC=array_add($timeZoneC, 'Canada/Central','Canada/Central');
		$timeZoneC=array_add($timeZoneC, 'Canada/East-Saskatchewan','Canada/East-Saskatchewan');
		$timeZoneC=array_add($timeZoneC, 'Canada/Saskatchewan','Canada/Saskatchewan');
		$timeZoneC=array_add($timeZoneC, 'Chile/EasterIsland','Chile/EasterIsland');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+6','Etc/GMT+6');
		$timeZoneC=array_add($timeZoneC, 'Mexico/General','Mexico/General');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Easter','Pacific/Easter');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Galapagos','Pacific/Galapagos');
		$timeZoneC=array_add($timeZoneC, 'SystemV/CST6','SystemV/CST6');
		$timeZoneC=array_add($timeZoneC, 'SystemV/CST6CDT','SystemV/CST6CDT');
		$timeZoneC=array_add($timeZoneC, 'US/Central','US/Central');
		$timeZoneC=array_add($timeZoneC, 'America/Bogota','America/Bogota');
		$timeZoneC=array_add($timeZoneC, 'America/Cayman','America/Cayman');
		$timeZoneC=array_add($timeZoneC, 'America/Detroit','America/Detroit');
		$timeZoneC=array_add($timeZoneC, 'America/Eirunepe','America/Eirunepe');
		$timeZoneC=array_add($timeZoneC, 'America/Fort_Wayne','America/Fort_Wayne');
		$timeZoneC=array_add($timeZoneC, 'America/Grand_Turk','America/Grand_Turk');
		$timeZoneC=array_add($timeZoneC, 'America/Guayaquil','America/Guayaquil');
		$timeZoneC=array_add($timeZoneC, 'America/Havana','America/Havana');
		$timeZoneC=array_add($timeZoneC, 'America/Indiana/Indianapolis','America/Indiana/Indianapolis');
		$timeZoneC=array_add($timeZoneC, 'America/Indiana/Knox','America/Indiana/Knox');
		$timeZoneC=array_add($timeZoneC, 'America/Indiana/Marengo','America/Indiana/Marengo');
		$timeZoneC=array_add($timeZoneC, 'America/Indiana/Vevay','America/Indiana/Vevay');
		$timeZoneC=array_add($timeZoneC, 'America/Indianapolis','America/Indianapolis');
		$timeZoneC=array_add($timeZoneC, 'America/Iqaluit','America/Iqaluit');
		$timeZoneC=array_add($timeZoneC, 'America/Jamaica','America/Jamaica');
		$timeZoneC=array_add($timeZoneC, 'America/Kentucky/Louisville','America/Kentucky/Louisville');
		$timeZoneC=array_add($timeZoneC, 'America/Kentucky/Monticello','America/Kentucky/Monticello');
		$timeZoneC=array_add($timeZoneC, 'America/Knox_IN','America/Knox_IN');
		$timeZoneC=array_add($timeZoneC, 'America/Lima','America/Lima');
		$timeZoneC=array_add($timeZoneC, 'America/Louisville','America/Louisville');
		$timeZoneC=array_add($timeZoneC, 'America/Montreal','America/Montreal');
		$timeZoneC=array_add($timeZoneC, 'America/Nassau','America/Nassau');
		$timeZoneC=array_add($timeZoneC, 'America/New_York','America/New_York');
		$timeZoneC=array_add($timeZoneC, 'America/Nipigon','America/Nipigon');
		$timeZoneC=array_add($timeZoneC, 'America/Panama','America/Panama');
		$timeZoneC=array_add($timeZoneC, 'America/Pangnirtung','America/Pangnirtung');
		$timeZoneC=array_add($timeZoneC, 'America/Port-au-Prince','America/Port-au-Prince');
		$timeZoneC=array_add($timeZoneC, 'America/Porto_Acre','America/Porto_Acre');
		$timeZoneC=array_add($timeZoneC, 'America/Rio_Branco','America/Rio_Branco');
		$timeZoneC=array_add($timeZoneC, 'America/Thunder_Bay','America/Thunder_Bay');
		$timeZoneC=array_add($timeZoneC, 'America/Toronto','America/Toronto');
		$timeZoneC=array_add($timeZoneC, 'Brazil/Acre','Brazil/Acre');
		$timeZoneC=array_add($timeZoneC, 'Canada/Eastern','Canada/Eastern');
		$timeZoneC=array_add($timeZoneC, 'Cuba','Cuba');
		$timeZoneC=array_add($timeZoneC, 'EST','EST');
		$timeZoneC=array_add($timeZoneC, 'EST5EDT','EST5EDT');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+5','Etc/GMT+5');
		$timeZoneC=array_add($timeZoneC, 'IET','IET');
		$timeZoneC=array_add($timeZoneC, 'Jamaica','Jamaica');
		$timeZoneC=array_add($timeZoneC, 'SystemV/EST5','SystemV/EST5');
		$timeZoneC=array_add($timeZoneC, 'SystemV/EST5EDT','SystemV/EST5EDT');
		$timeZoneC=array_add($timeZoneC, 'US/East-Indiana','US/East-Indiana');
		$timeZoneC=array_add($timeZoneC, 'US/Eastern','US/Eastern');
		$timeZoneC=array_add($timeZoneC, 'US/Indiana-Starke','US/Indiana-Starke');
		$timeZoneC=array_add($timeZoneC, 'US/Michigan','US/Michigan');
		$timeZoneC=array_add($timeZoneC, 'America/Anguilla','America/Anguilla');
		$timeZoneC=array_add($timeZoneC, 'America/Antigua','America/Antigua');
		$timeZoneC=array_add($timeZoneC, 'America/Aruba','America/Aruba');
		$timeZoneC=array_add($timeZoneC, 'America/Asuncion','America/Asuncion');
		$timeZoneC=array_add($timeZoneC, 'America/Barbados','America/Barbados');
		$timeZoneC=array_add($timeZoneC, 'America/Boa_Vista','America/Boa_Vista');
		$timeZoneC=array_add($timeZoneC, 'America/Campo_Grande','America/Campo_Grande');
		$timeZoneC=array_add($timeZoneC, 'America/Caracas','America/Caracas');
		$timeZoneC=array_add($timeZoneC, 'America/Cuiaba','America/Cuiaba');
		$timeZoneC=array_add($timeZoneC, 'America/Curacao','America/Curacao');
		$timeZoneC=array_add($timeZoneC, 'America/Dominica','America/Dominica');
		$timeZoneC=array_add($timeZoneC, 'America/Glace_Bay','America/Glace_Bay');
		$timeZoneC=array_add($timeZoneC, 'America/Goose_Bay','America/Goose_Bay');
		$timeZoneC=array_add($timeZoneC, 'America/Grenada','America/Grenada');
		$timeZoneC=array_add($timeZoneC, 'America/Guadeloupe','America/Guadeloupe');
		$timeZoneC=array_add($timeZoneC, 'America/Guyana','America/Guyana');
		$timeZoneC=array_add($timeZoneC, 'America/Halifax','America/Halifax');
		$timeZoneC=array_add($timeZoneC, 'America/La_Paz','America/La_Paz');
		$timeZoneC=array_add($timeZoneC, 'America/Manaus','America/Manaus');
		$timeZoneC=array_add($timeZoneC, 'America/Martinique','America/Martinique');
		$timeZoneC=array_add($timeZoneC, 'America/Montserrat','America/Montserrat');
		$timeZoneC=array_add($timeZoneC, 'America/Port_of_Spain','America/Port_of_Spain');
		$timeZoneC=array_add($timeZoneC, 'America/Porto_Velho','America/Porto_Velho');
		$timeZoneC=array_add($timeZoneC, 'America/Puerto_Rico','America/Puerto_Rico');
		$timeZoneC=array_add($timeZoneC, 'America/Santiago','America/Santiago');
		$timeZoneC=array_add($timeZoneC, 'America/Santo_Domingo','America/Santo_Domingo');
		$timeZoneC=array_add($timeZoneC, 'America/St_Kitts','America/St_Kitts');
		$timeZoneC=array_add($timeZoneC, 'America/St_Lucia','America/St_Lucia');
		$timeZoneC=array_add($timeZoneC, 'America/St_Thomas','America/St_Thomas');
		$timeZoneC=array_add($timeZoneC, 'America/St_Vincent','America/St_Vincent');
		$timeZoneC=array_add($timeZoneC, 'America/Thule','America/Thule');
		$timeZoneC=array_add($timeZoneC, 'America/Tortola','America/Tortola');
		$timeZoneC=array_add($timeZoneC, 'America/Virgin','America/Virgin');
		$timeZoneC=array_add($timeZoneC, 'Antarctica/Palmer','Antarctica/Palmer');
		$timeZoneC=array_add($timeZoneC, 'Atlantic/Bermuda','Atlantic/Bermuda');
		$timeZoneC=array_add($timeZoneC, 'Atlantic/Stanley','Atlantic/Stanley');
		$timeZoneC=array_add($timeZoneC, 'Brazil/West','Brazil/West');
		$timeZoneC=array_add($timeZoneC, 'Canada/Atlantic','Canada/Atlantic');
		$timeZoneC=array_add($timeZoneC, 'Chile/Continental','Chile/Continental');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+4','Etc/GMT+4');
		$timeZoneC=array_add($timeZoneC, 'PRT','PRT');
		$timeZoneC=array_add($timeZoneC, 'SystemV/AST4','SystemV/AST4');
		$timeZoneC=array_add($timeZoneC, 'SystemV/AST4ADT','SystemV/AST4ADT');
		$timeZoneC=array_add($timeZoneC, 'America/St_Johns','America/St_Johns');
		$timeZoneC=array_add($timeZoneC, 'CNT','CNT');
		$timeZoneC=array_add($timeZoneC, 'Canada/Newfoundland','Canada/Newfoundland');
		$timeZoneC=array_add($timeZoneC, 'AGT','AGT');
		$timeZoneC=array_add($timeZoneC, 'America/Araguaina','America/Araguaina');
		$timeZoneC=array_add($timeZoneC, 'America/Bahia','America/Bahia');
		$timeZoneC=array_add($timeZoneC, 'America/Belem','America/Belem');
		$timeZoneC=array_add($timeZoneC, 'America/Buenos_Aires','America/Buenos_Aires');
		$timeZoneC=array_add($timeZoneC, 'America/Catamarca','America/Catamarca');
		$timeZoneC=array_add($timeZoneC, 'America/Cayenne','America/Cayenne');
		$timeZoneC=array_add($timeZoneC, 'America/Cordoba','America/Cordoba');
		$timeZoneC=array_add($timeZoneC, 'America/Fortaleza','America/Fortaleza');
		$timeZoneC=array_add($timeZoneC, 'America/Godthab','America/Godthab');
		$timeZoneC=array_add($timeZoneC, 'America/Jujuy','America/Jujuy');
		$timeZoneC=array_add($timeZoneC, 'America/Maceio','America/Maceio');
		$timeZoneC=array_add($timeZoneC, 'America/Mendoza','America/Mendoza');
		$timeZoneC=array_add($timeZoneC, 'America/Miquelon','America/Miquelon');
		$timeZoneC=array_add($timeZoneC, 'America/Montevideo','America/Montevideo');
		$timeZoneC=array_add($timeZoneC, 'America/Paramaribo','America/Paramaribo');
		$timeZoneC=array_add($timeZoneC, 'America/Recife','America/Recife');
		$timeZoneC=array_add($timeZoneC, 'America/Rosario','America/Rosario');
		$timeZoneC=array_add($timeZoneC, 'America/Sao_Paulo','America/Sao_Paulo');
		$timeZoneC=array_add($timeZoneC, 'Antarctica/Rothera','Antarctica/Rothera');
		$timeZoneC=array_add($timeZoneC, 'BET','BET');
		$timeZoneC=array_add($timeZoneC, 'Brazil/East','Brazil/East');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+3','Etc/GMT+3');
		$timeZoneC=array_add($timeZoneC, 'America/Noronha','America/Noronha');
		$timeZoneC=array_add($timeZoneC, 'Atlantic/South_Georgia','Atlantic/South_Georgia');
		$timeZoneC=array_add($timeZoneC, 'Brazil/DeNoronha','Brazil/DeNoronha');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+2','Etc/GMT+2');
		$timeZoneC=array_add($timeZoneC, 'America/Scoresbysund','America/Scoresbysund');
		$timeZoneC=array_add($timeZoneC, 'Atlantic/Azores','Atlantic/Azores');
		$timeZoneC=array_add($timeZoneC, 'Atlantic/Cape_Verde','Atlantic/Cape_Verde');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+1','Etc/GMT+1');
		$timeZoneC=array_add($timeZoneC, 'Africa/Abidjan','Africa/Abidjan');
		$timeZoneC=array_add($timeZoneC, 'Africa/Accra','Africa/Accra');
		$timeZoneC=array_add($timeZoneC, 'Africa/Bamako','Africa/Bamako');
		$timeZoneC=array_add($timeZoneC, 'Africa/Banjul','Africa/Banjul');
		$timeZoneC=array_add($timeZoneC, 'Africa/Bissau','Africa/Bissau');
		$timeZoneC=array_add($timeZoneC, 'Africa/Casablanca','Africa/Casablanca');
		$timeZoneC=array_add($timeZoneC, 'Africa/Conakry','Africa/Conakry');
		$timeZoneC=array_add($timeZoneC, 'Africa/Dakar','Africa/Dakar');
		$timeZoneC=array_add($timeZoneC, 'Africa/El_Aaiun','Africa/El_Aaiun');
		$timeZoneC=array_add($timeZoneC, 'Africa/Freetown','Africa/Freetown');
		$timeZoneC=array_add($timeZoneC, 'Africa/Lome','Africa/Lome');
		$timeZoneC=array_add($timeZoneC, 'Africa/Monrovia','Africa/Monrovia');
		$timeZoneC=array_add($timeZoneC, 'Africa/Nouakchott','Africa/Nouakchott');
		$timeZoneC=array_add($timeZoneC, 'Africa/Ouagadougou','Africa/Ouagadougou');


		$timeZoneC=array_add($timeZoneC, 'Africa/Sao_Tome','Africa/Sao_Tome');
		$timeZoneC=array_add($timeZoneC, 'Africa/Timbuktu','Africa/Timbuktu');
		$timeZoneC=array_add($timeZoneC, 'America/Danmarkshavn','America/Danmarkshavn');
		$timeZoneC=array_add($timeZoneC, 'Atlantic/Canary','Atlantic/Canary');
		$timeZoneC=array_add($timeZoneC, 'Atlantic/Faeroe','Atlantic/Faeroe');
		$timeZoneC=array_add($timeZoneC, 'Atlantic/Madeira','Atlantic/Madeira');
		$timeZoneC=array_add($timeZoneC, 'Atlantic/Reykjavik','Atlantic/Reykjavik');
		$timeZoneC=array_add($timeZoneC, 'Atlantic/St_Helena','Atlantic/St_Helena');
		$timeZoneC=array_add($timeZoneC, 'Eire','Eire');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT','Etc/GMT');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT+0','Etc/GMT+0');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-0','Etc/GMT-0');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT0','Etc/GMT0');
		$timeZoneC=array_add($timeZoneC, 'Etc/Greenwich','Etc/Greenwich');
		$timeZoneC=array_add($timeZoneC, 'Etc/UCT','Etc/UCT');
		$timeZoneC=array_add($timeZoneC, 'Etc/UTC','Etc/UTC');
		$timeZoneC=array_add($timeZoneC, 'Etc/Universal','Etc/Universal');
		$timeZoneC=array_add($timeZoneC, 'Etc/Zulu','Etc/Zulu');
		$timeZoneC=array_add($timeZoneC, 'Europe/Belfast','Europe/Belfast');
		$timeZoneC=array_add($timeZoneC, 'Europe/Dublin','Europe/Dublin');
		$timeZoneC=array_add($timeZoneC, 'Europe/Lisbon','Europe/Lisbon');
		$timeZoneC=array_add($timeZoneC, 'Europe/London','Europe/London');
		$timeZoneC=array_add($timeZoneC, 'GB','GB');
		$timeZoneC=array_add($timeZoneC, 'GB-Eire','GB-Eire');
		$timeZoneC=array_add($timeZoneC, 'GMT','GMT');
		$timeZoneC=array_add($timeZoneC, 'GMT0','GMT0');
		$timeZoneC=array_add($timeZoneC, 'Greenwich','Greenwich');
		$timeZoneC=array_add($timeZoneC, 'Iceland','Iceland');
		$timeZoneC=array_add($timeZoneC, 'Portugal','Portugal');
		$timeZoneC=array_add($timeZoneC, 'UCT','UCT');
		$timeZoneC=array_add($timeZoneC, 'UTC','UTC');
		$timeZoneC=array_add($timeZoneC, 'Universal','Universal');
		$timeZoneC=array_add($timeZoneC, 'WET','WET');
		$timeZoneC=array_add($timeZoneC, 'Zulu','Zulu');
		$timeZoneC=array_add($timeZoneC, 'Africa/Algiers','Africa/Algiers');
		$timeZoneC=array_add($timeZoneC, 'Africa/Bangui','Africa/Bangui');
		$timeZoneC=array_add($timeZoneC, 'Africa/Brazzaville','Africa/Brazzaville');
		$timeZoneC=array_add($timeZoneC, 'Africa/Ceuta','Africa/Ceuta');
		$timeZoneC=array_add($timeZoneC, 'Africa/Douala','Africa/Douala');
		$timeZoneC=array_add($timeZoneC, 'Africa/Kinshasa','Africa/Kinshasa');
		$timeZoneC=array_add($timeZoneC, 'Africa/Lagos','Africa/Lagos');
		$timeZoneC=array_add($timeZoneC, 'Africa/Libreville','Africa/Libreville');
		$timeZoneC=array_add($timeZoneC, 'Africa/Luanda','Africa/Luanda');
		$timeZoneC=array_add($timeZoneC, 'Africa/Malabo','Africa/Malabo');
		$timeZoneC=array_add($timeZoneC, 'Africa/Ndjamena','Africa/Ndjamena');
		$timeZoneC=array_add($timeZoneC, 'Africa/Niamey','Africa/Niamey');
		$timeZoneC=array_add($timeZoneC, 'Africa/Porto-Novo','Africa/Porto-Novo');
		$timeZoneC=array_add($timeZoneC, 'Africa/Tunis','Africa/Tunis');
		$timeZoneC=array_add($timeZoneC, 'Africa/Windhoek','Africa/Windhoek');
		$timeZoneC=array_add($timeZoneC, 'Arctic/Longyearbyen','Arctic/Longyearbyen');
		$timeZoneC=array_add($timeZoneC, 'Atlantic/Jan_Mayen','Atlantic/Jan_Mayen');
		$timeZoneC=array_add($timeZoneC, 'CET','CET');
		$timeZoneC=array_add($timeZoneC, 'ECT','ECT');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-1','Etc/GMT-1');
		$timeZoneC=array_add($timeZoneC, 'Europe/Amsterdam','Europe/Amsterdam');
		$timeZoneC=array_add($timeZoneC, 'Europe/Andorra','Europe/Andorra');
		$timeZoneC=array_add($timeZoneC, 'Europe/Belgrade','Europe/Belgrade');
		$timeZoneC=array_add($timeZoneC, 'Europe/Berlin','Europe/Berlin');
		$timeZoneC=array_add($timeZoneC, 'Europe/Bratislava','Europe/Bratislava');
		$timeZoneC=array_add($timeZoneC, 'Europe/Brussels','Europe/Brussels');
		$timeZoneC=array_add($timeZoneC, 'Europe/Budapest','Europe/Budapest');
		$timeZoneC=array_add($timeZoneC, 'Europe/Copenhagen','Europe/Copenhagen');
		$timeZoneC=array_add($timeZoneC, 'Europe/Gibraltar','Europe/Gibraltar');
		$timeZoneC=array_add($timeZoneC, 'Europe/Ljubljana','Europe/Ljubljana');
		$timeZoneC=array_add($timeZoneC, 'Europe/Luxembourg','Europe/Luxembourg');
		$timeZoneC=array_add($timeZoneC, 'Europe/Madrid','Europe/Madrid');
		$timeZoneC=array_add($timeZoneC, 'Europe/Malta','Europe/Malta');
		$timeZoneC=array_add($timeZoneC, 'Europe/Monaco','Europe/Monaco');
		$timeZoneC=array_add($timeZoneC, 'Europe/Oslo','Europe/Oslo');
		$timeZoneC=array_add($timeZoneC, 'Europe/Paris','Europe/Paris');
		$timeZoneC=array_add($timeZoneC, 'Europe/Prague','Europe/Prague');
		$timeZoneC=array_add($timeZoneC, 'Europe/Rome','Europe/Rome');
		$timeZoneC=array_add($timeZoneC, 'Europe/San_Marino','Europe/San_Marino');
		$timeZoneC=array_add($timeZoneC, 'Europe/Sarajevo','Europe/Sarajevo');
		$timeZoneC=array_add($timeZoneC, 'Europe/Skopje','Europe/Skopje');
		$timeZoneC=array_add($timeZoneC, 'Europe/Stockholm','Europe/Stockholm');
		$timeZoneC=array_add($timeZoneC, 'Europe/Tirane','Europe/Tirane');
		$timeZoneC=array_add($timeZoneC, 'Europe/Vaduz','Europe/Vaduz');
		$timeZoneC=array_add($timeZoneC, 'Europe/Vatican','Europe/Vatican');
		$timeZoneC=array_add($timeZoneC, 'Europe/Vienna','Europe/Vienna');
		$timeZoneC=array_add($timeZoneC, 'Europe/Warsaw','Europe/Warsaw');
		$timeZoneC=array_add($timeZoneC, 'Europe/Zagreb','Europe/Zagreb');
		$timeZoneC=array_add($timeZoneC, 'Europe/Zurich','Europe/Zurich');
		$timeZoneC=array_add($timeZoneC, 'MET','MET');
		$timeZoneC=array_add($timeZoneC, 'Poland','Poland');
		$timeZoneC=array_add($timeZoneC, 'ART','ART');
		$timeZoneC=array_add($timeZoneC, 'Africa/Blantyre','Africa/Blantyre');
		$timeZoneC=array_add($timeZoneC, 'Africa/Bujumbura','Africa/Bujumbura');
		$timeZoneC=array_add($timeZoneC, 'Africa/Cairo','Africa/Cairo');
		$timeZoneC=array_add($timeZoneC, 'Africa/Gaborone','Africa/Gaborone');
		$timeZoneC=array_add($timeZoneC, 'Africa/Harare','Africa/Harare');
		$timeZoneC=array_add($timeZoneC, 'Africa/Johannesburg','Africa/Johannesburg');
		$timeZoneC=array_add($timeZoneC, 'Africa/Kigali','Africa/Kigali');
		$timeZoneC=array_add($timeZoneC, 'Africa/Lubumbashi','Africa/Lubumbashi');
		$timeZoneC=array_add($timeZoneC, 'Africa/Lusaka','Africa/Lusaka');
		$timeZoneC=array_add($timeZoneC, 'Africa/Maputo','Africa/Maputo');
		$timeZoneC=array_add($timeZoneC, 'Africa/Maseru','Africa/Maseru');
		$timeZoneC=array_add($timeZoneC, 'Africa/Mbabane','Africa/Mbabane');
		$timeZoneC=array_add($timeZoneC, 'Africa/Tripoli','Africa/Tripoli');
		$timeZoneC=array_add($timeZoneC, 'Asia/Amman','Asia/Amman');
		$timeZoneC=array_add($timeZoneC, 'Asia/Beirut','Asia/Beirut');
		$timeZoneC=array_add($timeZoneC, 'Asia/Damascus','Asia/Damascus');
		$timeZoneC=array_add($timeZoneC, 'Asia/Gaza','Asia/Gaza');
		$timeZoneC=array_add($timeZoneC, 'Asia/Istanbul','Asia/Istanbul');
		$timeZoneC=array_add($timeZoneC, 'Asia/Jerusalem','Asia/Jerusalem');
		$timeZoneC=array_add($timeZoneC, 'Asia/Nicosia','Asia/Nicosia');
		$timeZoneC=array_add($timeZoneC, 'Asia/Tel_Aviv','Asia/Tel_Aviv');
		$timeZoneC=array_add($timeZoneC, 'CAT','CAT');
		$timeZoneC=array_add($timeZoneC, 'EET','EET');
		$timeZoneC=array_add($timeZoneC, 'Egypt','Egypt');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-2','Etc/GMT-2');
		$timeZoneC=array_add($timeZoneC, 'Europe/Athens','Europe/Athens');
		$timeZoneC=array_add($timeZoneC, 'Europe/Bucharest','Europe/Bucharest');
		$timeZoneC=array_add($timeZoneC, 'Europe/Chisinau','Europe/Chisinau');
		$timeZoneC=array_add($timeZoneC, 'Europe/Helsinki','Europe/Helsinki');
		$timeZoneC=array_add($timeZoneC, 'Europe/Istanbul','Europe/Istanbul');
		$timeZoneC=array_add($timeZoneC, 'Europe/Kaliningrad','Europe/Kaliningrad');
		$timeZoneC=array_add($timeZoneC, 'Europe/Kiev','Europe/Kiev');
		$timeZoneC=array_add($timeZoneC, 'Europe/Minsk','Europe/Minsk');
		$timeZoneC=array_add($timeZoneC, 'Europe/Nicosia','Europe/Nicosia');
		$timeZoneC=array_add($timeZoneC, 'Europe/Riga','Europe/Riga');
		$timeZoneC=array_add($timeZoneC, 'Europe/Simferopol','Europe/Simferopol');
		$timeZoneC=array_add($timeZoneC, 'Europe/Sofia','Europe/Sofia');
		$timeZoneC=array_add($timeZoneC, 'Europe/Tallinn','Europe/Tallinn');
		$timeZoneC=array_add($timeZoneC, 'Europe/Tiraspol','Europe/Tiraspol');
		$timeZoneC=array_add($timeZoneC, 'Europe/Uzhgorod','Europe/Uzhgorod');
		$timeZoneC=array_add($timeZoneC, 'Europe/Vilnius','Europe/Vilnius');
		$timeZoneC=array_add($timeZoneC, 'Europe/Zaporozhye','Europe/Zaporozhye');
		$timeZoneC=array_add($timeZoneC, 'Israel','Israel');
		$timeZoneC=array_add($timeZoneC, 'Libya','Libya');
		$timeZoneC=array_add($timeZoneC, 'Turkey','Turkey');
		$timeZoneC=array_add($timeZoneC, 'Africa/Addis_Ababa','Africa/Addis_Ababa');
		$timeZoneC=array_add($timeZoneC, 'Africa/Asmera','Africa/Asmera');
		$timeZoneC=array_add($timeZoneC, 'Africa/Dar_es_Salaam','Africa/Dar_es_Salaam');
		$timeZoneC=array_add($timeZoneC, 'Africa/Djibouti','Africa/Djibouti');
		$timeZoneC=array_add($timeZoneC, 'Africa/Kampala','Africa/Kampala');
		$timeZoneC=array_add($timeZoneC, 'Africa/Khartoum','Africa/Khartoum');
		$timeZoneC=array_add($timeZoneC, 'Africa/Mogadishu','Africa/Mogadishu');
		$timeZoneC=array_add($timeZoneC, 'Africa/Nairobi','Africa/Nairobi');
		$timeZoneC=array_add($timeZoneC, 'Antarctica/Syowa','Antarctica/Syowa');
		$timeZoneC=array_add($timeZoneC, 'Asia/Aden','Asia/Aden');
		$timeZoneC=array_add($timeZoneC, 'Asia/Baghdad','Asia/Baghdad');
		$timeZoneC=array_add($timeZoneC, 'Asia/Bahrain','Asia/Bahrain');
		$timeZoneC=array_add($timeZoneC, 'Asia/Kuwait','Asia/Kuwait');
		$timeZoneC=array_add($timeZoneC, 'Asia/Qatar','Asia/Qatar');
		$timeZoneC=array_add($timeZoneC, 'Asia/Riyadh','Asia/Riyadh');
		$timeZoneC=array_add($timeZoneC, 'EAT','EAT');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-3','Etc/GMT-3');
		$timeZoneC=array_add($timeZoneC, 'Europe/Moscow','Europe/Moscow');
		$timeZoneC=array_add($timeZoneC, 'Indian/Antananarivo','Indian/Antananarivo');
		$timeZoneC=array_add($timeZoneC, 'Indian/Comoro','Indian/Comoro');
		$timeZoneC=array_add($timeZoneC, 'Indian/Mayotte','Indian/Mayotte');
		$timeZoneC=array_add($timeZoneC, 'W-SU','W-SU');
		$timeZoneC=array_add($timeZoneC, 'Asia/Riyadh87','Asia/Riyadh87');
		$timeZoneC=array_add($timeZoneC, 'Asia/Riyadh88','Asia/Riyadh88');
		$timeZoneC=array_add($timeZoneC, 'Asia/Riyadh89','Asia/Riyadh89');
		$timeZoneC=array_add($timeZoneC, 'Mideast/Riyadh87','Mideast/Riyadh87');
		$timeZoneC=array_add($timeZoneC, 'Mideast/Riyadh88','Mideast/Riyadh88');
		$timeZoneC=array_add($timeZoneC, 'Mideast/Riyadh89','Mideast/Riyadh89');
		$timeZoneC=array_add($timeZoneC, 'Asia/Tehran','Asia/Tehran');
		$timeZoneC=array_add($timeZoneC, 'Iran','Iran');
		$timeZoneC=array_add($timeZoneC, 'Asia/Aqtau','Asia/Aqtau');
		$timeZoneC=array_add($timeZoneC, 'Asia/Baku','Asia/Baku');
		$timeZoneC=array_add($timeZoneC, 'Asia/Dubai','Asia/Dubai');
		$timeZoneC=array_add($timeZoneC, 'Asia/Muscat','Asia/Muscat');
		$timeZoneC=array_add($timeZoneC, 'Asia/Oral','Asia/Oral');
		$timeZoneC=array_add($timeZoneC, 'Asia/Tbilisi','Asia/Tbilisi');
		$timeZoneC=array_add($timeZoneC, 'Asia/Yerevan','Asia/Yerevan');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-4','Etc/GMT-4');
		$timeZoneC=array_add($timeZoneC, 'Europe/Samara','Europe/Samara');
		$timeZoneC=array_add($timeZoneC, 'Indian/Mahe','Indian/Mahe');
		$timeZoneC=array_add($timeZoneC, 'Indian/Mauritius','Indian/Mauritius');
		$timeZoneC=array_add($timeZoneC, 'Indian/Reunion','Indian/Reunion');
		$timeZoneC=array_add($timeZoneC, 'NET','NET');
		$timeZoneC=array_add($timeZoneC, 'Asia/Kabul','Asia/Kabul');
		$timeZoneC=array_add($timeZoneC, 'Asia/Aqtobe','Asia/Aqtobe');
		$timeZoneC=array_add($timeZoneC, 'Asia/Ashgabat','Asia/Ashgabat');
		$timeZoneC=array_add($timeZoneC, 'Asia/Ashkhabad','Asia/Ashkhabad');
		$timeZoneC=array_add($timeZoneC, 'Asia/Bishkek','Asia/Bishkek');
		$timeZoneC=array_add($timeZoneC, 'Asia/Dushanbe','Asia/Dushanbe');
		$timeZoneC=array_add($timeZoneC, 'Asia/Karachi','Asia/Karachi');
		$timeZoneC=array_add($timeZoneC, 'Asia/Samarkand','Asia/Samarkand');
		$timeZoneC=array_add($timeZoneC, 'Asia/Tashkent','Asia/Tashkent');
		$timeZoneC=array_add($timeZoneC, 'Asia/Yekaterinburg','Asia/Yekaterinburg');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-5','Etc/GMT-5');
		$timeZoneC=array_add($timeZoneC, 'Indian/Kerguelen','Indian/Kerguelen');
		$timeZoneC=array_add($timeZoneC, 'Indian/Maldives','Indian/Maldives');
		$timeZoneC=array_add($timeZoneC, 'PLT','PLT');
		$timeZoneC=array_add($timeZoneC, 'Asia/Calcutta','Asia/Calcutta');
		$timeZoneC=array_add($timeZoneC, 'IST','IST');
		$timeZoneC=array_add($timeZoneC, 'Asia/Katmandu','Asia/Katmandu');
		$timeZoneC=array_add($timeZoneC, 'Antarctica/Mawson','Antarctica/Mawson');
		$timeZoneC=array_add($timeZoneC, 'Antarctica/Vostok','Antarctica/Vostok');
		$timeZoneC=array_add($timeZoneC, 'Asia/Almaty','Asia/Almaty');
		$timeZoneC=array_add($timeZoneC, 'Asia/Colombo','Asia/Colombo');
		$timeZoneC=array_add($timeZoneC, 'Asia/Dacca','Asia/Dacca');
		$timeZoneC=array_add($timeZoneC, 'Asia/Dhaka','Asia/Dhaka');
		$timeZoneC=array_add($timeZoneC, 'Asia/Novosibirsk','Asia/Novosibirsk');
		$timeZoneC=array_add($timeZoneC, 'Asia/Omsk','Asia/Omsk');
		$timeZoneC=array_add($timeZoneC, 'Asia/Qyzylorda','Asia/Qyzylorda');
		$timeZoneC=array_add($timeZoneC, 'Asia/Thimbu','Asia/Thimbu');
		$timeZoneC=array_add($timeZoneC, 'Asia/Thimphu','Asia/Thimphu');
		$timeZoneC=array_add($timeZoneC, 'BST','BST');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-6','Etc/GMT-6');
		$timeZoneC=array_add($timeZoneC, 'Indian/Chagos','Indian/Chagos');
		$timeZoneC=array_add($timeZoneC, 'Asia/Rangoon','Asia/Rangoon');
		$timeZoneC=array_add($timeZoneC, 'Indian/Cocos','Indian/Cocos');
		$timeZoneC=array_add($timeZoneC, 'Antarctica/Davis','Antarctica/Davis');
		$timeZoneC=array_add($timeZoneC, 'Asia/Bangkok','Asia/Bangkok');
		$timeZoneC=array_add($timeZoneC, 'Asia/Hovd','Asia/Hovd');
		$timeZoneC=array_add($timeZoneC, 'Asia/Jakarta','Asia/Jakarta');
		$timeZoneC=array_add($timeZoneC, 'Asia/Krasnoyarsk','Asia/Krasnoyarsk');
		$timeZoneC=array_add($timeZoneC, 'Asia/Phnom_Penh','Asia/Phnom_Penh');
		$timeZoneC=array_add($timeZoneC, 'Asia/Pontianak','Asia/Pontianak');
		$timeZoneC=array_add($timeZoneC, 'Asia/Saigon','Asia/Saigon');
		$timeZoneC=array_add($timeZoneC, 'Asia/Vientiane','Asia/Vientiane');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-7','Etc/GMT-7');
		$timeZoneC=array_add($timeZoneC, 'Indian/Christmas','Indian/Christmas');
		$timeZoneC=array_add($timeZoneC, 'VST','VST');
		$timeZoneC=array_add($timeZoneC, 'Antarctica/Casey','Antarctica/Casey');
		$timeZoneC=array_add($timeZoneC, 'Asia/Brunei','Asia/Brunei');
		$timeZoneC=array_add($timeZoneC, 'Asia/Chongqing','Asia/Chongqing');
		$timeZoneC=array_add($timeZoneC, 'Asia/Chungking','Asia/Chungking');
		$timeZoneC=array_add($timeZoneC, 'Asia/Harbin','Asia/Harbin');
		$timeZoneC=array_add($timeZoneC, 'Asia/Hong_Kong','Asia/Hong_Kong');
		$timeZoneC=array_add($timeZoneC, 'Asia/Irkutsk','Asia/Irkutsk');
		$timeZoneC=array_add($timeZoneC, 'Asia/Kashgar','Asia/Kashgar');
		$timeZoneC=array_add($timeZoneC, 'Asia/Kuala_Lumpur','Asia/Kuala_Lumpur');
		$timeZoneC=array_add($timeZoneC, 'Asia/Kuching','Asia/Kuching');
		$timeZoneC=array_add($timeZoneC, 'Asia/Macao','Asia/Macao');
		$timeZoneC=array_add($timeZoneC, 'Asia/Macau','Asia/Macau');
		$timeZoneC=array_add($timeZoneC, 'Asia/Makassar','Asia/Makassar');
		$timeZoneC=array_add($timeZoneC, 'Asia/Manila','Asia/Manila');
		$timeZoneC=array_add($timeZoneC, 'Asia/Shanghai','Asia/Shanghai');
		$timeZoneC=array_add($timeZoneC, 'Asia/Singapore','Asia/Singapore');
		$timeZoneC=array_add($timeZoneC, 'Asia/Taipei','Asia/Taipei');
		$timeZoneC=array_add($timeZoneC, 'Asia/Ujung_Pandang','Asia/Ujung_Pandang');
		$timeZoneC=array_add($timeZoneC, 'Asia/Ulaanbaatar','Asia/Ulaanbaatar');
		$timeZoneC=array_add($timeZoneC, 'Asia/Ulan_Bator','Asia/Ulan_Bator');
		$timeZoneC=array_add($timeZoneC, 'Asia/Urumqi','Asia/Urumqi');
		$timeZoneC=array_add($timeZoneC, 'Australia/Perth','Australia/Perth');
		$timeZoneC=array_add($timeZoneC, 'Australia/West','Australia/West');
		$timeZoneC=array_add($timeZoneC, 'CTT','CTT');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-8','Etc/GMT-8');
		$timeZoneC=array_add($timeZoneC, 'Hongkong','Hongkong');
		$timeZoneC=array_add($timeZoneC, 'PRC','PRC');
		$timeZoneC=array_add($timeZoneC, 'Singapore','Singapore');
		$timeZoneC=array_add($timeZoneC, 'Asia/Choibalsan','Asia/Choibalsan');
		$timeZoneC=array_add($timeZoneC, 'Asia/Dili','Asia/Dili');
		$timeZoneC=array_add($timeZoneC, 'Asia/Jayapura','Asia/Jayapura');
		$timeZoneC=array_add($timeZoneC, 'Asia/Pyongyang','Asia/Pyongyang');
		$timeZoneC=array_add($timeZoneC, 'Asia/Seoul','Asia/Seoul');
		$timeZoneC=array_add($timeZoneC, 'Asia/Tokyo','Asia/Tokyo');
		$timeZoneC=array_add($timeZoneC, 'Asia/Yakutsk','Asia/Yakutsk');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-9','Etc/GMT-9');
		$timeZoneC=array_add($timeZoneC, 'JST','JST');
		$timeZoneC=array_add($timeZoneC, 'Japan','Japan');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Palau','Pacific/Palau');
		$timeZoneC=array_add($timeZoneC, 'ROK','ROK');
		$timeZoneC=array_add($timeZoneC, 'ACT','ACT');
		$timeZoneC=array_add($timeZoneC, 'Australia/Adelaide','Australia/Adelaide');
		$timeZoneC=array_add($timeZoneC, 'Australia/Broken_Hill','Australia/Broken_Hill');
		$timeZoneC=array_add($timeZoneC, 'Australia/Darwin','Australia/Darwin');
		$timeZoneC=array_add($timeZoneC, 'Australia/North','Australia/North');
		$timeZoneC=array_add($timeZoneC, 'Australia/South','Australia/South');
		$timeZoneC=array_add($timeZoneC, 'Australia/Yancowinna','Australia/Yancowinna');
		$timeZoneC=array_add($timeZoneC, 'AET','AET');
		$timeZoneC=array_add($timeZoneC, 'Antarctica/DumontDUrville','Antarctica/DumontDUrville');
		$timeZoneC=array_add($timeZoneC, 'Asia/Sakhalin','Asia/Sakhalin');
		$timeZoneC=array_add($timeZoneC, 'Asia/Vladivostok','Asia/Vladivostok');
		$timeZoneC=array_add($timeZoneC, 'Australia/ACT','Australia/ACT');
		$timeZoneC=array_add($timeZoneC, 'Australia/Brisbane','Australia/Brisbane');
		$timeZoneC=array_add($timeZoneC, 'Australia/Canberra','Australia/Canberra');
		$timeZoneC=array_add($timeZoneC, 'Australia/Hobart','Australia/Hobart');
		$timeZoneC=array_add($timeZoneC, 'Australia/Lindeman','Australia/Lindeman');
		$timeZoneC=array_add($timeZoneC, 'Australia/Melbourne','Australia/Melbourne');
		$timeZoneC=array_add($timeZoneC, 'Australia/NSW','Australia/NSW');
		$timeZoneC=array_add($timeZoneC, 'Australia/Queensland','Australia/Queensland');
		$timeZoneC=array_add($timeZoneC, 'Australia/Sydney','Australia/Sydney');
		$timeZoneC=array_add($timeZoneC, 'Australia/Tasmania','Australia/Tasmania');
		$timeZoneC=array_add($timeZoneC, 'Australia/Victoria','Australia/Victoria');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-10','Etc/GMT-10');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Guam','Pacific/Guam');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Port_Moresby','Pacific/Port_Moresby');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Saipan','Pacific/Saipan');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Truk','Pacific/Truk');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Yap','Pacific/Yap');
		$timeZoneC=array_add($timeZoneC, 'Australia/LHI','Australia/LHI');
		$timeZoneC=array_add($timeZoneC, 'Australia/Lord_Howe','Australia/Lord_Howe');
		$timeZoneC=array_add($timeZoneC, 'Asia/Magadan','Asia/Magadan');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-11','Etc/GMT-11');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Efate','Pacific/Efate');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Guadalcanal','Pacific/Guadalcanal');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Kosrae','Pacific/Kosrae');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Noumea','Pacific/Noumea');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Ponape','Pacific/Ponape');
		$timeZoneC=array_add($timeZoneC, 'SST','SST');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Norfolk','Pacific/Norfolk');
		$timeZoneC=array_add($timeZoneC, 'Antarctica/McMurdo','Antarctica/McMurdo');
		$timeZoneC=array_add($timeZoneC, 'Antarctica/South_Pole','Antarctica/South_Pole');
		$timeZoneC=array_add($timeZoneC, 'Asia/Anadyr','Asia/Anadyr');
		$timeZoneC=array_add($timeZoneC, 'Asia/Kamchatka','Asia/Kamchatka');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-12','Etc/GMT-12');
		$timeZoneC=array_add($timeZoneC, 'Kwajalein','Kwajalein');
		$timeZoneC=array_add($timeZoneC, 'NST','NST');
		$timeZoneC=array_add($timeZoneC, 'NZ','NZ');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Auckland','Pacific/Auckland');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Fiji','Pacific/Fiji');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Funafuti','Pacific/Funafuti');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Kwajalein','Pacific/Kwajalein');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Majuro','Pacific/Majuro');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Nauru','Pacific/Nauru');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Tarawa','Pacific/Tarawa');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Wake','Pacific/Wake');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Wallis','Pacific/Wallis');
		$timeZoneC=array_add($timeZoneC, 'NZ-CHAT','NZ-CHAT');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Chatham','Pacific/Chatham');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-13','Etc/GMT-13');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Enderbury','Pacific/Enderbury');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Tongatapu','Pacific/Tongatapu');
		$timeZoneC=array_add($timeZoneC, 'Etc/GMT-14','Etc/GMT-14');
		$timeZoneC=array_add($timeZoneC, 'Pacific/Kiritimati','Pacific/Kiritimati');
		$timeZoneC=array_add($timeZoneC, 'Asia/Kolkata','Asia/Kolkata');
		
		

	return $timeZoneC;
}

	public function fransearch()
	{
		  Log::info('------- inside fransearch--------');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        Log::info(' inside multi ' );
       
        $fransId = $redis->smembers('S_Franchises');
       
     
       
        $orgArr = array();
        foreach($fransId as $org) {
            $orgArr = array_add($orgArr, $org,$org);
        }
        $fransId = $orgArr;
     
                 
 
                 
        return View::make ( 'vdm.franchise.frans' )->with('fransId',$fransId);       
       
	}




public function users()
	{
		  Log::info('------- inside fransearch--------');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        Log::info(' inside multi ' );
       
        $userId = $redis->smembers('S_Franchises');
       
     
       
        $orgArr = array();
        foreach($userId as $org) {
        	$temp=$redis->smembers( 'S_Users_' . $org);
        	foreach ($temp as $key) {
        		 $orgArr = array_add($orgArr, $key,$key);
        	}

           
        }
        $userId = $orgArr;
     
                 
 
                 
        return View::make ( 'vdm.franchise.users' )->with('userId',$userId);       
       
	}


	public function buyAddress()
	{
		  Log::info('------- inside fransearch--------');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $addressCount=$redis->get('VAMOSADDRESS_CONTROL');   
        if($addressCount==null)
        {
        	$addressCount=0;
        }   
        return View::make ( 'vdm.franchise.buyAddress' )->with('addressCount',$addressCount);       
       
	}


	public function updateAddCount() {
                    log::info( '-----------List----------- ::');
                    if (! Auth::check () ) {
                                    return Redirect::to ( 'login' );
                    }
                     $redis = Redis::connection ();
                   $redis->set('VAMOSADDRESS_CONTROL',Input::get ( 'addressCount' ));     
                return Redirect::to ( 'vdmFranchises' );
    }



		public function findFransList() {
                                log::info( '-----------List----------- ::');
                                if (! Auth::check () ) {
                                                return Redirect::to ( 'login' );
                                }
                                 $redis = Redis::connection ();
                               $username = Input::get ( 'frans' );
							$franDetails_json = $redis->hget ( 'H_Franchise', $username);
							$franchiseDetails=json_decode($franDetails_json,true);
						
								if(isset($franchiseDetails['userId'])==1)
									$username=$franchiseDetails['userId'];
								else
									$username=null;

					          if($username==null)
                                {
                                                log::info( '--------use one----------' );
                                                $username = Session::get('page');
                                }
                                else{
                                                log::info( '--------use two----------'.$username);
                                                Session::put('page',$username);
                                }
                                               
                                                try{
                                                                 $user=User::where('username', '=', $username)->firstOrFail();
												log::info( '--------new name----------' .$user);
					                                Auth::login($user);
                                                }catch(\Exception $e)
								                   {
								                                return Redirect::to ( 'vdmFranchises/fransearch' ); 
								                   }
                                 //$user = User::find(10);
                               
                               
                               
                               
                                return Redirect::to ( 'Business' );
                }





                public function findUsersList() {
                                log::info( '-----------List----------- ::');
                                if (! Auth::check () ) {
                                                return Redirect::to ( 'login' );
                                }
                                 $redis = Redis::connection ();
                               $username = Input::get ( 'users' );

					          if($username==null)
                                {
                                                log::info( '--------use one----------' );
                                                $username = Session::get('page');
                                }
                                else{
                                                log::info( '--------use two----------'.$username);
                                                Session::put('page',$username);
                                }
                                               
                                                try{
                                                                 $user=User::where('username', '=', $username)->firstOrFail();
												log::info( '--------new name----------' .$user);
					                                Auth::login($user);
                                                }catch(\Exception $e)
								                   {
								                                return Redirect::to ( 'vdmFranchises/users' ); 
								                   }
                                 //$user = User::find(10);
                               
                               
                               
                               
                                return Redirect::to ( 'Business' );
                }
	/**

		Frabchise is created by VAMOS Admin
		Franchise name
		Franchise ID (company ID)
		Franchise description
		Franchise full address
		Franchise landline no
		Franchise mobile number 1
		Franchise mobile number 2
		Franchise email id1
		Franchise email id2
		Franchise other details
		Franchise login details
		
	 * 
	 * @return Response
	 */
	public function store() {
		Log::info("reached franchise store");
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		
		$rules = array (
				'fname' => 'required',
				'fcode' => 'required',
				'description' => 'required',
				'fullAddress' => 'required',
				'landline' => 'required',
				'mobileNo1' => 'required|numeric',
				'mobileNo2' => 'numeric',
				'email1' => 'required|email',
				'email2' => 'email',
				'userId' => 'required', 
				'website' => 'required', 
				'otherDetails' => 'required' 
		);
		$validator = Validator::make ( Input::all (), $rules );
		$userId = Input::get ('userId');
		$fcode = Input::get ( 'fcode' );
		$dbIpindex=Input::get ( 'ipadd' );
		Log::info("dbIpindex..".$dbIpindex);
		$val = $redis->sismember('S_Franchises',$fcode);
		$val1= $redis->sismember ( 'S_Users_' . $fcode, $userId );

			
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmFranchises/create' )->withErrors ( $validator );
		}else if($val==1 ) {
			Session::flash ( 'message', $fcode . 'Franchise already exist ' . '!' );
			return Redirect::to ( 'vdmFranchises/create' );
		}
		else if($val1==1) {
			Session::flash ( 'message', $userId . ' already exist. Please use different id ' . '!' );
			return Redirect::to ( 'vdmFranchises/create' );
		}
		else {
			// store
			$dbIpAr=VdmFranchiseController::dbIp();
			$dbIp=$dbIpAr[$dbIpindex];
			$fname = Input::get ( 'fname' );
			$fcode = Input::get ( 'fcode' );
			$description = Input::get ( 'description' );
			$fullAddress = Input::get ( 'fullAddress' );
			$landline = Input::get ( 'landline' );
			$mobileNo1 = Input::get ( 'mobileNo1' );
			$mobileNo2 = Input::get ( 'mobileNo2' );
			$website = Input::get ( 'website' );
			$email1 = Input::get ( 'email1' );
			$email2 = Input::get ( 'email2' );
			$userId = Input::get ('userId');
			$otherDetails = Input::get ('otherDetails');
			$numberofLicence = Input::get ('numberofLicence');
			$smsSender=Input::get ('smsSender');
			$smsProvider=Input::get ('smsProvider');
			$providerUserName=Input::get ('providerUserName');
			$providerPassword=Input::get ('providerPassword');
			$backUpDays=Input::get('backUpDays');
			//$eFDSchedular=Input::get ('eFDSchedular');
			$timeZone=Input::get ('timeZone');
			$apiKey=Input::get('apiKey');
			$dbType=Input::get('dbType');
			
			// $refDataArr = array('regNo'=>$regNo,'vehicleMake'=>$vehicleMake,'vehicleType'=>$vehicleType,'oprName'=>$oprName,
			// 'mobileNo'=>$mobileNo,'vehicleCap'=>$vehicleCap,'deviceModel'=>$deviceModel);
			

			$redis->sadd('S_Franchises',$fcode);
			
			
	
			/*$redis->hmset ( 'H_Franchise', $fcode.':fname',$fname,$fcode.':description',$description,
					$fcode.':landline',$landline,$fcode.':mobileNo1',$mobileNo1,$fcode.':mobileNo2',$mobileNo2,
					$fcode.':email1',$email1,$fcode.':email2',$email2,$fcode.':userId',$userId);*///ram what to do migration
			
			$details = array (
					'fname' => $fname,
					'description' => $description,
					'landline' => $landline,
					'mobileNo1' => $mobileNo1,					
					'mobileNo2' => $mobileNo2,
					'email1' => $email1,
					'email2' => $email2,
					'userId' => $userId,
					'fullAddress' => $fullAddress,
					'otherDetails' => $otherDetails,
					'numberofLicence' => $numberofLicence,
					'availableLincence'=>$numberofLicence,
					'website'=>$website,
					'smsSender'=>$smsSender,
					'smsProvider'=>$smsProvider,
					'providerUserName'=>$providerUserName,
					'providerPassword'=>$providerPassword,
					'timeZone'=>$timeZone,
					'apiKey'=>$apiKey,	
					'backUpDays'=>$backUpDays,
					'dbType'=>$dbType,
					//'eFDSchedular'=>$eFDSchedular
			);
			$redis->hmset('H_Franchise_Mysql_DatabaseIP',$fcode,$dbIp);
			$detailsJson = json_encode ( $details );
			$redis->hmset ( 'H_Franchise', $fcode,$detailsJson);
			$redis->hmset('H_Fcode_Timezone_Schedular',$fcode,$timeZone);
			$redis->sadd ( 'S_Users_' . $fcode, $userId );
			$password='awesome';
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo', $mobileNo1,$userId.':email',$email1 ,$userId.':password',$password,$userId.':OWN','admin');
			$user = new User;
			$user->name = $fname;
			$user->username=$userId;
			$user->email=$email1; 
			$user->mobileNo=$mobileNo1;
			$user->password=Hash::make($password);
			$user->save();

			Log::info("going to email..");
			
			/** 
			 * Add vamos admin user for each franchise
			 * 
			 */
			$user = new User;
			$vamosid='vamos'.$fcode;	
			$user->name = 'vamos'.$fname;
			$user->mobileNo='1234567890';
			$user->username=$vamosid;
			$user->email='support@vamosys.com';
			$user->password=Hash::make($password);
			$user->save();
			$redis->sadd ( 'S_Users_' . $fcode, $vamosid );
			$redis->hmset ( 'H_UserId_Cust_Map', $vamosid . ':fcode', $fcode);
		
        /*				
			Mail::queue('emails.welcome', array('fname'=>$fname,'userId'=>$userId,'password'=>$password), function($message)
			{
				Log::info("Inside email :" . Input::get ( 'email1' ));
				
				$message->to(Input::get ( 'email1' ))->subject('Welcome to VAMO Systems');
			});
			*/
			
			// redirect
			Session::flash ( 'message', 'Successfully created ' . $fname . '!' );
			return Redirect::to ( 'vdmFranchises' );
		}
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function show($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
	
		$fcode=$id;
		$redis = Redis::connection ();
		
	
		/*$franDetails = $redis->hmget ( 'H_Franchise', $fcode.':fname',$fcode.':descrption:',
				$fcode.':landline',$fcode.':mobileNo1',$fcode.':mobileNo2',
				$fcode.':email1',$fcode.':email2',$fcode.':userId');*/
				$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);	
				$franDetails=json_decode($franDetails_json,true);
		$franchiseDetails = implode ( '<br/>', $franDetails );
		
		return View::make ( 'vdm.franchise.show', array (
				'fname' => $franDetails['fname'] 
		) )->with ( 'fcode', $fcode )->with ( 'franchiseDetails', $franchiseDetails );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function edit($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		$redis = Redis::connection ();
		$fcode = $id;
		
		/*$franchiseDetails = $redis->hmget ( 'H_Franchise', $fcode.':fname',$fcode.':description',
				$fcode.':landline',$fcode.':mobileNo1',$fcode.':mobileNo2',
				$fcode.':email1',$fcode.':email2',$fcode.':userId',$fcode.':fullAddress',$fcode.':otherDetails');*/
			
		$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
				$franchiseDetails=json_decode($franDetails_json,true);
		
		if(isset($franchiseDetails['description'])==1)
			$description=$franchiseDetails['description'];
		else
			$description='';
		if(isset($franchiseDetails['landline'])==1)
			$landline=$franchiseDetails['landline'];
		else
			$landline='';
		if(isset($franchiseDetails['mobileNo1'])==1)
			$mobileNo1=$franchiseDetails['mobileNo1'];
		else
			$mobileNo1='';
		if(isset($franchiseDetails['mobileNo2'])==1)
			$mobileNo2=$franchiseDetails['mobileNo2'];
		else
			$mobileNo2='';
			
		if(isset($franchiseDetails['email1'])==1)
			$email1=$franchiseDetails['email1'];
		else
			$email1='';
		if(isset($franchiseDetails['email2'])==1)
			$email2=$franchiseDetails['email2'];
		else
			$email2='';
		if(isset($franchiseDetails['userId'])==1)
			$userId=$franchiseDetails['userId'];
		else
			$userId='';
		if(isset($franchiseDetails['fullAddress'])==1)
			$fullAddress=$franchiseDetails['fullAddress'];
		else
			$fullAddress='';
		if(isset($franchiseDetails['otherDetails'])==1)
			$otherDetails=$franchiseDetails['otherDetails'];
		else
			$otherDetails='';
		if(isset($franchiseDetails['numberofLicence'])==1)
			$numberofLicence=$franchiseDetails['numberofLicence'];
		else
			$numberofLicence='0';
		if(isset($franchiseDetails['availableLincence'])==1)
			$availableLincence=$franchiseDetails['availableLincence'];
		else
			$availableLincence='0';
		if(isset($franchiseDetails['website'])==1)
			$website=$franchiseDetails['website'];
		else
			$website='';
		if(isset($franchiseDetails['smsSender'])==1)
			$smsSender=$franchiseDetails['smsSender'];
		else
			$smsSender='';
		if(isset($franchiseDetails['smsProvider'])==1)
			$smsProvider=$franchiseDetails['smsProvider'];
		else
			$smsProvider='nill';
		if(isset($franchiseDetails['providerUserName'])==1)
			$providerUserName=$franchiseDetails['providerUserName'];
		else
			$providerUserName='';
		if(isset($franchiseDetails['providerPassword'])==1)
			$providerPassword=$franchiseDetails['providerPassword'];
		else
			$providerPassword='';
		// if(isset($franchiseDetails['eFDSchedular'])==1)
		// 	$eFDSchedular=$franchiseDetails['eFDSchedular'];
		// else
		// 	$eFDSchedular='';
		if(isset($franchiseDetails['timeZone'])==1)
			$timeZone=$franchiseDetails['timeZone'];
		else
			$timeZone='Asia/Kolkata';
		if($timeZone=='')
			$timeZone='Asia/Kolkata';
		if(isset($franchiseDetails['apiKey'])==1)
			$apiKey=$franchiseDetails['apiKey'];
		else
			$apiKey='';
		if(isset($franchiseDetails['backUpDays'])==1)
			$backUpDays=$franchiseDetails['backUpDays'];
		else
			$backUpDays='60';
		if(isset($franchiseDetails['dbType'])==1)
			$dbType=$franchiseDetails['dbType'];
		else
			$dbType='mysql';
		
		$dbIp=$redis->hget('H_Franchise_Mysql_DatabaseIP',$fcode);
		if($dbIp=='')
		{
			$dbIp='188.166.244.126';
		}
		$key = array_search($dbIp, VdmFranchiseController::dbIp());
		$dbIp=$key;
		Log::info("dbIp..".$dbIp);
		return View::make ( 'vdm.franchise.edit', array (
				'fname' => $franchiseDetails['fname'] 
		) )->with ( 'fcode', $fcode )->with ( 'franchiseDetails', $franchiseDetails )
		->with('description',$description)
		->with('landline',$landline)
		->with('mobileNo1',$mobileNo1)
		->with('mobileNo2',$mobileNo2)
		->with('email1',$email1)
		->with('email2',$email2)
		->with('userId',$userId)
		->with('fullAddress',$fullAddress)
		->with('otherDetails',$otherDetails)
		->with('numberofLicenceO',$numberofLicence)
		->with('availableLincenceO',$availableLincence)
		->with('numberofLicence',$numberofLicence)
		->with('availableLincence',$availableLincence)
		->with('website',$website)
		->with('smsSender',$smsSender)
		->with('smsProvider',$smsProvider)
		->with('providerUserName',$providerUserName)
		->with('providerPassword',$providerPassword)
		->with('timeZone',$timeZone)
		->with('apiKey', $apiKey)
		->with('dbIp', $dbIp)
		->with('dbType', $dbType)
		->with('backUpDays', $backUpDays)
		->with('dbIpAr', VdmFranchiseController::dbIp())
		->with('smsP',VdmFranchiseController::smsP())
	    ->with('timeZoneC',VdmFranchiseController::timeZoneC())
        ->with('backType',VdmFranchiseController::backTypeC());
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function update($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$fcode = $id;
		$username = Auth::user ()->username;
		$redis = Redis::connection ();

		$rules = array (

				'email1' => 'required|email',
				'email2' => 'email',
		);
		$validator = Validator::make ( Input::all (), $rules );

		
		if ($validator->fails ()) {
			Log::info(" failed ");
			Session::flash ( 'message', 'Update failed. Please check logs for more details' . '!' );
				
		//	return Redirect::to ( 'vdmFranchises' )->withErrors ( $validator );

			return Redirect::to ( 'vdmFranchises/update' )->withErrors ( $validator );
		} 
		else {
			// store
				
			$dbIpindex=Input::get ( 'ipadd' );
			$dbIpAr=VdmFranchiseController::dbIp();
			$dbIp=$dbIpAr[$dbIpindex];
			$description = Input::get ( 'description' );
			$fullAddress = Input::get ( 'fullAddress' );
			$landline = Input::get ( 'landline' );
			$mobileNo1 = Input::get ( 'mobileNo1' );
			$mobileNo2 = Input::get ( 'mobileNo2' );
			$email1 = Input::get ( 'email1' );
			$email2 = Input::get ( 'email2' );

			$otherDetails = Input::get ('otherDetails');
			$numberofLicence = Input::get ('addLicence');	
			$website= Input::get ('website');
			$smsSender=Input::get ('smsSender');
			$smsProvider=Input::get ('smsProvider');
			$providerUserName=Input::get ('providerUserName');
			$providerPassword=Input::get ('providerPassword');
			$timeZone=Input::get ('timeZone');
			$apiKey=Input::get('apiKey');
			$backUpDays=Input::get('backUpDays');
			$dbType=Input::get('dbType');
			$redis = Redis::connection ();
				
				if($numberofLicence==null)
				{
					$numberofLicence=0;
				}
				/*if($numberofLicence<Session::get('available'))
				{
					log::info('--------------inside less value-----------');
					return Redirect::to ( 'vdmFranchises/update' )->withErrors ( 'Please check the License count' );
				}
				else{*/
					$availableLincence=$numberofLicence+Input::get('availableLincenceO');
					$numberofLicence=$numberofLicence+Input::get('numberofLicenceO');
				//}
				
			// $refDataArr = array('regNo'=>$regNo,'vehicleMake'=>$vehicleMake,'vehicleType'=>$vehicleType,'oprName'=>$oprName,
			// 'mobileNo'=>$mobileNo,'vehicleCap'=>$vehicleCap,'deviceModel'=>$deviceModel);
				
		
			$val = $redis->sadd('S_Franchises',$fcode);
				
			log::info(" redis return code :"+$val);
			//TODO
			/**
			* If code is not unique this method fail and return - suggesting
			* that the ID should be unique.
			*
			* Possible improvement..implement ajax call - verify the code while typing itself
			*
			*/
			$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
				$franchiseDetails=json_decode($franDetails_json,true);
			
			$userId = $franchiseDetails['userId'];
			$fname =$franchiseDetails['fname'];
			
			/*$redis->hmset ( 'H_Franchise', $fcode.':description',$description,
					$fcode.':landline',$landline,$fcode.':mobileNo1',$mobileNo1,$fcode.':mobileNo2',$mobileNo2,
					$fcode.':email1',$email1,$fcode.':email2',$email2);*/
					
					$details = array (
					'fname' => $fname,
					'description' => $description,
					'landline' => $landline,
					'mobileNo1' => $mobileNo1,					
					'mobileNo2' => $mobileNo2,
					'email1' => $email1,
					'email2' => $email2,
					'userId' => $userId,
					'fullAddress' => $fullAddress,
					'otherDetails' => $otherDetails,
					'numberofLicence' => $numberofLicence,
					'availableLincence'=>$availableLincence,
					'website'=>$website,
					'smsSender'=>$smsSender,
					'smsProvider'=>$smsProvider,
					'providerUserName'=>$providerUserName,
					'providerPassword'=>$providerPassword,	
					//'eFDSchedular'=>$eFDSchedular,
					'timeZone'=>$timeZone,
					'apiKey'=>$apiKey,	
					'backUpDays'=>$backUpDays,
					'dbType'=>$dbType,
					
			);
			$detailsJson = json_encode ( $details );
			log::info($detailsJson);
			log::info($apiKey);
			$redis->hmset('H_Franchise_Mysql_DatabaseIP',$fcode,$dbIp);
			$redis->hmset ( 'H_Franchise', $fcode,$detailsJson);
			$redis->hmset('H_Fcode_Timezone_Schedular',$fcode,$timeZone);
			
			$redis->sadd ( 'S_Users_' . $fcode, $userId );
			$redis->hmset ( 'H_UserId_Cust_Map', $userId . ':fcode', $fcode, $userId . ':mobileNo',
					 $mobileNo1,$userId.':email',$email1 );

			
			DB::table('users')
			->where('username', $userId)
			->update(array('email' => $email1));
			
	

		}
					
		// redirect
		Session::flash ( 'message', 'Successfully updated ' . $fname . '!' );
		return Redirect::to ( 'vdmFranchises' );
		
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function destroy($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		
		$fcode = $id;
		
		$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
				$franchiseDetails=json_decode($franDetails_json,true);
			
			$userId = $franchiseDetails['userId'];
			$fname =$franchiseDetails['fname'];
			$email1 = $franchiseDetails['email1'];
		
		
		/*$userId = $redis->hget('H_Franchise',$fcode.':userId');
		$fname = $redis->hget('H_Franchise',$fcode.':fname');
		
		$email1 = $redis->hget('H_Franchise', $fcode.':email1');*/
		
		$redis->hdel ( 'H_Franchise', $fcode);
				
				
				
		
		$redis->srem('S_Franchises',$fcode);

		$redis->srem ( 'S_Users_' . $fcode, $userId );
		$redis->hdel ( 'H_UserId_Cust_Map', $userId . ':fcode', $userId . ':mobileNo', $userId.':email');
		
		Log::info(" about to delete user" .$userId);
		
		DB::table('users')->where('username', $userId)->delete();
		
		$vamosid = 'vamos'.$fcode;
		
		
		$redis->srem ( 'S_Users_' . $fcode, $vamosid );
		$redis->hdel ( 'H_UserId_Cust_Map', $vamosid . ':fcode');
		$redis->hdel('H_Franchise_Mysql_DatabaseIP',$fcode);
		
		Session::put('email1',$email1);
		Log::info("Email Id :" . Session::get ( 'email1' ));
		
		Mail::queue('emails.welcome', array('fname'=>$fname,'userId'=>$userId), function($message)
		{
			Log::info("Inside email :" . Session::get ( 'email1' ));
		
			$message->to(Session::pull ( 'email1' ))->subject('User Id deleted');
		});
	
		
		Session::flash ( 'message', 'Successfully deleted ' . 'fname:'.$fcode . '!' );
		return Redirect::to ( 'vdmFranchises' );
	}
}

