<?php

  function dbConnect ( $sqlArgs ) {

    // break out $sqlArgs array into more readable variables
         $email = $sqlArgs["email"];
     $firstName = $sqlArgs["firstName"];
      $lastName = $sqlArgs["lastName"];
        $postal = $sqlArgs["postal"];

        // if key value pair exists, set variable as the value
        $gender = isset($sqlArgs["gender"]) ? $sqlArgs["gender"] : 'null';
      $category = isset($sqlArgs["category"]) ? $sqlArgs["category"] : 'null';
       $company = isset($sqlArgs["company"]) ? $sqlArgs["company"] : 'null';
       $guestOf = isset($sqlArgs["guestOf"]) ? $sqlArgs["guestOf"] : 'null';
      $hasGuest = false;

      // Is the Entry bringing a guest? Create those variables if the key value pair exists
      if ( isset( $sqlArgs["hasGuest"] ) ) {
           $hasGuest = true;
          $guestName = $sqlArgs["guestName"];
         $guestEmail = $sqlArgs["guestEmail"];
        }

    // Create connection

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Check connection

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // CHECK IF EMAIL IS IN DB
    $result = $conn->query("SELECT id FROM " . DB_TABLE . " WHERE EMAIL = '$email'");

      if ($result === false) {

        echo "Error";

      } else if($result->num_rows == 0) {

        //Add to mysql database
          if ( $hasGuest ) {

            $sql = "INSERT INTO " . DB_TABLE . " ( email, firstName, lastName, postal, gender, category, company, guestOf, guestName, guestEmail )
            VALUES (
              `$email`, `$firstName`,`$lastName`,`$postal`, `$gender`, `$category`, `$company`, `$guestOf`, `$guestName`, `$guestEmail`)";

          } else {

            $sql = "INSERT INTO" . " " . DB_TABLE . " " . "(
                email, firstName, lastName,  postal, gender, category, company, guestOf )
            VALUES (`$email`, `$firstName`, `$lastName`, `$postal`, `$gender`, `$category`, `$company`, `$guestOf`)";
          }

      if ( $conn->query($sql) === TRUE ) {

        echo "<p><strong>". $firstName .", thank you for your RSVP.</strong></p>
            <p>We've added you to the guest list and you will be receiving your confirmation email shortly.</p><br/>";


         // Calendar Event Info
         echo '<span class="addtocalendar atc-style-blue">
              <var class="atc_event">
                <var class="atc_date_start">2017-05-4 18:00:00</var>
                <var class="atc_date_end">2017-04-9 22:00:00</var>
                <var class="atc_timezone">America/Toronto</var>
                <var class="atc_title">Sharp The Book For Men SS17 Launch Event </var>
                <var class="atc_location">505 Richmond St West</var>
                <var class="atc_organizer">Sharp Magazine</var>
                <var class="atc_organizer_email">event@sharpmagazine.com.com</var>
             </var>
          </span>';

          echo "<div class='confirmation--footer'> <a href='http://www.sharpmagazine.com' target='_top'><img src='imgs/sharp_logo_black.svg'></a>
              </div>";

        //	On successful add to db, send email

        $emailArgs = array (
              "email" => $email,
          "firstName" => $firstName,
           "lastName" => $lastName
        );

        sendEmail( $emailArgs );

    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

  } else {

    echo "<p><strong>Sorry ". $firstName .", it looks like you've already registered. Please check your inbox for your confirmation email.</strong></p>
      <p>If you have any questions, you can contact <a href='mailto:event@sharpmagazine.com'>event@sharpmagazine.com</a>.</p>";

      echo "<div class='confirmation--footer'> <a href='http://www.sharpmagazine.com' target='_top'><img src='imgs/sharp_logo_black.svg'></a>
          </div>";
  }

  $conn->close();

} // end of dbConnect();

  function dbUnknwr( $sqlArgs ) {

    // break out $sqlArgs array into more readable variables
         $email = $sqlArgs["email"];
     $firstName = $sqlArgs["firstName"];
      $lastName = $sqlArgs["lastName"];
        $postal = $sqlArgs["postal"];

      $hasGuest = false;

      // Is the Entry bringing a guest? Create those variables if the key value pair exists
      if ( isset( $sqlArgs["hasGuest"] ) ) {
           $hasGuest = true;
          $guestName = $sqlArgs["guestName"];
         $guestEmail = $sqlArgs["guestEmail"];

         // if entry is bringing a guest, set the key pair value in the array staffArgs() on line 170.
         $staffArgs["guestName"] = $guestName;
         $staffArgs["guestEmail"] = $guestEmail;
        }

    $unknownConn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Check connection

    if ($unknownConn->connect_error) {
        die("Connection failed: " . $unknownConn->connect_error);
    }

      // CHECK IF EMAIL ALREADY IN DB
      $unknownResult = $unknownConn->query("SELECT id FROM " . UNKNWNR . " WHERE EMAIL = `$email`");

        if ($unknownResult === false) {

          echo "Error";

        } else if($unknownResult->num_rows == 0) {

          //Add to mysql database
            if ( $hasGuest ) {

              $sql = "INSERT INTO " . UNKNWNR . " ( email, firstName, lastName,  postal, guestName, guestEmail )
              VALUES (`$email`, `$firstName`, `$lastName`, `$postal`, `$guestName`, `$guestEmail`)";

            } else {

              $sql = "INSERT INTO " . UNKNWNR . " ( email, firstName, lastName,  postal )
              VALUES (`$email`, `$firstName`, `$lastName`, `$postal`)";
            }

        if ( $unknownConn->query($sql) === TRUE ) {

          echo "<p><strong>Thank you for submitting your RSVP.</strong></p>
            <p>You'll be receiving a confirmation email shortly.</p>
            <p>If you have any questions, please contact <a href='mailto:event@sharpmagazine.com'>event@sharpmagazine.com</a>.</p>";

          echo "<div class='confirmation--footer'> <a href='http://www.sharpmagazine.com' target='_top'><img src='imgs/sharp_logo_black.svg'></a>
            </div>";

        //	On unknown entry, send staff email

        $staffArgs = array (
          "email" => $email,
          "firstName" => $firstName,
          "lastName" => $lastName,
          "postal" => $postal,
        );

      sendStaffEmail( $staffArgs );

    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

  } else {

    echo "<p><strong>Sorry ". $firstName .", it looks like you've already registered. Please check your inbox for your confirmation email.</strong></p>
      <p>If you have any questions, you can contact <a href='mailto:event@sharpmagazine.com'>event@sharpmagazine.com</a>.</p>";

      echo "<div class='confirmation--footer'> <a href='http://www.sharpmagazine.com' target='_top'><img src='imgs/sharp_logo_black.svg'></a>
          </div>";
  }

    $unknownConn->close();

} // end of dbUnknwr();

function delete_unknown($conn, $email){

	// Make sure entry is in unknown DB
	$delete = $conn->query("SELECT ID FROM " . UNKNWNR . " WHERE EMAIL = `$email`");

		if ($delete === false) {

			echo "Error";

		} else if ($delete->num_rows > 0) {

			$sql = "DELETE FROM " . UNKNWNR . " WHERE EMAIL = `$email`";

		}

		if ($conn->query($sql) === TRUE) {

			echo "Deleted from Unknown RSVPS";

		}
} // End of delete_unknown();


function sendEmail( $emailArgs ) {

	$subject = SUBJECT_LINE;

	$message = file_get_contents( __DIR__ . '/email.html' );

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To: ' . $emailArgs["firstName"] .' '. $emailArgs["lastName"] .' <' . $emailArgs["email"] . '>' . "\r\n";
	$headers .= 'From: ' . EVENT_HOSTS . '<' . EMAIL_FROM . '>' . "\r\n";

	// Mail it
	mail( $emailArgs["email"], $subject, $message, $headers);

} // end of sendEmail();

function sendStaffEmail( $staffArgs ) {

	//$mailTo = 'event@sharpmagazine.com';

  $mailTo = 'colin.rabyniuk@contempomedia.com';

	$subject = STAFF_SUBJECT;

	$message = '
		<html>
		<head>
		  <title>Who is this person?</title>
		</head>
		<body>
			<p>Privet Elena!</p>
		    <p>Somebody who isn\'t on the invite list just RSVP\'d for the BFM Party. This is their info:</p><br/>

		  	          <p>Name: '. $staffArgs["firstName"] . ' ' . $staffArgs["lastName"] .' </p>
		  	         <p>Email: '. $staffArgs["email"] .'</p>
		  	        <p>Postal: '. $staffArgs["postal"] .'</p>
		  	      <p>Plus One: '. $staffArgs["guestName"] .'</p>
		  	<p>Plus One Email: '. $staffArgs["lastName"] .'</p>

		</body>
		</html>
	';

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers

	//$headers .= 'To: Elena Okulova <event@sharpmagazine.com>' . "\r\n";

  $headers .= 'To: Colin Rabyniuk <colin.rabyniuk@contempomedia.com>' . "\r\n";

	$headers .= 'From: Contempo BFM Bot <' . STAFF_EMAIL_FROM . '>' . "\r\n";

	// Mail it
	mail( $mailTo, $subject, $message, $headers );

} // End of sendStaffEmail();

function rejectEmail( $email, $firstName, $lastName ) {

	$subject = SUBJECT_LINE;

  $message = file_get_contents( __DIR__ . '/email-reject.html' );

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To: ' . $firstName .' '. $lastName .' <' . $email . '>' . "\r\n";
	$headers .= 'From: ' . EVENT_HOSTS . '<' . EMAIL_FROM . '>' . "\r\n";

	// Mail it
	mail($email, $subject, $message, $headers);

}

?>
