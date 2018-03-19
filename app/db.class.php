<?php
  require_once __DIR__ . '/../config/config.php';
  include(__DIR__ . '/email.class.php');

  class DB {

  // *****
  // Connects to Database
  //
  // return mysqli
  // *****
    public function dbConnect() {
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
  // return boolean : returns true if $email is not in database already
  // *****

    private function checkDuplicate($conn, $dbTable, $email){
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

    public function insertRsvp($obj) {

      global RSVP_TYPE;

      $email     = $obj->getEmail();
      $firstName = $obj->getFirstName();
      $lastName  = $obj->getLastName();
      $postal    = $obj->getPostal();
      $hasGuest  = $obj->getHasGuest();

      // if key value pair exists, set variable as the value
      $gender    = $obj->getGender() ? $obj->getGender() : ' ';
      $category  = $obj->getCategory() ? $obj->getCategory() : ' ';
      $company   = $obj->getCompany() ? $obj->getCompany() : ' ';
      $guestOf   = $obj->getGuestOf() ? $obj->getGuestOf() : ' ';
      $verdict   = $obj->getAction() ? $obj->getAction() : ' ';

      // Create connection
      $conn = $this->dbConnect();

      if ($this->checkDuplicate($conn, DB_TABLE, $email)) {
        if (isset($verdict) && $verdict === 'delete'){
          printf($obj->getAction() . ': ' . $email . '     ');
          $this->delete_unknown($conn, $email);

          rejectEmailPM($obj);
          json_encode($obj);
          return;
        }

        if ($hasGuest == true){
          $guestFirstName = $obj->getGuestFirstName();
          $guestLastName  = $obj->getGuestLastName();
          $guestEmail     = $obj->getGuestEmail();

          // prepared SQL stmt to insert guest and plus one
          $guest_query = 'INSERT INTO ' . DB_TABLE . '(email, firstName, lastName, postal, gender, category, company, guestOf, guestFirstName, guestLastName, guestEmail)
          VALUES (?,?,?,?,?,?,?,?,?,?,?)';

          $rsvp_stmt = $conn->prepare($guest_query);

          if ($rsvp_stmt) {
            $rsvp_stmt->bind_param('sssssssssss', $email, $firstName, $lastName, $postal, $gender, $category, $company, $guestOf, $guestFirstName, $guestLastName, $guestEmail);
          }
        } else {
          // prepared SQL stmt to insert guest
          $single_query = 'INSERT INTO ' . DB_TABLE . '(email, firstName, lastName, postal, gender, category, company, guestOf)
          VALUES (?,?,?,?,?,?,?,?)';

          $rsvp_stmt = $conn->prepare($single_query);
          if ($rsvp_stmt) {
            $rsvp_stmt->bind_param('ssssssss', $email, $firstName, $lastName, $postal, $gender, $category, $company, $guestOf);
          }
        }

        if (!$rsvp_stmt->execute()){
          printf($rsvp_stmt->error);
        }

        if (!$rsvp_stmt->store_result()){
          echo 'Error: ' . $rsvp_stmt->error . '<br>' . $conn->error;
        } else {
          $email = new Email();
          $email->sendConfirmaion($obj);

          if (isset($verdict) && $verdict == 'approve'){
            printf($obj->getAction() . ': ' . $email . '     ');
            $this->delete_unknown($conn, $email);
            return;
          }

          if (RSVP_TYPE === 'Match' || RSVP_TYPE === 'Open'){
            include(BASEPATH . '/_inc/alerts/conf-msg.php'); //
          }

          if (RSVP_TYPE === 'Capacity'){
            include(BASEPATH . '/_inc/alerts/capacity-msg.php');

            //	On successful add to db, send email
            $email = new Email();
            $email->sendStaffEmail($obj);
          }

          $rsvp_stmt->close();
        } // end of $rsvp_stmt->store_result()
      } else { // if checkDuplicate() returns false
        include(BASEPATH . '/_inc/alerts/reg-msg.php');
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

    public function dbUnknown($obj) {
      $email     = $obj->getEmail();
      $firstName = $obj->getFirstName();
      $lastName  = $obj->getLastName();
      $postal    = $obj->getPostal();
      $hasGuest  = $obj->getHasGuest();
      $verdict   = $obj->getAction() ? $obj->getAction() : ' ';

      // Create connection
      $conn = $this->dbConnect();

      if (!$this->checkDuplicate($conn, UNKNWNR, $email)) {
        include(BASEPATH . '/_inc/alerts/reg-msg.php');
      } else {
        if ($hasGuest = true) {
          $guestFirstName = $obj->getGuestFirstName();
          $guestLastName  = $obj->getGuestLastName();
          $guestEmail     = $obj->getGuestEmail();

          $unknown_query = 'INSERT INTO ' . UNKNWNR . ' (email, firstName, lastName, postal, guestFirstName, guestLastName, guestEmail) VALUES (?,?,?,?,?,?,?)';

          $unknown_stmt = $conn->prepare($unknown_query);

          if ($unknown_stmt) {
            $unknown_stmt->bind_param('sssssss', $email, $firstName, $lastName, $postal, $guestFirstName, $guestLastName, $guestEmail);
          }
        } else {
          $unknown_query= 'INSERT INTO ' . UNKNWNR . ' (email, firstName, lastName, postal) VALUES (?,?,?,?)';

          $unknown_stmt = $conn->prepare($unknown_query);

          if ($unknown_stmt) {
            $unknown_stmt->bind_param('ssss', $email, $firstName, $lastName, $postal);
          }
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
          $email = new Email();
          $email->sendStaffEmail($obj);

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

    private function delete_unknown($conn, $email) {
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

  }
?>
