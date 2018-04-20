<?php
  require_once('../../config/config.php');
  include('../header.php');
?>

	<div class="container">
		<div class="alert alert-success fade in" role="alert" style="display: none;">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  	<strong>Cool!</strong> You did it. An email has been sent which they will get shortly.
		</div>

	<div class="title">
		<h1><?php echo EVENT_NAME;?> Unknown RSVPs</h1>

    <div class="form-group">
      <h4>Download this List</h4>
      <form method='post' action='download.php'>
        <input type='submit' value='Export' name='Export'>
      </form>
    </div>
	</div>

	<?php
    $admin = new Admin();
	  $admin->viewResults(UNKNWNR);
  ?>

  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/dist/all.js"></script>
</body>
</html>
