<?php
  require_once __DIR__ . '/../config/config.php';
  include(__DIR__ . '/email-postmark.php');

  // *****
  // Cleans form data and prepares RSVP class for use
  //
  // @param array $arr: array of form data from homepage rsvp form
  // @param object $obj: rsvp class object
  //
  // return object: transformed rsvp object with sanitized data, augmented with aditional info if
  // plus one
  //
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
  //
  // return mysqli
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
  //
  // @param mysqli $conn: the database connection
  // @param string $dbTable: database table name
  // @param string $email: RSVP email to query the db with
  //
  // return boolean : returns true if $email is not in databes already
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
  //
  // @param object $obj: the RSVP class object.
  //
  // return string : confirmation string with results of sql command
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
  }

  // *****
  // Inserts Unknown RSVP into Unknown Table
  //
  // @param object $obj: the RSVP class object.
  //
  // return string : confirmation string with results of sql command
  // *****

  function dbUnknwnr($obj){
    $conn = dbConnect();

    if (!checkDuplicate($conn, UNKNWNR, $obj->email)){
      // if email is already in the DB (user has already registered)
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
  }

  // *****
  // Removes unknown RSVP from unknown table
  //
  // @param mixed $conn: the database table which we want to export.
  // @param string $email: the unique email of the rsvp to be deleted
  //
  // return string : confirmation string with results of sql command
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
  }

  // *****
  // Show Entries in table, either RSVPs or Unknown RSVPS
  //
  // @param string $dbTable: the database table which we want to export.
  //
  // return mixed : returns results from SQL Query
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
      <?php } else { // If table has no unknown RSVPs display a message
        echo '<div>';
          echo 'No unknown RSVPs right now. Check back later.';
        echo '</div>';
      }
      $stmt->close();
    }
    $conn->close();
  }

  // *****
  // Generate CSV file from DB
  //
  // @param string $dbTable: the database table which we want to export.
  //
  // return mixed : generates new CSV file
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

  // *****
  // Scrapes uploaded event guest list and prepares data to import into Mailchimp
  //
  // @param string $file: the location and file name of the event guest list on the server
  //
  // return mixed
  // *****

  function mailchimpImport($file) {
    $row = 2;

    if (!file_exists($file)) {
      return 'No Event List was found.';
    } else {
      if (($handle = fopen($file, 'r')) !== false) {
        while(($data = fgetcsv($handle, 1500, ',')) !== false) {
          $row++;

          // To Do
          //
          // If the column X includes instructions to import to mailchimp,
          // prepare the data from the row to be imported into Mailchimp.
          //
          // if $data[8] === 'mailchimp import' {
          //
          // }
          //

          $individual_data = array(
            'apikey'        => MAILCHIMP_API,
            'email_address' => $data[3],
            'status'        => 'subscribed',
            'merge_fields'  => array(
              'FNAME'   => $data[0],
              'LNAME'   => $data[1],
              'SEX'     => $data[4],
              'VIPTYPE' => $data[5],
              'COMPANY' => $data[6],
              'GUESTOF' => $data[7],
            )
          );

          $memberId = md5(strtolower($data[3]));
          $json = json_encode($individual_data);

          $final_data['operations'][] = array(
            'method' => 'PUT',
            'path'   => 'lists/'. MAILCHIMP_LIST_ID .'/members/' . $memberId,
            'body'   => $json
          );
        }

        fclose($handle);
        $api_respsonse = batchSubscribe($final_data, MAILCHIMP_API);

        print_r($api_respsonse);

        echo 'Check Mailchimp in a few minutes to ensure the list has been imported.
          </br>
          </br>';
      } else {
        echo 'there\'s been an error';
      } // end of if ($handle = fopen() !== false)
    } // end of if (!file_exists);
  }

  // *****
  // Automatically Subscriber event guests to our Mailchimp list
  //
  // @param array $data: array of operations to be posted to Mailchimp API
  // @param string $api_key mailchimp api key, defined in config.php
  //
  // return mixed
  // *****

  function batchSubscribe($data, $api_key) {
    $auth          = base64_encode('user:' . $api_key);
    $json_postData = json_encode($data);
    $ch            = curl_init();
    $dataCenter    = substr($api_key, strpos($api_key, '-') + 1);
    $curlopt_url   = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/batches/';

    curl_setopt($ch, CURLOPT_URL, $curlopt_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
         'Authorization: Basic ' . $auth));
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_postData);

    $result = curl_exec($ch);
    return $result;
  }

  // *****
  // Upload Event List
  //
  // return string : confirmation string
  // *****

  function upload_list() {
    $target_dir = BASEPATH . '/admin/list/';
    $target_file = $target_dir . 'event-invites.csv';

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

          mailchimpImport($target_file);

          echo 'The file '. basename($_FILES['fileToUpload']['name']) .' has been uploaded.<br/>';
        } else {
          echo 'Sorry, there was an error uploading your file';
        }
      }
    }
  }

;?>
