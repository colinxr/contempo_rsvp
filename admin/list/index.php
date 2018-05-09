<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

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
      <p>The current RSVP Type is set to <strong><?php echo RSVP_TYPE; ?></strong></p>

      <form id="js-match-type">
        <select name="rsvp_types" id="rsvp_types">
          <option value="">Select RSVP Type</option>
          <option value="Match">Match</option>
          <option value="Open">Open</option>
          <option value="Capacity">Capacity</option>
          <option value="Closed">Closed</option>
        </select>
        <button type="submit" name="submit">Submit</button>
      </form>
    </div>

    <div class="">
      <h3>Create Partner Landing Page</h3>
      <p>To create a new Partner Landing Page, simply enter the brand's name in the field below and hit submit.</p>
      <p>A new landing page will be created at <?php echo BASE_URL; ?>/your-partner-name.</p>

      <form id="js-partner-rsvp">
        <label for="partner-name">Partner Name</label>
        <input type="text" id="js-partner-name" name="partner-name">
        <button type="submit" name="submit">Submit</button>
      </form>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="<?php BASE_URL ?>/dist/all.js"></script>
</body>
</html>
