(function($) {
  $(document).ready(function() {

    if ($('#js-match-type').length) {
      var form = $('#js-match-type');

      form.submit(function(e) {
        e.preventDefault();

        // if (request) request.abort();

        var value = $('#rsvp_types').val();

        var inputs = form.find('select, button');
        var data = {
          rsvpType : value,

        };
        var rsvpType = JSON.stringify(data);

        inputs.prop('disabled', true);

        ajax_post(rsvpType);

        function ajax_post(rsvpType) {
      		$.ajax({
      	  	url: '/admin/list/rsvp-type.php',
      	    method: 'POST',
      	    data: {'setting' : rsvpType},
      	    success: function(resp, textStatus, xhr){
      	    	console.log(textStatus);
              alert(resp);
              setTimeout(function() {
                location.reload();  //Refresh page
              }, 1500);
      	    },
      	    error: function(xhr, textStatus, errorThrown) {
      		  	console.log('ajax loading error...');
      				console.log(xhr.responseText);
      				console.log(textStatus);
      		    	return false;
      	    },
            // always: function() {
            //   inputs.prop('disabled', false);
            // }
      		});
      	}


      });
    }


  });
})(jQuery);
