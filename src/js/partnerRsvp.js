(function($) {
  $(document).ready(function() {

    if ($('#js-partner-rsvp').length) {
      var form = $('#js-partner-rsvp');

      form.submit(function(e) {
        e.preventDefault();

        var value    = $('#js-partner-name').val();
        var inputs   = form.find('input, button');
        var data     = { partnerName : value };
        var partner = JSON.stringify(data);
        var url      = 'partner-rsvp.php';

        inputs.prop('disabled', true);

        ajax_post(partner, url);

        function ajax_post(partner) {
      		$.ajax({
      	  	url: url,
      	    method: 'POST',
      	    data: {'data' : partner},
      		})
          .done(function(resp, textStatus, xhr) {
            console.log(textStatus);
            alert(resp);
            setTimeout(function() {
              location.reload();  //Refresh page
            }, 250);
          })
          .fail(function(xhr, textStatus, errorThrown) {
            console.log('ajax loading error...');
            console.log(xhr.responseText);
            console.log(textStatus);
              return false;
          })
          .always(function() {
            inputs.prop('disabled', false);
          });
      	}
      });
    }
  });
})(jQuery);
