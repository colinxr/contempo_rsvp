<?php require_once 'header.php'; ?>
<body>
	<div class="wrapper">
		<div class="column column--left">
			<?php
				if (EVENT_THEME == 'Theme Two') {
					include('event-info.php');
				}
			?>
		</div>
		<div class="column column--right alert">
			<?php
				if (EVENT_THEME == 'Theme One') {
					include('event-info.php');
				}

				require_once '../config/config.php';
			       require '../app/app.php';

				$submit = $_POST['rsvp']['submit'];
				$formData = formSanitize($_POST); // Clean form data
				$rsvp = new Rsvp($formData); //Define new Rsvp Class

				if (RSVP_TYPE === 'Capacity') {
					include(BASEPATH . '../_inc/alerts/capacity-msg.php');

					//	On successful add to db, send email
					$email = new Email();
					$email->sendStaffEmail($obj);
					return;
				}// end of rsvpType = Capacity

			  if (PARTNER_RSVP) {
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
