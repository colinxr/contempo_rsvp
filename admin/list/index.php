<?php
  require('../../config/config.php');

  include('../header.php');
?>

  <div class="container">
    <header>
      <h1><?php echo EVENT_NAME; ?> RSVPs</h1>
      <h3>Upload the event invite list</h3>

      <p>Please note, only upload CSV Files with a maxmium of 1MB.</p>
    </header>

    <form enctype="multipart/form-data" action="upload.php" method="POST">
      <p>Upload your file</p>
      <input type="file" name="fileToUpload"></input><br />
      <input type="submit" name="submit" value="Upload"></input>
    </form>

  </div>

  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
  <!--<script type="text/javascript" src="../js/build/production.min.js"></script> -->
  </body>
</html>
