/*!
 * classie - class helper functions
 * from bonzo https://github.com/ded/bonzo
 * 
 * classie.has( elem, 'my-class' ) -> true/false
 * classie.add( elem, 'my-new-class' )
 * classie.remove( elem, 'my-unwanted-class' )
 * classie.toggle( elem, 'my-class' )
 */

/*jshint browser: true, strict: true, undef: true */
/*global define: false */

( function( window ) {

'use strict';

// class helper functions from bonzo https://github.com/ded/bonzo

function classReg( className ) {
  return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
}

// classList support for class management
// altho to be fair, the api sucks because it won't accept multiple classes at once
var hasClass, addClass, removeClass;

if ( 'classList' in document.documentElement ) {
  hasClass = function( elem, c ) {
    return elem.classList.contains( c );
  };
  addClass = function( elem, c ) {
    elem.classList.add( c );
  };
  removeClass = function( elem, c ) {
    elem.classList.remove( c );
  };
}
else {
  hasClass = function( elem, c ) {
    return classReg( c ).test( elem.className );
  };
  addClass = function( elem, c ) {
    if ( !hasClass( elem, c ) ) {
      elem.className = elem.className + ' ' + c;
    }
  };
  removeClass = function( elem, c ) {
    elem.className = elem.className.replace( classReg( c ), ' ' );
  };
}

function toggleClass( elem, c ) {
  var fn = hasClass( elem, c ) ? removeClass : addClass;
  fn( elem, c );
}

var classie = {
  // full names
  hasClass: hasClass,
  addClass: addClass,
  removeClass: removeClass,
  toggleClass: toggleClass,
  // short names
  has: hasClass,
  add: addClass,
  remove: removeClass,
  toggle: toggleClass
};

// transport
if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( classie );
} else {
  // browser global
  window.classie = classie;
}

})( window );

///////
// Unknwnr approval
///////
(function($) {
$(document).ready(function() {
	//approve click function
	$('.btn-link').on('click', function() {

		//Add the ID 'activeRow' to the TR which holds the button clicked
		$(this).parent().parent().attr('id', 'activeRow');

		var activeRow = $('#activeRow');
		 var alertBox = $('.alert');

		//Creates the data object that holds the Unknown RSVP data from activeRow's TDs
		var data = {
			firstName  : activeRow.find('td:eq(1)').text(),
			lastName   : activeRow.find('td:eq(2)').text(),
			email 	   : activeRow.find('td:eq(3)').text(),
			postal     : activeRow.find('td:eq(4)').text(),
			guestName  : activeRow.find('td:eq(5)').text(),
		  guestEmail : activeRow.find('td:eq(6)').text()
		};

	  //check if users clicked Approve or Deny
	  if ($(this).is('.btn.approve')){
			data.action = 'approve';
	    confirm('You want to approve ' + data.firstName + ' ' + data.lastName + '?' );
	    // alert('Person Approved');
		} else {
			data.action = 'delete';
			confirm('You want to reject ' + data.firstName + ' ' + data.lastName + '?' );
			// alert('Person Denied');
		}

		//converts var data to JSON object to submit through AJAX to PHP
		var json = JSON.stringify(data);

		//submits var data JSON object through ajax_post
		ajax_post(json);

		//removes the completed entry from table list
		activeRow.remove();

		// Alert Notice Handler
		alertBox.fadeIn('fast').fadeOut(2500);
	});
	// Function to sends JSON data object to process-data.php to submit to database.
	function ajax_post(json) {
		$.ajax({
	  	url: 'process-data.php',
	    method: 'POST',
	    data: {'rsvp' : json},
	    success: function(data){
	    	console.log(data);
	    },
	    error: function(xhr, textStatus, errorThrown) {
		  	console.log('ajax loading error...');
				console.log(xhr.responseText);
				console.log(textStatus);
		    	return false;
	    }
		});
	}
}); // end of document ready function
})(jQuery);

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
        var url      = '/admin/list/partner-rsvp.php';

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

///////
// RSVP Form Validation
///////
(function($) {
	$(document).ready(function() {
		var column = $('.info');
		// var columnHeight = column[0].scrollHeight
		var email = getQueryVariable('email');

		// resizeColumn();
		//
		// var resizeTimeout;
		// $(window).resize(function(e) {
		// 	clearTimeout(resizeTimeout);
		//
		// 	resizeTimeout = setTimeout(function() {
		// 		console.log('resize');
		// 		resizeColumn();
		// 	}, 250);
	  // });

		if (email) {
			$('#email').val(email);
		}

		$('#plus-one').click(function() {
			$('.guests').fadeToggle('fast','swing');
			$('input[name="guest-name"]').attr("required", true);
			$('input[name="guest-email"]').attr("required", true);
		});

		$('form').submit(function(e) {
			submitForm();
	  });

		function resizeColumn() {
			var columnHeight = column[0].scrollHeight;
			var vpHeight = $(window).height();

			if (columnHeight > vpHeight && !column.hasClass('smaller')) {
				column.addClass('smaller');
			} else if (columnHeight < vpHeight && column.hasClass('smaller')){
				column.removeClass('smaller');
			}
		}

		function getQueryVariable(variable) {
		  var query = window.location.search.substring(1);
		  var vars = query.split('&');
		  for (var i=0;i<vars.length;i++) {
		     var pair = vars[i].split('=');
		     if(pair[0] == variable){
		     	return pair[1];
		     }
		  }
		  return(false);
		}

		function isValid() {
			var email = $('#email').val();
			var name = $('#name').val();
			var postal = $('#postal').val();
			if($('#plus-one').is(':checked')) {
				var guestName = $('#guest-name').val();
				var guestEmail = $('#guest-email').val();
			}

			// DO VALIDATION
			return true;
		}

		function submitForm() {
			if (isValid()) {
				$('#js-rsvp-form').submit();
			}
		}
	});
})(jQuery);

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
