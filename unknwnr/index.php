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
      <a href="/rsvps">See RSVPs here</a>
    </section>
	</div>

		<?php
			require("../_inc/config.php");

			// Create connection
			$conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

      // Check connection
			if ( $conn->connect_error ) {
				die ("Connection failed: " . $conn->connect_error );
			}

			// Check if there are any unknown RSVPs
			$result = $conn->query( "SELECT * FROM " . UNKNWNR );

				if ( $result === false ) {
					echo "Error";
				} else if( $result->num_rows > 0 ) { // Display unknown RSVPS ?>

					<table class='table table-striped' id='rsvp-table'>
						<thead>
							<tr>
							    <th>ID</th>
							    <th>First Name</th>
							    <th>Last Name</th>
							    <th>Email</th>
							    <th>Postal</th>
							    <th>Guest Name</th>
							    <th>Guest Email</th>
							    <th>Approve/Deny</th>
					        </tr>
						</thead>
						<tbody>

					<? $counter = 0;
						while( $data = mysqli_fetch_array( $result ) ){
							$counter++;
							echo "<tr id='". $counter . "'>";

							echo "<td>" . $counter . "</td>";
							echo "<td id='firstName' class='value'>" . $data['firstName'] . "</td>";
							echo "<td id='lastName' class='value'>" . $data['lastName'] . "</td>";
							echo "<td id='email' class='value'>" . $data['email'] . "</td>";
							echo "<td id='postal' class='value'>" . $data['postal'] . "</td>";
							echo "<td id='guestName' class='value'>" . $data['guestName'] . "</td>";
							echo "<td id='guestEmail' class='value'>" . $data['guestEmail'] . "</td>";

							echo "<td><input type='button' id='". $counter ."' class='btn btn-link approve' value='Approve' />";

              echo "<input type='button' class='btn btn-link deny' value='Deny' /></td>";

							echo "</tr>";
						}

						echo "</tbody></table>";
					} else {// If table has no unknown RSVPs display a message
						echo "No unknown RSVPs right now. Check back later.";
					}
		;?>
	</div>

	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="js/build/production.js"></script>
</body>
</html>
