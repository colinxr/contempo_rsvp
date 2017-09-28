<?php

  function dbConnect ( $sqlArgs ) {
    // Create connection

    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    // Check connection

    if ( $conn->connect_error ) {
      die( "Connection failed: " . $conn->connect_error );
    }

    // break out $sqlArgs array into more readable variables
         $email = $conn->real_escape_string($sqlArgs["email"]);
     $firstName = $conn->real_escape_string($sqlArgs["firstName"]);
      $lastName = $conn->real_escape_string($sqlArgs["lastName"]);
        $postal = $conn->real_escape_string($sqlArgs["postal"]);

        // if key value pair exists, set variable as the value
        $gender = isset( $sqlArgs["gender"] ) ? $conn->real_escape_string($sqlArgs["gender"]) : 'null';
      $category = isset( $sqlArgs["category"] ) ? $conn->real_escape_string($sqlArgs["category"]) : 'null';
       $company = isset( $sqlArgs["company"] ) ? $conn->real_escape_string($sqlArgs["company"]) : 'null';
       $guestOf = isset( $sqlArgs["guestOf"] ) ? $conn->real_escape_string($sqlArgs["guestOf"]) : 'null';

      $hasGuest = false;

      // Is the Entry bringing a guest? Create those variables if the key value pair exists
      if ( isset( $sqlArgs["hasGuest"] ) ) {
              $hasGuest = true;
        $guestFirstName = $conn->real_escape_string($sqlArgs["guestFirstName"]);
         $guestLastName = $conn->real_escape_string($sqlArgs["guestLastName"]);
            $guestEmail = $conn->real_escape_string($sqlArgs["guestEmail"]);
        }


    // CHECK IF EMAIL IS IN DB
    $result = $conn->query( "SELECT id FROM " . DB_TABLE . " WHERE EMAIL = '$email'" );

    if ( $result === false ) {
      echo "Error Result = false";
    } else if( $result->num_rows == 0 ) {
      //Add to mysql database
      if ( $hasGuest ) {
        $sql = "INSERT INTO " . DB_TABLE . " ( email, firstName, lastName, postal, gender, category, company, guestOf, guestFirstName, guestLastName, guestEmail )
        VALUES (
        '$email', '$firstName','$lastName','$postal', '$gender', '$category', '$company', '$guestOf', '$guestFirstName', '$guestLastName', '$guestEmail')";
      } else {
        $sql = "INSERT INTO " . DB_TABLE . " ( email, firstName, lastName,  postal, gender, category, company, guestOf )
        VALUES ( '$email', '$firstName', '$lastName', '$postal', '$gender', '$category', '$company', '$guestOf' )";
      }

      if ( $conn->query($sql) === TRUE ) {
        $path = '/_inc/alerts/conf-msg.html';
        $alert = file_get_contents( BASEPATH . $path );
        echo $alert;

        //	On successful add to db, send email
        $emailArgs = array (
              "email" => $email,
          "firstName" => $firstName,
           "lastName" => $lastName
        );

        sendConfirmPm( $emailArgs );
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    } // end of $conn->query($sql) === TRUE
  } else { // if email is alreayd in the DB (user has already registered)
    $path = '/_inc/alerts/reg-msg.html';
    $alert = file_get_contents( BASEPATH . $path );
    echo $alert;
  }
  $conn->close();
} // end of dbConnect();

  function dbUnknwr( $sqlArgs ) {

    $unknownConn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    // Check connection
    if ( $unknownConn->connect_error ) {
      die( "Connection failed: " . $unknownConn->connect_error );
    }

    // break out $sqlArgs array into more readable variables
         $email = $unknownConn->real_escape_string($sqlArgs["email"]);
     $firstName = $unknownConn->real_escape_string($sqlArgs["firstName"]);
      $lastName = $unknownConn->real_escape_string($sqlArgs["lastName"]);
        $postal = $unknownConn->real_escape_string($sqlArgs["postal"]);

      $hasGuest = false;

      // Is the Entry bringing a guest? Create those variables if the key value pair exists
      if ( isset( $sqlArgs["hasGuest"] ) ) {
              $hasGuest = true;
        $guestFirstName = $unknownConn->real_escape_string($sqlArgs["guestFirstName"]);
         $guestLastName = $unknownConn->real_escape_string($sqlArgs["guestLastName"]);
            $guestEmail = $unknownConn->real_escape_string($sqlArgs["guestEmail"]);

         // if entry is bringing a guest, set the key pair value in the array staffArgs() on line 170.
          $staffArgs["guestFirstName"] = $guestFirstName;
           $staffArgs["guestLastName"] = $guestLastName;
              $staffArgs["guestEmail"] = $guestEmail;
      }

    // CHECK IF EMAIL ALREADY IN DB
    $unknownResult = $unknownConn->query( "SELECT id FROM " . UNKNWNR . " WHERE EMAIL = '$email'" );

      if ( $unknownResult === false ) {
        echo "Error: Uknownr";
      } else if( $unknownResult->num_rows == 0 ) {
        //Add to mysql database
        if ( $hasGuest ) {
          $sql = "INSERT INTO " . UNKNWNR . " ( email, firstName, lastName, postal, guestFirstName, guestLastName, guestEmail )
          VALUES ( '$email', '$firstName', '$lastName', '$postal', '$guestFirstName', '$guestLastName', '$guestEmail' )";
        } else {
          $sql = "INSERT INTO " . UNKNWNR . " ( email, firstName, lastName,  postal )
          VALUES ('$email', '$firstName', '$lastName', '$postal')";
        }

        if ( $unknownConn->query($sql) === TRUE ) {
          $path = '../_inc/alerts/unknown-msg.html';
          $alert = file_get_contents( BASEPATH . $path );
          echo $alert;

        //	On unknown entry, send staff email
        $staffArgs = array (
              "email" => $email,
          "firstName" => $firstName,
           "lastName" => $lastName,
             "postal" => $postal,
     "guestFirstName" => $guestFirstName,
      "guestLastName" => $guestLastName
        );
        sendStaffEmail( $staffArgs );
      } else {
        echo "Error: " . $sql . "<br>" . $unknownConn->error;
      }
    } else {
      $path = '/_inc/alerts/reg-msg.html';
      $alert = file_get_contents( BASEPATH . $path );
      echo $alert;
    }
    $unknownConn->close();
  } // end of dbUnknwr();

function delete_unknown( $conn, $email ){
	// Make sure entry is in unknown DB
	$delete = $conn->query( "SELECT ID FROM " . UNKNWNR . " WHERE EMAIL = '$email'" );

		if ( $delete === false ) {
			echo "Error";
		} else if ( $delete->num_rows > 0 ) {
			$sql = "DELETE FROM " . UNKNWNR . " WHERE EMAIL = '$email'";
		}

		if ( $conn->query($sql) === TRUE ) {
			echo "Deleted from Unknown RSVPS";
		}
  } // End of delete_unknown();
?>
