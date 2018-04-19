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

    <div>
      <h3>Set RSVP Type</h3>
      <p>The current RSVP Type is set to <?php echo RSVP_TYPE ?></p>

      <form enctype="multipart/form-data" action="rsvp-type.php" method="POST">
        <select name="rsvp_types" id="rsvp_types">
          <option value="">Select RSVP Type</option>
          <option value="Match">Match</option>
          <option value="Open">Open</option>
          <option value="Capacity">Capacity</option>
          <option value="Closed">Closed</option>
        </select>
        <button type="submit" name="submit" >Submit</button>
      </form>
    </div>

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.map"></script>
  <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
  <!--<script type="text/javascript" src="../js/build/production.min.js"></script> -->
  </body>
</html>
