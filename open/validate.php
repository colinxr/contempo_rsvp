<?php require_once 'header.php'; ?>
<body>

<div class="container">
	<div class="col left img">
	</div>

	<div class="col right info info--alert">

		<?php include 'event-info.php'; ?>
		<?php
			require_once '../config/config.php';
		       require '../app/app.php';
					 // require 'app/db.class.php';
					 // require 'app/rsvp.class.php';

			$submit = $_POST['rsvp']['submit'];
			$formData = formSanitize($_POST); // Clean form data
			$rsvp = new Rsvp($formData); //Define new Rsvp Class

			if (RSVP_TYPE === 'Capacity') {
				include(BASEPATH . '/_inc/alerts/capacity-msg.php');

				//	On successful add to db, send email
				$email = new Email();
				$email->sendStaffEmail($obj);
				return;
			}// end of rsvpType = Capacity

		  if (PARTNER_RSVP === true) {
		  	if ($submit) {
					$rsvp->setAction('insert');
					$db = new DB();
					$db->insertRsvp($rsvp);
		    }

				return;
		  }// end of rsvpType = open
		?>
	</div>
</div>
</body>
</html>
