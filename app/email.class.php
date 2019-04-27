<?php
  require_once(__DIR__ . '/../config/config.php');
  require_once(__DIR__ . '/../vendor/autoload.php');

  use Postmark\PostmarkClient;

  class Email {
    public function sendConfirmation($rsvp) {
      $subject = '=?UTF-8?B?'.base64_encode(utf8_encode(SUBJECT_LINE)).'?=';
      // $html_body = file_get_contents(BASEPATH . '/_inc/emails/email.html');

      $body = '
        <html>
          <head>
            <title>Your ' . EVENT_NAME . ' RSVP Confirmation</title>
          </head>
          <body>
            <p>Hi '. $rsvp->getFirstName() .',</p>
            <p>Thank you for your RSVP to ' . EVENT_NAME . '.</p>
            <p>If you would like to make changes to your RSVP please email <a href="mailto:' . EMAIL_FROM . '">' . EMAIL_FROM . '</a>.</p>
            <p>The event details are here:</p>
            <p>Wednesday, April 25, 2018
            <br/>6:30PM - 10:30PM
            <br/>11 Polson St.
            <br/>Toronto, ON
            </p>

            <br />
            <p>Best,
            <br/>Michael La Fave
            <br/>Editorial and Creative Director / Co-founder
            <br/>Contempo Media Inc.
            <br/>Sharp Magazine – Sharp: The Book for Men – S/magazine
            <br/>SharpMagazine.com
            <br/>SMagazineofficial.com
          </body>
          </html>';

      $html_body = utf8_encode($body);

      $message = [
        'To'       => $rsvp->getEmail(),
        'From'     => EMAIL_FROM,
        'Subject'  => $subject,
        'HtmlBody' => $body
      ];

      $client = new PostmarkClient(POSTMARK_API);

      // Send an email:
      $sendResult = $client->sendEmailBatch([$message]);
    }

    public function sendStaffEmail($rsvp){
      $subject = '=?UTF-8?B?'.base64_encode(utf8_encode(STAFF_SUBJECT)).'?=';

      $html_body = '
    		<html>
    		<head>
    		  <title>Who is this person?</title>
    		</head>
    		<body>
    			<p>Privet Elena!</p>
    		  <p>Somebody who isn\'t on the invite list just RSVP\'d for the BFM Party. This is their info:</p><br/>

    		      <p>Name: '. $rsvp->getFirstName() . ' ' . $rsvp->getLastName() .' </p>
             <p>Email: '. $rsvp->getEmail() .'</p>
            <p>Postal: '. $rsvp->getPostal() .'</p>
          <p>Plus One: '. $rsvp->getGuestFirstName() .' ' . $rsvp->getGuestLastName() .'</p>

    		</body>
    		</html>';

      $message = [
        'To'       => EMAIL_FROM,
        'From'     => STAFF_EMAIL_FROM,
        'Subject'  => $subject,
        'HtmlBody' => $html_body
      ];

      $client = new PostmarkClient(POSTMARK_API);
      $sendResult = $client->sendEmailBatch([$message]);
    }

    public function sendRejection($rsvp){//PostMark Email API
      $subject = '=?UTF-8?B?'.base64_encode(utf8_encode(EVENT_NAME . ' RSVP Status')).'?=';
      //$html_body = file_get_contents(BASEPATH . '/_inc/emails/email.html');

      $body = '
        <html>
        <head>
          <title>Your ' . EVENT_NAME . ' RSVP</title>
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
        'To'       => $rsvp->getEmail(),
        'From'     => EMAIL_FROM,
        'Subject'  => $subject,
        'HtmlBody' => $body
      ];
      // var_dump($rsvp->getEmail());
      $client = new PostmarkClient(POSTMARK_API);
      $sendResult = $client->sendEmailBatch([$message]);
    }
  }
?>
