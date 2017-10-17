<?php

// *****
// Approves Email, if RSVP Type is 'Match'
// *****

  function checkEmail($email) {
    //Check email and compare to list, if match, grab ancillary information
    $row = 1;
    $emailMatch = false;

    global $gender;
    global $category;
    global $company;
    global $guestOf;

    // convert email string to all lowercase to make sure variable capitalization doesn't miss the email in wtf.csv
    $emailLower = strtolower( $email );

    if ( ( $handle = fopen( BASEPATH . '/wtf.csv', 'r') ) !== FALSE ) {
      while ( ( $data = fgetcsv( $handle, 1500, ',' ) ) !== FALSE ) {
        $row++;
        if ( $data[3] == $emailLower ) {

            $gender = $data[4];
          $category = $data[5];
           $company = $data[6];
           $guestOf = $data[7];

        $emailMatch = true;
        }
      }
      fclose( $handle );
      return true;
    }
  }

// *****
// Connects to Database
// *****

  function dbConnect() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($mysqli->connect_error) {
      die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
    }

    return $mysqli;
  }

// *****
// Inserts rsvp into Database
// *****

  function dbInsert ( $sqlArgs ) {

    global $rsvpType;

    // break out $sqlArgs array into more readable variables
         $email = $sqlArgs['email'];
     $firstName = $sqlArgs['firstName'];
      $lastName = $sqlArgs['lastName'];
        $postal = $sqlArgs['postal'];

        // if key value pair exists, set variable as the value
        $gender = isset( $sqlArgs['gender'] ) ? $sqlArgs['gender'] : 'null';
      $category = isset( $sqlArgs['category'] ) ? $sqlArgs['category'] : 'null';
       $company = isset( $sqlArgs['company'] ) ? $sqlArgs['company'] : 'null';
       $guestOf = isset( $sqlArgs['guestOf'] ) ? $sqlArgs['guestOf'] : 'null';

      $hasGuest = false;

      // Is the Entry bringing a guest? Create those variables if the key value pair exists
      if ( isset( $sqlArgs['hasGuest'] ) ) {
            $hasGuest = true;
      $guestFirstName = $sqlArgs['guestFirstName'];
       $guestLastName = $sqlArgs['guestLastName'];
          $guestEmail = $sqlArgs['guestEmail'];
      }

      // break out $sqlArgs array into more readable variables
          $email = $sqlArgs['email'];
      $firstName = $sqlArgs['firstName'];
       $lastName = $sqlArgs['lastName'];
         $postal = $sqlArgs['postal'];

       $hasGuest = false;

      // Is the Entry bringing a guest? Create those variables if the key value pair exists
      if ( isset( $sqlArgs['hasGuest'] ) ) {
            $hasGuest = true;
      $guestFirstName = $sqlArgs['guestFirstName'];
       $guestLastName = $sqlArgs['guestLastName'];
          $guestEmail = $sqlArgs['guestEmail'];

       // if entry is bringing a guest, set the key pair value in the array staffArgs() on line 170.
       $staffArgs['guestFirstName'] = $guestFirstName;
        $staffArgs['guestLastName'] = $guestLastName;
           $staffArgs['guestEmail'] = $guestEmail;
      }

    // Create connection
    $conn = dbConnect();

    // Query to check if email exists in Db Table
    $query = 'SELECT id FROM ' . DB_TABLE . ' WHERE EMAIL=?';

    if ($stmt = $conn->prepare($query)) {
      $stmt->bind_param('s', $email);
      $stmt->execute();

      if (!$stmt->store_result()){
        echo 'Error Result = false';
      } else if ( $stmt->num_rows == 0 ) { // if email is not in Db table
        if ($hasGuest) {
          // prepared SQL stmt to inster guest and plus one
          $guest_query = 'INSERT INTO ' . DB_TABLE . '( email, firstName, lastName, postal, gender, category, company, guestOf, guestFirstName, guestLastName, guestEmail )
          VALUES (?,?,?,?,?,?,?,?,?,?,?)';

          $rsvp_stmt = $conn->prepare($guest_query);

          $rsvp_stmt->bind_param('sssssssssss', $email, $firstName, $lastName, $postal, $gender, $category, $company, $guestOf, $guestFirstName, $guestLastName, $guestEmail);
        } else {
          // prepared SQL stmt to insert guest
          $single_query = 'INSERT INTO ' . DB_TABLE . '( email, firstName, lastName, postal, gender, category, company, guestOf)
          VALUES (?,?,?,?,?,?,?,?)';

          $rsvp_stmt = $conn->prepare($single_query);

          $rsvp_stmt->bind_param('ssssssss', $email, $firstName, $lastName, $postal, $gender, $category, $company, $guestOf);
        }

        $rsvp_stmt->execute();

        if ($rsvp_stmt->store_result()){
          if ($rsvpType === 'match' || $rsvpType === 'open'){
            $path = '/_inc/alerts/conf-msg.html'; //
            $alert = file_get_contents( BASEPATH . $path );
            echo $alert;

            //	On successful add to db, send email
            $emailArgs = array (
                  'email' => $email,
              'firstName' => $firstName,
               'lastName' => $lastName
            );

            sendConfirmPm( $emailArgs );
          } else if ($rsvpType === 'capacity'){
            $path = '/_inc/alerts/capacity-msg.html'; //
            $alert = file_get_contents( BASEPATH . $path );
            echo $alert;

            //	On successful add to db, send email
            $staffArgs = array (
                  'email' => $email,
              'firstName' => $firstName,
               'lastName' => $lastName,
                 'postal' => $postal
            );

            sendStaffEmailPM( $staffArgs );
          }

          $rsvp_stmt->close();
        } else {
          echo 'Error: ' . $rsvp_stmt->error . '<br>' . $conn->error;
        } // end of $conn->query($sql) === TRUE
      } else { // if email is alreayd in the DB (user has already registered)
        $path = '/_inc/alerts/reg-msg.html';
        $alert = file_get_contents( BASEPATH . $path );
        echo $alert;
      }
      //already registered message
      $stmt->close();
    }
  $conn->close();
}// end of dbConnect();

