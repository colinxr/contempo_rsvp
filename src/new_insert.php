<?php

require('..')

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
          echo json_encode($obj->action);
          delete_unknown($conn, $email);

          rejectEmailPM($rsvp);
          echo 'deleted';

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
            echo 'success';
            delete_unknown($conn, $email);

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
?>
