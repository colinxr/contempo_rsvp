<?php
	require_once(__DIR__ . '/../vendor/autoload.php');
	require_once(__DIR__ . '/../app/classes.php');

	$dotenv = new Dotenv\Dotenv(__DIR__);
	$dotenv->load();

	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define( 'DB_NAME', $_ENV['DB_NAME'] );
	define( 'DB_USER', $_ENV['DB_USER'] );
	define( 'DB_PASS', $_ENV['DB_PASS'] );
	define( 'DB_HOST', $_ENV['DB_HOST'] );
	define( 'DB_HOST_SLAVE', 'localhost' );
	/** Database Charset to use in creating database tables. */
	define( 'DB_CHARSET', 'utf8');
	/** The Database Collate type. Don't change this if in doubt. */
	define( 'DB_COLLATE', '');

	// ** Event Tables - Changes for Specific Events, must match up with create-rsvp_**-table.sql &&  create-unknown_**-table.sql
	define( 'ADMIN_TABLE', 'rsvp_test_Admin' );
	define( 'DB_TABLE', 'rsvp_test' );
	define( 'UNKNWNR', 'unknown_test' );

	define( 'BASE_URL', $_ENV['BASE_URL'] );
	define( 'BASEPATH', $_SERVER['DOCUMENT_ROOT'] . BASE_URL );

	// ** Event Hosts
	define( 'EVENT_HOSTS', 'Colin Rabyniuk' );
	define( 'EVENT_NAME', 'Colin\'s Test' );

	// ** Email confirmation settings
	define( 'MAILCHIMP_API', $_ENV['MAILCHIMP_API'] );
	define( 'MAILCHIMP_LIST_ID', $_ENV['MAILCHIMP_LIST_ID'] );

	define( 'POSTMARK_API', $_ENV['POSTMARK_API'] );
	define( 'EMAIL_FROM', 'event@sharpmagazine.com' ); 	// confirmation from email address
	define( 'STAFF_EMAIL_FROM', 'colinxr@gmail.com' ); // Staff email from address
	define( 'SUBJECT_LINE', 'Colin\'s Test Confirmation' );
	define( 'STAFF_SUBJECT', 'Colin\'s Test: Unknown RSVP' );

	//** set type of RSVP
	//** "Match" => "Email matches master lists",
	//** "Open" => "No email check",
	//** Capacity => "After submit, page says we're at capacity, pushes email to unknown database",
	//** Closed => "Form is Hidden and message saying we're full is displayed"

	$admin = new Admin();
	define( 'RSVP_TYPE', $admin->fetch_admin_setting('RSVP_TYPE') );
	define( 'PARTNER_RSVP', $admin->fetch_admin_setting('PARTNER_RSVP') );

	?>
