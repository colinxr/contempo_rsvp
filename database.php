<?php

// *****
// Approves Email, if RSVP Type is 'Match'
// *****

  function checkEmail($email){
    //Check email and compare to list, if match, grab ancillary information
    $row = 1;
    $emailMatch = false;

    global $gender;
    global $category;
    global $company;
    global $guestOf;

    // convert email string to all lowercase to make sure variable capitalization doesn't miss the email in wtf.csv
    $emailLower = strtolower( $email );

    if ( ( $handle = fopen( BASEPATH . '/wtf.csv', 'r') ) !== FALSE ){
      while ( ( $data = fgetcsv( $handle, 1500, ',' ) ) !== FALSE ){
        $row++;
        if ( $data[3] == $emailLower ){
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

  function dbConnect(){
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($mysqli->connect_error){
      die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
    }

    return $mysqli;
  }

// *****
// Inserts rsvp into Database
// *****

  function dbInsert($rsvp){

    global $rsvpType;

    // if key value pair exists, set variable as the value
    $gender = property_exists('Rsvp', 'gender') ? $rsvp->gender : '';
    $category = property_exists('Rsvp', 'category') ? $rsvp->category : '';
    $company = property_exists('Rsvp', 'company') ? $rsvp->company : '';
    $guestOf = property_exists('Rsvp', 'guestOf') ? $rsvp->guestOf : '';

    // Create connection
    $conn = dbConnect();

    // Query to check if email exists in Db Table
    $query = 'SELECT id FROM ' . DB_TABLE . ' WHERE EMAIL=?';

    if ($stmt = $conn->prepare($query)){
      $stmt->bind_param('s', $rsvp->email);
      $stmt->execute();

      if (!$stmt->store_result()){
        echo 'Error Result = false';
      } else if ($stmt->num_rows == 0){ // if email is not in Db table
        if ($rsvp->hasGuest == true){
          // prepared SQL stmt to inster guest and plus one
          $guest_query = 'INSERT INTO ' . DB_TABLE . '(email, firstName, lastName, postal, gender, category, company, guestOf, guestFirstName, guestLastName, guestEmail)
          VALUES (?,?,?,?,?,?,?,?,?,?,?)';

          $rsvp_stmt = $conn->prepare($guest_query);

          $rsvp_stmt->bind_param('sssssssssss', $rsvp->email, $rsvp->firstName, $rsvp->lastName, $rsvp->postal, $gender, $category, $company, $guestOf, $rsvp->guestFirstName, $rsvp->guestLastName, $rsvp->guestEmail);
        } else {
          // prepared SQL stmt to insert guest
          $single_query = 'INSERT INTO ' . DB_TABLE . '(email, firstName, lastName, postal, gender, category, company, guestOf)
          VALUES (?,?,?,?,?,?,?,?)';

          $rsvp_stmt = $conn->prepare($single_query);

          $rsvp_stmt->bind_param('ssssssss', $rsvp->email, $rsvp->firstName, $rsvp->lastName, $rsvp->postal, $gender, $category, $company, $guestOf);
        }

        $rsvp_stmt->execute();

        if ($rsvp_stmt->store_result()){
          if ($rsvpType === 'match' || $rsvpType === 'open'){
            $path = '/_inc/alerts/conf-msg.html'; //
            $alert = file_get_contents( BASEPATH . $path );
            echo $alert;

            //	On successful add to db, send email
            sendConfirmPm($rsvp);
          } else if ($rsvpType === 'capacity'){
            $path = '/_inc/alerts/capacity-msg.html'; //
            $alert = file_get_contents(BASEPATH . $path);
            echo $alert;

            //	On successful add to db, send email
            sendStaffEmailPM($rsvp);
          }

          $rsvp_stmt->close();
        } else {
          echo 'Error: ' . $rsvp_stmt->error . '<br>' . $conn->error;
        } // end of $conn->query($sql) === TRUE
      } else { // if email is alreayd in the DB (user has already registered)
        $path = '/_inc/alerts/reg-msg.html';
        $alert = file_get_contents(BASEPATH . $path);
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

  function dbUnknwnr($rsvp){
    $conn = dbConnect();

    // CHECK IF EMAIL ALREADY IN DB
    $query = 'SELECT id FROM ' . DB_TABLE . ' WHERE EMAIL=?';

    if ($stmt = $conn->prepare($query)){
      $stmt->bind_param('s', $rsvp->email);
      $stmt->execute();

      if (!$stmt->store_result()){
        echo 'Error Result = false';
      } else if ($stmt->num_rows == 0){
        if ($rsvp->hasGuest == true){
          $unknown_query = 'INSERT INTO ' . UNKNWNR . ' (email, firstName, lastName, postal, guestFirstName, guestLastName, guestEmail)
          VALUES (?,?,?,?,?,?,?)';

          $unknown_stmt = $conn->prepare($unknown_query);

          $unknown_stmt->bind_param('sssssss', $rsvp->email, $rsvp->firstName, $rsvp->lastName, $rsvp->postal, $rsvp->guestFirstName, $rsvp->guestLastName, $rsvp->guestEmail);
        } else {
          $unknown_query= 'INSERT INTO ' . UNKNWNR . ' (email, firstName, lastName, postal)
          VALUES (?,?,?,?)';

          $unknown_stmt = $conn->prepare($unknown_query);

          $unknown_stmt->bind_param('ssss', $rsvp->email, $rsvp->firstName, $rsvp->lastName, $rsvp->postal);
        }

      $unknown_stmt->execute();

      if ($unknown_stmt->store_result()){
        $path = '/_inc/alerts/unknown-msg.html'; // confirmation message
        $alert = file_get_contents(BASEPATH . $path);
        echo $alert;

        //	On successful add to db, send email
        $staffArgs = array (
          'email' => $rsvp->email,
          'firstName' => $rsvp->firstName,
          'lastName' => $rsvp->lastName,
          'postal' => $rsvp->postal
        );

        sendStaffEmailPM($staffArgs);

        $unknown_stmt->close();
      } else {
        echo 'Error: ' . $unknown_stmt->error . '<br>' . $conn->error;
      }
    } else {
      // if email is alreayd in the DB (user has already registered)
      $path = '/_inc/alerts/reg-msg.html';
      $alert = file_get_contents(BASEPATH . $path);
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

  function delete_unknown($conn, $email){
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
        echo $email 
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
    } else {
      echo 'error ' . $del_stmt->error;
    }

    $stmt->close();
  } // End of delete_unknown();

// *****
// Show Entries in table, either RSVPs or Unknown RSVPS
// *****

  function viewResults($dbTable){
    // Create connection
    $conn = dbConnect();

    $query = 'SELECT id, firstName, lastName, email, postal, guestFirstName, guestLastName, guestEmail FROM ' . $dbTable;

    if ($stmt = $conn->prepare($query)){
      $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);

      $stmt->store_result();

      $stmt->bind_result($id, $firstName, $lastName, $email, $postal, $guestFirstName, $guestLastName, $guestEmail);

      if ($stmt->num_rows > 0){ ?>
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
              <?php if ($dbTable === UNKNWNR){ ?>
                <th>Approve/Deny</th>
              <? } ?>
            </tr>
          </thead>
          <tbody>

            <?php
            while ($stmt->fetch()){ ?>
              <tr id="<?php echo $id ;?>">
                <td><?php echo $id ;?></td>
                <td id="firstName" class="value"><?php echo $firstName; ?></td>
                <td id="lastName" class="value"><?php echo $lastName; ?></td>
                <td id="email" class="value"><?php echo $email; ?></td>
                <td id="postal" class="value"><?php echo $postal; ?></td>
                <td id="guestName" class="value"><?php echo $guestFirstName . ' ' . $guestLastName; ?></td>
                <td id="guestEmail" class="value"><?php echo $guestEmail; ?></td>

              <?php if ($dbTable === UNKNWNR){ ?>
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

    if ($result === false){
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

        while ($row = $result->fetch_array(MYSQLI_NUM)){
          fputcsv($output, array_values($row));
        }

        fclose($output);
        readfile($output);
        unlink($output);
        exit();
      }
    }
  }

  function newInsert($obj){

    global $rsvpType;

    // if key value pair exists, set variable as the value
    $gender = property_exists('Rsvp', 'gender') ? $obj->gender : '';
    $category = property_exists('Rsvp', 'category') ? $obj->category : '';
    $company = property_exists('Rsvp', 'company') ? $obj->company : '';
    $guestOf = property_exists('Rsvp', 'guestOf') ? $obj->guestOf : '';

    if ($obj->action != ''){
      $verdict = $obj->action;
    }
    // Create connection
    $conn = dbConnect();

    // Query to check if email exists in Db Table
    $query = 'SELECT id FROM ' . DB_TABLE . ' WHERE EMAIL=?';

    if ($stmt = $conn->prepare($query)){
      $stmt->bind_param('s', $obj->email);
      $stmt->execute();

      if (!$stmt->store_result()){
        echo 'Error Result = false';
      } else if ($stmt->num_rows == 0){ // if email is not in Db table

        if (isset($verdict)){
          if ($verdict === 'delete'){
            $email = $rsvp->email;
            delete_unknown($conn, $email);

            rejectEmailPM($rsvp);
            json_encode($obj);
            return;
          }
        }


        if ($obj->hasGuest == true){
          printf('wtfff');
          // prepared SQL stmt to inster guest and plus one
          $guest_query = 'INSERT INTO ' . DB_TABLE . '(email, firstName, lastName, postal, gender, category, company, guestOf, guestFirstName, guestLastName, guestEmail)
          VALUES (?,?,?,?,?,?,?,?,?,?,?)';

          $rsvp_stmt = $conn->prepare($guest_query);

          $rsvp_stmt->bind_param('sssssssssss', $obj->email, $obj->firstName, $obj->lastName, $obj->postal, $gender, $category, $company, $guestOf, $obj->guestFirstName, $obj->guestLastName, $obj->guestEmail);
        } else {
          printf('yyyyyyyyyyy');
          // prepared SQL stmt to insert guest
          $single_query = 'INSERT INTO ' . DB_TABLE . '(email, firstName, lastName, postal, gender, category, company, guestOf)
          VALUES (?,?,?,?,?,?,?,?)';

          $rsvp_stmt = $conn->prepare($single_query);

          $rsvp_stmt->bind_param('ssssssss', $obj->email, $obj->firstName, $obj->lastName, $obj->postal, $gender, $category, $company, $guestOf);
        }

        $rsvp_stmt->execute();

        if ($rsvp_stmt->store_result()){
          //	On successful add to db, send email
          sendConfirmPm($obj);

          if (isset($verdict)){
            if ($verdict === 'approve'){
              echo json_encode($str_json->action);
              printf('why u no work?');
              echo 'success';
              delete_unknown($conn, $$rsvp->email);
              return;
            }
          }

          if ($rsvpType === 'match' || $rsvpType === 'open'){
            $path = '/_inc/alerts/conf-msg.html'; //
            $alert = file_get_contents( BASEPATH . $path );
            echo $alert;
          }

          if ($rsvpType === 'capacity'){
            $path = '/_inc/alerts/capacity-msg.html'; //
            $alert = file_get_contents(BASEPATH . $path);
            echo $alert;

            //	On successful add to db, send email
            sendStaffEmailPM($obj);
          }
          $rsvp_stmt->close();
        } else {
          echo 'Error: ' . $rsvp_stmt->error . '<br>' . $conn->error;
        } // end of $conn->query($sql) === TRU
      } else { // if email is alreayd in the DB (user has already registered)
        if ($verdict != ''){
          echo json_encode($obj->action);
  				echo 'email already in db';
        } else {
          $path = '/_inc/alerts/reg-msg.html';
          $alert = file_get_contents(BASEPATH . $path);
          echo $alert;
        }
      //already registered message
      $stmt->close();
    }
  $conn->close();
  };// end of dbConnect();
  }
;?>
