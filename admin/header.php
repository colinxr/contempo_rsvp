<!DOCTYPE>
<html lang="en">
<head>
  <meta charset="utf-8">

	<title><?php echo EVENT_NAME;?> Unknown RSVP Confirmations</title>

	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' type='text/css' media='all' />
	<link rel='stylesheet' href='../../css/bootstrap-responsive.min.css' type='text/css' media='all' />
  <link ref='stylesheet' href="../../css/main.css" type='text/css' media='all' />
	<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />

  <style>
    .oldRow {color: #999;}
  </style>
</head>

<body>
	<header class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
					<img src="../../imgs/sharp_logo_black.svg" class="navbar-brand">
				</div>
        <ul class="nav navbar-nav navbar-right">
    			<li><a href="/">Landing Page</a></li>
    			<li><a href="/admin/unknown">Unknown RSVPs</a></li>
          <li><a href="/admin/list/">Upload Invite List</a></li>
    		</ul>
		</div>
	</header>
