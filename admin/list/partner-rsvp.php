<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

  require(__DIR__ . '/../../config/config.php');

	if (isset($_POST['data'])) {
		$data = json_decode($_POST['data'], true);
		$admin = new Admin();
		$partner_name = $data['partnerName'];

		if (!$admin->create_partner_page($partner_name)) {
			echo 'The partner page already exists.';
		} else {
			$admin->set_admin_setting('PARTNER_RSVP', 'TRUE');
			echo 'Your page has been created at ' . $_SERVER['HTTP_HOST'] . '/' . $partner_name;
		}

	}
?>
