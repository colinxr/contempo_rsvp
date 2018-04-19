<?php require_once 'header.php'; ?>
	<body>
		<div class="container">
			<div class="col left img">
			</div>

			<div class="col right info info--alert">
				<?php include 'event-info.php'; ?>
				<?php
					require_once 'config/config.php';
					require 'app/app.php';

					$submit = $_POST['rsvp']['submit'];

					$formData = formSanitize($_POST); // Clean form data

					$rsvp = new Rsvp($formData); //Define new Rsvp Class

				  if (RSVP_TYPE === 'Open') {
				  	if ($submit) {
							$rsvp->setAction('insert');
							$db = new DB();
							$db->insertRsvp($rsvp);
				    }

						return;
				  }// end of rsvpType = open

					if (RSVP_TYPE === 'Match') {
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

					if (RSVP_TYPE === 'Capacity') {
						$rsvp->setAction('insert');

						$db = new DB();
						$db->dbUnknown($rsvp);

						include(BASEPATH . '/_inc/alerts/capacity-msg.php');
					}
				?>
			</div>
		</div>
	</body>
</html>
