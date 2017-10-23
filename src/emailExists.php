<?php

  function emailExists($dbTable, $email){
    // Query to check if email exists in Db Table
    $query = 'SELECT id FROM ' . DB_TABLE . ' WHERE EMAIL=?';

    if ($stmt = $conn->prepare($query)){
      $stmt->bind_param('s', $obj->email);
      $stmt->execute();

      if (!$stmt->store_result()){
        echo 'Error Result = false';
      } else if ($stmt->num_rows == 0){
        return true;
      } else {
        return false;
      }
    }
    //already registered message
    $stmt->close();
  }






?>
