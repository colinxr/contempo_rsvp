<?php

	require('../config/config.php');
	require('../src/database.php');
	require('../src/email.php');

	if (isset($_POST['rsvp'])){

		//Decodes JSON object
		$str_json = json_decode($_POST['rsvp']);

		//Set variables from str_json
		$email     = $str_json->email;
		$firstName = $str_json->firstName;
		$lastName  = $str_json->lastName;
		$postal    = $str_json->postal;
		$verdict   = $str_json->action;

		if ($str_json->guestEmail != ''){
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
		$conn = dbConnect();

		$query = 'SELECT id FROM ' . DB_TABLE . ' WHERE EMAIL=?';

		if ($stmt = $conn->prepare($query)){
			$stmt->bind_param('s', $email);
			$stmt->execute();

			if (!$stmt->store_result()){
        echo 'Error Result = false';
      } else if ($stmt->num_rows == 0){
				if ($verdict == 'approve'){
					if ($hasGuest) {
	          // prepared SQL stmt to inster guest and plus one
	          $guest_query = 'INSERT INTO ' . DB_TABLE . '( email, firstName, lastName, postal,  guestFirstName, guestLastName, guestEmail )
	          VALUES (?,?,?,?,?,?,?)';

	          $rsvp_stmt = $conn->prepare($guest_query);

	          $rsvp_stmt->bind_param('sssssss', $email, $firstName, $lastName, $postal, $guestFirstName, $guestLastName, $guestEmail);
	        } else {
	          // prepared SQL stmt to insert guest
	          $single_query = 'INSERT INTO ' . DB_TABLE . '( email, firstName, lastName, postal)
	          VALUES (?,?,?,?)';

	          $rsvp_stmt = $conn->prepare($single_query);

	          $rsvp_stmt->bind_param('ssss', $email, $firstName, $lastName, $postal);
	        }

					$rsvp_stmt->execute();

					if ($rsvp_stmt->store_result()){
						echo json_encode($str_json->action);
						echo 'success';
						delete_unknown($conn, $email);

						$emailArgs = array (
									'email' => $email,
		        	'firstName' => $firstName,
		           'lastName' => $lastName
		        );

		        sendConfirmPm( $emailArgs );

						$rsvp_stmt->close();
					}
				} else if ($verdict === 'delete') {
					echo json_encode($str_json->action);
					delete_unknown($conn, $email);
					rejectEmailPM( $email );
					echo 'deleted';
				}
			} else {
				echo json_encode($str_json->action);
				echo 'email already in db';
			} // end of num_rows

			$stmt->close();
		} // end of $stmt = $conn->prepare($query)

		$conn-close();
}
;?>
