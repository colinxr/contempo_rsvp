(function($) {
  $(document).ready(function() {

    if ($('#js-match-type').length) {
      var form = $('#js-match-type');

      form.submit(function(e) {
        e.preventDefault();

        var value    = $('#rsvp_types').val();
        var inputs   = form.find('select, button');
        var data     = { rsvpType : value };
        var rsvpType = JSON.stringify(data);
        var url      = '/admin/list/rsvp-type.php';

        inputs.prop('disabled', true);

        ajax_post(rsvpType, url);

        function ajax_post(rsvpType) {
      		$.ajax({
      	  	url: url,
      	    method: 'POST',
      	    data: {'data' : rsvpType},
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
