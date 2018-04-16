<?php
	require_once('../../config/config.php');
	// require('../../app/rsvp.class.php');

	if (isset($_POST['rsvp'])){

		var_dump($_POST['rsvp']);

		//Decodes JSON object
		$data = json_decode($_POST['rsvp'], true);

		// var_dump($data);

		if (!$data['guestEmail']) {
			$data['hasGuest'] = false;
		} else {
			$guestName = explode(' ', $data['guestName']);
			$data['guestFirstName'] = array_shift($guestName);

			$lName = implode($guestName);
			$data['guestLastName'] = $lName;

			$data['hasGuest'] = true;
		}

		$data['category'] = 'Approved Unknown RSVP';
		$data['gender'] = '';
		$data['company'] = '';
		$data['guestOf'] = '';


		$rsvp = new Rsvp($data);

		$rsvp->dump();

		// $rsvp->email 		 = $str_json->email;
		// $rsvp->firstName = $str_json->firstName;
		// $rsvp->lastName  = $str_json->lastName;
		// $rsvp->postal 	 = $str_json->postal;
		// $rsvp->postal 	 = $str_json->postal;
		// $rsvp->action 	 = $str_json->action;
		//
		// if ($str_json->guestEmail !== ''){
		// 	$rsvp->guestEmail 		= $str_json->guestEmail;
		// 	$rsvp->guestFirstName = $str_json->guestFirstName;
		// 	$rsvp->guestLastName 	= $str_json->guestLastName;
		// 	$rsvp->hasGuest 			= true;
		// } else {
		// 	$str_json->hasGuest = false;
		// }

		$db = new DB();
		$db->insertRsvp($rsvp);
	};

	?>
