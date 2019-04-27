<?php
	require_once(__DIR__ . '/../vendor/autoload.php');
	require_once(__DIR__ . '/../app/classes.php');

	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define( 'DB_NAME', 'db751061181' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASS', 'root' );
	define( 'DB_HOST', 'localhost' );
	define( 'DB_HOST_SLAVE', 'localhost' );
	/** Database Charset to use in creating database tables. */
	define( 'DB_CHARSET', 'utf8');
	/** The Database Collate type. Don't change this if in doubt. */
	define( 'DB_COLLATE', '');

	// ** Event Tables - Changes for Specific Events, must match up with create-rsvp_**-table.sql &&  create-unknown_**-table.sql
	define( 'ADMIN_TABLE', 'rsvp_test_Admin' );
	define( 'DB_TABLE', 'rsvp_test' );
	define( 'UNKNWNR', 'unknown_test' );

	define( 'BASE_URL', '/contempo_rsvp' );
	define( 'BASEPATH', $_SERVER['DOCUMENT_ROOT'] . BASE_URL );

	// ** Event info
	// define( 'EVENT_THEME', 'Theme One');
	define( 'EVENT_HOSTS', 'Sharp Magazine' );
	define( 'EVENT_NAME', 'Sharp: The Book for Men Launch Event' );

	define( 'POSTMARK_API', '476f4535-b4e6-45cb-bf0b-5e0852c83965' );
	define( 'EMAIL_FROM', 'event@sharpmagazine.com' ); 	// confirmation from email address
	define( 'STAFF_EMAIL_FROM', 'colinxr@gmail.com' ); // Staff email from address
	define( 'SUBJECT_LINE', 'Your Sharp: The Book for Men Launch Event Confirmation' );
	define( 'STAFF_SUBJECT', 'Colin\'s Test: Unknown RSVP' );

	//** set type of RSVP
	//** "Match" => "Email matches master lists",
	//** "Open" => "No email check",
	//** Capacity => "After submit, page says we're at capacity, pushes email to unknown database",
	//** Closed => "Form is Hidden and message saying we're full is displayed"

	$admin = new Admin();
	define( 'RSVP_TYPE', $admin->fetch_admin_setting('RSVP_TYPE') );
	define( 'EVENT_THEME', $admin->fetch_admin_setting('EVENT_THEME') );
	define( 'PARTNER_RSVP', $admin->fetch_admin_setting('PARTNER_RSVP') );

	?>