// *****
// Inserts Unknown RSVP into Unknown Table
// *****

  function dbUnknwnr( $sqlArgs ) {

    // break out $sqlArgs array into more readable variables
         $email = $sqlArgs['email'];
     $firstName = $sqlArgs['firstName'];
      $lastName = $sqlArgs['lastName'];
        $postal = $sqlArgs['postal'];

      $hasGuest = false;

      // Is the Entry bringing a guest? Create those variables if the key value pair exists
      if ( isset( $sqlArgs['hasGuest'] ) ) {
              $hasGuest = true;
        $guestFirstName = $sqlArgs['guestFirstName'];
         $guestLastName = $sqlArgs['guestLastName'];
            $guestEmail = $sqlArgs['guestEmail'];

         // if entry is bringing a guest, set the key pair value in the array staffArgs() on line 170.
          $staffArgs['guestFirstName'] = $guestFirstName;
           $staffArgs['guestLastName'] = $guestLastName;
              $staffArgs['guestEmail'] = $guestEmail;
      }

    $conn = dbConnect();

    // CHECK IF EMAIL ALREADY IN DB
    $query = 'SELECT id FROM ' . DB_TABLE . ' WHERE EMAIL=?';

    if ($stmt = $conn->prepare($query)) {
      $stmt->bind_param('s', $email);
      $stmt->execute();

      if (!$stmt->store_result()){
        echo 'Error Result = false';
      } else if ($stmt->num_rows == 0) {
        if ($hasGuest){
          $unknown_query = 'INSERT INTO ' . UNKNWNR . ' ( email, firstName, lastName, postal, guestFirstName, guestLastName, guestEmail )
          VALUES (?,?,?,?,?,?,?)';

          $unknown_stmt = $conn->prepare($unknown_query);

          $unknown_stmt->bind_param('sssssss', $email, $firstName, $lastName, $postal, $guestFirstName, $guestLastName, $guestEmail);
        } else {
          $unknown_query= 'INSERT INTO ' . UNKNWNR . ' ( email, firstName, lastName, postal )
          VALUES (?,?,?,?)';

          $unknown_stmt = $conn->prepare($unknown_query);

          $unknown_stmt->bind_param('ssss', $email, $firstName, $lastName, $postal);
        }

      $unknown_stmt->execute();

      if ($unknown_stmt->store_result()){
        $path = '/_inc/alerts/unknown-msg.html'; // confirmation message
        $alert = file_get_contents( BASEPATH . $path );
        echo $alert;

        //	On successful add to db, send email
        $staffArgs = array (
              'email' => $email,
          'firstName' => $firstName,
           'lastName' => $lastName,
             'postal' => $postal
        );

        sendStaffEmailPM( $staffArgs );

        $unknown_stmt->close();
      } else {
        echo 'Error: ' . $unknown_stmt->error . '<br>' . $conn->error;
      }
    } else {
      // if email is alreayd in the DB (user has already registered)
      $path = '/_inc/alerts/reg-msg.html';
      $alert = file_get_contents( BASEPATH . $path );
      echo $alert;
    }
    //already registered message
    $stmt->close();
    }
  $conn->close();
}// end of dbConnect();

