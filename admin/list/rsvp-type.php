<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

  require(__DIR__ . '/../../config/config.php');


	if (!$_POST['setting']) echo 'no setting';

	if (isset($_POST['setting'])) {
		$data = json_decode($_POST['setting'], true);

		// var_dump($data["rsvpType"]);
		$admin = new Admin();
		$new_rsvp_type = $data['rsvpType'];
		$admin->set_admin_setting('RSVP_TYPE', $new_rsvp_type);
	}
?>
