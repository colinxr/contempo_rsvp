<?php require_once 'header.php' ;?>
<body>

<div class="container">
<div class="wrapper">
	<div class="col left info">
		<?php require '_inc/event-info.php'; ?>
	</div>

	<div class="col right confirmation">
<?php
  require_once '_inc/config.php';
       require 'src/database.php';

  //Get form info
      $email = strtolower( $_POST['email'] ); //formants data to match with Email Lower column in wtf.csv
  $firstName = ucwords(strtolower( $_POST['first-name'] ) ); // Formats data for any stray capitals in user form
   $lastName = ucwords(strtolower( $_POST['last-name'] ) ); // Formats data for any stray capitals in user form
     $postal = strtoupper( $_POST['postal'] ); // Formats data proper Postal Code form
     $submit = $_POST['submit'];

  if ( isset( $_POST['plus-one'] ) ) { // handles plus one inputs, used to set sql query
    $hasGuest = true;
  } else {
    $hasGuest = false;
  }

  if ( $hasGuest ) {
    $guestFirstName = ucwords( strtolower( $_POST['guest-firstName'] ) );// Formats data for any stray capitals in user form
		$guestLastName = ucwords( strtolower( $_POST['guest-lastName'] ) );// Formats data for any stray capitals in user form
   $guestEmail = $_POST['guest-email'];
  }

	$sqlArgs = array (
				"email" => $email,
		"firstName" => $firstName,
		 "lastName" => $lastName,
			 "postal" => $postal
		);

	if ( $hasGuest ) {
		$sqlArgs["hasGuest"] = $hasGuest;
		$sqlArgs["guestFirstName"] = $guestFirstName;
		$sqlArgs["guestLastName"] = $guestLastName;
		$sqlArgs["guestEmail"] = $guestEmail;
	}

  // Put form info into array to pass into SQL Connect function
  if ( $rsvpType === "open" ) {
    $emailLower = strtolower( $email );

  	if ( $submit ) {
      //call Database Connect Function;
      dbInsert ( $sqlArgs );
    }
  } elseif ( $rsvpType === "match" || $rsvpType === "capacity" ) {

		$gender = '';
		$category = '';
		$company = '';
		$guestOf = '';

		if ( checkEmail($email) ) {
			$sqlArgs["gender"] = $gender;
		$sqlArgs["category"] = $category;
		 $sqlArgs["company"] = $company;
		 $sqlArgs["guestOf"] = $guestOf;

    	//call Database Connect Function;
    	dbInsert ( $sqlArgs );
		} else {
			dbUnknwnr( $sqlArgs );
		}
	}// end of rsvpType = Match
;?>
</div>

<!-- script for add to calendar -->
<script type="text/javascript">(function () {
	if (window.addtocalendar)if(typeof window.addtocalendar.start == "function")return;
	if (window.ifaddtocalendar == undefined) { window.ifaddtocalendar = 1;
	    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
	    s.type = 'text/javascript';s.charset = 'UTF-8';s.async = true;
	    s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addtocalendar.com/atc/1.5/atc.min.js';
	    var h = d[g]('body')[0];h.appendChild(s); }})();
</script>

</body>
</html>
