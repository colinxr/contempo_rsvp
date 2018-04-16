<div class="confirmation">
  <p><strong>Great! Thank you for your RSVP, <?php printf($obj->getFirstName()); ?>! </strong></p>
      <p>We've added you to the guest list and you will be receiving your confirmation email shortly.</p><br/>

   <!--  Calendar Event Info
   <span class="addtocalendar atc-style-blue">
    <var class="atc_event">
      <var class="atc_date_start">05/25/2018 19:00:00</var>
      <var class="atc_date_end">05/25/2018 23:00:00</var>
      <var class="atc_timezone">America/Toronto</var>
      <var class="atc_title">Sharp The Book For Men SS18 Toronto Launch Event </var>
      <var class="atc_description">Celebrate the Launch of Sharp: The Book For Men with Sharp Magazine, presented by Genesis</var>
      <var class="atc_location">11 Polson St</var>
      <var class="atc_organizer">Sharp Magazine</var>
      <var class="atc_organizer_email">event@sharpmagazine.com</var>
    </var>
  </span>-->

  <!-- Button code -->
  <div title="Add to Calendar" class="addeventatc">
    Add to Calendar
    <span class="start">05/02/2018 18:30:00</span>
    <span class="end">05/02/2018 22:30:00</span>
    <span class="timezone">America/Toronto</span>
    <span class="title">Sharp The Book For Men SS18 Vancouver Launch Event</span>
    <!-- <span class="description">Celebrate the Launch of Sharp: The Book For Men with Sharp Magazine, presented by Genesis</span> -->
    <span class="location">11 Polson St</span>
    <span class="organizer_email">event@sharpmagazine.com</span>
    <span class="alarm_reminder">1440</span>
  </div>

  <div class='confirmation__footer'>
    <img src='<?php echo BASE_URL; ?>/imgs/sharp_logo_black.svg'>
  </div>


  <!-- script for add to calendar -->
  <!-- <script type="text/javascript">(function () {
  	if (window.addtocalendar)if(typeof window.addtocalendar.start == "function")return;
  	if (window.ifaddtocalendar == undefined) { window.ifaddtocalendar = 1;
  	    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
  	    s.type = 'text/javascript';s.charset = 'UTF-8';s.async = true;
  	    s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addtocalendar.com/atc/1.5/atc.min.js';
  	    var h = d[g]('body')[0];h.appendChild(s); }})();
  </script> -->
  <script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>

</div>
