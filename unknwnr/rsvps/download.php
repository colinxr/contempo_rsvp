<?php
require("../../_inc/config.php");

//Check connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ( $conn->connect_error ) {
  die( "Connection failed:" . $conn->connect_error );
}

$query = "SELECT * from " . DB_TABLE . " ORDER BY ID DESC";

$result = $conn->query( $query );

if ( $result === false ) {
  die('Could not fetch records');
} else {
  $num_fields = mysqli_num_fields($result);

  $headers = array();

  while ( $field_info = mysqli_fetch_field( $result ) ){
    $headers[] = $field_info->name;
  }

  $output = fopen('php://output', 'w');

  if ( $output && $result ){
    header("Cache-Control: private");
    header("Content-Description: File Transfer");
    header("Content-Type: application/force-download");
    header("Content-Transfer-Encoding: binary");
    header("Content-Type: binary/octet-stream");
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=export.csv");

    fputcsv($output, $headers);

    while ($row = $result->fetch_array(MYSQLI_NUM)) {
      fputcsv($output, array_values($row));
    }

    fclose($output);
  }
}
?>
