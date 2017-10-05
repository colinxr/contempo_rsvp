<?php

require_once( BASEPATH . '/vendor/autoload.php');
use Postmark\PostmarkClient;

function sendConfirmPm( $emailArgs ) {//PostMark Email API

  $subject = '=?UTF-8?B?'.base64_encode(utf8_encode(SUBJECT_LINE)).'?=';

  //$html_body = file_get_contents( BASEPATH . '/_inc/emails/email.html' );

  $body = '
  <html>
    <head>
      <title>Your Land Rover Event RSVP Confirmation</title>
    </head>
    <body>
      <p>Hi '. $emailArgs["firstName"] .',</p>
        <p>Thank you for your RSVP to our Land Rover event.</p>
        <p>If you would like to make changes to your RSVP please email <a href="mailto:event@sharpmagazine.com">event@sharpmagazine.com</a>.</p>
      <br />
      <p>Best,
      <br/>Peter Saltsman
      <br/>Editor In Chief
      <br/>Sharp Magazine, Sharp: The Book For Men
    </body>
    </html>
  ';

  $html_body = utf8_encode($body);


  $message = [
    'To' => $emailArgs['email'],
    'From' => EMAIL_FROM,
    'Subject' => $subject,
    'HtmlBody' => $html_body
  ];

  $client = new PostmarkClient( CLIENT_API );

  // Send an email:
  $sendResult = $client->sendEmailBatch([$message]);
}

function sendStaffPM( $staffArgs ) {//PostMark Email API
  $subject = '=?UTF-8?B?'.base64_encode(utf8_encode(STAFF_SUBJECT)).'?=';

  $html_body = '
		<html>
		<head>
		  <title>Who is this person?</title>
		</head>
		<body>
			<p>Privet Elena!</p>
		    <p>Somebody who isn\'t on the invite list just RSVP\'d for the BFM Party. This is their info:</p><br/>

		  	          <p>Name: '. $staffArgs["firstName"] . ' ' . $staffArgs["lastName"] .' </p>
		  	         <p>Email: '. $staffArgs["email"] .'</p>
		  	        <p>Postal: '. $staffArgs["postal"] .'</p>
		  	      <p>Plus One: '. $staffArgs["guestFirstName"] .' ' . $staffArgs["guestLastName"] .'</p>

		</body>
		</html>
	';

  $message = [
    'To' => EMAIL_FROM,
    'From' => STAFF_EMAIL_FROM,
    'Subject' => $subject,
    'HtmlBody' => $html_body
  ];

  $client = new PostmarkClient( CLIENT_API );

  // Send an email:
  $sendResult = $client->sendEmailBatch([$message]);
}

function sendEmail( $emailArgs ) {
  $subject = '=?UTF-8?B?'.base64_encode(utf8_encode(SUBJECT_LINE)).'?=';

  //$message = file_get_contents( __DIR__ . '/email.html' );
  $message = '
  <html>
    <head>
      <title>Your VIP Launch Event RSVP Confirmation - Sharp: The Book for Men & S/Volume</title>
    </head>
    <body>
      <p>Hi '. $emailArgs["firstName"] .',</p>
        <p>Thank you for your RSVP to our VIP launch event.</p>
        <p>If you would like to make changes to your RSVP please email <a href="mailto:event@sharpmagazine.com">event@sharpmagazine.com</a>.</p>
      <br />
      <p>Best,
      <p>Michael La Fave
      <p>Editorial & Creative Director
      <p>Contempo Media
    </body>
    </html>
  ';

  $message_encode = utf8_encode($message);
  $message_wrap = wordwrap($message_encode, 70, "\r\n");

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To: ' . $emailArgs["firstName"] .' '. $emailArgs["lastName"] .' <' . $emailArgs["email"] . '>' . "\r\n";
	$headers .= 'From: ' . EVENT_HOSTS . '<' . EMAIL_FROM . '>' . "\r\n";

	// Mail it
	mail( $emailArgs["email"], $subject, $message_wrap, $headers);

} // end of sendEmail();

function sendStaffEmail( $staffArgs ) {

	//$mailTo = 'event@sharpmagazine.com';
  $mailTo = 'colin.rabyniuk@contempomedia.com';

	$subject = STAFF_SUBJECT;

	$message = '
		<html>
		<head>
		  <title>Who is this person?</title>
		</head>
		<body>
			<p>Privet Elena!</p>
		    <p>Somebody who isn\'t on the invite list just RSVP\'d for the BFM Party. This is their info:</p><br/>

		  	          <p>Name: '. $staffArgs["firstName"] . ' ' . $staffArgs["lastName"] .' </p>
		  	         <p>Email: '. $staffArgs["email"] .'</p>
		  	        <p>Postal: '. $staffArgs["postal"] .'</p>
		  	      <p>Plus One: '. $staffArgs["guestFirstName"] .' ' . $staffArgs["guestLastName"] .'</p>

		</body>
		</html>
	';

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers

	//$headers .= 'To: Elena Okulova <event@sharpmagazine.com>' . "\r\n";
  $headers .= 'To: Colin Rabyniuk <colin.rabyniuk@contempomedia.com>' . "\r\n";
	$headers .= 'From: Contempo RSVP Bot <' . STAFF_EMAIL_FROM . '>' . "\r\n";

	// Mail it
	mail( $mailTo, $subject, $message, $headers );

} // End of sendStaffEmail();

function rejectEmail( $email, $firstName, $lastName ) {

  $subject_line = SUBJECT_LINE;
  $subject = '=?UTF-8?B?'.base64_encode(utf8_encode($subject_line)).'?=';

  $message =
  "<html>
  <head>
    <title></title>
  </head>
  <body>
    <p>Unfortunately we have a strict guest list policy, and only those invited are given access to our the Sharp: The Book For Men and S/Volume Fall/Winter 2017 VIP launch event.</p>
    <p>Thank you for your understanding.</p>
    </br>
    <p>Contempo Media

  </body>
  </html>";

  $message_encode = utf8_encode($message);
  $message_wrap = wordwrap($message_encode, 70, "\r\n");

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To: ' . $firstName .' '. $lastName .' <' . $email . '>' . "\r\n";
	$headers .= 'From: ' . EVENT_HOSTS . '<' . EMAIL_FROM . '>' . "\r\n";

	// Mail it
	mail($email, $subject, $message_wrap, $headers);
}
