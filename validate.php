<?php require_once 'header.php'; ?>
<body>

<div class="container">
<div class="wrapper">
	<div class="col left info">
		<?php require 'event-info.php'; ?>
	</div>

	<div class="col right confirmation">
	<?php
		require_once 'config/config.php';
	       require 'app/app.php';
				 require 'app/rsvp-class.php';

		$submit = $_POST['rsvp']['submit'];
		$rsvp = new Rsvp(); //Define new Rsvp Class
		formSanitize($_POST, $rsvp); // Clean form data

	  if ($rsvpType === 'open') {
	  	if ($submit) {
	      insertRsvp($rsvp);
	    }

			return;
	  }// end of rsvpType = open

		if ($rsvpType === 'match' || $rsvpType === 'capacity') {
			$event_list = BASEPATH . '/admin/list/event-invites.csv';

			$gender   = '';
			$category = '';
			$company  = '';
			$guestOf  = '';

			$email = $rsvp->email;

			if (!file_exists($event_list)) {
				echo '<h2>Please Upload an Event Invite List</h2>';
				echo '<h5>Contact <a href="webadmin@contempomedia.com">webadmin@contempomedia.com</a></h5>';
				return false;
			} else {
				if ($rsvp->checkEmail($email) == true) {
					$rsvp->gender = $gender;
					$rsvp->category = $category;
					$rsvp->company = $company;
					$rsvp->guestOf = $guestOf;

		    	insertRsvp($rsvp); //Insert RSVP Class into db table;
				} else {
					dbUnknwnr($rsvp);
				}
			}
		}// end of rsvpType = Match
	?>
</div>

</body>
</html>
