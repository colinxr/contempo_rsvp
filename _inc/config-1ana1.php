	<?php
	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define( 'DB_NAME', 'db398471106' );

	define( 'DB_USER', 'dbo398471106' );

	define( 'DB_PASSWORD', 'o\0$6*\mes[,XjEL#1' );

	define( 'DB_HOST', 'db398471106.db.1and1.com' );

	define( 'DB_HOST_SLAVE', 'db398471106.db.1and1.com' );

	//** local MAMP database info

	/*define( 'DB_USER', 'root' );

	define( 'DB_PASSWORD', 'root' );

	define( 'DB_HOST', 'localhost' );

	define( 'DB_NAME', 'RSVP' );*/

	/** Database Charset to use in creating database tables. */
	define('DB_CHARSET', 'utf8');

	/** The Database Collate type. Don't change this if in doubt. */
	define('DB_COLLATE', '');

	// ** Event Hosts
	define('EVENT_HOSTS', "Sharp Magazine");

	define('EVENT_NAME', "Sharp Magazine's 10th Anniversary Event");

	// ** Email confirmation settings
	define('EMAIL_FROM', "event@sharpmagazine.com"); 	// confirmation from email address

	define('STAFF_EMAIL_FROM', "event@sharpmagazine.com"); // Staff email from address

	define( 'DB_TABLE', 'rsvp_test');

	define( 'UNKNWNR', 'unknown_test');

	// ** set type of RSVP

	//**  "Match" => "Email matches master lists",
	//**   "Open" => "No email check",
	//** Capacity => "After submit, page says we're at capacity, pushes email to unknown database",
	//**   Closed => "No Form"

	$rsvpType = "Match";

		 ; ?>
