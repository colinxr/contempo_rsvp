<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
?>
<!DOCTYPE>
<html lang="en">
<head>
  <meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<title><?php echo EVENT_NAME;?> Unknown RSVP Confirmations</title>

	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' type='text/css' media='all' />
	<link rel='stylesheet' href=<?php echo BASE_URL; ?>'/dist/css/bootstrap-responsive.min.css' type='text/css' media='all' />
  <link ref='stylesheet' href="<?php echo BASE_URL; ?>/dist/css/main.css" type='text/css' media='all' />
	<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />

  <style>
    .oldRow {color: #999;}
  </style>
</head>

<body>
	<header class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
					<img src="<?php echo BASE_URL; ?>/imgs/sharp_logo_black.svg" class="navbar-brand">
				</div>
        <ul class="nav navbar-nav navbar-right">
    			<li><a href="<?php echo BASE_URL; ?>">Landing Page</a></li>
          <li><a href="<?php echo BASE_URL; ?>/admin/">RSVPs</a></li>
    			<li><a href="<?php echo BASE_URL; ?>/admin/unknown">Unknown RSVPs</a></li>
          <li><a href="<?php echo BASE_URL; ?>/admin/list/">Upload Invite List</a></li>
    		</ul>
		</div>
	</header>
