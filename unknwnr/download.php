<?php
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Transfer-Encoding: Binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Type: application/force-download');
  header('Content-Disposition: attachment; filename="rsvps_' .date('j-m-y') . '.csv"');

  require('../config/config.php');
  require('../src/database.php');

  $dbTable = UNKNWNR;

  download_results($dbTable);
?>
