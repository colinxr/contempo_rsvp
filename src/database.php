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
            while ($stmt->fetch()) { ?>
              <tr id="<?php echo $id ;?>">
                <td><?php echo $id ;?></td>
                <td id="firstName" class="value"><?php echo $id; ?></td>
                <td id="lastName" class="value"><?php echo $lastName; ?></td>
                <td id="email" class="value"><?php echo $email; ?></td>
                <td id="postal" class="value"><?php echo $postal; ?></td>
                <td id="guestName" class="value"><?php echo $guestFirstName . ' ' . $guestLastName; ?></td>
                <td id="guestEmail" class="value"><?php echo $guestEmail; ?></td>

              <?php if ($dbTable === UNKNWNR) { ?>
                <td><input type="button" id="<?php echo $id; ?>" class="btn btn-link approve" value="Approve" />
                <input type="button" class="btn btn-link deny" value="Deny" /></td>
              <?php } ?>
            </tr>
            <?php } ?>
            </tbody></table>
      <?php } else {// If table has no unknown RSVPs display a message
        echo 'No unknown RSVPs right now. Check back later.';
      }
      $stmt->close();
    }
    $conn->close();
  }

  // *****
  // Generate CSV file from DB
  // *****

  function download_results($dbTable){
    $conn = dbConnect();
    $query = 'SELECT * from ' . $dbTable . ' ORDER BY ID DESC';
    $result = $conn->query($query);

    if ($result === false) {
      die('Could not fetch records');
    } else {
      $num_fields = mysqli_num_fields($result);
      $headers = array();

      while ($field_info = mysqli_fetch_field($result)){
        $headers[] = $field_info->name;
      }

      $output = fopen('php://output', 'w');

      if ( $output && $result ){
        fputcsv($output, $headers);

        while ($row = $result->fetch_array(MYSQLI_NUM)) {
          fputcsv($output, array_values($row));
        }

        fclose($output);
        readfile($output);
        unlink($output);
        exit();
      }
    }
  }
;?>
