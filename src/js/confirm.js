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
			instagram  : activeRow.find('td:eq(5)').text(),
			guestName  : activeRow.find('td:eq(6)').text(),
		  	guestEmail : activeRow.find('td:eq(7)').text()
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
		alertBox.find('span').text('You did it. They will receive an email shortly.');
	});
	// Function to sends JSON data object to process-data.php to submit to database.
	$('.js-remove-entry').on('click', function() {
		$(this).parent().parent().attr('id', 'activeRow');

		var activeRow = $('#activeRow');
		var alertBox = $('.alert');
		var data = {
			firstName  : activeRow.find('td:eq(1)').text(),
			lastName   : activeRow.find('td:eq(2)').text(),
			email 	   : activeRow.find('td:eq(3)').text(),
			postal     : activeRow.find('td:eq(4)').text(),
			guestName  : activeRow.find('td:eq(5)').text(),
		  guestEmail : activeRow.find('td:eq(6)').text()
		};

		data.action = 'remove';

		confirm('You want to remove' + data.firstName + ' ' + data.lastName + ' ' + 'from the unknown RSVPs?');

		var json = JSON.stringify(data);

		ajax_delete(json);

		activeRow.remove();

		alertBox.fadeIn('fast').fadeOut(2500);
		alertBox.find('span').text(data.firstName + ' ' + data.lastName + ' ' + 'has been removed from the RSVPs');
	});

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

	function ajax_delete(json) {
		$.ajax({
	  	url: 'remove_unknown.php',
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
