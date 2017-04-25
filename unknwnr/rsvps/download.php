<?php
require ("../../_inc/config.php");

$dbConn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Check connection

if ($dbConn->connect_error){
  die ("Connection failed: " . $dbConn->connect_error);
}

// Select all entries from DB table
  $result = $dbConn->query( "SELECT * FROM " . DB_TABLE );

    if ( $result === false ) {

      echo "Error";

  } else if( $result->num_rows > 0 ) {


  $num_fields = mysqli_num_fields($result);


  $headers = array();

  for ($i = 0; $i < $num_fields; $i++) {
    $headers[] = mysqli_fetch_field($result , $i);
  }

  $fp = fopen('php://output', 'w');

  if ($fp && $result) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="export.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    fputcsv($fp, $headers);

    while ($row = $result->fetch_array(MYSQLI_NUM)) {
      fputcsv($fp, array_values($row));
    }

    die;
  }
}
; ?>
