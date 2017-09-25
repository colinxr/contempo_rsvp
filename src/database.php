<?php

  function dbConnect ( $sqlArgs ) {

    // break out $sqlArgs array into more readable variables
         $email = $sqlArgs["email"];
     $firstName = $sqlArgs["firstName"];
      $lastName = $sqlArgs["lastName"];
        $postal = $sqlArgs["postal"];

        // if key value pair exists, set variable as the value
        $gender = isset( $sqlArgs["gender"] ) ? $sqlArgs["gender"] : 'null';
      $category = isset( $sqlArgs["category"] ) ? $sqlArgs["category"] : 'null';
       $company = isset( $sqlArgs["company"] ) ? $sqlArgs["company"] : 'null';
       $guestOf = isset( $sqlArgs["guestOf"] ) ? $sqlArgs["guestOf"] : 'null';

      $hasGuest = false;

      // Is the Entry bringing a guest? Create those variables if the key value pair exists
      if ( isset( $sqlArgs["hasGuest"] ) ) {
              $hasGuest = true;
        $guestFirstName = $sqlArgs["guestFirstName"];
         $guestLastName = $sqlArgs["guestLastName"];
            $guestEmail = $sqlArgs["guestEmail"];
        }

    // Create connection

    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    // Check connection

    if ( $conn->connect_error ) {
      die( "Connection failed: " . $conn->connect_error );
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
        $sql = "INSERT INTO" . " " . DB_TABLE . " " . "( email, firstName, lastName,  postal, gender, category, company, guestOf )
        VALUES ( '$email', '$firstName', '$lastName', '$postal', '$gender', '$category', '$company', '$guestOf' )";
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

          echo "<div class='confirmation--footer'><img src='imgs/sharp_logo_black.svg'>
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
    } // end of $conn->query($sql) === TRUE
  } else { // if email is alreayd in the DB (user has already registered)
    echo "<p><strong>Sorry ". $firstName .", it looks like you've already registered. Please check your inbox for your confirmation email.</strong></p>
      <p>If you have any questions, you can contact <a href='mailto:event@sharpmagazine.com'>event@sharpmagazine.com</a>.</p>";
    echo "<div class='confirmation--footer'><img src='imgs/sharp_logo_black.svg'>
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
        $guestFirstName = $sqlArgs["guestFirstName"];
         $guestLastName = $sqlArgs["guestLastName"];
            $guestEmail = $sqlArgs["guestEmail"];

         // if entry is bringing a guest, set the key pair value in the array staffArgs() on line 170.
          $staffArgs["guestFirstName"] = $guestFirstName;
           $staffArgs["guestLastName"] = $guestLastName;
              $staffArgs["guestEmail"] = $guestEmail;
      }

    $unknownConn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    // Check connection
    if ( $unknownConn->connect_error ) {
      die( "Connection failed: " . $unknownConn->connect_error );
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
          echo "<p><strong>Thank you for submitting your RSVP.</strong></p>
            <p>You'll be receiving a confirmation email shortly.</p>
            <p>If you have any questions, please contact <a href='mailto:event@sharpmagazine.com'>event@sharpmagazine.com</a>.</p>";
          echo "<div class='confirmation--footer'><img src='imgs/sharp_logo_black.svg'>
            </div>";

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
      echo "<p><strong>Sorry ". $firstName .", it looks like you've already registered. Please check your inbox for your confirmation email.</strong></p>
      <p>If you have any questions, you can contact <a href='mailto:event@sharpmagazine.com'>event@sharpmagazine.com</a>.</p>";
      echo "<div class='confirmation--footer'><img src='imgs/sharp_logo_black.svg'>
          </div>";
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
