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
        <p>If you would like to make changes to your RSVP please email <a href="mailto:'. EMAIL_FROM .'">' . EMAIL_FROM . '</a>.</p>
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

function rejectEmailPm( $email ) {//PostMark Email API

  $subject = '=?UTF-8?B?'.base64_encode(utf8_encode(SUBJECT_LINE)).'?=';

  //$html_body = file_get_contents( BASEPATH . '/_inc/emails/email.html' );

  $body = '
    <html>
    <head>
      <title></title>
    </head>
    <body>
      <p>Unfortunately we have a strict guest list policy, and only those invited are given access to our event.</p>
      <p>Thank you for your understanding.</p>
      </br>
      <p>Contempo Media

    </body>
    </html>
  ';

  $html_body = utf8_encode($body);

  $message = [
    'To' => $email,
    'From' => EMAIL_FROM,
    'Subject' => $subject,
    'HtmlBody' => $html_body
  ];

  $client = new PostmarkClient( CLIENT_API );

  // Send an email:
  $sendResult = $client->sendEmailBatch([$message]);
}
