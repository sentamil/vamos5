<?php 
 /*
 * 
 */
 class ScheduledController extends \BaseController
 {
 	
/*
	Insert or update function in scheduling mail for daily
*/	
public function reportScheduling(){
	/*
		Redis Connection
	*/
	$redis = Redis::connection ();
	
	$grpName    = Input::get ( 'groupName' );
	$username   = Input::get ( 'userName' );
  //$vehicle    = Input::get ( 'vehicle' );
	$reportList = Input::get ( 'reportList' );

       log::info($reportList);
    // log::info($grpName);
    // log::info($username);
	// $fromt = Input::get ( 'fromt' );
	// $mail = Input::get ( 'mail' );
	// $tot = Input::get ( 'tot' );
	$fcode = $redis->hget ( 'H_UserId_Cust_Map', $reportList[0][0] . ':fcode' );
	
	$franchiesJson 	= 	$redis->hget('H_Franchise_Mysql_DatabaseIP', $fcode);

	$servername = $franchiesJson;
	//$servername   =  "128.199.159.130";
	
	//if (!$servername){
	if (strlen($servername) > 0 && strlen(trim($servername) == 0)){
		// $servername = "188.166.237.200";
		return 'Ipaddress Failed !!!';
	}
	
	$usernamedb = "root";
	$password   = "#vamo123";
	$dbname     = $fcode;
	
	$conn = mysqli_connect($servername, $usernamedb, $password, $dbname);
   
   	if( !$conn ) {
    	die('Could not connect: ' . mysqli_connect_error());
      	return 'Please Update One more time Connection failed';
   	} else { 

   		log::info(' created connection ');
   	
   		/*
			Create table
   		*/
		$create		= 	"CREATE TABLE IF NOT EXISTS ScheduledReport (vehicleId varchar(100) NOT NULL, fromTime int(2) NOT NULL, toTime int(2) NOT NULL, email TEXT NOT NULL, reports TEXT NOT NULL, fCode varchar(10) NOT NULL, userName varchar(100) NOT NULL, groupName varchar(100) NOT NULL, status varchar(20), PRIMARY KEY(vehicleId,userName,groupName,fCode))";

		if($conn->query($create))
   		{
   			$select 	=	"SELECT * FROM ScheduledReport WHERE userName='$username' AND groupName='$grpName'";
   			$results = $conn->query($select);
	   		if($results->num_rows > 0) {

	   			$delete =	"DELETE FROM ScheduledReport WHERE userName='$username' AND groupName='$grpName'";
	   			$conn->query($delete);

	   		} 
	   	}
   		
   		// try {
	    	for ($x = 0; $x <= count($reportList)-1; $x++) {
	   			mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
	   			$reports = $reportList[$x][1];
	   			$vId = $reportList[$x][2];
	   			$fromt = $reportList[$x][3];
	   			$tot =	$reportList[$x][4];
	   			$email = $reportList[$x][5];
	   			// $usrName = $reportList[$x][0];
	   			// $grpName = $reportList[$x][6];
	   			$insert 	= 	"INSERT INTO ScheduledReport (vehicleId, fromTime, toTime, email, reports, fCode, userName, groupName, status)VALUES ('$vId', '$fromt','$tot','$email','$reports','$fcode', '$username','$grpName','inserted')";
	   			$conn->multi_query($insert);
			   	$conn->commit();
			} 
		// } catch (Exception $e) {
		// 	    log::info($e->getMessage());
		// }
	
  	   	// ($result->num_rows > 0) ? $conn->query($update) : $conn->query($insert);

	   	log::info(' Sucessfully inserted/updated !!! ');
		
   	$conn->close();
   	
	return 'correct';
 	}
 }


/*
	Insert or update function in scheduling mail for monthly
*/	

public function monthReportScheduling() {

	log::info('Scheduling func called !....');

    /*
		Redis Connection
	*/
		$redis      =  Redis::connection();
	    $username   =  Input::get('userName');
 	    $reptList   =  Input::get('reportList');

 	       log::info($reptList); 
 	        log::info($reptList[0][0]);  

        $fcode           =  $redis->hget('H_UserId_Cust_Map',$reptList[0][0].':fcode');
		$franchiesJson   =  $redis->hget('H_Franchise_Mysql_DatabaseIP', $fcode);
	    $servername      =  $franchiesJson;
	    //$servername      =  "128.199.159.130";

		if (strlen($servername) > 0 && strlen(trim($servername) == 0)){
		 return 'Ipaddress Failed !!!';	
	    }
	
	    $usernamedb  =  "root";
	    $password    =  "#vamo123";
	    $dbname      =  $fcode;

	     log::info($dbname);
	     log::info($servername);

        $conn  =  mysqli_connect($servername, $usernamedb, $password);
	    $create_db = "CREATE DATABASE IF NOT EXISTS $dbname";

	   
	    if($conn->query($create_db)) {

	    	$conn  =  mysqli_connect($servername, $usernamedb, $password, $dbname);
	   
	
	     if(!$conn) {

    	   die('Could not connect:'.mysqli_connect_error());

      	 return 'Please Update One more time Connection failed';
   	    
   	    } else { 

   		log::info(' created connection ');
   	
   	   /*
	      Create table
   	   */

       $create = "CREATE TABLE IF NOT EXISTS ScheduledReportMonthly(groupName varchar(100) NOT NULL, reportName TEXT NOT NULL, userName varchar(100) NOT NULL, email TEXT NOT NULL, PRIMARY KEY(groupName,userName))";

	   if($conn->query($create))
   	   {
   			$select   =	 "SELECT * FROM ScheduledReportMonthly WHERE userName='$username'";
   			$results  =  $conn->query($select);

	   		if($results->num_rows > 0) {

	   			$delete  =	"DELETE FROM ScheduledReportMonthly WHERE userName='$username'";
	   			$conn->query($delete);
	   		}

	   		log::info('delllleeeeeted.....');
	   	}
   		
   		// try {
	    	for ($x = 0; $x <= count($reptList)-1; $x++) {
	   			mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
	   			$reports = $reptList[$x][1];
	   			$grpName = $reptList[$x][2];
	   			$email   = $reptList[$x][3];
	   	   		
	   	   $insert  = 	"INSERT INTO ScheduledReportMonthly (groupName, reportName, userName, email)VALUES ('$grpName', '$reports', '$username','$email')";
	   			$conn->multi_query($insert);
			   	$conn->commit();
			} 
        }

    } else{

        log::info('Database not connected...');

    }

}


/*  Get Report Name for Scheduled Reports  */
public function getRepName(){

  $redis 	   =  Redis::connection ();
  $reportNames =  $redis->smembers('S_ScheduledReports');

    // log::info($reportNames);

return $reportNames;
}


/*
	display the mail sending function
*/
public function getValue() {
	
	$redis 			= 	Redis::connection ();
	$username 		= 	Input::get ( 'userName' );
	$grpName 		= 	Input::get( 'groupName' );
	$fcode 			= 	$redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
	$franchiesJson 	= 	$redis->hget('H_Franchise_Mysql_DatabaseIP', $fcode);
	
	$servername 	= 	$franchiesJson;
	//$servername 	=  "128.199.159.130";

	$valueList 		= 	[];
	if (strlen($servername) > 0 && strlen(trim($servername) == 0)){
		return 'Ipaddress Failed !!!';
	}
	
	$usernamedb = "root";
	$password 	= "#vamo123";
	$dbname 	= $fcode;
	log::info($username);
	try
	{
		$con = mysqli_connect($servername, $usernamedb, $password, $fcode);
   	
	   	if( !$con ) {
	    	die('Could not connect: ' . mysqli_connect_error());
	      	log::info("Connection failed: ");
	   	} else {

	   		log::info(' Connection Sucessfully ');
	   		$results = $con->query("SELECT * FROM ScheduledReport WHERE userName='$username' AND groupName='$grpName'");
	   		if($results->num_rows > 0) {
	   			return(mysqli_fetch_all($results,MYSQLI_ASSOC));
	   		} else {
	   			return [];
	   		}
	     }
	   	$con->close();
	}
	catch(Exception $e) {
  		log::info( 'Message: ' .$e->getMessage());	
	}
   	
}

/*
	display the mail sending function
*/
public function getMonValue() {

	log::info('getMonValue...');
	
	$redis 			= 	Redis::connection ();
	$username 		= 	Input::get ('userName');
	$fcode 			= 	$redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
	$franchiesJson 	= 	$redis->hget('H_Franchise_Mysql_DatabaseIP', $fcode);
	
	$servername 	= 	$franchiesJson;
	//$servername 	=  "128.199.159.130";
	$valueList 		= 	[];

	if (strlen($servername) > 0 && strlen(trim($servername) == 0)){
		return 'Ipaddress Failed !!!';
	}
	
	$usernamedb = "root";
	$password 	= "#vamo123";
	$dbname 	= $fcode;

	log::info($username);

        log::info($servername);

   try
	{

		$con = mysqli_connect($servername, $usernamedb, $password);

   		if( !$con ) {
	    	die('Could not connect: ' . mysqli_connect_error());
	      	log::info("Connection failed: ");
	   	} else {

	   		$db_selected = mysqli_select_db($con, $dbname);

	   		if(!$db_selected) {

	   		  log::info('Database '.$dbname.' not exists...');

	   		} else {

	   		   $con = mysqli_connect($servername, $usernamedb, $password, $dbname);

	   		   log::info('Connected Sucessfully...');

	   		   $results = $con->query("SELECT * FROM ScheduledReportMonthly WHERE userName='$username'");
	   		  
	   		   if($results->num_rows > 0) {
	   			 return(mysqli_fetch_all($results,MYSQLI_ASSOC));
	   		   } else {
	   			 return [];
	   		   }

	   		} 
	    }

	   	$con->close();
	}
	catch(Exception $e) {
  		log::info( 'Message: ' .$e->getMessage());	
	}
   	
}

/*
	DELETE VALUE IN SCHEDULED REPORTS
*/
public function reportDelete() {

	$username 		= 	Input::get ( 'userName' );
	$grpName 		= 	Input::get ( 'groupName' );
	$vidName 		= 	Input::get ( 'vehiId' );
	$redis 			= 	Redis::connection ();
	$fcode 			= 	$redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
	$franchiesJson 	= 	$redis->hget('H_Franchise_Mysql_DatabaseIP', $fcode);
	$servername 	= 	$franchiesJson;
	//$servername 	=  "128.199.159.130";
	
	if (strlen($servername) > 0 && strlen(trim($servername) == 0)){
		return 'Ipaddress Failed !!!';
	}
	
	$usernamedb 	= "root";
	$password 		= "#vamo123";
	$dbname 		= $fcode;
	$groupQuery 	= "DELETE FROM ScheduledReport WHERE userName='$username' AND groupName='$grpName'";
	$vehiQuery 		= "DELETE FROM ScheduledReport WHERE userName='$username' AND groupName='$grpName' AND vehicleId='$vidName'";
	$ex_Query 		= ($vidName ? $vehiQuery : $groupQuery);
    	
    log::info($ex_Query);
	try {

		$con = mysqli_connect($servername, $usernamedb, $password, $fcode);

		if( !$con ) {
	    	
	    	die('Could not connect: ' . mysqli_connect_error());
	      	log::info("Connection failed: ");

	   	} else {


            $db_selected = mysqli_select_db($con, $dbname);

	   		if(!$db_selected) {

	   		  log::info('Database '.$dbname.' not exists...');

	   		} else {


	   		log::info(' Connection Sucessfully ');
	   		$con->query($ex_Query);
	   		return 'correct';

	   	    }
	   	$con->close();
	  
	  }
	
	} catch (Exception $e) {
		
		log::info($e);
		return 'incorrect';

	}
	
}

public function reportMonDelete(){

	$username 		=  Input::get('userName');
	$grpName 		=  Input::get('groupName');
	$redis 			=  Redis::connection();
	$fcode 			=  $redis->hget('H_UserId_Cust_Map',$username .':fcode');
	$franchiesJson 	=  $redis->hget('H_Franchise_Mysql_DatabaseIP',$fcode);
	$servername 	=  $franchiesJson;
	//$servername 	=  "128.199.159.130";
	
	if (strlen($servername) > 0 && strlen(trim($servername) == 0)){
		return 'Ipaddress Failed !!!';
	}
	
	$usernamedb 	=  "root";
	$password 		=  "#vamo123";
	$dbname 		=  $fcode;

   	$userQuery 	    =  "DELETE FROM ScheduledReportMonthly WHERE userName='$username'";
	$grpQuery 		=  "DELETE FROM ScheduledReportMonthly WHERE userName='$username' AND groupName='$grpName'";
	$ex_Query 		=  ($grpName ? $grpQuery  : $userQuery);
    	
    log::info($ex_Query);

	try {

		$con = mysqli_connect($servername, $usernamedb, $password);

		if(!$con) {
	        
	        die('Could not connect:'.mysqli_connect_error());
	      	 	log::info("Connection failed: ");

	   	} else {

	   		$db_selected = mysqli_select_db($con, $dbname);

	   		if(!$db_selected) {
	   		  log::info('Database '.$dbname.' not exists...');
	   		} else {

	   		    log::info(' Connection Sucessfully ');
	   		    $con->query($ex_Query);
	   		return 'correct';
	       	}
	   	}

	   	$con->close();
	
	} catch (Exception $e) {
		
		log::info($e);
		return 'incorrect';
	}

}


}




