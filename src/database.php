<?php
include('email-postmark.php');

// *****
// Cleans form data and prepares RSVP class for use
// *****

  function formSanitize($arr, $obj) {
    //Get form info
    $email = strtolower($arr['rsvp']['email']); //match with Email Lower column in wtf.csv
    $firstName = ucwords(strtolower($arr['rsvp']['first-name'])); //stray capitals in user form
    $lastName = ucwords(strtolower($arr['rsvp']['last-name'])); //stray capitals in user form
    $postal = strtoupper($arr['rsvp']['postal']); // proper Postal Code form

    //Define new Rsvp Class
    $obj->email = $email;
    $obj->firstName = $firstName;
    $obj->lastName = $lastName;
    $obj->postal = $postal;
    $obj->action = '';

    if (isset($arr['rsvp']['plus-one'])){ // handles plus one inputs, used to set sql query
      $guestFirstName = ucwords(strtolower($arr['rsvp']['guest-firstName']));// Formats data for any stray capitals in user form
      $guestLastName = ucwords(strtolower($arr['rsvp']['guest-lastName']));// Formats data for any stray capitals in user form
      $guestEmail = strtolower($arr['rsvp']['guest-email']);

      $obj->guestFirstName = $guestFirstName;
      $obj->guestLastName = $guestLastName;
      $obj->guestEmail = $guestEmail;
      $obj->hasGuest = true;
    }

    return $obj; // return obj for use in other functions
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
// Check if email is already in DB table
// *****

function checkDuplicate($conn, $dbTable, $email){
  // Query to check if email exists in Db Table
  $query = 'SELECT id FROM ' . $dbTable . ' WHERE EMAIL=?';

  if ($stmt = $conn->prepare($query)){
    $stmt->bind_param('s', $email);
    $stmt->execute();

    if (!$stmt->store_result()) {
      echo 'Error Result = false';
    } else {
      if ($stmt->num_rows == 0){
        return true;
      } else {
        return false;
      }
    }
  } else {
    return $conn->error;
  }
}

// *****
// Inserts RSVP into Database
// *****
  function insertRsvp($obj){

    global $rsvpType;

    // if key value pair exists, set variable as the value
    $gender = property_exists('Rsvp', 'gender') ? $obj->gender : ' ';
    $category = property_exists('Rsvp', 'category') ? $obj->category : ' ';
    $company = property_exists('Rsvp', 'company') ? $obj->company : ' ';
    $guestOf = property_exists('Rsvp', 'guestOf') ? $obj->guestOf : ' ';

    if ($obj->action != ''){
      $verdict = $obj->action;
    }
    // Create connection
    $conn = dbConnect();

    if (checkDuplicate($conn, DB_TABLE, $obj->email)){
      if (isset($verdict) && $verdict === 'delete'){
        $email = $obj->email;
        delete_unknown($conn, $email);

        rejectEmailPM($rsvp);
        json_encode($obj);
        return;
      }

      if ($obj->hasGuest == true){
        // prepared SQL stmt to inster guest and plus one
        $guest_query = 'INSERT INTO ' . DB_TABLE . '(email, firstName, lastName, postal, gender, category, company, guestOf, guestFirstName, guestLastName, guestEmail)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)';

        $rsvp_stmt = $conn->prepare($guest_query);

        $rsvp_stmt->bind_param('sssssssssss', $obj->email, $obj->firstName, $obj->lastName, $obj->postal, $gender, $category, $company, $guestOf, $obj->guestFirstName, $obj->guestLastName, $obj->guestEmail);
      } else {
        // prepared SQL stmt to insert guest
        $single_query = 'INSERT INTO ' . DB_TABLE . '(email, firstName, lastName, postal, gender, category, company, guestOf)
        VALUES (?,?,?,?,?,?,?,?)';

        $rsvp_stmt = $conn->prepare($single_query);

        $rsvp_stmt->bind_param('ssssssss', $obj->email, $obj->firstName, $obj->lastName, $obj->postal, $gender, $category, $company, $guestOf);
      }

      if (!$rsvp_stmt->execute()){
        printf($rsvp_stmt->error);
      }

      if (!$rsvp_stmt->store_result()){
        echo 'Error: ' . $rsvp_stmt->error . '<br>' . $conn->error;
      } else { // IF $rsvp_stmt->store_result() errors out.
        //	On successful add to db, send email
        sendConfirmPm($obj);

        if (isset($verdict) && $verdict === 'approve'){
          $email = $obj->email;
          printf($obj->action . ': ' . $obj->email . '     ');
          delete_unknown($conn, $email);
          return;
        }

        if ($rsvpType === 'match' || $rsvpType === 'open'){
          include(BASEPATH . '/_inc/alerts/conf-msg.php'); //
        }

        if ($rsvpType === 'capacity'){
          include(BASEPATH . '/_inc/alerts/capacity-msg.php');

          //	On successful add to db, send email
          sendStaffEmailPM($obj);
        }

        $rsvp_stmt->close();
      } // end of $rsvp_stmt->store_result()
    } else { // if checkDuplicate() returns false
      if (isset($verdict) && $verdict != ''){
        echo 'email already in db';
      } else {
        include(BASEPATH . '/_inc/alerts/reg-msg.php');
      }
    }

  $conn->close();
  // end of dbConnect();
  }

// *****
// Inserts Unknown RSVP into Unknown Table
// *****

  function dbUnknwnr($obj){
    $conn = dbConnect();

    if (!checkDuplicate($conn, UNKNWNR, $obj->email)){
      // if email is alreayd in the DB (user has already registered)
      include(BASEPATH .'/_inc/alerts/reg-msg.php');
    } else {
      if ($obj->hasGuest == true){
        $unknown_query = 'INSERT INTO ' . UNKNWNR . ' (email, firstName, lastName, postal, guestFirstName, guestLastName, guestEmail)
        VALUES (?,?,?,?,?,?,?)';

        $unknown_stmt = $conn->prepare($unknown_query);

        $unknown_stmt->bind_param('sssssss', $obj->email, $obj->firstName, $obj->lastName, $obj->postal, $obj->guestFirstName, $obj->guestLastName, $obj->guestEmail);
      } else {
        $unknown_query= 'INSERT INTO ' . UNKNWNR . ' (email, firstName, lastName, postal)
        VALUES (?,?,?,?)';

        $unknown_stmt = $conn->prepare($unknown_query);

        $unknown_stmt->bind_param('ssss', $obj->email, $obj->firstName, $obj->lastName, $obj->postal);
      }

      if (!$unknown_stmt->execute()){
          printf($unknown_stmt->error);
      }

      if (!$unknown_stmt->store_result()){
        echo 'Error: ' . $unknown_stmt->error . '<br>' . $conn->error;
      } else {
        //$path = '/_inc/alerts/unknown-msg.php'; // confirmation message
        //$alert = file_get_contents(BASEPATH . $path);
        //echo $alert;
        include(BASEPATH .'/_inc/alerts/unknown-msg.php');

        //	On successful add to db, send email
        sendStaffEmailPM($obj);

        $unknown_stmt->close();
      }
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

    if (!$stmt->store_result()){
      echo 'error ' . $del_stmt->error;
    } else {
      if ($stmt->num_rows > 0){
        $del_query = 'DELETE FROM ' . UNKNWNR . ' WHERE EMAIL=?';

        $del_stmt = $conn->prepare($del_query);
        $del_stmt->bind_param('s', $email);
      } else {
        echo 'not in unknown db';
      }
    }

    $del_stmt->execute();

    if (!$del_stmt->store_result()){
      echo 'error ' . $del_stmt->error;
    } else {
      echo 'deleted from Unknown RSVPS';
      $del_stmt->close();
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

      if ($output && $result){
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


  function upload_list() {
    $target_dir = BASEPATH . '/list/';
    echo $target_dir;
    $target_file = $target_dir . basename($_FILES['fileToUpload']['name']);

    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    if ($_POST['submit']) {

      if ($_FILES['fileToUpload']['size'] > 1000000) {
        echo 'Sorry, the file is too large';
        $uploadOk = 0;
      }

      if ($fileType !== 'csv') {
        echo 'Sorry, only csv files are allowed';
        $uploadOk = 0;
      }

      if ($uploadOk == 0) {
        echo 'Sorry, your file was not uploaded';
      } else {
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
          echo 'The file '. basename($_FILES['fileToUpload']['name']) .' has been uploaded.';
        } else {
          echo 'Sorry, there was an error uploading your file';
        }
      }
    }
  }

;?>
