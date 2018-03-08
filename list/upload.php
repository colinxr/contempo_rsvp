<?php
  require_once('../config/config.php');
  require('../src/database.php');

  ?>
<!DOCTYPE>
<html lang="en">
  <head>
    <meta charset="utf-8">

  	<title>Event RSVP Confirmations</title>

  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" type="text/css" media="all" />
  	<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" type="text/css" media="all" />

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
          <li><a href="<?php echo BASEPATH . '/list/invtes.php'; ?>">Upload Invite List</a></li>
    		</ul>
    	</div>
    </header>

    <div class="container">
      <header>
        <h1><?php echo EVENT_NAME; ?> RSVPs</h1>

        <?php
          upload_list();
        ?>
      </header>
    </div>
  </body>
</html>
