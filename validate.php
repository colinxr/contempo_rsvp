<?php require_once 'header.php'; ?>
<body>

<div class="container">
<div class="wrapper">
	<div class="col left info">
		<?php require '_inc/event-info.php'; ?>
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
		$email = $rsvp->email;

		if ($rsvp->checkEmail($email) === true) {
    	insertRsvp($rsvp); //Insert RSVP Class into db table;
		} else {
			dbUnknwnr($rsvp);
		}
	}// end of rsvpType = Match
?>
</div>

</body>
</html>
