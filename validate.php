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
       require 'src/database.php';
			 require 'src/rsvp-class.php';



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
		$gender   = '';
		$category = '';
		$company  = '';
		$guestOf  = '';

		$email = $rsvp->email;

		if ($rsvp->checkEmail($email) == true) {
			$rsvp->gender = $gender;
			$rsvp->category = $category;
			$rsvp->company = $company;
			$rsvp->guestOf = $guestOf;

    	insertRsvp($rsvp); //Insert RSVP Class into db table;
		} else if ($rsvp->checkEmail($email) == false) {
			dbUnknwnr($rsvp);
		} else {
			echo 'Please Upload an Event Invite List';
		}
	}// end of rsvpType = Match
?>
</div>

</body>
</html>
