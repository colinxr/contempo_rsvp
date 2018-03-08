<?php

	require('../../config/config.php');
	require('../../app/database.php');
	require('../../app/rsvp-class.php');

	if (isset($_POST['rsvp'])){

		//Decodes JSON object
		$str_json = json_decode($_POST['rsvp']);

		$rsvp = new Rsvp();

		$rsvp->email = $str_json->email;
		$rsvp->firstName = $str_json->firstName;
		$rsvp->lastName = $str_json->lastName;
		$rsvp->postal = $str_json->postal;
		$rsvp->postal = $str_json->postal;
		$rsvp->action = $str_json->action;

		if ($str_json->guestEmail != ''){
			$rsvp->guestEmail = $str_json->guestEmail;
			$rsvp->guestFirstName = $str_json->guestFirstName;
			$rsvp->guestLastName = $str_json->guestLastName;
			$rsvp->hasGuest = true;
		} else {
			$str_json->hasGuest = false;
		}

		insertRsvp($rsvp);
	};

	?>
