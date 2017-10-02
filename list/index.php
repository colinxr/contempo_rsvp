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
			<img src="../imgs/sharp_logo_black.svg" class="navbar-brand">
		</div>
		<ul class="nav navbar-nav navbar-right">
			<li><a href="<?php echo BASEPATH; ?>">Landing Page</a></li>
			<li><a href="<?php echo BASEPATH . '/unknwnr'; ?>">Unknown RSVPs</a></li>
		</ul>
	</div>
</header>

<div class="container">

<header>
  <h1>S/Volume & BFM F/W 2017 RSVPs</h1>

  <h3>Here are the RSVPs and Plus Ones.</h3>

  <section>
    <div>
      <div class="form-group">
        <div class="col-md-4">
          <a href="http://contempomedia.ca/test/unknwnr/rsvps/download.php" download> <button type="submit" name="export" class="btn btn-success" value="export RSVPs"/>Export</button></a>
        </div>
      </div>
    </div>
  </section>
</header>

<?php
  require_once("../_inc/config.php");
  require("../src/database.php");

  $dbTable = DB_TABLE;

  viewResults($dbTable);

?>

</div>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
	<!--<script type="text/javascript" src="../js/build/production.min.js"></script> -->


</body>

</html>
