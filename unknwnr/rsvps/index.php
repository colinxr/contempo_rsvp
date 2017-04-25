<!DOCTYPE>
<html lang="en">
<head>
  <meta charset="utf-8">

	<title>Unknown RSVP Confirmations</title>

	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' type='text/css' media='all' />
	<link rel='stylesheet' href='../css/bootstrap-responsive.min.css' type='text/css' media='all' />

	<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">



</head>

<body>

<header class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<img src="../imgs/s-.svg" class="navbar-brand">
		</div>
		<ul class="nav navbar-nav navbar-right">
			<li><a href="http://www.contempomedia.com/sharp/bfm">Landing Page</a></li>
			<li><a href="../">Unknown RSVPs</a></li>
		</ul>
	</div>
</header>

<div class="container">

<header>
  <h1>Book For Men Spring/Summer 2017 RSVPs</h1>

  <h3>Here are the RSVPs and Plus Ones.</h3>

  <!--<section>

    <a href="download.php" class="btn">Download RSVPs</a>

  </section> -->
</header>

<?php

		include("../../_inc/config.php");


			// Create connection

				$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

			// Check connection

				if ( $conn->connect_error ) {
				    die ("Connection failed: " . $conn->connect_error );
				}

			// CHECK IF EMAIL ALREADY IN DB
				$result = $conn->query( "SELECT * FROM " . DB_TABLE );

					if ( $result === false ) {

						echo "Error";

				} else if( $result->num_rows > 0 ) {

					echo "<table class='table table-striped' id='rsvp-table'>
						    <thead>
						      <tr>
						      	<th>ID</th>
						        <th>First Name</th>
						        <th>Last Name</th>
						        <th>Email</th>
						        <th>Guest Name</th>
						        <th>Guest Email</th>
						      </tr>
						    </thead>
						    <tbody>";

				$counter = 0;

				while( $data = mysqli_fetch_array( $result ) ){

					$counter++;

					echo "<tr>";

					echo "<td>" . $counter . "</td>";
					echo "<td>" . $data['firstName'] . "</td>";
					echo "<td>" . $data['lastName'] . "</td>";
					echo "<td>" . $data['email'] . "</td>";
					echo "<td>" . $data['guestName'] . "</td>";
					echo "<td>" . $data['guestEmail'] . "</td>";

					echo "</tr>";

				}

				echo "</tbody></table>";

			} else {
				echo "No one has RSVP'd yet. Check back in a little while. Or maybe something's gone wrong. I don't know.";
			}


;?>

</div>


	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
	<!--<script type="text/javascript" src="../js/build/production.min.js"></script> -->


</body>

</html>
