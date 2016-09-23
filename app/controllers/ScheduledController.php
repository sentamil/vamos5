<?php 
 /*
 * 
 */
 class ScheduledController extends \BaseController
 {
 	


/*
	Insert or update function in scheduling mail
*/	
public function reportScheduling(){
	/*
		Redis Connection
	*/
	$redis = Redis::connection ();
	
	$grpName = Input::get( 'groupName' );
	$username = Input::get ( 'userName' );
	// $vehicle = Input::get ( 'vehicle' );
	$reportList = Input::get ( 'reportList' );
	log::info($grpName);
	log::info($username);
	// $fromt = Input::get ( 'fromt' );
	// $mail = Input::get ( 'mail' );
	// $tot = Input::get ( 'tot' );
	$fcode = $redis->hget ( 'H_UserId_Cust_Map', $reportList[0][0] . ':fcode' );
	
	$franchiesJson 	= 	$redis->hget('H_Franchise_Mysql_DatabaseIP', $fcode);

	$servername = $franchiesJson;
	
	if (!$servername){
		$servername = "188.166.237.200";
	}
	
	$usernamedb = "root";
	$password = "#vamo123";
	$dbname = $fcode;
	
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
	display the mail sending function
*/
public function getValue(){
	
	$redis 			= 	Redis::connection ();
	$username 		= 	Input::get ( 'userName' );
	$grpName 		= 	Input::get( 'groupName' );
	$fcode 			= 	$redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
	$franchiesJson 	= 	$redis->hget('H_Franchise_Mysql_DatabaseIP', $fcode);
	$servername 	= 	$franchiesJson;
	$valueList 		= 	[];
	if (!$servername){
		$servername = "188.166.237.200";
	}
	
	$usernamedb = "root";
	$password 	= "#vamo123";
	$dbname 	= $fcode;
	log::info($username);
	try
	{
		$con 		= mysqli_connect($servername, $usernamedb, $password, $fcode);
   	
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

}