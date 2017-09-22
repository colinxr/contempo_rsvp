<?php

	require("../_inc/config.php");
	require("../src/database.php");
	require("../src/email.php");

	if (isset($_POST["rsvp"])){

		//Decodes JSON object
		$str_json = json_decode($_POST["rsvp"]);

		//Set variables from str_json
		$email     = $str_json->email;
		$firstName = $str_json->firstName;
		$lastName  = $str_json->lastName;
		$postal    = $str_json->postal;
		$verdict   = $str_json->action;

		if ($str_json->guestEmail != ""){
			$hasGuest = true;
		} else {
			$hasGuest = false;
		}

		if ($hasGuest){
			$guestFirstName  = $str_json->guestFirstName;
			$guestLastName  = $str_json->guestLastName;
		  $guestEmail = $str_json->guestEmail;
		}

		//Create DB connection
		$conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

		//Check connection
		if ( $conn->connect_error ){
			die ( "Connection failed: " . $conn->connect_error) ;
		}

		// CHECK IF EMAIL ALREADY IN DB
		$result = $conn->query( "SELECT id FROM " . DB_TABLE . " WHERE EMAIL = '$email'" ) ;

		if ($result === false){
			echo "Error";
		} else if( $result->num_rows == 0 ){
			//If BTN action = approved
			if ( $verdict == "approve" ){
				//Add to mysql database
				if ( $hasGuest ){
					$sql = "INSERT INTO " . DB_TABLE . " ( email, firstName, lastName, postal, guestFirstName, guestLastName, guestEmail )
					VALUES ( '$email', '$firstName', '$lastName', '$postal', '$guestFirstName', '$guestLastName', '$guestEmail' )";
				} else {
					$sql = "INSERT INTO " . DB_TABLE . " ( email, firstName, lastName, postal )
					VALUES ( '$email', '$firstName', '$lastName', '$postal' )";
				}

				if ($conn->query($sql) === TRUE){
					echo json_encode($str_json->action);
					echo "success";
					delete_unknown($conn, $email);
					$emailArgs = array (
								"email" => $email,
	        	"firstName" => $firstName,
	           "lastName" => $lastName
	        );

	        sendEmail( $emailArgs );
				}
		} else if ($verdict === "delete") {
			echo json_encode($str_json->action);
			delete_unknown($conn, $email);
			rejectEmail( $email, $firstName, $lastName );
			echo "deleted";
		}
		$conn->close();
	}
}
;?>
