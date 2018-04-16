<?php
  require_once(__DIR__ . '/../config/config.php');
  include('header.php');
?>

  <div class="container">
    <header>
      <h1><?php echo EVENT_NAME; ?> RSVPs</h1>
      <h3>Here are the RSVPs and Plus Ones.</h3>

        <div class="form-group">
          <h4>Download this List</h4>
          <form method='post' action='download.php'>
            <input type='submit' value='Export' name='Export'>
          </form>
        </div>
          <?php
            $admin = new Admin();
            $admin->countRsvps();
            $admin->countPlusOnes();
          ?>
        <div>

        </div>
    </header>

    <?php
      // $admin = new Admin();
      $admin->viewResults(DB_TABLE);
    ?>

  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.map"></script>
	<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
	<!--<script type="text/javascript" src="../js/build/production.min.js"></script> -->
</body>
</html>
