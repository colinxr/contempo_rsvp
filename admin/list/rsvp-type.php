<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

  require(__DIR__ . '/../../config/config.php');

	if (isset($_POST['data'])) {
		$data = json_decode($_POST['data'], true);

		$admin = new Admin();
		$new_rsvp_type = $data['rsvpType'];
		$admin->set_admin_setting('RSVP_TYPE', $new_rsvp_type);
	}
?>
