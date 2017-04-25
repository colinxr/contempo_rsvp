	<?php
	// ** MySQL settings - You can get this info from your web host ** //

	//** local MAMP database info

	define( 'DB_USER', '****' );

	define( 'DB_PASSWORD', '****' );

	define( 'DB_HOST', '****' );

	define( 'DB_NAME', '****' );

	/** Database Charset to use in creating database tables. */
	define('DB_CHARSET', 'utf8');

	/** The Database Collate type. Don't change this if in doubt. */
	define('DB_COLLATE', '');


	// ** Event Tables - Changes for Specific Events, must match up with create-rsvp_**-table.sql &&  create-unknown_**-table.sql

	define( 'DB_TABLE', '****');

	define( 'UNKNWNR', '****');



	// ** Event Hosts
	define('EVENT_HOSTS', "Sharp Magazine");

	define('EVENT_NAME', "Sharp Magazine's 10th Anniversary Event");



	// ** Email confirmation settings
	define('EMAIL_FROM', "event@sharpmagazine.com"); 	// confirmation from email address

	define('STAFF_EMAIL_FROM', "event@sharpmagazine.com"); // Staff email from address

	define('SUBJECT_LINE', "Your Sharp Magazine 10th Anniversary RSVP Confirmation");

	define('STAFF_SUBJECT', "BFM SS17: Unknown RSVP");





	// ** set type of RSVP

	//**  "Match" => "Email matches master lists",
	//**   "Open" => "No email check",
	//** Capacity => "After submit, page says we're at capacity, pushes email to unknown database",
	//**   Closed => "No Form"

	$rsvpType = "Match";

		 ; ?>
