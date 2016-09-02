<?php 
 /**
 * 
 */
 class ScheduledController extends \BaseController
 {
 	
 	// function __construct(argument)
 	// {
 	// 	# code...
 	// }
public function reportScheduling(){
	/*
		Redis Connection
	*/
	$redis = Redis::connection ();
	
	$username = Input::get ( 'userName' );
	$vehicle = Input::get ( 'vehicle' );
	$report = Input::get ( 'report' );
	$fromt = Input::get ( 'fromt' );
	$mail = Input::get ( 'mail' );
	$tot = Input::get ( 'tot' );
	$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
	
	$franchiesJson 	= 	$redis->hget('H_Franchise_Mysql_DatabaseIP', $fcode);

	// $franchies 	=	json_decode($franchiesJson,true);
	// if(isset($franchies['availableLincence'])==1)
	/*
		 Mysql Connection
	*/

	$servername = $franchiesJson;
	
	if (!$servername){
		$servername = "188.166.237.200";
	}
	
	$usernamedb = "root";
	$password = "#vamo123";
	$dbname = $fcode;
	
	$conn = mysqli_connect($servername, $usernamedb, $password, $dbname);
   
   	if( !$conn ) {
    	die('Could not connect: ' . mysql_error());
      	log::info("Connection failed: ");
   	} else {
   		
   		$results = $conn->query("SELECT * FROM ScheduledReport");
   		/*
			Create table
   		*/
		$create		= 	"CREATE TABLE IF NOT EXISTS ScheduledReport (id int NOT NULL AUTO_INCREMENT PRIMARY KEY, vehicleId TEXT NOT NULL, fromTime int , toTime int, email TEXT, reports TEXT ,fCode TEXT, userName TEXT,status TEXT)";

		$insert 	= 	"INSERT IGNORE INTO ScheduledReport (vehicleId, fromTime, toTime, email, reports, fcode, userName, status)VALUES ('$vehicle', $fromt,$tot,'$mail','$report','$fcode', '$username','inserted')";

		$update 	=	"UPDATE ScheduledReport SET fromTime='$fromt', toTime='$tot', email='$mail', reports='$report' WHERE vehicleId='$vehicle' AND userName='$username'";

   		$conn->query($create);
   			
   		$sql	=	"SELECT * FROM  ScheduledReport WHERE vehicleId='$vehicle' AND userName='$username'";
	   	$result =	$conn->query($sql);
	   	
	   	($result->num_rows > 0) ? $conn->query($update) : $conn->query($insert);

	   	log::info(' Sucessfully inserted/updated !!! ');
				// ;

		// } else {
		// 	;
				// log::info(' Sucessfully update !!! ');
		// }
	
   	// }
   	$conn->close();
	return 'correct';
 	}
 }
}