<?php
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Transfer-Encoding: Binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Type: application/force-download');
  header('Content-Disposition: attachment; filename="final_rsvp_list.csv"');

  require('../config/config.php');
  // require('../app/admin.class.php');

  $admin = new Admin();
  $admin->export_final_list();
?>
