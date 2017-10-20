<?php

	require('../config/config.php');
	require('../src/database.php');
	require('../src/new_insert.php');
	require('../src/email-postmark.php');

	if (isset($_POST['rsvp'])){

		//Decodes JSON object
		$str_json = json_decode($_POST['rsvp']);

		if ($str_json->guestEmail != ''){
			$str_json->hasGuest = true;
		} else {
			$str_json->hasGuest = false;
		}

		newInsert($str_json);
	};

	?>
