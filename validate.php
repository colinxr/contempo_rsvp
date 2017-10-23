<?php require_once 'header.php' ;?>
<body>

<div class="container">
<div class="wrapper">
	<div class="col left info">
		<?php require '_inc/event-info.php'; ?>
	</div>

	<div class="col right confirmation">
<?php
  require_once 'config/config.php';
       require 'src/database.php';;
			 require 'src/rsvp-class.php';

  //Get form info
  $email = strtolower($_POST['email']); //formants data to match with Email Lower column in wtf.csv
  $firstName = ucwords(strtolower($_POST['first-name'])); // Formats data for any stray capitals in user form
  $lastName = ucwords(strtolower($_POST['last-name'])); // Formats data for any stray capitals in user form
  $postal = strtoupper($_POST['postal']); // Formats data proper Postal Code form
  $submit = $_POST['submit'];

	//Define new Rsvp Class
	$rsvp = new Rsvp();
	$rsvp->email = $email;
	$rsvp->firstName = $firstName;
	$rsvp->lastName = $lastName;
	$rsvp->postal = $postal;
	$rsvp->action = '';

  if (isset($_POST['plus-one'])){ // handles plus one inputs, used to set sql query
  	$guestFirstName = ucwords( strtolower($_POST['guest-firstName']));// Formats data for any stray capitals in user form
		$guestLastName = ucwords( strtolower($_POST['guest-lastName']));// Formats data for any stray capitals in user form
  	$guestEmail = strtolower($_POST['guest-email']);

		$rsvp->guestFirstName = $guestFirstName;
		$rsvp->guestLastName = $guestLastName;
		$rsvp->guestEmail = $guestEmail;
		$rsvp->hasGuest = true;
  }

  // Put form info into array to pass into SQL Connect function
  if ($rsvpType === "open") {
  	if ($submit) {
      //call Database Connect Function;
      dbInsert($rsvp);
    }
  } elseif ($rsvpType === "match" || $rsvpType === "capacity") {
		$gender = '';
		$category = '';
		$company = '';
		$guestOf = '';

		if ($rsvp->checkEmail($email) === true) {
			$rsvp->gender = $gender;
			$rsvp->category = $category;
		 	$rsvp->company = $company;
		 	$rsvp->guestOf = $guestOf;

    	//call Database Connect Function;
    	newInsert($rsvp);
		} else {
			dbUnknwnr($rsvp);
		}
	}// end of rsvpType = Match
;?>
</div>

</body>
</html>
