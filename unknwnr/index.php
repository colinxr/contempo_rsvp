<?php
  require('../config/config.php');
?>
<!DOCTYPE>
<html lang="en">
<head>
  <meta charset="utf-8">

	<title><?php echo EVENT_NAME;?> Unknown RSVP Confirmations</title>

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
		<h1><?php echo EVENT_NAME;?> Unknown RSVPs</h1>

    <section>
      <div>
        <div class="form-group">
          <div class="col-md-4">
            <h4>Download this List</h4>
            <form method='post' action='download.php'>
            <input type='submit' value='Export' name='Export'>
            </form>

          </div>
        </div>
      </div>
    </section>
	</div>

		<?php
      require('../src/database.php');

      $dbTable = UNKNWNR;

		  viewResults($dbTable);
    ?>
      </div>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="js/build/production.js"></script>
</body>
</html>
