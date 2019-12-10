<?php

	require_once('./Connect.php');
	require_once('./DBfuncs.php');
	require __DIR__ . '/twilio-php-master/src/Twilio/autoload.php';
	use Twilio\Rest\Client;

	function insert_message_info($messageSID){
		
		$dbh = ConnectDB();
		
		$account_sid	= "ACCOUNT_SID_HERE";
		$auth_token		= "AUTH_TOKEN_HERE";

		$twilio = new Client($account_sid, $auth_token);

		$message = $twilio->messages($messageSID)->fetch();

		try{
			$query = 
			"INSERT INTO messageInfo (".
				"sid,".
				"date_created,".
				"date_sent,".
				"date_updated,".
				"from_number,".
				"to_number".
				") ".
			"VALUES (".
				"\"".$message->sid."\",".
				"\"".$message->dateCreated->format('D, d M Y H:i:s O')."\",".
				"\"".$message->dateSent->format('D, d M Y H:i:s O')."\",".
				"\"".$message->dateUpdated->format('D, d M Y H:i:s O')."\",".
				"\"".$message->from."\",".
				"\"".$message->to."\"".
			");";	
		}catch(PDOException $e){
			die('PDO Error inserting: '.$e->getMessage());
		}


	}

	function send_sms(
	$phoneNumber, $movieName, $theaterName, $theaterAddress, $showTime){
		$message = 
			"Hello,\n\t The address for ". $theaterName. " is at " .$theaterAddress. " and " .$movieName. " starts at " .$showTime. ". Enjoy!";
	
		$userNumber = '+1'.preg_replace("/[^0-9]/","",$phoneNumber);

		$account_sid	= "ACCOUNT_SID_HERE";
		$auth_token	= "AUTH_TOKEN_HERE";
		$twilio_number	= "PHONE_NUMBER_HERE";

		$twilio = new Client($account_sid, $auth_token);

		$client = $twilio->messages->create(
			$userNumber,
			array(
				'from' => $twilio_number,
				'body' => $message
			)
		);

		insert_message_info($client->sid);
	}

?>
