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
				 require 'app/db.php';
				 require 'app/rsvp.php';

		$submit = $_POST['rsvp']['submit'];

		$formData = formSanitize($_POST); // Clean form data

		$rsvp = new Rsvp($formData); //Define new Rsvp Class

	  if ($rsvpType === 'open') {
	  	if ($submit) {
				$db = new DB();
				$db->insertRsvp($rsvp);
	    }

			return;
	  }// end of rsvpType = open

		if ($rsvpType === 'Match' || $rsvpType === 'Capacity') {
			$event_list = BASEPATH . '/admin/list/event-invites.csv';
			$email = $rsvp->getEmail();

			if (!file_exists($event_list)) {
				echo '<h2>Please Upload an Event Invite List</h2>';
				echo '<h5>Contact <a href="webadmin@contempomedia.com">webadmin@contempomedia.com</a></h5>';
			} else {
				if ($rsvp->checkEmail($email)) {
					$rsvp->getUserInfo($email);
					$rsvp->setAction('insert');

					$db = new DB();
					$db->insertRsvp($rsvp);
				} else {
					$rsvp->setAction('insert');

					$db = new DB();
					$db->dbUnknown($rsvp);
				}
			}
		}// end of rsvpType = Match
	?>
</div>

</body>
</html>
