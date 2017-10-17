<?php
  error_reporting(E_ALL);
  ini_set('display_errors',1);
?>
<!DOCTYPE>
<html lang="en">
<head>
  <meta charset="utf-8">

	<title>BFM SS17 Unknown RSVP Confirmations</title>

	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' type='text/css' media='all' />
	<link rel='stylesheet' href='css/bootstrap-responsive.min.css' type='text/css' media='all' />
  <link ref='stylesheet' href="css/style.css" type='text/css' media='all' />
	<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />

  <style>
  .oldRow {color: #999;}
  </style>

</head>

<body>
	<header class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
					<img src="http://www.contempomedia.ca/sharp/bfm/imgs/sharp_logo_black.svg" class="navbar-brand">
				</div>
		</div>
	</header>

	<div class="container">
		<div class="alert alert-success fade in" role="alert" style="display: none;">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  	<strong>Cool!</strong> You did it. An email has been sent which they will get shortly.
		</div>

	<div class="title">
		<h1>Book For Men Spring/Summer 2017 Unknown RSVPs</h1>
		<h3>Here are the Unknown RSVPs</h3>
    <section>
      <a href="/test/unknwnr/rsvps">See RSVPs here</a>
    </section>
	</div>

		<?php
			require('../config/config.php');
      require('../src/database.php');
      require('../src/select.php');

      $dbTable = UNKNWNR;

		  viewResults($dbTable);
    ?>
      </div>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="js/build/production.js"></script>
</body>
</html>
