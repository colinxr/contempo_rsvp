<div class="confirmation">
  <p><strong>Great! Thank you for your RSVP, <?php printf($obj->getFirstName()); ?>! </strong></p>
      <p>We've added you to the guest list and you will be receiving your confirmation email shortly.</p><br/>

  <!-- Button code -->
  <div title="Add to Calendar" class="addeventatc">
    Add to Calendar
    <span class="start"></span>
    <span class="end"></span>
    <span class="timezone">America/Toronto</span>
    <span class="title"></span>
    <!-- <span class="description">Celebrate the Launch of Sharp: The Book For Men with Sharp Magazine, presented by Genesis</span> -->
    <span class="location"></span>
    <span class="organizer_email"><?php echo EMAIL_FROM ?></span>
    <span class="alarm_reminder"></span>
  </div>

  <div class='confirmation__footer'>
    <img src='<?php echo BASE_URL; ?>/imgs/sharp_logo_black.svg'>
  </div>

  <script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>

</div>
