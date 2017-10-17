<?php
require('../config/config.php');
require('../src/database.php');

//Check connection
$conn = dbConnect();

$query = 'SELECT * from ' . DB_TABLE . ' ORDER BY ID DESC';

$result = $conn->query($query);

if ($result === false) {
  die('Could not fetch records');
} else {
  $num_fields = mysqli_num_fields($result);

  $headers = array();

  while ($field_info = mysqli_fetch_field($result)){
    $headers[] = $field_info->name;
  }

  //header('Content-Type: text/csv; charset=utf-8');
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header("Content-Transfer-Encoding: Binary");
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Type: application/force-download');
  header('Content-Disposition: attachment; filename="rsvps_' .date('j-m-y') . '.csv"');

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
?>
