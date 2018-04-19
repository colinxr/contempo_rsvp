<?php
  require_once __DIR__ . '/../config/config.php';

  class Admin {
  // *****
  // Show Entries in table, either RSVPs or Unknown RSVPS
  //
  // @param string $dbTable: the database table which we want to view.
  //
  // return mixed : returns results from SQL Query
  // *****

    public function viewResults($dbTable){
      // Create connection
      $db = new DB();
      $conn = $db->dbConnect();

      $query = 'SELECT id, firstName, lastName, email, postal, guestFirstName, guestLastName, guestEmail FROM ' . $dbTable;


      if ($stmt = $conn->prepare($query)){
        $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);
        $stmt->store_result();
        $stmt->bind_result($id, $firstName, $lastName, $email, $postal, $guestFirstName, $guestLastName, $guestEmail);

        if ($stmt->num_rows > 0) {
          echo '<table class="table table-striped" id="rsvp-table">';
            echo '<thead>';
              echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>First Name</th>';
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';
                echo '<th>Postal</th>';
                echo '<th>Guest Name</th>';
                echo '<th>Guest Email</th>';
                if ($dbTable === UNKNWNR) {
                  echo '<th>Approve/Deny</th>';
                 }
              echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

              while ($stmt->fetch()) {
                echo '<tr id="' . $id . '">';
                  echo '<td>' . $id . '</td>';
                  echo '<td id="firstName" class="value">' . $firstName . '</td>';
                  echo '<td id="lastName" class="value">' . $lastName . '</td>';
                  echo '<td id="email" class="value">' . $email . '</td>';
                  echo '<td id="postal" class="value">' . $postal . '</td>';
                  echo '<td id="guestName" class="value">' .  $guestFirstName . ' ' . $guestLastName . '</td>';
                  echo '<td id="guestEmail" class="value">' . $guestEmail . '</td>';

                if ($dbTable === UNKNWNR) {
                  echo '<td><input type="button" id="' . $id . '" class="btn btn-link approve" value="Approve" />';
                  echo '<input type="button" class="btn btn-link deny" value="Deny" /></td>';
                 }
              echo '</tr>';
            }
            echo '</tbody>';
          echo '</table>';
        } else { // If table has no unknown RSVPs display a message
          echo '<div>';
            if ($dbTable === UNKNWNR) {
              echo '<th>No unknown RSVPs right now. Check back later.</th>';
            } else {
              echo 'No  RSVPs right now. Check back later.';
            }
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

    public function download_results($dbTable){
      $db = new DB();
      $conn = $db->dbConnect();

      $query = 'SELECT * from ' . $dbTable . ' ORDER BY ID DESC';
      $result = $conn->query($query);

      if (!$result){
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

    private function mailchimpImport($file) {
      $row = 2;

      if (!file_exists($file)) {
        return 'No Event List was found.';
      } else {
        if (($handle = fopen($file, 'r')) !== false) {
          while(($data = fgetcsv($handle, 1500, ',')) !== false) {
            $row++;

            // $individual_data = new stdClass;
            $individual_data = array();

            if ($data[9] == 'import') {
              $individual_data['apikey'] = MAILCHIMP_API;
              $individual_data['email_address'] = $data[6];
              $individual_data['status'] = 'subscribed';
              $individual_data['merge_fields'] = array(
                'GUESTOF' => $data[0],
                'FNAME'   => $data[1],
                'LNAME'   => $data[2],
                'COMPANY' => $data[3],
                'SEX'     => $data[7],
                'VIPTYPE' => $data[8]
                );
            }

            $memberId = md5(strtolower($data[6]));
            $json = json_encode($individual_data);

            $final_data['operations'][] = array(
              'method' => 'PUT',
              'path'   => 'lists/' . MAILCHIMP_LIST_ID . '/members/' . $memberId,
              'body'   => $json
            );


            $api_response = $this->batchSubscribe($final_data, MAILCHIMP_API);
            $json_response = json_decode($api_response);
          }

          echo 'Check Mailchimp in a few minutes to ensure the list has been imported.';
          echo '</br>';
          echo '</br>';

          echo '<pre>';
            echo 'id: ' . $json_response->id . '</br>';
            echo 'status: ' . $json_response->status . '</br>';;
            echo 'submitted at: ' . $json_response->submitted_at . '</br>';;
            echo 'debug link: ';
            print_r($json_response->_links[1]->href);
          echo '</pre>';
          echo '<br />';
          echo '<br />';
        } else {
          echo 'there\'s been an error';
        } // end of if ($handle = fopen() !== false)

        fclose($handle);
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

    private function batchSubscribe($data, $api_key) {
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

    public function upload_list() {
      $target_dir = BASEPATH . '/admin/list/';
      $target_file = $target_dir . 'event-invites.csv';

      $uploadOk = 1;
      $fileType = strtolower($_FILES['fileToUpload']['type']);

      if ($_POST['submit']) {
        if ($_FILES['fileToUpload']['size'] > 1000000) {
          echo 'Sorry, the file is too large';
          $uploadOk = 0;
        }

        if ($fileType !== 'text/csv' && $fileType !== 'application/vnd.ms-excel') {
          echo '<pre>';
            var_dump($_FILES['fileToUpload']);
            var_dump($fileType);
          echo '</pre>';
          echo 'Sorry, only csv files are allowed </br>';
          $uploadOk = 0;
        }

        if ($uploadOk == 0) {
          echo 'Sorry, your file was not uploaded';
        } else {
          if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
            echo '<p>The file '. basename($_FILES['fileToUpload']['name']) .' has been uploaded.</p>';
            echo '<br/>';
            echo '<br/>';

            // $this->mailchimpImport($target_file);
          } else {
            echo 'Sorry, there was an error uploading your file';
          }
        }
      }
    }

    // *****
    // Fetch RSVP Type as specified in Admin DB Table
    //
    // return String : If value is defined in DB, return that, else return "Open"
      public function fetch_rsvp_type() {
        $db = new DB();
        $conn = $db->dbConnect();

        $val = 'RSVP_TYPE';
        $sql = 'SELECT VALUE FROM ' . ADMIN_TABLE . ' WHERE SETTING=?';

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
          trigger_error($stmt->error, E_USER_ERROR);
          return;
        }

        $stmt->bind_param('s', $val);
        $stmt->execute();

        if (!$stmt->execute()){
          printf($stmt->error);
          trigger_error($stmt->error, E_USER_ERROR);
        }

        $stmt->bind_result($rsvp_type);
        $stmt->fetch();

        // var_dump($rsvp_type);

        return $rsvp_type;

        $conn->close();
      }

      public function set_rsvp_type($str) {
        $db = new DB();
        $conn = $db->dbConnect();

        $val = 'RSVP_TYPE';
        $sql = 'INSERT into ' . ADMIN_TABLE . '
                (SETTING, VALUE) VALUES (?,?)
                ON DUPLICATE KEY UPDATE
                SETTING = values(SETTING),
                VALUE = values(VALUE)';

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
          trigger_error($conn->error, E_USER_ERROR);
        }

        $stmt->bind_param('ss', $val, $str);
        $stmt->execute();

        if (!$stmt->execute()) {
          trigger_error($conn->error, E_USER_ERROR);
        }

        if (!$stmt->store_result()) {
          trigger_error($stmt->error, E_USER_ERROR);
        }

        echo '<h3>The current RSVP Type is set to ' . $str . '</h3>';

        $stmt->close();
        $conn->close();

      }

      public function countRsvps(){
        $db = new DB();
        $conn = $db->dbConnect();

        if ($result = $conn->query('SELECT * FROM '. DB_TABLE)) {
          $row_count = $result->num_rows;

          printf('<h5>There are a total of %d rsvps.</h5>', $row_count);

          $result->close();
        }
        $conn->close();
      }

      public function countPlusOnes(){
        $db = new DB();
        $conn = $db->dbConnect();

        if ($result = $conn->query("SELECT * from ". DB_TABLE ." WHERE guestFirstName<>''")) {
          $row_count = $result->num_rows;

          printf('<h5>There are a total of %d plus ones.</h5>', $row_count);

          $result->close();
        }
        $conn->close();
      }

  }
?>