// *****
// Removes unknown RSVP from unknown table
// *****

  function delete_unknown( $conn, $email ){
  	// Make sure entry is in unknown DB

    $query = 'SELECT ID FROM ' . UNKNWNR . ' WHERE EMAIL =?';

    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();

    if ($stmt->store_result()){
      if ($stmt->num_rows > 0){
        $del_query = 'DELETE FROM ' . UNKNWNR . ' WHERE EMAIL=?';

        $del_stmt = $conn->prepare($del_query);
        $del_stmt->bind_param('s', $email);
      } else {
        echo 'not in unknown db';
      }
    } else {
      echo 'error ' . $del_stmt->error;
    }

    $del_stmt->execute();

    if ($del_stmt->store_result()){
      echo 'deleted from Unknown RSVPS';

      rejectEmailPm($email);

      $del_stmt->close();
    }

    $stmt->close();
  } // End of delete_unknown();

// *****
// Show Entries in table, either RSVPs or Unknown RSVPS
// *****

  function viewResults( $dbTable ) {
    // Create connection
    $conn = dbConnect();

    $query = 'SELECT id, firstName, lastName, email, postal, guestFirstName, guestLastName, guestEmail FROM ' . $dbTable;

    if ($stmt = $conn->prepare($query)) {
      $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);

      $stmt->store_result();

      $stmt->bind_result($id, $firstName, $lastName, $email, $postal, $guestFirstName, $guestLastName, $guestEmail);

      if ($stmt->num_rows > 0) { ?>
        <table class="table table-striped" id="rsvp-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Postal</th>
              <th>Guest Name</th>
              <th>Guest Email</th>
              <?php if ($dbTable === UNKNWNR) { ?>
                <th>Approve/Deny</th>
              <? } ?>
            </tr>
          </thead>
          <tbody>

        <?php
          while ($stmt->fetch()) {
            echo '<tr id="'. $id . '">';

            echo '<td>' . $id . '</td>';
            echo '<td id="firstName" class="value">' . $firstName . "</td>";
            echo '<td id="lastName" class="value">' . $lastName . '</td>';
            echo '<td id="email" class="value">' . $email . '</td>';
            echo '<td id="postal" class="value">' . $postal . '</td>';
            echo '<td id="guestName" class="value">' . $guestFirstName . ' ' . $guestLastName . '</td>';
            echo '<td id="guestEmail" class="value">' . $guestEmail . '</td>';

            if ($dbTable === UNKNWNR) {
              echo '<td><input type="button" id="'. $id .'" class="btn btn-link approve" value="Approve" />';
              echo '<input type="button" class="btn btn-link deny" value="Deny" /></td>';
            }
          echo '</tr>';
        }
        echo '</tbody></table>';
      } else {// If table has no unknown RSVPs display a message
        echo 'No unknown RSVPs right now. Check back later.';
      }
      $stmt->close();
    }
    $conn->close();
  }

;?>
