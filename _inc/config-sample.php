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
	define( 'DB_TABLE', 'rsvp_test');
	define( 'UNKNWNR', 'unkonwn_test');

	define( 'BASEPATH', $_SERVER[DOCUMENT_ROOT] .'/test' );

	// ** Event Hosts
	define('EVENT_HOSTS', "Colin Rabyniuk");
	define('EVENT_NAME', "Colin's Test");

	// ** Email confirmation settings
	define( 'CLIENT_API', '*********' ); //refer to postmark account
	define('EMAIL_FROM', 'colinxr@gmail.com'); 	// confirmation from email address
	define('STAFF_EMAIL_FROM', "colinxr@gmail.com"); // Staff email from address
	define('SUBJECT_LINE', "Colin's Test Confirmation");
	define('STAFF_SUBJECT', "Colin's Test: Unknown RSVP");

	//** set type of RSVP
	//** "Match" => "Email matches master lists",
	//** "Open" => "No email check",
	//** Capacity => "After submit, page says we're at capacity, pushes email to unknown database",
	//** Closed => "No Form"

	$rsvpType = "Match";

	; ?>
