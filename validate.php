<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE>
<html>
<head>
	<title><?php
	include '_inc/config.php';
	echo EVENT_NAME;
 	?> RSVP</title>
	<meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="theme-color" content="#4a4a4a">
	<link rel="icon" href="imgs/favicon.png" type="image/x-icon" />

	<link rel='stylesheet' href='css/normalize.css' type='text/css' media='all' />
	<link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
	<link href="http://addtocalendar.com/atc/1.5/atc-style-blue.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>

<div class="container">
<div class="wrapper">
	<div class="col left info">
		<?php require '_inc/event-info.php'; ?>
	</div>

		<div class="col right confirmation">
<?php
  require_once '_inc/config.php';
       require 'src/functions.php';

  //Get form info
      $email = strtolower($_POST['email']); //formants data to match with Email Lower column in wtf.csv
  $firstName = ucwords(strtolower($_POST['first-name'])); // Formats data for any stray capitals in user form
   $lastName = ucwords(strtolower($_POST['last-name'])); // Formats data for any stray capitals in user form
     $postal = strtoupper($_POST['postal']); // Formats data proper Postal Code form
     $submit = $_POST['submit'];

  if ( isset($_POST['plus-one']) ) { // handles plus one inputs, used to set sql query
    $hasGuest = true;
  } else {
    $hasGuest = false;
  }

  if ($hasGuest) {
    $guestName = ucwords(strtolower($_POST['guest-name']));// Formats data for any stray capitals in user form
   $guestEmail = $_POST['guest-email'];
  }

  // Put form info into array to pass into SQL Connect function
  if ($rsvpType = "open") {
    $row = 1;
    $emailMatch = true;

    $emailLower = strtolower($email);

    $sqlArgs = array (
          "email" => $email,
      "firstName" => $firstName,
       "lastName" => $lastName,
         "postal" => $postal
      );

      if ($hasGuest) {
        $sqlArgs["hasGuest"] = $hasGuest;
        $sqlArgs["guestName"] = $guestName;
        $sqlArgs["guestEmail"] = $guestEmail;
      }

      if ($emailMatch) {
        //call Database Connect Function;
        dbConnect ( $sqlArgs );
      }

    } elseif ($rsvpType = "Match") {

    //Check email and compare to list, if match, grab ancillary information
    $row = 1;
    $emailMatch = false;

    // convert email string to all lowercase to make sure variable capitalization doesn't miss the email in wtf.csv
    $emailLower = strtolower($email);

    if (($handle = fopen("wtf.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1500, ",")) !== FALSE) {
        $row++;
        if ($data[3] == $emailLower) {

            $gender = $data[4];
          $category = $data[5];
           $company = $data[6];
           $guestOf = $data[7];

        $emailMatch = true;
        }
      }
      fclose($handle);
    }

    if ($emailMatch) {
      $sqlArgs["gender"] = $gender;
    $sqlArgs["category"] = $category;
     $sqlArgs["company"] = $company;
     $sqlArgs["guestOf"] = $guestOf;

      //call Database Connect Function;
    	dbConnect ( $sqlArgs );
    } else { /* If $emailMatch is false */
      dbUnknwr( $sqlArgs );
      // call Unknown Databse Conntect function
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
